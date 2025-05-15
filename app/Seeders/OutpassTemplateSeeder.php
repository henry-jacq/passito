<?php

namespace App\Seeders;

use App\Entity\OutpassTemplate;
use App\Entity\User;
use App\Enum\Gender;
use App\Services\OutpassService;
use Doctrine\ORM\EntityManagerInterface;

class OutpassTemplateSeeder
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly OutpassService $outpassService,
    ) {}

    public function run()
    {
        $templates = [
            [
                'data' => [
                    'name' => 'Home Pass',
                    'description' => 'Students can apply for a home pass using this template.',
                    'allowAttachments' => true,
                ],
                'fields' => [
                    ['name' => 'From Date', 'type' => 'date', 'required' => true, 'system' => true],
                    ['name' => 'From Time', 'type' => 'time', 'required' => true, 'system' => true],
                    ['name' => 'To Date', 'type' => 'date', 'required' => true, 'system' => true],
                    ['name' => 'To Time', 'type' => 'time', 'required' => true, 'system' => true],
                    ['name' => 'Destination', 'type' => 'text', 'required' => true, 'system' => true],
                    ['name' => 'Reason', 'type' => 'text', 'required' => true, 'system' => true],
                ],
            ],
            [
                'data' => [
                    'name' => 'Emergency Pass',
                    'description' => 'Students can apply for an emergency pass using this template.',
                    'allowAttachments' => true,
                ],
                'fields' => [
                    ['name' => 'From Date', 'type' => 'date', 'required' => true, 'system' => true],
                    ['name' => 'From Time', 'type' => 'time', 'required' => true, 'system' => true],
                    ['name' => 'To Date', 'type' => 'date', 'required' => true, 'system' => true],
                    ['name' => 'To Time', 'type' => 'time', 'required' => true, 'system' => true],
                    ['name' => 'Destination', 'type' => 'text', 'required' => true, 'system' => true],
                    ['name' => 'Reason', 'type' => 'text', 'required' => true, 'system' => true],
                ],
            ],
        ];

        foreach ($templates as $template) {
            $existingTemplate = $this->em->getRepository(OutpassTemplate::class)
                ->findOneBy(['name' => $template['data']['name']]);

            if ($existingTemplate) {
                echo "Template '{$template['data']['name']}' already exists. Skipping.\n";
                continue;
            }

            $this->outpassService->createTemplate(
                Gender::MALE,
                $template['data'],
                $template['fields'],
                true
            );

            $this->outpassService->createTemplate(
                Gender::FEMALE,
                $template['data'],
                $template['fields'],
                true
            );
        }

        $this->em->flush();

        echo "Outpass templates seeded successfully!\n";
    }
}
