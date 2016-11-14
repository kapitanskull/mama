<?php

class Product_m extends CI_Model
{

	function _construct()
	{
		parent::_construct();// Call the Model constructor
	}
	

	
	function addproduct(){
		$image_path = "uploads/";
		$user_name = str_replace(" ","_",$this->session->userdata('name'));
		$folder_name = $this->session->userdata('userid') . "_" . $user_name ;
		
		if($this->input->post()){
		
			$product_name = $this->input->post('product_name');
			$product_price = $this->input->post('product_price');
			$product_commission = $this->input->post('product_commission');
			
			if($product_name == ''){
				$this->set_message("error_p", "Please enter product name.");
				return false;
			}
			
			if($product_name != ''){
				$product_name1 = strtolower($product_name);
				$sql = "SELECT * FROM product WHERE product_name = " . $this->db->escape($product_name1). "AND product_registrar_id = " . $this->db->escape($this->session->userdata('userid'));			
				$query = $this->db->query($sql);
				if($query->num_rows() > 0) {
					$this->set_message("error_p", "This product already exist in this system");
					return false;
				}
			}
			
			if($product_price == ''){
				$this->set_message("error_p", "Please enter product price.");
				return false;
			}
			if(!is_numeric($product_price)){
				$this->set_message("error_p", "Price contain only digits.");
				return false;
			}
			
			if($product_commission == ''){
				$this->set_message("error_p", "Please enter product commision.");
				return false;
			}
			if(!is_numeric($product_commission)){
				$this->set_message("error_p", "Commision contain only digits.");
				return false;
			}
			
			if(is_numeric($product_commission) && is_numeric($product_price)){
				$product_commission = number_format($product_commission,2);
				$product_price = number_format($product_price,2);
				if($product_commission  > $product_price){
					$this->set_message("error_p", "Commision cannot bigger than sell price");
					return false;
				}
			}
			
			if($_FILES["product_image"]["name"]!=''){
				
				if(!is_dir($image_path . $folder_name)){
					mkdir($image_path . $folder_name,0777,TRUE);
				}
				
				$config['upload_path'] = $image_path . $folder_name;				
				$config['allowed_types'] = 'png|jpg';
				$config['max_size'] = '2048'; // 2Mb
				
				$this->load->library('upload', $config);
				
				if (!$this->upload->do_upload('product_image'))
				{
					#case - failure
					$upload_error = $this->upload->display_errors();
					$this->set_message("error_p", $upload_error);
					return false;
				}
				
				else
				{
					#case - success
					$upload_data = $this->upload->data();
					$path = $upload_data['file_name'];
					$DBproduct['product_image_path'] = $image_path . $folder_name . "/" . $path;
					
				}
			}
			
			$DBproduct['product_name'] = strtolower($product_name);
			$DBproduct['product_price'] = number_format($product_price,2);
			$DBproduct['product_commission'] = number_format($product_commission,2);
			$DBproduct['product_registrar_id'] = $this->session->userdata('userid');
  			
			$rs = $this->db->insert('product', $DBproduct);
			$last_insert_id =  $this->db->insert_id();
			
			$color_name = $this->input->post('colour_name');
			
			foreach($color_name as $k => $v){
			
				$colour_name = $color_name[$k];
				
				if($colour_name != ""){
					$DBcolor['colour_name'] = $colour_name ;
				}
				else{
					$DBcolor['colour_name'] = "";
				}
				$DBcolor['product_id'] = $last_insert_id ;
				$DBcolor['registrar_id'] = $this->session->userdata('userid');
				
				$roc = $this->db->insert('product_color_management', $DBcolor);
			}
		
			$this->session->set_flashdata("success","Register product successful.");
			return $roc;
		}
	}
	
	function product_listing(){
		$userid = $this->session->userdata('userid');
		
		$query = $this->db->query("SELECT COUNT(id) AS total FROM product WHERE product_registrar_id=" . $this->db->escape($userid))->row();
		$data['total_rows'] = $query->total;
		
		$config['base_url'] = base_url() . 'product/product_listing/';
		$config['uri_segment'] = 3;
		$config['total_rows'] = $data['total_rows'];
		$config['per_page'] = 7;
		
		$this->pagination->initialize($config);

		$sql = '';
		if($data['total_rows'] > 0) {
			if($this->uri->segment($config['uri_segment']) && is_numeric($this->uri->segment($config['uri_segment'])))
			{
				$sql = ' LIMIT ' . $this->uri->segment($config['uri_segment']) . ', ' . $config['per_page'];
			}
			else
			{
				$sql = ' LIMIT 0, ' . $config['per_page'];
			}
		}
		$query = $this->db->query("SELECT * FROM product WHERE product_registrar_id=" . $this->db->escape($userid) . "ORDER BY id DESC" .  $sql);

		return $query;
	}
	
	function product_color($data = false){
		$all_data = array();
		$i = 1;
		if($data != false){
			if($data->num_rows() > 0){
				foreach ($data->result() as $row){
					$sql = "SELECT * FROM `product_color_management` WHERE `product_id` = " . $this->db->escape($row->id) . "AND `registrar_id` = " . $this->db->escape($this->session->userdata('userid'));
					$query = $this->db->query($sql);
					// $all_data[$row->id] = $query;
					if($query->num_rows() > 0)
					foreach($query->result() as $row2)
					{
						$all_data["colour"][$row2->product_id][] = $row2->colour_name;
					}
				}
			}
		}
		if(count($all_data) > 0){
			return $all_data;
		}
		else{
			return false;
		}	
	}

	function product_remove(){
		$userid = $this->session->userdata('userid');
		
		if($this->input->post()){
			$id = $this->input->post('remove_product_id');
	    
			if($id > 0) {
				$query = $this->db->query("SELECT * FROM product WHERE id=" . $this->db->escape($id) . "AND product_registrar_id =" .$this->db->escape($userid))->row();
				$image_path = $query->product_image_path;
				
				if($image_path != "")
				{
					unlink($image_path);
				}
				$sql = "DELETE FROM product WHERE id = " . $this->db->escape($id) . " LIMIT 1";
				$rs = $this->db->query($sql);
				// $this->audit_trail($this->db->last_query(), 'user_m.php', 'customer_remove()', 'Delete User');
				
				return $rs;
			}
		}
	}
	
	function get_product($id = 0){
		$userid = $this->session->userdata('userid');
		
		if($id > 0){
			$query = $this->db->query("SELECT * FROM product WHERE id=" . $this->db->escape($id) . "AND product_registrar_id=" . $this->db->escape($userid));
			
			if($query->num_rows() > 0){
				return $query->row_array();
			}
		}
	}
	
	function save_product(){
		$image_path = "uploads/";
		$user_name = str_replace(" ","_",$this->session->userdata('name'));
		$folder_name = $this->session->userdata('userid') . "_" . $user_name ;
		
		if($this->input->post()){
			
			$id = $this->input->post('product_id');
			$product_name = $this->input->post('product_name');
			$product_price = $this->input->post('product_price');
			$product_commission = $this->input->post('product_commission');
			
			if($product_name == ''){
				$this->set_message("error_edit", "Please enter product name.");
				return false;
			}
			
			if($product_price == ''){
				$this->set_message("error_edit", "Please enter product price.");
				return false;
			}
			if(!is_numeric($product_price)){
				$this->set_message("error_edit", "Price contain only digits.");
				return false;
			}
			
			if($product_commission == ''){
				$this->set_message("error_edit", "Please enter product commision.");
				return false;
			}
			if(!is_numeric($product_commission)){
				$this->set_message("error_edit", "Commision contain only digits.");
				return false;
			}
			
			if(is_numeric($product_commission) && is_numeric($product_price)){
				$product_commission = number_format($product_commission,2);
				$product_price = number_format($product_price,2);
				if($product_commission  > $product_price){
					$this->set_message("error_edit", "Commision cannot bigger than sell price");
					return false;
				}
			}
			
			if($_FILES["product_image"]["name"]!=''){
				
				if(!is_dir($image_path . $folder_name)){
					mkdir($image_path . $folder_name,0777,TRUE);
				}
				
				$config['upload_path'] = $image_path . $folder_name;				
				$config['allowed_types'] = 'png|jpg';
				$config['max_size'] = '2048'; // 2Mb
				
				$this->load->library('upload', $config);
				
				if (!$this->upload->do_upload('product_image'))
				{
					#case - failure
					$upload_error = $this->upload->display_errors();
					$this->set_message("error_edit", $upload_error);
					return false;
				}
				
				else
				{
					#case - success
					$upload_data = $this->upload->data();
					$path = $upload_data['file_name'];
					$DBproduct['product_image_path'] = $image_path . $folder_name . "/" . $path;
				}
			}
			
			$DBproduct['product_name'] = $product_name;
			$DBproduct['product_price'] = number_format($product_price,2);
			$DBproduct['product_commission'] = number_format($product_commission,2);
			  			
			$this->db->where('id', $id);
	    	$rs = $this->db->update('product', $DBproduct);
			$this->session->set_flashdata("success_edit","Upadate product details successful.");
			
			return $rs;
		}
	}
	
	function set_message($status,$mesej)
	{
		$this->session->set_flashdata($status,$mesej);
	}
	
	function audit_trail($last_query,$filename,$function,$action_desc,$userid){
		$data['last_query'] = $last_query;
		$data['filename'] = $filename;
		$data['function'] = $function;
		$data['action_desc'] = $action_desc;
		$modify_date = date("Y-m-d H:i:s");
		$data['modify_date'] = $modify_date;
		$data['userid'] = $userid;
		
		$this->db->insert('audit_trail', $data);
	}

}// end of class Model_users extend CI_Model

