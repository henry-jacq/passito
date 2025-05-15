<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250515205413 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE outpass_requests ADD template_id INT NOT NULL, ADD reason VARCHAR(255) NOT NULL, ADD customValues JSON DEFAULT NULL, DROP passType, DROP purpose');
        $this->addSql('ALTER TABLE outpass_requests ADD CONSTRAINT FK_5DF928845DA0FB8 FOREIGN KEY (template_id) REFERENCES outpass_templates (id)');
        $this->addSql('CREATE INDEX IDX_5DF928845DA0FB8 ON outpass_requests (template_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE outpass_requests DROP FOREIGN KEY FK_5DF928845DA0FB8');
        $this->addSql('DROP INDEX IDX_5DF928845DA0FB8 ON outpass_requests');
        $this->addSql('ALTER TABLE outpass_requests ADD purpose VARCHAR(255) NOT NULL, DROP template_id, DROP customValues, CHANGE reason passType VARCHAR(255) NOT NULL');
    }
}
