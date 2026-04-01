<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Events extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Event_model');
		$this->load->library('form_validation');
	}

	public function index()
	{
		$data['admin'] = $this->admin;
		$data['page_title'] = 'Kelola Event';
		$data['events'] = $this->Event_model->get_all();

		$this->load->view('admin/layout/header', $data);
		$this->load->view('admin/layout/sidebar', $data);
		$this->load->view('admin/events/index', $data);
		$this->load->view('admin/layout/footer');
	}

	public function create()
	{
		$data['admin'] = $this->admin;
		$data['page_title'] = 'Tambah Event Baru';
		$data['event'] = null;
		$data['sessions'] = [];
		$data['participants'] = [];
		$data['mode'] = 'create';

		if ($this->input->method() === 'post') {
			$this->form_validation->set_rules('name', 'Nama Event', 'required|max_length[255]');
			$this->form_validation->set_rules('start_date', 'Tanggal Mulai', 'required');
			$this->form_validation->set_rules('end_date', 'Tanggal Selesai', 'required');
			$this->form_validation->set_rules('start_time', 'Waktu Mulai', 'required');
			$this->form_validation->set_rules('end_time', 'Waktu Selesai', 'required');
			$this->form_validation->set_rules('input_label', 'Label Input', 'required|max_length[255]');

			if ($this->form_validation->run()) {
				$event_uuid = generate_uuid();
				$event_data = [
					'id'                => $event_uuid,
					'name'              => $this->input->post('name'),
					'description'       => $this->input->post('description'),
					'start_date'        => $this->input->post('start_date'),
					'end_date'          => $this->input->post('end_date'),
					'start_time'        => $this->input->post('start_time'),
					'end_time'          => $this->input->post('end_time'),
					'input_label'       => $this->input->post('input_label'),
					'input_description' => $this->input->post('input_description'),
					'has_participants'   => $this->input->post('has_participants') ? 1 : 0,
					'created_by'        => $this->admin['id']
				];

				$event_id = $this->Event_model->create($event_data);

				// Handle background image upload
				$bg = $this->_upload_background($event_uuid);
				if ($bg) {
					$this->Event_model->update($event_uuid, ['background_image' => $bg]);
				}

				// Handle sessions
				$this->_save_sessions($event_uuid);

				// Handle CSV upload
				if ($event_data['has_participants'] && !empty($_FILES['csv_file']['name'])) {
					$this->_process_csv($event_uuid, $event_data['input_label']);
				}

				$this->session->set_flashdata('success', 'Event berhasil dibuat.');
				redirect('admin/events');
			}
		}

		$this->load->view('admin/layout/header', $data);
		$this->load->view('admin/layout/sidebar', $data);
		$this->load->view('admin/events/form', $data);
		$this->load->view('admin/layout/footer');
	}

	public function edit($id = null)
	{
		if (!$id) show_404();

		$data['admin'] = $this->admin;
		$data['page_title'] = 'Edit Event';
		$data['event'] = $this->Event_model->get_by_id($id);
		$data['sessions'] = $this->Event_model->get_sessions($id);
		$data['participants'] = $this->Event_model->get_participants($id);
		$data['mode'] = 'edit';

		if (!$data['event']) show_404();

		if ($this->input->method() === 'post') {
			$this->form_validation->set_rules('name', 'Nama Event', 'required|max_length[255]');
			$this->form_validation->set_rules('start_date', 'Tanggal Mulai', 'required');
			$this->form_validation->set_rules('end_date', 'Tanggal Selesai', 'required');
			$this->form_validation->set_rules('start_time', 'Waktu Mulai', 'required');
			$this->form_validation->set_rules('end_time', 'Waktu Selesai', 'required');
			$this->form_validation->set_rules('input_label', 'Label Input', 'required|max_length[255]');

			if ($this->form_validation->run()) {
				$event_data = [
					'name'              => $this->input->post('name'),
					'description'       => $this->input->post('description'),
					'start_date'        => $this->input->post('start_date'),
					'end_date'          => $this->input->post('end_date'),
					'start_time'        => $this->input->post('start_time'),
					'end_time'          => $this->input->post('end_time'),
					'input_label'       => $this->input->post('input_label'),
					'input_description' => $this->input->post('input_description'),
					'has_participants'   => $this->input->post('has_participants') ? 1 : 0
				];

				$this->Event_model->update($id, $event_data);

				// Handle background image upload
				$bg = $this->_upload_background($id);
				if ($bg) {
					// Delete old image if exists
					if (!empty($data['event']->background_image)) {
						$old = FCPATH . 'assets/uploads/events/' . $data['event']->background_image;
						if (file_exists($old)) unlink($old);
					}
					$this->Event_model->update($id, ['background_image' => $bg]);
				}

				// Rebuild sessions
				$this->Event_model->delete_sessions($id);
				$this->_save_sessions($id);

				// Handle CSV upload
				if (!empty($_FILES['csv_file']['name'])) {
					if ($this->input->post('replace_participants')) {
						$this->Event_model->delete_participants($id);
					}
					$this->_process_csv($id, $event_data['input_label']);
				}

				$this->session->set_flashdata('success', 'Event berhasil diperbarui.');
				redirect('admin/events');
			}
		}

		$this->load->view('admin/layout/header', $data);
		$this->load->view('admin/layout/sidebar', $data);
		$this->load->view('admin/events/form', $data);
		$this->load->view('admin/layout/footer');
	}

	public function delete($id = null)
	{
		if (!$id) show_404();

		// Delete background image if exists
		$event = $this->Event_model->get_by_id($id);
		if ($event && !empty($event->background_image)) {
			$path = FCPATH . 'assets/uploads/events/' . $event->background_image;
			if (file_exists($path)) unlink($path);
		}

		$this->Event_model->delete($id);
		$this->session->set_flashdata('success', 'Event berhasil dihapus.');
		redirect('admin/events');
	}

	private function _upload_background($event_id)
	{
		if (empty($_FILES['background_image']['name'])) {
			return null;
		}

		$config = [
			'upload_path'   => FCPATH . 'assets/uploads/events/',
			'allowed_types' => 'jpg|jpeg|png|webp',
			'max_size'      => 5120, // 5MB
			'file_name'     => 'event_' . $event_id . '_' . time()
		];

		$this->load->library('upload', $config);
		if ($this->upload->do_upload('background_image')) {
			return $this->upload->data('file_name');
		}

		$this->session->set_flashdata('error', 'Upload gambar gagal: ' . strip_tags($this->upload->display_errors()));
		return null;
	}

	private function _save_sessions($event_id)
	{
		$session_names = $this->input->post('session_name');
		$session_start = $this->input->post('session_start_time');
		$session_end = $this->input->post('session_end_time');

		if ($session_names && is_array($session_names)) {
			foreach ($session_names as $i => $name) {
				if (!empty(trim($name))) {
					$this->Event_model->add_session([
						'id'            => generate_uuid(),
						'event_id'      => $event_id,
						'session_name'  => trim($name),
						'session_order' => $i + 1,
						'start_time'    => isset($session_start[$i]) && $session_start[$i] !== '' ? $session_start[$i] : null,
						'end_time'      => isset($session_end[$i]) && $session_end[$i] !== '' ? $session_end[$i] : null
					]);
				}
			}
		}
	}

	private function _process_csv($event_id, $input_label = '')
	{
		if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
			return;
		}

		$allowed_types = ['text/csv', 'text/plain', 'application/vnd.ms-excel', 'application/octet-stream'];
		if (!in_array($_FILES['csv_file']['type'], $allowed_types)) {
			$this->session->set_flashdata('error', 'File harus berformat CSV.');
			return;
		}

		$file = $_FILES['csv_file']['tmp_name'];
		if (($handle = fopen($file, 'r')) !== false) {
			$header = fgetcsv($handle);

			// Detect column mapping from header
			$col_map = $this->_detect_csv_columns($header, $input_label);

			$row_num = 0;
			while (($row = fgetcsv($handle)) !== false) {
				$code = isset($row[$col_map['code']]) ? trim($row[$col_map['code']]) : '';
				if ($code === '') continue;

				$name = ($col_map['name'] !== null && isset($row[$col_map['name']])) ? trim($row[$col_map['name']]) : '';

				// Gather remaining columns as additional info
				$extra = [];
				foreach ($col_map['extra'] as $ei) {
					if (isset($row[$ei]) && trim($row[$ei]) !== '') {
						$label = isset($header[$ei]) ? trim($header[$ei]) : '';
						$extra[] = ($label ? $label . ': ' : '') . trim($row[$ei]);
					}
				}

				$this->Event_model->add_participant([
					'event_id'        => (int)$event_id,
					'name'            => $name,
					'unique_code'     => $code,
					'additional_info' => implode(' | ', $extra)
				]);
				$row_num++;
			}
			fclose($handle);
			if ($row_num > 0) {
				$this->session->set_flashdata('csv_info', $row_num . ' peserta berhasil diimpor.');
			}
		}
	}

	/**
	 * Detect which CSV columns map to name, unique_code, and extra info.
	 * The input_label directly matches a CSV header to determine the code column.
	 * Name is auto-detected from common patterns.
	 */
	private function _detect_csv_columns($header, $input_label)
	{
		$name_patterns = ['nama', 'name', 'nama_lengkap', 'full_name', 'fullname', 'nama lengkap'];

		$name_col = null;
		$code_col = null;
		$normalized = [];

		if ($header) {
			foreach ($header as $i => $h) {
				$normalized[$i] = strtolower(trim(preg_replace('/[^a-z0-9_\s]/i', '', $h)));
			}

			// Match code column: input_label must match a header exactly
			$label_normalized = strtolower(trim(preg_replace('/[^a-z0-9_\s]/i', '', $input_label)));
			foreach ($normalized as $i => $nh) {
				if ($nh === $label_normalized) {
					$code_col = $i;
					break;
				}
			}

			// Match name column from common patterns
			foreach ($name_patterns as $pattern) {
				foreach ($normalized as $i => $nh) {
					if ($i === $code_col) continue;
					if ($nh === $pattern || strpos($nh, $pattern) !== false) {
						$name_col = $i;
						break 2;
					}
				}
			}
		}

		// Fallback if no code column matched
		if ($code_col === null) {
			$code_col = ($header && count($header) > 1) ? 1 : 0;
		}
		if ($name_col === null) {
			$name_col = ($code_col === 0 && $header && count($header) > 1) ? 1 : 0;
			if ($name_col === $code_col) $name_col = null;
		}

		// Extra columns: everything except name and code
		$extra = [];
		if ($header) {
			for ($i = 0; $i < count($header); $i++) {
				if ($i !== $name_col && $i !== $code_col) {
					$extra[] = $i;
				}
			}
		}

		return ['name' => $name_col, 'code' => $code_col, 'extra' => $extra];
	}
}


