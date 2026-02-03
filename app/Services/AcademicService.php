<?php

namespace App\Services;

use App\Entity\User;
use App\Enum\Gender;
use App\Core\Session;
use App\Entity\AcademicYear;
use App\Entity\Hostel;
use App\Enum\HostelType;
use App\Entity\Institution;
use App\Enum\InstitutionType;
use App\Entity\InstitutionProgram;
use Doctrine\ORM\EntityManagerInterface;

class AcademicService
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

    public function getHostelsByType(User $user)
    {
        if ($user->getGender() == Gender::MALE) {
            return $this->em->getRepository(Hostel::class)->findBy(['hostelType' => HostelType::GENTS]);
        }

        return $this->em->getRepository(Hostel::class)->findBy(['hostelType' => HostelType::LADIES]);
    }

    public function getHostelById(int $id): ?Hostel
    {
        return $this->em->getRepository(Hostel::class)->find($id);
    }

    public function getHostelByName(string $name)
    {
        return $this->em->getRepository(Hostel::class)->findOneBy([
            'hostelName' => $name
        ]);
    }

    public function createHostel(array $data): Hostel|bool
    {
        // Check If the hostel name already exists
        $hostel = $this->em->getRepository(Hostel::class)->findOneBy(['hostelName' => $data['hostel_name']]);
        
        if ($hostel) {
            return false;
        }
        
        $hostel = new Hostel();
        $hostel->setName($data['hostel_name']);
        $hostel->setCategory($data['category']);
        $hostel->setHostelType($data['hostel_type']);

        $this->em->persist($hostel);
        $this->em->flush();

        return $hostel;
    }

    public function updateHostel(int $id, array $data): Hostel
    {
        $hostel = $this->getHostelById($id);
        $hostel->setName($data['hostel_name']);
        if (isset($data['hostel_type'])) {
            $hostel->setHostelType($data['hostel_type']);
        }
        if (isset($data['category'])) {
            $hostel->setCategory($data['category']);
        }

        $this->em->flush();

        return $hostel;
    }

    public function removeHostel(int $id): bool
    {
        $hostel = $this->getHostelById($id);
        if (!$hostel) {
            return false;
        }

        $this->em->createQueryBuilder()
            ->delete(\App\Entity\WardenAssignment::class, 'wa')
            ->where('wa.hostelId = :hostelId')
            ->setParameter('hostelId', $id)
            ->getQuery()
            ->execute();

        $this->em->remove($hostel);
        $this->em->flush();

        return true;
    }

    public function hostelHasStudents(Hostel $hostel): bool
    {
        $count = $this->em->getRepository(\App\Entity\Student::class)->count([
            'hostel' => $hostel
        ]);

        return $count > 0;
    }

    public function getPrograms()
    {
        return $this->em->getRepository(InstitutionProgram::class)->findAll();
    }

    public function getProgramById(int $id): ?InstitutionProgram
    {
        return $this->em->getRepository(InstitutionProgram::class)->find($id);
    }

    public function getProgramByShortCode(string $shortCode): ?InstitutionProgram
    {
        return $this->em->getRepository(InstitutionProgram::class)->findOneBy(['shortCode' => $shortCode]);
    }
    
    public function getProgramsByInstitution(Institution $institution)
    {
        return $this->em->getRepository(InstitutionProgram::class)
            ->findBy(['providedBy' => $institution]);
    }

    public function getAcademicYears(User $adminUser)
    {
        return $this->em->getRepository(AcademicYear::class)->findAll();
    }

    public function getAcademicYearById(int $id): ?AcademicYear
    {
        return $this->em->getRepository(AcademicYear::class)->find($id);
    }

    public function getAcademicYearByLabel(string $label): ?AcademicYear
    {
        return $this->em->getRepository(AcademicYear::class)->findOneBy(['label' => $label]);
    }

    public function createAcademicYear(array $data): AcademicYear|bool
    {
        $label = trim((string)($data['label'] ?? ''));
        if ($label === '') {
            return false;
        }

        $existing = $this->getAcademicYearByLabel($label);
        if ($existing) {
            return false;
        }

        $academicYear = new AcademicYear();
        $academicYear->setLabel($label);
        $academicYear->setStartYear(isset($data['start_year']) ? (int)$data['start_year'] : null);
        $academicYear->setEndYear(isset($data['end_year']) ? (int)$data['end_year'] : null);
        $academicYear->setStatus(isset($data['status']) ? (bool)$data['status'] : true);

        $this->em->persist($academicYear);
        $this->em->flush();

        return $academicYear;
    }

    public function updateAcademicYear(int $id, array $data): AcademicYear|bool
    {
        $academicYear = $this->getAcademicYearById($id);
        if (!$academicYear) {
            return false;
        }

        $label = trim((string)($data['label'] ?? ''));
        if ($label === '') {
            return false;
        }

        $existing = $this->getAcademicYearByLabel($label);
        if ($existing && $existing->getId() !== $academicYear->getId()) {
            return false;
        }

        $academicYear->setLabel($label);
        $academicYear->setStartYear(isset($data['start_year']) ? (int)$data['start_year'] : null);
        $academicYear->setEndYear(isset($data['end_year']) ? (int)$data['end_year'] : null);
        $academicYear->setStatus(isset($data['status']) ? (bool)$data['status'] : $academicYear->getStatus());

        $this->em->flush();

        return $academicYear;
    }

    public function removeAcademicYear(int $id): bool
    {
        $academicYear = $this->getAcademicYearById($id);
        if (!$academicYear) {
            return false;
        }

        $this->em->remove($academicYear);
        $this->em->flush();

        return true;
    }
}
