<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');

		$this->load->model('Company_model');
		$this->load->model('Kontak_model');
		$this->load->model('Ion_auth_model');
		$this->load->model('Wilayah_model');

		$this->data['module'] = 'Customer';
		$this->data['company_data'] 			= $this->Company_model->get_by_company();
		$this->data['kontak'] 						= $this->Kontak_model->get_all();
	}

	public function register()
	{
		$this->data['title'] 							= 'Register/ Login';

		// Cek sudah/ belum login
		if ($this->ion_auth->logged_in()){redirect(base_url());}

		/* setting bawaan ionauth */
		$tables 					= $this->config->item('tables','ion_auth');
		$identity_column 	= $this->config->item('identity','ion_auth');

		$this->data['identity_column'] = $identity_column;

		// validasi form input
		$this->form_validation->set_rules('name', 'Nama', 'required|trim|is_unique[users.name]');
		$this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[users.username]');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('phone', 'No. HP', 'trim|numeric');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', 'Konfirmasi Password', 'required');

		// set pesan
		$this->form_validation->set_message('required', '{field} mohon diisi');
		$this->form_validation->set_message('valid_email', 'Format email tidak benar');
		$this->form_validation->set_message('numeric', 'No. HP harus angka');
		$this->form_validation->set_message('matches', 'Password baru dan konfirmasi harus sama');
		$this->form_validation->set_message('is_unique', '%s telah terpakai, silahkan ganti dengan yang lain');

		// cek form_validation dan register ke db
		if ($this->form_validation->run() == true)
		{
			$email    = strtolower($this->input->post('email'));
			$identity = ($identity_column==='email') ? $email : $this->input->post('identity');
			$password = $this->input->post('password');

			// data tambahan yang untuk dimasukkan pada tabel
			$additional_data = array(
				'name' 				=> $this->input->post('name'),
				'username'  	=> $this->input->post('username'),
				'phone'      	=> $this->input->post('phone'),
				'address'    	=> $this->input->post('alamat'),
				'provinsi' 		=> $this->input->post('provinsi_id'),
				'kota'   			=> $this->input->post('kota_id'),
				'usertype'    => '4',
			);

			// mengirimkan data yang sudah disediakan diatas $additional_data $email, $identity $password
			$this->ion_auth->register($identity, $password, $email, $additional_data);

			// check to see if we are creating the user | redirect them back to the admin page
			$this->session->set_flashdata('message', '<div class="alert alert-success alert">Registrasi Berhasil, silahkan login untuk mulai booking lapangan.</div>');
			redirect(base_url());
		}
			else
			{
				// display the create user form | set the flash data error message if there is one
				$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

				$this->data['name'] = array(
					'name'  => 'name',
					'id'    => 'name',
					'type'  => 'text',
					'class'  => 'form-control',
					'value' => $this->form_validation->set_value('name'),
				);
				$this->data['username'] = array(
					'name'  => 'username',
					'id'    => 'username',
					'class'  => 'form-control',
					'value' => $this->form_validation->set_value('username'),
				);
				$this->data['email'] = array(
					'name'  => 'email',
					'id'    => 'email',
					'class'  => 'form-control',
					'value' => $this->form_validation->set_value('email'),
				);
				$this->data['phone'] = array(
					'name'  => 'phone',
					'id'    => 'phone',
					'class'  => 'form-control',
					'value' => $this->form_validation->set_value('phone'),
				);
				$this->data['password'] = array(
					'name'  => 'password',
					'id'    => 'password',
					'class'  => 'form-control',
					'value' => $this->form_validation->set_value('password'),
				);
				$this->data['password_confirm'] = array(
					'name'  => 'password_confirm',
					'id'    => 'password_confirm',
					'class'  => 'form-control',
					'value' => $this->form_validation->set_value('password_confirm'),
				);
				$this->data['alamat'] = array(
					'name'  => 'alamat',
					'id'    => 'alamat',
					'class'  => 'form-control',
					'cols'  => '2',
					'rows'  => '2',
					'value' => $this->form_validation->set_value('alamat'),
				);
				$this->data['provinsi_id'] = array(
		      'name'        => 'provinsi_id',
		      'id'          => 'provinsi_id',
		      'class'       => 'form-control',
		      'onChange'    => 'tampilKota()',
		      'required'    => '',
		    );
		    $this->data['kota_id'] = array(
		      'name'        => 'kota_id',
		      'id'          => 'kota_id',
		      'class'       => 'form-control',
		      'required'    => '',
		    );

				$this->data['ambil_provinsi'] = $this->Wilayah_model->get_provinsi();

				$this->load->view('front/auth/register', $this->data);
			}
	}

	public function pilih_kota()
	{
		$this->data['kota']=$this->Wilayah_model->get_kota($this->uri->segment(3));
		$this->load->view('front/auth/kota',$this->data);
	}

	public function login()
	{
		$this->data['title'] 							= 'Login';

		// cek sudah login/belum
		if($this->ion_auth->logged_in()){redirect(base_url());}

		// panggil library recaptcha
		$this->load->library('Recaptcha');

		// siapkan data recaptcha
		$this->data['captcha'] = $this->recaptcha->getWidget();
		$this->data['script_captcha'] = $this->recaptcha->getScriptTag();
		$recaptcha 	= $this->input->post('g-recaptcha-response');
    $response 	= $this->recaptcha->verifyResponse($recaptcha);

		//validate form input
		$this->form_validation->set_rules('identity', 'Identity', 'callback_identity_check');
		$this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');
		$this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'required');
		$this->form_validation->set_message('required', '{field} mohon diisi');

		// jika form_validation gagal dijalankan dan response recaptcha juga gagal maka akan diarahkan kembali ke halaman login
		// if ($this->form_validation->run() == FALSE)
		// {
		if ($this->form_validation->run() == FALSE || !isset($response['success']) || $response['success'] <> TRUE)
		{
			// set pesan error dari ion_auth
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			// email atau username yang diatur pada config ion_auth
			$this->data['identity'] = array(
				'name' 	=> 'identity',
				'id'    => 'identity',
				'class' => 'form-control',
				'value' => $this->form_validation->set_value('identity'),
			);
			$this->data['password'] = array(
				'name' 	=> 'password',
				'id'   	=> 'password',
				'class' => 'form-control',
			);

			// _render_page == view
			$this->_render_page('front/auth/login', $this->data);
		}
			else
			{
				// cek user login dan menekan tombol remember me
				$remember = (bool) $this->input->post('remember');

				// cek keberhasilan login dengan function login_front
				if ($this->ion_auth->login_front($this->input->post('identity'), $this->input->post('password'), $remember))
				{
					//set message dan redirect ke home apabila berhasil login
					$this->session->set_flashdata('message', '<div class="alert alert-block alert-success"><i class="ace-icon fa fa-bullhorn green"></i> Login Berhasil</div>');
					redirect(base_url(), 'refresh');
				}
					else
					{
						//set message dan redirect ke form login apabila gagal login
						$this->session->set_flashdata('message', $this->ion_auth->errors(''));
						redirect('auth/login', 'refresh');
					}

			}
	}

	public function logout()
	{
		$logout = $this->ion_auth->logout();
		redirect(base_url(), 'refresh');
	}

	// cek identity
	public function identity_check($str){
		$this->load->model('Ion_auth_model');
		if ($this->ion_auth_model->identity_check($str)){
			return TRUE;
		}
		else{
			$this->form_validation->set_message('identity_check','Username tidak ditemukan');
			return FALSE;
		}
	}

	public function profil()
	{
		$this->data['title'] 			= 'Profil Saya';

		$this->data['profil'] = $this->Ion_auth_model->profil();

		$this->load->view('front/auth/profil', $this->data);
	}

	public function edit_profil($id)
	{
		$this->data['title'] = 'Edit Data User';

		$user = $this->ion_auth->user($id)->row();

		// cek validitas sudah login/belum dan mencegah edit data orang lain
		if (!$this->ion_auth->logged_in() || !($this->ion_auth->user()->row()->id == $id)){
			redirect(base_url(), 'refresh');
		}
		// cek ketersediaan user di tabel
		if($user == FALSE){
			$this->session->set_flashdata('message', '
			<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
			<i class="ace-icon fa fa-bullhorn green"></i> Data tidak ditemukan
			</div>');
			redirect(base_url(), 'refresh');
		}

		// validate form input
		$this->form_validation->set_rules('name', 'Nama', 'required|trim');
		$this->form_validation->set_rules('username', 'Username', 'required|trim');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('phone', 'No. HP', 'trim|numeric');

		// Validasi form
		$this->form_validation->set_message('required', '{field} mohon diisi');
		$this->form_validation->set_message('numeric', 'No. HP harus angka');
		$this->form_validation->set_message('valid_email', 'Format email salah');
		$this->form_validation->set_message('min_length', 'Password minimal 8 huruf');
		$this->form_validation->set_message('max_length', 'Password maksimal 20 huruf');
		$this->form_validation->set_message('matches', 'Password baru dan konfirmasi harus sama');

		// cek tombol submit ditekan atau tidak/ berisi
		if (isset($_POST) && !empty($_POST))
		{
			// mengecek validitas request update data
			// if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')){
			// 	show_error($this->lang->line('error_csrf'));
			// }

			// update password jika dimasukkan/ diisi
			if ($this->input->post('password')){
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
			}

			// jalankan form_validation
			if ($this->form_validation->run() === TRUE)
			{
				// siapkan data
				$data = array(
					'name' 			=> $this->input->post('name'),
					'username'  => $this->input->post('username'),
					'email'     => strtolower($this->input->post('email')),
					'address'  	=> $this->input->post('address'),
					'phone'     => $this->input->post('phone'),
					'provinsi'     => $this->input->post('provinsi_id'),
					'kota'     => $this->input->post('kota_id'),
				);

				// jika password terisi
				if ($this->input->post('password')){
					$data['password'] = $this->input->post('password');
				}

				// apabila berhasil update data maka diarahkan ke halaman profil
				if($this->ion_auth->update($user->id, $data))
				{
					$this->session->set_flashdata('message', '
	        <div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
						<i class="ace-icon fa fa-bullhorn green"></i> Update Data Berhasil
	        </div>');
	        redirect(site_url('auth/profil'));
				}
				else{
					// Set pesan
					$this->session->set_flashdata('message', '
	        <div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
						<i class="ace-icon fa fa-bullhorn green"></i> Update Data Gagal
	        </div>');
	        redirect(site_url(base_url()));
				}
			}
		}

		// perintah security csrf dari ion_auth, detailnya bisa baca di https://www.owasp.org/index.php/Cross-Site_Request_Forgery_(CSRF)
		// $this->data['csrf'] = $this->_get_csrf_nonce();

		// mengatur pesan/ flashdata eror
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		// melempar data $user ke view supaya bisa digunakan
		$this->data['user'] = $user;

		$this->data['name'] = array(
			'name'  => 'name',
			'id'    => 'name',
			'class'  => 'form-control',
			'value' => $this->form_validation->set_value('name', $user->name),
		);
		$this->data['username'] = array(
			'name'  => 'username',
			'id'    => 'username',
			'class'  => 'form-control',
			'readonly'    => '',
			'value' => $this->form_validation->set_value('username', $user->username),
		);
		$this->data['email'] = array(
			'name'  => 'email',
			'id'    => 'email',
			'class'  => 'form-control',
			'value' => $this->form_validation->set_value('email', $user->email),
		);
		$this->data['address'] = array(
			'name'  => 'address',
			'id'    => 'address',
			'class'  => 'form-control',
			'rows'  => '2',
			'cols'  => '2',
			'value' => $this->form_validation->set_value('address', $user->address),
		);
		$this->data['usertype'] = array(
			'name'  => 'usertype',
			'id'    => 'usertype',
			'type'  => 'text',
			'class'  => 'form-control',
		);
		$this->data['phone'] = array(
			'name'  => 'phone',
			'id'    => 'phone',
			'class'  => 'form-control',
			'value' => $this->form_validation->set_value('phone', $user->phone),
		);
		$this->data['password'] = array(
			'name' => 'password',
			'id'   => 'password',
			'class'  => 'form-control',
			'placeholder'  => 'diisi jika mengubah password'
		);
		$this->data['password_confirm'] = array(
			'name' => 'password_confirm',
			'id'   => 'password_confirm',
			'class'  => 'form-control',
			'placeholder'  => 'diisi jika mengubah password'
		);
		$this->data['provinsi_id'] = array(
			'name'        => 'provinsi_id',
			'id'          => 'provinsi_id',
			'class'       => 'form-control',
			'onChange'    => 'tampilKota()',
			'required'    => '',
		);
		$this->data['kota_id'] = array(
			'name'        => 'kota_id',
			'id'          => 'kota_id',
			'class'       => 'form-control',
			'required'    => '',
		);

		$kota = $user->provinsi;

		$this->data['ambil_provinsi'] = $this->Wilayah_model->get_provinsi();
		$this->data['ambil_kota'] 		= $this->Wilayah_model->get_kota($kota);

		$this->_render_page('front/auth/edit_profil', $this->data);
	}

	// Lupa Password
	public function forgot_password()
	{
		// cek identity nya apakah email atau username untuk dijadikan bagian lupa password
		if($this->config->item('identity', 'ion_auth') != 'email' )
		{
			$this->form_validation->set_rules('identity', $this->lang->line('forgot_password_username_label'), 'required');
		}
			else
			{
				$this->form_validation->set_rules('identity', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
			}

		if ($this->form_validation->run() == FALSE)
		{
			$this->data['type'] = $this->config->item('identity','ion_auth');
			// setup the input
			$this->data['identity'] = array('name' => 'identity',
				'id' => 'identity',
			);

			echo "<script>alert('Email harus diisi!');history.go(-1)</script>";
		}
			else
			{
				// perintah bawaan ion_auth untuk identity (jangan diotak-atik)
				$identity_column = $this->config->item('identity','ion_auth');
				// siapkan identity
				$identity = $this->ion_auth->where($identity_column, $this->input->post('identity'))->users()->row();

				// cek ketersediaan data akun $identity di tabel
				if(empty($identity))
				{
					echo "<script>alert('Email tidak ditemukan!');history.go(-1)</script>";
				}
					else
					{
						// jalankan method forgotten_password untuk lanjut ke proses lupa password
						$forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});
						// jika berhasil
						if ($forgotten)
						{
							echo "<script>alert('Reset Password berhasil, silahkan cek email Anda!');history.go(-1)</script>";
						}
							else
							{
								echo "<script>alert('Reset Password gagal, silahkan dicoba kembali!');history.go(-1)</script>";
							}
					}
			}
	}

	// Tahap lanjutan dari lupa password -> reset password
	public function reset_password($code = NULL){
		// cek kode reset_password apakah ada/tidak di tabel
		if (!$code)
		{
			show_404();
		}

		$user = $this->ion_auth->forgotten_password_check($code);

		// cek akun yang punya kode reset menggunakan $user diatas dengan function forgotten_password_check
		if ($user)
		{
			//siapkan form_validation bagian password baru dan ulangi password
			$this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

			if ($this->form_validation->run() == false)
			{
				// set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

				// tampilkan settingan panjang minimum password
				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');

				// siapkan data untuk insert password baru
				$this->data['new_password'] = array(
					'name' => 'new',
					'id'   => 'new',
					'type' => 'password',
					'class' => 'form-control',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['new_password_confirm'] = array(
					'name'    => 'new_confirm',
					'id'      => 'new_confirm',
					'type'    => 'password',
					'class' => 'form-control',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['user_id'] = array(
					'name'  => 'user_id',
					'id'    => 'user_id',
					'type'  => 'hidden',
					'value' => $user->id,
				);

				// perintah csrf bawaan dari ion_auth(jangan diotak-atik)
				$this->data['csrf'] = $this->_get_csrf_nonce();
				// code reset password yang ada di db dan url
				$this->data['code'] = $code;

				$this->_render_page('front/auth/reset_password', $this->data);
			}
			else
			{
				// cek validitas request data yang diminta
				if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id'))
				{
					// hapus forgotten_password_code di tabel
					$this->ion_auth->clear_forgotten_password_code($code);
					show_error($this->lang->line('error_csrf'));
				}
				else
				{
					// siapkan $identity untuk dimasukkan pada bagian $change
					$identity = $user->{$this->config->item('identity', 'ion_auth')};

					// siapkan $change untuk mereset password yang sudah final dan sebagai pengecekan berhasil/tidaknya password baru
					$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));
					if ($change)
					{
						$this->session->set_flashdata('message', '<div class="alert alert-success alert">Reset Password Berhasil</div>');
						redirect("auth/login", 'refresh');
					}
					else
					{
						$this->session->set_flashdata('message', $this->ion_auth->errors());
						redirect('auth/reset_password/' . $code, 'refresh');
					}
				}
			}
		}
		else
		{
			// jika tidak ditemukan/ invalid
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}

	// Aktivasi user
	public function activate($id, $code=false){
		if ($code !== false)
		{
			$activation = $this->ion_auth->activate($id, $code);
		}
		if($activation)
		{
			// jika berhasil aktivasi akun
			$this->session->set_flashdata('message', '
			<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
				<i class="ace-icon fa fa-bullhorn green"></i> Akun berhasil diaktifkan
			</div>');
			redirect("auth/login", 'refresh');
		}
		else
		{
			$this->session->set_flashdata('message', '
			<div class="alert alert-block alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
				<i class="ace-icon fa fa-bullhorn green"></i> Akun gagal diaktifkan
			</div>');
			redirect("auth/login", 'refresh');
		}
	}

	public function _get_csrf_nonce(){
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	public function _valid_csrf_nonce(){
		if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
		$this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	// sama seperti view
	public function _render_page($view, $data=null, $returnhtml=false){

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}

}
