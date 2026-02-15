<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260215134429 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add password reset tokens table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE password_reset_tokens (id BIGINT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, tokenHash CHAR(64) NOT NULL, expiresAt DATETIME NOT NULL, consumedAt DATETIME DEFAULT NULL, createdAt DATETIME NOT NULL, requestIp VARCHAR(45) DEFAULT NULL, userAgent VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_PRT_TOKEN_HASH (tokenHash), INDEX prt_user_idx (user_id), INDEX prt_expires_idx (expiresAt), INDEX prt_consumed_idx (consumedAt), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE password_reset_tokens ADD CONSTRAINT FK_PRT_USER FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE password_reset_tokens DROP FOREIGN KEY FK_PRT_USER');
        $this->addSql('DROP TABLE password_reset_tokens');
    }
}

