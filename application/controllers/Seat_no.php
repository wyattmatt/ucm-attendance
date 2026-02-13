<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_query_builder $db
 * @property CI_Input $input
 */
class Seat_no extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Makassar');
        $this->load->database();
    }

    public function index()
    {
        // Get event ID from URL parameter
        $event_id = $this->input->get('event_id');

        if (!$event_id) {
            show_error('Event ID is required');
        }

        // Get event info
        $event = $this->db->get_where('m_event', ['id' => $event_id])->row_array();
        if (!$event) {
            show_error('Event not found');
        }

        // Get participants with seat numbers, ordered by seat_no
        $sql = "SELECT g.*, a.scanned_time, 
                    CASE WHEN a.id IS NOT NULL THEN 'present' ELSE 'absent' END as final_status,
                    es.name as session_name, es.start_time, es.end_time, 
                    g.desc_2 as prodi, g.full_name as name, g.nis, g.session, g.seat_no, g.page_no, g.kategori,
                    g.created_date as registration_time
                FROM m_guest g
                LEFT JOIN attendance a ON g.id = a.m_guest_id AND g.event_id = a.m_event_id
                LEFT JOIN m_event_session es ON g.event_id = es.m_event_id AND g.session = es.session
                WHERE g.event_id = ? AND g.seat_no IS NOT NULL AND g.seat_no != ''
                ORDER BY CAST(g.seat_no AS UNSIGNED), g.seat_no";

        $data['participants'] = $this->db->query($sql, [$event_id])->result_array();
        $data['event'] = $event;
        $data['event_id'] = $event_id;

        // Get sessions for filtering
        $data['sessions'] = $this->db->order_by('session', 'ASC')
            ->get_where('m_event_session', ['m_event_id' => $event_id])
            ->result_array();

        $this->load->view('seat_no/index', $data);
    }
}
