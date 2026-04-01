<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
}

class Admin_Controller extends MY_Controller
{
	protected $admin;

	public function __construct()
	{
		parent::__construct();
		$this->admin = $this->session->userdata('admin');
		if (!$this->admin) {
			redirect('auth/login');
		}
	}

	protected function require_superadmin()
	{
		if ($this->admin['role'] !== 'superadmin') {
			show_error('Access denied. Superadmin only.', 403);
		}
	}
}
