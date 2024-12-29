<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241229210256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial migration to create the core database schema for Passito.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hostels (id INT AUTO_INCREMENT NOT NULL, institution_id INT NOT NULL, warden_id INT NOT NULL, hostelName VARCHAR(255) NOT NULL, hostelType VARCHAR(255) NOT NULL, INDEX IDX_C8E0C04110405986 (institution_id), INDEX IDX_C8E0C0419533F2F6 (warden_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE institutions (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE outpass_requests (id BIGINT AUTO_INCREMENT NOT NULL, student_id INT NOT NULL, approved_by INT DEFAULT NULL, fromDate DATE NOT NULL, toDate DATE NOT NULL, fromTime TIME NOT NULL, toTime TIME NOT NULL, passType VARCHAR(255) NOT NULL, destination VARCHAR(255) NOT NULL, purpose VARCHAR(255) NOT NULL, attachments JSON DEFAULT NULL, status VARCHAR(255) NOT NULL, remarks VARCHAR(255) DEFAULT NULL, approvedTime DATETIME DEFAULT NULL, createdAt DATETIME NOT NULL, INDEX IDX_5DF92884CB944F1A (student_id), INDEX IDX_5DF928844EA3CB3D (approved_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE settings (id INT AUTO_INCREMENT NOT NULL, keyName VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, updatedAt DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE students (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, hostel_id INT NOT NULL, name VARCHAR(255) NOT NULL, digitalId INT NOT NULL, year INT NOT NULL, branch VARCHAR(255) NOT NULL, department VARCHAR(255) NOT NULL, roomNo VARCHAR(255) NOT NULL, parentNo VARCHAR(15) NOT NULL, status VARCHAR(255) NOT NULL, updatedAt DATETIME NOT NULL, UNIQUE INDEX UNIQ_A4698DB2ECA9E64F (digitalId), INDEX IDX_A4698DB2A76ED395 (user_id), INDEX IDX_A4698DB2FC68ACC0 (hostel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, gender VARCHAR(255) NOT NULL, createdAt DATETIME NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE verifier_logs (verifier_id INT NOT NULL, outpass_id BIGINT NOT NULL, logId INT AUTO_INCREMENT NOT NULL, inTime DATETIME NOT NULL, outTime DATETIME NOT NULL, timestamp DATETIME NOT NULL, INDEX IDX_3A19F72795561DEE (verifier_id), INDEX IDX_3A19F7272070ABE0 (outpass_id), PRIMARY KEY(logId)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE verifiers (id INT AUTO_INCREMENT NOT NULL, verifierName VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, ipAddress VARCHAR(255) NOT NULL, machineId VARCHAR(255) NOT NULL, authToken VARCHAR(255) NOT NULL, lastSync DATETIME NOT NULL, createdAt DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wardens (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, phoneNo VARCHAR(15) NOT NULL, INDEX IDX_3C3351BBA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hostels ADD CONSTRAINT FK_C8E0C04110405986 FOREIGN KEY (institution_id) REFERENCES institutions (id)');
        $this->addSql('ALTER TABLE hostels ADD CONSTRAINT FK_C8E0C0419533F2F6 FOREIGN KEY (warden_id) REFERENCES wardens (id)');
        $this->addSql('ALTER TABLE outpass_requests ADD CONSTRAINT FK_5DF92884CB944F1A FOREIGN KEY (student_id) REFERENCES students (id)');
        $this->addSql('ALTER TABLE outpass_requests ADD CONSTRAINT FK_5DF928844EA3CB3D FOREIGN KEY (approved_by) REFERENCES wardens (id)');
        $this->addSql('ALTER TABLE students ADD CONSTRAINT FK_A4698DB2A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE students ADD CONSTRAINT FK_A4698DB2FC68ACC0 FOREIGN KEY (hostel_id) REFERENCES hostels (id)');
        $this->addSql('ALTER TABLE verifier_logs ADD CONSTRAINT FK_3A19F72795561DEE FOREIGN KEY (verifier_id) REFERENCES verifiers (id)');
        $this->addSql('ALTER TABLE verifier_logs ADD CONSTRAINT FK_3A19F7272070ABE0 FOREIGN KEY (outpass_id) REFERENCES outpass_requests (id)');
        $this->addSql('ALTER TABLE wardens ADD CONSTRAINT FK_3C3351BBA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hostels DROP FOREIGN KEY FK_C8E0C04110405986');
        $this->addSql('ALTER TABLE hostels DROP FOREIGN KEY FK_C8E0C0419533F2F6');
        $this->addSql('ALTER TABLE outpass_requests DROP FOREIGN KEY FK_5DF92884CB944F1A');
        $this->addSql('ALTER TABLE outpass_requests DROP FOREIGN KEY FK_5DF928844EA3CB3D');
        $this->addSql('ALTER TABLE students DROP FOREIGN KEY FK_A4698DB2A76ED395');
        $this->addSql('ALTER TABLE students DROP FOREIGN KEY FK_A4698DB2FC68ACC0');
        $this->addSql('ALTER TABLE verifier_logs DROP FOREIGN KEY FK_3A19F72795561DEE');
        $this->addSql('ALTER TABLE verifier_logs DROP FOREIGN KEY FK_3A19F7272070ABE0');
        $this->addSql('ALTER TABLE wardens DROP FOREIGN KEY FK_3C3351BBA76ED395');
        $this->addSql('DROP TABLE hostels');
        $this->addSql('DROP TABLE institutions');
        $this->addSql('DROP TABLE outpass_requests');
        $this->addSql('DROP TABLE settings');
        $this->addSql('DROP TABLE students');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE verifier_logs');
        $this->addSql('DROP TABLE verifiers');
        $this->addSql('DROP TABLE wardens');
    }
}
