<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Attendance_model extends CI_Model
{

	public function record($data)
	{
		$data['attended_at'] = date('Y-m-d H:i:s');
		return $this->db->insert('attendances', $data);
	}

	public function get_by_event($event_id)
	{
		return $this->db->select('attendances.*, participants.name as participant_name, event_sessions.session_name')
			->from('attendances')
			->join('participants', 'participants.id = attendances.participant_id', 'left')
			->join('event_sessions', 'event_sessions.id = attendances.session_id', 'left')
			->where('attendances.event_id', $event_id)
			->order_by('attendances.attended_at', 'DESC')
			->get()
			->result();
	}

	public function check_duplicate($event_id, $input_value, $session_id = null)
	{
		$where = [
			'event_id' => $event_id,
			'input_value' => $input_value
		];
		if ($session_id) {
			$where['session_id'] = $session_id;
		}
		return $this->db->get_where('attendances', $where)->row();
	}

	public function find_participant($event_id, $unique_code)
	{
		return $this->db->get_where('participants', [
			'event_id' => $event_id,
			'unique_code' => $unique_code
		])->row();
	}

	public function get_stats($event_id)
	{
		$total_participants = $this->db->where('event_id', $event_id)->count_all_results('participants');

		// Count unique participants who attended (by distinct input_value)
		$this->db->select('COUNT(DISTINCT input_value) as cnt')
			->from('attendances')
			->where('event_id', $event_id);
		$total_attended = (int)$this->db->get()->row()->cnt;

		return [
			'total_participants' => $total_participants,
			'total_attended' => $total_attended
		];
	}

	public function get_attendance_with_participants($event_id, $session_id = null)
	{
		$participants = $this->db->where('event_id', $event_id)->get('participants')->result();

		$this->db->where('event_id', $event_id);
		if ($session_id) {
			$this->db->where('session_id', $session_id);
		}
		$attendances = $this->db->get('attendances')->result();

		$attended_map = [];
		foreach ($attendances as $a) {
			$key = $a->input_value;
			if (!isset($attended_map[$key])) {
				$attended_map[$key] = $a->attended_at;
			}
		}

		foreach ($participants as &$p) {
			$p->is_present = isset($attended_map[$p->unique_code]);
			$p->attended_at = $attended_map[$p->unique_code] ?? null;
		}

		return $participants;
	}

	public function count_by_event($event_id)
	{
		return $this->db->where('event_id', $event_id)->count_all_results('attendances');
	}
}
