<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Event_model');
		$this->load->model('Attendance_model');
	}

	public function index()
	{
		$data['events'] = $this->Event_model->get_active_events();
		$this->load->view('user/events', $data);
	}

	public function attend($event_id = null)
	{
		if (!$event_id) show_404();

		$event = $this->Event_model->get_by_id($event_id);
		if (!$event || $event->status === 'completed') {
			show_404();
		}

		$data['event'] = $event;
		$data['sessions'] = $this->Event_model->get_sessions($event_id);
		$this->load->view('user/attend', $data);
	}

	public function submit_attendance()
	{
		$this->output->set_content_type('application/json');

		$csrf_hash = $this->security->get_csrf_hash();
		$event_id = $this->input->post('event_id');
		$input_value = trim($this->input->post('input_value'));

		if (!$event_id || $input_value === '' || $input_value === false) {
			$this->output->set_output(json_encode([
				'status' => 'error',
				'message' => 'Mohon isi kolom yang diperlukan.',
				'csrf_hash' => $csrf_hash
			]));
			return;
		}

		$event = $this->Event_model->get_by_id($event_id);
		if (!$event || $event->status === 'completed') {
			$this->output->set_output(json_encode([
				'status' => 'error',
				'message' => 'Event tidak ditemukan atau sudah selesai.',
				'csrf_hash' => $csrf_hash
			]));
			return;
		}

		// Auto-detect active session
		$session_id = null;
		$session_name = null;
		$sessions = $this->Event_model->get_sessions($event_id);
		if (!empty($sessions)) {
			$current_time = date('H:i:s');
			foreach ($sessions as $s) {
				if ($s->start_time && $s->end_time) {
					if ($current_time >= $s->start_time && $current_time <= $s->end_time) {
						$session_id = $s->id;
						$session_name = $s->session_name;
						break;
					}
				}
			}
			if (!$session_id) {
				$session_id = $sessions[0]->id;
				$session_name = $sessions[0]->session_name;
			}
		}

		// Check duplicate per session
		$existing = $this->Attendance_model->check_duplicate($event_id, $input_value, $session_id);
		if ($existing) {
			$this->output->set_output(json_encode([
				'status' => 'error',
				'message' => 'Kode ini sudah tercatat' . ($session_name ? ' untuk ' . $session_name : '') . '.',
				'csrf_hash' => $csrf_hash
			]));
			return;
		}

		// Match participant by unique_code
		$participant_id = null;
		if ($event->has_participants) {
			$participant = $this->Attendance_model->find_participant($event_id, $input_value);
			if (!$participant) {
				$this->output->set_output(json_encode([
					'status' => 'error',
					'message' => 'Data tidak ditemukan.',
					'csrf_hash' => $csrf_hash
				]));
				return;
			}
			$participant_id = $participant->id;
		}

		$this->Attendance_model->record([
			'event_id'       => (int)$event_id,
			'session_id'     => $session_id,
			'participant_id' => $participant_id,
			'input_value'    => $input_value
		]);

		$this->output->set_output(json_encode([
			'status' => 'success',
			'message' => 'Kehadiran Tercatat!',
			'event_name' => $event->name,
			'session_name' => $session_name,
			'time' => date('d M Y, H:i') . ' WITA',
			'csrf_hash' => $csrf_hash
		]));
	}
}
