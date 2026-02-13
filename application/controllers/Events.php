<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Event_model $Event_model
 */
class Events extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Makassar');
        $this->load->model('Event_model');
    }

    public function index()
    {
        $data['events'] = $this->Event_model->get_active_events(6);
        $this->load->view('events/index', $data);
    }
}
