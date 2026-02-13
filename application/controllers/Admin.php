<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_query_builder $db
 * @property CI_Input $input
 */
class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Makassar');
        $this->load->database();
    }

    public function index()
    {
        // Events with counts
        $sql = "SELECT e.*, 
                COUNT(DISTINCT g.id) as total_participants,
                COUNT(DISTINCT a.id) as total_attended
                FROM m_event e
                LEFT JOIN m_guest g ON e.id = g.event_id
                LEFT JOIN attendance a ON e.id = a.m_event_id
                GROUP BY e.id
        ORDER BY e.name ASC";
        $data['events'] = $this->db->query($sql)->result_array();

        $selected_event = $this->input->get('event_id');
        $data['selected_event'] = $selected_event;
        $data['participants'] = [];
        $data['sessions'] = [];
        
       
               

        if ($selected_event) {
            // All participants (both attended and not attended)
            $sqlP = "SELECT g.*, a.scanned_time, 
                        CASE WHEN a.id IS NOT NULL THEN 'present' ELSE 'absent' END as final_status,
                        es.name as session_name, es.start_time, es.end_time, es.start_date, es.end_date,
                CONCAT(
                COALESCE(g.desc_1_name,''),' : ' ,COALESCE(g.desc_1,''),' , ',
                COALESCE(g.desc_2_name,''),' : ' ,COALESCE(g.desc_2,''),' , ',
                COALESCE(g.desc_3_name,''),' : ' ,COALESCE(g.desc_3,'')) as prodi, g.full_name as name, g.nis, g.session, g.seat_no, g.kategori,
                        g.created_date as registration_time
                    FROM m_guest g
                    LEFT JOIN attendance a ON g.id = a.m_guest_id AND g.event_id = a.m_event_id
                    LEFT JOIN m_event_session es ON g.event_id = es.m_event_id AND g.session = es.session
                    WHERE g.event_id = ?
                    ORDER BY g.full_name";
            $data['participants'] = $this->db->query($sqlP, [$selected_event])->result_array();

            // Sessions list
            $data['sessions'] = $this->db->order_by('session', 'ASC')->get_where('m_event_session', ['m_event_id' => $selected_event])->result_array();
        }

        $this->load->view('admin/index', $data);
    }
}
