<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Guest_model extends CI_Model
{
    public function find_by_nis_event($nis, $event_id)
    {
        $this->db->select('g.*, es.name as session_name, es.start_time, es.end_time, es.start_date, es.end_date, g.full_name as name,CONCAT(
                COALESCE(g.desc_1_name,"")," : " ,COALESCE(g.desc_1,"")," , ",
                COALESCE(g.desc_2_name,"")," : " ,COALESCE(g.desc_2,"")," , ",
                COALESCE(g.desc_3_name,"")," : " ,COALESCE(g.desc_3,"")) as prodi');
        $this->db->from('m_guest g');
        $this->db->join('m_event_session es', 'g.event_id = es.m_event_id AND g.session = es.session', 'left');
        $this->db->where('g.nis', $nis);
        $this->db->where('g.event_id', $event_id);
        return $this->db->get()->row_array();
    }

    public function find_by_qrcode_event($qrcode, $event_id)
    {
        $this->db->select('g.*, es.name as session_name, es.start_time, es.end_time, es.start_date, es.end_date, g.full_name as name,CONCAT(
                COALESCE(g.desc_1_name,"")," : " ,COALESCE(g.desc_1,"")," , ",
                COALESCE(g.desc_2_name,"")," : " ,COALESCE(g.desc_2,"")," , ",
                COALESCE(g.desc_3_name,"")," : " ,COALESCE(g.desc_3,"")) as prodi');
        $this->db->from('m_guest g');
        $this->db->join('m_event_session es', 'g.event_id = es.m_event_id AND g.session = es.session', 'left');

        $this->db->group_start();
        $this->db->where('g.qrcode', $qrcode);
        $this->db->or_where('g.nis', $qrcode);
        $this->db->or_where('g.nis', substr($qrcode, 0, 13));
        $this->db->group_end();
        $this->db->where('g.event_id', $event_id);

        return $this->db->get()->row_array();
    }

    public function find_by_nis_any_event($nis)
    {
        $this->db->select('g.*, m_event.name as event_name, es.name as session_name, es.start_time, es.end_time, es.start_date, es.end_date, g.full_name as name, CONCAT(
                COALESCE(g.desc_1_name,"")," : " ,COALESCE(g.desc_1,"")," , ",
                COALESCE(g.desc_2_name,"")," : " ,COALESCE(g.desc_2,"")," , ",
                COALESCE(g.desc_3_name,"")," : " ,COALESCE(g.desc_3,"")) as  prodi');
        $this->db->from('m_guest g');
        $this->db->join('m_event', 'm_event.id = g.event_id', 'left');
        $this->db->join('m_event_session es', 'g.event_id = es.m_event_id AND g.session = es.session', 'left');
        $this->db->where('g.nis', $nis);
        return $this->db->get()->row_array();
    }

    public function find_by_qrcode_any_event($qrcode)
    {
        $this->db->select('g.*, m_event.name as event_name, es.name as session_name, es.start_time, es.end_time, es.start_date, es.end_date, g.full_name as name, CONCAT(
                COALESCE(g.desc_1_name,"")," : " ,COALESCE(g.desc_1,"")," , ",
                COALESCE(g.desc_2_name,"")," : " ,COALESCE(g.desc_2,"")," , ",
                COALESCE(g.desc_3_name,"")," : " ,COALESCE(g.desc_3,"")) as prodi');
        $this->db->from('m_guest g');
        $this->db->join('m_event', 'm_event.id = g.event_id', 'left');
        $this->db->join('m_event_session es', 'g.event_id = es.m_event_id AND g.session = es.session', 'left');

        $this->db->group_start();
        $this->db->where('g.qrcode', $qrcode);
        $this->db->or_where('g.nis', $qrcode);
        $this->db->or_where('g.nis', substr($qrcode, 0, 13));
        $this->db->group_end();

        return $this->db->get()->row_array();
    }

    public function get_all_by_event($event_id, $filter = 'all')
    {
        $this->db->from('m_guest g');
        $this->db->join('m_event_session es', 'g.event_id = es.m_event_id AND g.session = es.session', 'left');

        if ($filter === 'scanned') {
            // Only show participants who have scanned (inner join)
            $this->db->select('g.*, es.name as session_name, a.scanned_time, g.full_name as name, CONCAT(
                COALESCE(g.desc_1_name,"")," : " ,COALESCE(g.desc_1,"")," , ",
                COALESCE(g.desc_2_name,"")," : " ,COALESCE(g.desc_2,"")," , ",
                COALESCE(g.desc_3_name,"")," : " ,COALESCE(g.desc_3,"")) as prodi');
            $this->db->join('attendance a', 'g.id = a.m_guest_id AND a.m_event_id = g.event_id', 'inner');
        } elseif ($filter === 'unscanned') {
            // Only show participants who have NOT scanned (not in attendance table)
            $this->db->select('g.*, es.name as session_name, CAST(NULL AS DATETIME) as scanned_time, g.full_name as name, g.desc_2 as prodi', FALSE);
            $this->db->where("g.id NOT IN (SELECT m_guest_id FROM attendance WHERE m_event_id = " . $this->db->escape($event_id) . ")");
        } else {
            // Default: show all participants with attendance status (left join)
            $this->db->select('g.*, es.name as session_name, a.scanned_time, g.full_name as name, g.desc_2 as prodi');
            $this->db->join('attendance a', 'g.id = a.m_guest_id AND a.m_event_id = g.event_id', 'left');
        }

        $this->db->where('g.event_id', $event_id);
        $this->db->order_by('g.created_date', 'ASC');
        return $this->db->get()->result_array();
    }
}
