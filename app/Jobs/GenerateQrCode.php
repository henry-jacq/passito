<?php

namespace App\Jobs;

use App\Core\Storage;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Color\Color;
use App\Interfaces\JobInterface;
use App\Services\OutpassService;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\ErrorCorrectionLevel;

class GenerateQrCode implements JobInterface
{
    public function __construct(
        private readonly Storage $storage,
        private readonly OutpassService $outpassService
    ) {}

    public function handle(array $payload)
    {
        try {
            if (
                empty($payload['directory'])
                || empty($payload['qr_data'])
                || empty($payload['qr_secret'])
                || empty($payload['margin'])
                || empty($payload['size'])
            ) {
                throw new \InvalidArgumentException('Invalid payload for GenerateQrJob ' . json_encode($payload));
            }
            
            // Encrypt the QR data with the provided secret
            $data = $this->outpassService->encryptQrData(
                json_encode($payload['qr_data']),
                $payload['qr_secret']
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

            // Send qrCodePath to dependent jobs
            return ['qrCodePath' => $qrCodePath];

        } catch (\Exception $e) {
            error_log('QR Code generation failed: ' . $e->getMessage());
        }
    }
}
