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
		
		// echo "<pre>"; 
		// print_r($data); 
		// echo "</pre>";
		// exit;
		$this->load->view('product/product_listing_v',$data);
	}
	
	public function add_product(){
		$data['main_breadcrumb'] = "Product";
		$data['submain_breadcrumb1'] = "Add Product";
		$data['sub_nav_active'] = "add_product";
		$data['title_page'] = "Add Product";
		if($this->input->post()){
			$rc = $this->product_m->addproduct();
			
			if($rc == false){
				$data['post'] = $this->input->post();
				
				$this->load->view('product/add_product_v',$data);
			}
			else{
			
				redirect('product/product_listing');
			}
		}
		else{
			$this->load->view('product/add_product_v',$data);
		}
	}
	
	public function product_remove(){
		
		$rs = $this->product_m->product_remove();
		if($rs === false) 
			$this->session->set_flashdata("error","Delete failed. Data has not changed");
		else 
			$this->session->set_flashdata("success","Product has been deleted.");
		
		redirect('product/product_listing/');
	}
	
	public function edit($id = 0){
		
		$data['main_breadcrumb'] = "Product";
		$data['submain_breadcrumb1'] = "Edit Product";
		$data['title_page'] = "Edit Product";
		
		if($id > 0){
			if($this->input->post()){
				$rs = $this->product_m->save_product();
				
				if($rs == false){
					$data['post'] = $this->input->post();
				
					$this->load->view('product/edit_product_v',$data);
				}
				else{
					redirect('product/edit/' . $id);
				}
			}
			else{
				$data['post'] = $this->product_m->get_product($id);
		
				$this->load->view('product/edit_product_v',$data);
			}
		}
		else
			redirect("product/product_listing");
	}

}//end of login controller
