<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {

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
		$this->load->model('Customer_model');
		date_default_timezone_set('Asia/Jakarta');
	}

	public function index()
	{	
		//check session here

		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{	

			$data = new stdClass();
			$data->title = 'Menu Customer';
			$data->page_active = 'Customer';
			$data->role = $_SESSION['role'];
			$data->username = $_SESSION['username'];
			$data->logged_in = $_SESSION['logged_in'];
			$data->fullname = $_SESSION['fullname'];

			$this->load->view('header', $data);

			$this->load->view('menu_customer');

		}else{
			$data = new stdClass();
			$data->title = 'LOGIN';
			$this->load->view('login_form', $data);
		}
	}

	public function master_customer(){

		//check session here

		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{	

			$data = new stdClass();
			$data->title = 'Master Customer';
			$data->page_active = 'master';
			$data->role = $_SESSION['role'];
			$data->username = $_SESSION['username'];
			$data->logged_in = $_SESSION['logged_in'];
			$data->fullname = $_SESSION['fullname'];
			$this->load->view('header', $data);

			$this->load->view('customer_v', $data);

		}else{
			$data = new stdClass();
			$data->title = 'LOGIN';
			$this->load->view('login_form', $data);
		}

	}

	public function get_data_customer()
	{	
		if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
			$list = $this->Customer_model->get_datatables();
			$data = array();
			$no = $_POST['start'];
			foreach ($list as $field) {
				$no++;
				$row = array();
				$row['no'] = $no;
				$row['id_customer'] = $field->id_customer;
				$row['nama_customer'] = $field->nama_customer;
				$row['alamat_customer'] = $field->alamat_customer;
				$row['telepon_customer'] = $field->telepon_customer;
				$row['status'] = $field->status;
			
				$data[] = $row;
				
			}

			$output = array(
				"draw"=> $_POST['draw'], 
				"recordsTotal" =>$this->Customer_model->count_all(),
				"recordsFiltered"=>$this->Customer_model->count_filtered(),
				"data"=>$data,
			);
			echo json_encode($output);
		}else{
			show_404();
		}
	
	}

	public function submit_customer()
	{

		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{
			if($this->input->post(null)){
				 // validation
				$this->form_validation->set_rules('nama', 'Nama', 'required|max_length[50]',
		                array('required' => '%s harap diinput'));
		        $this->form_validation->set_rules('alamat', 'Alamat', 'required|max_length[250]',
		                array('required' => '%s harap diinput.'));
		        $this->form_validation->set_rules('telepon', 'Telepon', 'required|max_length[50]|numeric',
		                array('required' => '%s harap diinput.'));

				

				if ($this->form_validation->run() == FALSE){ //if can't pass validation

		            $errors = validation_errors();
		            $respons_ajax['type'] = 'error';
		            $respons_ajax['pesan'] = $errors;
		            echo json_encode($respons_ajax);

		        }else{
		        	$id = $this->_get_id();
					$nama = $this->input->post('nama', TRUE);
					$alamat = strtoupper($this->input->post('alamat', TRUE));
					$telepon = $this->input->post('telepon', TRUE);

					if($this->Customer_model->new_Customer($id, $nama, $alamat, $telepon)){
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
		$id = uniqid();

		return $id;
	}

	public function get_data($id = NULL){

		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{
			if($id == null){
				show_404();
			}else{
				$id = $this->uri->segment(3);

				if($this->Customer_model->get_data($id)){

					echo json_encode($this->Customer_model->get_data($id));
				}else{
					echo json_encode($this->Customer_model->get_data($id));
				}
				
			}
		}else{
			show_404();
		}
	}

	public function edit_Customer()
	{

		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{
			if($this->input->post(null)){


				$status = $this->input->post('status');
				$id = $this->input->post('id_customer', TRUE);
				if($this->Customer_model->edit_customer($id, $status)){
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

				if($this->Customer_model->hapus_Customer($id)){
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
