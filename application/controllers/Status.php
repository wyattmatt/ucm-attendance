<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property Event_model $Event_model
 * @property Guest_model $Guest_model
 */
class Status extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Makassar');
        $this->load->model(['Event_model', 'Guest_model']);
    }

    public function none_list()
    {
        // Determine event id
        $event_id = $this->input->get('event_id');
        if (!$event_id) {
            $events = $this->Event_model->get_active_events(1);
            if ($events) $event_id = $events[0]['id'];
        }
        if (!$event_id) show_error('No active event found');
        $data['event'] = $this->Event_model->get_by_id($event_id);
        // Get only participants who haven't scanned yet (not in attendance table)
        $data['participants'] = $this->Guest_model->get_all_by_event($event_id, 'unscanned');
        $this->load->view('status/none', $data);
    }

    // Backward-compatible route method used by existing links: site_url('status/none')
    public function none()
    {
        return $this->none_list();
    }
}
