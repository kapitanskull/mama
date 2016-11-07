<?php

class Login_m extends CI_Model
{

	function _construct()
	{
		parent::_construct();// Call the Model constructor
	}
	
	#register new user
	function registration_user() 
	{
	
		$DBdata = array(
			'name' => trim($this->input->post('name')),
			'email' => trim($this->input->post('email')),
			'contact_num' => trim($this->input->post('contact_num')),
			'username' =>trim($this->input->post('username')),
		);
		//rpassword
		
		if($DBdata['name'] == '') {
		  $this->set_message("error", "Please enter your name.");
		  return false;
		}
		
		if($DBdata['name'] != ''){
			$DBdata['name'] = ucwords(strtolower($DBdata['name']));
		}
		
		if($DBdata['email'] == '') {
		  $this->set_message("error", "Please enter your email.");
		  return false;
		}
		
		if($DBdata['email'] != ''){
			if (!filter_var($DBdata['email'], FILTER_VALIDATE_EMAIL)) {
				$this->set_message("error", "Invalid email format");
				return false;
			}
		}
		
		if($DBdata['contact_num'] == '') {
		  $this->set_message("error", "Please enter your contact number.");
		  return false;
	    }
		if(!is_numeric($DBdata['contact_num'])) {
			$this->set_message("error", "Contact can only contain number");
			return false;
		}

		if($DBdata['username'] == '') {
		  $this->set_message("error", "Please enter your username.");
		  return false;
	    }
		if($DBdata['username'] != '') {
			$sql = "SELECT * FROM user WHERE username = " . $this->db->escape($DBdata['username']);			
		    $query = $this->db->query($sql);
			if($query->num_rows() > 0) {
				$this->set_message("error", "Username is taken. Please try other Username.");
				return false;
			}
	    }
		
		if($this->input->post('password') == ''){
			$this->set_message("error", "Please enter password.");
			return false;
		}
		if($this->input->post('password') != '') {
	    	if(md5(trim($this->input->post('rpassword'))) != md5(trim($this->input->post('password')))) {
				$this->set_message("error", "Re-type Password does not match. Please try again.");
	    		return false;
	    	}
	    	$DBdata['password'] = md5(trim($this->input->post('password')));
    	}
		$DBdata['register_date'] = date("Y-m-d");
		
	   #Storing the date into DB. Using Active Record.
		$rs = $this->db->insert('user', $DBdata);
		$insert_id = $this->db->insert_id();
		$this->audit_trail($this->db->last_query(), 'login_m.php', 'registration_user()', 'Register User',$insert_id);
		
		if($rs == true && $insert_id > 0){
			$sql = "SELECT * FROM table_running_number WHERE id = 1";
			$query = $this->db->query($sql);
			if($query->num_rows() > 0){
				$trnrow = $query->row();
				$dtn['userid'] = "MACCMNGT" . $trnrow->running_number;
				
				$this->db->where('id', $insert_id);
				$rn = $this->db->update('user', $dtn);
				$this->audit_trail($this->db->last_query(), 'login_m.php', 'registration_user()', 'Update User id',$insert_id);
				
				if($rn == true)
				{
					$sql = "UPDATE `table_running_number` SET running_number = running_number+1 WHERE id = 1";
					$query = $this->db->query($sql);
					$this->set_message("success","Registeration success !");
					
					return $rn;
				}
			}
		}
		
	}
	
	function verify(){
		
		$username = $this->input->post('username');
		$password = $this->input->post('password');
			
		if (empty($username) OR empty($password)) {
			$this->set_message('error','Please enter username and password.');
			return false;
		}
		
		if(isset($username) && $username != "" && isset($password) && $password != ""){
			
			$sql = "SELECT * FROM user WHERE username = " . $this->db->escape($username) . " AND password = " . $this->db->escape(md5($password)) . " LIMIT 1";
			$query = $this->db->query($sql);
			
			if($query->num_rows() > 0){
				$row = $query->row();
				$this->session->set_userdata(array('name'=> $row->name, 'username' => $row->username, 'userid' => $row->userid, 'contact_num' => $row->contact_num, 'email' => $row->email, 'register_date' => $row->register_date));
				return true;
			}
			else{
				$this->set_message('error','Invalid username and password.');
				return false;
			}
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

