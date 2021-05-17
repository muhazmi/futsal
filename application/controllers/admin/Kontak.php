<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kontak extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->model('Kontak_model');

    $this->data['module'] = 'Kontak';

    if(!$this->ion_auth->logged_in()){redirect('admin/auth/login', 'refresh');}
    elseif(!$this->ion_auth->is_superadmin() && !$this->ion_auth->is_admin()){redirect(base_url());}
  }

  public function index()
  {
    $this->data['title']    = 'Data '.$this->data['module'];
    $this->data['get_all']  = $this->Kontak_model->get_all();

    $this->load->view('back/kontak/kontak_list', $this->data);
  }

  public function create()
  {
    $this->data['title']          = 'Tambah Data '.$this->data['module'];
    $this->data['action']         = site_url('admin/kontak/create_action');
    $this->data['button_submit']  = 'Simpan';
    $this->data['button_reset']   = 'Reset';

    $this->data['nama_kontak'] = array(
      'name'  => 'nama_kontak',
      'id'    => 'nama_kontak',
      'class' => 'form-control',
      'value' => $this->form_validation->set_value('nama_kontak'),
    );

    $this->data['nohp'] = array(
      'name'  => 'nohp',
      'id'    => 'nohp',
      'class' => 'form-control',
      'value' => $this->form_validation->set_value('nohp'),
    );

    $this->load->view('back/kontak/kontak_add', $this->data);
  }

  public function create_action()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE)
    {
      $this->create();
    }
      else
      {
        $data = array(
          'nama_kontak'   => $this->input->post('nama_kontak'),
          'nohp'          => $this->input->post('nohp'),
          'created_by'    => $this->session->userdata('username'),
        );

        // eksekusi query INSERT
        $this->Kontak_model->insert($data);
        // set pesan data berhasil dibuat
        $this->session->set_flashdata('message', '
        <div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
					<i class="ace-icon fa fa-bullhorn green"></i> Data berhasil disimpan
        </div>');
        redirect(site_url('admin/kontak'));
      }
  }

  public function update($id)
  {
    $row = $this->Kontak_model->get_by_id($id);
    $this->data['kontak'] = $this->Kontak_model->get_by_id($id);

    if ($row)
    {
      $this->data['title']          = 'Ubah Data '.$this->data['module'];
      $this->data['action']         = site_url('admin/kontak/update_action');
      $this->data['button_submit']  = 'Simpan';
      $this->data['button_reset']   = 'Reset';

      $this->data['id_kontak'] = array(
        'name'  => 'id_kontak',
        'id'    => 'id_kontak',
        'type'  => 'hidden',
      );

      $this->data['nama_kontak'] = array(
        'name'  => 'nama_kontak',
        'id'    => 'nama_kontak',
        'class' => 'form-control',
      );

      $this->data['nohp'] = array(
        'name'  => 'nohp',
        'id'    => 'nohp',
        'class' => 'form-control',
      );

      $this->load->view('back/kontak/kontak_edit', $this->data);
    }
      else
      {
        $this->session->set_flashdata('message', '
        <div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
					<i class="ace-icon fa fa-bullhorn green"></i> Data tidak ditemukan
        </div>');
        redirect(site_url('admin/kontak'));
      }
  }

  public function update_action()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE)
    {
      $this->update($this->input->post('id_kontak'));
    }
      else
      {
        $data = array(
          'nama_kontak'   => $this->input->post('nama_kontak'),
          'nohp'          => $this->input->post('nohp'),
          'modified_by'   => $this->session->userdata('username'),
        );

        $this->Kontak_model->update($this->input->post('id_kontak'), $data);
        $this->session->set_flashdata('message', '
        <div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
					<i class="ace-icon fa fa-bullhorn green"></i> Data berhasil disimpan
        </div>');
        redirect(site_url('admin/kontak'));
      }
  }

  public function delete($id)
  {
    $row = $this->Kontak_model->get_by_id($id);

    if ($row)
    {
      $this->Kontak_model->delete($id);
      $this->session->set_flashdata('message', '
      <div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
        <i class="ace-icon fa fa-bullhorn green"></i> Data berhasil dihapus
      </div>');
      redirect(site_url('admin/kontak'));
    }
      // Jika data tidak ada
      else
      {
        $this->session->set_flashdata('message', '
        <div class="alert alert-block alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
          <i class="ace-icon fa fa-bullhorn green"></i> Data tidak ditemukan
        </div>');
        redirect(site_url('admin/kontak'));
      }
  }

  public function _rules()
  {
    $this->form_validation->set_rules('nama_kontak', 'nama_kontak Kontak', 'trim|required');

    // set pesan form validasi error
    $this->form_validation->set_message('required', '{field} wajib diisi');

    $this->form_validation->set_rules('id_kontak', 'id_kontak', 'trim');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert" align="left">', '</div>');
  }

}
