<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends Admin_Controller
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
		$data['page_title'] = 'Dashboard';
		$data['total_events'] = $this->db->count_all('events');
		$data['total_ongoing'] = $this->db->where('status', 'ongoing')->count_all_results('events');
		$data['total_upcoming'] = $this->db->where('status', 'upcoming')->count_all_results('events');
		$data['total_completed'] = $this->db->where('status', 'completed')->count_all_results('events');
		$data['total_attendances'] = $this->db->count_all('attendances');
		$data['recent_events'] = $this->db->order_by('created_at', 'DESC')->limit(5)->get('events')->result();

		$this->load->view('admin/layout/header', $data);
		$this->load->view('admin/layout/sidebar', $data);
		$this->load->view('admin/dashboard', $data);
		$this->load->view('admin/layout/footer');
	}
}
