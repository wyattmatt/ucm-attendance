<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Printcard extends CI_Controller
{
    public $input;
    public $Event_model;
    public $Guest_model;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Event_model');
        $this->load->model('Session_model');
        $this->load->model('Guest_model');
    }

    public function index($event_id = null)
    {
        // If no event_id provided, try to get from query parameter or use default
        if (!$event_id) {
            $event_id = $this->input->get('event_id');
            if (!$event_id) {
                // Get the first active event as default
                $events = $this->Event_model->get_active_events();
                if (!empty($events)) {
                    $event_id = $events[0]['id'];
                } else {
                    show_error('No active events found', 404);
                    return;
                }
            }
        }

        // Get event details
        $event = $this->Event_model->get_by_id($event_id);
        if (!$event) {
            show_error('Event not found', 404);
            return;
        }

        // Pass data to view
        $data = [
            'event' => $event,
            'event_id' => $event_id
        ];

        $this->load->view('print/index', $data);
    }

    public function get_student_info()
    {
        // Set JSON header
        header('Content-Type: application/json');

        // Get POST data
        $input = json_decode(file_get_contents('php://input'), true);
        $event_id = $input['event_id'] ?? null;
        $qr_code_data = $input['qr_code_data'] ?? null;

        if (!$event_id || !$qr_code_data) {
            echo json_encode(['success' => false, 'message' => 'Missing required data']);
            return;
        }

        // Load Guest model to find student data
        $this->load->model('Guest_model');

        // Try to find the student by QR code data (could be NIS or other identifier)
        $student = $this->Guest_model->find_by_qrcode_event($qr_code_data, $event_id);

        if ($student) {
            // Student found, return their info
            echo json_encode([
                'success' => true,
                'student_info' => [
                    'name' => $student['name'],
                    'nis' => $student['nis'],
                    'seat_no' => $student['seat_no'],
                    'prodi' => $student['prodi'],
                    'session_name' => $student['session_name'] ?? null
                ]
            ]);
        } else {
            // Student not found
            echo json_encode([
                'success' => false,
                'message' => 'QR Code tidak ditemukan dalam database'
            ]);
        }
    }
}
