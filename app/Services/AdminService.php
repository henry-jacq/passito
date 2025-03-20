<?php

namespace App\Services;

use App\Core\View;
use App\Core\Storage;
use App\Enum\OutpassStatus;
use App\Entity\OutpassRequest;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class AdminService
{
    public function __construct(
        private readonly View $view,
        private readonly Storage $storage,
        private readonly MailService $mail,
        private readonly OutpassService $outpass,
        private readonly VerifierService $verifierService,
        private readonly EntityManagerInterface $em
    )
    {
    }

    public function getDashboardDetails(): array
    {
        $pendingCount = count($this->outpass->getPendingOutpass());
        $approvedCount = count($this->outpass->getApprovedOutpass());
        $expiredCount = count($this->outpass->getExpiredOutpass());
        $rejectedCount = count($this->outpass->getRejectedOutpass());
        $checkedOutCount = count($this->verifierService->fetchCheckedOutLogs());

        return [
            'pending' => $pendingCount,
            'approved' => $approvedCount + $expiredCount,
            'rejected' => $rejectedCount,
            'checkedOut' => $checkedOutCount
        ];
    }

    public function approveAllPending(User $approvedBy)
    {
        $pendingPass = $this->outpass->getPendingOutpass();
        foreach ($pendingPass as $pending) {
            $this->approvePending($pending, $approvedBy);
        }
    }

    public function approvePending(OutpassRequest $outpass, $approvedBy): OutpassRequest|bool
    {
        $outpass->setStatus(OutpassStatus::APPROVED);
        $outpass->setRemarks(null);
        $outpass->setApprovedTime($time = new \DateTime());
        $outpass->setApprovedBy($approvedBy);

        $accepted = $this->view->renderEmail('outpass/accepted', [
            'studentName' => $outpass->getStudent()->getUser()->getName(),
            'outpass' => $outpass,
        ]);

        $userEmail = $outpass->getStudent()->getUser()->getEmail();
        $subject = "Your Outpass Request #{$outpass->getId()} Has Been Approved";

        $qrData = [
            'id' => $outpass->getId(),
            'type' => $outpass->getPassType()->value,
            'student' => $userEmail,
        ];

        // Generate QR code and outpass document
        $qrCodePath = $this->outpass->generateQRCode($qrData);
        $outpass->setQrCode(basename($qrCodePath));

        $documentPath = $this->outpass->generateOutpassDocument($outpass);
        $outpass->setDocument(basename($documentPath));

        $attachments = [$documentPath, $qrCodePath];

        // Update outpass status
        $outpass = $this->outpass->updateOutpass($outpass);
        $queue = $this->mail->queueEmail(
            $subject,
            $accepted,
            $userEmail,
            $attachments
        );

        if (!$queue) {
            return false;
        }

        return $outpass;
    }
}