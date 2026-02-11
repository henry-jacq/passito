<?php

namespace App\Jobs;

use App\Core\Config;
use App\Core\Storage;
use App\Core\View;
use App\Interfaces\JobInterface;
use App\Services\MailService;
use App\Services\OutpassService;
use App\Services\FileService;
use App\Enum\ResourceType;
use App\Enum\ResourceVisibility;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

class ProcessApprovedOutpass implements JobInterface
{
    public function __construct(
        private readonly Config $config,
        private readonly Storage $storage,
        private readonly View $view,
        private readonly MailService $mailService,
        private readonly EntityManagerInterface $em,
        private readonly OutpassService $outpassService,
        private readonly FileService $fileService
    ) {}

    public function handle(array $payload): void
    {
        try {
            $outpassId = $payload['outpass_id'] ?? null;
            $emailTo = $payload['email_to'] ?? $payload['to'] ?? null;
            $emailSubject = $payload['email_subject'] ?? $payload['subject'] ?? null;
            $emailTemplate = $payload['email_template'] ?? 'outpass/accepted';

            if (empty($outpassId) || empty($emailTo) || empty($emailSubject)) {
                throw new \InvalidArgumentException(
                    'Invalid payload for ProcessApprovedOutpass ' . json_encode($payload)
                );
            }

            $qrDirectory = $payload['qr_directory'] ?? 'qr_codes';
            $qrPrefix = $payload['qr_prefix'] ?? 'qrcode';
            $qrMargin = (int) ($payload['qr_margin'] ?? 10);
            $qrSize = (int) ($payload['qr_size'] ?? 300);

            $pdfDirectory = $payload['pdf_directory'] ?? 'outpasses';
            $pdfPrefix = $payload['pdf_prefix'] ?? 'document';

            // Fetch outpass
            $outpass = $this->outpassService->getOutpassById($outpassId);
            if (!$outpass) {
                throw new \RuntimeException("Outpass not found with ID: {$outpassId}");
            }

            // Build QR data
            $qrData = [
                'id' => $outpass->getId(),
                'student' => $outpass->getStudent()->getUser()->getEmail(),
                'type' => $outpass->getTemplate()->getName(),
            ];

            $secretKey = $this->config->get('app.qr_secret');
            $encrypted = $this->outpassService->encryptQrData(json_encode($qrData), $secretKey);

            // Generate QR code image
            $qrCode = new QrCode(
                $encrypted,
                new Encoding('UTF-8'),
                ErrorCorrectionLevel::High,
                $qrSize,
                $qrMargin,
                RoundBlockSizeMode::Margin,
                new Color(0, 0, 0),
                new Color(255, 255, 255)
            );

            $writer = new PngWriter();
            $qrImageData = $writer->write($qrCode)->getString();
            $qrCodePath = $this->storage->generateFileName($qrDirectory, 'png', $qrPrefix);
            $this->storage->write($qrCodePath, $qrImageData);

            // Render outpass PDF (uses generated QR code)
            $html = $this->view->renderEmail('outpass/document', [
                'outpass' => $outpass,
                'qrCodeFile' => basename($qrCodePath),
            ]);

            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('chroot', realpath(STORAGE_PATH));

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $pdfOutput = $dompdf->output();

            $pdfPath = $this->storage->generateFileName($pdfDirectory, 'pdf', $pdfPrefix);
            $this->storage->write($pdfPath, $pdfOutput);

            // Persist outpass updates
            $studentUser = $outpass->getStudent()->getUser();
            $qrFile = $this->fileService->registerStoredFile(
                $qrCodePath,
                "outpass_{$outpassId}_qr.png",
                'image/png',
                strlen($qrImageData),
                ResourceType::OUTPASS_QR,
                $studentUser,
                $outpassId,
                ResourceVisibility::OWNER
            );
            $pdfFile = $this->fileService->registerStoredFile(
                $pdfPath,
                "outpass_{$outpassId}.pdf",
                'application/pdf',
                strlen($pdfOutput),
                ResourceType::OUTPASS_DOCUMENT,
                $studentUser,
                $outpassId,
                ResourceVisibility::OWNER
            );

            $outpass->setQrCode($qrFile->getUuid());
            $outpass->setDocument($pdfFile->getUuid());
            $this->em->persist($outpass);
            $this->em->flush();

            // Render and send email with attachments
            $body = $this->view->renderEmail($emailTemplate, ['outpass' => $outpass]);
            $this->mailService->notify(
                $emailTo,
                $emailSubject,
                $body,
                true,
                [$pdfPath, $qrCodePath]
            );
        } catch (\Throwable $e) {
            error_log("[ProcessApprovedOutpass] " . $e->getMessage() . "\n" . $e->getTraceAsString());
            throw $e;
        }
    }
}
