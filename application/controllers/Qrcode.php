<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Event_model $Event_model
 */
class Qrcode extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Makassar');
        $this->load->model('Event_model');
        $this->load->helper('url');
    }

    public function index($event_id = null)
    {
        if (!$event_id) {
            // Try first active event
            $events = $this->Event_model->get_active_events(1);
            if (!$events) redirect('events');
            $event_id = $events[0]['id'];
        }

        $event = $this->Event_model->get_by_id($event_id);
        if (!$event) return redirect('events');

        $data['event'] = $event;
        $this->load->view('qrcode/index', $data);
    }
}
