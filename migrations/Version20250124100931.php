<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration for adding default values to the OutpassSettings table
 */
final class Version20250124100931 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds default values to the outpass_settings table.';
    }

    public function up(Schema $schema): void
    {
        // Insert default values into the 'outpass_settings' table
        $this->addSql("
            INSERT INTO outpass_settings (
                dailyLimit,
                weeklyLimit,
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
                NULL, -- dailyLimit (optional)
                NULL, -- weeklyLimit (optional)
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
    }

    public function down(Schema $schema): void
    {
        // Delete the default values from the 'outpass_settings' table
        $this->addSql("
            DELETE FROM outpass_settings WHERE 
                dailyLimit IS NULL AND
                weeklyLimit IS NULL AND
                parentApproval = false AND
                companionVerification = false AND
                emergencyContactNotification = false AND
                weekdayCollegeHoursStart = '09:00:00' AND
                weekdayCollegeHoursEnd = '17:00:00' AND
                weekdayOvernightStart = '22:00:00' AND
                weekdayOvernightEnd = '06:00:00' AND
                weekendStartTime = '09:00:00' AND
                weekendEndTime = '23:59:59' AND
                emailNotification = true AND
                smsNotification = false AND
                appNotification = true
        ");
    }
}
