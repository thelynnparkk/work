<?php
class User extends CI_Controller{
	public function __construct(){
		parent::__construct();
		
		$this->load->model('User_model');
		$this->load->helper('url');
    	$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->helper('language');
		$this->load->helper('cookie');
	}

	public function login(){
		
		$this->data['title'] = $this->lang->line('login_heading');

		//Check for existing cookie
		if($this->User_model->validate_cookie($this->input->cookie('CI_username',true), $this->input->cookie('CI_password')) === true){
			if($this->User_model->get_role($this->input->cookie('CI_username')) == 'user')
				redirect('forum');
			else
				redirect('manage');
		}

		//validate form input
		$this->form_validation->set_rules(array(
			'field' => 'username',
			'label' => 'ชื่อผู้ใช้',
			'rules' => 'required',
			'error' => array(
						'required' => 'เติมด้วยจ้า')
		));
		$this->form_validation->set_rules('password', "Password Field", 'required');

		if ($this->form_validation->run() == true){
			// check to see if the user is logging in
			$remember = (bool) $this->input->post('remember');
			$login_result = $this->User_model->login($this->input->post('username'), $this->input->post('password'));

			// TODO check for "remember me"

			if ($login_result){
				//if the login is successful and create cookie
				$this->input->set_cookie(array(
					'name' => 'CI_username',
					'value' => $this->input->post('username'),
					'expire' => '1000',
				));
				$this->input->set_cookie(array(
					'name' => 'CI_password',
					'value' => $login_result,
					'expire' => '1000',
				));
				redirect('login');
			}
			else{
				// if the login was un-successful
				// redirect them back to the login page
				$this->session->set_flashdata('message', "Login Error");
		    	// $this->session->set_flashdata('messageClass','danger');
				redirect('login');
			}
		}else{
			// the user is not logging in so display the login page
			// set the flash data error message if there is one

			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->data['messageClass'] = validation_errors() ? 'danger' : $this->session->flashdata('messageClass');

			$this->data['username'] = array(
				'name' => 'username',
				'id'    => 'identity',
				'type'  => 'text',
				'required' => 'true',
				'class' => 'form-control',
				'placeholder' => 'username',
				'autofocus' => 'true',
				'value' => $this->form_validation->set_value('username'),
			);
			$this->data['password'] = array(
				'name' => 'password',
				'id'   => 'password',
				'type' => 'password',
				'required' => 'true',
				'class' => 'form-control input-lg',
				'placeholder' => 'password',
			);

			$this->load->view('user/login', $this->data);
		}
	}

	public function logout(){
		delete_cookie('CI_username');
		delete_cookie('CI_password');
		$this->load->view('user/logout'); 
	}

	public function register(){
		$this->data['title'] = $this->lang->line('login_heading');

		//Check for existing cookie
		// if($this->User_model->validate_cookie($this->input->cookie('CI_username',true), $this->input->cookie('CI_password')) === true){
		// 	redirect('forum');
		// }

		//validate form input
		$this->form_validation->set_rules('username', "Username", 'required');
		$this->form_validation->set_rules('password', "Password", 'required');
		$this->form_validation->set_rules('confirmpassword', "Confirm Password", 'required|matches[password]');
		$this->form_validation->set_message('matches', 'Password Mismatch');

		if ($this->form_validation->run() == true){
			// check to see if the user is logging in
			// check for "remember me"
			$remember = (bool) $this->input->post('remember');
			$result = $this->User_model->register($this->input->post('username'), $this->input->post('password'), $this->input->post('role'));
			if ($result){
				//if the login is successful
				$this->input->set_cookie(array(
					'name' => 'CI_username',
					'value' => $this->input->post('username'),
					'expire' => '1000',
				));
				$this->input->set_cookie(array(
					'name' => 'CI_password',
					'value' => $result,
					'expire' => '1000',
				));
				redirect('user/data');
			}
			else{
				// if the login was un-successful
				// redirect them back to the login page
				$this->session->set_flashdata('message', "Register Error:" + $result);
		    	$this->session->set_flashdata('messageClass','danger');
				redirect('register', 'refresh');
			}
		}
		else{
			// the user is not logging in so display the login page
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->data['messageClass'] = validation_errors() ? 'danger' : $this->session->flashdata('messageClass');

			$this->data['username'] = array(
				'name' => 'username',
				'id'    => 'identity',
				'type'  => 'text',
				'required' => 'true',
				'class' => 'form-control',
				'placeholder' => 'username',
				'autofocus' => 'true',
				'value' => $this->form_validation->set_value('username'),
			);
			$this->data['password'] = array(
				'name' => 'password',
				'id'   => 'password',
				'type' => 'password',
				'required' => 'true',
				'class' => 'form-control input-lg',
				'placeholder' => 'password',
			);
			$this->data['confirmpassword'] = array(
				'name' => 'confirmpassword',
				'id'   => 'confirmpassword',
				'type' => 'password',
				'required' => 'true',
				'class' => 'form-control input-lg',
				'placeholder' => 'Type password again',
			);

			$this->data['role'] = array(
				'name' => 'role',
				'id'   => 'role',
				'required' => 'true',
				'class' => 'form-control input-lg',
				'placeholder' => 'Type password again',
			);

			$this->load->view('user/register', $this->data);
		}
	}

	public function data($id){

	}


	public function manage($id = 0){

		//Check for existing cookie
		if($this->User_model->validate_cookie($this->input->cookie('CI_username',true), $this->input->cookie('CI_password')) === true){
			if($this->User_model->get_role($this->input->cookie('CI_username')) != 'tutor'){
				echo 'Unautorized'; //ถ้า role ไม่ใช่ admin
				return;
			}
		}

		if($id != 0){
			$this->User_model->update($id, array('username' => $this->input->post('username'), 'password' => $this->input->post('password'), 'role' => $this->input->post('role')));
		}

		$this->data['users'] = $this->User_model->get_all_users();

		$this->load->view('user/manage', $this->data);
	}
}
?>
