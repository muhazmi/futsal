<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Transaksi extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->helper('tgl_indo');
    $this->load->model('Cart_model');
    $this->load->model('Company_model');

    $this->data['module']         = 'Transaksi';
    $this->data['button_submit']  = 'Simpan';
    $this->data['button_reset']   = 'Reset';

    $this->data['company_data'] 			= $this->Company_model->get_by_company();

    if(!$this->ion_auth->logged_in()){redirect('admin/auth/login', 'refresh');}
		elseif(!$this->ion_auth->is_superadmin() && !$this->ion_auth->is_admin()){redirect(base_url());}
  }

  public function index()
  {
    $this->data['title']    = 'Data '.$this->data['module'];
    $this->data['get_all']  = $this->Cart_model->get_all();

    $this->load->view('back/transaksi/transaksi_list',$this->data);
  }

  public function create()
  {
    $this->load->model('Jam_model');
    $this->load->model('Lapangan_model');
		$this->load->model('Transaksi_detail_model');

    $this->data['title'] = 'Tambah '.$this->data['module'].' Baru';
    $this->data['action'] = site_url('transaksi/create_action');

    $this->data['tanggal'] = array(
      'name'        => 'tanggal[]',
      'id'          => 'tanggal',
			'class' 			=> 'tanggal',
			'required'    => '',
			'autocomplete'    => 'off',
    );
		$this->data['jam_mulai'] = array(
      'name'        => 'jam_mulai[]',
      'id'          => 'jam_mulai',
      'class'       => 'jam_mulai',
      'required'    => '',
    );

    $this->data['get_all']  = $this->Lapangan_model->get_all();

    $this->load->view('back/transaksi/transaksi_add', $this->data);
  }

  public function create_action()
  {
    $this->load->helper('clean_helper');
    $this->_rules();

    if ($this->form_validation->run() == FALSE)
    {
      $this->create();
    }
      else
      {
        // mengambil 1 data terakhir dari tabel untuk pengecekan id_invoice
        $hasil_cek = $this->Transaksi_model->create_invoiceCode();

        // jika data tidak sama NULL atau tidak kosong atau datanya sudah ada di tabel maka buat id_invoice yang selanjutnya
        if($hasil_cek != NULL)
        {
          // mengganti string dengan fungsi substr dari hasil_cek data terakhir
          $kode_akhir = substr($hasil_cek->id_invoice,10,6);
          // membuat id_invoice
          $kode2      = str_pad($kode_akhir+1, 4, '0', STR_PAD_LEFT);
        }
          // jika datanya masih kosong maka buat id_invoice baru
          else{$kode2 = "0001";}

        // pembuatan tanggal
        $kode1  = date('ymd');
        /*$kode   = "J-".$kode1."-".$kode2;*/
        $kode   = "J-".$kode1."-".$kode2;

        $transaksi = array(
          'id_invoice'      => $kode,
          'customer'        => $this->input->post('customer'),
          'grand_total'     => clean($this->input->post('grand_total')),
          'bayar'           => clean($this->input->post('bayar')),
          'kembalian'       => clean($this->input->post('kembalian')),
          'catatan'         => $this->input->post('catatan'),
          'cabang'          => $this->session->userdata('cabang'),
          'company'         => $this->session->userdata('company'),
          'created_by'      => $this->session->userdata('username'),
          'created_date'    => date('Y-m-d'),
          'created_time'    => date("h:i:s")
        );
        $this->Transaksi_model->insert($transaksi);

        $this->db->select_max('id_transaksi');
        $result = $this->db->get('transaksi')->row_array();

        // menghitung total data yang dientry berdasarkan nama_barang
        $count = count($this->input->post('nama_barang'));

        // looping data yang diinput dan disimpan dalam variabel $data_detail[$i]
        for($i=0;$i<$count;$i++)
        {
          $data_detail[$i] = array(
            'transaksi_id'    => $result['id_transaksi'],
            'barang_id'       => $this->input->post('nama_barang['.$i.']'),
            'qty'             => $this->input->post('qty['.$i.']'),
            'ket'             => $this->input->post('ket['.$i.']'),
            'harga_jual'      => $this->input->post('harga_jual['.$i.']'),
            'total'           => $this->input->post('total['.$i.']'),
            'created_by'      => $this->session->userdata('username'),
            'created_date'    => date('Y-m-d'),
            'created_time'    => date("h:i:s")
          );
        }
        // eksekusi query INSERT dan UPDATE + looping
        foreach($data_detail as $transaksi_detail)
        {
          $this->Transaksi_model->insert2($transaksi_detail);
        }

        // set pesan data berhasil dibuat
        $this->session->set_flashdata('message', '<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Data berhasil dibuat</div>');
        redirect(site_url('transaksi'));
      }
  }

  public function detail($id)
  {
    $this->load->model('Bank_model');

    $invoice = $this->uri->segment(4);
    $row      = $this->Cart_model->get_by_id($invoice);

    if($row)
    {
      $this->data['title']              = 'Detail '.$this->data['module'];

      $this->data['cart_finished']	    			= $this->Cart_model->get_cart_per_customer_finished_back($invoice)->result();
  		$this->data['cart_finished_row']   			= $this->Cart_model->get_cart_per_customer_finished_back($invoice)->row();
  		$this->data['data_bank'] 								= $this->Bank_model->get_all();

      $this->load->view('back/transaksi/transaksi_detail', $this->data);
    }
      else
      {
        $this->session->set_flashdata('message', '<div class="alert alert-warning alert">Data tidak ditemukan</div>');
        redirect(site_url('admin/transaksi'));
      }
  }

  public function print($id)
  {
    $row = $this->Transaksi_model->get_by_id($id);

    if(!$row)
    {
      $this->session->set_flashdata('message', "<script>alert('Data tidak ditemukan');</script>");
      redirect(site_url('transaksi'));
    }
    elseif($this->ion_auth->is_superadmin() && $this->session->userdata('company') != $row->company)
    {
      $this->session->set_flashdata('message', "<script>alert('Data tidak ditemukan');</script>");
      redirect(site_url('transaksi'));
    }
    elseif($this->ion_auth->is_admin() && $this->session->userdata('cabang') != $row->cabang)
    {
      $this->session->set_flashdata('message', "<script>alert('Data tidak ditemukan');</script>");
      redirect(site_url('transaksi'));
    }
      else
      {
        if($this->ion_auth->is_superadmin())
        {
          $data['cek_data']   = $this->Transaksi_model->get_by_id_print_superadmin($id);
          $data['transaksi_detail'] = $this->Transaksi_model->get_by_id_print_detail_superadmin($id);
        }
        elseif($this->ion_auth->is_admin())
        {
          $data['cek_data']   = $this->Transaksi_model->get_by_id_print_admin($id);
          $data['transaksi_detail'] = $this->Transaksi_model->get_by_id_print_detail_admin($id);
        }

        if($data['cek_data'])
        {
          $this->load->view('back/transaksi/print', $data);
        }
          else
          {
            $this->session->set_flashdata('message', "<script>alert('Data tidak ditemukan');</script>");
            redirect(site_url('transaksi'));
          }
      }
  }

  public function set_lunas($id)
  {
    $row = $this->Cart_model->get_by_id($id);

    if ($row)
    {
      $this->db->where('id_trans', $id);
  		$this->db->update('transaksi', array(
  			'status'			=>	'2',
  		));

      $this->session->set_flashdata('message', '<div class="alert alert-success alert">Transaksi berhasil dinyatakan LUNAS</div>');
      redirect(site_url('admin/transaksi'));
    }
      // Jika data tidak ada
      else
      {
        $this->session->set_flashdata('message', '<div class="alert alert-warning alert">Transaksi tidak ditemukan</div>');
        redirect(site_url('admin/transaksi'));
      }
  }

  public function getJamMulai(){
		$tanggal = $this->input->post('tanggal');
		$lapangan_id = $this->input->post('lapangan_id');

		if($tanggal === FALSE || $lapangan_id === FALSE){
			echo json_encode(array());
			die();
		}

		$list_jam_mulai_terpakai = $this->Transaksi_detail_model->get_jam_mulai_terpakai($tanggal,$lapangan_id);

		$list_jam_mulai_terpakai_arr = array();
		foreach($list_jam_mulai_terpakai as $a_jam){

			if(intval($a_jam->durasi) > 1){
				$list_jam_range = $this->Jam_model->get_jam_range($a_jam->jam_mulai, $a_jam->jam_selesai);
				foreach($list_jam_range as $a_jam_from_range){
					if( !in_array($a_jam_from_range->jam, $list_jam_mulai_terpakai_arr) )
						array_push($list_jam_mulai_terpakai_arr, $a_jam_from_range->jam);
				}
			}else{
				if( !in_array($a_jam->jam_mulai, $list_jam_mulai_terpakai_arr) )
					array_push($list_jam_mulai_terpakai_arr, $a_jam->jam_mulai);
			}
		}

		$list_jam = $this->Jam_model->get();

		$list_jam_arr = array();
		foreach($list_jam as $a_jam){
			array_push($list_jam_arr, $a_jam->jam);
		}

		$result = array();

		foreach($list_jam_arr as $a_jam){
			if( !in_array($a_jam, $list_jam_mulai_terpakai_arr) ){
				$a_jam_row = new stdClass();
				$a_jam_row->durasi = '1';
				$a_jam_row->jam_mulai = $a_jam;

				array_push($result, $a_jam_row);
			}
		}

		echo json_encode($result);
	}

  // public function print($id)
  // {
  //   $this->load->helper('tgl_indo');
  //   $this->load->model('Company_model');
  //
  //   if($data['cek_data'])
  //   {
  //     Include the main TCPDF library (search for installation path).
  //     require_once('assets/plugins/tcpdf/tcpdf.php');
  //
  //     // create new PDF document
  //     $pdf = new TCPDF('P', 'mm', array('58','48'), true, 'UTF-8', false);
  //     // $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  //
  //     // set document information
  //     $pdf->SetCreator('AzmiColeJr');
  //     $pdf->SetAuthor('AzmiColeJr');
  //     $pdf->SetTitle('Print '.$row->id_invoice);
  //
  //     // Set font
  //     // dejavusans is a UTF-8 Unicode font, if you only need to
  //     // print standard ASCII chars, you can use core fonts like
  //     // helvetica or times to reduce file size.
  //     $pdf->SetFont('helvetica', '', 5, '', true);
  //
  //     // remove default header/footer
  //     $pdf->setPrintHeader(false);
  //     $pdf->setPrintFooter(false);
  //
  //     // set margins
  //     $pdf->SetMargins('10', '10', '100');
  //
  //     // set auto page breaks
  //     $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  //
  //     // set image scale factor
  //     $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  //
  //     // set some language-dependent strings (optional)
  //     if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
  //         require_once(dirname(__FILE__).'/lang/eng.php');
  //         $pdf->setLanguageArray($l);
  //     }
  //
  //     // Add a page
  //     // This method has several options, check the source code documentation for more information.
  //     $pdf->AddPage();
  //
  //     // set text shadow effect
  //     $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
  //
  //     // Set some content to print
  //     $html = $this->load->view('back/transaksi/print', $data, true);;
  //
  //     // Print text using writeHTMLCell()
  //     $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
  //
  //     // ---------------------------------------------------------
  //
  //     // Close and output PDF document
  //     // This method has several options, check the source code documentation for more information.
  //     $pdf->Output('Print '.$row->id_invoice.'.pdf', 'I');
  //   }
  //     else
  //     {
  //       $this->session->set_flashdata('message', "<script>alert('Data tidak ditemukan');</script>");
  //       redirect(site_url('transaksi'));
  //     }
  // }

  public function update_diskon($id)
  {
    $this->db->select('id, harga');
    $this->db->where('id', '1');
    $row = $this->db->get('diskon')->row();

    $this->data['diskon'] = $row;

    if ($row)
    {
      $this->data['title']          = 'Ubah Data Diskon Member';
      $this->data['action']         = site_url('admin/transaksi/update_diskon_action');
      $this->data['button_submit']  = 'Simpan';
      $this->data['button_reset']   = 'Reset';

      $this->data['id'] = array(
        'name'  => 'id',
        'id'    => 'id',
        'type'  => 'hidden',
      );

      $this->data['harga'] = array(
        'name'  => 'harga',
        'id'    => 'harga',
        'type'  => 'number',
        'class' => 'form-control',
      );

      $this->load->view('back/transaksi/update_diskon', $this->data);
    }
      else
      {
        $this->session->set_flashdata('message', '<div class="alert alert-warning alert">Data tidak ditemukan</div>');
        redirect(site_url('admin/transaksi/update_diskon/1'));
      }
  }

  public function update_diskon_action()
  {
    $this->form_validation->set_rules('harga', 'Diskon Member', 'required');

    if ($this->form_validation->run() == FALSE)
    {
      $this->update($this->input->post('id'));
    }
      else
      {
        $data = array(
          'harga'   => $this->input->post('harga'),
        );

        $this->db->where('id',$this->input->post('id'));
        $this->db->update('diskon', $data);

        $this->session->set_flashdata('message', '<div class="alert alert-success alert">Edit Data Berhasil</div>');
        redirect(site_url('admin/transaksi/update_diskon/1'));
      }
  }

  public function _rules()
  {
    $this->form_validation->set_rules('nama_barang[]', 'Nama Barang', 'required');
    $this->form_validation->set_rules('bayar', 'Bayar', 'required');
    $this->form_validation->set_rules('kembalian', 'Kembalian', 'required');
    // set pesan form validasi error
    $this->form_validation->set_message('required', '{field} wajib diisi');

    $this->form_validation->set_rules('id_transaksi', 'id_transaksi', 'trim');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert">', '</div>');
  }

}
