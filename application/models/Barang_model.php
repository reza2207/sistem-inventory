<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User_model class.
 * 
 * @extends CI_Model
 */
class Barang_model extends CI_Model {

	var $table = 'tb_barang';
	var $column_order = array(null, 'nama_barang', 'satuan','nama_supplier', null);//,'status');//field yang ada di table user
	var $column_search = array('tb_barang.id_barang', 'tb_barang.nama_barang', 'tb_barang.satuan', 'tb_barang.id_supplier', 'tb_supplier.nama_supplier');//,'status');//field yang dizinkan untuk pencarian
	var $order = array('id_barang'=>'asc'); //default sort
	
	public function __construct() {
		
		parent::__construct();
		$this->load->database();
		
	}
	private function _get_datatables_query() 
	{
		$this->db->select('tb_barang.id_barang, tb_barang.nama_barang, tb_barang.satuan, tb_barang.id_supplier, tb_supplier.nama_supplier, tb_barang.status, tb_barang.min, tb_barang.max');
		$this->db->from($this->table);
		$this->db->join('tb_supplier','tb_barang.id_supplier = tb_supplier.id_supplier', 'LEFT');
		$i = 0;
		foreach($this->column_search as $item) // looping awal
		{
			if($_POST['search']['value']) // jika dtb mengirimkan pencarian melalui method post
			{
				if($i === 0) // looping awal
				{
					$this->db->group_start();
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column_search) -1 == $i)
					$this->db->group_end();
			}
			$i++;
		}

		if(isset($_POST['order']))
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		}
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}

	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function new_barang($idbarang, $nama, $satuan, $idsupplier, $min, $max)
	{
		$data = array(
			'id_barang' => $idbarang,
			'nama_barang' =>$nama,
			'satuan' =>$satuan,
			'id_supplier' =>  $idsupplier,
			'min'=>$min,
			'max'=>$max);
		return $this->db->insert($this->table, $data);
	}


	public function hapus_barang($id)
	{	
		return $this->db->delete($this->table, array('id_barang' => $id));
	}

	public function get_data($id)
	{
		$this->db->from($this->table);
		$this->db->where('id_barang', $id);
		return $this->db->get()->row();
	}

	public function get_last_id($abjad)
	{
		$this->db->select('id_barang');
		$this->db->from($this->table);
		$this->db->where('LEFT(id_barang,1) = ', $abjad, TRUE);
		$this->db->order_by('id_barang', 'DESC');
		$this->db->limit('1');
		return $this->db->get();
	}

	public function edit_barang($id, $status)
	{
		$data = array(
			
			'status' =>$status);
		$this->db->where('id_barang', $id);
		return $this->db->update($this->table, $data);
	}

	public function get_list(){
		$this->db->select('id_barang, nama_barang');
		$this->db->from($this->table);
		$this->db->where('status', 'Active');
		return $this->db->get()->result();
	}
	public function get_list_keluar(){
		$this->db->select('tb_barang.id_barang, tb_barang.nama_barang, IFNULL(IFNULL(a.stok_akhir, "0")+b.qtyin-IFNULL(c.qtyout,"0")-IFNULL(d.qtyretur,"0"),"0") AS stok_akhir');
		$this->db->from($this->table);
		//get data stok opname last month
		$this->db->join('(SELECT id_barang, stok_terakhir, jumlah_hilang, jumlah_rusak, (stok_benar-jumlah_hilang-jumlah_rusak) AS stok_akhir FROM tb_detail_stok_opname LEFT JOIN tb_stok_opname ON tb_detail_stok_opname.id_so = tb_stok_opname.id_so WHERE MONTH(tb_stok_opname.tgl_so) = MONTH(CURRENT_DATE)-1 AND YEAR(tb_stok_opname.tgl_so) = YEAR(CURRENT_DATE) ) AS a', 'tb_barang.id_barang = a.id_barang', 'LEFT');
		//get data in from current month where status approve
		$this->db->join('(SELECT tb_barang.id_barang, tb_barang.nama_barang, IFNULL(a.qtyin,"0") AS qtyin FROM tb_barang LEFT JOIN( SELECT id_barang, SUM(tb_detail_barang_masuk.qty) AS qtyin FROM `tb_detail_barang_masuk` LEFT JOIN tb_barang_masuk ON tb_detail_barang_masuk.id_barang_masuk = tb_barang_masuk.id_barang_masuk WHERE MONTH(tb_barang_masuk.tgl_masuk) = MONTH(CURRENT_DATE) AND YEAR(tb_barang_masuk.tgl_masuk) = YEAR(CURRENT_DATE) AND tb_barang_masuk.status = "Approve" GROUP BY tb_detail_barang_masuk.id_barang) AS a ON tb_barang.id_barang = a.id_barang) b ', 'tb_barang.id_barang = b.id_barang', 'LEFT');
		//get data in from current month
		$this->db->join('(SELECT tb_barang.id_barang, tb_barang.nama_barang, IFNULL(b.qtyout,"0") AS qtyout FROM tb_barang LEFT JOIN(SELECT id_barang, SUM(tb_detail_barang_keluar.qty) AS qtyout FROM `tb_detail_barang_keluar` LEFT JOIN tb_barang_keluar ON tb_detail_barang_keluar.id_barang_keluar = tb_barang_keluar.id_barang_keluar WHERE MONTH(tb_barang_keluar.tgl_faktur) = MONTH(CURRENT_DATE) AND YEAR(tb_barang_keluar.tgl_faktur) = YEAR(CURRENT_DATE) AND tb_barang_keluar.status = "Approve" GROUP BY tb_detail_barang_keluar.id_barang) AS b ON tb_barang.id_barang = b.id_barang) AS c', 'tb_barang.id_barang = c.id_barang', 'LEFT');
		//get data retur current month
		$this->db->join('(SELECT tb_barang.id_barang, tb_barang.nama_barang, IFNULL(b.qty,"0") AS qtyretur FROM tb_barang LEFT JOIN(SELECT id_barang, SUM(tb_detail_retur_barang_masuk.qty) AS qty FROM `tb_detail_retur_barang_masuk` LEFT JOIN tb_retur_barang_masuk ON tb_detail_retur_barang_masuk.id_retur_barang_masuk = tb_retur_barang_masuk.id_retur_barang_masuk WHERE MONTH(tb_retur_barang_masuk.tgl_retur) = MONTH(CURRENT_DATE) AND YEAR(tb_retur_barang_masuk.tgl_retur) = YEAR(CURRENT_DATE) AND tb_retur_barang_masuk.status = "Approve" GROUP BY tb_detail_retur_barang_masuk.id_barang) AS b ON tb_barang.id_barang = b.id_barang) AS d', 'tb_barang.id_barang = d.id_barang', 'LEFT');
		
		$this->db->where('tb_barang.status', 'Active');
		$this->db->where('IFNULL(IFNULL(a.stok_akhir, "0")+b.qtyin-IFNULL(c.qtyout, "0"), "0") !=', 0);
		return $this->db->get();
	}

	public function validate_qty_item($idbarang)
	{
		$this->db->select('tb_barang.id_barang, tb_barang.nama_barang, IFNULL(IFNULL(a.stok_akhir, "0")+b.qtyin-IFNULL(c.qtyout,"0"),"0") AS stok_akhir');
		$this->db->from($this->table);
		//get data stok opname last month
		$this->db->join('(SELECT id_barang, stok_terakhir, jumlah_hilang, jumlah_rusak, (stok_terakhir-jumlah_hilang-jumlah_rusak) AS stok_akhir FROM tb_detail_stok_opname LEFT JOIN tb_stok_opname ON tb_detail_stok_opname.id_so = tb_stok_opname.id_so WHERE MONTH(tb_stok_opname.tgl_so) = MONTH(CURRENT_DATE)-1 AND YEAR(tb_stok_opname.tgl_so) = YEAR(CURRENT_DATE) ) AS a', 'tb_barang.id_barang = a.id_barang', 'LEFT');
		//get data in from current month where status approve
		$this->db->join('(SELECT tb_barang.id_barang, tb_barang.nama_barang, IFNULL(a.qtyin,"0") AS qtyin FROM tb_barang LEFT JOIN( SELECT id_barang, SUM(tb_detail_barang_masuk.qty) AS qtyin FROM `tb_detail_barang_masuk` LEFT JOIN tb_barang_masuk ON tb_detail_barang_masuk.id_barang_masuk = tb_barang_masuk.id_barang_masuk WHERE MONTH(tb_barang_masuk.tgl_masuk) = MONTH(CURRENT_DATE) AND YEAR(tb_barang_masuk.tgl_masuk) = YEAR(CURRENT_DATE) AND tb_barang_masuk.status = "Approve" GROUP BY tb_detail_barang_masuk.id_barang) AS a ON tb_barang.id_barang = a.id_barang) b ', 'tb_barang.id_barang = b.id_barang', 'LEFT');
		//get data in from current month
		$this->db->join('(SELECT tb_barang.id_barang, tb_barang.nama_barang, IFNULL(b.qtyout,"0") AS qtyout FROM tb_barang LEFT JOIN(SELECT id_barang, SUM(tb_detail_barang_keluar.qty) AS qtyout FROM `tb_detail_barang_keluar` LEFT JOIN tb_barang_keluar ON tb_detail_barang_keluar.id_barang_keluar = tb_barang_keluar.id_barang_keluar WHERE MONTH(tb_barang_keluar.tgl_faktur) = MONTH(CURRENT_DATE) AND YEAR(tb_barang_keluar.tgl_faktur) = YEAR(CURRENT_DATE) AND tb_barang_keluar.status = "Approve" GROUP BY tb_detail_barang_keluar.id_barang) AS b ON tb_barang.id_barang = b.id_barang) AS c', 'tb_barang.id_barang = c.id_barang', 'LEFT');
		$this->db->where('tb_barang.status', 'Active');
		$this->db->where('tb_barang.id_barang',$idbarang);
		return $this->db->get();
	}

	public function get_days_a_month($month, $year){
		$this->db->select('date_field');
		$this->db->from('(SELECT MAKEDATE('.$year.',1)+INTERVAL ('.$month.'-1)MONTH+INTERVAL daynum DAY date_field FROM (SELECT t*10+u daynum FROM (SELECT 0 t UNION SELECT 1 UNION SELECT 2 UNION SELECT 3) A, (SELECT 0 u UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) B ORDER BY daynum) AA) AS AAA');
		$this->db->where('MONTH(date_field)', $month);
		$this->db->order_by('date_field', 'ASC');
		return $this->db->get();
	}

	/*public function get_report($month, $year)
	{
		$query = $this->db->query("SELECT tb_barang.id_barang, tb_barang.nama_barang, IFNULL(bbefore.qtyin,0) - IFNULL(abefore.qtyout,0) - IFNULL(returinbf.qtyin ,0) stokawal, IFNULL(b.qtyin,0) + IFNULL(returoutbf.qtyout,0) stokmasuk, 
			IFNULL(a.qtyout,0) stokkeluar, IFNULL(bbefore.qtyin,0) - IFNULL(abefore.qtyout,0) +
			IFNULL(b.qtyin,0)-IFNULL(a.qtyout,0)-IFNULL(returinbf.qtyin,0)-IFNULL(returinnow.qtyin,0) stokakhir
			, IFNULL(bbefore.qtyin,0)qtyinold, IFNULL(abefore.qtyout,0) qtyoutold, IFNULL(returinbf.qtyin,0) rqtyinold, IFNULL(returoutbf.qtyout,0) rqtyoutold, IFNULL(returinnow.qtyin,0) rqtyinnow,IFNULL(returoutnow.qtyout,0) rqtyoutnow, IFNULL(returoutbf.qtyout,0)+IFNULL(returoutnow.qtyout,0) returlast FROM tb_barang LEFT JOIN (SELECT SUM(tb_detail_barang_keluar.qty) AS qtyout, tb_detail_barang_keluar.id_barang FROM tb_detail_barang_keluar LEFT JOIN tb_barang_keluar ON tb_detail_barang_keluar.id_barang_keluar = tb_barang_keluar.id_barang_keluar WHERE MONTH(tb_barang_keluar.tgl_faktur) = '$month' AND YEAR(tb_barang_keluar.tgl_faktur) = '$year' GROUP BY tb_detail_barang_keluar.id_barang) a ON tb_barang.id_barang = a.id_barang LEFT JOIN
(SELECT SUM(tb_detail_barang_masuk.qty) AS qtyin, tb_detail_barang_masuk.id_barang FROM tb_detail_barang_masuk LEFT JOIN tb_barang_masuk ON tb_detail_barang_masuk.id_barang_masuk = tb_barang_masuk.id_barang_masuk WHERE MONTH(tb_barang_masuk.tgl_po) = '$month' AND YEAR(tb_barang_masuk.tgl_po) = '$year' GROUP BY tb_detail_barang_masuk.id_barang) b ON tb_barang.id_barang = b.id_barang
LEFT JOIN 
(SELECT SUM(tb_detail_barang_masuk.qty) AS qtyin, tb_detail_barang_masuk.id_barang FROM tb_detail_barang_masuk LEFT JOIN tb_barang_masuk ON tb_detail_barang_masuk.id_barang_masuk = tb_barang_masuk.id_barang_masuk WHERE MONTH(tb_barang_masuk.tgl_po) < '$month' AND YEAR(tb_barang_masuk.tgl_po) = '$year' GROUP BY tb_detail_barang_masuk.id_barang) AS bbefore ON tb_barang.id_barang = bbefore.id_barang
LEFT JOIN
(SELECT SUM(tb_detail_barang_keluar.qty) AS qtyout, tb_detail_barang_keluar.id_barang FROM tb_detail_barang_keluar LEFT JOIN tb_barang_keluar ON tb_detail_barang_keluar.id_barang_keluar = tb_barang_keluar.id_barang_keluar WHERE MONTH(tb_barang_keluar.tgl_faktur) < '$month' AND YEAR(tb_barang_keluar.tgl_faktur) = '$year' GROUP BY tb_detail_barang_keluar.id_barang) abefore ON tb_barang.id_barang = abefore.id_barang 
LEFT JOIN
(SELECT SUM(tb_detail_retur_barang_masuk.qty) AS qtyin, tb_detail_retur_barang_masuk.id_barang FROM tb_detail_retur_barang_masuk LEFT JOIN tb_retur_barang_masuk ON tb_detail_retur_barang_masuk.id_retur_barang_masuk = tb_retur_barang_masuk.id_retur_barang_masuk WHERE MONTH(tb_retur_barang_masuk.tgl_retur) < '$month' AND YEAR(tb_retur_barang_masuk.tgl_retur) = '$year' GROUP BY tb_detail_retur_barang_masuk.id_barang) AS returinbf ON tb_barang.id_barang = returinbf.id_barang 
LEFT JOIN
(SELECT SUM(tb_detail_retur_barang_keluar.qty) AS qtyout, tb_detail_retur_barang_keluar.id_barang FROM tb_detail_retur_barang_keluar LEFT JOIN tb_retur_barang_keluar ON tb_detail_retur_barang_keluar.id_retur_barang_keluar = tb_retur_barang_keluar.id_retur_barang_keluar WHERE MONTH(tb_retur_barang_keluar.tgl_retur) < '$month' AND YEAR(tb_retur_barang_keluar.tgl_retur) = '$year' GROUP BY tb_detail_retur_barang_keluar.id_barang) AS returoutbf ON tb_barang.id_barang = returoutbf.id_barang
LEFT JOIN
(SELECT SUM(tb_detail_retur_barang_masuk.qty) AS qtyin, tb_detail_retur_barang_masuk.id_barang FROM tb_detail_retur_barang_masuk LEFT JOIN tb_retur_barang_masuk ON tb_detail_retur_barang_masuk.id_retur_barang_masuk = tb_retur_barang_masuk.id_retur_barang_masuk WHERE MONTH(tb_retur_barang_masuk.tgl_retur) = '$month' AND YEAR(tb_retur_barang_masuk.tgl_retur) = '$year' GROUP BY tb_detail_retur_barang_masuk.id_barang) AS returinnow ON tb_barang.id_barang = returinnow.id_barang 
LEFT JOIN
(SELECT SUM(tb_detail_retur_barang_keluar.qty) AS qtyout, tb_detail_retur_barang_keluar.id_barang FROM tb_detail_retur_barang_keluar LEFT JOIN tb_retur_barang_keluar ON tb_detail_retur_barang_keluar.id_retur_barang_keluar = tb_retur_barang_keluar.id_retur_barang_keluar WHERE MONTH(tb_retur_barang_keluar.tgl_retur) = '$month' AND YEAR(tb_retur_barang_keluar.tgl_retur) = '$year' GROUP BY tb_detail_retur_barang_keluar.id_barang) AS returoutnow ON tb_barang.id_barang = returoutnow.id_barang

	WHERE tb_barang.status = 'Active'");
		return $query;
	}*/

	//revisi
	public function get_report($month, $year, $lm, $ly)
	{
		$query = $this->db->query("SELECT tb_barang.id_barang, tb_barang.nama_barang, IFNULL(bbefore.stok_benar,0) - IFNULL(returinbf.qtyin ,0) stokawal, IFNULL(b.qtyin,0) + IFNULL(returoutbf.qtyout,0) stokmasuk, IFNULL(a.qtyout,0) stokkeluar, IFNULL(bbefore.stok_benar,0) + IFNULL(b.qtyin,0)-IFNULL(a.qtyout,0)-IFNULL(returinbf.qtyin,0)-IFNULL(returinnow.qtyin,0) stokakhir, IFNULL(returinbf.qtyin,0) rqtyinold, IFNULL(returoutbf.qtyout,0) rqtyoutold, IFNULL(returinnow.qtyin,0) rqtyinnow,IFNULL(returoutnow.qtyout,0) rqtyoutnow, IFNULL(bbefore.jumlah_retur ,0)+IFNULL(returoutnow.qtyout,0) returlast FROM tb_barang LEFT JOIN (SELECT SUM(tb_detail_barang_keluar.qty) AS qtyout, tb_detail_barang_keluar.id_barang FROM tb_detail_barang_keluar 
LEFT JOIN tb_barang_keluar ON tb_detail_barang_keluar.id_barang_keluar = tb_barang_keluar.id_barang_keluar WHERE MONTH(tb_barang_keluar.tgl_faktur) = '$month' AND YEAR(tb_barang_keluar.tgl_faktur) = '$year' AND tb_barang_keluar.status = 'Approve' GROUP BY tb_detail_barang_keluar.id_barang) a ON tb_barang.id_barang = a.id_barang LEFT JOIN
(SELECT SUM(tb_detail_barang_masuk.qty) AS qtyin, tb_detail_barang_masuk.id_barang FROM tb_detail_barang_masuk LEFT JOIN tb_barang_masuk ON tb_detail_barang_masuk.id_barang_masuk = tb_barang_masuk.id_barang_masuk WHERE MONTH(tb_barang_masuk.tgl_po) = '$month' AND YEAR(tb_barang_masuk.tgl_po) = '$year' AND tb_barang_masuk.status = 'Approve' GROUP BY tb_detail_barang_masuk.id_barang) b ON tb_barang.id_barang = b.id_barang
LEFT JOIN 
(SELECT tb_detail_stok_opname.id_barang, tb_detail_stok_opname.stok_benar, tb_detail_stok_opname.jumlah_retur FROM tb_detail_stok_opname LEFT JOIN tb_stok_opname ON tb_detail_stok_opname.id_so = tb_stok_opname.id_so WHERE MONTH(tb_stok_opname.tgl_so) = '$lm' AND YEAR(tb_stok_opname.tgl_so) = '$ly') AS bbefore ON tb_barang.id_barang = bbefore.id_barang
LEFT JOIN

(SELECT SUM(tb_detail_retur_barang_masuk.qty) AS qtyin, tb_detail_retur_barang_masuk.id_barang FROM tb_detail_retur_barang_masuk LEFT JOIN tb_retur_barang_masuk ON tb_detail_retur_barang_masuk.id_retur_barang_masuk = tb_retur_barang_masuk.id_retur_barang_masuk WHERE MONTH(tb_retur_barang_masuk.tgl_retur) < '$month' AND YEAR(tb_retur_barang_masuk.tgl_retur) = '$ly' GROUP BY tb_detail_retur_barang_masuk.id_barang) AS returinbf ON tb_barang.id_barang = returinbf.id_barang 
LEFT JOIN
(SELECT SUM(tb_detail_retur_barang_keluar.qty) AS qtyout, tb_detail_retur_barang_keluar.id_barang FROM tb_detail_retur_barang_keluar LEFT JOIN tb_retur_barang_keluar ON tb_detail_retur_barang_keluar.id_retur_barang_keluar = tb_retur_barang_keluar.id_retur_barang_keluar WHERE MONTH(tb_retur_barang_keluar.tgl_retur) < '$month' AND YEAR(tb_retur_barang_keluar.tgl_retur) = '$ly' GROUP BY tb_detail_retur_barang_keluar.id_barang) AS returoutbf ON tb_barang.id_barang = returoutbf.id_barang
LEFT JOIN
(SELECT SUM(tb_detail_retur_barang_masuk.qty) AS qtyin, tb_detail_retur_barang_masuk.id_barang FROM tb_detail_retur_barang_masuk LEFT JOIN tb_retur_barang_masuk ON tb_detail_retur_barang_masuk.id_retur_barang_masuk = tb_retur_barang_masuk.id_retur_barang_masuk WHERE MONTH(tb_retur_barang_masuk.tgl_retur) = '$month' AND YEAR(tb_retur_barang_masuk.tgl_retur) = '$year' GROUP BY tb_detail_retur_barang_masuk.id_barang) AS returinnow ON tb_barang.id_barang = returinnow.id_barang 
LEFT JOIN
(SELECT SUM(tb_detail_retur_barang_keluar.qty) AS qtyout, tb_detail_retur_barang_keluar.id_barang FROM tb_detail_retur_barang_keluar LEFT JOIN tb_retur_barang_keluar ON tb_detail_retur_barang_keluar.id_retur_barang_keluar = tb_retur_barang_keluar.id_retur_barang_keluar WHERE MONTH(tb_retur_barang_keluar.tgl_retur) = '$month' AND YEAR(tb_retur_barang_keluar.tgl_retur) = '$year' GROUP BY tb_detail_retur_barang_keluar.id_barang) AS returoutnow ON tb_barang.id_barang = returoutnow.id_barang

	WHERE tb_barang.status = 'Active'");
		return $query;
	}

	public function get_report_a_days($date, $lm, $ly)
	{
		$query = $this->db->query("SELECT tb_barang.id_barang, tb_barang.nama_barang, IFNULL(bbefore.stok_benar,0) stokawal, IFNULL(b.qtyin,0) stokmasuk, IFNULL(a.qtyout,0) stokkeluar,IFNULL(bbefore.stok_benar,0) + IFNULL(b.qtyin,0)- IFNULL(a.qtyout,0) stokakhir, IFNULL(returinbf.qtyin,0) rqtyinold, IFNULL(returoutbf.qtyout,0) rqtyoutold, IFNULL(returinnow.qtyin,0) rqtyinnow,IFNULL(returoutnow.qtyout,0) rqtyoutnow FROM tb_barang 
LEFT JOIN 
(SELECT SUM(tb_detail_barang_keluar.qty) AS qtyout, tb_detail_barang_keluar.id_barang FROM tb_detail_barang_keluar LEFT JOIN tb_barang_keluar ON tb_detail_barang_keluar.id_barang_keluar = tb_barang_keluar.id_barang_keluar WHERE tb_barang_keluar.tgl_faktur = '$date' AND tb_barang_keluar.status = 'Approve' GROUP BY tb_detail_barang_keluar.id_barang) a ON tb_barang.id_barang = a.id_barang 
LEFT JOIN
(SELECT SUM(tb_detail_barang_masuk.qty) AS qtyin, tb_detail_barang_masuk.id_barang FROM tb_detail_barang_masuk LEFT JOIN tb_barang_masuk ON tb_detail_barang_masuk.id_barang_masuk = tb_barang_masuk.id_barang_masuk WHERE tb_barang_masuk.tgl_po = '$date'  AND tb_barang_masuk.status = 'Approve' GROUP BY tb_detail_barang_masuk.id_barang) b ON tb_barang.id_barang = b.id_barang
LEFT JOIN 
(SELECT tb_detail_stok_opname.id_barang, tb_detail_stok_opname.stok_benar, tb_detail_stok_opname.jumlah_retur FROM tb_detail_stok_opname LEFT JOIN tb_stok_opname ON tb_detail_stok_opname.id_so = tb_stok_opname.id_so WHERE MONTH(tb_stok_opname.tgl_so) = '$lm' AND YEAR(tb_stok_opname.tgl_so) = '$ly') AS bbefore ON tb_barang.id_barang = bbefore.id_barang
LEFT JOIN
(SELECT SUM(tb_detail_retur_barang_masuk.qty) AS qtyin, tb_detail_retur_barang_masuk.id_barang FROM tb_detail_retur_barang_masuk LEFT JOIN tb_retur_barang_masuk ON tb_detail_retur_barang_masuk.id_retur_barang_masuk = tb_retur_barang_masuk.id_retur_barang_masuk WHERE tb_retur_barang_masuk.tgl_retur < '$date' AND tb_retur_barang_masuk.status = 'Approve' GROUP BY tb_detail_retur_barang_masuk.id_barang) AS returinbf ON tb_barang.id_barang = returinbf.id_barang 
LEFT JOIN
(SELECT SUM(tb_detail_retur_barang_keluar.qty) AS qtyout, tb_detail_retur_barang_keluar.id_barang FROM tb_detail_retur_barang_keluar LEFT JOIN tb_retur_barang_keluar ON tb_detail_retur_barang_keluar.id_retur_barang_keluar = tb_retur_barang_keluar.id_retur_barang_keluar WHERE tb_retur_barang_keluar.tgl_retur < '$date' AND tb_retur_barang_keluar.status = 'Approve'  GROUP BY tb_detail_retur_barang_keluar.id_barang) AS returoutbf ON tb_barang.id_barang = returoutbf.id_barang
LEFT JOIN
(SELECT SUM(tb_detail_retur_barang_masuk.qty) AS qtyin, tb_detail_retur_barang_masuk.id_barang FROM tb_detail_retur_barang_masuk LEFT JOIN tb_retur_barang_masuk ON tb_detail_retur_barang_masuk.id_retur_barang_masuk = tb_retur_barang_masuk.id_retur_barang_masuk WHERE tb_retur_barang_masuk.tgl_retur = '$date' GROUP BY tb_detail_retur_barang_masuk.id_barang) AS returinnow ON tb_barang.id_barang = returinnow.id_barang 
LEFT JOIN
(SELECT SUM(tb_detail_retur_barang_keluar.qty) AS qtyout, tb_detail_retur_barang_keluar.id_barang FROM tb_detail_retur_barang_keluar LEFT JOIN tb_retur_barang_keluar ON tb_detail_retur_barang_keluar.id_retur_barang_keluar = tb_retur_barang_keluar.id_retur_barang_keluar WHERE tb_retur_barang_keluar.tgl_retur = '$date' GROUP BY tb_detail_retur_barang_keluar.id_barang) AS returoutnow ON tb_barang.id_barang = returoutnow.id_barang

WHERE tb_barang.status = 'Active'");
		return $query;
	}

	public function get_list_max()
	{
		$query = $this->db->query('SELECT a.id_barang, a.nama_barang, a.stok_akhir, a.max, a.hasil FROM (SELECT `tb_barang`.`id_barang`, `tb_barang`.`nama_barang`, IFNULL(IFNULL(a.stok_akhir, "0")+b.qtyin-IFNULL(c.qtyout, "0")-IFNULL(d.qtyretur, "0"), "0") AS stok_akhir, `tb_barang`.`max`, IFNULL(IFNULL(a.stok_akhir, "0")+b.qtyin-IFNULL(c.qtyout, "0")-IFNULL(d.qtyretur, "0"), "0") > `tb_barang`.`max` hasil FROM `tb_barang` LEFT JOIN (SELECT id_barang, stok_terakhir, jumlah_hilang, jumlah_rusak, (stok_benar-jumlah_hilang-jumlah_rusak) AS stok_akhir FROM tb_detail_stok_opname LEFT JOIN tb_stok_opname ON tb_detail_stok_opname.id_so = tb_stok_opname.id_so WHERE MONTH(tb_stok_opname.tgl_so) = MONTH(CURRENT_DATE)-1 AND YEAR(tb_stok_opname.tgl_so) = YEAR(CURRENT_DATE) ) AS a ON `tb_barang`.`id_barang` = `a`.`id_barang` LEFT JOIN (SELECT tb_barang.id_barang, tb_barang.nama_barang, IFNULL(a.qtyin,"0") AS qtyin FROM tb_barang LEFT JOIN( SELECT id_barang, SUM(tb_detail_barang_masuk.qty) AS qtyin FROM `tb_detail_barang_masuk` LEFT JOIN tb_barang_masuk ON tb_detail_barang_masuk.id_barang_masuk = tb_barang_masuk.id_barang_masuk WHERE MONTH(tb_barang_masuk.tgl_masuk) = MONTH(CURRENT_DATE) AND YEAR(tb_barang_masuk.tgl_masuk) = YEAR(CURRENT_DATE) AND tb_barang_masuk.status = "Approve" GROUP BY tb_detail_barang_masuk.id_barang) AS a ON tb_barang.id_barang = a.id_barang) b ON `tb_barang`.`id_barang` = `b`.`id_barang` LEFT JOIN (SELECT tb_barang.id_barang, tb_barang.nama_barang, IFNULL(b.qtyout,"0") AS qtyout FROM tb_barang LEFT JOIN(SELECT id_barang, SUM(tb_detail_barang_keluar.qty) AS qtyout FROM `tb_detail_barang_keluar` LEFT JOIN tb_barang_keluar ON tb_detail_barang_keluar.id_barang_keluar = tb_barang_keluar.id_barang_keluar WHERE MONTH(tb_barang_keluar.tgl_faktur) = MONTH(CURRENT_DATE) AND YEAR(tb_barang_keluar.tgl_faktur) = YEAR(CURRENT_DATE) AND tb_barang_keluar.status = "Approve" GROUP BY tb_detail_barang_keluar.id_barang) AS b ON tb_barang.id_barang = b.id_barang) AS c ON `tb_barang`.`id_barang` = `c`.`id_barang` LEFT JOIN (SELECT tb_barang.id_barang, tb_barang.nama_barang, IFNULL(b.qty,"0") AS qtyretur FROM tb_barang LEFT JOIN(SELECT id_barang, SUM(tb_detail_retur_barang_masuk.qty) AS qty FROM `tb_detail_retur_barang_masuk` LEFT JOIN tb_retur_barang_masuk ON tb_detail_retur_barang_masuk.id_retur_barang_masuk = tb_retur_barang_masuk.id_retur_barang_masuk WHERE MONTH(tb_retur_barang_masuk.tgl_retur) = MONTH(CURRENT_DATE) AND YEAR(tb_retur_barang_masuk.tgl_retur) = YEAR(CURRENT_DATE) AND tb_retur_barang_masuk.status = "Approve" GROUP BY tb_detail_retur_barang_masuk.id_barang) AS b ON tb_barang.id_barang = b.id_barang) AS d ON `tb_barang`.`id_barang` = `d`.`id_barang` WHERE tb_barang.status = "Active") a WHERE a.hasil = 1');
		return $query;
	
	}

	public function get_list_min()
	{
		$query = $this->db->query('SELECT a.id_barang, a.nama_barang, a.stok_akhir, a.min, a.hasil FROM 
(SELECT `tb_barang`.`id_barang`, `tb_barang`.`nama_barang`, IFNULL(IFNULL(a.stok_akhir, "0")+b.qtyin-IFNULL(c.qtyout, "0")-IFNULL(d.qtyretur, "0"), "0") AS stok_akhir, IFNULL(IFNULL(a.stok_akhir, "0")+b.qtyin-IFNULL(c.qtyout, "0")-IFNULL(d.qtyretur, "0"), "0") < tb_barang.min hasil, `tb_barang`.`min` FROM `tb_barang` LEFT JOIN (SELECT id_barang, stok_terakhir, jumlah_hilang, jumlah_rusak, (stok_benar-jumlah_hilang-jumlah_rusak) AS stok_akhir FROM tb_detail_stok_opname LEFT JOIN tb_stok_opname ON tb_detail_stok_opname.id_so = tb_stok_opname.id_so WHERE MONTH(tb_stok_opname.tgl_so) = MONTH(CURRENT_DATE)-1 AND YEAR(tb_stok_opname.tgl_so) = YEAR(CURRENT_DATE) ) AS a ON `tb_barang`.`id_barang` = `a`.`id_barang` LEFT JOIN (SELECT tb_barang.id_barang, tb_barang.nama_barang, IFNULL(a.qtyin,"0") AS qtyin FROM tb_barang LEFT JOIN( SELECT id_barang, SUM(tb_detail_barang_masuk.qty) AS qtyin FROM `tb_detail_barang_masuk` LEFT JOIN tb_barang_masuk ON tb_detail_barang_masuk.id_barang_masuk = tb_barang_masuk.id_barang_masuk WHERE MONTH(tb_barang_masuk.tgl_masuk) = MONTH(CURRENT_DATE) AND YEAR(tb_barang_masuk.tgl_masuk) = YEAR(CURRENT_DATE) AND tb_barang_masuk.status = "Approve" GROUP BY tb_detail_barang_masuk.id_barang) AS a ON tb_barang.id_barang = a.id_barang) b ON `tb_barang`.`id_barang` = `b`.`id_barang` LEFT JOIN (SELECT tb_barang.id_barang, tb_barang.nama_barang, IFNULL(b.qtyout,"0") AS qtyout FROM tb_barang LEFT JOIN(SELECT id_barang, SUM(tb_detail_barang_keluar.qty) AS qtyout FROM `tb_detail_barang_keluar` LEFT JOIN tb_barang_keluar ON tb_detail_barang_keluar.id_barang_keluar = tb_barang_keluar.id_barang_keluar WHERE MONTH(tb_barang_keluar.tgl_faktur) = MONTH(CURRENT_DATE) AND YEAR(tb_barang_keluar.tgl_faktur) = YEAR(CURRENT_DATE) AND tb_barang_keluar.status = "Approve" GROUP BY tb_detail_barang_keluar.id_barang) AS b ON tb_barang.id_barang = b.id_barang) AS c ON `tb_barang`.`id_barang` = `c`.`id_barang` LEFT JOIN (SELECT tb_barang.id_barang, tb_barang.nama_barang, IFNULL(b.qty,"0") AS qtyretur FROM tb_barang LEFT JOIN(SELECT id_barang, SUM(tb_detail_retur_barang_masuk.qty) AS qty FROM `tb_detail_retur_barang_masuk` LEFT JOIN tb_retur_barang_masuk ON tb_detail_retur_barang_masuk.id_retur_barang_masuk = tb_retur_barang_masuk.id_retur_barang_masuk WHERE MONTH(tb_retur_barang_masuk.tgl_retur) = MONTH(CURRENT_DATE) AND YEAR(tb_retur_barang_masuk.tgl_retur) = YEAR(CURRENT_DATE) AND tb_retur_barang_masuk.status = "Approve" GROUP BY tb_detail_retur_barang_masuk.id_barang) AS b ON tb_barang.id_barang = b.id_barang) AS d ON `tb_barang`.`id_barang` = `d`.`id_barang` WHERE tb_barang.status = "Active") a WHERE a.hasil = 1');
		return $query;
	
	}


	
}