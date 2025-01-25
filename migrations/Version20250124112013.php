<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration for adding default values to the OutpassSettings table
 */
final class Version20250124112013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds default values to the outpass_settings table for both genders.';
    }

    public function up(Schema $schema): void
    {
        // Insert default values for male
        $this->addSql("
            INSERT INTO outpass_settings (
                dailyLimit,
                weeklyLimit,
                type,
                parentApproval,
                companionVerification,
                emergencyContactNotification,
                weekdayCollegeHoursStart,
                weekdayCollegeHoursEnd,
                weekdayOvernightStart,
                weekdayOvernightEnd,
                weekendStartTime,
                weekendEndTime,
                emailNotification,
                smsNotification,
                appNotification
            ) VALUES (
                NULL, -- dailyLimit
                NULL, -- weeklyLimit
                'male', -- type
                false, -- parentApproval
                false, -- companionVerification
                false, -- emergencyContactNotification
                '09:00:00', -- weekdayCollegeHoursStart
                '17:00:00', -- weekdayCollegeHoursEnd
                '22:00:00', -- weekdayOvernightStart
                '06:00:00', -- weekdayOvernightEnd
                '09:00:00', -- weekendStartTime
                '23:59:59', -- weekendEndTime
                true, -- emailNotification
                false, -- smsNotification
                true -- appNotification
            )
        ");

        // Insert default values for female
        $this->addSql("
            INSERT INTO outpass_settings (
                dailyLimit,
                weeklyLimit,
                type,
                parentApproval,
                companionVerification,
                emergencyContactNotification,
                weekdayCollegeHoursStart,
                weekdayCollegeHoursEnd,
                weekdayOvernightStart,
                weekdayOvernightEnd,
                weekendStartTime,
                weekendEndTime,
                emailNotification,
                smsNotification,
                appNotification
            ) VALUES (
                NULL, -- dailyLimit
                NULL, -- weeklyLimit
                'female', -- type
                false, -- parentApproval
                false, -- companionVerification
                true, -- emergencyContactNotification (specific to female)
                '09:00:00', -- weekdayCollegeHoursStart
                '17:00:00', -- weekdayCollegeHoursEnd
                '20:00:00', -- weekdayOvernightStart (specific to female)
                '06:00:00', -- weekdayOvernightEnd
                '09:00:00', -- weekendStartTime
                '22:00:00', -- weekendEndTime (specific to female)
                true, -- emailNotification
                true, -- smsNotification (specific to female)
                true -- appNotification
            )
        ");
    }

    public function down(Schema $schema): void
    {
        // Delete default values for male and female
        $this->addSql("
            DELETE FROM outpass_settings WHERE 
                type IN ('male', 'female')
        ");
    }
}
