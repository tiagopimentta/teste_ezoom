<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Task_model extends CI_Model
{
	public function getTasks($page, $itemsPerPage)
	{
		$offset = ($page - 1) * $itemsPerPage;
		$query = $this->db->get('tasks', $itemsPerPage, $offset);
		return $query->result();
	}

	public function countTasks()
	{
		return $this->db->count_all('tasks');
	}
	public function getTask($id)
	{
		return $this->db->get_where('tasks', array(
			'id' => $id
		))->row_array();
	}

	public function createTask($request)
	{
		$this->db->insert('tasks', $request);
		$last_id = $this->db->insert_id();

		return $this->db->where('id', $last_id)->get('tasks')->row();
	}

	public function updateTask($id, $request)
	{
		$this->db->where('id', $id)->update('tasks', $request);

		return $this->db->where('id', $id)->get('tasks')->row();
	}

	public function deleteTask($id)
	{
		$this->db->where('id', $id)->delete('tasks');
		return true;
	}

}
