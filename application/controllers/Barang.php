<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang extends CI_Controller {

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

	public function master_barang(){

		//check session here

		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{	

			if($_SESSION['role'] == 'admin'){
				$data = new stdClass();
				$data->title = 'Master Barang';
				$data->page_active = 'barang';
				$data->role = $_SESSION['role'];
				$data->username = $_SESSION['username'];
				$data->logged_in = $_SESSION['logged_in'];
				$data->fullname = $_SESSION['fullname'];
				$data->supplier = $this->_get_data_supplier();
				$this->load->view('header', $data);

				$this->load->view('barang_v', $data);
			}else{
				show_404();
			}

		}else{
			$data = new stdClass();
			$data->title = 'LOGIN';
			$this->load->view('login_form', $data);
		}

	}

	public function get_data_barang()
	{	
		if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
			$list = $this->Barang_model->get_datatables();
			$data = array();
			$no = $_POST['start'];
			foreach ($list as $field) {
				$no++;
				$row = array();
				$row['no'] = $no;
				$row['id_barang'] = $field->id_barang;
				$row['nama_barang'] = $field->nama_barang;
				$row['satuan'] = $field->satuan;
				$row['min'] = $field->min;
				$row['max'] = $field->max;
				$row['nama_supplier'] = $field->nama_supplier;
				$row['id_supplier'] = $field->id_supplier;
				$row['status'] = $field->status;
				$data[] = $row;
				
			}

			$output = array(
				"draw"=> $_POST['draw'], 
				"recordsTotal" =>$this->Barang_model->count_all(),
				"recordsFiltered"=>$this->Barang_model->count_filtered(),
				"data"=>$data,
			);
			echo json_encode($output);
		}else{
			show_404();
		}
	
	}

	public function submit_barang()
	{

		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{
			if($this->input->post(null)){
				 // validation
				$this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required|max_length[200]',
		                array('required' => '%s harap diinput'));
		        $this->form_validation->set_rules('satuan', 'Satuan', 'required|max_length[20]',
		        	array('required' => '%s harap diinput.'));
		         $this->form_validation->set_rules('min', 'Minimum Stok', 'required|max_length[10]|greater_than[10]',
		                array('required' => '%s harap diinput.'));
		         $this->form_validation->set_rules('max', 'Maximum Stok', 'required|max_length[10]',
		                array('required' => '%s harap diinput.'));
		        $this->form_validation->set_rules('id_supplier', 'Id Supplier', 'required',
		                array('required' => '%s harap diinput.'));

				

				if ($this->form_validation->run() == FALSE){ //if can't pass validation

		            $errors = validation_errors();
		            $respons_ajax['type'] = 'error';
		            $respons_ajax['pesan'] = $errors;
		            echo json_encode($respons_ajax);

		        }else{
					$nama = $this->input->post('nama_barang', TRUE);
					$satuan = strtoupper($this->input->post('satuan', TRUE));
					$idsupplier = $this->input->post('id_supplier', TRUE);
					$idbarang = $this->_get_id($nama);
					$min = $this->input->post('min');
					$max = $this->input->post('max');

					if($this->Barang_model->new_barang($idbarang, $nama, $satuan, $idsupplier, $min, $max)){
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

	protected function _get_id($nama)
	{
		$abjad = strtoupper(substr($nama, 0, 1));
		if($this->Barang_model->get_last_id($abjad)->num_rows() == 0){
			$id = $abjad.'.001';
			
		}else{
			$lastid = $this->Barang_model->get_last_id($abjad)->row('id_barang');
			$a = explode(".", $lastid);
			$id = $abjad.'.'.STR_PAD((int) $a[1]+1, 3, "0", STR_PAD_LEFT);
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

				if($this->Barang_model->get_data($id)){

					echo json_encode($this->Barang_model->get_data($id));
				}else{
					echo json_encode($this->Barang_model->get_data($id));
				}
				
			}
		}else{
			show_404();
		}
	}

	public function edit_barang()
	{

		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{
			if($this->input->post(null)){

				
	        	$id = $this->input->post('id_barang',TRUE);
				$status = $this->input->post('status', TRUE);
				if($this->Barang_model->edit_barang($id, $status)){
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

	/*public function hapus_data()
	{
		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{
			if($this->input->post(null)){
				$id = $this->input->post('id');

				if($this->Barang_model->hapus_barang($id)){
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
*/
	protected function _get_data_supplier()
	{
		return $this->Supplier_model->get_data_supplier();
	}

	//mulai transaksi
	//
	//
	public function penerimaan() //halaman penerimaan barang
	{
		//check session here

		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{	

			if($_SESSION['role'] != 'admin'){
				$data = new stdClass();
				$data->title = 'Penerimaan Barang';
				$data->page_active = 'transaksi';
				$data->role = $_SESSION['role'];
				$data->username = $_SESSION['username'];
				$data->logged_in = $_SESSION['logged_in'];
				$data->fullname = $_SESSION['fullname'];
				$data->supplier = $this->_get_data_supplier();
				$data->barang = $this->Barang_model->get_list();
				$this->load->view('header', $data);

				$this->load->view('penerimaan_barang_v', $data);
			}else{
				show_404();
			}
		}else{
			$data = new stdClass();
			$data->title = 'LOGIN';
			$this->load->view('login_form', $data);
		}
	}

	public function get_data_barang_masuk()
	{	
		if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
			$list = $this->Barang_masuk_model->get_datatables();
			$data = array();
			$no = $_POST['start'];
			foreach ($list as $field) {
				$no++;
				$row = array();
				$row['no'] = $no;
				$row['id_barang_masuk'] = $field->id_barang_masuk;
				$row['no_surat_jalan'] = $field->no_surat_jalan;
				$row['nama_supplier'] = $field->nama_supplier;
				$row['tgl_surat_jalan'] = $field->tgl_surat_jalan;
				$row['no_po'] = $field->no_po;
				$row['tgl_po'] = $field->tgl_po;
				$row['tgl_masuk'] = $field->tgl_masuk;
				$row['status'] = $field->status;
			
				$data[] = $row;
				
			}

			$output = array(
				"draw"=> $_POST['draw'], 
				"recordsTotal" =>$this->Barang_masuk_model->count_all(),
				"recordsFiltered"=>$this->Barang_masuk_model->count_filtered(),
				"data"=>$data,
			);
			echo json_encode($output);
		}else{
			show_404();
		}
	}

	public function submit_barang_masuk()
	{

		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{
			if($this->input->post(null)){
			 // validation
			 
			 	$this->form_validation->set_rules('harga[]', 'harga', 'required|trim|xss_clean');
				$this->form_validation->set_rules('jumlah[]', 'Jumlah', 'required|trim|numeric|xss_clean');
				$this->form_validation->set_rules('namabarang[]', 'Nama Barang', 'required|trim|xss_clean');
				$this->form_validation->set_rules('no_po', 'No. PO', 'required|trim|xss_clean');
				$this->form_validation->set_rules('tgl_po', 'Tanggal PO', 'required|trim');
				$this->form_validation->set_rules('no_sj', 'No. Surat Jalan', 'required|is_unique[tb_barang_masuk.no_surat_jalan]|trim|xss_clean');
				$this->form_validation->set_rules('tgl_sj', 'Tanggal Surat Jalan', 'required|trim');
				$this->form_validation->set_rules('id_supplier', 'Supplier', 'required|trim');
				$this->form_validation->set_rules('tgl_masuk', 'Tanggal Masuk', 'required|trim');

				if ($this->form_validation->run() == FALSE){ //if can't pass validation
		            $errors = validation_errors();
		            $respons_ajax['type'] = 'error';
		            $respons_ajax['pesan'] = $errors;
		            echo json_encode($respons_ajax);

	        	}else{

	        		//array
	        		$harga = $this->input->post('harga');
					//bukan array
					
					$nosj = $this->input->post('no_sj');
					$tglsj = tanggal1($this->input->post('tgl_sj'));
					$idsupplier = $this->input->post('id_supplier');
					$nopo = $this->input->post('no_po');
					$tglpo = tanggal1($this->input->post('tgl_po'));
					$tglmasuk = tanggal1($this->input->post('tgl_masuk'));
					$id = uniqid();
					$result = array();
					$no = 0;
					$ids = 0;
					for($i = 0;$i< count($harga);$i++){
						$no++;
		     			$result[] = array(
		      			"id_barang"  => $_POST['namabarang'][$i],
		      			"qty"  => $_POST['jumlah'][$i],
		      			"harga_satuan"  => $_POST['harga'][$i],
		      			"id_barang_masuk" =>$id,
		      			"id_detail_barang_masuk" => $id.'-'.STR_PAD((int) $no, 3, "0", STR_PAD_LEFT)
	     				);
	    			}   

					if($this->db->insert_batch('tb_detail_barang_masuk', $result) && $this->Barang_masuk_model->new_data($id, $nopo, $tglpo, $nosj, $tglsj,$idsupplier, $tglmasuk)){
						$respons_ajax['type'] = 'success';
						$respons_ajax['pesan'] = 'Success';
						echo json_encode($respons_ajax);
						$log = $_SESSION['username'].' ('.$_SESSION['fullname'].')'.' Menambahkan data barang masuk. No PO: '.$nopo;
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

	public function hapus_data_transaksi()
	{
		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{
			if($this->input->post(null)){
				$id = $this->input->post('id');
				if($this->input->post('trans') == 'terima'){
					
					if($this->Barang_masuk_model->get_status($id)->row()->status == 'Pending'){
						if($this->Barang_masuk_model->hapus_data($id) && $this->Barang_masuk_model->hapus_detail($id)){
							$respons_ajax['type'] = 'success';
							$respons_ajax['pesan'] = 'Success';
							echo json_encode($respons_ajax);
							$log = $_SESSION['username'].' ('.$_SESSION['fullname'].')'.' Menghapus data barang masuk. ID: '.$id;
							$id = uniqid();
							$this->User_model->log_user($log, $id);
						}else{
							$respons_ajax['type'] = 'error';
							$respons_ajax['pesan'] = 'Failed';
							echo json_encode($respons_ajax);
						}
						
					}else{
							$respons_ajax['type'] = 'error';
							$respons_ajax['pesan'] = 'Failed';
							echo json_encode($respons_ajax);
					}
				}elseif($this->input->post('trans') == 'keluar'){
					if($this->Barang_keluar_model->get_status($id)->row()->status == 'Pending'){
						if($this->Barang_keluar_model->hapus_data($id) && $this->Barang_keluar_model->hapus_detail($id)){
							$respons_ajax['type'] = 'success';
							$respons_ajax['pesan'] = 'Success';
							echo json_encode($respons_ajax);
							$log = $_SESSION['username'].' ('.$_SESSION['fullname'].')'.' Menghapus data barang keluar. ID: '.$id;
							$id = uniqid();
							$this->User_model->log_user($log, $id);
						}else{
							$respons_ajax['type'] = 'error';
							$respons_ajax['pesan'] = 'Failed';
							echo json_encode($respons_ajax);
						}
						
					}else{
							$respons_ajax['type'] = 'error';
							$respons_ajax['pesan'] = 'Failed';
							echo json_encode($respons_ajax);
					}
				}elseif($this->input->post('trans') == 'retur_terima'){
					if($this->Retur_masuk_model->get_status($id)->row()->status == 'Pending'){
						if($this->Retur_masuk_model->hapus_data($id) && $this->Retur_masuk_model->hapus_detail($id)){
							$respons_ajax['type'] = 'success';
							$respons_ajax['pesan'] = 'Success';
							echo json_encode($respons_ajax);
							$log = $_SESSION['username'].' ('.$_SESSION['fullname'].')'.' Menghapus data retur barang masuk. ID: '.$id;
							$id = uniqid();
							$this->User_model->log_user($log, $id);
						}else{
							$respons_ajax['type'] = 'error';
							$respons_ajax['pesan'] = 'Failed';
							echo json_encode($respons_ajax);
						}
						
					}else{
							$respons_ajax['type'] = 'error';
							$respons_ajax['pesan'] = 'Failed';
							echo json_encode($respons_ajax);
					}
				}elseif($this->input->post('trans') == 'retur_keluar'){
					if($this->Retur_keluar_model->get_status($id)->row()->status == 'Pending'){
						if($this->Retur_keluar_model->hapus_data($id) && $this->Retur_keluar_model->hapus_detail($id)){
							$respons_ajax['type'] = 'success';
							$respons_ajax['pesan'] = 'Success';
							echo json_encode($respons_ajax);
							$log = $_SESSION['username'].' ('.$_SESSION['fullname'].')'.' Menghapus data retur barang masuk. ID: '.$id;
							$id = uniqid();
							$this->User_model->log_user($log, $id);
						}else{
							$respons_ajax['type'] = 'error';
							$respons_ajax['pesan'] = 'Failed';
							echo json_encode($respons_ajax);
						}
						
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

	public function get_data_id_barang_masuk()
	{
		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{
			if($this->input->post(null)){
				$id = $this->input->post('id');

				echo json_encode($this->Barang_masuk_model->get_data_id($id)->row());
			}else{

				show_404();
			}
		}

	}


	public function get_pdf(){
		if($this->uri->segment(3) && $this->uri->segment(4)){

			if($this->uri->segment(3) == 'in'){
				$id = $this->uri->segment(4);
				$data = $this->Barang_masuk_model->get_data_id($id)->row();

				$pdf = new FPDF('P','mm','A4');
		        // membuat halaman baru
		        $pdf->AddPage();
		        // setting jenis font yang akan digunakan
		        $pdf->SetFont('Arial','B',16);
		        // mencetak string 
		        $pdf->Cell(190,7,'LAPORAN BARANG MASUK',0,1,'C');
		        $pdf->SetFont('Arial','B',12);
		        $pdf->Cell(190,7,'PO No. '.$data->no_po,0,1,'C');
		        $pdf->Cell(190,7,'Tgl. '.tanggal_indo($data->tgl_po),0,1,'C');
		         $pdf->Cell(190,7,$data->nama_supplier,0,1,'C');
		        // Memberikan space kebawah agar tidak terlalu rapat
		        $pdf->Cell(10,7,'',0,1);
		        $pdf->SetFont('Arial','B',10);
		        $pdf->Cell(15,6,'No',1,0,'C');
		        $pdf->Cell(90,6,'Nama Barang',1,0);
		        $pdf->Cell(27,6,'Qty',1,0,'C');
		        $pdf->Cell(25,6,'Harga',1,0,'C');  
		        $pdf->Cell(30,6,'Jumlah',1,1,'C');
		        $pdf->SetFont('Arial','',10);
		        $barang = $this->Barang_masuk_model->get_barang($id)->result();
		        $no = 0;
		        foreach ($barang as $row){
		        	$no++;
		            $pdf->Cell(15,6,titik($no),1,0,'C');
		            $pdf->Cell(90,6,$row->nama_barang,1,0);
		            $pdf->Cell(27,6,titik($row->qty),1,0,'R');
		            $pdf->Cell(25,6,'Rp. '.titik($row->harga_satuan),1,0,'R');
		            $pdf->Cell(30,6,'Rp. '.titik($row->jumlah),1,1,'R'); 
		        }
		        $pdf->SetFont('Arial','B',12);
		        $pdf->Cell(15+90+27+25,6,'Jumlah',1,0,'R');
		        $pdf->Cell(30,6,'Rp. '.titik($row->jumlahtotal),1,1,'R');
		        $namafile = $data->id_barang_masuk.'.pdf';
		        $pdf->Output('',$namafile);

		    }elseif($this->uri->segment(3) == 'out'){
		    	$id = $this->uri->segment(4);
				$data = $this->Barang_keluar_model->get_data_id($id)->row();

				$pdf = new FPDF('P','mm','A4');
		        // membuat halaman baru
		        $pdf->AddPage();
		        // setting jenis font yang akan digunakan
		        $pdf->SetFont('Arial','B',16);
		        // mencetak string 
		        $pdf->Cell(190,7,'LAPORAN BARANG KELUAR',0,1,'C');
		        $pdf->SetFont('Arial','B',12);
		        $pdf->Cell(190,7,'PO No. '.$data->no_faktur,0,1,'C');
		        $pdf->Cell(190,7,'Tgl. '.tanggal_indo($data->tgl_faktur),0,1,'C');
		         $pdf->Cell(190,7,$data->nama_customer,0,1,'C');
		        // Memberikan space kebawah agar tidak terlalu rapat
		        $pdf->Cell(10,7,'',0,1);
		        $pdf->SetFont('Arial','B',10);
		        $pdf->Cell(15,6,'No',1,0,'C');
		        $pdf->Cell(90,6,'Nama Barang',1,0);
		        $pdf->Cell(27,6,'Qty',1,0,'C');
		        $pdf->Cell(25,6,'Harga',1,0,'C');  
		        $pdf->Cell(30,6,'Jumlah',1,1,'C');
		        $pdf->SetFont('Arial','',10);
		        $barang = $this->Barang_keluar_model->get_barang($id)->result();
		        $no = 0;
		        foreach ($barang as $row){
		        	$no++;
		            $pdf->Cell(15,6,titik($no),1,0,'C');
		            $pdf->Cell(90,6,$row->nama_barang,1,0);
		            $pdf->Cell(27,6,titik($row->qty),1,0,'R');
		            $pdf->Cell(25,6,'Rp. '.titik($row->harga_satuan),1,0,'R');
		            $pdf->Cell(30,6,'Rp. '.titik($row->jumlah),1,1,'R'); 
		        }
		        $pdf->SetFont('Arial','B',12);
		        $pdf->Cell(15+90+27+25,6,'Jumlah',1,0,'R');
		        $pdf->Cell(30,6,'Rp. '.titik($row->jumlahtotal),1,1,'R');
		        $namafile = $data->id_barang_keluar.'.pdf';
		        $pdf->Output('',$namafile);
			}elseif($this->uri->segment(3) == 'retur_in'){
				$id = $this->uri->segment(4);
				$data = $this->Retur_masuk_model->get_data_id($id)->row();

				$pdf = new FPDF('P','mm','A4');
		        // membuat halaman baru
		        $pdf->AddPage();
		        // setting jenis font yang akan digunakan
		        $pdf->SetFont('Arial','B',16);
		        // mencetak string 
		        $pdf->Cell(190,7,'LAPORAN RETUR BARANG MASUK',0,1,'C');
		        $pdf->SetFont('Arial','B',12);
		        $pdf->Cell(190,7,'No. Surat Jalan: 	'.$data->no_surat_jalan,0,1,'C');
		        $pdf->Cell(190,7,'Tgl. Retur: '.tanggal_indo($data->tgl_retur),0,1,'C');
		         $pdf->Cell(190,7,$data->nama_supplier,0,1,'C');
		        // Memberikan space kebawah agar tidak terlalu rapat
		        $pdf->Cell(10,7,'',0,1);
		        $pdf->SetFont('Arial','B',10);
		        $pdf->Cell(15,6,'No',1,0,'C');
		        $pdf->Cell(150,6,'Nama Barang',1,0);
		        $pdf->Cell(27,6,'Qty',1,1,'C');
		        $pdf->SetFont('Arial','',10);
		        $barang = $this->Retur_masuk_model->get_barang($id)->result();
		        $no = 0;
		        foreach ($barang as $row){
		        	$no++;
		            $pdf->Cell(15,6,titik($no),1,0,'C');
		            $pdf->Cell(150,6,$row->nama_barang,1,0);
		            $pdf->Cell(27,6,titik($row->qty),1,1,'R');
		        }
		        $namafile = $data->id_retur_barang_masuk.'.pdf';
		        $pdf->Output('',$namafile);

		    }elseif($this->uri->segment(3) == 'retur_out'){
				$id = $this->uri->segment(4);
				$data = $this->Retur_keluar_model->get_data_id($id)->row();

				$pdf = new FPDF('P','mm','A4');
		        // membuat halaman baru
		        $pdf->AddPage();
		        // setting jenis font yang akan digunakan
		        $pdf->SetFont('Arial','B',16);
		        // mencetak string 
		        $pdf->Cell(190,7,'LAPORAN RETUR BARANG KELUAR',0,1,'C');
		        $pdf->SetFont('Arial','B',12);
		        $pdf->Cell(190,7,'No. Surat Jalan: 	'.$data->no_faktur,0,1,'C');
		        $pdf->Cell(190,7,'Tgl. Retur: '.tanggal_indo($data->tgl_retur),0,1,'C');
		         $pdf->Cell(190,7,$data->nama_customer,0,1,'C');
		        // Memberikan space kebawah agar tidak terlalu rapat
		        $pdf->Cell(10,7,'',0,1);
		        $pdf->SetFont('Arial','B',10);
		        $pdf->Cell(15,6,'No',1,0,'C');
		        $pdf->Cell(150,6,'Nama Barang',1,0);
		        $pdf->Cell(27,6,'Qty',1,1,'C');
		        $pdf->SetFont('Arial','',10);
		        $barang = $this->Retur_keluar_model->get_barang($id)->result();
		        $no = 0;
		        foreach ($barang as $row){
		        	$no++;
		            $pdf->Cell(15,6,titik($no),1,0,'C');
		            $pdf->Cell(150,6,$row->nama_barang,1,0);
		            $pdf->Cell(27,6,titik($row->qty),1,1,'R');
		        }
		        $namafile = $data->id_retur_barang_keluar.'.pdf';
		        $pdf->Output('',$namafile);

		    }
		}
	}
	// end penerimaan barang
	// 
	public function pengeluaran() //halaman pengeluaran barang
	{
		//check session here

		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{	

			$data = new stdClass();
			$data->title = 'Pengeluaran Barang';
			$data->page_active = 'transaksi';
			$data->role = $_SESSION['role'];
			$data->username = $_SESSION['username'];
			$data->logged_in = $_SESSION['logged_in'];
			$data->fullname = $_SESSION['fullname'];
			$data->customer = $this->Customer_model->get_list()->result();
			$data->barang = $this->Barang_model->get_list_keluar()->result();
			$this->load->view('header', $data);

			$this->load->view('pengeluaran_barang_v', $data);

		}else{
			$data = new stdClass();
			$data->title = 'LOGIN';
			$this->load->view('login_form', $data);
		}
	}

	public function get_data_barang_keluar()
	{	
		if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
			$list = $this->Barang_keluar_model->get_datatables();
			$data = array();
			$no = $_POST['start'];
			foreach ($list as $field) {
				$no++;
				$row = array();
				$row['no'] = $no;
				$row['id_barang_keluar'] = $field->id_barang_keluar;
				$row['no_faktur'] = $field->no_faktur;
				$row['tgl_faktur'] = $field->tgl_faktur;
				$row['tgl_keluar'] = $field->tgl_keluar;
				$row['nama_customer'] = $field->nama_customer;
				$row['status'] = $field->status;
				$data[] = $row;
				
			}

			$output = array(
				"draw"=> $_POST['draw'], 
				"recordsTotal" =>$this->Barang_keluar_model->count_all(),
				"recordsFiltered"=>$this->Barang_keluar_model->count_filtered(),
				"data"=>$data,
			);
			echo json_encode($output);
		}else{
			show_404();
		}
	}

	public function submit_barang_keluar()
	{
		if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) 
		{
			if($this->input->post(null)){
				$this->form_validation->set_rules('no_faktur', 'No. Faktur', 'required|trim|is_unique[tb_barang_keluar.no_faktur]|xss_clean');
				$this->form_validation->set_rules('tgl_faktur', 'Tgl. Faktur', 'required|trim|xss_clean');
				$this->form_validation->set_rules('id_customer', 'Nama Customer', 'required|trim|xss_clean');
				$this->form_validation->set_rules('tgl_keluar', 'Tgl. Keluar', 'required|trim|xss_clean');
				$this->form_validation->set_rules('namabarang[]', 'Nama Barang', 'required');
				$this->form_validation->set_rules('jumlah[]', 'Jumlah', 'required|trim|xss_clean');
				$this->form_validation->set_rules('harga[]', 'Harga', 'required|trim|xss_clean');
				
				if ($this->form_validation->run() == FALSE){ //if can't pass validation

		            $errors = validation_errors();
		            $respons_ajax['type'] = 'error';
		            $respons_ajax['pesan'] = $errors;
		            echo json_encode($respons_ajax);

		        }else{
		        	$nofaktur = $this->input->post('no_faktur');
					$tglfaktur = tanggal1($this->input->post('tgl_faktur'));
					$idcust = $this->input->post('id_customer');
					$tglkeluar = tanggal1($this->input->post('tgl_keluar'));
					//array
					$idbarang = $this->input->post('namabarang');
					$jumlah = $this->input->post('jumlah');
					$harga = $this->input->post('harga');
					$id = uniqid();
					$no = 0;
					$ids = 0;
					for($i = 0;$i< count($harga);$i++){
						$no++;
		     			$result[] = array(
		      			"id_barang"  => $_POST['namabarang'][$i],
		      			"qty"  => $_POST['jumlah'][$i],
		      			"harga_satuan"  => $_POST['harga'][$i],
		      			"id_barang_keluar" =>$id,
		      			"id_detail_barang_keluar" => $id.'-'.STR_PAD((int) $no, 3, "0", STR_PAD_LEFT)
	     				);
	    			}   

					if($this->db->insert_batch('tb_detail_barang_keluar', $result) && $this->Barang_keluar_model->new_data($id, $nofaktur, $tglfaktur, $idcust, $tglkeluar)){
						$respons_ajax['type'] = 'success';
						$respons_ajax['pesan'] = 'Success';
						echo json_encode($respons_ajax);
						$log = $_SESSION['username'].' ('.$_SESSION['fullname'].')'.' Menambahkan data barang keluar. No Faktur: '.$nofaktur;
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

	public function get_data_id_barang_keluar()
	{
		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{
			if($this->input->post(null)){
				$id = $this->input->post('id');

				echo json_encode($this->Barang_keluar_model->get_data_id($id)->row());
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
					$model = 'Barang_masuk_model';
				}elseif($table == 'Pengeluaran'){
					$model = 'Barang_keluar_model';
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

	public function get_list_max()
	{
		
		echo json_encode($this->Barang_model->get_list_max()->result());
	}

	public function cek_max()
	{
		if($this->Barang_model->get_list_max()->num_rows() > 0)
		{
			echo json_encode($this->Barang_model->get_list_max()->num_rows());
		}else{
			echo json_encode('-');
		}
		
	}

	public function get_list_min()
	{
		
		echo json_encode($this->Barang_model->get_list_min()->result());
	}

	public function cek_min()
	{
		if($this->Barang_model->get_list_min()->num_rows() > 0)
		{	
			echo json_encode($this->Barang_model->get_list_min()->num_rows());
		}else{
			echo json_encode('-');
		}
		
	}

}
