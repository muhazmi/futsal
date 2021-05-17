<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Album extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->model('Album_model');

    $this->data['module'] = 'Album';

    if(!$this->ion_auth->logged_in()){redirect('admin/auth/login', 'refresh');}
    elseif(!$this->ion_auth->is_superadmin() && !$this->ion_auth->is_admin()){redirect(base_url());}
  }

  public function index()
  {
    $this->data['title']    = 'Data '.$this->data['module'];
    $this->data['get_all']  = $this->Album_model->get_all();

    $this->load->view('back/album/album_list', $this->data);
  }

  public function create()
  {
    $this->data['title']          = 'Tambah '.$this->data['module'].' Baru';
    $this->data['action']         = site_url('admin/'.strtolower($this->data['module']).'/create_action');
    $this->data['button_submit']  = 'Simpan';
    $this->data['button_reset']   = 'Reset';

    $this->data['nama_album'] = array(
      'name'  => 'nama_album',
      'id'    => 'nama_album',
      'class' => 'form-control',
      'value' => $this->form_validation->set_value('nama_album'),
    );

    $this->load->view('back/album/album_add', $this->data);
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
        $nmfile = strtolower(url_title($this->input->post('nama_album'))).date('YmdHis');

        /* memanggil library upload ci */
        $config['upload_path']      = './assets/images/album/';
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
            $config['source_image']   = './assets/images/album/'.$foto['file_name'].'';
            // rasio resolusi
            $config['maintain_ratio'] = FALSE;
            // lebar
            $config['width']          = 1280;
            // tinggi
            $config['height']         = 720;

            $this->load->library('image_lib', $config);
            $this->image_lib->resize();

            $data = array(
              'nama_album'    => $this->input->post('nama_album'),
              'slug_album'     => strtolower(url_title($this->input->post('nama_album'))),
              'foto'            => $nmfile.$foto['file_ext'],
              'created_by'      => $this->session->userdata('username')
            );

            // eksekusi query INSERT
            $this->Album_model->insert($data);
            // set pesan data berhasil disimpan
            $this->session->set_flashdata('message', '
            <div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
              <i class="ace-icon fa fa-bullhorn green"></i> Data berhasil disimpan
            </div>');
            redirect(site_url('admin/album'));
          }
      }
      else // Jika file upload kosong
      {
        $data = array(
          'nama_album'  => $this->input->post('nama_album'),
          'slug_album'     => strtolower(url_title($this->input->post('nama_album'))),
          'deskripsi'      => $this->input->post('deskripsi'),
          'created_by'      => $this->session->userdata('username')
        );

        // eksekusi query INSERT
        $this->Album_model->insert($data);
        // set pesan data berhasil disimpan
        $this->session->set_flashdata('message', '
        <div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
          <i class="ace-icon fa fa-bullhorn green"></i> Data berhasil disimpan
        </div>');
        redirect(site_url('admin/album'));
      }
    }
  }

  public function update($id)
  {
    $row = $this->Album_model->get_by_id($id);
    $this->data['album'] = $this->Album_model->get_by_id($id);

    if ($row)
    {
      $this->data['title']          = 'Ubah Data '.$this->data['module'];
      $this->data['action']         = site_url('admin/album/update_action');
      $this->data['button_submit']  = 'Simpan';
      $this->data['button_reset']   = 'Reset';

      $this->data['id_album'] = array(
        'name'  => 'id_album',
        'id'    => 'id_album',
        'type'  => 'hidden',
      );
      $this->data['nama_album'] = array(
        'name'  => 'nama_album',
        'id'    => 'nama_album',
        'class' => 'form-control',
      );

      $this->load->view('back/album/album_edit', $this->data);
    }
      else
      {
        $this->session->set_flashdata('message', '
        <div class="alert alert-block alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
          <i class="ace-icon fa fa-bullhorn green"></i> Data tidak ditemukan
        </div>');
        redirect(site_url('admin/album'));
      }
  }

  public function update_action()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE)
    {
      $this->update($this->input->post('id_album'));
    }
      else
      {
        $nmfile = strtolower(url_title($this->input->post('nama_album'))).date('YmdHis');
        $id['id_album'] = $this->input->post('id_album');

        /* Jika file upload diisi */
        if ($_FILES['foto']['error'] <> 4)
        {
          $nmfile = strtolower(url_title($this->input->post('nama_album'))).date('YmdHis');

          //load uploading file library
          $config['upload_path']      = './assets/images/album/';
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

            $this->update($this->input->post('id_album'));
          }
            // Jika file berhasil diupload -> lanjutkan ke query INSERT
            else
            {
              $delete = $this->Album_model->del_by_id($this->input->post('id_album'));

              $dir        = "assets/images/album/".$delete->foto;

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
              $config['source_image']   = './assets/images/album/'.$foto['file_name'].'';
              // rasio resolusi
              $config['maintain_ratio'] = FALSE;
              // lebar
              $config['width']          = 1280;
              // tinggi
              $config['height']         = 720;

              $this->load->library('image_lib', $config);
              $this->image_lib->resize();

              $data = array(
                'nama_album'  => $this->input->post('nama_album'),
                'slug_album'   => strtolower(url_title($this->input->post('nama_album'))),
                'foto'        => $nmfile.$foto['file_ext'],
                'modified_by' => $this->session->userdata('username')
              );

              $this->Album_model->update($this->input->post('id_album'), $data);
              $this->session->set_flashdata('message', '
              <div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
                <i class="ace-icon fa fa-bullhorn green"></i> Data berhasil disimpan
              </div>');
              redirect(site_url('admin/album'));
            }
        }
          // Jika file upload kosong
          else
          {
            $data = array(
              'nama_album'  => $this->input->post('nama_album'),
              'slug_album'   => strtolower(url_title($this->input->post('nama_album'))),
              'modified_by' => $this->session->userdata('username')
            );

            $this->Album_model->update($this->input->post('id_album'), $data);
            $this->session->set_flashdata('message', '
            <div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
              <i class="ace-icon fa fa-bullhorn green"></i> Data berhasil disimpan
            </div>');
            redirect(site_url('admin/album'));
          }
      }
  }

  public function delete($id)
  {
    $delete = $this->Album_model->del_by_id($id);

    // menyimpan lokasi gambar dalam variable
    $dir = "assets/images/album/".$delete->foto;

    // Hapus foto
    unlink($dir);

    // Jika data ditemukan, maka hapus foto dan record nya
    if($delete)
    {
      $this->Album_model->delete($id);

      $this->session->set_flashdata('message', '
      <div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
        <i class="ace-icon fa fa-bullhorn green"></i> Data berhasil dihapus
      </div>');
      redirect(site_url('admin/album'));
    }
      // Jika data tidak ada
      else
      {
        $this->session->set_flashdata('message', '
        <div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
					<i class="ace-icon fa fa-bullhorn green"></i> Data tidak ditemukan
        </div>');
        redirect(site_url('admin/album'));
      }
  }

  public function _rules()
  {
    $this->form_validation->set_rules('nama_album', 'nama Album', 'trim|required');

    // set pesan form validasi error
    $this->form_validation->set_message('required', '{field} wajib diisi');

    $this->form_validation->set_rules('id_album', 'id_album', 'trim');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert">', '</div>');
  }

}
