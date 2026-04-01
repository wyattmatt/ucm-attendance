<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Attendance extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Event_model');
		$this->load->model('Attendance_model');
	}

	public function index()
	{
		$data['admin'] = $this->admin;
		$data['page_title'] = 'Laporan Kehadiran';
		$data['events'] = $this->Event_model->get_all();

		// Add attendance count per event
		foreach ($data['events'] as &$event) {
			$event->attendance_count = $this->Attendance_model->count_by_event($event->id);
			$event->participant_count = $this->Event_model->count_participants($event->id);
		}

		$this->load->view('admin/layout/header', $data);
		$this->load->view('admin/layout/sidebar', $data);
		$this->load->view('admin/attendance/index', $data);
		$this->load->view('admin/layout/footer');
	}

	public function view($event_id = null)
	{
		if (!$event_id) show_404();

		$data['admin'] = $this->admin;
		$data['event'] = $this->Event_model->get_by_id($event_id);

		if (!$data['event']) show_404();

		$data['page_title'] = 'Kehadiran: ' . $data['event']->name;
		$data['sessions'] = $this->Event_model->get_sessions($event_id);
		$data['attendances'] = $this->Attendance_model->get_by_event($event_id);
		$data['stats'] = $this->Attendance_model->get_stats($event_id);
		$data['participants'] = [];
		$data['extra_columns'] = [];

		if ($data['event']->has_participants) {
			$data['participants'] = $this->Attendance_model->get_attendance_with_participants($event_id);

			// Parse extra column names from first participant's additional_info
			if (!empty($data['participants'])) {
				$first = $data['participants'][0];
				if (!empty($first->additional_info)) {
					$parts = explode(' | ', $first->additional_info);
					foreach ($parts as $part) {
						$colonPos = strpos($part, ': ');
						if ($colonPos !== false) {
							$data['extra_columns'][] = substr($part, 0, $colonPos);
						}
					}
				}
			}
		}

		$this->load->view('admin/layout/header', $data);
		$this->load->view('admin/layout/sidebar', $data);
		$this->load->view('admin/attendance/view', $data);
		$this->load->view('admin/layout/footer');
	}

	public function export($event_id = null)
	{
		if (!$event_id) show_404();

		$event = $this->Event_model->get_by_id($event_id);
		if (!$event) show_404();

		$filename = 'attendance_' . url_title($event->name, 'dash', true) . '_' . date('Y-m-d') . '.csv';

		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename="' . $filename . '"');

		$output = fopen('php://output', 'w');
		fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM for Excel

		if ($event->has_participants) {
			$participants = $this->Attendance_model->get_attendance_with_participants($event_id);

			// Parse extra column names from first participant
			$extra_columns = [];
			if (!empty($participants) && !empty($participants[0]->additional_info)) {
				$parts = explode(' | ', $participants[0]->additional_info);
				foreach ($parts as $part) {
					$colonPos = strpos($part, ': ');
					if ($colonPos !== false) {
						$extra_columns[] = substr($part, 0, $colonPos);
					}
				}
			}

			// Build header row
			$header = ['No', 'Nama', $event->input_label];
			foreach ($extra_columns as $col) {
				$header[] = ucwords($col);
			}
			$header[] = 'Status';
			$header[] = 'Waktu Hadir';
			fputcsv($output, $header);

			$i = 1;
			foreach ($participants as $p) {
				$row = [$i++, $p->name, $p->unique_code];

				// Parse extra info for this participant
				$extra = [];
				if (!empty($p->additional_info)) {
					$parts = explode(' | ', $p->additional_info);
					foreach ($parts as $part) {
						$colonPos = strpos($part, ': ');
						if ($colonPos !== false) {
							$extra[substr($part, 0, $colonPos)] = substr($part, $colonPos + 2);
						}
					}
				}
				foreach ($extra_columns as $col) {
					$row[] = isset($extra[$col]) ? $extra[$col] : '';
				}

				$row[] = $p->is_present ? 'Hadir' : 'Tidak Hadir';
				$row[] = $p->attended_at ?? '-';
				fputcsv($output, $row);
			}
		} else {
			fputcsv($output, ['No', 'Input', 'Sesi', 'Waktu Hadir']);
			$attendances = $this->Attendance_model->get_by_event($event_id);
			$i = 1;
			foreach ($attendances as $a) {
				fputcsv($output, [
					$i++,
					$a->input_value,
					$a->session_name ?? '-',
					$a->attended_at
				]);
			}
		}

		fclose($output);
		exit;
	}

	// AJAX endpoint for real-time monitoring
	public function ajax_data($event_id = null)
	{
		if (!$event_id) {
			$this->output->set_content_type('application/json')->set_output(json_encode(['error' => 'Invalid event']));
			return;
		}

		$attendances = $this->Attendance_model->get_by_event($event_id);
		$stats = $this->Attendance_model->get_stats($event_id);

		$participants = null;
		$event = $this->Event_model->get_by_id($event_id);
		if ($event && $event->has_participants) {
			$participants = $this->Attendance_model->get_attendance_with_participants($event_id);
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode([
				'attendances'  => $attendances,
				'stats'        => $stats,
				'participants' => $participants
			]));
	}
}
