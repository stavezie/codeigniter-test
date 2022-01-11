<?php

class Table_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function getRecords()
	{
		$this->db->select('*');
		$this->db->from('records');
		$this->db->join('categories', 'categories.id = records.catid');

		$query = $this->db->get();

		return $query->result_array();
	}

	public function getCategories()
	{
		$query = $this->db->get('categories');
		return $query->result_array();
	}


	public function insertRecord($name, $catid, $is_bought, $tmp_id)
	{
		$query="INSERT INTO `records`( `name`, `catid`, `is_bought`, `tmp_id`) VALUES ('$name','$catid','$is_bought', '$tmp_id')";
		$this->db->query($query);
	}

	public function insertCategory($catname, $tmp_id)
	{
		$query="INSERT INTO `categories`( `catname`, `tmp_id`)  VALUES ('$catname', $tmp_id)";
		$this->db->query($query);
	}

	public function deleteRecord($id)
	{
		$this->db->delete('records', array('tmp_id' => $id));
	}

	public function updateIsBought($state, $id)
	{
		$this->db->set('is_bought', $state);
		$this->db->where('tmp_id', $id);
		$this->db->update('records');
	}

}
