<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241010081037 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE outpasses (id INT AUTO_INCREMENT NOT NULL, student_id INT DEFAULT NULL, fromDate DATE NOT NULL, toDate DATE NOT NULL, fromTime TIME NOT NULL, toTime TIME NOT NULL, passType VARCHAR(64) NOT NULL, destination VARCHAR(128) NOT NULL, subject VARCHAR(256) NOT NULL, purpose LONGTEXT NOT NULL, attachments JSON DEFAULT NULL, status VARCHAR(20) NOT NULL, wardenApprovalTime DATETIME DEFAULT NULL, wardenRemarks LONGTEXT DEFAULT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME DEFAULT NULL, INDEX IDX_C88F0434CB944F1A (student_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE students (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(64) NOT NULL, digitalId INT NOT NULL, year INT NOT NULL, branch VARCHAR(32) NOT NULL, roomNo VARCHAR(8) NOT NULL, parentNo VARCHAR(20) NOT NULL, institution VARCHAR(128) NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_A4698DB2ECA9E64F (digitalId), INDEX IDX_A4698DB2A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(64) NOT NULL, password VARCHAR(256) NOT NULL, role ENUM(\'admin\', \'user\'), createdAt DATETIME NOT NULL, updatedAt DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wardens (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(64) NOT NULL, phoneNo VARCHAR(20) NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME DEFAULT NULL, INDEX IDX_3C3351BBA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE outpasses ADD CONSTRAINT FK_C88F0434CB944F1A FOREIGN KEY (student_id) REFERENCES students (id)');
        $this->addSql('ALTER TABLE students ADD CONSTRAINT FK_A4698DB2A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE wardens ADD CONSTRAINT FK_3C3351BBA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE outpasses DROP FOREIGN KEY FK_C88F0434CB944F1A');
        $this->addSql('ALTER TABLE students DROP FOREIGN KEY FK_A4698DB2A76ED395');
        $this->addSql('ALTER TABLE wardens DROP FOREIGN KEY FK_3C3351BBA76ED395');
        $this->addSql('DROP TABLE outpasses');
        $this->addSql('DROP TABLE students');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE wardens');
    }
}
