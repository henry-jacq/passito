#!/usr/bin/env bash
set -e

# Project: Passito - Hostel Outpass Management System
# Script: populate.sh
# Description: Populate the database with initial data for Passito.

echo "[!] Populating database with initial data..."

read -r -p "[?] Do you want to create a migration diff? (y/n): " diff
diff="${diff,,}"

if [[ "$diff" == "y" ]]; then
    php passito.php migrations:diff
else
    echo "[!] Skipping migration diff creation."
fi

read -r -p "[?] Do you want to seed super admins in CLI? (y/n): " setup_done
setup_done="${setup_done,,}"

read -r -p "[?] Do you want to seed domain-specific data? (y/n): " seed_domain
seed_domain="${seed_domain,,}"

echo -e "\n[!] Running migrations..."
php passito.php migrations:migrate
echo -e "[+] Migrations completed successfully.\n"

echo -e "[!] Seeding initial application data...\n"
php passito.php app:seed app_settings
php passito.php app:seed outpass_templates
php passito.php app:seed report_configs

if [[ "$seed_domain" == "y" ]]; then
    echo "[!] Seeding domain-specific data..."
    php passito.php app:seed institutions
    php passito.php app:seed hostels
    php passito.php app:seed programs
    php passito.php app:seed academic_years
    php passito.php app:seed wardens
else
    echo "[!] Skipping domain-specific data seeding."
fi

if [[ "$setup_done" == "y" ]]; then
    echo "[!] Creating super admin accounts..."
    php passito.php app:create-super-admin admin@passito.com admin@098 male
    php passito.php app:create-super-admin superadmin@passito.com superadmin@098 female
else
    echo "[!] Skipping super admin account creation."
fi

echo -e "\n[+] Database population process completed.\n"
