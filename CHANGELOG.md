# Changelog

## v0.6.0 (2026-02-08)

### 🎯 Major Features
- **Data Transfer Objects (DTOs)**: Introduced `CreateOutpassDto` for type-safe outpass creation with built-in validation
- **QR Code Scanning**: Implemented QR code scanning functionality for outpass verification using ZXing library
- **Manual Verifier Management**: Added comprehensive manual verifier management system with dashboard and controls

### ✨ Enhancements
- **Search & Filtering**: Added search, filter, and date filtering capabilities across:
  - Pending outpass records
  - Outpass records (with status filtering)
  - Logbook entries (with action filtering)
- **Pagination**: Implemented pagination for student outpass history
- **Student Dashboard**: Added outpass statistics display on student dashboard
- **Report Improvements**: Enhanced report generation with date filtering and better error handling for empty datasets

### 🔧 Refactoring
- **Settings Migration**: Migrated from Settings entity to SystemSettingsService for better configuration management
- **Code Cleanup**: Removed unused dependency injections and imports across services, controllers, and middleware
- **User Status Management**: Consolidated verifier status management and improved user status handling
- **Middleware Enhancement**: Preserved redirect URI on authentication failures for better UX
- **DTO Simplification**: Removed WardenAssignmentView DTO in favor of simpler data structures
- **JavaScript Organization**: Moved inline JavaScript code to appropriate main JS files

### 🐛 Bug Fixes
- Fixed profile error for warden assignment retrieval
- Fixed expired outpass cleanup command to focus on document removal
- Improved error handling for report generation with proper status codes (422 for no data)
- Enhanced AJAX error response parsing to extract messages from JSON or text responses

### 🏗️ Technical Improvements
- **Type Safety**: Implemented DTOs with readonly properties and static factory methods
- **Validation**: Centralized validation logic in DTOs for consistency
- **Build Configuration**: Added verifier and setup entry points to Vite config
- **Dependencies**: Added ZXing browser library for QR code functionality

### 📝 Code Quality
- Normalized code formatting across the codebase
- Improved error messages and validation feedback
- Enhanced IDE support with better type hints and autocomplete
- Removed duplicate methods and consolidated logic

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
