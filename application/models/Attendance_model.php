<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Attendance_model extends CI_Model
{

    public function get_by_guest_event($guest_id, $event_id)
    {
        return $this->db->get_where('attendance', [
            'm_guest_id' => $guest_id,
            'm_event_id' => $event_id
        ])->row_array();
    }

    public function create_attendance($guest_id, $event_id, $scanned_time)
    {
        $data = [
            'id' => $this->generate_uuid(),
            'm_guest_id' => $guest_id,
            'm_event_id' => $event_id,
            'scanned_time' => $scanned_time
        ];
        $this->db->insert('attendance', $data);
        
        return $data['id'];
    }

    public function get_attendance_by_event($event_id)
    {
        $this->db->select('a.*, g.full_name as name, g.nis,
                CONCAT(
                COALESCE(g.desc_1_name,"")," : " ,COALESCE(g.desc_1,"")," , ",
                COALESCE(g.desc_2_name,"")," : " ,COALESCE(g.desc_2,"")," , ",
                COALESCE(g.desc_3_name,"")," : " ,COALESCE(g.desc_3,"")) as prodi, es.name as session_name');
        $this->db->from('attendance a');
        $this->db->join('m_guest g', 'a.m_guest_id = g.id');
        $this->db->join('m_event_session es', 'g.event_id = es.m_event_id AND g.session = es.session', 'left');
        $this->db->where('a.m_event_id', $event_id);
        $this->db->order_by('a.scanned_time', 'ASC');
        return $this->db->get()->result_array();
    }

    private function generate_uuid()
    {
        // Simplified UUID v4
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}
