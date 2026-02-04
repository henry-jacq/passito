# Changelog

## v0.4.0
- Added CSRF protection and JWT-based authentication across the app.
- Introduced seeders for academic years, hostels, and wardens; updated database seeding flow.
- Added AcademicYear and WardenAssignment entities and related assignment views/endpoints.
- Implemented CRUD for institutions, programs, hostels, wardens, and academic years with improved UI feedback.
- Enforced uniqueness for institution program short codes and refined error handling.
- Improved hostel/warden management with cascading deletes and cleaner field sets.
- Updated Vite config for CORS and refreshed Composer dependencies.
