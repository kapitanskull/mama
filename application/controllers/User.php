<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		//$this->load->model('login_m');
		//$this->load->library('phpsession');
		
		if(!$this->session->userdata('userid') || !$this->session->userdata('username') )
		{
			redirect('login/login_page');
		}
		$this->load->model('user_m');
	}
	
	public function index()
	{
		redirect('user/home');
	}
	
	public function home(){
		$data['main_breadcrumb'] = "Dashboard";
		$this->load->view('user/user_dashboard_v',$data);
	}
	
	public function add(){
		$data['product_query'] = $this->user_m->getProductDetails();
		
		$data['main_breadcrumb'] = "Dashboard";
		$data['submain_breadcrumb1'] = "Customer";
		$data['submain_breadcrumb2'] = "Register Customer";
		$data['current_process'] = "add";
		#For side bar active-highlight for current selected menu which by trigger "active open" in class = "nav-item"   
		$data['sub_nav_active'] ="register_customer";
		
		if($this->input->post()){
			$rc = $this->user_m->addcustomer();
			
			if($rc == false){
				$data['post'] = $this->input->post();
			
				$this->load->view('user/add_customer_v',$data);
			}
			else{
				redirect('user/listing');
			}
		}
		else{
			// $data['product_query'] = $this->user_m->getProductDetails();
			$this->load->view('user/add_customer_v',$data);
		}
	}
	
	public function listing(){
		$data['cust_query'] = $this->user_m->customer_listing();
		$data['product_query'] = $this->user_m->product_name($data['cust_query']);
		$data['pagination'] = $this->pagination->create_links();
		
		$data['main_breadcrumb'] = "Order";
		$data['submain_breadcrumb1'] = "Order Listing";
		
		$this->load->view('user/order_listing_v',$data);
	}
	
	public function edit($id = null){
		
		// if($id > 0){
			// $data['cust_query'] = $this->user_m->edit_customer($id);
		// }
		$data['product_query'] = $this->user_m->getProductDetails();
		$data['current_process'] = "edit";
		$data['main_breadcrumb'] = "Order";
		$data['submain_breadcrumb1'] = "Customer Update";
		$this->load->view('user/edit_product_v',$data);
		
	}
	
	public function customer_remove(){
		
		$rs = $this->user_m->customer_remove();
		if($rs === false) 
			$this->session->set_flashdata("error","Delete failed. Data has not changed");
		else 
			$this->session->set_flashdata("success","User has been deleted.");
		
		redirect('user/listing/');
	}
	
	public function getProductPrice(){
		if($this->input->post()){
			$data =  $this->user_m->getProductPrice();
			echo $data;
		}
		
	}

}//end of login controller
