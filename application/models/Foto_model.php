<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Foto_model extends CI_Model
{
  public $table = 'foto';
  public $id    = 'id_foto';
  public $order = 'DESC';

  function get_all()
  {
    $this->db->select('id_album, id_foto, nama_foto, nama_album, foto.foto as gambar, foto.created_at, foto.created_by, foto.modified_at, foto.modified_by');
    $this->db->join('album', 'foto.album_id = album.id_album');
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

  function get_all_foto_sidebar()
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
    $this->db->where('slug_foto', $id);
    return $this->db->get($this->table)->row();
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
  function get_cari_foto()
  {
    $cari_foto = $this->input->post('cari_foto');

    $this->db->like('nama_foto', $cari_foto);
    return $this->db->get($this->table)->result();
  }

}
