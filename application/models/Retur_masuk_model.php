<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User_model class.
 * 
 * @extends CI_Model
 */
class Retur_masuk_model extends CI_Model {

	var $table = 'tb_retur_barang_masuk';
	var $column_order = array('id_retur_barang_masuk', 'no_surat_jalan','tgl_surat_jalan','nama_supplier','tgl_retur','status', null);//,'status');//field yang ada di table user
	var $column_search = array('id_barang_masuk', 'no_surat_jalan', 'tgl_surat_jalan','nama_supplier');//,'status');//field yang dizinkan untuk pencarian
	var $order = array('tgl_retur'=>'asc'); //default sort
	
	public function __construct() {
		
		parent::__construct();
		$this->load->database();
		
	}
	private function _get_datatables_query() 
	{
		$this->db->select('tb_retur_barang_masuk.id_retur_barang_masuk,  tb_retur_barang_masuk.tgl_retur, tb_retur_barang_masuk.no_surat_jalan, tb_barang_masuk.tgl_surat_jalan, tb_supplier.nama_supplier, tb_retur_barang_masuk.status');
		$this->db->from($this->table);
		$this->db->join('tb_barang_masuk','tb_retur_barang_masuk.no_surat_jalan =  tb_barang_masuk.no_surat_jalan', 'LEFT');
		$this->db->join('tb_supplier','tb_barang_masuk.id_supplier =  tb_supplier.id_supplier', 'LEFT');
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

	public function new_data($id, $nosj, $tglretur, $keterangan)
	{
		$data = array(
			'id_retur_barang_masuk'=>$id,
			'no_surat_jalan'=>$nosj,
			'tgl_retur'=>$tglretur,
			'keterangan'=>$keterangan);
		return $this->db->insert($this->table, $data);
	}

	public function hapus_data($id){
		return $this->db->delete($this->table, array('id_retur_barang_masuk' => $id));
	}

	public function hapus_detail($id){
		return $this->db->delete('tb_detail_retur_barang_masuk', array('id_detail_retur_barang_masuk' => $id));

	}

	public function get_data_id($id){
		$this->db->select("tb_retur_barang_masuk.id_retur_barang_masuk, tb_supplier.nama_supplier, tb_barang_masuk.tgl_surat_jalan, a.id_barang, a.qty,a.nama, tb_retur_barang_masuk.no_surat_jalan, tb_retur_barang_masuk.status, tb_retur_barang_masuk.keterangan, tb_retur_barang_masuk.tgl_retur");
		$this->db->from($this->table);
		$this->db->JOIN('tb_barang_masuk', 'tb_retur_barang_masuk.no_surat_jalan = tb_barang_masuk.no_surat_jalan', 'LEFT');
		$this->db->JOIN("(SELECT GROUP_CONCAT(id_detail_retur_barang_masuk SEPARATOR '|') AS id_barang, GROUP_CONCAT(qty SEPARATOR '|') AS qty, id_retur_barang_masuk, GROUP_CONCAT(tb_barang.nama_barang SEPARATOR '|') AS nama FROM tb_detail_retur_barang_masuk LEFT JOIN tb_barang ON tb_detail_retur_barang_masuk.id_barang = tb_barang.id_barang GROUP BY id_retur_barang_masuk) AS a", "tb_retur_barang_masuk.id_retur_barang_masuk = a.id_retur_barang_masuk", "LEFT");
		$this->db->JOIN("tb_supplier", "tb_barang_masuk.id_supplier = tb_supplier.id_supplier", "LEFT");
		$this->db->where('tb_retur_barang_masuk.id_retur_barang_masuk', $id);
		return $this->db->get();
	}


	public function get_barang($id)
	{
		$this->db->select('tb_detail_retur_barang_masuk.id_retur_barang_masuk, tb_detail_retur_barang_masuk.id_barang, tb_detail_retur_barang_masuk.qty, tb_barang.nama_barang');
		$this->db->from('tb_detail_retur_barang_masuk');
		$this->db->join('tb_barang', 'tb_detail_retur_barang_masuk.id_barang = tb_barang.id_barang', 'left');
		$this->db->where('tb_detail_retur_barang_masuk.id_retur_barang_masuk',$id);
		return $this->db->get();

	}

	public function approve_trans($id, $status)
	{
		$data = array(
				'status' =>$status);
		$this->db->where('id_retur_barang_masuk', $id);
		return $this->db->update($this->table, $data);
	}

	public function get_status($id)
	{
		$this->db->select('status');
		$this->db->from($this->table);
		$this->db->where('id_retur_barang_masuk',$id);
		return $this->db->get();
	}
}