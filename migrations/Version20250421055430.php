<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250421055430 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE parent_verifications (id INT AUTO_INCREMENT NOT NULL, outpass_id BIGINT NOT NULL, verificationToken VARCHAR(255) NOT NULL, isUsed TINYINT(1) NOT NULL, decision VARCHAR(255) DEFAULT NULL, verifiedAt DATETIME DEFAULT NULL, createdAt DATETIME NOT NULL, UNIQUE INDEX UNIQ_500084D45CE70D23 (verificationToken), INDEX IDX_500084D42070ABE0 (outpass_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE parent_verifications ADD CONSTRAINT FK_500084D42070ABE0 FOREIGN KEY (outpass_id) REFERENCES outpass_requests (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE parent_verifications DROP FOREIGN KEY FK_500084D42070ABE0');
        $this->addSql('DROP TABLE parent_verifications');
    }
}
