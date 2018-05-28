<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	public function __construct()
    {
        parent::__construct();
        $this->load->database();
        Engine::class_loader();
    }

    function isUserLoggedIn() {
		$session = $this->session->userdata('user_id');
		if($session) {
			$decode = $this->encrypt->decode($session);
			$user = User::findById(array("id"=>$decode));
			if($user) {
				
				$credentials = array(
						'user_id' 		=> $this->encrypt->encode($user['id']),
						'firstname' 	=> $user['firstname'],
						'middlename' 	=> $user['middlename'],
						'lastname' 		=> $user['lastname'],
						'name' 			=> $user['firstname'] . ' ' . $user['lastname'],
						'account_type' 	=> $user['account_type'],
					);

				$this->session->set_userdata($credentials);

				return true;
			}
		}

		return false;
	}

	function emailSend($params){
		$this->load->library('email');

		$from_email = $params['from_email'];
		$from_name	= $params['from_name'];

		$to			= $params['to'];
		$subject 	= $params['subject'];
		$message	= $params['message'];

		$this->email->from($from_email, $from_name);
		$this->email->to($to);
		$this->email->subject($subject);
		$this->email->message($message);

		if($this->email->send()){
			return "Email Sent";
		} else {
			return "Email not sent";
		}

	}

	function getAccessPermission($module_id){
		$session 			= $this->session->all_userdata();
		$user_type_id 		= User_Type::findByName( array("user_type" => $session['account_type'] ));
		$permission 		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => $module_id));

		return $permission;
	}
}