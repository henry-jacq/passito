# Database Schema

This document provides comprehensive documentation of Passito's database structure, relationships, and design decisions.

## Overview

Passito uses MySQL/MariaDB with Doctrine ORM for database management. The schema is designed for:

- Data integrity through foreign key constraints
- Performance through strategic indexing
- Flexibility for future enhancements
- Clear entity relationships

## Entity Relationship Diagram

```
┌──────────────┐         ┌──────────────┐
│    users     │────────▶│   students   │
│              │   1:1   │              │
└──────┬───────┘         └──────┬───────┘
       │                        │
       │ 1:N                    │ N:1
       │                        │
       ▼                        ▼
┌──────────────┐         ┌──────────────┐
│   verifiers  │         │   hostels    │
└──────────────┘         └──────┬───────┘
                                │
                                │ N:M
                                │
                         ┌──────▼───────┐
                         │   warden_    │
                         │  assignments │
                         └──────────────┘

┌──────────────┐         ┌──────────────┐
│  outpass_    │────────▶│   parent_    │
│  requests    │   1:1   │ verification │
└──────┬───────┘         └──────────────┘
       │
       │ 1:N
       │
       ▼
┌──────────────┐
│   logbook    │
└──────────────┘

┌──────────────┐         ┌──────────────┐
│ institutions │────────▶│ institution_ │
│              │   1:N   │   programs   │
└──────────────┘         └──────────────┘
```

## Core Tables

### users

Stores all system users with role-based access.

```sql
CREATE TABLE users (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'warden', 'verifier', 'admin', 'super_admin') NOT NULL,
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Columns**:
- `id`: Primary key
- `username`: Unique username for login
- `email`: User email address
- `password`: Bcrypt hashed password
- `role`: User role for access control
- `status`: Account status
- `created_at`: Account creation timestamp
- `updated_at`: Last update timestamp

**Indexes**:
- Primary key on `id`
- Unique index on `username` and `email`
- Index on `role` for role-based queries
- Index on `status` for active user queries

### students

Extended profile information for student users.

```sql
CREATE TABLE students (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNIQUE NOT