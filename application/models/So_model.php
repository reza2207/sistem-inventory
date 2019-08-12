<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User_model class.
 * 
 * @extends CI_Model
 */
class So_model extends CI_Model {

	var $table = 'tb_stok_opname';
	var $column_order = array('id_so', 'tgl_so', null);//,'status');//field yang ada di table user
	var $column_search = array('id_so', 'tgl_so');//,'status');//field yang dizinkan untuk pencarian
	var $order = array('id_so'=>'asc'); //default sort
	
	public function __construct() {
		
		parent::__construct();
		$this->load->database();
		
	}
	private function _get_datatables_query() 
	{
		$this->db->select('id_so, tgl_so');
		$this->db->from($this->table);
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

	public function cek_so($m, $y)
	{
		$this->db->from($this->table);
		$this->db->where('MONTH(tgl_so)', $m);
		$this->db->where('YEAR(tgl_so)', $y);
		return $this->db->get();
	}

	public function new_data($id, $tgl_so)
	{
		$data = array(
			'id_so'=>$id,
			'tgl_so'=>$tgl_so);
		return $this->db->insert($this->table, $data);
	}

	/*public function cek_stok()
	{
		$this->db->select('id_detail_so, id_so, id_barang, ')
	}*/

	/*public function get_data_id($id){
		$this->db->select("tb_stok_opname.id_so, tb_stok_opname.tgl_so, a.qtystok, a.qtybenar,a.jumlah_rusak, a.nama_barang");
		$this->db->from("tb_stok_opname");
		$this->db->JOIN("(SELECT GROUP_CONCAT(tb_detail_stok_opname.id_barang SEPARATOR '|') AS id_barang, GROUP_CONCAT(stok_terakhir SEPARATOR '|') AS qtystok, id_detail_so, id_so, GROUP_CONCAT(stok_benar SEPARATOR '|') AS qtybenar, GROUP_CONCAT(tb_barang.nama_barang SEPARATOR '|') AS nama_barang, GROUP_CONCAT(jumlah_rusak SEPARATOR '|') AS jumlah_rusak  FROM tb_detail_stok_opname LEFT JOIN tb_barang ON tb_detail_stok_opname.id_barang = tb_barang.id_barang GROUP BY id_so) AS a", "tb_stok_opname.id_so = a.id_so", "LEFT");
		$this->db->where('tb_stok_opname.id_so', $id);
		return $this->db->get();
	}*/

	public function get_data_id($id)
	{
		$this->db->select("tb_detail_stok_opname.id_barang, stok_terakhir AS qtystok, id_detail_so, tb_stok_opname.id_so, stok_benar AS qtybenar, tb_barang.nama_barang,jumlah_rusak, tb_stok_opname.tgl_so, tb_detail_stok_opname.jumlah_retur");
		$this->db->from("tb_detail_stok_opname");
		$this->db->join("tb_barang", "tb_detail_stok_opname.id_barang = tb_barang.id_barang", "LEFT");
		$this->db->join("tb_stok_opname", "tb_detail_stok_opname.id_so = tb_stok_opname.id_so", "LEFT");
		$this->db->where("tb_detail_stok_opname.id_so", $id);
		return $this->db->get();
	}

	public function get_data_so_id($id){
		$this->db->select("id_so, tgl_so, MONTH(tgl_so) AS bln");
		$this->db->from($this->table);
		return $this->db->get()->row();
	}

	public function hapus_d()
	{
		$this->db->empty_table('tb_detail_stok_opname');
	}

	


	
	
}