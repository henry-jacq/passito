<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250509194940 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE outpass_template_fields (id INT AUTO_INCREMENT NOT NULL, template_id INT NOT NULL, fieldName VARCHAR(100) NOT NULL, fieldType VARCHAR(20) NOT NULL, isSystemField TINYINT(1) NOT NULL, isRequired TINYINT(1) NOT NULL, INDEX IDX_1A1543DD5DA0FB8 (template_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE outpass_templates (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, isSystemTemplate TINYINT(1) NOT NULL, gender VARCHAR(255) NOT NULL, allowAttachments TINYINT(1) NOT NULL, isActive TINYINT(1) NOT NULL, createdAt DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE outpass_template_fields ADD CONSTRAINT FK_1A1543DD5DA0FB8 FOREIGN KEY (template_id) REFERENCES outpass_templates (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE outpass_template_fields DROP FOREIGN KEY FK_1A1543DD5DA0FB8');
        $this->addSql('DROP TABLE outpass_template_fields');
        $this->addSql('DROP TABLE outpass_templates');
    }
}
