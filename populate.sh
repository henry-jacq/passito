#!/bin/bash

# This script populates the database with initial data.

echo "Populating database with initial data..."

read -p "Do you want to create a migration diff? (y/n): " diff

if [ "$diff" == "y" ]; then
    php passito.php migrations:diff
else
    echo "Skipping migration diff creation."
fi

echo "Running migrations..."

php passito.php migrations:migrate
php passito.php app:seed app_settings
php passito.php app:seed institutions
php passito.php app:seed outpass_rules
php passito.php app:seed outpass_templates
php passito.php app:seed programs

echo "Creating super admin accounts..."

php passito.php app:create-super-admin admin@passito.com admin@098 male
php passito.php app:create-super-admin superadmin@passito.com superadmin@098 female

echo "Database population complete."