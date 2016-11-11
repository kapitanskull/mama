<?php

class User_m extends CI_Model
{

	function _construct()
	{
		parent::_construct();// Call the Model constructor
	}
	
	function addcustomer(){
		
		 $DBdata = array(
			'customer_name' => trim($this->input->post('customer_name')),
			'customer_contact_no' => trim($this->input->post('customer_contact_no')),
			'customer_order_date' =>trim($this->input->post('customer_order_date')),
			'customer_address' =>trim($this->input->post('customer_address')),
			'customer_city' =>trim($this->input->post('customer_city')),
			'customer_state' =>trim($this->input->post('customer_state')),
			'customer_postcode' =>trim($this->input->post('customer_postcode')),
			'customer_country' =>trim($this->input->post('customer_country'))
		);
	
		if($DBdata['customer_name'] == ''){
			$this->set_message("error", "Please enter customer name.");
			return false;
		}
		if($DBdata['customer_name'] != ''){
			$DBdata['customer_name'] = ucwords(strtolower($DBdata['customer_name']));
		}
		
		if($DBdata['customer_contact_no'] == ''){
			$this->set_message("error", "Please enter customer contact number.");
			return false;
		}
		if(!is_numeric($DBdata['customer_contact_no'])){
			$this->set_message("error", "Contact number contains only digit.");
			return false;
		}
		if($DBdata['customer_contact_no'] != ''){
			$contact_number = str_replace("-","",$DBdata['customer_contact_no']);
			if(strlen($contact_number) > 12 || strlen($contact_number) < 10 )
			{
				$this->set_message("error", "Invalid contact number.");
				return false;
			}
			else{
				$firstthree = substr($contact_number,0,3);
				$next = substr($contact_number,3,7);
				$DBdata['customer_contact_no'] = $firstthree . "-" . $next;
			}
		}
		
		if($DBdata['customer_order_date'] == ''){
			$this->set_message("error", "Please select order date.");
			return false;
		}
		if($DBdata['customer_order_date'] != ''){
			$date = explode("-",$DBdata['customer_order_date']);
			$order_date = array($date[2], $date[1], $date[0]);
			
			$DBdata['customer_order_date'] = implode("-",$order_date);
		}
	
		if($DBdata['customer_address'] == ''){
			$this->set_message("error", "Please enter address.");
			return false;
		}
		
		if($DBdata['customer_city'] == ''){
			$this->set_message("error", "Please enter city.");
			return false;
		}
		
		if($DBdata['customer_state'] == ''){
			$this->set_message("error", "Please enter state.");
			return false;
		}
		
		if($DBdata['customer_postcode'] == ''){
			$this->set_message("error", "Please enter postcode.");
			return false;
		}
		
		if(!is_numeric($DBdata['customer_postcode'])){
			$this->set_message("error", "Post code contains only digit.");
			return false;
		}
		
		if($DBdata['customer_country'] == ''){
			$this->set_message("error", "Please select country.");
			return false;
		}
		
		$query = $this->db->query("SELECT running_number FROM table_running_number WHERE id=2"); 
		$tr = $query->row();
		$trasanction_no = "TRN" . $tr->running_number;
			
		$products_id = $this->input->post('customer_order_product_id');
		foreach($products_id as $k => $v){
			
			$p_id = $products_id[$k];
			$product_price = $this->input->post('product_price[' . $k . ']' );
			$product_quantity =  $this->input->post('product_quantity[' . $k . ']');
			
			if($p_id == "" || $product_price == "" || $product_quantity == "" ){
				$this->set_message("error", "Order details must not be empty");
				return false;
			}
			
			if($p_id != "" ){
				$query = $this->db->query("SELECT product_commision FROM product WHERE id=" . $this->db->escape($p_id))->row(); 
				$DBordered['transaction_commision']= $query->product_commision;
			}
			
			if($product_quantity != "" && !is_numeric($product_quantity)){
				$this->set_message("error", "Quantity contain only digit");
				return false;
			}
			
			if($product_quantity < 0){
				$this->set_message("error", "Quantity must more than 0");
				return false;
			}
			$totpayment = number_format($product_price,2) * $product_quantity;
			
			$DBordered['product_id'] = $p_id;
			$DBordered['order_quantity'] = $product_quantity;
			$DBordered['total_price'] = number_format($totpayment,2);
			$DBordered['transaction_number'] = $trasanction_no;
			$DBordered['transaction_date'] = $DBdata['customer_order_date'];
			$rod = $this->db->insert('order_transaction', $DBordered);
		}
		
		$userid = $this->session->userdata('userid');
		$DBdata['customer_registrar_userid'] = $userid;
		$DBdata['customer_order_status'] = 'Ordered';
		$DBdata['customer_transaction_num'] = $trasanction_no;
		$rs = $this->db->insert('customer', $DBdata);
		$insert_id = $this->db->insert_id();
		
		if($rs == true){
			$DBortrans['customer_id'] = $insert_id;
			$this->db->where('transaction_number', $DBdata['customer_transaction_num']);
	    	$otr = $this->db->update('order_transaction', $DBortrans);
			
			if($otr == true){
				$this->db->query("UPDATE table_running_number SET running_number = running_number+1 WHERE id=2"); 
				return true;
			}
		}
		$this->audit_trail($this->db->last_query(), 'user_m.php', 'registration_customer()', 'Register Customer',$insert_id);
	}
	
	function getProductDetails(){
		$sql = "SELECT * FROM product";
		$query = $this->db->query($sql);
		
		if($query->num_rows() > 0){
			return $query;
		}
	}
	
	function customer_listing(){
		$userid = $this->session->userdata('userid');
		
		$query = $this->db->query("SELECT COUNT(id) AS total FROM customer WHERE customer_registrar_userid=" . $this->db->escape($userid))->row();
		$data['total_rows'] = $query->total;
		
		$config['base_url'] = base_url() . 'user/listing/';
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
		$query = $this->db->query("SELECT * FROM customer ORDER BY id DESC" .  $sql);

		return $query;
	}
	
	function product_name( $data = false){
		
		$all_data = array();
		if($data != false){
			if($data->num_rows() > 0){
				foreach ($data->result() as $row){
					$sql = "SELECT * FROM `order_transaction` WHERE `transaction_number` = " . $this->db->escape($row->customer_transaction_num) . "";
					$query = $this->db->query($sql);
					$all_data[$row->id] = $query;
					if($query->num_rows() > 0){
						foreach ($query->result() as $row_b){
							$sql = "SELECT * FROM `product` WHERE `id` = " . $this->db->escape($row_b->product_id) . "";
							$query = $this->db->query($sql);
							if($query->num_rows() > 0){
								foreach ($query->result() as $row_c){
									$all_data["name"][$row_b->product_id] = $row_c->product_name;
								}
							}
						}
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
	
	 function customer_remove()
    {
    	$id = $this->input->post('remove_user_id');
	    
		if($id > 0) {
			$sql = "DELETE FROM customer WHERE id = " . $this->db->escape($id) . " LIMIT 1";
			$rs = $this->db->query($sql);
			// $this->audit_trail($this->db->last_query(), 'user_m.php', 'customer_remove()', 'Delete User');
			
			return $rs;
		}
		
		return false;
	}
	
	function getProductPrice(){
		$id = $this->input->post('product_id');
		if($id > 0){
			$sql = "SELECT product_price FROM product WHERE id = " . $this->db->escape($id) . " LIMIT 1";
			$query = $this->db->query($sql);
			$row = $query->row();
			return $row->product_price;
		}
		
	}
	
	function addproduct(){
		$image_path = "uploads/";
		$folder_name = $this->session->userdata('userid') . "_" . $this->session->userdata('name');
		
		if($this->input->post()){
		
			$product_name = $this->input->post('product_name');
			$product_price = $this->input->post('product_price');
			$product_commission = $this->input->post('product_commission');
			
			if($product_name == ''){
				$this->set_message("error", "Please enter product name.");
				return false;
			}
			
			if($product_price == ''){
				$this->set_message("error", "Please enter product price.");
				return false;
			}
			if(!is_numeric($product_price)){
				$this->set_message("error", "Price contain only digits.");
				return false;
			}
			
			if($product_commission == ''){
				$this->set_message("error", "Please enter product commision.");
				return false;
			}
			if(!is_numeric($product_commission)){
				$this->set_message("error", "Commision contain only digits.");
				return false;
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
					$this->set_message("error", $upload_error);
					return false;
				}
				
				else
				{
					#case - success
					$upload_data = $this->upload->data();
					$path = $upload_data['file_name'];
					$DBproduct['product_image_path'] = $folder_name. "/" .$path;
					
				}
			}
			
			$DBproduct['product_name'] = $product_name;
			$DBproduct['product_price'] = number_format($product_price,2);
			$DBproduct['product_commision'] = number_format($product_commission,2);
			$DBproduct['product_registrar_id'] = $this->session->userdata('userid');
  			
			$rs = $this->db->insert('product', $DBproduct);
			
			return $rs;
		}
	}
	
	function product_listing(){
		
		$userid = $this->session->userdata('userid');
		
		$query = $this->db->query("SELECT COUNT(id) AS total FROM product WHERE product_registrar_id=" . $this->db->escape($userid))->row();
		$data['total_rows'] = $query->total;
		
		$config['base_url'] = base_url() . 'user/product_listing/';
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
		$query = $this->db->query("SELECT * FROM product ORDER BY id DESC" .  $sql);

		return $query;
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

