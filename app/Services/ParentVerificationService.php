<?php

namespace App\Services;

use App\Core\View;
use App\Core\Config;
use App\Entity\User;
use Ramsey\Uuid\Uuid;
use App\Enum\OutpassStatus;
use App\Entity\OutpassRequest;
use App\Entity\ParentVerification;
use Doctrine\ORM\EntityManagerInterface;

class ParentVerificationService
{
    public function __construct(
        private readonly Config $config,
        private readonly View $view,
        private readonly EntityManagerInterface $em,
        private readonly OutpassService $outpassService,
    ) {}

    public function createEntry(OutpassRequest $outpass): ParentVerification
    {
        $parent = $outpass->getStudent()->getParentNo();

        if (!$parent) {
            throw new \RuntimeException('Parent not found for the user.');
        }

        $existingVerification = $this->em->getRepository(ParentVerification::class)
            ->findOneBy(['outpassRequest' => $outpass]);

        if ($existingVerification) {
            throw new \RuntimeException('A verification entry already exists for this outpass.');
        }

        $uuid = Uuid::uuid4()->toString();

        $parentVerification = new ParentVerification();
        $parentVerification->setOutpassRequest($outpass);
        $parentVerification->setVerificationToken($uuid);

        $outpass->setStatus(OutpassStatus::PARENT_PENDING);

        $this->em->persist($parentVerification);
        $this->em->persist($outpass);
        
        $this->em->flush();

        return $parentVerification;
    }

    public function processDecision(ParentVerification $verification, string $decision): ParentVerification
    {
        $status = OutpassStatus::from($decision);

        if ($verification->isUsed()) {
            throw new \RuntimeException('This verification entry has already been used.');
        }

        if ($status === OutpassStatus::PARENT_APPROVED) {
            $verification->setDecision(OutpassStatus::PARENT_APPROVED);
        } elseif ($status === OutpassStatus::PARENT_DENIED) {
            $verification->setDecision(OutpassStatus::PARENT_DENIED);
        } else {
            throw new \RuntimeException('Invalid decision provided.');
        }

        // Update the verification entry
        $verification->setIsUsed(true);
        $verification->setDecision($status);
        $verification->setVerifiedAt(new \DateTime());

        $outpass = $verification->getOutpassRequest();
        $outpass->setStatus($status);

        $this->em->flush();

        return $verification;
    }

    public function getVerificationByToken(string $token): ?ParentVerification
    {
        return $this->em->getRepository(ParentVerification::class)->findOneBy(['verificationToken' => $token]);
    }

    public function getMessage(User $user, OutpassRequest $outpass, ParentVerification $entry): string
    {
        // Generate the verification URL
        $url = $this->getVerificationUrl($entry->getVerificationToken());

        $message = $this->config->get('notification.sms.twilio.verification_message');
        $message = str_replace('{student_name}', $user->getName(), $message);
        $message = str_replace('{purpose}', strtolower($outpass->getPurpose()), $message);
        $message = str_replace('{verification_link}', $url, $message);

        return $message;
    }

    public function getVerificationUrl(string $token): string
    {
        $route = $this->view->urlFor('parent.verify', [], [
            'token' => $token,
        ]);

        $qualifiedRoute = $this->config->get('app.host') . $route;

        return $qualifiedRoute;
    }
}
