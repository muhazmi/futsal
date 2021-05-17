<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Foto extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->model('Foto_model');

    $this->data['module'] = 'Foto';

    if (!$this->ion_auth->logged_in()){redirect('admin/auth/login', 'refresh');}
    // elseif($this->ion_auth->is_user()){redirect('admin/auth/login', 'refresh');}
  }

  public function index()
  {
    $this->data['title'] = 'Data '.$this->data['module'];
    $this->data['get_all']  = $this->Foto_model->get_all();

    $this->load->view('back/foto/foto_list', $this->data);
  }

  public function create()
  {
    $this->load->model('Album_model');

    $this->data['title']          = 'Tambah Data '.$this->data['module'];
    $this->data['action']         = site_url('admin/'.strtolower($this->data['module']).'/create_action');
    $this->data['button_submit']  = 'Simpan';
    $this->data['button_reset']   = 'Reset';

    $this->data['nama_foto'] = array(
      'name'  => 'nama_foto',
      'id'    => 'nama_foto',
      'class' => 'form-control',
      'value' => $this->form_validation->set_value('nama_foto'),
    );

    $this->data['album_id'] = array(
      'name'  => 'album_id',
      'id'    => 'album_id',
      'class' => 'form-control',
      'required'    => '',
    );

    $this->data['ambil_album'] = $this->Album_model->ambil_album();

    $this->load->view('back/foto/foto_add', $this->data);
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
        $nmfile = strtolower(url_title($this->input->post('nama_foto'))).date('YmdHis');

        /* memanggil library upload ci */
        $config['upload_path']      = './assets/images/foto/';
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
            $config['source_image']   = './assets/images/foto/'.$foto['file_name'].'';
            // rasio resolusi
            $config['maintain_ratio'] = FALSE;
            // lebar
            $config['width']          = 1280;
            // tinggi
            $config['height']         = 720;

            $this->load->library('image_lib', $config);
            $this->image_lib->resize();

            $data = array(
              'nama_foto'    => $this->input->post('nama_foto'),
              'slug_foto'     => strtolower(url_title($this->input->post('nama_foto'))),
              'album_id'      => $this->input->post('album_id'),
              'foto'            => $nmfile.$foto['file_ext'],
              'created_by'      => $this->session->userdata('username')
            );

            // eksekusi query INSERT
            $this->Foto_model->insert($data);
            // set pesan data berhasil disimpan
            $this->session->set_flashdata('message', '
            <div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
              <i class="ace-icon fa fa-bullhorn green"></i> Data berhasil disimpan
            </div>');
            redirect(site_url('admin/foto'));
          }
      }
      else // Jika file upload kosong
      {
        $data = array(
          'nama_foto'  => $this->input->post('nama_foto'),
          'slug_foto'     => strtolower(url_title($this->input->post('nama_foto'))),
          'id_album'      => $this->input->post('id_album'),
          'deskripsi'      => $this->input->post('deskripsi'),
          'created_by'      => $this->session->userdata('username')
        );

        // eksekusi query INSERT
        $this->Foto_model->insert($data);
        // set pesan data berhasil disimpan
        $this->session->set_flashdata('message', '
        <div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
          <i class="ace-icon fa fa-bullhorn green"></i> Data berhasil disimpan
        </div>');
        redirect(site_url('admin/foto'));
      }
    }
  }

  public function update($id)
  {
    $this->load->model('Album_model');

    $this->data['foto']           = $this->Foto_model->get_by_id($id);
    $this->data['ambil_album'] = $this->Album_model->ambil_album();

    if($this->data['foto'])
    {
      $this->data['title']          = 'Ubah Data '.$this->data['module'];
      $this->data['action']         = site_url('admin/foto/update_action');
      $this->data['button_submit']  = 'Simpan';
      $this->data['button_reset']   = 'Reset';

      $this->data['id_foto'] = array(
        'name'  => 'id_foto',
        'id'    => 'id_foto',
        'type'  => 'hidden',
      );
      $this->data['nama_foto'] = array(
        'name'  => 'nama_foto',
        'id'    => 'nama_foto',
        'class' => 'form-control',
      );
      $this->data['album_id'] = array(
        'name'  => 'album_id',
        'id'    => 'album_id',
        'class' => 'form-control',
      );

      $this->load->view('back/foto/foto_edit', $this->data);
    }
      else
      {
        $this->session->set_flashdata('message', '
        <div class="alert alert-block alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
          <i class="ace-icon fa fa-bullhorn green"></i> Data tidak ditemukan
        </div>');
        redirect(site_url('admin/foto'));
      }
  }

  public function update_action()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE)
    {
      $this->update($this->input->post('id_foto'));
    }
      else
      {
        $nmfile = strtolower(url_title($this->input->post('nama_foto'))).date('YmdHis');
        $id['id_foto'] = $this->input->post('id_foto');

        /* Jika file upload diisi */
        if ($_FILES['foto']['error'] <> 4)
        {
          $nmfile = strtolower(url_title($this->input->post('nama_foto'))).date('YmdHis');

          //load uploading file library
          $config['upload_path']      = './assets/images/foto/';
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

            $this->update($this->input->post('id_foto'));
          }
            // Jika file berhasil diupload -> lanjutkan ke query INSERT
            else
            {
              $delete = $this->Foto_model->del_by_id($this->input->post('id_foto'));

              $dir        = "assets/images/foto/".$delete->foto;

              if(file_exists($dir))
              {
                // Hapus foto dan thumbnail
                unlink($dir);
              }

              $foto = $this->upload->data();
              // library yang disediakan codeigniter
              $thumbnail                = $config['file_name'];
              //nama yang terupload nantinya
              $config['image_library']  = 'gd2';
              // gambar yang akan disimpan thumbnail
              $config['source_image']   = './assets/images/foto/'.$foto['file_name'].'';
              // rasio resolusi
              $config['maintain_ratio'] = FALSE;
              // lebar
              $config['width']          = 1280;
              // tinggi
              $config['height']         = 720;

              $this->load->library('image_lib', $config);
              $this->image_lib->resize();

              $data = array(
                'nama_foto'  => $this->input->post('nama_foto'),
                'slug_foto'   => strtolower(url_title($this->input->post('nama_foto'))),
                'album_id'  => $this->input->post('album_id'),
                'foto'        => $nmfile.$foto['file_ext'],
                'modified_by' => $this->session->userdata('username')
              );

              $this->Foto_model->update($this->input->post('id_foto'), $data);
              $this->session->set_flashdata('message', '
              <div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
                <i class="ace-icon fa fa-bullhorn green"></i> Data berhasil disimpan
              </div>');
              redirect(site_url('admin/foto'));
            }
        }
          // Jika file upload kosong
          else
          {
            $data = array(
              'nama_foto'  => $this->input->post('nama_foto'),
              'slug_foto'   => strtolower(url_title($this->input->post('nama_foto'))),
              'album_id'  => $this->input->post('album_id'),
              'modified_by' => $this->session->userdata('username')
            );

            $this->Foto_model->update($this->input->post('id_foto'), $data);
            $this->session->set_flashdata('message', '
            <div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
              <i class="ace-icon fa fa-bullhorn green"></i> Data berhasil disimpan
            </div>');
            redirect(site_url('admin/foto'));
          }
      }
  }

  public function delete($id)
  {
    $delete = $this->Foto_model->del_by_id($id);

    // menyimpan lokasi gambar dalam variable
    $dir = "assets/images/foto/".$delete->foto.$delete->foto_type;

    // Hapus foto
    unlink($dir);

    // Jika data ditemukan, maka hapus foto dan record nya
    if($delete)
    {
      $this->Foto_model->delete($id);

      $this->session->set_flashdata('message', '
      <div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
        <i class="ace-icon fa fa-bullhorn green"></i> Data berhasil dihapus
      </div>');
      redirect(site_url('admin/foto'));
    }
      // Jika data tidak ada
      else
      {
        $this->session->set_flashdata('message', '
        <div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
					<i class="ace-icon fa fa-bullhorn green"></i> Data tidak ditemukan
        </div>');
        redirect(site_url('admin/foto'));
      }
  }

  public function _rules()
  {
    $this->form_validation->set_rules('nama_foto', 'nama Foto', 'trim|required');

    // set pesan form validasi error
    $this->form_validation->set_message('required', '{field} wajib diisi');

    $this->form_validation->set_rules('id_foto', 'id_foto', 'trim');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert">', '</div>');
  }

}
