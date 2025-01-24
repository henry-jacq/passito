<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250124100816 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE outpass_settings (id INT AUTO_INCREMENT NOT NULL, dailyLimit INT DEFAULT NULL, weeklyLimit INT DEFAULT NULL, parentApproval TINYINT(1) NOT NULL, companionVerification TINYINT(1) NOT NULL, emergencyContactNotification TINYINT(1) NOT NULL, weekdayCollegeHoursStart TIME DEFAULT NULL, weekdayCollegeHoursEnd TIME DEFAULT NULL, weekdayOvernightStart TIME DEFAULT NULL, weekdayOvernightEnd TIME DEFAULT NULL, weekendStartTime TIME DEFAULT NULL, weekendEndTime TIME DEFAULT NULL, emailNotification TINYINT(1) NOT NULL, smsNotification TINYINT(1) NOT NULL, appNotification TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE outpass_settings');
    }
}
