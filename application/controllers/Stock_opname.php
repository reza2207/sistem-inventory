<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_opname extends CI_Controller {

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
		$this->load->model('So_model');
		date_default_timezone_set('Asia/Jakarta');
		
	}

	public function index()
	{	
		//check session here

		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{	

			$data = new stdClass();
			$data->title = 'Stock Opname';
			$data->page_active = 'Stock Opname';
			$data->role = $_SESSION['role'];
			$data->username = $_SESSION['username'];
			$data->logged_in = $_SESSION['logged_in'];
			$data->fullname = $_SESSION['fullname'];

			$this->load->view('header', $data);

			$this->load->view('stok_opname_v');

		}else{
			$data = new stdClass();
			$data->title = 'LOGIN';
			$this->load->view('login_form', $data);
		}
	}



	public function get_data_so()
	{	
		if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
			$list = $this->So_model->get_datatables();
			$data = array();
			$no = $_POST['start'];
			foreach ($list as $field) {
				$no++;
				$row = array();
				$row['no'] = $no;
				$row['id_so'] = $field->id_so;
				$row['tgl_so'] = $field->tgl_so;
				$data[] = $row;
				
			}

			$output = array(
				"draw"=> $_POST['draw'], 
				"recordsTotal" =>$this->So_model->count_all(),
				"recordsFiltered"=>$this->So_model->count_filtered(),
				"data"=>$data,
			);
			echo json_encode($output);
		}else{
			show_404();
		}
	
	}

	public function cek_so()
	{
		$m = (int) date('m');
		$y = date('Y');
		if($this->So_model->cek_so($m, $y)->num_rows() > 0)
		{
			echo json_encode('done');
		}else{

			echo json_encode('-');
		}
	}

	public function get_card()
	{
		
		if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) 
		{
				$month = (int) date('m');
				$year = date('Y');

				$pdf = new FPDF('P','mm','A4');
		        // membuat halaman baru
		        $pdf->AddPage();
		        // setting jenis font yang akan digunakan
		        $pdf->SetFont('Arial','B',16);
		        // mencetak string 
		        $pdf->Cell(190,7,'Kartu Stock',0,1,'C');
		        $pdf->Cell(190,7,'PT. SURYAINDAH WIRAPERKASA',0,1,'C');
		        $pdf->SetFont('Arial','B',12);
		        $pdf->Cell(190,7,'Per '.bulanindo($month).' '.$year,0,1,'C');
		        // Memberikan space kebawah agar tidak terlalu rapat
		        $pdf->Cell(10,7,'',0,1);
		        $pdf->SetFont('Arial','B',10);
		        $pdf->Cell(15,6,'No',1,0,'C');
		        $pdf->Cell(60,6,'Nama Barang',1,0);
		        $pdf->Cell(30,6,'Stok Terakhir',1,0,'C');
		        $pdf->Cell(30,6,'Stok Sebenarnya',1,0,'C');
		        $pdf->Cell(30,6,'Barang Rusak',1,0,'C');
		        $pdf->Cell(30,6,'Selisih',1,1,'C');
		        $pdf->SetFont('Arial','',10);
		        $barang = $this->Barang_model->get_report($month, $year)->result();
		        $no = 0;
		        foreach ($barang as $row){
		        	$no++;
		            $pdf->Cell(15,6,titik($no),1,0,'C');
		            $pdf->Cell(60,6,$row->nama_barang,1,0);
		            $pdf->Cell(30,6,titik($row->stokakhir),1,0,'R');
		            $pdf->Cell(30,6,'',1,0,'R');
		             $pdf->Cell(30,6,'',1,0,'R');
		            $pdf->Cell(30,6,'',1,1,'R'); 
		        } 
		        

		        $namafile = 'Kartu Stock Per '.bulanindo($month).' '.$year.'.pdf';
		        $pdf->Output('',$namafile);

		    
		}
	}

	public function submit_so()
	{
		if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) 
		{
			if($this->input->post(null)){
				$this->form_validation->set_rules('stok_benar[]', 'Stok Sebenarnya', 'numeric|trim');
				$this->form_validation->set_rules('jml_rusak[]', 'Jumlah Rusak', 'numeric|trim');
				$this->form_validation->set_rules('selisih', 'Stok Sebenarnya', 'numeric|trim');

				if ($this->form_validation->run() == FALSE){
					$errors = validation_errors();
		            $respons_ajax['type'] = 'error';
		            $respons_ajax['pesan'] = $errors;
		            echo json_encode($respons_ajax);
				}else{
					$idbrg = $this->input->post('id_brg');
					$rusak = $this->input->post('jml_rusak');
					$selisih = $this->input->post('selisih');
					$id = 'SO/'.date('Y').'/'.date('m');
					$no = 0;
					$ids = 0;
					$tgl_so = date('Y-m-d');
					//$result = count($idbrg);
					for($i = 0;$i < count($idbrg);$i++){
						$no++;
		     			$result[] = array(
		      			"id_detail_so" => $id.'-'.STR_PAD((int) $no, 3, "0", STR_PAD_LEFT),
		      			"id_so"=>$id,
		      			"id_barang"  => $_POST['id_brg'][$i],
		      			"stok_terakhir"  => $_POST['so_akhir'][$i],
		      			"stok_benar"  => $_POST['stok_benar'][$i],
		      			"jumlah_rusak"  => $_POST['jml_rusak'][$i]
		      			
	     				);
					}

					if($this->db->insert_batch('tb_detail_stok_opname', $result) && $this->So_model->new_data($id, $tgl_so)){
						
						$respons_ajax['type'] = 'success';
						$respons_ajax['pesan'] = 'Success';
						echo json_encode($respons_ajax);
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

	public function get_data_id_so()
	{
		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{
			if($this->input->post(null)){
				$id = $this->input->post('id');

				echo json_encode($this->So_model->get_data_id($id)->result());
			}else{

				show_404();
			}
		}

	}

	public function get_pdf()
	{
		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{

			if($_GET['id'] == NULL){
				show_404();
			}else{
				$id = $_GET['id'];

				$r = $this->So_model->get_data_so_id($id);
				$pdf = new FPDF('P','mm','A4');
		        // membuat halaman baru
		        $pdf->AddPage();
		        // setting jenis font yang akan digunakan
		        $pdf->SetFont('Arial','B',16);
		        // mencetak string 
		        $pdf->Cell(190,7,'STOK OPNAME',0,1,'C');
		        $pdf->Cell(190,7,'PT. SURYAINDAH WIRAPERKASA',0,1,'C');
		        $pdf->SetFont('Arial','B',12);
		        $pdf->Cell(190,7,'Tanggal '.tanggal_indo($r->tgl_so),0,1,'C');
		        // Memberikan space kebawah agar tidak terlalu rapat
		        $pdf->Cell(10,7,'',0,1);
		        $pdf->SetFont('Arial','B',10);
		        $pdf->Cell(15,6,'No',1,0,'C');
		        $pdf->Cell(60,6,'Nama Barang',1,0);
		        $pdf->Cell(35,6,'Stok Terakhir',1,0,'C');
		        $pdf->Cell(35,6,'Stok Sebenarnya',1,0,'C');  
		        $pdf->Cell(20,6,'Rusak',1,0,'C');
		        $pdf->Cell(20,6,'Selisih',1,1,'C');
		        $pdf->SetFont('Arial','',10);
		        $barang = $this->So_model->get_data_id($id)->result();
		        $no = 0;
		        foreach ($barang as $row){
		        	$no++;
		            $pdf->Cell(15,6,titik($no),1,0,'C');
		            $pdf->Cell(60,6,$row->nama_barang,1,0);
		            $pdf->Cell(35,6,titik($row->qtystok),1,0,'R');
		            $pdf->Cell(35,6,titik($row->qtybenar),1,0,'R');
		            $pdf->Cell(20,6,titik($row->jumlah_rusak),1,0,'R');
		            $pdf->Cell(20,6,titik($row->qtybenar - $row->qtystok),1,1,'R'); 
		        }

		        $namafile = 'Stok Opname tanggal '.tanggal_indo($r->tgl_so).'.pdf';
		        $pdf->Output('',$namafile);
			}
		}else{
			show_404();
		}
	}

	public function hapus_rahasia()
	{
		$this->db->empty_table('tb_detail_stok_opname');
		$this->db->empty_table('tb_stok_opname');
	}
	

}
