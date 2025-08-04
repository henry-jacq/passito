<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250804173033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE institution_programs (id INT AUTO_INCREMENT NOT NULL, provided_by INT NOT NULL, programName VARCHAR(255) NOT NULL, courseName VARCHAR(255) NOT NULL, shortCode VARCHAR(255) NOT NULL, duration INT NOT NULL, createdAt DATETIME NOT NULL, INDEX IDX_EF2C69DBFFD315FB (provided_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE institution_programs ADD CONSTRAINT FK_EF2C69DBFFD315FB FOREIGN KEY (provided_by) REFERENCES institutions (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE institution_programs DROP FOREIGN KEY FK_EF2C69DBFFD315FB');
        $this->addSql('DROP TABLE institution_programs');
    }
}
