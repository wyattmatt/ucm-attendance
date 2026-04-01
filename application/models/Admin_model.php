<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_model extends CI_Model
{

    public function login($email, $password)
    {
        $admin = $this->db->get_where('admins', ['email' => $email])->row();
        if ($admin && password_verify($password, $admin->password)) {
            return $admin;
        }
        return false;
    }

    public function get_all()
    {
        return $this->db->order_by('role', 'ASC')->order_by('name', 'ASC')->get('admins')->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('admins', ['id' => (int)$id])->row();
    }

    public function email_exists($email, $exclude_id = null)
    {
        if ($exclude_id) {
            $this->db->where('id !=', (int)$exclude_id);
        }
        return $this->db->where('email', $email)->count_all_results('admins') > 0;
    }

    public function create($data)
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('admins', $data);
    }

    public function update($id, $data)
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->where('id', (int)$id)->update('admins', $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', (int)$id)->delete('admins');
    }
}
