<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Album_model extends CI_Model
{
  public $table = 'album';
  public $id    = 'id_album';
  public $order = 'DESC';

  function ambil_album()
  {
  	$data=$this->db->get($this->table);
  	if($data->num_rows()>0)
    {
  		foreach ($data->result_array() as $row)
			{
				$result['']= '- Pilih Album -';
				$result[$row['id_album']]= ucwords(strtolower($row['nama_album']));
			}
			return $result;
		}
	}

  function get_all()
  {
    $this->db->order_by($this->id, $this->order);
    return $this->db->get($this->table)->result();
  }

  function get_all_new_home()
  {
    $this->db->order_by($this->id, $this->order);
    return $this->db->get($this->table)->result();
  }

  function get_all_sidebar()
  {
    $this->db->limit(3);
    $this->db->order_by($this->id, $this->order);
    return $this->db->get($this->table)->result();
  }

  function get_all_random()
  {
    $this->db->limit(3);
    $this->db->order_by($this->id, 'random');
    return $this->db->get($this->table)->result();
  }

  function get_all_arsip($per_page,$dari)
  {
    $this->db->order_by($this->id, 'DESC');
    $query = $this->db->get($this->table,$per_page,$dari);
    return $query->result();
  }

  function get_data_sidebar()
  {
    $this->db->limit(5);
    $this->db->order_by($this->id, $this->order);
    return $this->db->get($this->table)->result();
  }

  function get_all_album_sidebar()
  {
    $this->db->limit(5);
    $this->db->where('publish','ya');
    $this->db->order_by($this->id, $this->order);
    return $this->db->get($this->table)->result();
  }


  // get data by id
  function get_by_id($id)
  {
    $this->db->where($this->id, $id);
    return $this->db->get($this->table)->row();
  }

  function get_by_id_front($id)
  {
    $this->db->join('foto', 'album.id_album = foto.album_id');
    $this->db->where('album.slug_album', $id);
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

  // insert data
  function insert_komentar($data)
  {
    $this->db->insert('komentar', $data);
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
  function get_cari_album()
  {
    $cari_album = $this->input->post('cari_album');

    $this->db->like('nama_album', $cari_album);
    return $this->db->get($this->table)->result();
  }

}
