<?php

namespace App\Seeders;

use DateTime;
use App\Entity\Institution;
use App\Entity\InstitutionProgram;
use Doctrine\ORM\EntityManagerInterface;

class ProgramSeeder
{
    public function __construct(private readonly EntityManagerInterface $em) {}

    public function run()
    {
        $ssn = $this->em->getRepository(Institution::class)
            ->findOneBy(['name' => 'SSN College of Engineering']);

        $snu = $this->em->getRepository(Institution::class)
            ->findOneBy(['name' => 'Shiv Nadar University']);

        if (!$ssn || !$snu) {
            throw new \RuntimeException('One or both institutions not found.');
        }

        $programs = [

            // SSN Programs
            ['institution' => $ssn, 'programName' => 'B.E.', 'courseName' => 'Electrical and Electronics Engineering', 'shortCode' => 'EEE', 'duration' => 4],
            ['institution' => $ssn, 'programName' => 'B.E.', 'courseName' => 'Electronics And Communication Engineering', 'shortCode' => 'ECE', 'duration' => 4],
            ['institution' => $ssn, 'programName' => 'B.E.', 'courseName' => 'Computer Science And Engineering', 'shortCode' => 'CSE', 'duration' => 4],
            ['institution' => $ssn, 'programName' => 'B.Tech', 'courseName' => 'Information Technology', 'shortCode' => 'IT', 'duration' => 4],
            ['institution' => $ssn, 'programName' => 'B.E.', 'courseName' => 'Mechanical Engineering', 'shortCode' => 'MECH', 'duration' => 4],
            ['institution' => $ssn, 'programName' => 'B.Tech', 'courseName' => 'Chemical Engineering', 'shortCode' => 'CHE', 'duration' => 4],
            ['institution' => $ssn, 'programName' => 'B.E.', 'courseName' => 'Bio-Medical Engineering', 'shortCode' => 'BME', 'duration' => 4],
            ['institution' => $ssn, 'programName' => 'B.E.', 'courseName' => 'Civil Engineering', 'shortCode' => 'CIVIL', 'duration' => 4],
            ['institution' => $ssn, 'programName' => 'M.E.', 'courseName' => 'Communication Systems', 'shortCode' => 'MECS', 'duration' => 2],
            ['institution' => $ssn, 'programName' => 'M.E.', 'courseName' => 'Computer Science And Engineering', 'shortCode' => 'ME-CSE', 'duration' => 2],
            ['institution' => $ssn, 'programName' => 'M.E.', 'courseName' => 'Power Electronics and Drives', 'shortCode' => 'PED', 'duration' => 2],
            ['institution' => $ssn, 'programName' => 'M.Tech', 'courseName' => 'Information Technology', 'shortCode' => 'MTECH-IT', 'duration' => 2],
            ['institution' => $ssn, 'programName' => 'M.E.', 'courseName' => 'VLSI Design', 'shortCode' => 'VLSI', 'duration' => 2],
            ['institution' => $ssn, 'programName' => 'M.E.', 'courseName' => 'Energy Engineering', 'shortCode' => 'ENE', 'duration' => 2],
            ['institution' => $ssn, 'programName' => 'M.E.', 'courseName' => 'Manufacturing Engineering', 'shortCode' => 'MANU', 'duration' => 2],
            ['institution' => $ssn, 'programName' => 'M.E.', 'courseName' => 'Medical Electronics', 'shortCode' => 'MED-ELEC', 'duration' => 2],
            ['institution' => $ssn, 'programName' => 'M.Tech', 'courseName' => 'Environmental Science And Technology', 'shortCode' => 'ENV', 'duration' => 2],

            // SNU Programs
            ['institution' => $snu, 'programName' => 'B.Tech', 'courseName' => 'Computer Science and Engineering (Internet of Things)', 'shortCode' => 'CSE-IOT', 'duration' => 4],
            ['institution' => $snu, 'programName' => 'B.Tech', 'courseName' => 'Artificial Intelligence and Data Science', 'shortCode' => 'AIDS', 'duration' => 4],
            ['institution' => $snu, 'programName' => 'B.Tech', 'courseName' => 'Computer Science and Engineering (Cyber Security)', 'shortCode' => 'CSE-CS', 'duration' => 4],
            ['institution' => $snu, 'programName' => 'M.Tech', 'courseName' => 'Artificial Intelligence and Data Science', 'shortCode' => 'MTECH-AIDS', 'duration' => 2],
            ['institution' => $snu, 'programName' => 'B.Com', 'courseName' => 'Professional Accounting', 'shortCode' => 'BCOM-PA', 'duration' => 3],
            ['institution' => $snu, 'programName' => 'B.Com', 'courseName' => '', 'shortCode' => 'BCOM', 'duration' => 3],
            ['institution' => $snu, 'programName' => 'B.Sc', 'courseName' => 'Economics (Data Science)', 'shortCode' => 'ECODS', 'duration' => 3],
            ['institution' => $snu, 'programName' => 'PhD', 'courseName' => 'PhD Program', 'shortCode' => 'PHD', 'duration' => 7],
            ['institution' => $snu, 'programName' => 'BA.LLB', 'courseName' => '', 'shortCode' => 'BALLB', 'duration' => 5],
        ];

        foreach ($programs as $program) {
            $existing = $this->em->getRepository(InstitutionProgram::class)
                ->findOneBy([
                    'shortCode' => $program['shortCode'],
                    'providedBy' => $program['institution']
                ]);

            if (!$existing) {
                $newProgram = new InstitutionProgram();
                $newProgram->setProvidedBy($program['institution']);
                $newProgram->setProgramName($program['programName']);
                $newProgram->setCourseName($program['courseName'] ?: $program['programName']); // fallback
                $newProgram->setShortCode($program['shortCode']);
                $newProgram->setDuration($program['duration']);
                $newProgram->setCreatedAt(new DateTime());

                $this->em->persist($newProgram);
            }
        }

        $this->em->flush();

        echo "Institution Programs for SSN and SNU seeded successfully!\n";
    }
}
