<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Company_model extends CI_Model
{
  public $table = 'company';
  public $id    = 'id_company';
  public $order = 'ASC';

  var $column = array('id_company','company_name','company_desc','company_address','company_phone','company_phone2','company_fax','company_email','created','modified');

  function get_all()
  {
    $this->db->order_by($this->id, $this->order);
    return $this->db->get($this->table)->result();
  }

  function get_all_company()
  {
    $this->db->order_by('company_name', 'ASC');
    $query = $this->db->get($this->table);

    if($query->num_rows() > 0){
     	$data = array();
	    foreach ($query->result_array() as $row)
	    {
	      $data[$row['id_company']] = $row['company_name'];
	    }
	    return $data;
    }
  }

  function insert($data)
  {
    $this->db->insert($this->table, $data);
  }

  function update($id, $data)
  {
    $this->db->where($this->id,$id);
    $this->db->update($this->table, $data);
  }

  // delete data
  function delete($id)
  {
    $this->db->where($this->id, $id);
    $this->db->delete($this->table);
  }

  function delete_userfile($id)
  {
    $this->db->select("logo, logo_type");
    $this->db->where($this->id,$id);
    return $this->db->get($this->table)->row();
  }

  public function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

  private function _get_datatables_query()
	{
		$this->db->from($this->table);

		$i = 0;

		foreach ($this->column as $item) // loop column
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$column[$i] = $item; // set column array variable to order processing
			$i++;
		}

		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		}
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

  public function count_filtered()
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

  // get data by id
  function get_by_id($id)
  {
    $this->db->where('id_company', $id);
    return $this->db->get($this->table)->row();
  }

  function del_by_id($id)
  {
    $this->db->select("foto, foto_type");
    $this->db->where($this->id,$id);
    return $this->db->get($this->table)->row();
  }

  function get_by_company()
  {
    $this->db->where('id_company', '1');
    return $this->db->get($this->table)->row();
  }

  function total_rows() {
    return $this->db->get($this->table)->num_rows();
  }

}
