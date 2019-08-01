<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User_model class.
 * 
 * @extends CI_Model
 */
class Supplier_model extends CI_Model {

	var $table = 'tb_supplier';
	var $column_order = array(null, 'id_supplier', 'nama_supplier', 'alamat_supplier','telepon_supplier','status', 'id_supplier');//,'status');//field yang ada di table user
	var $column_search = array('id_supplier', 'nama_supplier', 'alamat_supplier','telepon_supplier', 'status');//,'status');//field yang dizinkan untuk pencarian
	var $order = array('id_supplier'=>'asc'); //default sort
	
	public function __construct() {
		
		parent::__construct();
		$this->load->database();
		
	}
	private function _get_datatables_query() 
	{
		$this->db->select('id_supplier, nama_supplier, alamat_supplier, telepon_supplier, status');
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

	public function new_supplier($id, $nama, $alamat, $telepon)
	{
		$data = array(
			'id_supplier' => $id,
			'nama_supplier' =>$nama,
			'alamat_supplier' =>$alamat,
			'telepon_supplier' => $telepon);
		return $this->db->insert($this->table, $data);
	}


	public function hapus_supplier($id)
	{	
		return $this->db->delete($this->table, array('id_supplier' => $id));
	}

	public function get_data($id)
	{
		$this->db->from($this->table);
		$this->db->where('id_supplier', $id);
		return $this->db->get()->row();
	}

	public function get_last_id()
	{
		$this->db->select('id_supplier');
		$this->db->from($this->table);
		$this->db->order_by('id_supplier', 'DESC');
		$this->db->limit('1');
		return $this->db->get();
	}

	public function edit_supplier($id, $status)
	{
		$data = array(
			'id_supplier' => $id,
			'status'=>$status);
		$this->db->where('id_supplier', $id);
		return $this->db->update($this->table, $data);
	}

	public function get_data_supplier()
	{
		$this->db->select('id_supplier, nama_supplier');
		$this->db->from($this->table);
		$this->db->where('status', 'Active');
		return $this->db->get()->result();
	}


	
}