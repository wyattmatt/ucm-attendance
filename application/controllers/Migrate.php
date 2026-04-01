<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migrate extends CI_Controller
{

    public function index()
    {
        $this->_create_tables();
        $this->_seed_admins();
        echo '<h2>UCM Attendance - Setup Complete</h2>';
        echo '<p>Tables created and admin accounts seeded.</p>';
        echo '<p><a href="' . base_url('auth/login') . '">Go to Admin Login</a></p>';
        echo '<hr>';
        echo '<p><strong>Superadmin:</strong> ictadmin@ciputra.ac.id / ictadmin</p>';
        echo '<p><strong>Admin:</strong> ictmagang@ciputra.ac.id / ictmagang</p>';
    }

    private function _create_tables()
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS admins (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                role ENUM('superadmin', 'admin') NOT NULL DEFAULT 'admin',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS events (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                description TEXT,
                start_date DATE NOT NULL,
                end_date DATE NOT NULL,
                start_time TIME NOT NULL,
                end_time TIME NOT NULL,
                input_label VARCHAR(255) NOT NULL DEFAULT 'Kode Kehadiran',
                input_description VARCHAR(500) DEFAULT NULL,
                has_participants TINYINT(1) NOT NULL DEFAULT 0,
                background_image VARCHAR(255) DEFAULT NULL,
                status ENUM('upcoming', 'ongoing', 'completed') NOT NULL DEFAULT 'upcoming',
                created_by INT DEFAULT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (created_by) REFERENCES admins(id) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS event_sessions (
                id INT AUTO_INCREMENT PRIMARY KEY,
                event_id INT NOT NULL,
                session_name VARCHAR(255) NOT NULL,
                session_order INT NOT NULL DEFAULT 1,
                start_time TIME DEFAULT NULL,
                end_time TIME DEFAULT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS participants (
                id INT AUTO_INCREMENT PRIMARY KEY,
                event_id INT NOT NULL,
                name VARCHAR(255) DEFAULT NULL,
                unique_code VARCHAR(255) NOT NULL,
                additional_info TEXT DEFAULT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS attendances (
                id INT AUTO_INCREMENT PRIMARY KEY,
                event_id INT NOT NULL,
                session_id INT DEFAULT NULL,
                participant_id INT DEFAULT NULL,
                input_value VARCHAR(255) NOT NULL,
                attended_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
                FOREIGN KEY (session_id) REFERENCES event_sessions(id) ON DELETE SET NULL,
                FOREIGN KEY (participant_id) REFERENCES participants(id) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }

    private function _seed_admins()
    {
        $count = $this->db->count_all_results('admins');
        if ($count > 0) {
            echo '<p>Admins already exist, skipping seed.</p>';
            return;
        }

        $this->db->insert('admins', [
            'name'     => 'ICT Admin',
            'email'    => 'ictadmin@ciputra.ac.id',
            'password' => password_hash('ictadmin', PASSWORD_DEFAULT),
            'role'     => 'superadmin'
        ]);

        $this->db->insert('admins', [
            'name'     => 'ICT Magang',
            'email'    => 'ictmagang@ciputra.ac.id',
            'password' => password_hash('ictmagang', PASSWORD_DEFAULT),
            'role'     => 'admin'
        ]);
    }
}
