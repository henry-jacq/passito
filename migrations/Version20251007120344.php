<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251007120344 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hostels DROP FOREIGN KEY FK_C8E0C0419533F2F6');
        $this->addSql('DROP INDEX IDX_C8E0C0419533F2F6 ON hostels');
        $this->addSql('ALTER TABLE hostels DROP warden_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hostels ADD warden_id INT NOT NULL');
        $this->addSql('ALTER TABLE hostels ADD CONSTRAINT FK_C8E0C0419533F2F6 FOREIGN KEY (warden_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_C8E0C0419533F2F6 ON hostels (warden_id)');
    }
}
