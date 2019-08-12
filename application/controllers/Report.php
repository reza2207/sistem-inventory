<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

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
			if($_SESSION['role'] != 'operator'){
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

	public function bulanan() //halaman penerimaan barang
	{
		//check session here

		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{	
			if($_SESSION['role'] != 'operator'){
			
				$data = new stdClass();
				$data->title = 'Report Bulanan';
				$data->page_active = 'report';
				$data->role = $_SESSION['role'];
				$data->username = $_SESSION['username'];
				$data->logged_in = $_SESSION['logged_in'];
				$data->fullname = $_SESSION['fullname'];
				$data->barang = $this->Barang_model->get_list();
				$this->load->view('header', $data);

				$this->load->view('report_bulanan_v', $data);
			}else{
				show_404();
			}
			
		}else{
			$data = new stdClass();
			$data->title = 'LOGIN';
			$this->load->view('login_form', $data);
		}
	}

	public function harian() //halaman penerimaan barang
	{
		//check session here

		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{	
			if($_SESSION['role'] != 'operator'){
				$data = new stdClass();
				$data->title = 'Report Harian';
				$data->page_active = 'report';
				$data->role = $_SESSION['role'];
				$data->username = $_SESSION['username'];
				$data->logged_in = $_SESSION['logged_in'];
				$data->fullname = $_SESSION['fullname'];
				$data->barang = $this->Barang_model->get_list();
				$this->load->view('header', $data);

				$this->load->view('report_harian_v', $data);
			}else{
				show_404();
			}
			
		}else{
			$data = new stdClass();
			$data->title = 'LOGIN';
			$this->load->view('login_form', $data);
		}
	}

	public function get_pdf()
	{
		
		if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) 
		{
			if($_SESSION['role'] != 'operator'){
				if(isset($_GET['bulan']) && isset($_GET['tahun'])){
					$month = $_GET['bulan'];
					$year = $_GET['tahun'];
					$lm = $month == 1 ? '12' : $month - 1;
					$ly = $month == 1 ? $year - 1 : $year;
					$pdf = new FPDF('P','mm','A4');
			        // membuat halaman baru
			        $pdf->AddPage();
			        // setting jenis font yang akan digunakan
			        $pdf->SetFont('Arial','B',16);
			        // mencetak string 
			        $pdf->Cell(190,7,'LAPORAN PERSEDIAAN BARANG',0,1,'C');
			        $pdf->Cell(190,7,'PT. SURYAINDAH WIRAPERKASA',0,1,'C');
			        $pdf->SetFont('Arial','B',12);
			        $pdf->Cell(190,7,'Per '.bulanindo($month).' '.$year,0,1,'C');
			        // Memberikan space kebawah agar tidak terlalu rapat
			        $pdf->Cell(10,7,'',0,1);
			        $pdf->SetFont('Arial','B',10);
			        $pdf->Cell(15,6,'No',1,0,'C');
			        $pdf->Cell(60,6,'Nama Barang',1,0);
			        $pdf->Cell(27,6,'Stok Awal',1,0,'C');
			        $pdf->Cell(25,6,'Stok Masuk',1,0,'C');  
			        $pdf->Cell(30,6,'Stok Keluar',1,0,'C');
			        $pdf->Cell(30,6,'Stok Akhir',1,1,'C');
			        $pdf->SetFont('Arial','',10);
			        $barang = $this->Barang_model->get_report($month, $year, $lm, $ly)->result();
			        $no = 0;
			        foreach ($barang as $row){
			        	$no++;
			            $pdf->Cell(15,6,titik($no),1,0,'C');
			            $pdf->Cell(60,6,$row->nama_barang,1,0);
			            $pdf->Cell(27,6,titik($row->stokawal),1,0,'R');
			            $pdf->Cell(25,6,titik($row->stokmasuk),1,0,'R');
			            $pdf->Cell(30,6,titik($row->stokkeluar),1,0,'R');
			            $pdf->Cell(30,6,titik($row->stokakhir),1,1,'R'); 
			        }

			        $namafile = 'LAPORAN PERSEDIAAN BARANG Per '.bulanindo($month).' '.$year.'.pdf';
			        $pdf->Output('',$namafile);

			    }elseif(isset($_GET['tanggal'])){

					$tanggal = $_GET['tanggal'];

					$pdf = new FPDF('P','mm','A4');
			        // membuat halaman baru
			        $pdf->AddPage();
			        // setting jenis font yang akan digunakan
			        $pdf->SetFont('Arial','B',16);
			        // mencetak string 
			        $pdf->Cell(190,7,'LAPORAN PERSEDIAAN BARANG',0,1,'C');
			        $pdf->Cell(190,7,'PT. SURYAINDAH WIRAPERKASA',0,1,'C');
			        $pdf->SetFont('Arial','B',12);
			        $pdf->Cell(190,7,'Per '.tanggal_indo(tanggal1($tanggal)),0,1,'C');
			        // Memberikan space kebawah agar tidak terlalu rapat
			        $pdf->Cell(10,7,'',0,1);
			        $pdf->SetFont('Arial','B',10);
			        $pdf->Cell(15,6,'No',1,0,'C');
			        $pdf->Cell(60,6,'Nama Barang',1,0);
			        $pdf->Cell(27,6,'Stok Awal',1,0,'C');
			        $pdf->Cell(25,6,'Stok Masuk',1,0,'C');  
			        $pdf->Cell(30,6,'Stok Keluar',1,0,'C');
			        $pdf->Cell(30,6,'Stok Akhir',1,1,'C');
			        $pdf->SetFont('Arial','',10);
			        $d = explode('-',tanggal1($tanggal));
					$month = (int) $d[1];
					$year = (int) $d[0];
					$lm = $month == 1 ? '12' : $month - 1;
					$ly = $month == 1 ? $year - 1 : $year;
			        $barang = $this->Barang_model->get_report_a_days(tanggal1($tanggal), $lm, $ly)->result();
			        $no = 0;
			        foreach ($barang as $row){
			        	$no++;
			            $pdf->Cell(15,6,titik($no),1,0,'C');
			            $pdf->Cell(60,6,$row->nama_barang,1,0);
			            $pdf->Cell(27,6,titik($row->stokawal),1,0,'R');
			            $pdf->Cell(25,6,titik($row->stokmasuk),1,0,'R');
			            $pdf->Cell(30,6,titik($row->stokkeluar),1,0,'R');
			            $pdf->Cell(30,6,titik($row->stokakhir),1,1,'R'); 
			        }

			        $namafile = 'LAPORAN PERSEDIAAN BARANG Per '.tanggal_indo(tanggal1($tanggal)).'.pdf';
			        $pdf->Output('',$namafile);
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
	
	public function get_days_a_month()
	{

		//untuk mengambil data stok opname
		if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) 
		{
			if($this->input->post(null)){
				$month = $this->input->post('bulan');
				$year = $this->input->post('tahun');
				$lm = $month == 1 ? '12' : $month - 1;
				
				$ly = $month == 1 ? $year - 1 : $year;
				echo json_encode($this->Barang_model->get_report($month, $year, $lm, $ly)->result());
			}else{
				$month = (int) date('m');
				$year = date('Y');
				$lm = $month == 1 ? '12' : $month - 1;
				$ly = $month == 1 ? $year - 1 : $year;

				echo json_encode($this->Barang_model->get_report($month, $year, $lm, $ly)->result());
			}
		}else{
			show_404();
		}

	}
	public function get_days_in_a_month()
	{
		if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) 
		{

			$date = tanggal1($this->input->post('tanggal'));
			$d = explode('-',$date);
			$month = (int) $d[1];
			$year = (int) $d[0];
			$lm = $month == 1 ? '12' : $month - 1;
			$ly = $month == 1 ? $year - 1 : $year;
			echo json_encode($this->Barang_model->get_report_a_days($date, $lm, $ly)->result());
		}else{
			show_404();
		}

	}



}
