<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('login_m');
	}
	
	public function index()
	{
		redirect('login/login_page');
	}
	
	public function login_page(){
		if($this->input->post()){
			$rs = $this->login_m->registration_user();
			
			if($rs == false){
				$this->session->set_userdata(array('error_register'=> "errorregister"));
				
				#to check either error_login session has be set or not if yes it will be unset 
				#if error_register been set,view is register form otherwise login form
				#process view done by login-4.js
				$checkerror = $this->session->userdata('error_login');
				if($checkerror != ""){
					$this->session->unset_userdata('error_login');
				}
				
				$data = $this->input->post();
				$this->load->view('login_v',$data);
			}
			else{
				redirect('login/login_page');
			}
		}
		else{
			$this->load->view('login_v');
		}
	}
	
	public function verify(){
		
		if(!$this->input->post()){
			redirect('login/login_page');
		}
		else{
			
			$check = $this->login_m->verify();
			if($check == false){
				$this->session->set_userdata(array('error_login'=> "errorlogin"));
				
				#to check either error_register session has be set or not if yes it will be unset 
				#if error_login been set,view is login form
				#process view done by login-4.js
				$checkerror = $this->session->userdata('error_register');
				if($checkerror != ""){
					$this->session->unset_userdata('error_register');
				}
				redirect('login/login_page');
			}
			else
			{
				$error_items = array('error_login', 'error_login');
				$this->session->unset_userdata($error_items);
				
				redirect('user/');
			}
			
		}
		
	}
	
	public function adios(){
		
		$this->session->unset_userdata(array('name','username','userid','contact_num','email','register_date')); //the other way to destroy session 
		//$this->session->sess_destroy();
		$this->session->set_flashdata('success','You have successfully signed out. Thank you.');
		redirect('login/login_page');
		
	}
		

}//end of login controller
