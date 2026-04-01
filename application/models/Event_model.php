<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Event_model extends CI_Model
{

	public function get_active_events()
	{
		$this->update_statuses();
		return $this->db->where_in('status', ['upcoming', 'ongoing'])
			->order_by('start_date', 'ASC')
			->order_by('start_time', 'ASC')
			->get('events')
			->result();
	}

	public function get_all()
	{
		$this->update_statuses();
		return $this->db->order_by('created_at', 'DESC')
			->get('events')
			->result();
	}

	public function get_by_id($id)
	{
		return $this->db->get_where('events', ['id' => $id])->row();
	}

	public function create($data)
	{
		$data['created_at'] = date('Y-m-d H:i:s');
		$this->db->insert('events', $data);
		return isset($data['id']) ? $data['id'] : null;
	}

	public function update($id, $data)
	{
		$data['updated_at'] = date('Y-m-d H:i:s');
		return $this->db->where('id', $id)->update('events', $data);
	}

	public function delete($id)
	{
		return $this->db->where('id', $id)->delete('events');
	}

	// --- Sessions ---

	public function get_sessions($event_id)
	{
		return $this->db->where('event_id', $event_id)
			->order_by('session_order', 'ASC')
			->get('event_sessions')
			->result();
	}

	public function add_session($data)
	{
		return $this->db->insert('event_sessions', $data);
	}

	public function delete_sessions($event_id)
	{
		return $this->db->where('event_id', $event_id)->delete('event_sessions');
	}

	// --- Participants ---

	public function get_participants($event_id)
	{
		return $this->db->where('event_id', $event_id)
			->order_by('id', 'ASC')
			->get('participants')
			->result();
	}

	public function count_participants($event_id)
	{
		return $this->db->where('event_id', $event_id)->count_all_results('participants');
	}

	public function add_participant($data)
	{
		return $this->db->insert('participants', $data);
	}

	public function delete_participants($event_id)
	{
		return $this->db->where('event_id', $event_id)->delete('participants');
	}

	// --- Status Auto-Update ---

	public function update_statuses()
	{
		$today = date('Y-m-d');

		// upcoming -> ongoing (start_date reached, end_date not passed)
		$this->db->where('status', 'upcoming')
			->where('start_date <=', $today)
			->where('end_date >=', $today)
			->update('events', ['status' => 'ongoing']);

		// ongoing -> completed (end_date passed)
		$this->db->where('status !=', 'completed')
			->where('end_date <', $today)
			->update('events', ['status' => 'completed']);
	}
}
