<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		//$this->load->model('login_m');
		//$this->load->library('phpsession');
		
		if(!$this->session->userdata('userid') || !$this->session->userdata('username') )
		{
			redirect('login/login_page');
		}
		$this->load->model('product_m');
	}
	
	public function index()
	{
		redirect('product/product_listing');
	}
	public function product_listing(){
		$data['main_breadcrumb'] = "Product";
		$data['submain_breadcrumb1'] = "Product Listing";
		$data['sub_nav_active'] = "product_listing";
		
		$data['product_query'] = $this->product_m->product_listing();
		$data['color_query'] = $this->product_m->product_color($data['product_query']);
		$data['pagination'] = $this->pagination->create_links();
		
		$this->load->view('product/product_listing_v',$data);
	}
	
	public function form($id = 0){
		
		if($this->input->post()){
			$rc = $this->product_m->addproduct();
			
			if($rc == false){
				$data = $this->input->post();
			}
			else{
				redirect('product/form/'. $rc);
			}
		}
		if($id > 0){
			$data = $this->product_m->get_product($id);
			
			#to convert certain character into html character.eg test!@#$%^&*())"'nama ni if did not use htmlentities , the output is test!@#$%^&*()) 
			$data = array_map('htmlentities', $data); 
			$data['color_query'] = $this->product_m->get_color($id);
			$data['submain_breadcrumb1'] = "Edit Product";
			$data['title_page'] = "Edit Product";
		
		}
		else{
			$data['submain_breadcrumb1'] = "Add Product";
			$data['sub_nav_active'] = "add product";
			$data['title_page'] = "Add Product";
		}
		
		$data['main_breadcrumb'] = "Product";
		
		$this->load->view('product/form_product_v',$data);
		
	}
	
	public function product_remove(){
		
		$rs = $this->product_m->product_remove();
		if($rs === false) 
			$this->session->set_flashdata("error","Delete failed. Data has not changed");
		else 
			$this->session->set_flashdata("success","Product has been deleted.");
		
		redirect('product/product_listing/');
	}
	
	public function remove_productcolor(){
		$rc = $this->product_m->product_color_remove();
		echo $rc;
	}
	

}//end of login controller
