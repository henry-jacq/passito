<?php

namespace App\Services;

use DateTime;
use App\Core\View;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Student;
use App\Enum\OutpassType;
use App\Enum\OutpassStatus;
use App\Entity\OutpassRequest;
use Doctrine\ORM\EntityManagerInterface;
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

    /**
     * Generate outpass document and return the file path
     */
    public function generateOutpassDocument(OutpassRequest $outpass): string
    {
        if (!$outpass instanceof OutpassRequest) {
            throw new \Exception('Invalid outpass request provided.');
        }

        // Student and outpass details
        $html = $this->view->renderEmail('outpass/document', [
            'outpass' => $outpass,
            'student' => $outpass->getStudent(),
        ]);

        // Set up Dompdf options
        $options = new Options();
        $options->set('isPhpEnabled', true); // Enable PHP (Optional)
        $options->set('isRemoteEnabled', true); // Enable remote assets like images

        // Initialize Dompdf
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF
        $output = $dompdf->output();

        // Generate unique file name
        $retry = 0;
        $maxRetries = 5;
        $outpassName = substr(md5(microtime(true) . $outpass->getId() . random_int(1000, 9999)), 0, 16) . '.pdf';
        $pdfPath = getStoragePath("outpasses/{$outpassName}", true);

        // Retry logic if file already exists
        while (file_exists($pdfPath) && $retry < $maxRetries) {
            $retry++;
            error_log("Retrying file creation for outpass ID {$outpass->getId()}. Attempt {$retry}.");
            $outpassName = substr(md5(microtime(true) . $outpass->getId() . random_int(1000, 9999)), 0, 16) . '.pdf';
            $pdfPath = getStoragePath("outpasses/{$outpassName}", true);
        }

        if (file_exists($pdfPath)) {
            throw new \Exception('Failed to generate unique file name for outpass document after retries.');
        }

        // Save the PDF to a file
        file_put_contents($pdfPath, $output);

        return $pdfPath;
    }

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
    public function checkAndExpireOutpass(): void
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

        foreach ($outpasses as $outpass) {
            // Mark as expired
            $outpass->setStatus(OutpassStatus::EXPIRED);

            // Remove the document and update the outpass record
            $this->removeOutpassDocument($outpass);
            $outpass->setDocument(null);

            // Persist changes (batching handled later)
            $this->em->persist($outpass);
        }

        // Persist all changes in one operation
        $this->em->flush();
    }
}
