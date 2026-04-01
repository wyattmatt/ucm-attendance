<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admins extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->require_superadmin();
		$this->load->model('Admin_model');
		$this->load->library('form_validation');
	}

	public function index()
	{
		$data['admin'] = $this->admin;
		$data['page_title'] = 'Kelola Admin';
		$data['admins'] = $this->Admin_model->get_all();

		$this->load->view('admin/layout/header', $data);
		$this->load->view('admin/layout/sidebar', $data);
		$this->load->view('admin/admins/index', $data);
		$this->load->view('admin/layout/footer');
	}

	public function create()
	{
		$data['admin'] = $this->admin;
		$data['page_title'] = 'Tambah Admin Baru';
		$data['admin_data'] = null;
		$data['mode'] = 'create';

		if ($this->input->method() === 'post') {
			$this->form_validation->set_rules('name', 'Nama', 'required|max_length[255]');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[admins.email]');
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
			$this->form_validation->set_rules('role', 'Role', 'required|in_list[superadmin,admin]');

			if ($this->form_validation->run()) {
				$this->Admin_model->create([
					'name'     => $this->input->post('name'),
					'email'    => $this->input->post('email'),
					'password' => $this->input->post('password'),
					'role'     => $this->input->post('role')
				]);

				$this->session->set_flashdata('success', 'Admin berhasil dibuat.');
				redirect('admin/admins');
			}
		}

		$this->load->view('admin/layout/header', $data);
		$this->load->view('admin/layout/sidebar', $data);
		$this->load->view('admin/admins/form', $data);
		$this->load->view('admin/layout/footer');
	}

	public function edit($id = null)
	{
		if (!$id) show_404();

		$data['admin'] = $this->admin;
		$data['page_title'] = 'Edit Admin';
		$data['admin_data'] = $this->Admin_model->get_by_id($id);
		$data['mode'] = 'edit';

		if (!$data['admin_data']) show_404();

		if ($this->input->method() === 'post') {
			$this->form_validation->set_rules('name', 'Nama', 'required|max_length[255]');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('role', 'Role', 'required|in_list[superadmin,admin]');

			// Check email uniqueness (excluding current admin)
			$new_email = $this->input->post('email');
			if ($new_email !== $data['admin_data']->email) {
				if ($this->Admin_model->email_exists($new_email, $id)) {
					$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[admins.email]');
				}
			}

			if ($this->form_validation->run()) {
				$update = [
					'name'  => $this->input->post('name'),
					'email' => $this->input->post('email'),
					'role'  => $this->input->post('role')
				];

				if ($this->input->post('password')) {
					$update['password'] = $this->input->post('password');
				}

				$this->Admin_model->update($id, $update);

				$this->session->set_flashdata('success', 'Admin berhasil diperbarui.');
				redirect('admin/admins');
			}
		}

		$this->load->view('admin/layout/header', $data);
		$this->load->view('admin/layout/sidebar', $data);
		$this->load->view('admin/admins/form', $data);
		$this->load->view('admin/layout/footer');
	}

	public function delete($id = null)
	{
		if (!$id) show_404();

		if ($id == $this->admin['id']) {
			$this->session->set_flashdata('error', 'Tidak dapat menghapus akun sendiri.');
			redirect('admin/admins');
		}

		$this->Admin_model->delete($id);
		$this->session->set_flashdata('success', 'Admin berhasil dihapus.');
		redirect('admin/admins');
	}
}
