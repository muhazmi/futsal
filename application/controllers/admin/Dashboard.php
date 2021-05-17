<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function index(){
		$this->load->model('Album_model');
		$this->load->model('Cart_model');
		$this->load->model('Event_model');
		$this->load->model('Foto_model');
		$this->load->model('Ion_auth_model');
		$this->load->model('Kategori_model');
		$this->load->model('Kontak_model');
		$this->load->model('Lapangan_model');

		$this->load->model('Slider_model');

		if(!$this->ion_auth->logged_in()){redirect('admin/auth/login', 'refresh');}
		elseif(!$this->ion_auth->is_superadmin() && !$this->ion_auth->is_admin()){redirect(base_url());}
		else
		{
			$this->data = array(
	      'title' 						=> 'Dashboard',
		    'total_album' 			=> $this->Album_model->total_rows(),
				'total_event' 			=> $this->Event_model->total_rows(),
				'total_foto' 				=> $this->Foto_model->total_rows(),
				'total_kategori' 		=> $this->Kategori_model->total_rows(),
				'total_kontak' 			=> $this->Kontak_model->total_rows(),
				'total_lapangan'		=> $this->Lapangan_model->total_rows(),
				'total_slider' 			=> $this->Slider_model->total_rows(),
				'total_customer' 		=> $this->Ion_auth_model->total_rows_customer(),

				'omset_harian' 				=> $this->Cart_model->get_omset_harian(),
				'omset_bulanan' 			=> $this->Cart_model->get_omset_bulanan(),
				'omset_tahunan' 			=> $this->Cart_model->get_omset_tahunan(),
				'total_omset' 				=> $this->Cart_model->get_omset_total(),
				'stats_omset_bulanan' => $this->Cart_model->stats_omset_bulanan(),
				'stats_omset_tahunan' => $this->Cart_model->stats_omset_tahunan(),
			);

			$this->load->view('back/dashboard',$this->data);
		}
	}

}
