<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends CI_Controller {

	function __construct()
  {
    parent::__construct();
    /* memanggil model untuk ditampilkan pada masing2 modul */
    $this->load->model('Client_model');
    $this->load->model('Company_model');
		$this->load->model('Featured_model');
		$this->load->model('Kategori_model');
		$this->load->model('Kontak_model');
    $this->load->model('Produk_model');

    /* memanggil function dari masing2 model yang akan digunakan */
    $this->data['client_data'] 				= $this->Client_model->get_all_sidebar();
    $this->data['company_data'] 			= $this->Company_model->get_by_company();
    $this->data['featured_data'] 			= $this->Featured_model->get_all_front();
    $this->data['kategori_data'] 			= $this->Kategori_model->get_all();
		$this->data['kontak'] 						= $this->Kontak_model->get_all();
  }

	public function index()
	{
		/* menyiapkan data yang akan disertakan/ ditampilkan pada view */
    $this->data['page'] = "Arsip Client";

    /* memanggil library pagination (membuat halaman) */
    $this->load->library('pagination');

    /* menghitung jumlah total data */
    $jumlah = $this->Client_model->total_rows();

    // Mengatur base_url
    $config['base_url'] = base_url().'client/arsip/halaman/';
    //menghitung total baris
    $config['total_rows'] = $jumlah;
    //mengatur total data yang tampil per halamannya
    $config['per_page'] = 7;
    // tag pagination bootstrap

		$config['full_tag_open']    = "<ul class='pagination'>";
    $config['full_tag_close']   = "</ul>";
    $config['num_tag_open']     = "<li>";
    $config['num_tag_close']    = "</li>";
    $config['cur_tag_open']     = "<li class='disabled'><li class='active'><a href='#'>";
    $config['cur_tag_close']    = "<span class='sr-only'></span></a></li>";
    $config['next_link']        = "Selanjutnya";
    $config['next_tag_open']    = "<li>";
    $config['next_tagl_close']  = "</li>";
    $config['prev_link']        = "Sebelumnya";
    $config['prev_tag_open']    = "<li>";
    $config['prev_tagl_close']  = "</li>";
    $config['first_link']       = "Awal";
    $config['first_tag_open']   = "<li>";
    $config['first_tagl_close'] = "</li>";
    $config['last_link']        = 'Terakhir';
    $config['last_tag_open']    = "<li>";
    $config['last_tagl_close']  = "</li>";

    // mengambil uri segment ke-4
    $dari = $this->uri->segment('4');

    /* eksekusi library pagination ke model penampilan data */
    $this->data['arsip'] = $this->Client_model->get_all_arsip($config['per_page'],$dari);
    $this->pagination->initialize($config);

    /* memanggil view yang telah disiapkan dan passing data dari model ke view*/
    $this->load->view('front/client/arsip', $this->data);
	}

	public function read($id)
	{
    /* mengambil data berdasarkan id */
		$row = $this->Client_model->get_by_id_front($id);

    /* melakukan pengecekan data, apabila ada maka akan ditampilkan */
		if ($row)
    {
      /* memanggil function dari masing2 model yang akan digunakan */
    	$this->data['client']            = $this->Client_model->get_by_id_front($id);
      $this->data['client_lainnya']    = $this->Client_model->get_all_random();

      $this->data['page'] = $row->judul_client;

      /* memanggil view yang telah disiapkan dan passing data dari model ke view*/
			$this->load->view('front/client/body', $this->data);
		}
		else
    {
			$this->session->set_flashdata('message', '<div class="alert alert-dismissible alert-danger">
        <button type="button" class="close" data-dismiss="alert">&times;</button>Client tidak ditemukan</b></div>');
      redirect(base_url());
    }
	}

  public function buat_captcha()
  {
    /* memanggil helper captcha dan string */
    $this->load->helper('captcha');

    /* menyiapkan data variabel vals melalui array untuk proses pembuatan captcha */
    $vals = array(
      /* lokasi gambar captcha, ex: captcha */
      'img_path'      => './captcha/',
      /* alamat gambar captcha, ex: www.abcd.com/captcha */
      'img_url'       => base_url().'captcha/',
      /* tinggi gambar */
      'img_height'    => 30,
      /* waktu berlaku captcha disimpan pada folder aplikasi (100 = dalam detik) */
      'expiration'    => 100,
      /* jumlah huruf/ karakter yang ditampilkan */
      'word_length'   => 5,
      // pengaturan warna dan background captcha
      'colors'        => array(
                          'background' => array(255, 255, 255),
                          'border' => array(0, 0, 0),
                          'text' => array(0, 0, 0),
                          'grid' => array(255, 240, 0)
                        )
    );

    $cap = create_captcha($vals);
    return $cap;
  }

  public function komen($id)
  {
    /* set aturan form validasi pada form */
    $this->form_validation->set_rules('kode_captcha', 'Kode Captcha', 'required|callback_cek_captcha');

    /* memanggil function dari masing2 model yang akan digunakan */
    $this->data['client']                   = $this->Client_model->get_by_id($id);
    $this->data['get_komentar']             = $this->Client_model->get_komentar($id);
    $this->data['get_all_random']           = $this->Client_model->get_all_random();

    /* pengecekan form_validation */
    if ($this->form_validation->run() === FALSE)
    {
      /* buat captcha */
      $cap = $this->buat_captcha();
      $this->data['cap_img'] = $cap['image'];
      $this->session->set_userdata('kode_captcha', $cap['word']);

      $this->load->view('front/client/body', $this->data);
    }
      else
      {
        /* menyiapkan/ menyimpan data ke dalam array */
        $data = array(
          'id_client'     => $this->input->post('id_client'),
          'isi_komentar'  => $this->input->post('isi_komentar'),
          'nama'          => $this->session->userdata('identity'),
          'status'        => 'tidak'
        );

        /* proses insert ke database melalui function yang ada pada model */
        $this->Client_model->insert_komentar($data);

        /* menghapus session captcha */
        $this->session->unset_userdata('kode_captcha');

        /* membuat notifikasi pada halaman yang dituju */
        $this->session->set_flashdata('message', '<div class="alert alert-dismissible alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>Komentar berhasil terkirim dan akan diverifikasi Admin terlebih dahulu</b></div>');

        redirect(base_url());
      }
  }

  public function cek_captcha($input)
  {
    /* pengecekan hasil input captcha */
    if($input === $this->session->userdata('kode_captcha'))
    {
      return TRUE;
    }
    else
    {
      $this->form_validation->set_message('cek_captcha', '%s yang anda input salah!');
      $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert">', '</div>');
      return FALSE;
    }
  }

	public function cari_client()
  {
    /* menyiapkan data yang akan disertakan/ ditampilkan pada view */
  	$this->data['page'] = 'Hasil Pencarian Anda';

  	$this->data['title'] = 'Portal Client CI';

    /* memanggil function dari model yang akan digunakan */
    $this->data['hasil_pencarian'] = $this->Client_model->get_cari_client();

    /* memanggil view yang telah disiapkan dan passing data dari model ke view*/
    $this->load->view('front/home/hasil_pencarian', $this->data);
  }

  public function kategori($id)
  {
    /* mengambil uri segment ke-3 dan mengubah huruf awal menjadi kapital/ cetak */
  	$kat = ucfirst($this->uri->segment(3));

    /* menyiapkan data yang akan disertakan/ ditampilkan pada view */
    $this->data['page']        = "Kategori: $kat";

    $this->data['title']        = "Portal Client CI";

    /* memanggil function dari model yang akan digunakan */
    $this->data['kategori']     = $this->Kategori_model->get_by_id_front($id);

    /* memanggil view yang telah disiapkan dan passing data dari model ke view*/
    $this->load->view('front/kategori/body', $this->data);
  }

  public function arsip()
  {
    /* menyiapkan data yang akan disertakan/ ditampilkan pada view */
    $this->data['page'] = "Arsip Client";

    $this->data['title'] = "Portal Client CI";

    /* memanggil library pagination (membuat halaman) */
    $this->load->library('pagination');

    /* menghitung jumlah total data */
    $jumlah = $this->Client_model->total_rows();

    // Mengatur base_url
    $config['base_url'] = base_url().'client/arsip/halaman/';
    //menghitung total baris
    $config['total_rows'] = $jumlah;
    //mengatur total data yang tampil per halamannya
    $config['per_page'] = 6;
    // tag pagination bootstrap
    $config['full_tag_open']    = "<ul class='pagination'>";
    $config['full_tag_close']   = "</ul>";
    $config['num_tag_open']     = "<li>";
    $config['num_tag_close']    = "</li>";
    $config['cur_tag_open']     = "<li class='disabled'><li class='active'><a href='#'>";
    $config['cur_tag_close']    = "<span class='sr-only'></span></a></li>";
    $config['next_link']        = "Selanjutnya";
    $config['next_tag_open']    = "<li>";
    $config['next_tagl_close']  = "</li>";
    $config['prev_link']        = "Sebelumnya";
    $config['prev_tag_open']    = "<li>";
    $config['prev_tagl_close']  = "</li>";
    $config['first_link']       = "Awal";
    $config['first_tag_open']   = "<li>";
    $config['first_tagl_close'] = "</li>";
    $config['last_link']        = 'Terakhir';
    $config['last_tag_open']    = "<li>";
    $config['last_tagl_close']  = "</li>";

    // mengambil uri segment ke-4
    $dari = $this->uri->segment('4');

    /* eksekusi library pagination ke model penampilan data */
    $this->data['arsip'] = $this->Client_model->get_all_arsip($config['per_page'],$dari);
    $this->pagination->initialize($config);

    /* memanggil view yang telah disiapkan dan passing data dari model ke view*/
    $this->load->view('front/client/arsip', $this->data);
  }

}
