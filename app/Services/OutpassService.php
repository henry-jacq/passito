<?php

namespace App\Services;

use DateTime;
use App\Core\View;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Student;
use App\Enum\OutpassType;
use Endroid\QrCode\QrCode;
use App\Enum\OutpassStatus;
use App\Entity\OutpassRequest;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\RoundBlockSizeMode;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\ErrorCorrectionLevel;
use Doctrine\ORM\Tools\Pagination\Paginator;

class OutpassService
{
    public function __construct(
        private readonly View $view,
        private readonly EntityManagerInterface $em
    )
    {
    }
    
    public function createOutpass(array $data): OutpassRequest
    {
        $outpass = new OutpassRequest();
        $outpass->setStudent($data['student']);
        $outpass->setFromDate($data['from_date']);
        $outpass->setToDate($data['to_date']);
        $outpass->setFromTime($data['from_time']);
        $outpass->setToTime($data['to_time']);
        $outpass->setPassType(OutpassType::from($data['outpass_type']));
        $outpass->setStatus(OutpassStatus::PENDING);
        $outpass->setDestination($data['destination']);
        $outpass->setPurpose($data['purpose']);
        $outpass->setCreatedAt(new DateTime());
        // $outpass->setAttachments($data['attachments']);

        return $this->updateOutpass($outpass);
    }

    public function getPendingOutpass()
    {
        $outpasses = $this->em->getRepository(OutpassRequest::class)->findBy(
            ['status' => OutpassStatus::PENDING]
        );

        return $outpasses;
    }

    public function getRecentStudentOutpass(Student $student, int $limit = 5)
    {
        $outpasses = $this->em->getRepository(OutpassRequest::class)->findBy(
            ['student' => $student],
            ['createdAt' => 'DESC'],
            $limit
        );

        return $outpasses;
    }
    
    public function getOutpassByStudent(Student $student)
    {
        $outpasses = $this->em->getRepository(OutpassRequest::class)->findBy(
            ['student' => $student]
        );

        return $outpasses;
    }

    public function getOutpassRecords(int $page = 1, int $limit = 10)
    {
        $offset = ($page - 1) * $limit;

        // Convert OutpassStatus enum to scalar values (e.g., string or integer)
        $statuses = [
            OutpassStatus::APPROVED->value,
            OutpassStatus::EXPIRED->value,
        ];

        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder->select('o')
            ->from(OutpassRequest::class, 'o')
            ->where($queryBuilder->expr()->in('o.status', $statuses))
            ->orderBy('o.createdAt', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $query = $queryBuilder->getQuery();
        $paginator = new Paginator($query, true);

        return [
            'data' => iterator_to_array($paginator),
            'total' => count($paginator),
            'currentPage' => $page,
            'totalPages' => ceil(count($paginator) / $limit),
        ];
    }

    public function getOutpassById(int $id): ?OutpassRequest
    {
        return $this->em->getRepository(OutpassRequest::class)->find($id);
    }

    public function updateOutpass(OutpassRequest $outpass)
    {
        $this->em->persist($outpass);
        $this->em->flush();

        return $outpass;
    }

    private function generateUniqueFileName(string $directory, string $extension): string
    {
        $retry = 0;
        $maxRetries = 5;
        do {
            $fileName = substr(md5(microtime(true) . random_int(1000, 9999)), 0, 16) . ".{$extension}";
            $filePath = getStoragePath("{$directory}/{$fileName}", true);
            $retry++;
        } while (file_exists($filePath) && $retry < $maxRetries);

        if (file_exists($filePath)) {
            throw new \Exception("Failed to generate unique file name after {$maxRetries} retries.");
        }

        return $filePath;
    }

    /**
     * Generate a QR code for the given data
     */
    public function generateQRCode(string $data, int $size = 300, int $margin = 10): string
    {
        try {
            $qrCode = new QrCode(
                $data, // Data to encode in the QR code
                new Encoding('UTF-8'), // Encoding
                ErrorCorrectionLevel::High, // Error correction level
                $size, // Size
                $margin, // Margin
                RoundBlockSizeMode::Margin, // Round block size mode
                new Color(0, 0, 0), // Foreground color (black)
                new Color(255, 255, 255) // Background color (white)
            );

            // Generate the QR code image data
            $writer = new PngWriter();
            $imageData = $writer->write($qrCode)->getString();

            // Generate unique file name with path
            $qrCodePath = $this->generateUniqueFileName('qr_codes', 'png');

            file_put_contents($qrCodePath, $imageData);

            return $qrCodePath;
    
        } catch (\Exception $e) {
            // Handle exceptions
            error_log('QR Code generation failed: ' . $e->getMessage());
            echo 'QR Code generation failed: ' . $e->getMessage();
        }
    }

    /**
     * Generate outpass document and return the file path
     */
    public function generateOutpassDocument(OutpassRequest $outpass): string
    {
        // Render Outpass document HTML
        $html = $this->view->renderEmail('outpass/document', [
            'outpass' => $outpass,
            'student' => $outpass->getStudent(),
        ]);

        // Change the working directory to storage path
        $options = new Options();
        $options->set('chroot', realpath(STORAGE_PATH));

        // Initialize Dompdf
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF
        $output = $dompdf->output();

        // Generate unique file name with path
        $pdfPath = $this->generateUniqueFileName('outpasses', 'pdf');

        // Save the PDF to a file
        file_put_contents($pdfPath, $output);

        return $pdfPath;
    }

    /**
     * Remove the QR code file from storage
     */
    public function removeQrCode(OutpassRequest $outpass)
    {
        $qrCode = $outpass->getQrCode();
        $qrCodePath = getStoragePath("qr_codes/{$qrCode}");
        if (file_exists($qrCodePath)) {
            try {
                unlink($qrCodePath);
            } catch (\Exception $e) {
                error_log('Failed to delete QR code: ' . $qrCodePath . ' Error: ' . $e->getMessage());
            }
        }
    }

    /**
     * Remove the outpass document file from storage
     */
    public function removeOutpassDocument(OutpassRequest $outpass)
    {
        $document = $outpass->getDocument();
        if (!empty($document)) {
            $pdfPath = getStoragePath("outpasses/{$document}");
            if (file_exists($pdfPath)) {
                try {
                    unlink($pdfPath);
                } catch (\Exception $e) {
                    error_log('Failed to delete file: ' . $pdfPath . ' Error: ' . $e->getMessage());
                }
            }
        }
    }

    /**
     * Check and expire outpasses that have passed their expiry date
     */
    public function checkAndExpireOutpass(int $batchSize = 20): void
    {
        $now = new \DateTimeImmutable();

        // Fetch all approved outpasses in one query
        // NOTE: Ensure the CONCAT function is supported by the database
        $outpasses = $this->em->getRepository(OutpassRequest::class)
        ->createQueryBuilder('o')
        ->where('o.status = :status')
        ->andWhere("CONCAT(o.toDate, ' ', o.toTime) <= :now")
        ->setParameter('status', OutpassStatus::APPROVED->value)
        ->setParameter('now', $now->format('Y-m-d H:i:s'))
        ->getQuery()
        ->getResult();

        $count = 0;

        foreach ($outpasses as $outpass) {
            // Mark as expired
            $outpass->setStatus(OutpassStatus::EXPIRED);

            // Remove the document and update the outpass record
            $this->removeQrCode($outpass);
            $this->removeOutpassDocument($outpass);

            // Clear the document and QR code
            $outpass->setQrCode(null);
            $outpass->setDocument(null);

            // Persist changes
            $this->em->persist($outpass);

            // Flush and clear the EntityManager in batches
            if (($count % $batchSize) === 0) {
                $this->em->flush();

                // Detach only the current entity
                $this->em->detach($outpass);
            }
            $count++;
        }

        // Final flush
        $this->em->flush();
    }
}
