# UCM Attendance Management System

A web-based QR code attendance management system built with CodeIgniter 3 for Ciputra University Makassar (Universitas Ciputra Makassar). This system manages event registrations, participant tracking, and real-time attendance scanning using QR codes.

## 📋 Table of Contents

- [System Requirements](#system-requirements)
- [Installation](#installation)
- [Database Setup](#database-setup)
- [Configuration](#configuration)
- [Project Structure](#project-structure)
- [API Endpoints](#api-endpoints)
- [Database Schema](#database-schema)
- [License](#license)

## 🔧 System Requirements

- **Web Server**: Apache/Nginx with mod_rewrite enabled
- **PHP**: 5.3.7 or higher (PHP 7.x or 8.x recommended)
- **Database**: MySQL 5.0.7+ or MariaDB 10.x
- **Extensions**:
  - mysqli
  - mbstring
  - json

## 📦 Installation

### 1. Clone or Download the Project

```bash
git clone <repository-url> ucm-attendance
cd ucm-attendance
```

### 2. Install Dependencies (Optional)

If using Composer:

```bash
composer install
```

### 3. Set Permissions

```bash
chmod -R 755 application/cache
chmod -R 755 application/logs
chmod -R 755 uploads
```

## 🗄️ Database Setup

### 1. Create Database

```sql
CREATE DATABASE ucm_registration CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

### 2. Import Database Schema

Import the provided SQL file:

```bash
mysql -u your_username -p ucm_registration < ucm_registration.sql
```

Or import via phpMyAdmin by selecting the `ucm_registration.sql` file.

### 3. Database Configuration

Edit `application/config/database.php`:

```php
$db['default'] = array(
    'dsn'   => '',
    'hostname' => 'localhost',
    'username' => 'your_db_username',
    'password' => 'your_db_password',
    'database' => 'ucm_registration',
    'dbdriver' => 'mysqli',
    // ... other settings
);
```

## ⚙️ Configuration

### Base URL Configuration

Edit `application/config/config.php`:

```php
$config['base_url'] = 'http://your-domain.com/';
```

### Timezone Configuration

The system is configured for Asia/Makassar timezone. To change it, update in each controller's constructor or globally in `config.php`.

### Environment Configuration

Edit `index.php` to set the environment:

```php
define('ENVIRONMENT', 'production'); // development, testing, or production
```

## 📁 Project Structure

```
ucm-attendance/
├── application/
│   ├── controllers/
│   │   ├── Admin.php           # Admin dashboard
│   │   ├── Attendance.php      # Attendance processing
│   │   ├── Cron.php           # Automated status updates
│   │   ├── Events.php         # Events listing
│   │   ├── PrintCard.php      # Print participant cards
│   │   ├── Qrcode.php         # QR code registration
│   │   ├── Scanner.php        # QR code scanner interface
│   │   ├── Seat_no.php        # Seat number management
│   │   └── Status.php         # Attendance status views
│   ├── models/
│   │   ├── Attendance_model.php
│   │   ├── Event_model.php
│   │   ├── Guest_model.php
│   │   └── Session_model.php
│   ├── views/
│   │   ├── admin/             # Admin interface views
│   │   ├── events/            # Event listing views
│   │   ├── scanner/           # Scanner interface views
│   │   ├── qrcode/            # QR code views
│   │   ├── print/             # Print card views
│   │   ├── seat_no/           # Seat number views
│   │   └── status/            # Status views
│   ├── libraries/
│   │   └── Attendance_status_updater.php
│   └── config/
├── system/                    # CodeIgniter core files
├── uploads/                   # Upload directory
├── ucm_registration.sql       # Database schema
├── index.php                  # Entry point
└── README.md
```

## 🔌 API Endpoints

### POST `/attendance/process`

Process QR code scan and record attendance.

**Request Body:**

```json
{
	"event_id": "event-uuid",
	"qr_code_data": "participant-qrcode-or-nis"
}
```

**Success Response:**

```json
{
	"success": true,
	"message": "Absensi berhasil dicatat",
	"student_info": {
		"name": "John Doe",
		"nis": "1234567890",
		"prodi": "Computer Science",
		"kategori": "Student",
		"session_name": "Morning Session",
		"status": "present"
	}
}
```

**Error Responses:**

```json
{
	"success": false,
	"message": "Anda sudah absen." // Already scanned
}
```

```json
{
	"success": false,
	"message": "Belum saatnya absen. Waktu absen dimulai pada 19/09/2025 06:00"
}
```

```json
{
	"success": false,
	"message": "QR Code terdaftar pada acara berbeda: Event Name (Session)"
}
```

### POST `/printcard/get_student_info`

Get participant information for printing.

**Request Body:**

```json
{
	"event_id": "event-uuid",
	"qr_code_data": "participant-qrcode"
}
```

## 📊 Database Schema

### Main Tables

#### `m_event`

Events management table.

- `id` (UUID): Primary key
- `name`: Event name
- `description`: Event description
- `status`: active/inactive
- `created_at`: Timestamp

#### `m_event_session`

Event sessions table.

- `id` (UUID): Primary key
- `m_event_id`: Foreign key to m_event
- `name`: Session name
- `session`: Session number (1, 2, etc.)
- `start_date`, `start_time`: Session start
- `end_date`, `end_time`: Session end
- `created_at`: Timestamp

#### `m_guest`

Participants/guests table.

- `id` (UUID): Primary key
- `event_id`: Foreign key to m_event
- `nis`: Student ID
- `full_name`: Participant name
- `seat_no`: Assigned seat
- `session`: Session number
- `desc_1_name` to `desc_7_name`: Custom field labels
- `desc_1` to `desc_7`: Custom field values
- `qrcode`: QR code identifier
- `created_date`, `modified_date`: Timestamps
- `created_by`, `modified_by`: User tracking

#### `attendance`

Attendance records table.

- `id` (UUID): Primary key
- `m_event_id`: Foreign key to m_event
- `m_guest_id`: Foreign key to m_guest
- `scanned_time`: When attendance was recorded
- `status`: present/absent/pending/none

## 📄 License

This project uses the MIT License (from CodeIgniter framework). See [LICENSE](https://github.com/wyattmatt/ucm-attendance/blob/main/LICENSE) file for details.
