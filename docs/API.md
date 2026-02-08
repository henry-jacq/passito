# API Reference

This document provides comprehensive documentation for Passito's REST API endpoints.

## Base URL

```
Production: https://your-domain.com/api
Development: http://localhost:8000/api
```

## Authentication

Passito API uses JWT (JSON Web Token) for authentication.

### Obtaining a Token

**Endpoint**: `POST /api/auth/login`

**Request**:
```json
{
  "username": "student@example.com",
  "password": "password123"
}
```

**Response**:
```json
{
  "success": true,
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "user": {
    "id": 1,
    "username": "student@example.com",
    "role": "student"
  }
}
```

### Using the Token

Include the token in the Authorization header:

```
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc...
```

## Response Format

### Success Response

```json
{
  "success": true,
  "data": {
    // Response data
  },
  "message": "Operation successful"
}
```

### Error Response

```json
{
  "success": false,
  "error": "Error message",
  "code": 400
}
```

## HTTP Status Codes

- `200 OK`: Request successful
- `201 Created`: Resource created successfully
- `400 Bad Request`: Invalid request data
- `401 Unauthorized`: Authentication required
- `403 Forbidden`: Insufficient permissions
- `404 Not Found`: Resource not found
- `422 Unprocessable Entity`: Validation failed
- `500 Internal Server Error`: Server error

## Endpoints

### Authentication

#### Login

```http
POST /api/auth/login
```

**Request Body**:
```json
{
  "username": "string",
  "password": "string"
}
```

**Response**: `200 OK`
```json
{
  "success": true,
  "token": "string",
  "user": {
    "id": "integer",
    "username": "string",
    "role": "string"
  }
}
```

#### Logout

```http
POST /api/auth/logout
```

**Headers**: `Authorization: Bearer {token}`

**Response**: `200 OK`
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

#### Verify Token

```http
GET /api/auth/verify
```

**Headers**: `Authorization: Bearer {token}`

**Response**: `200 OK`
```json
{
  "success": true,
  "valid": true,
  "user": {
    "id": "integer",
    "username": "string",
    "role": "string"
  }
}
```

### Outpass Management

#### Get Outpass List

```http
GET /api/outpasses
```

**Headers**: `Authorization: Bearer {token}`

**Query Parameters**:
- `status` (optional): Filter by status (pending, approved, rejected, expired)
- `page` (optional): Page number (default: 1)
- `limit` (optional): Items per page (default: 20)
- `from_date` (optional): Filter from date (YYYY-MM-DD)
- `to_date` (optional): Filter to date (YYYY-MM-DD)

**Response**: `200 OK`
```json
{
  "success": true,
  "data": {
    "outpasses": [
      {
        "id": 1,
        "student_id": 123,
        "student_name": "John Doe",
        "purpose": "Home visit",
        "from_date": "2026-02-10 10:00:00",
        "to_date": "2026-02-12 18:00:00",
        "status": "approved",
        "created_at": "2026-02-09 14:30:00"
      }
    ],
    "pagination": {
      "current_page": 1,
      "total_pages": 5,
      "total_items": 100,
      "items_per_page": 20
    }
  }
}
```

#### Get Outpass Details

```http
GET /api/outpasses/{id}
```

**Headers**: `Authorization: Bearer {token}`

**Response**: `200 OK`
```json
{
  "success": true,
  "data": {
    "id": 1,
    "student": {
      "id": 123,
      "name": "John Doe",
      "digital_id": "STU001",
      "program": "Computer Science",
      "year": 2
    },
    "purpose": "Home visit",
    "destination": "Chennai",
    "from_date": "2026-02-10 10:00:00",
    "to_date": "2026-02-12 18:00:00",
    "status": "approved",
    "approved_by": "Dr. Smith",
    "approved_at": "2026-02-09 15:00:00",
    "remarks": "Approved for family emergency",
    "qr_code": "/storage/qr_codes/qrcode_abc123.png",
    "pdf_document": "/storage/outpasses/outpass_123.pdf",
    "created_at": "2026-02-09 14:30:00",
    "updated_at": "2026-02-09 15:00:00"
  }
}
```

#### Create Outpass

```http
POST /api/outpasses
```

**Headers**: 
- `Authorization: Bearer {token}`
- `Content-Type: application/json`

**Request Body**:
```json
{
  "purpose": "Home visit",
  "destination": "Chennai",
  "from_date": "2026-02-10 10:00:00",
  "to_date": "2026-02-12 18:00:00",
  "remarks": "Family emergency"
}
```

**Response**: `201 Created`
```json
{
  "success": true,
  "data": {
    "id": 1,
    "status": "pending",
    "message": "Outpass request created successfully"
  }
}
```

#### Update Outpass Status

```http
PATCH /api/outpasses/{id}/status
```

**Headers**: 
- `Authorization: Bearer {token}`
- `Content-Type: application/json`

**Request Body**:
```json
{
  "status": "approved",
  "remarks": "Approved by warden"
}
```

**Response**: `200 OK`
```json
{
  "success": true,
  "message": "Outpass status updated successfully"
}
```

#### Delete Outpass

```http
DELETE /api/outpasses/{id}
```

**Headers**: `Authorization: Bearer {token}`

**Response**: `200 OK`
```json
{
  "success": true,
  "message": "Outpass deleted successfully"
}
```

### Student Management

#### Get Student Profile

```http
GET /api/students/{id}
```

**Headers**: `Authorization: Bearer {token}`

**Response**: `200 OK`
```json
{
  "success": true,
  "data": {
    "id": 123,
    "digital_id": "STU001",
    "name": "John Doe",
    "email": "john.doe@example.com",
    "phone": "+919876543210",
    "gender": "male",
    "date_of_birth": "2004-05-15",
    "institution": "ABC University",
    "program": "Computer Science",
    "year": 2,
    "hostel": "Block A",
    "room_number": "A-201",
    "parent_name": "Jane Doe",
    "parent_phone": "+919876543211",
    "parent_email": "jane.doe@example.com",
    "created_at": "2025-08-01 10:00:00"
  }
}
```

#### Update Student Profile

```http
PATCH /api/students/{id}
```

**Headers**: 
- `Authorization: Bearer {token}`
- `Content-Type: application/json`

**Request Body**:
```json
{
  "phone": "+919876543210",
  "room_number": "A-202",
  "parent_phone": "+919876543211"
}
```

**Response**: `200 OK`
```json
{
  "success": true,
  "message": "Profile updated successfully"
}
```

### Verification

#### Verify Outpass by QR Code

```http
POST /api/verify/qr
```

**Headers**: 
- `Authorization: Bearer {token}`
- `Content-Type: application/json`

**Request Body**:
```json
{
  "qr_data": "encrypted_qr_code_data",
  "action": "check_out"
}
```

**Response**: `200 OK`
```json
{
  "success": true,
  "data": {
    "outpass_id": 1,
    "student_name": "John Doe",
    "purpose": "Home visit",
    "valid_until": "2026-02-12 18:00:00",
    "status": "verified",
    "action": "check_out",
    "timestamp": "2026-02-10 10:30:00"
  }
}
```

#### Get Verification History

```http
GET /api/verify/history
```

**Headers**: `Authorization: Bearer {token}`

**Query Parameters**:
- `from_date` (optional): Start date (YYYY-MM-DD)
- `to_date` (optional): End date (YYYY-MM-DD)
- `action` (optional): Filter by action (check_in, check_out)

**Response**: `200 OK`
```json
{
  "success": true,
  "data": {
    "verifications": [
      {
        "id": 1,
        "outpass_id": 123,
        "student_name": "John Doe",
        "action": "check_out",
        "verified_by": "Security Guard",
        "timestamp": "2026-02-10 10:30:00"
      }
    ]
  }
}
```

### Reports

#### Generate Daily Movement Report

```http
POST /api/reports/daily-movement
```

**Headers**: 
- `Authorization: Bearer {token}`
- `Content-Type: application/json`

**Request Body**:
```json
{
  "date": "2026-02-10",
  "hostel_id": 1
}
```

**Response**: `200 OK`
```json
{
  "success": true,
  "data": {
    "report_url": "/storage/reports/daily_movement_2026-02-10.pdf",
    "summary": {
      "total_outpasses": 45,
      "checked_out": 40,
      "checked_in": 35,
      "pending_return": 5
    }
  }
}
```

#### Generate Late Arrivals Report

```http
POST /api/reports/late-arrivals
```

**Headers**: 
- `Authorization: Bearer {token}`
- `Content-Type: application/json`

**Request Body**:
```json
{
  "from_date": "2026-02-01",
  "to_date": "2026-02-10",
  "hostel_id": 1
}
```

**Response**: `200 OK`
```json
{
  "success": true,
  "data": {
    "report_url": "/storage/reports/late_arrivals_2026-02.pdf",
    "summary": {
      "total_late_arrivals": 12,
      "students_affected": 8
    }
  }
}
```

### Statistics

#### Get Dashboard Statistics

```http
GET /api/statistics/dashboard
```

**Headers**: `Authorization: Bearer {token}`

**Response**: `200 OK`
```json
{
  "success": true,
  "data": {
    "total_outpasses": 150,
    "pending_approvals": 12,
    "active_outpasses": 25,
    "expired_outpasses": 113,
    "approval_rate": 85.5,
    "recent_activity": [
      {
        "type": "outpass_created",
        "student": "John Doe",
        "timestamp": "2026-02-09 14:30:00"
      }
    ]
  }
}
```

#### Get Student Statistics

```http
GET /api/statistics/student/{id}
```

**Headers**: `Authorization: Bearer {token}`

**Response**: `200 OK`
```json
{
  "success": true,
  "data": {
    "total_outpasses": 15,
    "approved": 12,
    "rejected": 2,
    "pending": 1,
    "average_duration": "2.5 days",
    "most_common_purpose": "Home visit"
  }
}
```

### System Settings

#### Get System Settings

```http
GET /api/settings
```

**Headers**: `Authorization: Bearer {token}`

**Response**: `200 OK`
```json
{
  "success": true,
  "data": {
    "max_outpass_duration": 7,
    "advance_booking_days": 3,
    "require_parent_approval": true,
    "verifier_mode": "manual",
    "maintenance_mode": false
  }
}
```

#### Update System Settings

```http
PATCH /api/settings
```

**Headers**: 
- `Authorization: Bearer {token}`
- `Content-Type: application/json`

**Request Body**:
```json
{
  "max_outpass_duration": 10,
  "require_parent_approval": false
}
```

**Response**: `200 OK`
```json
{
  "success": true,
  "message": "Settings updated successfully"
}
```

## Webhooks

Passito can send webhook notifications for specific events.

### Webhook Events

- `outpass.created`: New outpass request created
- `outpass.approved`: Outpass approved
- `outpass.rejected`: Outpass rejected
- `outpass.expired`: Outpass expired
- `verification.check_out`: Student checked out
- `verification.check_in`: Student checked in

### Webhook Payload

```json
{
  "event": "outpass.approved",
  "timestamp": "2026-02-09T15:00:00Z",
  "data": {
    "outpass_id": 123,
    "student_id": 456,
    "status": "approved",
    "approved_by": "Dr. Smith"
  }
}
```

### Configuring Webhooks

Webhooks are configured in the admin panel under Settings > Integrations.

## Rate Limiting

API requests are rate-limited to prevent abuse:

- **Authenticated requests**: 1000 requests per hour
- **Unauthenticated requests**: 100 requests per hour

Rate limit headers are included in responses:

```
X-RateLimit-Limit: 1000
X-RateLimit-Remaining: 999
X-RateLimit-Reset: 1644422400
```

## Error Codes

| Code | Description |
|------|-------------|
| 1001 | Invalid credentials |
| 1002 | Token expired |
| 1003 | Invalid token |
| 2001 | Outpass not found |
| 2002 | Invalid outpass status |
| 2003 | Outpass already processed |
| 3001 | Student not found |
| 3002 | Invalid student data |
| 4001 | Verification failed |
| 4002 | QR code invalid |
| 5001 | Permission denied |
| 5002 | Resource not found |
| 9999 | Internal server error |

## SDK and Libraries

### PHP SDK

```php
use Passito\SDK\Client;

$client = new Client([
    'base_url' => 'https://your-domain.com/api',
    'token' => 'your-jwt-token'
]);

// Get outpasses
$outpasses = $client->outpasses()->list([
    'status' => 'pending',
    'page' => 1
]);

// Create outpass
$outpass = $client->outpasses()->create([
    'purpose' => 'Home visit',
    'from_date' => '2026-02-10 10:00:00',
    'to_date' => '2026-02-12 18:00:00'
]);
```

### JavaScript SDK

```javascript
import PassitoClient from '@passito/sdk';

const client = new PassitoClient({
  baseUrl: 'https://your-domain.com/api',
  token: 'your-jwt-token'
});

// Get outpasses
const outpasses = await client.outpasses.list({
  status: 'pending',
  page: 1
});

// Create outpass
const outpass = await client.outpasses.create({
  purpose: 'Home visit',
  fromDate: '2026-02-10 10:00:00',
  toDate: '2026-02-12 18:00:00'
});
```

## Testing

### Postman Collection

Import the Postman collection for easy API testing:

```bash
curl -o passito-api.postman_collection.json \
  https://raw.githubusercontent.com/henry-jacq/passito/main/docs/postman/collection.json
```

### cURL Examples

**Login**:
```bash
curl -X POST https://your-domain.com/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"username":"student@example.com","password":"password123"}'
```

**Get Outpasses**:
```bash
curl -X GET https://your-domain.com/api/outpasses \
  -H "Authorization: Bearer YOUR_TOKEN"
```

**Create Outpass**:
```bash
curl -X POST https://your-domain.com/api/outpasses \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "purpose":"Home visit",
    "from_date":"2026-02-10 10:00:00",
    "to_date":"2026-02-12 18:00:00"
  }'
```

## Support

For API support:

- Email: api-support@passito.local
- Documentation: https://docs.passito.local
- GitHub Issues: https://github.com/henry-jacq/passito/issues

## Changelog

See [API Changelog](API_CHANGELOG.md) for version history and breaking changes.
