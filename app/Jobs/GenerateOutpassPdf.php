<?php

namespace App\Jobs;

use App\Core\View;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Core\Storage;
use App\Entity\OutpassRequest;
use App\Interfaces\JobInterface;
use App\Services\OutpassService;
use Doctrine\ORM\EntityManagerInterface;

class GenerateOutpassPdf implements JobInterface
{
    public function __construct(
        private readonly View $view,
        private readonly Storage $storage,
        private readonly EntityManagerInterface $em,
        private readonly OutpassService $outpassService
    ) {}

    public function handle(array $payload)
    {
        try {
            if (empty($payload['directory']) || empty($payload['prefix']) || empty($payload['outpass_id'])) {
                throw new \InvalidArgumentException(
                    'Invalid payload for GenerateOutpassPdf ' . json_encode($payload)
                );
            }

            // Dependency QR Code
            $qrCodeFile = null;
            if (!empty($payload['dependencies'])) {
                foreach ($payload['dependencies'] as $dep) {
                    if (!empty($dep['qrCodePath'])) {
                        $qrCodeFile = basename($dep['qrCodePath']);
                        break;
                    }
                }
            }

            // Fetch Outpass
            try {
                $outpass = $this->outpassService->getOutpassById($payload['outpass_id']);

                if (!$outpass instanceof OutpassRequest) {
                    throw new \RuntimeException("Outpass not found with ID: " . $payload['outpass_id']);
                }
            } catch (\Throwable $e) {
                throw new \RuntimeException("Failed fetching outpass: " . $e->getMessage(), 0, $e);
            }

            // Render HTML
            try {
                $html = $this->view->renderEmail('outpass/document', [
                    'outpass' => $outpass,
                    'qrCodeFile' => $qrCodeFile
                ]);
            } catch (\Throwable $e) {
                throw new \RuntimeException("Failed rendering outpass HTML: " . $e->getMessage(), 0, $e);
            }

            // Generate PDF
            try {
                $options = new Options();
                $options->set('isRemoteEnabled', true);
                $options->set('chroot', realpath(STORAGE_PATH));

                $dompdf = new Dompdf($options);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                $output = $dompdf->output();
            } catch (\Throwable $e) {
                throw new \RuntimeException("PDF generation failed: " . $e->getMessage(), 0, $e);
            }

            // Save PDF
            try {
                $pdfPath = $this->storage->generateFileName(
                    $payload['directory'],
                    'pdf',
                    $payload['prefix'] ?? 'document_'
                );
                $this->storage->write($pdfPath, $output);
            } catch (\Throwable $e) {
                throw new \RuntimeException("Failed saving PDF to storage: " . $e->getMessage(), 0, $e);
            }

            // Persist Outpass
            try {
                $outpass->setDocument(basename($pdfPath));
                $this->em->persist($outpass);
                $this->em->flush();
            } catch (\Throwable $e) {
                throw new \RuntimeException("Failed persisting Outpass entity: " . $e->getMessage(), 0, $e);
            }

            return ['pdfPath' => $pdfPath];
        } catch (\Throwable $e) {
            error_log("[GenerateOutpassPdf] " . $e->getMessage() . "\n" . $e->getTraceAsString());
            throw $e; // rethrow so job system sees it
        }
    }
}
