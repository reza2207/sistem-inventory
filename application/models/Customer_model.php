<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User_model class.
 * 
 * @extends CI_Model
 */
class Customer_model extends CI_Model {

	var $table = 'tb_customer';
	var $column_order = array('id_customer', 'nama_customer', 'alamat_customer','telepon_customer','status', null);//,'status');//field yang ada di table user
	var $column_search = array('id_customer', 'nama_customer', 'alamat_customer','telepon_customer', 'status');//,'status');//field yang dizinkan untuk pencarian
	var $order = array('id_customer'=>'asc'); //default sort
	
	public function __construct() {
		
		parent::__construct();
		$this->load->database();
		
	}
	private function _get_datatables_query() 
	{
		$this->db->select('tb_customer.id_customer, tb_customer.nama_customer, tb_customer.alamat_customer, tb_customer.telepon_customer, tb_customer.status');
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

	public function new_customer($idcust, $nama, $alamat, $telp)
	{
		$data = array(
			'id_customer' => $idcust,
			'nama_customer' =>$nama,
			'alamat_customer' =>$alamat,
			'telepon_customer' =>  $telp);
		return $this->db->insert($this->table, $data);
	}


	public function hapus_customer($id)
	{	
		return $this->db->delete($this->table, array('id_customer' => $id));
	}

	public function get_data($id)
	{
		$this->db->from($this->table);
		$this->db->where('id_customer', $id);
		return $this->db->get()->row();
	}

	
	public function edit_customer($id, $status)
	{
		$data = array(
			'status'=>$status);
		$this->db->where('id_customer', $id);
		return $this->db->update($this->table, $data);
	}

	public function get_list()
	{	
		$this->db->select('id_customer, nama_customer');
		$this->db->from($this->table);
		$this->db->where('status','Active');
		return $this->db->get();
	}


	
}