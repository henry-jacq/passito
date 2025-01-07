<?php

namespace App\Services;

use DateTime;
use App\Enum\OutpassType;
use App\Enum\OutpassStatus;
use App\Entity\OutpassRequest;
use Doctrine\ORM\EntityManagerInterface;

class OutpassService
{
    public function __construct(
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

        $this->em->persist($outpass);
        $this->em->flush();

        return $outpass;
    }

    public function getPendingOutpass()
    {
        $outpasses = $this->em->getRepository(OutpassRequest::class)->findBy(
            ['status' => OutpassStatus::PENDING]
        );

        return $outpasses;
    }
}
