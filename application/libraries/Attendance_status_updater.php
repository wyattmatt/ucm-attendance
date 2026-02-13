<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Attendance_status_updater
{
    /** @var CI_Controller */
    protected $CI;
    /** @var CI_DB_query_builder */
    protected $db;

    public function __construct()
    {
        $this->CI = &get_instance();
        // Initialize and store a DB connection (returns CI_DB_driver)
        $this->db = $this->CI->load->database('', true);
    }

    public function calculate_status($currentTime, $sessionStart, $sessionEnd)
    {
        $current = strtotime($currentTime);
        $start = strtotime($sessionStart);
        $end = strtotime($sessionEnd);

        if ($current < $start) return 'pending';
        if ($current >= $start && $current <= $end) return 'none';
        return 'absent';
    }

    public function update_event($event_id)
    {
        date_default_timezone_set('Asia/Makassar');
        $now = date('Y-m-d H:i:s');
        $sql = "SELECT a.id as attendance_id, a.status as current_status, g.id as guest_id, g.nis, g.full_name as name,
                       es.name as session_name, CONCAT(es.start_date,' ',es.start_time) as session_start,
                       CONCAT(es.end_date,' ',es.end_time) as session_end
                FROM m_guest g
                LEFT JOIN m_event_session es ON g.event_id = es.m_event_id AND g.session = es.session
                LEFT JOIN attendance a ON g.id = a.m_guest_id AND a.m_event_id = g.event_id
                WHERE g.event_id = ?";
        $rows = $this->db->query($sql, [$event_id])->result_array();
        $updated = 0;
        $errors = 0;
        foreach ($rows as $row) {
            if (!$row['session_start'] || !$row['session_end']) continue;
            $new = $this->calculate_status($now, $row['session_start'], $row['session_end']);
            if ($row['current_status'] !== $new && !in_array($row['current_status'], ['present'])) {
                // Don't overwrite present; set absent/pending/none transitions
                if (!empty($row['attendance_id'])) {
                    $this->db->where('id', $row['attendance_id']);
                    $ok = $this->db->update('attendance', ['status' => $new]);
                    if ($ok) $updated++;
                }
            }
        }
        return ['success' => true, 'updated' => $updated, 'errors' => $errors];
    }

    public function summary()
    {
        $sql = "SELECT m_event.name as event_name,
                       SUM(a.status='present') as present,
                       SUM(a.status='absent') as absent,
                       SUM(a.status='pending') as pending,
                       SUM(a.status='none') as none
                FROM m_event
                LEFT JOIN attendance a ON a.m_event_id = m_event.id
                GROUP BY m_event.id
                ORDER BY m_event.name";
        return $this->db->query($sql)->result_array();
    }
}
