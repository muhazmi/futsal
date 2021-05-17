<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wilayah_model extends CI_Model
{
  function get_provinsi()
  {
    $this->db->order_by('nama_provinsi','ASC');
  	$sql_prov=$this->db->get('provinsi');
  	if($sql_prov->num_rows()>0)
    {
  		foreach ($sql_prov->result_array() as $row)
			{
				$result['']= '- Pilih Provinsi -';
				$result[$row['id_provinsi']]= ucwords(strtolower($row['nama_provinsi']));
			}
			return $result;
		}
	}

  function get_kota($id)
  {
    $this->db->where('provinsi_id',$id);
  	$this->db->order_by('nama_kota','ASC');
  	$sql_kota=$this->db->get('kota');
  	if($sql_kota->num_rows()>0)
    {
  		foreach ($sql_kota->result_array() as $row)
      {
        $result[$row['id_kota']]= ucwords(strtolower($row['nama_kota']));
      }
      return $result;
    }
    // else
    // {
		//   $result['-']= '- Belum Ada Data -';
		// }
    // return $result;
	}

}
