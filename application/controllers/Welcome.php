<?php
defined('BASEPATH') or exit('Tidak ada akses langsung yang diizinkan');

class Welcome extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url'));

		$this->load->library('form_validation');

		$this->load->model('crud_model');
	}

	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function insert()
	{
		if ($this->input->is_ajax_request()) {
			$this->form_validation->set_rules('name', 'Name', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			if ($this->form_validation->run() == FALSE) {
				$data = array('responce' => 'error', 'message' => validation_errors());
			} else {
				$ajax_data = $this->input->post();
				if ($this->crud_model->insert_entry($ajax_data)) {
					$data = array('responce' => 'success', 'message' => 'Data berhasil ditambahkan');
				} else {
					$data = array('responce' => 'error', 'message' => 'Gagal menambahkan data');
				}
			}

			echo json_encode($data);
		} else {
			echo "Tidak ada akses langsung yang diizinkan";
		}
	}

	public function fetch()
	{
		if ($this->input->is_ajax_request()) {
			// if ($posts = $this->crud_model->get_entries()) {
			// 	$data = array('responce' => 'success', 'posts' => $posts);
			// }else{
			// 	$data = array('responce' => 'error', 'message' => 'Failed to fetch data');
			// }
			$posts = $this->crud_model->get_entries();
			$data = array('responce' => 'success', 'posts' => $posts);
			echo json_encode($data);
		} else {
			echo "Tidak ada akses langsung yang diizinkan";
		}
	}

	public function delete()
	{
		if ($this->input->is_ajax_request()) {
			$del_id = $this->input->post('del_id');

			if ($this->crud_model->delete_entry($del_id)) {
				$data = array('responce' => 'success');
			} else {
				$data = array('responce' => 'error');
			}

			echo json_encode($data);
		} else {
			echo "Tidak ada akses langsung yang diizinkan";
		}
	}

	public function edit()
	{
		if ($this->input->is_ajax_request()) {
			$edit_id = $this->input->post('edit_id');

			if ($post = $this->crud_model->edit_entry($edit_id)) {
				$data = array('responce' => 'success', 'post' => $post);
			} else {
				$data = array('responce' => 'error', 'message' => 'gagal mengambil data');
			}
			echo json_encode($data);
		} else {
			echo "Tidak ada akses langsung yang diizinkan";
		}
	}

	public function update()
	{
		if ($this->input->is_ajax_request()) {
			$this->form_validation->set_rules('edit_name', 'Name', 'required');
			$this->form_validation->set_rules('edit_email', 'Email', 'required|valid_email');
			if ($this->form_validation->run() == FALSE) {
				$data = array('responce' => 'error', 'message' => validation_errors());
			} else {
				$data['id'] = $this->input->post('edit_record_id');
				$data['name'] = $this->input->post('edit_name');
				$data['email'] = $this->input->post('edit_email');

				if ($this->crud_model->update_entry($data)) {
					$data = array('responce' => 'success', 'message' => 'Pembaruan data Berhasil');
				} else {
					$data = array('responce' => 'error', 'message' => 'Gagal memperbarui data');
				}
			}

			echo json_encode($data);
		} else {
			echo "Tidak ada akses langsung yang diizinkan";
		}
	}
}