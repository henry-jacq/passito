<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260210203021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Move outpass timing limits from system settings to outpass templates.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE outpass_templates ADD weekdayCollegeHoursStart TIME DEFAULT NULL');
        $this->addSql('ALTER TABLE outpass_templates ADD weekdayCollegeHoursEnd TIME DEFAULT NULL');
        $this->addSql('ALTER TABLE outpass_templates ADD weekdayOvernightStart TIME DEFAULT NULL');
        $this->addSql('ALTER TABLE outpass_templates ADD weekdayOvernightEnd TIME DEFAULT NULL');
        $this->addSql('ALTER TABLE outpass_templates ADD weekendStartTime TIME DEFAULT NULL');
        $this->addSql('ALTER TABLE outpass_templates ADD weekendEndTime TIME DEFAULT NULL');

        $this->addSql("UPDATE outpass_templates SET weekdayCollegeHoursStart = '09:00', weekdayCollegeHoursEnd = '17:00', weekdayOvernightStart = '22:00', weekdayOvernightEnd = '06:00', weekendStartTime = '09:00', weekendEndTime = '23:59' WHERE weekdayCollegeHoursStart IS NULL");
        $this->addSql("UPDATE outpass_templates SET weekdayOvernightStart = '20:00', weekendEndTime = '22:00' WHERE gender = 'female'");

        $this->addSql("DELETE FROM system_settings WHERE keyName IN ('outpass_weekday_college_hours_start', 'outpass_weekday_college_hours_end', 'outpass_weekday_overnight_start', 'outpass_weekday_overnight_end', 'outpass_weekend_start_time', 'outpass_weekend_end_time')");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE outpass_templates DROP weekdayCollegeHoursStart');
        $this->addSql('ALTER TABLE outpass_templates DROP weekdayCollegeHoursEnd');
        $this->addSql('ALTER TABLE outpass_templates DROP weekdayOvernightStart');
        $this->addSql('ALTER TABLE outpass_templates DROP weekdayOvernightEnd');
        $this->addSql('ALTER TABLE outpass_templates DROP weekendStartTime');
        $this->addSql('ALTER TABLE outpass_templates DROP weekendEndTime');
    }
}
