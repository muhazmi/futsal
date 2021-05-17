<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Auth extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');
		$this->load->model('Company_model');
		$this->load->model('Ion_auth_model');

		$this->data['module'] = 'User';
	}

	public function index()
	{
		$this->data['title'] = 'Data ' . $this->data['module'];

		// Cek sudah/ belum login
		if (!$this->ion_auth->logged_in()) {
			redirect('admin/auth/login', 'refresh');
		}
		// Cek super bukan
		elseif (!$this->ion_auth->is_superadmin()) {
			redirect('admin/dashboard', 'refresh');
		} else {
			// Set pesan flash data error jika ada
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['users'] 	= $this->ion_auth->get_all_users()->result();

			$this->_render_page('back/auth/index', $this->data);
		}
	}

	/**
	 * Log the user in
	 */
	public function login()
	{
		$this->data['title'] = "Login BackEnd Area";

		$this->load->library('Recaptcha');

		$this->data['captcha'] = $this->recaptcha->getWidget();
		$this->data['script_captcha'] = $this->recaptcha->getScriptTag();
		$this->data['title'] = $this->lang->line('login_heading');

		$recaptcha = $this->input->post('g-recaptcha-response');
		$response = $this->recaptcha->verifyResponse($recaptcha);

		// validate form input
		$this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'required');
		$this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');
		$this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'required');
		$this->form_validation->set_message('required', '{field} mohon diisi');

		// jika form_validation gagal dijalankan dan response recaptcha juga gagal maka akan diarahkan kembali ke halaman login
		// if ($this->form_validation->run() == FALSE)
		// {
		if ($this->form_validation->run() == FALSE || !isset($response['success']) || $response['success'] <> TRUE) {
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['identity'] = array(
				'name' 	=> 'identity',
				'id'    => 'identity',
				'class' => 'form-control',
				'placeholder' => 'Isikan Email Anda',
				'value' => $this->form_validation->set_value('identity'),
			);
			$this->data['password'] = array(
				'name' 	=> 'password',
				'id'   	=> 'password',
				'class' => 'form-control',
				'placeholder' => 'Isikan Password Anda',
			);

			$this->_render_page('back/auth/login', $this->data);
		} else {
			// check to see if the user is logging in
			// check for "remember me"
			$remember = (bool) $this->input->post('remember');

			if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {
				//if the login is successful
				//redirect them back to the home page
				$this->session->set_flashdata('message', '<div class="box-body">
					<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
					<i class="ace-icon fa fa-bullhorn green"></i> Login Berhasil
					</div></div>');
				redirect('admin/dashboard', 'refresh');
			} else {
				// if the login was un-successful
				// redirect them back to the login page
				$this->session->set_flashdata('message', $this->ion_auth->errors(''));
				redirect('admin/auth/login', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		}
	}

	/**
	 * Log the user out
	 */
	public function logout()
	{
		// log the user out
		$logout = $this->ion_auth->logout();

		// redirect them to the login page
		$this->session->set_flashdata('message', '
		<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
		<i class="ace-icon fa fa-bullhorn green"></i> Logout Berhasil
		</div>');
		redirect('admin/auth/login', 'refresh');
	}

	/**
	 * Change password
	 */
	public function change_password()
	{
		$this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
		$this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

		if (!$this->ion_auth->logged_in()) {
			redirect('admin/auth/login', 'refresh');
		}

		$user = $this->ion_auth->user()->row();

		if ($this->form_validation->run() === FALSE) {
			// display the form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
			$this->data['old_password'] = array(
				'name' => 'old',
				'id' => 'old',
				'type' => 'password',
			);
			$this->data['new_password'] = array(
				'name' => 'new',
				'id' => 'new',
				'type' => 'password',
				'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
			);
			$this->data['new_password_confirm'] = array(
				'name' => 'new_confirm',
				'id' => 'new_confirm',
				'type' => 'password',
				'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
			);
			$this->data['user_id'] = array(
				'name' => 'user_id',
				'id' => 'user_id',
				'type' => 'hidden',
				'value' => $user->id,
			);

			// render
			$this->_render_page('back/auth/change_password', $this->data);
		} else {
			$identity = $this->session->userdata('identity');

			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change) {
				//if the password was successfully changed
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				$this->logout();
			} else {
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('admin/auth/change_password', 'refresh');
			}
		}
	}

	/**
	 * Forgot password
	 */
	public function forgot_password()
	{
		// setting validation rules by checking whether identity is username or email
		if ($this->config->item('identity', 'ion_auth') != 'email') {
			$this->form_validation->set_rules('identity', $this->lang->line('forgot_password_identity_label'), 'required');
		} else {
			$this->form_validation->set_rules('identity', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
		}


		if ($this->form_validation->run() === FALSE) {
			$this->data['type'] = $this->config->item('identity', 'ion_auth');
			// setup the input
			$this->data['identity'] = array(
				'name' => 'identity',
				'id' => 'identity',
			);

			if ($this->config->item('identity', 'ion_auth') != 'email') {
				$this->data['identity_label'] = $this->lang->line('forgot_password_identity_label');
			} else {
				$this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
			}

			// set any errors and display the form
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_page('back/auth/forgot_password', $this->data);
		} else {
			$identity_column = $this->config->item('identity', 'ion_auth');
			$identity = $this->ion_auth->where($identity_column, $this->input->post('identity'))->users()->row();

			if (empty($identity)) {
				echo "<script>alert('Email tidak ditemukan!');history.go(-1)</script>";
			} else {
				// run the forgotten password method to email an activation code to the user
				$forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});
				if ($forgotten) {
					echo "<script>alert('Reset Password berhasil, silahkan cek email Anda!');history.go(-1)</script>";
				} else {
					echo "<script>alert('Reset Password gagal, silahkan dicoba kembali!');history.go(-1)</script>";
				}
			}
		}
	}

	/**
	 * Reset password - final step for forgotten password
	 *
	 * @param string|null $code The reset code
	 */
	public function reset_password($code = NULL)
	{
		if (!$code) {
			show_404();
		}

		$user = $this->ion_auth->forgotten_password_check($code);

		if ($user) {
			// if the code is valid then display the password reset form
			$this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

			if ($this->form_validation->run() === FALSE) {
				// set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
				$this->data['new_password'] = array(
					'name' => 'new',
					'id' => 'new',
					'type' => 'password',
					'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
				);
				$this->data['new_password_confirm'] = array(
					'name' => 'new_confirm',
					'id' => 'new_confirm',
					'type' => 'password',
					'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
				);
				$this->data['user_id'] = array(
					'name' => 'user_id',
					'id' => 'user_id',
					'type' => 'hidden',
					'value' => $user->id,
				);
				$this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;

				// render
				$this->_render_page('back/auth/reset_password', $this->data);
			} else {
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id')) {

					// something fishy might be up
					$this->ion_auth->clear_forgotten_password_code($code);

					show_error($this->lang->line('error_csrf'));
				} else {
					// finally change the password
					$identity = $user->{$this->config->item('identity', 'ion_auth')};
					$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

					if ($change) {
						if ($user->usertype == '1' or $user->usertype == '2') {
							// if the password was successfully changed
							$this->session->set_flashdata('message', $this->ion_auth->messages());
							redirect("admin/auth/login", 'refresh');
						}
					} else {
						$this->session->set_flashdata('message', $this->ion_auth->errors());
						redirect('admin/auth/reset_password/' . $code, 'refresh');
					}
				}
			}
		} else {
			// if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}

	// modified
	/**
	 * Activate the user
	 *
	 * @param int         $id   The user ID
	 * @param string|bool $code The activation code
	 */
	public function activate($id, $code = FALSE)
	{
		if ($code !== false) {
			$activation = $this->ion_auth->activate($id, $code);
		} else if ($this->ion_auth->is_superadmin()) {
			$activation = $this->ion_auth->activate($id);
		}

		if ($activation) {
			// redirect them to the auth page
			$this->session->set_flashdata('message', '
			<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
				<i class="ace-icon fa fa-bullhorn green"></i> Akun berhasil diaktifkan
			</div>');
			redirect("admin/auth/", 'refresh');
		} else {
			// redirect them to the forgot password page
			$this->session->set_flashdata('message', '
			<div class="alert alert-block alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
				<i class="ace-icon fa fa-bullhorn green"></i> Akun gagal diaktifkan
			</div>');
			redirect("admin/auth/", 'refresh');
		}
	}

	// modified
	/**
	 * Deactivate the user
	 *
	 * @param int|string|null $id The user ID
	 */
	public function deactivate($id = NULL)
	{
		$id = (int) $id;

		// mengecek usertype user apakah super bukan
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_superadmin()) {
			$this->ion_auth->deactivate($id);
		}

		// mengarahkan ke halaman user/ data user
		$this->session->set_flashdata('message', '
		<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
			<i class="ace-icon fa fa-bullhorn green"></i> Akun berhasil dinonaktifkan
		</div>');
		redirect('admin/auth/', 'refresh');
	}

	// modified
	/**
	 * Create a new user
	 */
	public function create_user()
	{
		$this->data['title'] = 'Tambah Data ' . $this->data['module'];

		/* setting bawaan ionauth */
		$tables 					= $this->config->item('tables', 'ion_auth');
		$identity_column 	= $this->config->item('identity', 'ion_auth');

		$this->data['identity_column'] = $identity_column;

		// validasi form input
		$this->form_validation->set_rules('name', $this->lang->line('create_user_validation_fname_label'), 'required|is_unique[' . $tables['users'] . '.' . $identity_column . ']');
		$this->form_validation->set_rules('username', $this->lang->line('create_username_validation_fname_label'), 'required');
		$this->form_validation->set_rules('address', 'Alamat', 'required');
		$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'valid_email|is_unique[' . $tables['users'] . '.email]');
		$this->form_validation->set_rules('phone', 'No. Telp', 'trim');
		$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

		// set_message / set pesan
		$this->form_validation->set_message('required', '{field} mohon diisi');
		$this->form_validation->set_message('valid_email', 'Format email tidak benar');
		$this->form_validation->set_message('numeric', 'No. HP harus angka');
		$this->form_validation->set_message('matches', 'Password baru dan konfirmasi harus sama');
		$this->form_validation->set_message('is_unique', '%s telah terpakai, ganti dengan yang lain');

		/* jalan form validasi */
		if ($this->form_validation->run() == true) {
			// jika mengisi form upload foto
			if ($_FILES['photo']['error'] <> 4) {
				$nmfile = strtolower(url_title($this->input->post('name'))) . date('YmdHis');

				/* memanggil library upload ci */
				$config['upload_path']      = './assets/images/user/';
				$config['allowed_types']    = 'jpg|jpeg|png|gif';
				$config['max_size']         = '2048'; // 2 MB
				$config['file_name']        = $nmfile; //name yang terupload nantinya

				$this->load->library('upload', $config);

				if (!$this->upload->do_upload('photo')) {
					//file gagal diupload -> kembali ke form tambah
					$error = array('error' => $this->upload->display_errors());
					$this->session->set_flashdata('message', '<div class="alert alert-danger alert">' . $error['error'] . '</div>');
				}
				//file berhasil diupload -> lanjutkan ke query INSERT
				else {
					$photo = $this->upload->data();
					$thumbnail                = $config['file_name'];
					// library yang disediakan codeigniter
					$config['image_library']  = 'gd2';
					// gambar yang akan dibuat thumbnail
					$config['source_image']   = './assets/images/user/' . $photo['file_name'] . '';
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

					$email    = strtolower($this->input->post('email'));
					$identity = ($identity_column === 'email') ? $email : $this->input->post('identity');
					$password = $this->input->post('password');

					$additional_data = array(
						'name' 				=> $this->input->post('name'),
						'username'  	=> $this->input->post('username'),
						'email'     	=> strtolower($this->input->post('email')),
						'active'  		=> '1',
						'usertype'  	=> $this->input->post('usertype'),
						'address'  		=> $this->input->post('address'),
						'phone'     	=> $this->input->post('phone'),
						'photo'       => $nmfile,
						'photo_type'  => $photo['file_ext'],
						'uploader'    => $this->session->userdata('user_id')
					);

					$this->ion_auth->register($identity, $password, $email, $additional_data);

					// check to see if we are creating the user | redirect them back to the admin page
					$this->session->set_flashdata('message', '<div class="alert alert-success alert">Data berhasil dibuat</div>');
					redirect('admin/auth', 'refresh');
				}
			}
			// Jika tidak upload foto
			else {
				$email    = strtolower($this->input->post('email'));
				$identity = ($identity_column === 'email') ? $email : $this->input->post('identity');
				$password = $this->input->post('password');

				$additional_data = array(
					'name' 				=> $this->input->post('name'),
					'username'  	=> $this->input->post('username'),
					'email'     	=> strtolower($this->input->post('email')),
					'active'  		=> '1',
					'usertype'  	=> $this->input->post('usertype'),
					'address'  		=> $this->input->post('address'),
					'phone'     	=> $this->input->post('phone'),
					'uploader'    => $this->session->userdata('user_id')
				);

				$this->ion_auth->register($identity, $password, $email, $additional_data);

				// check to see if we are creating the user | redirect them back to the admin page
				$this->session->set_flashdata('message', '
         <div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
 					<i class="ace-icon fa fa-bullhorn green"></i> Data berhasil dibuat
         </div>');
				redirect('admin/auth', 'refresh');
			}
		}

		// display the create user form | set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		$this->data['name'] = array(
			'name'  => 'name',
			'id'    => 'name',
			'class' => 'form-control',
			'value' => $this->form_validation->set_value('name'),
		);
		$this->data['username'] = array(
			'name'  => 'username',
			'id'    => 'username',
			'class' => 'form-control',
			'value' => $this->form_validation->set_value('username'),
		);
		$this->data['password'] = array(
			'name'  => 'password',
			'id'    => 'password',
			'class' => 'form-control',
			'value' => $this->form_validation->set_value('password'),
		);
		$this->data['password_confirm'] = array(
			'name'  => 'password_confirm',
			'id'    => 'password_confirm',
			'class' => 'form-control',
			'value' => $this->form_validation->set_value('password_confirm'),
		);
		$this->data['address'] = array(
			'name'  => 'address',
			'id'    => 'address',
			'class' => 'form-control',
			'cols'  => '2',
			'rows'  => '2',
			'value' => $this->form_validation->set_value('address'),
		);
		$this->data['email'] = array(
			'name'  => 'email',
			'id'    => 'email',
			'type'    => 'email',
			'class' => 'form-control',
			'value' => $this->form_validation->set_value('email'),
		);
		$this->data['phone'] = array(
			'name'  => 'phone',
			'id'    => 'phone',
			'class' => 'form-control',
			'type'    => 'number',
			'value' => $this->form_validation->set_value('phone'),
		);
		$this->data['usertype_css'] = array(
			'name'  => 'usertype',
			'id'    => 'usertype',
			'class' => 'form-control',
		);

		$this->data['get_all_users_group'] 	= $this->Ion_auth_model->get_all_users_group();

		$this->load->view('back/auth/create_user', $this->data);
	}

	/**
	 * Edit a user
	 *
	 * @param int|string $id
	 */
	public function edit_user($id)
	{
		$this->data['title'] = 'Edit Data ' . $this->data['module'];

		// Cek hak akses ubah password user lain (Hanya Superadmin yang dibolehkan)
		if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_superadmin() && !($this->ion_auth->user()->row()->id == $id))) {
			redirect('admin/dashboard', 'refresh');
		}

		$user = $this->ion_auth->user($id)->row();

		if ($user == FALSE) {
			$this->session->set_flashdata('message', '
 			<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
 			<i class="ace-icon fa fa-bullhorn green"></i> Data tidak ditemukan
 			</div>');
			redirect('admin/auth/', 'refresh');
		}

		// validate form input
		$this->form_validation->set_rules('name', 'name', 'required|trim');
		$this->form_validation->set_rules('username', 'Username', 'required|trim');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('phone', 'No. HP', 'trim|numeric');

		// set pesan
		$this->form_validation->set_message('required', '{field} mohon diisi');
		$this->form_validation->set_message('numeric', 'No. HP harus angka');
		$this->form_validation->set_message('valid_email', 'Format email salah');
		$this->form_validation->set_message('min_length', 'Password minimal 8 huruf');
		$this->form_validation->set_message('max_length', 'Password maksimal 20 huruf');
		$this->form_validation->set_message('matches', 'Password baru dan konfirmasi harus sama');

		if (isset($_POST) && !empty($_POST)) {
			// mengecek validitas request update data
			if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
				show_error($this->lang->line('error_csrf'));
			}

			// update password jika dimasukkan/ diisi
			if ($this->input->post('password')) {
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
			}

			if ($this->form_validation->run() === TRUE) {
				/* jika ada file foto yang ingin diupload*/
				if ($_FILES['photo']['error'] <> 4) {
					$delete = $this->Ion_auth_model->del_by_id($this->input->post('id'));

					// Jika ada foto lama, maka hapus foto kemudian upload yang baru
					if ($delete) {
						// menyimpan lokasi gambar dalam variable
						$dir = "assets/images/user/" . $delete->photo . $delete->photo_type;
						$dir_thumb = "assets/images/user/" . $delete->photo . '_thumb' . $delete->photo_type;

						if ($delete->photo > 0) {
							// Hapus foto
							unlink($dir);
							unlink($dir_thumb);
						}

						$nmfile = strtolower(url_title($this->input->post('name'))) . date('YmdHis');

						/* memanggil library upload ci */
						$config['upload_path']      = './assets/images/user/';
						$config['allowed_types']    = 'jpg|jpeg|png|gif';
						$config['max_size']         = '2048'; // 2 MB
						$config['file_name']        = $nmfile; //name yang terupload nantinya

						$this->load->library('upload', $config);

						if (!$this->upload->do_upload('photo')) {
							//file gagal diupload -> kembali ke form tambah
							$error = array('error' => $this->upload->display_errors());
							$this->session->set_flashdata('message', '<div class="alert alert-danger alert">' . $error['error'] . '</div>');
						}
						//file berhasil diupload -> lanjutkan ke query INSERT
						else {
							$photo = $this->upload->data();
							$thumbnail                = $config['file_name'];
							// library yang disediakan codeigniter
							$config['image_library']  = 'gd2';
							// gambar yang akan dibuat thumbnail
							$config['source_image']   = './assets/images/user/' . $photo['file_name'] . '';
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
								'name' 					=> $this->input->post('name'),
								'username'  		=> $this->input->post('username'),
								'email'     		=> strtolower($this->input->post('email')),
								'usertype'  		=> $this->input->post('usertype'),
								'address'  			=> $this->input->post('address'),
								'phone'     		=> $this->input->post('phone'),
								'photo'        	=> $nmfile,
								'photo_type'   	=> $photo['file_ext'],
								'uploader'     	=> $this->session->userdata('identity')
							);

							// jika password terisi
							if ($this->input->post('password')) {
								$data['password'] = $this->input->post('password');
							}

							// mengecek apakah sedang mengupdate data user
							if ($this->ion_auth->update($user->id, $data)) {
								$this->session->set_flashdata('message', '
 					        <div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
 										<i class="ace-icon fa fa-bullhorn green"></i> Update Data Berhasil
 					        </div>');
								redirect(site_url('admin/auth/'));
							} else {
								// Set pesan
								$this->session->set_flashdata('message', '
 					        <div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
 										<i class="ace-icon fa fa-bullhorn green"></i> Update Data Gagal
 					        </div>');
								redirect(site_url('admin/auth/'));
							}
						}
					}
					// Jika tidak ada foto pada record, maka upload foto baru
					else {
						$nmfile = strtolower(url_title($this->input->post('name'))) . date('YmdHis');
						
						//load uploading file library
						$config['upload_path']      = './assets/images/user/';
						$config['allowed_types']    = 'jpg|jpeg|png|gif';
						$config['max_size']         = '2048'; // 2 MB
						$config['file_name']        = $nmfile; //name yang terupload nantinya

						$this->load->library('upload', $config);

						// Jika file gagal diupload -> kembali ke form update
						if (!$this->upload->do_upload('photo')) {
							//file gagal diupload -> kembali ke form tambah
							$error = array('error' => $this->upload->display_errors());
							$this->session->set_flashdata('message', '<div class="alert alert-danger alert">' . $error['error'] . '</div>');
						}
						// Jika file berhasil diupload -> lanjutkan ke query INSERT
						else {
							$photo = $this->upload->data();
							// library yang disediakan codeigniter
							$thumbnail                = $config['file_name'];
							//name yang terupload nantinya
							$config['image_library']  = 'gd2';
							// gambar yang akan dibuat thumbnail
							$config['source_image']   = './assets/images/user/' . $photo['file_name'] . '';
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
								'name' 						=> $this->input->post('name'),
								'username'  			=> $this->input->post('username'),
								'email'     			=> strtolower($this->input->post('email')),
								'usertype'  			=> $this->input->post('usertype'),
								'address'  				=> $this->input->post('address'),
								'phone'     			=> $this->input->post('phone'),
								'photo'        		=> $nmfile,
								'photo_type'   		=> $photo['file_ext'],
								'uploader'        => $this->session->userdata('identity')
							);

							// jika password terisi
							if ($this->input->post('password')) {
								$data['password'] = $this->input->post('password');
							}

							// mengecek apakah sedang mengupdate data user
							if ($this->ion_auth->update($user->id, $data)) {
								$this->session->set_flashdata('message', '
 						        <div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
 											<i class="ace-icon fa fa-bullhorn green"></i> Update Data Berhasil
 						        </div>');
								redirect(site_url('admin/auth/'));
							} else {
								$this->session->set_flashdata('message', '
 						        <div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
 											<i class="ace-icon fa fa-bullhorn green"></i> Update Data Gagal
 						        </div>');
								redirect(site_url('admin/auth/'));
							}
						}
					}
				}
				// Jika file upload kosong
				else {
					$data = array(
						'name' 			=> $this->input->post('name'),
						'username'  => $this->input->post('username'),
						'email'     => strtolower($this->input->post('email')),
						'usertype'  => $this->input->post('usertype'),
						'address'  	=> $this->input->post('address'),
						'phone'     => $this->input->post('phone'),
						'uploader'  => $this->session->userdata('identity')
					);

					// jika password terisi
					if ($this->input->post('password')) {
						$data['password'] = $this->input->post('password');
					}

					// mengecek apakah sedang mengupdate data user
					if ($this->ion_auth->update($user->id, $data)) {
						$this->session->set_flashdata('message', '
 							<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
 								<i class="ace-icon fa fa-bullhorn green"></i> Update Data Berhasil
 							</div>');
						redirect(site_url('admin/auth/'));
					} else {
						$this->session->set_flashdata('message', '
 							<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
 								<i class="ace-icon fa fa-bullhorn green"></i> Update Data Gagal
 							</div>');
						redirect(site_url('admin/auth/'));
					}
				}
			}
		}

		// menampilkan form edit/ update data user
		$this->data['csrf'] = $this->_get_csrf_nonce();

		// mengatur pesan/ flash data eror jika ada
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		// melempar data user ke view
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

		$this->data['get_all_users_group'] = $this->Ion_auth_model->get_all_users_group();

		$this->_render_page('back/auth/edit_user', $this->data);
	}

	public function profil()
	{
		$this->data['title'] 			= 'Profil Saya';

		$this->data['profil'] = $this->Ion_auth_model->profil();

		$this->load->view('back/auth/profil', $this->data);
	}

	public function delete_user($id)
	{
		$delete = $this->Ion_auth_model->del_by_id($id);

		// Jika ada foto lama, maka hapus foto kemudian upload yang baru
		if ($delete) {
			// menyimpan lokasi gambar dalam variable
			$dir = "assets/images/user/" . $delete->photo . $delete->photo_type;
			$dir_thumb = "assets/images/user/" . $delete->photo . '_thumb' . $delete->photo_type;

			if ($delete->photo == TRUE) {
				// Hapus foto
				unlink($dir);
				unlink($dir_thumb);
			}

			$this->Ion_auth_model->delete_user($id);
			$this->session->set_flashdata('message', '
 			<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
 			<i class="ace-icon fa fa-bullhorn green"></i> Hapus Data Berhasil
 			</div>');
			redirect(site_url('admin/auth/'));
		} else {
			$this->session->set_flashdata('message', '
 				<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
 				<i class="ace-icon fa fa-bullhorn red"></i> Data tidak ditemukan
 				</div>');
			redirect(site_url('admin/auth/'));
		}
	}

	/**
	 * Create a new group
	 */
	public function create_group()
	{
		$this->data['title'] = $this->lang->line('create_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('admin/auth', 'refresh');
		}

		// validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'trim|required|alpha_dash');

		if ($this->form_validation->run() === TRUE) {
			$new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
			if ($new_group_id) {
				// check to see if we are creating the group
				// redirect them back to the admin page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("auth", 'refresh');
			}
		} else {
			// display the create group form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['group_name'] = array(
				'name'  => 'group_name',
				'id'    => 'group_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('group_name'),
			);
			$this->data['description'] = array(
				'name'  => 'description',
				'id'    => 'description',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('description'),
			);

			$this->_render_page('back/auth/create_group', $this->data);
		}
	}

	/**
	 * Edit a group
	 *
	 * @param int|string $id
	 */
	public function edit_group($id)
	{
		// bail if no group id given
		if (!$id || empty($id)) {
			redirect('admin/auth', 'refresh');
		}

		$this->data['title'] = $this->lang->line('edit_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('admin/auth', 'refresh');
		}

		$group = $this->ion_auth->group($id)->row();

		// validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'required|alpha_dash');

		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$group_update = $this->ion_auth->update_group($id, $_POST['group_name'], $_POST['group_description']);

				if ($group_update) {
					$this->session->set_flashdata('message', $this->lang->line('edit_group_saved'));
				} else {
					$this->session->set_flashdata('message', $this->ion_auth->errors());
				}
				redirect("auth", 'refresh');
			}
		}

		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		// pass the user to the view
		$this->data['group'] = $group;

		$readonly = $this->config->item('admin_group', 'ion_auth') === $group->name ? 'readonly' : '';

		$this->data['group_name'] = array(
			'name'    => 'group_name',
			'id'      => 'group_name',
			'type'    => 'text',
			'value'   => $this->form_validation->set_value('group_name', $group->name),
			$readonly => $readonly,
		);
		$this->data['group_description'] = array(
			'name'  => 'group_description',
			'id'    => 'group_description',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_description', $group->description),
		);

		$this->_render_page('back/auth/edit_group', $this->data);
	}

	/**
	 * @return array A CSRF key-value pair
	 */
	public function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	/**
	 * @return bool Whether the posted CSRF token matches
	 */
	public function _valid_csrf_nonce()
	{
		$csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
		if ($csrfkey && $csrfkey === $this->session->flashdata('csrfvalue')) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * @param string     $view
	 * @param array|null $data
	 * @param bool       $returnhtml
	 *
	 * @return mixed
	 */
	public function _render_page($view, $data = NULL, $returnhtml = FALSE) //I think this makes more sense
	{

		$this->viewdata = (empty($data)) ? $this->data : $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		// This will return html on 3rd argument being true
		if ($returnhtml) {
			return $view_html;
		}
	}
}
