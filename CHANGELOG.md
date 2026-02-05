# Changelog

## v0.5.0
- Added student management features including create/edit/delete, pagination, profile view, and search by name or digital ID.
- Refined admin UI structure with improved controller organization and header navigation.
- Renamed OutpassSettings to SystemSettings with a new verifier mode and removed daily/weekly limit fields.
- Enhanced data population scripts with super admin setup, domain-specific seeding, and conditional logic.
- Updated environment configuration defaults for JWT and CSRF settings.

## v0.4.0
- Added CSRF protection and JWT-based authentication across the app.
- Introduced seeders for academic years, hostels, and wardens; updated database seeding flow.
- Added AcademicYear and WardenAssignment entities and related assignment views/endpoints.
- Implemented CRUD for institutions, programs, hostels, wardens, and academic years with improved UI feedback.
- Enforced uniqueness for institution program short codes and refined error handling.
- Improved hostel/warden management with cascading deletes and cleaner field sets.
- Updated Vite config for CORS and refreshed Composer dependencies.
