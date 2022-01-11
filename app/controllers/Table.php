<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Table extends CI_Controller {

	public $id = 0;


	public function __construct()
	{
		parent::__construct();
		$this->load->model('table_model');

	}


	public function insertRecord()
	{
		$name=$this->input->post('data[name]');
		$catid=$this->input->post('data[catid]');
		$is_bought=$this->input->post('data[is_bought]');
		$tmp_id=$this->input->post('data[tmp_id]');
		$this->table_model->insertRecord($name,$catid,$is_bought,$tmp_id);
		echo json_encode(array(
			"statusCode"=>200
		));
	}

	public function insertCategory()
	{
		$catname=$this->input->post('catname');
		$this->table_model->insertCategory($catname);
		echo json_encode(array(
			"statusCode"=>200
		));
	}

	public function deleteRecord()
	{
		$id=$this->input->post('id');
		$this->table_model->deleteRecord($id);
	}

	public function updateIsBought()
	{
		$state = $this->input->post('newState');
		$id = $this->input->post('id');
		$this->table_model->updateIsBought($state, $id);
	}


	public function index()
	{
		$data['records'] = $this->table_model->getRecords();
		$data['categories'] = $this->table_model->getCategories();

		$this->load->view('table', $data);
	}


}
