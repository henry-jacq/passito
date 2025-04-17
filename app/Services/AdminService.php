<?php

namespace App\Services;

use App\Core\View;
use App\Core\Storage;
use App\Enum\OutpassStatus;
use App\Entity\OutpassRequest;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

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
        $expiredCount = count($this->outpass->getExpiredOutpass());
        $approvedCount = count($this->outpass->getApprovedOutpass());
        $rejectedCount = count($this->outpass->getRejectedOutpass());
        $checkedOutCount = count($this->verifierService->fetchCheckedOutLogs());
        $pendingCount = count($this->outpass->getPendingOutpass(paginate: false));

        return [
            'pending' => $pendingCount,
            'approved' => $approvedCount + $expiredCount,
            'rejected' => $rejectedCount,
            'checkedOut' => $checkedOutCount
        ];
    }

    public function approveAllPending(User $approvedBy)
    {
        $pendingPass = $this->outpass->getPendingOutpass(paginate: false);
        
        try {
            foreach ($pendingPass as $pending) {
                $this->approvePending($pending, $approvedBy);
            }
            return true;
        } catch(Exception $e) {
            return false;
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

    public function rejectPending(OutpassRequest $outpass, $approvedBy, $reason=null): OutpassRequest|bool
    {
        $outpass->setStatus(OutpassStatus::REJECTED);
        $outpass->setApprovedTime($time = new \DateTime());
        $outpass->setApprovedBy($approvedBy);
        $outpass->setAttachments(null);

        if (empty($reason)) {
            $outpass->setRemarks(null);
        } else {
            $reason = htmlspecialchars($reason, ENT_QUOTES, 'UTF-8');
            $outpass->setRemarks($reason);
        }

        $rejected = $this->view->renderEmail('outpass/rejected', [
            'studentName' => $outpass->getStudent()->getUser()->getName(),
            'outpass' => $outpass,
        ]);

        $userEmail = $outpass->getStudent()->getUser()->getEmail();
        $subject = "Your Outpass Request #{$outpass->getId()} Has Been Rejected";

        // Update outpass status
        $outpass = $this->outpass->updateOutpass($outpass);
        $queue = $this->mail->queueEmail(
            $subject,
            $rejected,
            $userEmail
        );

        if (!$queue) {
            return false;
        }

        return $outpass;
    }
}