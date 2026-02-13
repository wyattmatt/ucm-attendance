<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Session_model extends CI_Model {
    public function get_by_event($event_id) {
        return $this->db->where('m_event_id', $event_id)
            ->order_by('session', 'ASC')
            ->get('m_event_session')->result_array();
    }
}
