#!/bin/bash

# This script populates the database with initial data.

echo "Populating database with initial data..."

read -p "Do you want to create a migration diff? (y/n): " diff

if [ "$diff" == "y" ]; then
    php passito.php migrations:diff
else
    echo "Skipping migration diff creation."
fi

read -p "Do you want to setup super admins in CLI? (y/n): " setup_done

read -p "Do you want to seed domain-specific data? (y/n): " seed_domain

echo "Running migrations..."
php passito.php migrations:migrate


if [ "$setup_done" != "y" ]; then
    echo "Creating super admin accounts..."
    php passito.php app:create-super-admin admin@passito.com admin@098 male
    php passito.php app:create-super-admin superadmin@passito.com superadmin@098 female
fi

echo "Seeding initial application data..."
php passito.php app:seed app_settings
php passito.php app:seed outpass_rules
php passito.php app:seed outpass_templates
php passito.php app:seed report_configs


if [ "$seed_domain" != "y" || "$seed_domain" != "" ]; then
    echo "Skipping domain-specific data seeding."
    echo "Data population completed."
    exit 0
fi

echo "Seeding domain-specific data..."
php passito.php app:seed institutions
php passito.php app:seed hostels
php passito.php app:seed programs
php passito.php app:seed academic_years
php passito.php app:seed wardens

echo "Data population completed."
