<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bank_model extends CI_Model
{
  public $table = 'bank';
  public $id    = 'id_bank';
  public $order = 'DESC';

  function get_all()
  {
    return $this->db->get($this->table)->result();
  }

}
