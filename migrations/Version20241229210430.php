<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241229210430 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds default values to the settings table.';
    }

    public function up(Schema $schema): void
    {
        // Insert default values into the 'settings' table
        $this->addSql("
            INSERT INTO settings (keyName, value, updatedAt) VALUES 
            ('setup_complete', 'false', NOW()),
            ('admin_created', 'false', NOW()),
            ('app_name', 'Passito', NOW()),
            ('email_settings', '{}', NOW()),
            ('max_outpasses_per_day', '2', NOW())
        ");
    }

    public function down(Schema $schema): void
    {
        // Delete the inserted default values from the 'settings' table
        $this->addSql("
            DELETE FROM settings WHERE keyName IN (
                'setup_complete',
                'admin_created',
                'app_name',
                'email_settings',
                'max_outpasses_per_day'
            )
        ");
    }
}
