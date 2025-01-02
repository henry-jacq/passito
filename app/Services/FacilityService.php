<?php

namespace App\Services;

use App\Entity\User;
use App\Core\Session;
use App\Entity\Hostel;
use App\Enum\HostelType;
use App\Entity\Institution;
use App\Enum\InstitutionType;
use Doctrine\ORM\EntityManagerInterface;

class FacilityService
{
    public function __construct(
        private readonly Session $session,
        private readonly EntityManagerInterface $em
    )
    {
    }
    
    public function getInstitutions()
    {
        $institutions = $this->em->getRepository(Institution::class)->findAll();
        return $institutions;
    }

    public function getInstitutionById(int $id): ?Institution
    {
        return $this->em->getRepository(Institution::class)->find($id);
    }

    public function createInstitution(array $data): Institution
    {
        $institution = new Institution();
        $institution->setName($data['name']);
        $institution->setAddress($data['address']);
        $institution->setType(InstitutionType::from($data['type']));
        $institution->setCreatedAt(new \DateTime());

        $this->em->persist($institution);
        $this->em->flush();

        return $institution;
    }

    public function updateInstitution(int $id, array $data): Institution
    {
        $institution = $this->getInstitutionById($id);
        $institution->setName($data['name']);
        $institution->setAddress($data['address']);
        $institution->setType($data['type']);
        $institution->setUpdatedAt(new \DateTime());

        $this->em->flush();

        return $institution;
    }

    public function deleteInstitution(int $id): void
    {
        $institution = $this->getInstitutionById($id);
        $this->em->remove($institution);
        $this->em->flush();
    }

    public function getHostels()
    {
        $hostels = $this->em->getRepository(Hostel::class)->findAll();
        return $hostels;
    }

    public function getHostelById(int $id): ?Hostel
    {
        return $this->em->getRepository(Hostel::class)->find($id);
    }

    public function createHostel(array $data): Hostel
    {
        $warden = $this->em->getRepository(User::class)->find($data['warden_id']);
        $institution = $this->getInstitutionById($data['institution_id']);
        
        $hostel = new Hostel();
        $hostel->setInstitution($institution);
        $hostel->setWarden($warden);
        $hostel->setName($data['hostel_name']);
        $hostel->setHostelType($data['hostel_type']);

        $this->em->persist($hostel);
        $this->em->flush();

        return $hostel;
    }

    public function updateHostel(int $id, array $data): Hostel
    {
        $hostel = $this->getHostelById($id);
        $hostel->setInstitution($this->getInstitutionById($data['institution_id']));
        $hostel->setWarden($data['warden_id']);
        $hostel->setName($data['hostel_name']);
        $hostel->setHostelType($data['hostel_type']);

        $this->em->flush();

        return $hostel;
    }

    public function deleteHostel(int $id): void
    {
        $hostel = $this->getHostelById($id);
        $this->em->remove($hostel);
        $this->em->flush();
    }
}