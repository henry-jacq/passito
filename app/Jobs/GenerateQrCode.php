<?php

namespace App\Jobs;

use App\Core\Config;
use App\Core\Storage;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Color\Color;
use App\Interfaces\JobInterface;
use App\Services\OutpassService;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\RoundBlockSizeMode;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\ErrorCorrectionLevel;

class GenerateQrCode implements JobInterface
{
    public function __construct(
        private readonly Config $config,
        private readonly Storage $storage,
        private readonly EntityManagerInterface $em,
        private readonly OutpassService $outpassService
    ) {}

    public function handle(array $payload)
    {
        try {            
            if (
                empty($payload['directory'])
                || empty($payload['prefix'])
                || empty($payload['margin'])
                || empty($payload['size'])
                || empty($payload['outpass_id'])
            ) {
                throw new \InvalidArgumentException('Invalid payload for GenerateQrJob ' . json_encode($payload));
            }
            
            // Fetch outpass details
            $outpass = $this->outpassService->getOutpassById($payload['outpass_id']);
            
            $qr_data = [
                'id'      => $outpass->getId(),
                'student' => $outpass->getStudent()->getUser()->getEmail(),
                'type'    => $outpass->getTemplate()->getName(),
            ];

            // Fetch secret key from config
            $secretKey = $this->config->get('app.qr_secret');

            // Encrypt the QR data with the provided secret
            $data = $this->outpassService->encryptQrData(
                json_encode($qr_data),
                $secretKey
            );

            $qrCode = new QrCode(
                $data, // Data to encode in the QR code
                new Encoding('UTF-8'), // Encoding
                ErrorCorrectionLevel::High, // Error correction level
                $payload['size'], // Size
                $payload['margin'], // Margin
                RoundBlockSizeMode::Margin, // Round block size mode
                new Color(0, 0, 0), // Foreground color (black)
                new Color(255, 255, 255) // Background color (white)
            );

            // Generate the QR code image data
            $writer = new PngWriter();
            $imageData = $writer->write($qrCode)->getString();

            // Generate unique file name with path
            $qrCodePath = $this->storage->generateFileName($payload['directory'], 'png', $payload['prefix'] ?? 'qrcode_');

            // Save the QR code image
            $this->storage->write($qrCodePath, $imageData);

            $outpass->setQrCode(basename($qrCodePath));
            $this->em->persist($outpass);
            $this->em->flush();

            // Send qrCodePath to dependent jobs
            return ['qrCodePath' => $qrCodePath];

        } catch (\Exception $e) {
            error_log('QR Code generation failed: ' . $e->getMessage());
        }
    }
}
