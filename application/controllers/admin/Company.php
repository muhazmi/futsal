<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Company extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->model('Company_model');

    $this->data['module'] = 'Company';

		if (!$this->ion_auth->logged_in()){redirect('auth/login', 'refresh');}
  }

  public function update($id)
  {
    $row = $this->Company_model->get_by_id($id);
    $this->data['company'] = $this->Company_model->get_by_id($id);

    if ($row)
    {
      // if($this->session->userdata("company_id") != $row->)
      // {
      $this->data['title']          = 'Update Company';
      $this->data['action']         = site_url('admin/company/update_action');
      $this->data['button_submit']  = 'Update';
      $this->data['button_reset']   = 'Reset';

      $this->data['id_company'] = array(
        'name'  => 'id_company',
        'id'    => 'id_company',
        'type'  => 'hidden',
      );

      $this->data['company_name'] = array(
        'name'  => 'company_name',
        'id'    => 'company_name',
        'class' => 'form-control',
      );

      $this->data['company_email'] = array(
        'name'  => 'company_email',
        'id'    => 'company_email',
        'class' => 'form-control',
      );

      $this->data['company_desc'] = array(
        'name'  => 'company_desc',
        'id'    => 'company_desc',
        'class' => 'form-control',
        'rows'  => '2',
      );

      $this->data['company_address'] = array(
        'name'  => 'company_address',
        'id'    => 'company_address',
        'class' => 'form-control',
        'rows'  => '2',
      );

      $this->data['company_phone'] = array(
        'name'  => 'company_phone',
        'id'    => 'company_phone',
        'class' => 'form-control',
        'type'  => 'number',
      );

      $this->data['company_phone2'] = array(
        'name'  => 'company_phone2',
        'id'    => 'company_phone',
        'class' => 'form-control',
        'type'  => 'number',
      );

      $this->data['company_fax'] = array(
        'name'  => 'company_fax',
        'id'    => 'company_fax',
        'class' => 'form-control',
      );

      $this->load->view('back/company/company_edit', $this->data);
    }
      else
      {
        $this->session->set_flashdata('message', '<div class="alert alert-warning alert">Data tidak ditemukan</div>');
        redirect(site_url('admin/company/update/1'));
      }
  }

  public function update_action()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE)
    {
      $this->update($this->input->post('id_company'));
    }
      else
      {
        $nmfile = strtolower(url_title($this->input->post('company_name'))).date('YmdHis');
        $id['id_company'] = $this->input->post('id_company');

        /* Jika file upload diisi */
        if ($_FILES['foto']['error'] <> 4)
        {
          $delete = $this->Company_model->del_by_id($this->input->post('id_company'));

          // menyimpan lokasi gambar dalam variable
          $dir = "assets/images/company/".$delete->foto.$delete->foto_type;
          $dir_thumb = "assets/images/company/".$delete->foto.'_thumb'.$delete->foto_type;

          // Hapus foto dan thumbnail
          unlink($dir);
          unlink($dir_thumb);

          $nmfile = strtolower(url_title($this->input->post('company_name'))).date('YmdHis');

          //load uploading file library
          $config['upload_path']      = './assets/images/company/';
          $config['allowed_types']    = 'jpg|jpeg|png|gif';
          $config['max_size']         = '2048'; // 2 MB
          $config['file_name']        = $nmfile; //nama yang terupload nantinya

          $this->load->library('upload', $config);

          // Jika file gagal diupload -> kembali ke form update
          if (!$this->upload->do_upload('foto'))
          {
            //file gagal diupload -> kembali ke form tambah
            $error = array('error' => $this->upload->display_errors());
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert">'.$error['error'].'</div>');

            $this->update($this->input->post('id_company'));
          }
            // Jika file berhasil diupload -> lanjutkan ke query INSERT
            else
            {
              $foto = $this->upload->data();
              // library yang disediakan codeigniter
              $thumbnail                = $config['file_name'];
              //nama yang terupload nantinya
              $config['image_library']  = 'gd2';
              // gambar yang akan dibuat thumbnail
              $config['source_image']   = './assets/images/company/'.$foto['file_name'].'';
              // membuat thumbnail
              $config['create_thumb']   = TRUE;
              // rasio resolusi
              $config['maintain_ratio'] = FALSE;
              // lebar
              $config['width']          = 250;
              // tinggi
              $config['height']         = 250;

              $this->load->library('image_lib', $config);
              $this->image_lib->resize();

              $data = array(
                'company_name'      => $this->input->post('company_name'),
                'company_desc'      => $this->input->post('company_desc'),
                'company_address'   => $this->input->post('company_address'),
                'company_email'     => $this->input->post('company_email'),
                'company_phone'     => $this->input->post('company_phone'),
                'company_phone2'    => $this->input->post('company_phone2'),
                'company_fax'       => $this->input->post('company_fax'),
                'foto'              => $nmfile,
                'foto_type'         => $foto['file_ext'],
                'modified_by'       => $this->session->userdata('username')
              );

              $this->Company_model->update($this->input->post('id_company'), $data);
              $this->session->set_flashdata('message', '<div class="alert alert-success alert">Edit Data Berhasil</div>');
              redirect(site_url('admin/company/update/1'));
            }
        }
          // Jika file upload kosong
          else
          {
            $data = array(
              'company_name'      => $this->input->post('company_name'),
              'company_desc'      => $this->input->post('company_desc'),
              'company_address'   => $this->input->post('company_address'),
              'company_email'     => $this->input->post('company_email'),
              'company_phone'     => $this->input->post('company_phone'),
              'company_phone2'    => $this->input->post('company_phone2'),
              'company_fax'       => $this->input->post('company_fax'),
              'modified_by'      => $this->session->userdata('username')
            );

            $this->Company_model->update($this->input->post('id_company'), $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success alert">Edit Data Berhasil</div>');
            redirect(site_url('admin/company/update/1'));
          }
      }
  }

  public function _rules()
  {
    $this->form_validation->set_rules('company_name', 'Nama Perusahaan/ Organisasi', 'trim|required');
    $this->form_validation->set_rules('company_email', 'Email', 'required|valid_email');
    $this->form_validation->set_rules('company_phone', 'No. HP', 'numeric');
    $this->form_validation->set_rules('company_phone2', 'Telpon', 'numeric');

    // set pesan form validasi error
    $this->form_validation->set_message('required', '{field} wajib diisi');
    $this->form_validation->set_message('valid_email', '{field} wajib diisi');
    $this->form_validation->set_message('numeric', '{field} diisi angka saja');

    $this->form_validation->set_rules('id_company', 'id_company', 'trim');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert" align="left">', '</div>');
  }

}
