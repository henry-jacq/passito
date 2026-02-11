# Changelog

## v0.7.0 (2026-02-11)

### 🚀 Major Features
- **Resource Management System**: Added secure file access and resource management with proper authentication and authorization
- **Template Deletion**: Implemented template deletion functionality with proper validation and cleanup
- **Job Queue Health Monitoring**: Added comprehensive health check command and async cleanup processing
- **Job Supervisor**: Implemented dynamic worker process management for job queue

### ⚡ Performance Optimizations
- **Job Processing**: Consolidated outpass generation workflow from 3 sequential jobs into single `ProcessApprovedOutpass` job
  - Eliminated job dependency overhead and result tracking
  - Reduced database writes and queue processing time
  - Streamlined QR code generation, PDF creation, and email sending into one atomic operation

### 🔧 Refactoring
- **Entity Getters/Setters**: Removed 1,172 lines of boilerplate getter/setter code across 16 entity classes
  - Implemented magic `__call()` methods in `EntityGetSetTrait` for dynamic property access
  - Added explicit type casting for data consistency
  - Maintained backward compatibility while improving code maintainability
- **Job Dependencies**: Removed job dependencies and result tracking system for simpler architecture
- **Command Naming**: Renamed `RemoveExpiredOutpassCommand` to `CleanupExpiredFilesCommand` for clarity
- **Seeders**: Consolidated `OutpassRulesSeeder` into `AppSettingsSeeder` for better organization
- **Template System**: Moved outpass timing rules from settings to templates for better flexibility

### 📚 Documentation
- **Comprehensive API Documentation**: Added detailed API endpoint documentation with request/response examples
- **Job System Documentation**: Created extensive job queue documentation with health monitoring guide
- **System Architecture**: Added architecture documentation and implementation summaries

### 🏗️ Infrastructure
- **WebSocket Service**: Added systemd service file for WebSocket server deployment
- **Deployment Configuration**: Added supervisor configuration for production deployments

### 🐛 Bug Fixes
- **Session Dependency**: Restored Session dependency in AdminService for bulkUpload method
- **Type Handling**: Improved type handling in AppSettingsSeeder for better data consistency

### 🎨 UI/UX Improvements
- **Template Management**: Updated templates with improved API endpoints and UI updates
- **Admin Interface**: Enhanced admin dashboard and management interfaces

### 📝 Code Quality
- Improved type safety across entity classes
- Enhanced error handling and logging
- Better separation of concerns in job processing
- Cleaner codebase with reduced redundancy

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
