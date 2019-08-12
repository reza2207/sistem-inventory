<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	
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
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct(){

		parent::__construct();
		
		date_default_timezone_set('Asia/Jakarta');
		$this->load->library(array('session'));
		$this->load->model('user_model');
	}	


	public function index()
	{

		//check session here
		if(isset($_SESSION['logged_in']) AND $_SESSION['logged_in'] === true)
		{	
			if($_SESSION['role'] == 'admin'){
				$data = new stdClass();
				$data->title = 'Register';
				$data->role = $_SESSION['role'];

				$data->fullname = $_SESSION['fullname'];
				$this->load->view('header', $data);

				$this->load->view('register_v');
			}else{
				show_404();
			}
		}else{

			$this->load->view('login_form');
		}
	}

	public function submit_user()
	{

		if(isset($_SESSION['logged_in']) AND $_SESSION['logged_in'] === true)
		{	
			if($this->input->post(null)){
				//initialize button submit
				
				$this->form_validation->set_rules('username', 'Username', 'required|is_unique[tb_user.username]', 
						array('is_unique'=>'%s sudah terdaftar.'));
		        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]',
		                array('required' => '%s harap dimasukkan.',
		                	'min_length'=>'%s minimal 6 karakter.',
		                )
		        );
		        $this->form_validation->set_rules('fullname', 'Full Name', 'required|trim|min_length[3]');
		        $this->form_validation->set_rules('role', 'Role', 'required');

				
		       	if ($this->form_validation->run() == FALSE){

		            $errors = validation_errors();
		            $respons_ajax['type'] = 'error';
		            $respons_ajax['pesan'] = $errors;
		            echo json_encode($respons_ajax);

		        }else{
		        	$username = $this->input->post('username',TRUE);
					$password = $this->input->post('password');
					$fullname = ucwords($this->input->post('fullname', TRUE));
					$role = $this->input->post('role');
					$this->user_model->create_user($username, $password, $fullname, $role);
					$respons_ajax['type'] = 'success';
					$respons_ajax['pesan'] = 'Success';
					echo json_encode($respons_ajax);		
				}

			}
		}else{
			show_404();
		}
		
	}
	public function submit_login() 
	{
		
		if($this->input->post() != NULL){
			// create the data object
			$data = new stdClass();
			
			// load form helper and validation library
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			// set validation rules
			$this->form_validation->set_rules('username', 'Username', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');
			
			if ($this->form_validation->run() == false) {
				
				$data->type = 'error';
				$data->pesan = validation_errors();
				echo json_encode($data);
			} else {

			

				// set variables from the form
				$username = $this->input->post('username');
				$password = $this->input->post('password');
				
				if ($this->user_model->resolve_user_login($username, $password)) {
					
					$user    = $this->user_model->get_user($username);
					
					// set session user datas
					$_SESSION['username']     = (string)$user->username;
					$_SESSION['logged_in']    = (bool)true;
					$_SESSION['role']     = $user->peran;
					$_SESSION['fullname'] = (string)$user->fullname;

					$respons_ajax['type'] = 'success';
					$respons_ajax['pesan'] = "Welcome ".$_SESSION['fullname'];
					echo json_encode($respons_ajax);
					
				} else {
					
					// login failed
					$data->type = 'error';
					$data->pesan = 'Username atau Password Salah.';
					echo json_encode($data);
					
				}

			}
			
		}else{
			show_404();
		}
		
	}

	public function logout() {
		
		// create the data object
		$data = new stdClass();
		
		if (isset($_SESSION['logged_in'])  AND $_SESSION['logged_in'] === true) {
			
			// remove session datas
			foreach ($_SESSION as $key => $value) {
				unset($_SESSION[$key]);
			}
			redirect(base_url());
			
		} else {
			
			show_404();	
			
		}
		
	}

	public function forget_password(){

	}

	private function _send_email()
	{
		date_default_timezone_set('Asia/Jakarta');
		$this->load->library('email');
		
		$config['protocol'] = 'sendmail';
		$config['mailpath'] = '/usr/sbin/sendmail';
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = TRUE;

		$this->email->initialize($config);

		$this->email->from('admin@inventorysuryaindah.com', 'Administrator');
		$this->email->to('reza.2207@gmail.com');
		/*$this->email->cc('another@another-example.com');
		$this->email->bcc('them@their-example.com');*/

		$this->email->subject('Email Test');
		$this->email->message('<b>Testing the email class.</b>');

		$this->email->send();
	}

	public function log()
	{
		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{	

			if($_SESSION['role'] == 'admin'){
				$data = new stdClass();
				$data->title = 'Log User';
				$data->page_active = 'user';
				$data->role = $_SESSION['role'];
				$data->username = $_SESSION['username'];
				$data->logged_in = $_SESSION['logged_in'];
				$data->fullname = $_SESSION['fullname'];

				$this->load->view('header', $data);

				$this->load->view('log_user_v');
			}else{
				show_404();
			}

		}else{
			$data = new stdClass();
			$data->title = 'LOGIN';
			$this->load->view('login_form', $data);
		}
	}

	

	public function get_data_log()
	{	
		$this->load->model('Log_model');
		if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
			$list = $this->Log_model->get_datatables();
			$data = array();
			$no = $_POST['start'];
			foreach ($list as $field) {
				$no++;
				$row = array();
				$row['no'] = $no;
				$row['log'] = $field->log;
				$row['tgl_log'] = $field->tgl_log;
				$data[] = $row;
				
			}

			$output = array(
				"draw"=> $_POST['draw'], 
				"recordsTotal" =>$this->Log_model->count_all(),
				"recordsFiltered"=>$this->Log_model->count_filtered(),
				"data"=>$data,
			);
			echo json_encode($output);
		}else{
			show_404();
		}
	
	}

}