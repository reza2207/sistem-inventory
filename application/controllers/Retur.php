<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Retur extends CI_Controller {

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
		$this->load->model('Barang_model');
		$this->load->model('Barang_masuk_model');
		$this->load->model('Barang_keluar_model');
		$this->load->helper('terbilang_helper');
		$this->load->helper('tanggal_helper');
		$this->load->library('Pdf');
		$this->load->model('Customer_model');
		$this->load->model('Retur_masuk_model');
		$this->load->model('Retur_keluar_model');
		date_default_timezone_set('Asia/Jakarta');
		
	}

	public function index()
	{	
		//check session here

		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{	

			$data = new stdClass();
			$data->title = 'Menu Barang';
			$data->page_active = 'barang';
			$data->role = $_SESSION['role'];
			$data->username = $_SESSION['username'];
			$data->logged_in = $_SESSION['logged_in'];
			$data->fullname = $_SESSION['fullname'];

			$this->load->view('header', $data);

			$this->load->view('menu_barang');

		}else{
			$data = new stdClass();
			$data->title = 'LOGIN';
			$this->load->view('login_form', $data);
		}
	}


	protected function _get_data_supplier()
	{
		return $this->Supplier_model->get_data_supplier();
	}

	//mulai transaksi
	//
	//
	public function masuk() //halaman penerimaan barang
	{
		//check session here

		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{	

			if($_SESSION['role'] != 'admin'){
				$data = new stdClass();
				$data->title = 'Retur Barang Masuk';
				$data->page_active = 'return';
				$data->role = $_SESSION['role'];
				$data->username = $_SESSION['username'];
				$data->logged_in = $_SESSION['logged_in'];
				$data->fullname = $_SESSION['fullname'];
				$data->supplier = $this->_get_data_supplier();
				$data->barang = $this->Barang_model->get_list();
				$this->load->view('header', $data);

				$this->load->view('return_masuk_v', $data);
			}else{
				show_404();
			}
		}else{
			$data = new stdClass();
			$data->title = 'LOGIN';
			$this->load->view('login_form', $data);
		}
	}

	public function get_data_retur_masuk()
	{	
		if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
			$list = $this->Retur_masuk_model->get_datatables();
			$data = array();
			$no = $_POST['start'];
			foreach ($list as $field) {
				$no++;
				$row = array();
				$row['no'] = $no;
				$row['id_retur_barang_masuk'] = $field->id_retur_barang_masuk;
				$row['nama_supplier'] = $field->nama_supplier;
				$row['tgl_retur'] = $field->tgl_retur;
				$row['no_surat_jalan'] = $field->no_surat_jalan;
				$row['tgl_surat_jalan'] = $field->tgl_surat_jalan;
				$row['status'] = $field->status;
			
				$data[] = $row;
				
			}

			$output = array(
				"draw"=> $_POST['draw'], 
				"recordsTotal" =>$this->Retur_masuk_model->count_all(),
				"recordsFiltered"=>$this->Retur_masuk_model->count_filtered(),
				"data"=>$data,
			);
			echo json_encode($output);
		}else{
			show_404();
		}
	}

	public function submit_retur_masuk()
	{

		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{


			if($this->input->post(null)){
			 // validation
			 
				$this->form_validation->set_rules('jumlah[]', 'Jumlah', 'required|trim|numeric|xss_clean|greater_than[0]');
				$this->form_validation->set_rules('namabarang[]', 'Nama Barang', 'required|trim|xss_clean');
				$this->form_validation->set_rules('tgl_retur', 'Tgl. Retur', 'required|trim|xss_clean');
				$this->form_validation->set_rules('no_sj', 'No. Surat Jalan', 'required|trim|xss_clean');
				$this->form_validation->set_rules('keterangan','Keterangan', 'trim|xss_clean|max_length[200]');
				
				if ($this->form_validation->run() == FALSE){ //if can't pass validation
		            $errors = validation_errors();
		            $respons_ajax['type'] = 'error';
		            $respons_ajax['pesan'] = $errors;
		            echo json_encode($respons_ajax);

	        	}else{

	        		//array
	        		$namabarang = $this->input->post('namabarang');
					//bukan array
					
					$nosj = $this->input->post('no_sj');
					$keterangan = $this->input->post('keterangan');
					$tglretur = tanggal1($this->input->post('tgl_retur'));
					$id = uniqid();
					$result = array();
					$no = 0;
					$ids = 0;
					for($i = 0;$i< count($namabarang);$i++){
						$no++;
		     			$result[] = array(
		      			"id_barang"  => $_POST['namabarang'][$i],
		      			"qty"  => $_POST['jumlah'][$i],
		      			"id_retur_barang_masuk" =>$id,
		      			"no_surat_jalan"=>$nosj,
		      			"id_detail_retur_barang_masuk" => $id.'-'.STR_PAD((int) $no, 3, "0", STR_PAD_LEFT)
	     				);
	    			}   

					if($this->db->insert_batch('tb_detail_retur_barang_masuk', $result) && $this->Retur_masuk_model->new_data($id, $nosj, $tglretur,$keterangan)){
						$respons_ajax['type'] = 'success';
						$respons_ajax['pesan'] = 'Success';
						echo json_encode($respons_ajax);
						$log = $_SESSION['username'].' ('.$_SESSION['fullname'].')'.' Menambahkan data retur barang masuk. No Surat Jalan: '.$nosj;
						$id = uniqid();
						$this->User_model->log_user($log, $id);
					}else{
						$respons_ajax['type'] = 'error';
						$respons_ajax['pesan'] = 'Failed';
						echo json_encode($respons_ajax);
					}
				}
			}

		}
	}


	public function get_data_id_retur_masuk()
	{
		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{
			if($this->input->post(null)){
				$id = $this->input->post('id');

				echo json_encode($this->Retur_masuk_model->get_data_id($id)->row());
			}else{

				show_404();
			}
		}

	}

	public function get_sj(){
		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{
			if($this->input->post(null)){

				$id = $this->input->post('id');

				if($this->Barang_masuk_model->get_sj($id))
				{	
					if($this->Barang_masuk_model->get_sj($id)->num_rows() > 0){
						$respons_ajax['type'] = 'success';
						$respons_ajax['data'] = $this->Barang_masuk_model->get_sj($id)->result();
						echo json_encode($respons_ajax);
					}else{
						$respons_ajax['type'] = 'error';
						$respons_ajax['data'] = 'Data tidak ada';

						echo json_encode($respons_ajax);
					}
				}else{
						$respons_ajax['type'] = 'error';
						$respons_ajax['data'] = null;
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

	// end penerimaan barang
	// 
	public function keluar() //halaman pengeluaran barang
	{
		//check session here

		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{	

			$data = new stdClass();
			$data->title = 'Retur Barang Keluar';
			$data->page_active = 'return';
			$data->role = $_SESSION['role'];
			$data->username = $_SESSION['username'];
			$data->logged_in = $_SESSION['logged_in'];
			$data->fullname = $_SESSION['fullname'];
			$data->customer = $this->Customer_model->get_list()->result();
			$data->barang = $this->Barang_model->get_list_keluar()->result();
			$this->load->view('header', $data);

			$this->load->view('retur_keluar_v', $data);

		}else{
			$data = new stdClass();
			$data->title = 'LOGIN';
			$this->load->view('login_form', $data);
		}
	}

	public function get_data_retur_keluar()
	{	
		if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
			$list = $this->Retur_keluar_model->get_datatables();
			$data = array();
			$no = $_POST['start'];
			foreach ($list as $field) {
				$no++;
				$row = array();
				$row['no'] = $no;
				$row['id_retur_barang_keluar'] = $field->id_retur_barang_keluar;
				$row['no_faktur'] = $field->no_faktur;
				$row['tgl_faktur'] = $field->tgl_faktur;
				$row['tgl_retur'] = $field->tgl_retur;
				$row['nama_customer'] = $field->nama_customer;
				$row['status'] = $field->status;
				$data[] = $row;
				
			}

			$output = array(
				"draw"=> $_POST['draw'], 
				"recordsTotal" =>$this->Retur_keluar_model->count_all(),
				"recordsFiltered"=>$this->Retur_keluar_model->count_filtered(),
				"data"=>$data,
			);
			echo json_encode($output);
		}else{
			show_404();
		}
	}

	public function submit_retur_keluar()
	{
		if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) 
		{
			if($this->input->post(null)){
				$this->form_validation->set_rules('no_faktur', 'No. Faktur', 'required|trim|xss_clean');
				$this->form_validation->set_rules('jumlah[]', 'Jumlah', 'required|trim|numeric|xss_clean|greater_than[0]');
				$this->form_validation->set_rules('namabarang[]', 'Nama Barang', 'required|trim|xss_clean');
				$this->form_validation->set_rules('tgl_retur', 'Tgl. Retur', 'required|trim|xss_clean');
				$this->form_validation->set_rules('keterangan','Keterangan', 'trim|xss_clean|max_length[200]');
				
				if ($this->form_validation->run() == FALSE){ //if can't pass validation

		            $errors = validation_errors();
		            $respons_ajax['type'] = 'error';
		            $respons_ajax['pesan'] = $errors;
		            echo json_encode($respons_ajax);

		        }else{
		        	//array
	        		$namabarang = $this->input->post('namabarang');
					//bukan array
					
					$nofaktur = $this->input->post('no_faktur');
					$keterangan = $this->input->post('keterangan');
					$tglretur = tanggal1($this->input->post('tgl_retur'));
					$id = uniqid();
					$result = array();
					$no = 0;
					$ids = 0;
					for($i = 0;$i< count($namabarang);$i++){
						$no++;
		     			$result[] = array(
		      			"id_barang"  => $_POST['namabarang'][$i],
		      			"qty"  => $_POST['jumlah'][$i],
		      			"id_retur_barang_keluar" =>$id,
		      			"no_faktur"=>$nofaktur,
		      			"id_detail_retur_barang_keluar" => $id.'-'.STR_PAD((int) $no, 3, "0", STR_PAD_LEFT)
	     				);
	    			}   

					if($this->db->insert_batch('tb_detail_retur_barang_keluar', $result) && $this->Retur_keluar_model->new_data($id, $nofaktur, $tglretur,$keterangan)){
						$respons_ajax['type'] = 'success';
						$respons_ajax['pesan'] = 'Success';
						echo json_encode($respons_ajax);
						$log = $_SESSION['username'].' ('.$_SESSION['fullname'].')'.' Menambahkan data retur barang keluar. No Faktur: '.$nofaktur;
						$id = uniqid();
						$this->User_model->log_user($log, $id);
					}else{
						$respons_ajax['type'] = 'error';
						$respons_ajax['pesan'] = 'Failed';
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

	public function get_data_id_retur_keluar()
	{
		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{
			if($this->input->post(null)){
				$id = $this->input->post('id');

				echo json_encode($this->Retur_keluar_model->get_data_id($id)->row());
			}else{

				show_404();
			}
		}

	}

	public function approve_trans()
	{
		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{
			if($this->input->post(null)){
				
				$id = $this->input->post('id');
				$table = $this->input->post('table');
				$status = $this->input->post('status');

				if($table == 'Penerimaan'){
					$model = 'Retur_masuk_model';
				}elseif($table == 'Pengeluaran'){
					$model = 'Retur_keluar_model';
				}
				if($this->$model->approve_trans($id, $status))
				{
					$respons_ajax['type'] = 'success';
					$respons_ajax['pesan'] = 'Success';
					echo json_encode($respons_ajax);
					$log = $_SESSION['username'].' ('.$_SESSION['fullname'].')'.' Meng'.$status.' '.$table.' barang. id: '.$id;
						$id = uniqid();
						$this->User_model->log_user($log, $id);
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
	

	public function get_faktur(){
		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{
			if($this->input->post(null)){

				$id = $this->input->post('id');

				if($this->Barang_keluar_model->get_faktur($id))
				{	
					if($this->Barang_keluar_model->get_faktur($id)->num_rows() > 0){
						$respons_ajax['type'] = 'success';
						$respons_ajax['data'] = $this->Barang_keluar_model->get_faktur($id)->result();
						echo json_encode($respons_ajax);
					}else{
						$respons_ajax['type'] = 'error';
						$respons_ajax['data'] = 'Data tidak ada';

						echo json_encode($respons_ajax);
					}
				}else{
						$respons_ajax['type'] = 'error';
						$respons_ajax['data'] = null;
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
