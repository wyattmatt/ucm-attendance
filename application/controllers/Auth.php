<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Admin_model');
	}

	public function login()
	{
		if ($this->session->userdata('admin')) {
			redirect('admin/dashboard');
		}

		$data = ['error' => null];

		if ($this->input->method() === 'post') {
			$email = trim($this->input->post('email'));
			$password = $this->input->post('password');

			$admin = $this->Admin_model->login($email, $password);

			if ($admin) {
				$this->session->set_userdata('admin', [
					'id'    => $admin->id,
					'name'  => $admin->name,
					'email' => $admin->email,
					'role'  => $admin->role
				]);
				redirect('admin/dashboard');
			} else {
				$data['error'] = 'Email atau password salah.';
			}
		}

		$this->load->view('admin/login', $data);
	}

	public function logout()
	{
		$this->session->unset_userdata('admin');
		$this->session->sess_destroy();
		redirect('auth/login');
	}
}
