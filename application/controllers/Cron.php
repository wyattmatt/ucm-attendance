<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Output $output
 * @property CI_Input $input
 * @property Event_model $Event_model
 * @property Attendance_status_updater $attendance_status_updater
 */
class Cron extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Makassar');
        $this->load->library('attendance_status_updater');
        $this->load->model('Event_model');
    }

    public function index()
    {
        $data['events'] = $this->Event_model->get_all();
        $data['summary'] = $this->attendance_status_updater->summary();
        $this->load->view('cron/index', $data);
    }

    public function run_update()
    {
        $result = $this->attendance_status_updater->update_event($this->input->get('event_id'));
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function run_event($event_id)
    {
        $result = $this->attendance_status_updater->update_event($event_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }
}
