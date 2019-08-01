<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct(){

		parent::__construct();
		
		$this->load->model('User_model');
		$this->load->model('Supplier_model');
		date_default_timezone_set('Asia/Jakarta');
		
		
	}

	public function index()
	{	
		//check session here

		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{	
			if($_SESSION['role'] == 'admin'){
				$data = new stdClass();
				$data->title = 'Master Supplier';
				$data->page_active = 'supplier';
				$data->role = $_SESSION['role'];
				$data->username = $_SESSION['username'];
				$data->logged_in = $_SESSION['logged_in'];
				$data->fullname = $_SESSION['fullname'];

				$this->load->view('header', $data);

				$this->load->view('supplier_v');
			}else{
				show_404();
			}

		}else{
			$data = new stdClass();
			$data->title = 'LOGIN';
			$this->load->view('login_form', $data);
		}
	}

	public function get_data_supplier()
	{	
		if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
			$list = $this->Supplier_model->get_datatables();
			$data = array();
			$no = $_POST['start'];
			foreach ($list as $field) {
				$no++;
				$row = array();
				$row['no'] = $no;
				$row['id_supplier'] = $field->id_supplier;
				$row['nama_supplier'] = $field->nama_supplier;
				$row['alamat_supplier'] = $field->alamat_supplier;
				$row['telepon_supplier'] = $field->telepon_supplier;
				$row['status'] = ucwords($field->status);
			
				$data[] = $row;
				
			}

			$output = array(
				"draw"=> $_POST['draw'], 
				"recordsTotal" =>$this->Supplier_model->count_all(),
				"recordsFiltered"=>$this->Supplier_model->count_filtered(),
				"data"=>$data,
			);
			echo json_encode($output);
		}else{
			show_404();
		}
	
	}

	public function submit_supplier()
	{

		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{
			if($this->input->post(null)){
				 // validation
				$this->form_validation->set_rules('nama_supplier', 'Nama Supplier', 'required|max_length[200]',
		                array('required' => '%s harap diinput'));
		        $this->form_validation->set_rules('alamat', 'Alamat', 'required|max_length[250]',
		                array('required' => '%s harap diinput.'));
		        $this->form_validation->set_rules('telepon', 'Telepon', 'required|max_length[50]',
		                array('required' => '%s harap diinput.'));

				

				if ($this->form_validation->run() == FALSE){ //if can't pass validation

		            $errors = validation_errors();
		            $respons_ajax['type'] = 'error';
		            $respons_ajax['pesan'] = $errors;
		            echo json_encode($respons_ajax);

		        }else{
		        	$id = $this->_get_id();
					$nama = $this->input->post('nama_supplier', TRUE);
					$alamat = $this->input->post('alamat', TRUE);
					$telepon = $this->input->post('telepon', TRUE);

					if($this->Supplier_model->new_supplier($id, $nama, $alamat, $telepon)){
						$respons_ajax['type'] = 'success';
						$respons_ajax['pesan'] = 'Success';
						echo json_encode($respons_ajax);
					}
				}
			}else{
				show_404();

			}	

		}else{
			show_404();
		}
	}

	protected function _get_id()
	{
		if($this->Supplier_model->get_last_id()->num_rows() == 0){
			$id = 'S.01';
			
		}else{
			$lastid = $this->Supplier_model->get_last_id()->row('id_supplier');
			$idl = explode('.',$lastid);
			$id = 'S'.STR_PAD((int) $idl[1]+1, 2, "0", STR_PAD_LEFT);
		}

		return $id;
	}

	public function get_data($id = NULL){

		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{
			if($id == null){
				show_404();
			}else{
				$id = $this->uri->segment(3);

				if($this->Supplier_model->get_data($id)){

					echo json_encode($this->Supplier_model->get_data($id));
				}else{
					echo json_encode($this->Supplier_model->get_data($id));
				}
				
			}
		}else{
			show_404();
		}
	}

	public function edit_supplier()
	{

		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{
			if($this->input->post(null)){
					
				$id = $this->input->post('id_supplier', TRUE);
				$status = $this->input->post('status');
				if($this->Supplier_model->edit_supplier($id, $status)){
					$respons_ajax['type'] = 'success';
					$respons_ajax['pesan'] = 'Success';
					echo json_encode($respons_ajax);
				}else{
					$respons_ajax['type'] = 'error';
					$respons_ajax['pesan'] = 'Failed';
					echo json_encode($respons_ajax);
				}
				
			}else{

				show_404();
			}
		}else{
			show_404();
		}
	}

	public function hapus_data()
	{
		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{
			if($this->input->post(null)){
				$id = $this->input->post('id');

				if($this->Supplier_model->hapus_supplier($id)){
					$respons_ajax['type'] = 'success';
					$respons_ajax['pesan'] = 'Success';
					echo json_encode($respons_ajax);
				}else{
					$respons_ajax['type'] = 'error';
					$respons_ajax['pesan'] = 'Failed';
					echo json_encode($respons_ajax);
				}
			}else{
				show_404();
			}

		}else{
			show_404();
		}

	}
}
