<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subscriber_model extends CI_Model
{
  public $table = 'subscriber';
  public $id    = 'id_subscriber';
  public $order = 'DESC';

  var $column = array('id_subscriber','email');

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

  public function get_datatables()
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

  function get_all_combo()
  {
  	$data=$this->db->get($this->table);
  	if($data->num_rows()>0)
    {
  		foreach ($data->result_array() as $row)
			{				
				$result[$row['email']]= ucwords(strtolower($row['email']));
			}
			return $result;
		}
	}

  function get_all()
  {
    $this->db->order_by($this->id, $this->order);
    return $this->db->get($this->table)->result();
  }


  // get data by id
  function get_by_id($id)
  {
    $this->db->where($this->id, $id);
    return $this->db->get($this->table)->row();
  }

  function get_by_email($id)
  {
    $this->db->where('email', $id);
    return $this->db->get($this->table)->row();
  }

  function get_by_id_front($id)
  {
    $this->db->join('foto', 'subscriber.id_subscriber = foto.subscriber_id');
    $this->db->where('subscriber.slug_subscriber', $id);
    return $this->db->get($this->table);
  }

  // get total rows
  function total_rows() {
    return $this->db->get($this->table)->num_rows();
  }

  // insert data
  function insert($data)
  {
    $this->db->insert($this->table, $data);
  }

  // update data
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

  function del_by_id($id)
  {
    $this->db->select("foto");
    $this->db->where($this->id,$id);
    return $this->db->get($this->table)->row();
  }

  // get all
  function get_cari_subscriber()
  {
    $cari_subscriber = $this->input->post('cari_subscriber');

    $this->db->like('judul_subscriber', $cari_subscriber);
    return $this->db->get($this->table)->result();
  }

}
