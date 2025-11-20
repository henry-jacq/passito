<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251120083117 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE academic_years (id INT AUTO_INCREMENT NOT NULL, year VARCHAR(9) NOT NULL, createdAt DATETIME NOT NULL, UNIQUE INDEX UNIQ_574085B2BB827337 (year), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE hostels (id INT AUTO_INCREMENT NOT NULL, hostelName VARCHAR(255) NOT NULL, hostelType VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE institution_programs (id INT AUTO_INCREMENT NOT NULL, provided_by INT NOT NULL, programName VARCHAR(255) NOT NULL, courseName VARCHAR(255) NOT NULL, shortCode VARCHAR(255) NOT NULL, duration INT NOT NULL, createdAt DATETIME NOT NULL, INDEX IDX_EF2C69DBFFD315FB (provided_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE institutions (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE jobs (id BIGINT AUTO_INCREMENT NOT NULL, type VARCHAR(100) NOT NULL, payload JSON NOT NULL, availableAt DATETIME NOT NULL, attempts INT NOT NULL, maxAttempts INT NOT NULL, status VARCHAR(20) NOT NULL, dependencies JSON NOT NULL, result JSON DEFAULT NULL, createdAt DATETIME NOT NULL, lastError VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE logbook (verifier_id INT NOT NULL, outpass_id BIGINT NOT NULL, logId INT AUTO_INCREMENT NOT NULL, inTime DATETIME DEFAULT NULL, outTime DATETIME DEFAULT NULL, INDEX IDX_E96B931095561DEE (verifier_id), INDEX IDX_E96B93102070ABE0 (outpass_id), PRIMARY KEY(logId)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE outpass_requests (id BIGINT AUTO_INCREMENT NOT NULL, template_id INT NOT NULL, student_id INT NOT NULL, approved_by INT DEFAULT NULL, fromDate DATE NOT NULL, toDate DATE NOT NULL, fromTime TIME NOT NULL, toTime TIME NOT NULL, destination VARCHAR(255) NOT NULL, reason VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, customValues JSON DEFAULT NULL, attachments JSON DEFAULT NULL, remarks VARCHAR(255) DEFAULT NULL, document VARCHAR(255) DEFAULT NULL, qrCode VARCHAR(255) DEFAULT NULL, approvedTime DATETIME DEFAULT NULL, createdAt DATETIME NOT NULL, INDEX IDX_5DF928845DA0FB8 (template_id), INDEX IDX_5DF92884CB944F1A (student_id), INDEX IDX_5DF928844EA3CB3D (approved_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE outpass_settings (id INT AUTO_INCREMENT NOT NULL, dailyLimit INT DEFAULT NULL, weeklyLimit INT DEFAULT NULL, type VARCHAR(255) NOT NULL, parentApproval TINYINT(1) NOT NULL, companionVerification TINYINT(1) NOT NULL, emergencyContactNotification TINYINT(1) NOT NULL, weekdayCollegeHoursStart TIME DEFAULT NULL, weekdayCollegeHoursEnd TIME DEFAULT NULL, weekdayOvernightStart TIME DEFAULT NULL, weekdayOvernightEnd TIME DEFAULT NULL, weekendStartTime TIME DEFAULT NULL, weekendEndTime TIME DEFAULT NULL, emailNotification TINYINT(1) NOT NULL, smsNotification TINYINT(1) NOT NULL, appNotification TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE outpass_template_fields (id INT AUTO_INCREMENT NOT NULL, template_id INT NOT NULL, fieldName VARCHAR(100) NOT NULL, fieldType VARCHAR(20) NOT NULL, isSystemField TINYINT(1) NOT NULL, isRequired TINYINT(1) NOT NULL, INDEX IDX_1A1543DD5DA0FB8 (template_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE outpass_templates (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, isSystemTemplate TINYINT(1) NOT NULL, gender VARCHAR(255) NOT NULL, allowAttachments TINYINT(1) NOT NULL, isActive TINYINT(1) NOT NULL, createdAt DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE parent_verifications (id INT AUTO_INCREMENT NOT NULL, outpass_id BIGINT NOT NULL, verificationToken VARCHAR(255) NOT NULL, isUsed TINYINT(1) NOT NULL, decision VARCHAR(255) DEFAULT NULL, verifiedAt DATETIME DEFAULT NULL, createdAt DATETIME NOT NULL, UNIQUE INDEX UNIQ_500084D45CE70D23 (verificationToken), INDEX IDX_500084D42070ABE0 (outpass_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE report_configs (id INT AUTO_INCREMENT NOT NULL, reportKey VARCHAR(255) NOT NULL, isEnabled TINYINT(1) DEFAULT 0 NOT NULL, gender VARCHAR(255) NOT NULL, frequency VARCHAR(255) NOT NULL, dayOfWeek SMALLINT DEFAULT NULL, dayOfMonth SMALLINT DEFAULT NULL, month SMALLINT DEFAULT NULL, time TIME NOT NULL, lastSent DATETIME DEFAULT NULL, nextSend DATETIME DEFAULT NULL, createdAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE report_config_recipients (report_config_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_F6FA6F5FA6F6B9B (report_config_id), INDEX IDX_F6FA6F5FA76ED395 (user_id), PRIMARY KEY(report_config_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE settings (id INT AUTO_INCREMENT NOT NULL, keyName VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, updatedAt DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE students (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, hostel_id INT NOT NULL, program_id INT NOT NULL, digitalId INT NOT NULL, year INT NOT NULL, roomNo VARCHAR(255) NOT NULL, parentNo VARCHAR(15) NOT NULL, status TINYINT(1) NOT NULL, updatedAt DATETIME NOT NULL, UNIQUE INDEX UNIQ_A4698DB2ECA9E64F (digitalId), INDEX IDX_A4698DB2A76ED395 (user_id), INDEX IDX_A4698DB2FC68ACC0 (hostel_id), INDEX IDX_A4698DB23EB8070A (program_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, gender VARCHAR(255) NOT NULL, contactNo VARCHAR(32) NOT NULL, createdAt DATETIME NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE verifiers (id INT AUTO_INCREMENT NOT NULL, verifierName VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, ipAddress VARCHAR(255) DEFAULT NULL, machineId VARCHAR(255) DEFAULT NULL, authToken VARCHAR(255) NOT NULL, lastSync DATETIME DEFAULT NULL, createdAt DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE warden_assignments (id INT AUTO_INCREMENT NOT NULL, assigned_to INT NOT NULL, assigned_by INT NOT NULL, target_type VARCHAR(255) NOT NULL, assignment_id INT NOT NULL, createdAt DATETIME NOT NULL, INDEX IDX_C213683089EEAF91 (assigned_to), INDEX IDX_C213683061A2AF17 (assigned_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE institution_programs ADD CONSTRAINT FK_EF2C69DBFFD315FB FOREIGN KEY (provided_by) REFERENCES institutions (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE logbook ADD CONSTRAINT FK_E96B931095561DEE FOREIGN KEY (verifier_id) REFERENCES verifiers (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE logbook ADD CONSTRAINT FK_E96B93102070ABE0 FOREIGN KEY (outpass_id) REFERENCES outpass_requests (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE outpass_requests ADD CONSTRAINT FK_5DF928845DA0FB8 FOREIGN KEY (template_id) REFERENCES outpass_templates (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE outpass_requests ADD CONSTRAINT FK_5DF92884CB944F1A FOREIGN KEY (student_id) REFERENCES students (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE outpass_requests ADD CONSTRAINT FK_5DF928844EA3CB3D FOREIGN KEY (approved_by) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE outpass_template_fields ADD CONSTRAINT FK_1A1543DD5DA0FB8 FOREIGN KEY (template_id) REFERENCES outpass_templates (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE parent_verifications ADD CONSTRAINT FK_500084D42070ABE0 FOREIGN KEY (outpass_id) REFERENCES outpass_requests (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE report_config_recipients ADD CONSTRAINT FK_F6FA6F5FA6F6B9B FOREIGN KEY (report_config_id) REFERENCES report_configs (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE report_config_recipients ADD CONSTRAINT FK_F6FA6F5FA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE students ADD CONSTRAINT FK_A4698DB2A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE students ADD CONSTRAINT FK_A4698DB2FC68ACC0 FOREIGN KEY (hostel_id) REFERENCES hostels (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE students ADD CONSTRAINT FK_A4698DB23EB8070A FOREIGN KEY (program_id) REFERENCES institution_programs (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE warden_assignments ADD CONSTRAINT FK_C213683089EEAF91 FOREIGN KEY (assigned_to) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE warden_assignments ADD CONSTRAINT FK_C213683061A2AF17 FOREIGN KEY (assigned_by) REFERENCES users (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE institution_programs DROP FOREIGN KEY FK_EF2C69DBFFD315FB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE logbook DROP FOREIGN KEY FK_E96B931095561DEE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE logbook DROP FOREIGN KEY FK_E96B93102070ABE0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE outpass_requests DROP FOREIGN KEY FK_5DF928845DA0FB8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE outpass_requests DROP FOREIGN KEY FK_5DF92884CB944F1A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE outpass_requests DROP FOREIGN KEY FK_5DF928844EA3CB3D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE outpass_template_fields DROP FOREIGN KEY FK_1A1543DD5DA0FB8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE parent_verifications DROP FOREIGN KEY FK_500084D42070ABE0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE report_config_recipients DROP FOREIGN KEY FK_F6FA6F5FA6F6B9B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE report_config_recipients DROP FOREIGN KEY FK_F6FA6F5FA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE students DROP FOREIGN KEY FK_A4698DB2A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE students DROP FOREIGN KEY FK_A4698DB2FC68ACC0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE students DROP FOREIGN KEY FK_A4698DB23EB8070A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE warden_assignments DROP FOREIGN KEY FK_C213683089EEAF91
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE warden_assignments DROP FOREIGN KEY FK_C213683061A2AF17
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE academic_years
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE hostels
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE institution_programs
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE institutions
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE jobs
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE logbook
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE outpass_requests
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE outpass_settings
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE outpass_template_fields
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE outpass_templates
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE parent_verifications
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE report_configs
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE report_config_recipients
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE settings
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE students
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE users
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE verifiers
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE warden_assignments
        SQL);
    }
}
