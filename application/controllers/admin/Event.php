<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Event extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->model('Event_model');
    $this->load->model('Kategori_model');

    $this->data['module'] = 'Event';

    if(!$this->ion_auth->logged_in()){redirect('admin/auth/login', 'refresh');}
    elseif(!$this->ion_auth->is_superadmin() && !$this->ion_auth->is_admin()){redirect(base_url());}
  }

  public function index()
  {
    $this->data['title']    = 'Data '.$this->data['module'];
    $this->data['get_all']  = $this->Event_model->get_all();

    $this->load->view('back/event/event_list', $this->data);
  }

  public function create()
  {
    $this->data['title']          = 'Tambah '.$this->data['module'].' Baru';
    $this->data['action']         = site_url('admin/event/create_action');
    $this->data['button_submit']  = 'Simpan';
    $this->data['button_reset']   = 'Reset';

    $this->data['nama_event'] = array(
      'name'  => 'nama_event',
      'id'    => 'nama_event',
      'class' => 'form-control',
      'value' => $this->form_validation->set_value('nama_event'),
    );

    $this->data['deskripsi'] = array(
      'name'  => 'deskripsi',
      'id'    => 'deskripsi',
      'class' => 'form-control',
      'value' => $this->form_validation->set_value('deskripsi'),
    );

    $this->data['kat_id'] = array(
      'name'  => 'kat_id',
      'id'    => 'kat_id',
      'class' => 'form-control',
      'required'    => '',
    );

    $this->data['ambil_kategori'] = $this->Kategori_model->ambil_kategori();

    $this->load->view('back/event/event_add', $this->data);
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
      /* 4 adalah menyatakan tidak ada file yang diupload*/
      if ($_FILES['foto']['error'] <> 4)
      {
        $nmfile = strtolower(url_title($this->input->post('nama_event'))).date('YmdHis');

        /* memanggil library upload ci */
        $config['upload_path']      = './assets/images/event/';
        $config['allowed_types']    = 'jpg|jpeg|png|gif';
        $config['max_size']         = '2048'; // 2 MB
        $config['file_name']        = $nmfile; //nama yang terupload nantinya

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('foto'))
        {
          //file gagal diupload -> kembali ke form tambah
          $error = array('error' => $this->upload->display_errors());
          $this->session->set_flashdata('message', '<div class="alert alert-danger alert">'.$error['error'].'</div>');

          $this->create();
        }
          //file berhasil diupload -> lanjutkan ke query INSERT
          else
          {
            $foto = $this->upload->data();
            $thumbnail                = $config['file_name'];
            // library yang disediakan codeigniter
            $config['image_library']  = 'gd2';
            // gambar yang akan disimpan thumbnail
            $config['source_image']   = './assets/images/event/'.$foto['file_name'].'';
            // membuat thumbnail
            $config['create_thumb']   = TRUE;
            // rasio resolusi
            $config['maintain_ratio'] = FALSE;
            // lebar
            $config['width']          = 800;
            // tinggi
            $config['height']         = 400;

            $this->load->library('image_lib', $config);
            $this->image_lib->resize();

            $data = array(
              'nama_event'    => $this->input->post('nama_event'),
              'slug_event'     => strtolower(url_title($this->input->post('nama_event'))),
              'deskripsi'      => $this->input->post('deskripsi'),
              'kategori'      => $this->input->post('kat_id'),
              'foto'          => $nmfile,
              'foto_type'     => $foto['file_ext'],
              'created_by'    => $this->session->userdata('username')
            );

            // eksekusi query INSERT
            $this->Event_model->insert($data);
            // set pesan data berhasil disimpan
            $this->session->set_flashdata('message', '
            <div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
              <i class="ace-icon fa fa-bullhorn green"></i> Data berhasil disimpan
            </div>');
            redirect(site_url('admin/event'));
          }
      }
      else // Jika file upload kosong
      {
        $data = array(
          'nama_event'  => $this->input->post('nama_event'),
          'slug_event'     => strtolower(url_title($this->input->post('nama_event'))),
          'deskripsi'      => $this->input->post('deskripsi'),
          'kategori'      => $this->input->post('kat_id'),
          'created_by'      => $this->session->userdata('username')
        );

        // eksekusi query INSERT
        $this->Event_model->insert($data);
        // set pesan data berhasil disimpan
        $this->session->set_flashdata('message', '
        <div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
          <i class="ace-icon fa fa-bullhorn green"></i> Data berhasil disimpan
        </div>');
        redirect(site_url('admin/event'));
      }
    }
  }

  public function update($id)
  {
    $row = $this->Event_model->get_by_id($id);
    $this->data['event'] = $this->Event_model->get_by_id($id);

    if ($row)
    {
      $this->data['title']          = 'Ubah Data '.$this->data['module'];
      $this->data['action']         = site_url('admin/event/update_action');
      $this->data['button_submit']  = 'Simpan';
      $this->data['button_reset']   = 'Reset';

      $this->data['id_event'] = array(
        'name'  => 'id_event',
        'id'    => 'id_event',
        'type'  => 'hidden',
      );
      $this->data['nama_event'] = array(
        'name'  => 'nama_event',
        'id'    => 'nama_event',
        'class' => 'form-control',
      );
      $this->data['deskripsi'] = array(
        'name'  => 'deskripsi',
        'id'    => 'deskripsi',
        'class' => 'form-control',
      );
      $this->data['kat_id'] = array(
        'name'  => 'kat_id',
        'id'    => 'kat_id',
        'class' => 'form-control',
        'required'    => '',
      );

      $this->data['ambil_kategori'] = $this->Kategori_model->ambil_kategori();

      $this->load->view('back/event/event_edit', $this->data);
    }
      else
      {
        $this->session->set_flashdata('message', '
        <div class="alert alert-block alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
          <i class="ace-icon fa fa-bullhorn green"></i> Data tidak ditemukan
        </div>');
        redirect(site_url('admin/event'));
      }
  }

  public function update_action()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE)
    {
      $this->update($this->input->post('id_event'));
    }
      else
      {
        $nmfile = strtolower(url_title($this->input->post('nama_event'))).date('YmdHis');
        $id['id_event'] = $this->input->post('id_event');

        /* Jika file upload diisi */
        if ($_FILES['foto']['error'] <> 4)
        {
          $nmfile = strtolower(url_title($this->input->post('nama_event'))).date('YmdHis');

          //load uploading file library
          $config['upload_path']      = './assets/images/event/';
          $config['allowed_types']    = 'jpg|jpeg|png|gif';
          $config['max_size']         = '2048'; // 2 MB
          $config['file_name']        = $nmfile; //nama yang terupload nantinya

          $this->load->library('upload', $config);

          // Jika file gagal diupload -> kembali ke form update
          if (!$this->upload->do_upload('foto'))
          {
            //file gagal diupload -> kembali ke form update
            $error = array('error' => $this->upload->display_errors());
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert">'.$error['error'].'</div>');

            $this->update($this->input->post('id_event'));
          }
            // Jika file berhasil diupload -> lanjutkan ke query INSERT
            else
            {
              $delete = $this->Event_model->del_by_id($this->input->post('id_event'));

              $dir        = "assets/images/event/".$delete->foto.$delete->foto_type;
              $dir_thumb  = "assets/images/event/".$delete->foto.'_thumb'.$delete->foto_type;

              if(file_exists($dir))
              {
                // Hapus foto dan thumbnail
                unlink($dir);
                unlink($dir_thumb);
              }

              $foto = $this->upload->data();
              // library yang disediakan codeigniter
              $thumbnail                = $config['file_name'];
              //nama yang terupload nantinya
              $config['image_library']  = 'gd2';
              // gambar yang akan disimpan thumbnail
              $config['source_image']   = './assets/images/event/'.$foto['file_name'].'';
              // membuat thumbnail
              $config['create_thumb']   = TRUE;
              // rasio resolusi
              $config['maintain_ratio'] = FALSE;
              // lebar
              $config['width']          = 800;
              // tinggi
              $config['height']         = 400;

              $this->load->library('image_lib', $config);
              $this->image_lib->resize();

              $data = array(
                'nama_event'  => $this->input->post('nama_event'),
                'slug_event'   => strtolower(url_title($this->input->post('nama_event'))),
                'deskripsi'    => $this->input->post('deskripsi'),
                'kategori'    => $this->input->post('kat_id'),
                'foto'        => $nmfile,
                'foto_type'   => $foto['file_ext'],
                'modified_by' => $this->session->userdata('username')
              );

              $this->Event_model->update($this->input->post('id_event'), $data);
              $this->session->set_flashdata('message', '
              <div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
                <i class="ace-icon fa fa-bullhorn green"></i> Data berhasil disimpan
              </div>');
              redirect(site_url('admin/event'));
            }
        }
          // Jika file upload kosong
          else
          {
            $data = array(
              'nama_event'  => $this->input->post('nama_event'),
              'slug_event'   => strtolower(url_title($this->input->post('nama_event'))),
              'deskripsi'    => $this->input->post('deskripsi'),
              'kategori'    => $this->input->post('kat_id'),
              'modified_by' => $this->session->userdata('username')
            );

            $this->Event_model->update($this->input->post('id_event'), $data);
            $this->session->set_flashdata('message', '
            <div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
              <i class="ace-icon fa fa-bullhorn green"></i> Data berhasil disimpan
            </div>');
            redirect(site_url('admin/event'));
          }
      }
  }

  public function delete($id)
  {
    $delete = $this->Event_model->del_by_id($id);

    // menyimpan lokasi gambar dalam variable
    $dir = "assets/images/event/".$delete->foto.$delete->foto_type;
    $dir_thumb = "assets/images/event/".$delete->foto.'_thumb'.$delete->foto_type;

    // Hapus foto
    unlink($dir);
    unlink($dir_thumb);

    // Jika data ditemukan, maka hapus foto dan record nya
    if($delete)
    {
      $this->Event_model->delete($id);

      $this->session->set_flashdata('message', '
      <div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
        <i class="ace-icon fa fa-bullhorn green"></i> Data berhasil dihapus
      </div>');
      redirect(site_url('admin/event'));
    }
      // Jika data tidak ada
      else
      {
        $this->session->set_flashdata('message', '
        <div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
					<i class="ace-icon fa fa-bullhorn green"></i> Data tidak ditemukan
        </div>');
        redirect(site_url('admin/event'));
      }
  }

  public function _rules()
  {
    $this->form_validation->set_rules('nama_event', 'nama Event', 'trim|required');

    // set pesan form validasi error
    $this->form_validation->set_message('required', '{field} wajib diisi');

    $this->form_validation->set_rules('id_event', 'id_event', 'trim');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert">', '</div>');
  }

}
