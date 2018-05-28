<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MY_Controller {
	function __construct() {
		parent::__construct();
		date_default_timezone_set('Asia/Manila');
		$this->load->library('pusher');
	}

	function index() { 
		$this->user();
	}

	function user() {

		Engine::appStyle('bootstrap.min.css');
		Engine::appStyle('welcome-style.css');


		Jquery::form();
		Jquery::inline_validation();
		Jquery::tipsy();
		Jquery::pnotify();

		$data['page_title'] = "Login";
		if(!$this->is_user_logged_in()) {
			$login_failed = $this->session->flashdata('login_failed');
			if($login_failed) {
				$data['error_message'] = '<div class="alert alert-danger"><button data-dismiss="alert" class="close" type="button">×</button><b><center>Invalid Username or Password!</center></b></div>';
			}
		} else {
			redirect("authenticate");
		}

		$this->load->view('login/login',$data);
	}

	function is_user_logged_in() {
		$session = $this->session->userdata('user_id');
		if($session) {
			$decode = $this->encrypt->decode($session);
			$user = User::findById(array("id"=>$decode));
			if($user) {
				return true;
			}
		}

		return false;
	}

	function authenticate_account() {
		$posts = $this->input->post();
		if($posts) {
			$username = $posts['username'];
			$password = $posts['password'];

			$user = User::findActiveUserByUsernameOrEmail($username);

			if($user) {

				$verified_password 	= ($this->encrypt->decode($user['password']) == $password ? true : false);
				$verified_hash 		= Password_Hash::validate_password($password,$user['hash']);
				$is_user_exist		= User::findActiveUserByUsername($user['username']);

				if($verified_password && $verified_hash && $is_user_exist) {

					$user_credentials = User::findById(array("id"=>$user['id']));

					$credentials = array(
						'user_id' 		=> $this->encrypt->encode($user['id']),
						'firstname' 	=> $user_credentials['firstname'],
						'middlename' 	=> $user_credentials['middlename'],
						'lastname' 		=> $user_credentials['lastname'],
						'name' 			=> $user_credentials['firstname'] . ' ' . $user_credentials['lastname'],
						'account_type' 	=> $user_credentials['account_type'],
						'username'		=> $user_credentials['username']

					);

					$date = date('Y-m-d');
					$user = $user['id'];
					$age_history = Age_History::findByDate(array("id" => $date));

					if(empty($age_history)){
						#update age
						$patient = Patients::findAll();

						foreach ($patient as $key => $value) {
							$bday = new DateTime($value['birthdate']);
							$today = new DateTime('00:00:00');
							$diff = $today->diff($bday);
							$current_age = $diff->y;
							if($value['age'] != $current_age){
								$record = array(
									"age" => $current_age,
								);
								Patients::save($record, $value['id']);
							}
						}
						$history = array(
							"age_date_updated" => $date,
							"updated_by" =>$user
						);
						Age_History::save($history);
					}

					$this->session->set_userdata($credentials);
					// redirect("authenticate");
					$json['is_successful'] 	= true;
					$json['redirect_to'] 	= "authenticate";
					$json['message']		= "Welcome {$credentials['name']}!";
					
				} else {
					$this->session->set_flashdata('login_failed', true);
					$this->session->sess_destroy();
					$json['is_successful'] 	= false;
					$json['redirect_to'] 	= "login";
					$json['message']		= "Login Failed. Check your username/password.";
				}
				
			} else {
				$this->session->set_flashdata('login_failed', true);
				$this->session->sess_destroy();
				$json['is_successful'] 	= false;
				$json['redirect_to'] 	= "login";
				$json['message']		= "Login Failed. Check your username/password.";
			}
		}
		echo json_encode($json);
	}

	function module_gateway() {
		$session = $this->session->userdata("account_type");
		if($session){
			$account_type = $session;
			redirect("welcome");
			die();
		} else {
			$this->session->sess_destroy();
			redirect("login");
			die();
		}
		
		
	}

	function forgotaccount(){
		Engine::appStyle('welcome-style.css');
		
		Jquery::form();
		Jquery::datatable();
		Jquery::inline_validation();
		Jquery::tipsy();

		$data['page_title'] = "Forgot Password";

		$this->load->view('login/forgotpassword',$data);
	}

	function sendEmailAccountDetails(){
		Engine::appStyle('welcome-style.css');
		
		Jquery::form();
		Jquery::datatable();
		Jquery::inline_validation();
		Jquery::tipsy();
		Jquery::jBox();
		// Engine::XmlHttpRequestOnly();
		$this->load->library('email');
		$post = $this->input->post();

		//$email = $post['email'];

		$username   = $post['email'];
		$data['user'] = $email = User::findActiveUserByUsernameOrEmail($username);

		if ($email) {
			$data['question'] = Verification_Questions::findById(array("id" => $email['verification_question']));		
		} else {
			$data['error'] = "Sorry Email Address/Username not found!";
		}
		$this->load->view('login/random_question',$data);

		/*$config['protocol'] = "smtp";
		$config['smtp_host'] = "ssl://smtp.googlemail.com";
		$config['smtp_port'] = 25;
		$config['smtp_user'] = "johneleazar.perez@gmail.com";
		$config['smtp_pass'] = "kingjohn24";
		$this->email->initialize($config);

		$this->email->from('admin@royalpreventive.com', 'Do Not Reply');
		$this->email->to($email); 

		$this->email->subject('Account Recovery');
		$this->email->message('Testing the email class.');	
		if(!empty($email)){
			if($this->email->send()){ 
				echo "Please check your email.";
			}else{
				show_error($this->email->print_debugger());
			}
			
		}*/

		/*$config['protocol'] = 'smtp';
		$config['smtp_host'] = 'ssl://smtp.googlemail.com';
		$config['smtp_port'] = 465;
		$config['smtp_user'] = 'johneleazar.perez@gmail.com';
		$config['smtp_pass'] = 'kingjohn24';

	
		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");
		
		$this->email->from('johneleazar.perez@gmail.com', 'John');
		$this->email->to($email);		
		$this->email->subject('This is an email test');		
		$this->email->message('It is working. Great!');
		
		
		if($this->email->send())
		{
			echo 'Your email was sent, successfully.';
			echo $this->email->print_debugger();
		}
		
		else
		{
			show_error($this->email->print_debugger());
		}*/
	}

	function check_answer(){
		Engine::appStyle('welcome-style.css');
		
		Jquery::form();
		Jquery::datatable();
		Jquery::inline_validation();
		Jquery::tipsy();
		Jquery::jBox();

		$session 	= $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);
		$post 		= $this->input->post();
		$answer = strtolower($post['answer']);
		
		$data['user'] = $user = User::findById(array("id" => $post['user_id']));
		//$data['question'] = $question = Verification_Questions::findAnswer(array("id" => $post['id'], "answer" => $answer));

		if ($user['verification_answer'] == $answer) {		
			$json['is_successful'] 	= true;
			$json['message']		= "Correct Answer! Proceeding to Change Password...";
			$json['redirect_to']    = 'change_password/' .$user['id'];

		} else {
			$json['is_successful'] 	= false;
			$json['message']		= "Sorry! Wrong Answer. Try Again!";
		}

		echo json_encode($json);
	}

	function change_password(){
		Engine::appStyle('welcome-style.css');
		
		Jquery::form();
		Jquery::datatable();
		Jquery::inline_validation();
		Jquery::tipsy();
		Jquery::jBox();

		$id	= $this->uri->segment(3);
		$data['user'] = $user = User::findById(array("id" => $id));
		
		$this->load->view('login/change_password',$data);
	}

	function save_password(){
		Engine::appStyle('welcome-style.css');
		
		Jquery::form();
		Jquery::datatable();
		Jquery::inline_validation();
		Jquery::tipsy();
		Jquery::jBox();
		
		$post = $this->input->post();
		$user = User::findById(array("id" => $post['user_id']));

		$password 		= $this->encrypt->encode($post['password']);
		$hash 			= Password_Hash::create_hash($post['password']);
		$last_update 	= date("Y-m-d H:i:s",time());

		$record = array(
			"password" 			=> $password,
			"hash" 				=> $hash,
			"last_change_password" 	=> $last_update,
		);

		$user_id = User::save($record,$post['user_id']);

		$json['is_successful'] 	= true;
		$json['message']		= "Successfully Updated Password!";
		$json['redirect_to'] 	= "login";
		echo json_encode($json);
	}

	function user_logout() {
		$this->session->sess_destroy();
		session_destroy();
		redirect("authenticate");

	}

}