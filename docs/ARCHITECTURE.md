# Architecture Overview

This document provides a comprehensive overview of Passito's system architecture, design patterns, and technical implementation.

## System Architecture

Passito follows a layered architecture pattern with clear separation of concerns:

```
┌─────────────────────────────────────────────────────────────┐
│                     Presentation Layer                       │
│  (Views, Templates, JavaScript, CSS)                        │
└──────────────────────┬──────────────────────────────────────┘
                       │
┌──────────────────────┴──────────────────────────────────────┐
│                     Application Layer                        │
│  (Controllers, Middleware, Request/Response Handling)       │
└──────────────────────┬──────────────────────────────────────┘
                       │
┌──────────────────────┴──────────────────────────────────────┐
│                      Business Layer                          │
│  (Services, DTOs, Business Logic)                           │
└──────────────────────┬──────────────────────────────────────┘
                       │
┌──────────────────────┴──────────────────────────────────────┐
│                       Data Layer                             │
│  (Entities, Repositories, Database)                         │
└─────────────────────────────────────────────────────────────┘
```

## Core Components

### 1. Application Core

**Location**: `app/Core/`

The core provides fundamental application services:

- **Config**: Configuration management and environment variable access
- **Request**: HTTP request abstraction and handling
- **Session**: Session management and flash messages
- **View**: Template rendering and layout management
- **Storage**: File system operations using Flysystem
- **JobDispatcher**: Job queue management
- **JobPayloadBuilder**: Type-safe job payload construction

### 2. Controllers

**Location**: `app/Controller/`

Controllers handle HTTP requests and coordinate between services:

- **BaseController**: Common functionality for all controllers
- **AuthController**: Authentication and authorization
- **StudentController**: Student-specific operations
- **AdminController**: Administrative functions
- **VerifierController**: Outpass verification
- **ApiController**: REST API endpoints
- **StorageController**: File serving and downloads

**Pattern**: Controllers are thin, delegating business logic to services.

### 3. Services

**Location**: `app/Services/`

Services contain business logic and coordinate between entities:

- **AuthService**: Authentication, login, logout
- **UserService**: User management operations
- **OutpassService**: Outpass creation, approval, rejection
- **AdminService**: Administrative operations
- **VerifierService**: Verification logic
- **MailService**: Email sending via SMTP
- **SMSService**: SMS sending via Twilio
- **JwtService**: JWT token generation and validation
- **AcademicService**: Academic year and program management
- **ReportService**: Report generation
- **SystemSettingsService**: System configuration
- **ParentVerificationService**: Parent approval workflow
- **WebSocketService**: Real-time notifications

### 4. Entities

**Location**: `app/Entity/`

Entities represent database tables using Doctrine ORM:

- **User**: System users (students, wardens, admins)
- **Student**: Student-specific information
- **Verifier**: Verifier accounts
- **OutpassRequest**: Outpass records
- **OutpassTemplate**: Customizable outpass templates
- **OutpassTemplateField**: Template field definitions
- **Institution**: Educational institutions
- **InstitutionProgram**: Academic programs
- **Hostel**: Hostel information
- **WardenAssignment**: Warden-hostel relationships
- **AcademicYear**: Academic year definitions
- **ParentVerification**: Parent approval records
- **Logbook**: Entry/exit logging
- **Job**: Job queue records
- **SystemSettings**: System configuration
- **ReportConfig**: Report generation settings

### 5. Data Transfer Objects (DTOs)

**Location**: `app/Dto/`

DTOs provide type-safe data transfer with validation:

```php
class CreateOutpassDto
{
    private function __construct(
        public readonly int $studentId,
        public readonly string $purpose,
        public readonly \DateTimeImmutable $fromDate,
        public readonly \DateTimeImmutable $toDate,
        public readonly ?string $destination,
        public readonly ?string $remarks
    ) {}

    public static function fromArray(array $data): self
    {
        // Validation and construction
    }
}
```

**Benefits**:
- Type safety
- Centralized validation
- Immutability
- Clear contracts

### 6. Middleware

**Location**: `app/Middleware/`

Middleware processes requests before reaching controllers:

- **SessionStartMiddleware**: Initializes sessions
- **AuthMiddleware**: Verifies authentication
- **CsrfMiddleware**: CSRF token validation
- **AdminMiddleware**: Admin role verification
- **StudentMiddleware**: Student role verification
- **VerifierMiddleware**: Verifier role verification
- **SuperAdminMiddleware**: Super admin verification
- **SetupMiddleware**: Setup wizard access control
- **MaintenanceMiddleware**: Maintenance mode handling
- **TrailingSlashMiddleware**: URL normalization
- **ApiMiddleware**: API request handling

### 7. Job System

**Location**: `app/Jobs/`, `app/Command/`

Asynchronous job processing system:

**Jobs**:
- **GenerateQrCode**: QR code generation
- **GenerateOutpassPdf**: PDF document creation
- **SendOutpassEmail**: Email notifications
- **SendParentApproval**: Parent verification emails
- **CleanupExpiredFiles**: File cleanup tasks

**Commands**:
- **JobWorkerCommand**: Single job worker
- **JobSupervisorCommand**: Dynamic worker management
- **JobHealthCheckCommand**: Health monitoring

See [Job System Overview](JOB_SYSTEM_OVERVIEW.md) for details.

## Design Patterns

### 1. Dependency Injection

All components use constructor injection via PHP-DI:

```php
class OutpassService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly MailService $mailService,
        private readonly JobDispatcher $jobDispatcher
    ) {}
}
```

### 2. Repository Pattern

Doctrine ORM provides repository pattern for data access:

```php
$repository = $em->getRepository(OutpassRequest::class);
$outpass = $repository->find($id);
```

### 3. Service Layer Pattern

Business logic is encapsulated in services:

```php
// Controller
$outpass = $this->outpassService->createOutpass($dto);

// Service
public function createOutpass(CreateOutpassDto $dto): OutpassRequest
{
    // Business logic here
}
```

### 4. DTO Pattern

Data transfer between layers uses DTOs:

```php
$dto = CreateOutpassDto::fromArray($requestData);
$outpass = $outpassService->createOutpass($dto);
```

### 5. Factory Pattern

Static factory methods for object creation:

```php
$dto = CreateOutpassDto::fromArray($data);
$job = JobPayloadBuilder::create()
    ->set('key', 'value')
    ->dependsOn($jobId);
```

### 6. Command Pattern

CLI commands for system operations:

```php
class JobWorkerCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Command logic
    }
}
```

### 7. Observer Pattern

WebSocket notifications for real-time updates:

```php
$this->webSocketService->broadcast('outpass.approved', $data);
```

## Request Flow

### Web Request Flow

```
1. HTTP Request
   ↓
2. Apache/Nginx → public/index.php
   ↓
3. Bootstrap (bootstrap.php)
   ↓
4. Middleware Stack
   ↓
5. Router (routes/web.php)
   ↓
6. Controller
   ↓
7. Service Layer
   ↓
8. Entity/Repository
   ↓
9. Database
   ↓
10. Response (View/JSON)
```

### Job Processing Flow

```
1. Job Dispatch
   ↓
2. JobDispatcher → Database
   ↓
3. Job Supervisor monitors queue
   ↓
4. Worker spawned/assigned
   ↓
5. Worker fetches job (SELECT FOR UPDATE SKIP LOCKED)
   ↓
6. Job Handler executes
   ↓
7. Result stored
   ↓
8. Job marked complete/failed
```

## Database Design

### Entity Relationships

```
User (1) ──────────── (1) Student
  │
  └─ (1) ──────────── (N) OutpassRequest
  
Institution (1) ──── (N) InstitutionProgram
     │
     └─ (1) ──────── (N) Student

Hostel (1) ─────────── (N) Student
  │
  └─ (N) ──────────── (N) WardenAssignment ──── (1) User

OutpassRequest (1) ── (1) ParentVerification
```

### Key Tables

- **users**: System users with role-based access
- **students**: Student profiles and academic information
- **outpass_requests**: Outpass records with status tracking
- **jobs**: Job queue for asynchronous processing
- **logbook**: Entry/exit audit trail
- **system_settings**: Application configuration

See [Database Schema](DATABASE.md) for complete details.

## Security Architecture

### Authentication

- **Session-based**: Web interface uses PHP sessions
- **JWT-based**: API uses JSON Web Tokens
- **CSRF Protection**: All state-changing operations require CSRF tokens

### Authorization

Role-based access control (RBAC):

- **Student**: Create outpasses, view own records
- **Warden**: Approve/reject outpasses for assigned hostels
- **Verifier**: Scan and verify outpasses
- **Admin**: Manage users, hostels, settings
- **Super Admin**: Full system access, setup wizard

### Data Protection

- **Password Hashing**: bcrypt with cost factor 12
- **SQL Injection**: Parameterized queries via Doctrine
- **XSS Prevention**: Output escaping in templates
- **CSRF Tokens**: Synchronized token pattern
- **File Upload**: Type and size validation

## Scalability Considerations

### Horizontal Scaling

- **Stateless Application**: Sessions stored in database/Redis
- **Load Balancing**: Multiple application servers supported
- **Database Replication**: Read replicas for reporting
- **Job Workers**: Multiple workers across servers

### Vertical Scaling

- **Database Optimization**: Indexes on frequently queried columns
- **Query Optimization**: Eager loading to prevent N+1 queries
- **Caching**: Opcache for PHP, query result caching
- **Asset Optimization**: Minified CSS/JS, CDN for static assets

### Performance Optimization

- **Job Queue**: Offload heavy operations (PDF, QR, email)
- **Database Indexes**: Optimized for common queries
- **Lazy Loading**: Entities loaded on demand
- **Connection Pooling**: Persistent database connections

## Monitoring and Observability

### Health Checks

- **Job Queue Health**: Detects stale/stuck jobs
- **Database Connectivity**: Connection pool monitoring
- **Email Service**: SMTP connection validation
- **Storage**: Disk space monitoring

### Logging

- **Application Logs**: Error and debug logging
- **Access Logs**: Apache/Nginx request logs
- **Job Logs**: Worker output and errors
- **Audit Trail**: Logbook for all outpass operations

### Metrics

- **Job Queue**: Pending, processing, failed counts
- **Outpass Statistics**: Created, approved, rejected
- **User Activity**: Login attempts, active sessions
- **System Resources**: CPU, memory, disk usage

## Technology Choices

### Why PHP?

- Mature ecosystem with extensive libraries
- Excellent ORM support (Doctrine)
- Easy deployment on shared hosting
- Strong community and documentation

### Why Slim Framework?

- Lightweight and fast
- PSR-7 compliant
- Flexible middleware system
- Easy to learn and extend

### Why Doctrine ORM?

- Database abstraction
- Migration support
- Query builder and DQL
- Entity relationships

### Why Custom Job Queue?

- No external dependencies (Redis, RabbitMQ)
- Database-backed persistence
- Simple deployment
- Full control over implementation

## Future Enhancements

### Planned Features

- **Mobile Application**: Native iOS/Android apps
- **Biometric Verification**: Fingerprint/face recognition
- **Advanced Analytics**: Machine learning for pattern detection
- **Multi-tenancy**: Support multiple institutions
- **API Gateway**: Centralized API management
- **Microservices**: Split into smaller services

### Technical Improvements

- **Event Sourcing**: Complete audit trail
- **CQRS**: Separate read/write models
- **GraphQL API**: Flexible data querying
- **Redis Caching**: Improved performance
- **Elasticsearch**: Advanced search capabilities
- **Kubernetes**: Container orchestration

## Conclusion

Passito's architecture is designed for maintainability, scalability, and security. The layered approach with clear separation of concerns makes the codebase easy to understand and extend. The job queue system provides reliable asynchronous processing, while the health monitoring ensures system reliability.

For specific implementation details, refer to the relevant documentation sections.
