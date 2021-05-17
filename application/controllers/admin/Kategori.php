<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kategori extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->model('Kategori_model');

    $this->data['module'] = 'Kategori';

    if(!$this->ion_auth->logged_in()){redirect('admin/auth/login', 'refresh');}
    elseif(!$this->ion_auth->is_superadmin() && !$this->ion_auth->is_admin()){redirect(base_url());}
  }

  public function index()
  {
    $this->data['title']    = 'Data '.$this->data['module'];
    $this->data['get_all']  = $this->Kategori_model->get_all();

    $this->load->view('back/kategori/kategori_list', $this->data);
  }

  public function create()
  {
    $this->data['title']          = 'Tambah '.$this->data['module'].' Baru';
    $this->data['action']         = site_url('admin/kategori/create_action');
    $this->data['button_submit']  = 'Simpan';
    $this->data['button_reset']   = 'Reset';

    $this->data['id_kat'] = array(
      'name'  => 'id_kat',
      'id'    => 'id_kat',
      'type'  => 'hidden',
    );
    $this->data['nama_kategori'] = array(
      'name'  => 'nama_kategori',
      'id'    => 'nama_kategori',
      'type'  => 'text',
      'class' => 'form-control',
      'value' => $this->form_validation->set_value('nama_kategori'),
    );

    $this->load->view('back/kategori/kategori_add', $this->data);
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
          'nama_kategori'   => $this->input->post('nama_kategori'),
          'slug_kat'        => strtolower(url_title($this->input->post('nama_kategori'))),
          'created_by'      => $this->session->userdata('username'),
        );

        // eksekusi query INSERT
        $this->Kategori_model->insert($data);
        // set pesan data berhasil dibuat
        $this->session->set_flashdata('message', '<div class="alert alert-success alert">Data berhasil dibuat</div>');
        redirect(site_url('admin/kategori'));
      }
  }

  public function update($id)
  {
    $row = $this->Kategori_model->get_by_id($id);
    $this->data['kategori'] = $this->Kategori_model->get_by_id($id);

    if ($row)
    {
      $this->data['title']          = 'Ubah Data '.$this->data['module'];
      $this->data['action']         = site_url('admin/kategori/update_action');
      $this->data['button_submit']  = 'Simpan';
      $this->data['button_reset']   = 'Reset';

      $this->data['id_kategori'] = array(
        'name'  => 'id_kategori',
        'id'    => 'id_kategori',
        'type'  => 'hidden',
      );

      $this->data['nama_kategori'] = array(
        'name'  => 'nama_kategori',
        'id'    => 'nama_kategori',
        'type'  => 'text',
        'class' => 'form-control',
      );

      $this->load->view('back/kategori/kategori_edit', $this->data);
    }
      else
      {
        $this->session->set_flashdata('message', '<div class="alert alert-warning alert">Data tidak ditemukan</div>');
        redirect(site_url('admin/kategori'));
      }
  }

  public function update_action()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE)
    {
      $this->update($this->input->post('id_kategori'));
    }
      else
      {
        $data = array(
          'nama_kategori'   => $this->input->post('nama_kategori'),
          'slug_kat'        => strtolower(url_title($this->input->post('nama_kategori'))),
          'modified_by'     => $this->session->userdata('username'),
        );

        $this->Kategori_model->update($this->input->post('id_kategori'), $data);
        $this->session->set_flashdata('message', '<div class="alert alert-success alert">Edit Data Berhasil</div>');
        redirect(site_url('admin/kategori'));
      }
  }

  public function delete($id)
  {
    $row = $this->Kategori_model->get_by_id($id);

    if ($row)
    {
      $this->Kategori_model->delete($id);
      $this->session->set_flashdata('message', '<div class="alert alert-success alert">Data berhasil dihapus</div>');
      redirect(site_url('admin/kategori'));
    }
      // Jika data tidak ada
      else
      {
        $this->session->set_flashdata('message', '<div class="alert alert-warning alert">Data tidak ditemukan</div>');
        redirect(site_url('admin/kategori'));
      }
  }

  public function _rules()
  {
    $this->form_validation->set_rules('nama_kategori', 'nama Kategori', 'trim|required');

    // set pesan form validasi error
    $this->form_validation->set_message('required', '{field} wajib diisi');

    $this->form_validation->set_rules('id_kategori', 'id_kategori', 'trim');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert">', '</div>');
  }

}
