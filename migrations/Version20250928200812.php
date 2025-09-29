<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250928200812 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE academic_years (id INT AUTO_INCREMENT NOT NULL, year VARCHAR(9) NOT NULL, createdAt DATETIME NOT NULL, UNIQUE INDEX UNIQ_574085B2BB827337 (year), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE warden_assignments (id INT AUTO_INCREMENT NOT NULL, assigned_to INT NOT NULL, assigned_by INT NOT NULL, target_type VARCHAR(255) NOT NULL, assignment_id INT NOT NULL, createdAt DATETIME NOT NULL, INDEX IDX_C213683089EEAF91 (assigned_to), INDEX IDX_C213683061A2AF17 (assigned_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE warden_assignments ADD CONSTRAINT FK_C213683089EEAF91 FOREIGN KEY (assigned_to) REFERENCES users (id)');
        $this->addSql('ALTER TABLE warden_assignments ADD CONSTRAINT FK_C213683061A2AF17 FOREIGN KEY (assigned_by) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE warden_assignments DROP FOREIGN KEY FK_C213683089EEAF91');
        $this->addSql('ALTER TABLE warden_assignments DROP FOREIGN KEY FK_C213683061A2AF17');
        $this->addSql('DROP TABLE academic_years');
        $this->addSql('DROP TABLE warden_assignments');
    }
}
