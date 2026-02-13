<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Output $output
 * @property CI_Input $input
 * @property Guest_model $Guest_model
 * @property Attendance_model $Attendance_model
 */
class Attendance extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Makassar');
        $this->load->model(['Guest_model', 'Attendance_model']);
        $this->output->set_content_type('application/json');
    }

    public function process()
    {
        $input = json_decode($this->input->raw_input_stream, true);
        if (!$input || empty($input['event_id']) || empty($input['qr_code_data'])) {
            return $this->output->set_output(json_encode(['success' => false, 'message' => 'Data tidak lengkap']));
        }

        $event_id = $input['event_id'];
        $qrcode = trim((string)$input['qr_code_data']);
        
        // return $this->output->set_output($qrcode);
        // (string)
        // 67a54b0f-2db7-4fbf-ad80-20f7f5b11d48
        if ($qrcode === '') {
            return $this->output->set_output(json_encode(['success' => false, 'message' => 'QR Code tidak valid']));
        }

        // Find student in the event
        $student = $this->Guest_model->find_by_qrcode_event($qrcode, $event_id);
        
        if (!$student) {
            // Check if student exists in other events
            $other = $this->Guest_model->find_by_qrcode_any_event($qrcode);
            if ($other && !empty($other['event_id']) && (string)$other['event_id'] !== (string)$event_id) {
                $other_event_name = $other['event_name'] ?? ('Event #' . $other['event_id']);
                $session = $other['session_name'] ?? ('Sesi ' . ($other['session'] ?? ''));
                return $this->output->set_output(json_encode([
                    'success' => false,
                    'message' => 'QR Code terdaftar pada acara berbeda: ' . $other_event_name . ' (' . $session . ').'
                ]));
            }
            return $this->output->set_output(json_encode(['success' => false, 'message' => 'Data tidak ditemukan']));
        }

        // Check if already attended
        $existing_attendance = $this->Attendance_model->get_by_guest_event($student['id'], $event_id);
        if ($existing_attendance) {
            return $this->output->set_output(json_encode([
                'success' => false,
                'message' => 'Anda sudah absen.'
            ]));
        }

        // Validate session time
        $now = date('Y-m-d H:i:s');
        $session_start = $student['start_date'] . ' ' . $student['start_time'];
        $session_end = $student['end_date'] . ' ' . $student['end_time'];

        if ($now < $session_start) {
            return $this->output->set_output(json_encode([
                'success' => false,
                'message' => 'Belum saatnya absen. Waktu absen dimulai pada ' . date('d/m/Y H:i', strtotime($session_start))
            ]));
        }

        if ($now > $session_end) {
            return $this->output->set_output(json_encode([
                'success' => false,
                'message' => 'Waktu absensi telah berakhir. Waktu absen berakhir pada ' . date('d/m/Y H:i', strtotime($session_end))
            ]));
        }

        // Create attendance record
        $attendance_id = $this->Attendance_model->create_attendance($student['id'], $event_id, $now);

        $student_info = [
            'name' => $student['name'] ?? '',
            'nis' => $student['nis'] ?? $qrcode,
            'prodi' => $student['prodi'] ?? '',
            'kategori' => $student['kategori'] ?? '',
            'session_name' => $student['session_name'] ?? ('Sesi ' . ($student['session'] ?? '')),
            'status' => 'present'
        ];

        return $this->output->set_output(json_encode([
            'success' => true,
            'message' => 'Absensi berhasil dicatat',
            'student_info' => $student_info
        ]));
    }
}
