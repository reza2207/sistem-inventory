<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
		
		//$this->load->model('user_model');
		$this->load->library("Phpmailer_library");
		date_default_timezone_set('Asia/Jakarta');
		
	}

	public function index()
	{	
		//check session here

		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{	

			$data = new stdClass();
			$data->title = 'Home';
			$data->page_active = 'home';
			$data->role = $_SESSION['role'];
			$data->username = $_SESSION['username'];
			$data->logged_in = $_SESSION['logged_in'];
			$data->fullname = $_SESSION['fullname'];

			$this->load->view('header', $data);

			$this->load->view('home');

		}else{
			$data = new stdClass();
			$data->title = 'LOGIN';
			$this->load->view('login_form', $data);
		}
	}

	
}
