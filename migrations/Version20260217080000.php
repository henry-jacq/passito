<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260217080000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename students.digitalId column to rollNo for roll number naming consistency';
    }

    public function up(Schema $schema): void
    {
        if (!$schema->hasTable('students')) {
            return;
        }

        $table = $schema->getTable('students');
        if ($table->hasColumn('digitalId') && !$table->hasColumn('rollNo')) {
            $this->addSql('ALTER TABLE students CHANGE digitalId rollNo INT NOT NULL');
        }
    }

    public function down(Schema $schema): void
    {
        if (!$schema->hasTable('students')) {
            return;
        }

        $table = $schema->getTable('students');
        if ($table->hasColumn('rollNo') && !$table->hasColumn('digitalId')) {
            $this->addSql('ALTER TABLE students CHANGE rollNo digitalId INT NOT NULL');
        }
    }
}

