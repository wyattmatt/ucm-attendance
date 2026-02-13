<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event_model extends CI_Model {
    public function get_active_events($limit = 6) {
        return $this->db->where('status', 'active')
                    ->order_by('name', 'desc')
                    ->limit($limit)
                    ->get('m_event')->result_array();
    }

    public function get_by_id($id) {
        return $this->db->get_where('m_event', ['id' => $id])->row_array();
    }

    public function get_all() {
        return $this->db->order_by('name', 'ASC')->get('m_event')->result_array();
    }
}
