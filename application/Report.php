<?php
/**
 * @package			MRC iCap
 * @author			Affendy Iskandar <fendy@logicwise.com.my> (012-2886342) LOGICWISE SDN BHD fb.com/logicwise instagram.com/logicwise
 * @date			16 Feb 2017
 * @copyright		Copyright (c) Motordata Research Consortium Malaysia
 * @modified by		Haiqal Halim
 * @modified date	1 June 2017
 * @filesource
 */
 
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

	public function __construct()
   	{
        parent::__construct();
        
        security_checking();
		#Auto load the required model
 		$this->load->model('mvd/report_m');
		
		//$this->output->enable_profiler(TRUE);
	}
	
	public function index()
	{
		redirect('mvd/report/api_usage');
	}
	
	public function api_usage($type = 'html')
	{
		$meta['title'] = 'API Usage Report';
		$meta['sub_title'] = 'Parts Price Retrieval Log Details';
		$meta['breadcrumb_arr'] = array('API Usage Report' => 'mvd/report/api_usage');
		$data = $this->report_m->get_api_usage($type);
		$data['meta'] = $meta;
		$data['table_fields'] = $this->report_m->get_fields('api_history');
		
		$this->load->view('mvd/api_usage_listing_v', $data);
	}
	
	public function search_api_usage($type = 'html', $base64 = '')
	{
		if($_POST) {
			$serialized['search_str'] = $this->input->post('search_str');
			$serialized['search_column'] = $this->input->post('search_column');
			$serialized['sql_sort_column'] = $this->input->post('sql_sort_column');
			$serialized['sql_sort'] = $this->input->post('sql_sort');
			$serialized['start_date'] = trim(datepicker2mysql($this->input->post('start_date')));
			$serialized['end_date'] = trim(datepicker2mysql($this->input->post('end_date')));
			
			redirect('mvd/report/search_api_usage/' . $type . '/' . trim(base64_encode(serialize($serialized)), "=."));
		}
		
		
		if($base64 == '')
			redirect('mvd/report/api_usage/');
		
		$unserialized = unserialize(base64_decode($base64));
		$meta['title'] = 'API Usage Report';
		$meta['sub_title'] = 'Parts Price Retrieval Log Details';
		$meta['breadcrumb_arr'] = array('API Usage Report' => 'mvd/report/api_usage');
		
		$data = $this->report_m->get_api_usage_search($type, $unserialized);
		$data['meta'] = $meta;
		$data['table_fields'] = $this->report_m->get_fields('api_history');
		
		$this->load->view('mvd/api_usage_listing_v', $data);
	}
	
	public function ppc_usage($type = 'html')
	{
		$meta['title'] = 'PPC Usage Report';
		$meta['sub_title'] = 'Web Parts Price Check Log Details';
		$meta['breadcrumb_arr'] = array('PPC Usage Report' => 'mvd/report/ppc_usage');
		$data = $this->report_m->get_ppc_usage($type);
		$data['meta'] = $meta;
		$data['table_fields'] = $this->report_m->get_fields('api_ppc_history');
		
		$this->load->view('mvd/ppc_usage_listing_v', $data);
	}
	
	public function search_ppc_usage($type = 'html', $base64 = '')
	{
		if($_POST) {
			$serialized['search_str'] = $this->input->post('search_str');
			$serialized['search_column'] = $this->input->post('search_column');
			$serialized['sql_sort_column'] = $this->input->post('sql_sort_column');
			$serialized['sql_sort'] = $this->input->post('sql_sort');
			$serialized['start_date'] = trim(datepicker2mysql($this->input->post('start_date')));
			$serialized['end_date'] = trim(datepicker2mysql($this->input->post('end_date')));
			$serialized['industry_type'] = $this->input->post('industry_type');
			
			redirect('mvd/report/search_ppc_usage/' . $type . '/' . trim(base64_encode(serialize($serialized)), "=."));
		}
		
		
		if($base64 == '')
			redirect('mvd/report/ppc_usage/');
		
		$unserialized = unserialize(base64_decode($base64));
		$meta['title'] = 'PPC Usage Report';
		$meta['sub_title'] = ' Web Parts Price Check Log Details';
		$meta['breadcrumb_arr'] = array('PPC Usage Report' => 'mvd/report/ppc_usage');
		
		$data = $this->report_m->get_ppc_usage_search($type, $unserialized);
		$data['meta'] = $meta;
		$data['table_fields'] = $this->report_m->get_fields('api_ppc_history');
		
		$this->load->view('mvd/ppc_usage_listing_v', $data);
	}
	
	function total_parts($id = 0, $type = 'html')
	{
		$data = $this->report_m->get_parts_details($id, $type);
		$data['table_fields'] = $this->report_m->get_fields('api_history_parts');
		$this->load->view('mvd/api_usage_parts_details', $data);
	}
	
	public function billing($type = 'html')
	{
		$meta['title'] = 'Parts Price Retrieval Billing';
		$meta['sub_title'] = 'Finance Report';
		$meta['breadcrumb_arr'] = array('Finance Report' => 'mvd/report/billing');
		
		// $data = $this->report_m->get_billing($type);
		$data['meta'] = $meta;
		
		$this->load->view('mvd/report_billing_listing_v', $data);
	}
	
	public function search_billing($type = 'html', $base64 = '')
	{
		
		if($_POST) {
			$serialized['claim_type'] = $this->input->post('claim_type');
			$serialized['billed_type'] = $this->input->post('billed_type');
			$serialized['software_house'] = $this->input->post('software_house');
			$serialized['start_date'] = trim(datepicker2mysql($this->input->post('start_date')));
			$serialized['end_date'] = trim(datepicker2mysql($this->input->post('end_date')));
			
			redirect('mvd/report/search_billing/' . $type . '/' . trim(base64_encode(serialize($serialized)), "=."));
		}
		
		if($base64 == '')
			redirect('mvd/report/billing/');
		
		$unserialized = unserialize(base64_decode($base64));
		
		$meta['title'] = 'Parts Price Retrieval Billing';
		$meta['sub_title'] = 'Finance Report';
		$meta['breadcrumb_arr'] = array('Finance Report' => 'mvd/report/billing');
		
		$data = $this->report_m->get_billing_search($type, $unserialized);
		$data['meta'] = $meta;

		$this->load->view('mvd/report_billing_listing_v', $data);
	}
	
	public function confirm_billing($type = 'html', $base64 = '')
	{
		if($_POST) {
			$serialized['claim_type'] = $this->input->post('claim_type');
			$serialized['software_house'] = $this->input->post('software_house');
			$serialized['start_date'] = trim(datepicker2mysql($this->input->post('start_date')));
			$serialized['end_date'] = trim(datepicker2mysql($this->input->post('end_date')));
			
			redirect('mvd/report/confirm_billing/' . $type . '/' . trim(base64_encode(serialize($serialized)), "=."));
		}
		
		if($base64 == '')
			redirect('mvd/report/billing/');
		
		$unserialized = unserialize(base64_decode($base64));
		$this->report_m->confirm_billing($type, $unserialized);
		
		redirect('mvd/report/search_billing/' . $type . '/' . $base64);
	}
	
	public function billing_statement()
	{
		$meta['title'] = 'Parts Price Retrieval Billing';
		$meta['sub_title'] = 'Generated Billing';
		$meta['breadcrumb_arr'] = array('Finance Report' => 'mvd/report/billing', "Generated Billing" => "mvd/report/billing_history");
		
		 $data = $this->report_m->get_billing_statement($type);
		$data['meta'] = $meta;
		
		$this->load->view('mvd/report_billing_statement_listing_v', $data);
	}
	
	public function download_billing($id)
	{
		if($id > 0) {
			$this->load->helper('download');
		
			$path = './uploads/billing/' . $id . '.xls';
			$data = file_get_contents($path);
			
			$row_st = $this->report_m->get_billing_statement_by_id($id);
			
			if(is_object($row_st)) {
				$generated_date = $row_st->created_date;
			}
			
			force_download('Parts_Price_Billing_' . $generated_date . '.xls', $data);
		}
		
		set_messsage("Error: No such file found.", 'danger');
		redirect('mvd/report/billing_statement/');
	}
	
	public function billing_rate()
	{
		$meta['title'] = 'Parts Price Retrieval Billing';
		$meta['sub_title'] = 'Billing Rate';
		$meta['breadcrumb_arr'] = array('Finance Report' => 'mvd/report/billing', "Billing Rate" => "mvd/report/billing_rate");
		
		if($_POST) {
			$rs = $this->report_m->add_rate();

			#Return with validation error
			if($rs === false) {
				$data = $_POST;
				
				$data['meta'] = $meta;
				$this->load->view('mvd/report_billing_rate_v', $data);
			}
			
			#Successfully saved and redirect back to this function without $_POST
			else {
				set_message("New rate succefully saved. Thank you.", "success");
				redirect('mvd/report/billing/');
			}
		}
		else {
			$data['rate'] = $this->report_m->get_billing_rate();
			$data['meta'] = $meta;
			
			$this->load->view('mvd/report_billing_rate_v', $data);
		}
	}
	
	public function software_house($type = 'html'){
		$meta['title'] = 'Software House';
		$meta['sub_title'] = 'Graph';
		$meta['breadcrumb_arr'] = array('Software House' => 'mvd/report/management');
		$data['meta'] = $meta;
		$data['table_fields'] = $this->report_m->get_fields('api_history');
		$data['start_date'] = date("Y-m-d");
		$this->load->view('mvd/management_listing_v', $data);
	}
	
	public function software_house_graphs_data(){
		if($_POST) {
			$timedate = date('H:i:s');
			$start_date = trim(datepicker2mysql($this->input->post('start_date')));
			$end_date = trim(datepicker2mysql($this->input->post('end_date')));
			if($start_date == "" && $end_date == ""){
				$start_date = date("Y-m-d");
			}
			else if($start_date == "" && $end_date != ""){
				$start_date = $end_date;
				$end_date = "";
			}
			else if($start_date == $end_date){
				$end_date = "";
			}
			// get data for repairer
			$data['parts'] = $this->report_m->get_software_house_data($timedate,$start_date,$end_date);
			
			// Get String for date and time
			$start_date2 = $start_date != "" ? date("d/m/Y", strtotime($start_date)) : '';
			$end_date2 = $end_date != "" ? date("d/m/Y", strtotime($end_date)) : '';
			$date_time = "";
			if($end_date2 == ""){
				if($start_date2 == date("d/m/Y"))
					$date_time = "on <b>" . $start_date2 . " " . date('h:i:s A', strtotime($timedate)) . "</b>";
				else
					$date_time = "on <b>" . $start_date2 . "</b>";
			}
			else
				$date_time = "from <b>" . $start_date2 . "</b> to <b>" . $end_date2 . "</b>";
			
			$json_data["load_view"] = $this->load->view('mvd/software_house_graphs_v', $data, true);
			$json_data["date_time"] = $date_time;
			$json_data["start_date"] = $start_date;
			$json_data["end_date"] = $end_date;
			$json_data["timedate"] = $timedate;
			
			echo json_encode($json_data);
		}
	}
	
	public function parts_category($type = 'html'){
		$meta['title'] = 'Parts Category';
		$meta['sub_title'] = 'Graph';
		$meta['breadcrumb_arr'] = array('Parts Category' => 'mvd/report/parts_category');
		$data['meta'] = $meta;
		$data['table_fields'] = $this->report_m->get_fields('api_history');
		$data['start_date'] = date("Y-m-d");
		$this->load->view('mvd/parts_category_v', $data);
	}
	
	public function parts_category_graphs_data(){
		if($_POST) {
			$timedate = date('H:i:s');
			$start_date = trim(datepicker2mysql($this->input->post('start_date')));
			$end_date = trim(datepicker2mysql($this->input->post('end_date')));
			if($start_date == "" && $end_date == ""){
				$start_date = date("Y-m-d");
			}
			else if($start_date == "" && $end_date != ""){
				$start_date = $end_date;
				$end_date = "";
			}
			else if($start_date == $end_date){
				$end_date = "";
			}
			//get parts category data
			$data['parts'] = $this->report_m->get_parts_category_data($timedate,$start_date,$end_date);
			
			// Get String for date and time
			$start_date2 = $start_date != "" ? date("d/m/Y", strtotime($start_date)) : '';
			$end_date2 = $end_date != "" ? date("d/m/Y", strtotime($end_date)) : '';
			$date_time = "";
			if($end_date2 == ""){
				if($start_date2 == date("d/m/Y"))
					$date_time = "on <b>" . $start_date2 . " " . date('h:i:s A', strtotime($timedate)) . "</b>";
				else
					$date_time = "on <b>" . $start_date2 . "</b>";
			}
			else
				$date_time = "from <b>" . $start_date2 . "</b> to <b>" . $end_date2 . "</b>";
			
			$json_data["load_view"] = $this->load->view('mvd/parts_category_graphs_v', $data, true);
			$json_data["date_time"] = $date_time;
			$json_data["start_date"] = $start_date;
			$json_data["end_date"] = $end_date;
			$json_data["timedate"] = $timedate;
			
			echo json_encode($json_data);
		}
	}
	
	public function parts_category_details(){
		if($_POST){
			$timedate = trim($this->input->post('timedate'));
			$code = trim($this->input->post('code'));
			$description = trim($this->input->post('description'));
			$start_date = trim(datepicker2mysql($this->input->post('start_date')));
			$end_date = trim(datepicker2mysql($this->input->post('end_date')));
			if($start_date == date("Y-m-d")){
				$where = "";
			}
			else{
				if(($end_date == $start_date || $end_date == "") && $start_date != ""){
					$where .= ($where == "" ? " WHERE " : " AND ") . " ( `create_date` LIKE " . $this->db->escape($start_date. "%") . ") ";
				}
				else if($start_date != ""){
					$end_date2 = date("Y-m-d", strtotime($end_date . " + 1 days"));
					$where .= ($where == "" ? " WHERE " : " AND ") . " ( `create_date` BETWEEN " . $this->db->escape($start_date) . " 
								AND " . $this->db->escape($end_date2) . ") ";
				}
			}
			
			$data['SHouse'] = $this->report_m->management_SHouse_details($code,$timedate,$where);
			$data['code'] = $code;
			$data['description'] = $description;
			$data['start_date'] = $start_date;
			$data['end_date'] = $end_date;
			$data['timedate'] = $timedate;
			$this->load->view('mvd/software_house_details_v', $data);
		}
	}
	
	public function PartManDesc_details(){
		if($_POST){
			$timedate = trim($this->input->post('timedate'));
			$code = trim($this->input->post('code'));
			$description = trim($this->input->post('description'));
			$start_date = trim(datepicker2mysql($this->input->post('start_date')));
			$end_date = trim(datepicker2mysql($this->input->post('end_date')));
			if($start_date == date("Y-m-d")){
				$where = "";
			}
			else{
				if(($end_date == $start_date || $end_date == "") && $start_date != ""){
				$where .= ($where == "" ? " WHERE " : " AND ") . " ( `a`.`create_date` LIKE " . $this->db->escape($start_date. "%") . ") ";
				}
				else if($start_date != ""){
					$end_date2 = date("Y-m-d", strtotime($end_date . " + 1 days"));
					$where .= ($where == "" ? " WHERE " : " AND ") . " ( `a`.`create_date` BETWEEN " . $this->db->escape($start_date) . " 
								AND " . $this->db->escape($end_date2) . ") ";
				}
			}
			
			$data['parts'] = $this->report_m->management_PartManDesc_details($code,$timedate,$where);
			$data['description'] = $description == "" ? $code : $description;;
			$data['full_name'] = $full_name;
			$data['start_date'] = $start_date;
			$data['end_date'] = $end_date;
			$data['timedate'] = $timedate;
			$this->load->view('mvd/PartManDesc_details_v', $data);
		}
	}
	
	public function repairers($type = 'html'){
		$meta['title'] = 'Repairer Reports';
		$meta['sub_title'] = 'Graph';
		$meta['breadcrumb_arr'] = array('Repairers' => 'mvd/report/repairers');
		$data['meta'] = $meta;
		$data['table_fields'] = $this->report_m->get_fields('api_history');
		$data['start_date'] = date("Y-m-d");
		$this->load->view('mvd/repairers_v', $data);
	}
	
	public function insurers($type = 'html'){
		$meta['title'] = 'Insurer Reports';
		$meta['sub_title'] = 'Graph';
		$meta['breadcrumb_arr'] = array('Repairers' => 'mvd/report/insurers');
		$data['meta'] = $meta;
		$data['table_fields'] = $this->report_m->get_fields('api_history');
		$data['start_date'] = date("Y-m-d");
		$this->load->view('mvd/insurer_v', $data);
	}
	
	public function adjusters($type = 'html'){
		$meta['title'] = 'Adjuster Reports';
		$meta['sub_title'] = 'Graph';
		$meta['breadcrumb_arr'] = array('Adjusters' => 'mvd/report/adjusters');
		$data['meta'] = $meta;
		$data['table_fields'] = $this->report_m->get_fields('api_history');
		$data['start_date'] = date("Y-m-d");
		$this->load->view('mvd/adjuster_v', $data);
	}
	
	public function franchise($type = 'html'){
		$meta['title'] = 'Franchise Reports';
		$meta['sub_title'] = 'Graph';
		$meta['breadcrumb_arr'] = array('Franchise' => 'mvd/report/franchise');
		$data['meta'] = $meta;
		$data['table_fields'] = $this->report_m->get_fields('api_history');
		$data['start_date'] = date("Y-m-d");
		$this->load->view('mvd/francises_v', $data);
	}
	
	public function motorcars($type = 'html'){
		$meta['title'] = 'Motorcar Reports';
		$meta['sub_title'] = 'Graph';
		$meta['breadcrumb_arr'] = array('Motorcar' => 'mvd/report/motorcars');
		$data['meta'] = $meta;
		$data['table_fields'] = $this->report_m->get_fields('api_history');
		$data['start_date'] = date("Y-m-d");
		$this->load->view('mvd/motorcars_v', $data);
	}
	
	public function lawyers($type = 'html'){
		$meta['title'] = 'Lawyer Reports';
		$meta['sub_title'] = 'Graph';
		$meta['breadcrumb_arr'] = array('lawyer' => 'mvd/report/lawyer');
		$data['meta'] = $meta;
		$data['table_fields'] = $this->report_m->get_fields('api_history');
		$data['start_date'] = date("Y-m-d");
		$this->load->view('mvd/lawyers_v', $data);
	}
	
	public function vendors($type = 'html'){
		$meta['title'] = 'Vendor Reports';
		$meta['sub_title'] = 'Graph';
		$meta['breadcrumb_arr'] = array('Vendor' => 'mvd/report/vendors');
		$data['meta'] = $meta;
		$data['table_fields'] = $this->report_m->get_fields('api_history');
		$data['start_date'] = date("Y-m-d");
		$this->load->view('mvd/vendors_v', $data);
	}
	
	public function siteid_graphs_data(){
		if($_POST) {
			$timedate = date('H:i:s');
			$from_url = trim($this->input->post('from_url'));
			$start_date = trim(datepicker2mysql($this->input->post('start_date')));
			$end_date = trim(datepicker2mysql($this->input->post('end_date')));
			if($start_date == "" && $end_date == ""){
				$start_date = date("Y-m-d");
			}
			else if($start_date == "" && $end_date != ""){
				$start_date = $end_date;
				$end_date = "";
			}
			else if($start_date == $end_date){
				$end_date = "";
			}
			$valid_id = array();
			if($from_url == "repairers"){
				// get siteid for repairers
				$valid_id = $this->report_m->get_repairers_siteid();
			}
			else if($from_url == "insurers"){
				// get siteid for insurers
				$valid_id = $this->report_m->get_insurers_siteid();
			}
			else if($from_url == "adjusters"){
				// get siteid for adjuster
				$valid_id = $this->report_m->get_adjusters_siteid();
			}
			else if($from_url == "franchise"){
				// get siteid for francise
				$valid_id = $this->report_m->get_francises_siteid();
			}
			else if($from_url == "motorcars"){
				// get siteid for motorcar
				$valid_id = $this->report_m->get_motorcars_siteid();
			}
			else if($from_url == "lawyers"){
				// get siteid for lawyer
				$valid_id = $this->report_m->get_lawyers_siteid();
			}
			else if($from_url == "vendors"){
				// get siteid for vendor
				$valid_id = $this->report_m->get_vendors_siteid();
			}
			// get all data from site_id
			$data['parts'] = $this->report_m->get_data_from_site_id($timedate,$start_date,$end_date,$valid_id);
			
			// Get String for date and time
			$start_date2 = $start_date != "" ? date("d/m/Y", strtotime($start_date)) : '';
			$end_date2 = $end_date != "" ? date("d/m/Y", strtotime($end_date)) : '';
			$date_time = "";
			if($end_date2 == ""){
				if($start_date2 == date("d/m/Y"))
					$date_time = "on <b>" . $start_date2 . " " . date('h:i:s A', strtotime($timedate)) . "</b>";
				else
					$date_time = "on <b>" . $start_date2 . "</b>";
			}
			else
				$date_time = "from <b>" . $start_date2 . "</b> to <b>" . $end_date2 . "</b>";
			
			$json_data["load_view"] = $this->load->view('mvd/siteid_graphs_v', $data, true);
			$json_data["date_time"] = $date_time;
			$json_data["start_date"] = $start_date;
			$json_data["end_date"] = $end_date;
			$json_data["timedate"] = $timedate;
			
			echo json_encode($json_data);
		}
	}
	
	public function un_siteid($type = 'html'){
		$meta['title'] = 'Unmatchable Site ID';
		$meta['sub_title'] = 'Graph';
		$meta['breadcrumb_arr'] = array('Unmatchable Site ID' => 'mvd/report/un_siteid');
		$data['meta'] = $meta;
		$data['timedate'] = $timedate;
		$data['table_fields'] = $this->report_m->get_fields('api_history');
		$data['start_date'] = date("Y-m-d");
		$this->load->view('mvd/un_siteid_v', $data);
	}
	
	public function un_siteid_graphs_data(){
		if($_POST) {
			$timedate = date('H:i:s');
			$start_date = trim(datepicker2mysql($this->input->post('start_date')));
			$end_date = trim(datepicker2mysql($this->input->post('end_date')));
			if($start_date == "" && $end_date == ""){
				$start_date = date("Y-m-d");
			}
			else if($start_date == "" && $end_date != ""){
				$start_date = $end_date;
				$end_date = "";
			}
			else if($start_date == $end_date){
				$end_date = "";
			}
			// get data for repairer
			$data['parts'] = $this->report_m->get_un_siteid($timedate,$start_date,$end_date);
			
			// Get String for date and time
			$start_date2 = $start_date != "" ? date("d/m/Y", strtotime($start_date)) : '';
			$end_date2 = $end_date != "" ? date("d/m/Y", strtotime($end_date)) : '';
			$date_time = "";
			if($end_date2 == ""){
				if($start_date2 == date("d/m/Y"))
					$date_time = "on <b>" . $start_date2 . " " . date('h:i:s A', strtotime($timedate)) . "</b>";
				else
					$date_time = "on <b>" . $start_date2 . "</b>";
			}
			else
				$date_time = "from <b>" . $start_date2 . "</b> to <b>" . $end_date2 . "</b>";
			
			$json_data["load_view"] = $this->load->view('mvd/un_siteid_graphs_v', $data, true);
			$json_data["date_time"] = $date_time;
			$json_data["start_date"] = $start_date;
			$json_data["end_date"] = $end_date;
			$json_data["timedate"] = $timedate;
			
			echo json_encode($json_data);
		}
	}
	
	public function claimtype($type = 'html'){
		$meta['title'] = 'Claim Type';
		$meta['sub_title'] = 'Graph';
		$meta['breadcrumb_arr'] = array('Claim Type' => 'mvd/report/claimtype');
		$data['meta'] = $meta;
		$data['table_fields'] = $this->report_m->get_fields('api_history');
		$data['start_date'] = date("Y-m-d");
		$this->load->view('mvd/claimtype_v', $data);
	}
	
	public function claimtype_graphs_data(){
		if($_POST) {
			$timedate = date('H:i:s');
			$start_date = trim(datepicker2mysql($this->input->post('start_date')));
			$end_date = trim(datepicker2mysql($this->input->post('end_date')));
			if($start_date == "" && $end_date == ""){
				$start_date = date("Y-m-d");
			}
			else if($start_date == "" && $end_date != ""){
				$start_date = $end_date;
				$end_date = "";
			}
			else if($start_date == $end_date){
				$end_date = "";
			}
			// get data for repairer
			$data['parts'] = $this->report_m->get_claimtype($timedate,$start_date,$end_date);
			
			// Get String for date and time
			$start_date2 = $start_date != "" ? date("d/m/Y", strtotime($start_date)) : '';
			$end_date2 = $end_date != "" ? date("d/m/Y", strtotime($end_date)) : '';
			$date_time = "";
			if($end_date2 == ""){
				if($start_date2 == date("d/m/Y"))
					$date_time = "on <b>" . $start_date2 . " " . date('h:i:s A', strtotime($timedate)) . "</b>";
				else
					$date_time = "on <b>" . $start_date2 . "</b>";
			}
			else
				$date_time = "from <b>" . $start_date2 . "</b> to <b>" . $end_date2 . "</b>";
			
			$json_data["load_view"] = $this->load->view('mvd/claimtype_graphs_v', $data, true);
			$json_data["date_time"] = $date_time;
			$json_data["start_date"] = $start_date;
			$json_data["end_date"] = $end_date;
			$json_data["timedate"] = $timedate;
			
			echo json_encode($json_data);
		}
	}
	
	public function get_category_232_details(){
		if($_POST){
			$timedate = trim($this->input->post('timedate'));
			$code1 = trim($this->input->post('code1'));
			$codename1 = trim($this->input->post('codename1'));
			$start_date = trim(datepicker2mysql($this->input->post('start_date')));
			$end_date = trim(datepicker2mysql($this->input->post('end_date')));
			$name_page = trim($this->input->post('name_page'));
			if($start_date == date("Y-m-d")){
				$where = "";
			}
			else{
				if(($end_date == $start_date || $end_date == "") && $start_date != ""){
					$where .= ($where == "" ? " WHERE " : " AND ") . " ( `a`.`create_date` LIKE " . $this->db->escape($start_date. "%") . ") ";
				}
				else if($start_date != ""){
					$end_date2 = date("Y-m-d", strtotime($end_date . " + 1 days"));
					$where .= ($where == "" ? " WHERE " : " AND ") . " ( `a`.`create_date` BETWEEN " . $this->db->escape($start_date) . " 
								AND " . $this->db->escape($end_date2) . ") ";
				}
			}
			
			$data['partsArr'] = $this->report_m->get_category_232_details($name_page,$code1,$timedate,$where);
			$data['code1'] = $code1;
			$data['codename1'] = $codename1 == "" ? $code1 : $codename1;
			$data['start_date'] = $start_date;
			$data['end_date'] = $end_date;
			$data['timedate'] = $timedate;
			$data['name_page'] = $name_page;
			$this->load->view('mvd/parts_category_details_v', $data);
		}
	}
	
	public function data_parts_details(){
		if($_POST){
			$timedate = trim($this->input->post('timedate'));
			$code1 = trim($this->input->post('code1'));
			$codename1 = trim($this->input->post('codename1'));
			$code = trim($this->input->post('code'));
			$description = trim($this->input->post('description'));
			$start_date = trim(datepicker2mysql($this->input->post('start_date')));
			$end_date = trim(datepicker2mysql($this->input->post('end_date')));
			$name_page = trim($this->input->post('name_page'));
			if($start_date == date("Y-m-d")){
				$where = "";
			}
			else{
				if(($end_date == $start_date || $end_date == "") && $start_date != ""){
					$where .= ($where == "" ? " WHERE " : " AND ") . " ( `a`.`create_date` LIKE " . $this->db->escape($start_date. "%") . ") ";
				}
				else if($start_date != ""){
					$end_date2 = date("Y-m-d", strtotime($end_date . " + 1 days"));
					$where .= ($where == "" ? " WHERE " : " AND ") . " ( `a`.`create_date` BETWEEN " . $this->db->escape($start_date) . " 
								AND " . $this->db->escape($end_date2) . ") ";
				}
			}
			
			$data['parts'] = $this->report_m->get_data_parts_details($name_page,$code1,$code,$timedate,$where);
			$data['code1'] = $code1;
			$data['full_name'] = $codename1 == "" ? $code1 : $codename1;
			$data['code'] = $code;
			$data['description'] = $description == "" ? $code : $description;
			$data['start_date'] = $start_date;
			$data['end_date'] = $end_date;
			$data['timedate'] = $timedate;
			$this->load->view('mvd/PartManDesc_details_v', $data);
		}
	}
	
	public function vehicle_make(){
		$meta['title'] = 'Vehicle Make';
		$meta['sub_title'] = 'Graph';
		$meta['breadcrumb_arr'] = array('Vehicle Make' => 'mvd/report/vehicle_make');
		$data['meta'] = $meta;
		$data['table_fields'] = $this->report_m->get_fields('api_history');
		$data['start_date'] = date("Y-m-d");
		$this->load->view('mvd/vehicle_make_v', $data);
	}
	
	public function vehicle_make_graphs_data(){
		if($_POST) {
			$timedate = date('H:i:s');
			$start_date = trim(datepicker2mysql($this->input->post('start_date')));
			$end_date = trim(datepicker2mysql($this->input->post('end_date')));
			if($start_date == "" && $end_date == ""){
				$start_date = date("Y-m-d");
			}
			else if($start_date == "" && $end_date != ""){
				$start_date = $end_date;
				$end_date = "";
			}
			else if($start_date == $end_date){
				$end_date = "";
			}
			// get data for repairer
			$data['parts'] = $this->report_m->get_parts_vehicle_make($timedate,$start_date,$end_date);
			
			// Get String for date and time
			$start_date2 = $start_date != "" ? date("d/m/Y", strtotime($start_date)) : '';
			$end_date2 = $end_date != "" ? date("d/m/Y", strtotime($end_date)) : '';
			$date_time = "";
			if($end_date2 == ""){
				if($start_date2 == date("d/m/Y"))
					$date_time = "on <b>" . $start_date2 . " " . date('h:i:s A', strtotime($timedate)) . "</b>";
				else
					$date_time = "on <b>" . $start_date2 . "</b>";
			}
			else
				$date_time = "from <b>" . $start_date2 . "</b> to <b>" . $end_date2 . "</b>";
			
			$json_data["load_view"] = $this->load->view('mvd/vehicle_make_graphs_v', $data, true);
			$json_data["date_time"] = $date_time;
			$json_data["start_date"] = $start_date;
			$json_data["end_date"] = $end_date;
			$json_data["timedate"] = $timedate;
			
			echo json_encode($json_data);
		}
	}
	
	public function vehicle_make_type(){
		if($_POST){
			$timedate = trim($this->input->post('timedate'));
			$code = trim($this->input->post('code'));
			$description = trim($this->input->post('description'));
			$start_date = trim(datepicker2mysql($this->input->post('start_date')));
			$end_date = trim(datepicker2mysql($this->input->post('end_date')));
			if($start_date == date("Y-m-d")){
				$where = "";
			}
			else{
				if(($end_date == $start_date || $end_date == "") && $start_date != ""){
				$where .= ($where == "" ? " WHERE " : " AND ") . " ( `a`.`create_date` LIKE " . $this->db->escape($start_date. "%") . ") ";
				}
				else if($start_date != ""){
					$end_date2 = date("Y-m-d", strtotime($end_date . " + 1 days"));
					$where .= ($where == "" ? " WHERE " : " AND ") . " ( `a`.`create_date` BETWEEN " . $this->db->escape($start_date) . " 
							AND " . $this->db->escape($end_date2) . ") ";
				}
			}
			
			$data['parts'] = $this->report_m->vehicle_make_type($code,$timedate,$where);
			$data['description'] = $description == "" ? $code : $description;
			$data['vehicle_code'] = $code;
			$data['start_date'] = $start_date;
			$data['end_date'] = $end_date;
			$data['timedate'] = $timedate;
			$this->load->view('mvd/vehicle_make_type_v', $data);
		}
	}
	
	public function part_category_vehicle_make(){
		if($_POST){
			$timedate = trim($this->input->post('timedate'));
			$code = trim($this->input->post('code'));
			$description = trim($this->input->post('description'));
			$start_date = trim(datepicker2mysql($this->input->post('start_date')));
			$end_date = trim(datepicker2mysql($this->input->post('end_date')));
			if($start_date == date("Y-m-d")){
				$where = "";
			}
			else{
				if(($end_date == $start_date || $end_date == "") && $start_date != ""){
				$where .= ($where == "" ? " WHERE " : " AND ") . " ( `a`.`create_date` LIKE " . $this->db->escape($start_date. "%") . ") ";
				}
				else if($start_date != ""){
					$end_date2 = date("Y-m-d", strtotime($end_date . " + 1 days"));
					$where .= ($where == "" ? " WHERE " : " AND ") . " ( `a`.`create_date` BETWEEN " . $this->db->escape($start_date) . " 
							AND " . $this->db->escape($end_date2) . ") ";
				}
			}
			
			$data['parts'] = $this->report_m->part_category_vehicle_make($code,$timedate,$where);
			$data['description'] = $description == "" ? $code : $description;
			$data['vehicle_code'] = $code;
			$data['start_date'] = $start_date;
			$data['end_date'] = $end_date;
			$data['timedate'] = $timedate;
			$this->load->view('mvd/vehicle_make_parts_category_v', $data);
		}
	}
	
	public function part_code_vehicle_make($type = 0){
		if($_POST){
			$timedate = trim($this->input->post('timedate'));
			$code = trim($this->input->post('code'));
			$description = trim($this->input->post('description'));
			$start_date = trim(datepicker2mysql($this->input->post('start_date')));
			$end_date = trim(datepicker2mysql($this->input->post('end_date')));
			if($start_date == date("Y-m-d")){
				$where = "";
			}
			else{
				if(($end_date == $start_date || $end_date == "") && $start_date != ""){
				$where .= ($where == "" ? " WHERE " : " AND ") . " ( `a`.`create_date` LIKE " . $this->db->escape($start_date. "%") . ") ";
				}
				else if($start_date != ""){
					$end_date2 = date("Y-m-d", strtotime($end_date . " + 1 days"));
					$where .= ($where == "" ? " WHERE " : " AND ") . " ( `a`.`create_date` BETWEEN " . $this->db->escape($start_date) . " 
							AND " . $this->db->escape($end_date2) . ") ";
				}
			}
			if($type == 1){
				// ajax view more from parts category
				$vehicle_code = trim($this->input->post('vehicle_code'));
				$data['parts'] = $this->report_m->part_code_vehicle_make2($code,$vehicle_code,$timedate,$where);
			}
			else
				$data['parts'] = $this->report_m->part_code_vehicle_make($code,$timedate,$where);
			$data['description'] = $description == "" ? $code : $description;;
			$data['full_name'] = $full_name;
			$data['start_date'] = $start_date;
			$data['end_date'] = $end_date;
			$data['timedate'] = $timedate;
			$this->load->view('mvd/vehicle_make_parts_code_v', $data);
		}
	}
	
	public function vehicle_type(){
		$meta['title'] = 'Vehicle Type';
		$meta['sub_title'] = 'Graph';
		$meta['breadcrumb_arr'] = array('Vehicle Type' => 'mvd/report/vehicle_type');
		$data['meta'] = $meta;
		$data['table_fields'] = $this->report_m->get_fields('api_history');
		$data['start_date'] = date("Y-m-d");
		$this->load->view('mvd/vehicle_type_v', $data);
	}
	
	public function vehicle_type_graphs_data(){
		if($_POST) {
			$timedate = date('H:i:s');
			$start_date = trim(datepicker2mysql($this->input->post('start_date')));
			$end_date = trim(datepicker2mysql($this->input->post('end_date')));
			if($start_date == "" && $end_date == ""){
				$start_date = date("Y-m-d");
			}
			else if($start_date == "" && $end_date != ""){
				$start_date = $end_date;
				$end_date = "";
			}
			else if($start_date == $end_date){
				$end_date = "";
			}
			// get data for repairer
			$data['parts'] = $this->report_m->get_parts_vehicle_type($timedate,$start_date,$end_date);
			
			// Get String for date and time
			$start_date2 = $start_date != "" ? date("d/m/Y", strtotime($start_date)) : '';
			$end_date2 = $end_date != "" ? date("d/m/Y", strtotime($end_date)) : '';
			$date_time = "";
			if($end_date2 == ""){
				if($start_date2 == date("d/m/Y"))
					$date_time = "on <b>" . $start_date2 . " " . date('h:i:s A', strtotime($timedate)) . "</b>";
				else
					$date_time = "on <b>" . $start_date2 . "</b>";
			}
			else
				$date_time = "from <b>" . $start_date2 . "</b> to <b>" . $end_date2 . "</b>";
			
			$json_data["load_view"] = $this->load->view('mvd/vehicle_type_graph_v', $data, true);
			$json_data["date_time"] = $date_time;
			$json_data["start_date"] = $start_date;
			$json_data["end_date"] = $end_date;
			$json_data["timedate"] = $timedate;
			
			echo json_encode($json_data);
		}
	}
	
	public function part_category_vehicle_type(){
		if($_POST){
			$timedate = trim($this->input->post('timedate'));
			$code = trim($this->input->post('code'));
			$start_date = trim(datepicker2mysql($this->input->post('start_date')));
			$end_date = trim(datepicker2mysql($this->input->post('end_date')));
			if($start_date == date("Y-m-d")){
				$where = "";
			}
			else{
				if(($end_date == $start_date || $end_date == "") && $start_date != ""){
				$where .= ($where == "" ? " WHERE " : " AND ") . " ( `a`.`create_date` LIKE " . $this->db->escape($start_date. "%") . ") ";
				}
				else if($start_date != ""){
					$end_date2 = date("Y-m-d", strtotime($end_date . " + 1 days"));
					$where .= ($where == "" ? " WHERE " : " AND ") . " ( `a`.`create_date` BETWEEN " . $this->db->escape($start_date) . " 
							AND " . $this->db->escape($end_date2) . ") ";
				}
			}
			
			$data['parts'] = $this->report_m->part_category_vehicle_type($code,$timedate,$where);
			$data['type_code'] = $code;
			$data['start_date'] = $start_date;
			$data['end_date'] = $end_date;
			$data['timedate'] = $timedate;
			$this->load->view('mvd/vehicle_type_parts_category_v', $data);
		}
	}
	
	public function part_code_vehicle_type($type = 0){
		if($_POST){
			$timedate = trim($this->input->post('timedate'));
			$code = trim($this->input->post('code'));
			$start_date = trim(datepicker2mysql($this->input->post('start_date')));
			$end_date = trim(datepicker2mysql($this->input->post('end_date')));
			if($start_date == date("Y-m-d")){
				$where = "";
			}
			else{
				if(($end_date == $start_date || $end_date == "") && $start_date != ""){
				$where .= ($where == "" ? " WHERE " : " AND ") . " ( `a`.`create_date` LIKE " . $this->db->escape($start_date. "%") . ") ";
				}
				else if($start_date != ""){
					$end_date2 = date("Y-m-d", strtotime($end_date . " + 1 days"));
					$where .= ($where == "" ? " WHERE " : " AND ") . " ( `a`.`create_date` BETWEEN " . $this->db->escape($start_date) . " 
							AND " . $this->db->escape($end_date2) . ") ";
				}
			}
			if($type == 1){
				// ajax view more from parts category
				$type_code = trim($this->input->post('type_code'));
				$data['parts'] = $this->report_m->part_code_vehicle_type2($code,$type_code,$timedate,$where);
			}
			else
				$data['parts'] = $this->report_m->part_code_vehicle_type($code,$timedate,$where);
			
			$data['type_code'] = $type_code;
			$data['start_date'] = $start_date;
			$data['end_date'] = $end_date;
			$data['timedate'] = $timedate;
			$this->load->view('mvd/vehicle_type_parts_code_v', $data);
		}
	}
	
	public function ppc_billing($type = 'html')
	{
		$meta['title'] = 'PPC Usage Billing';
		$meta['sub_title'] = 'Web Parts Price Check Log Billing';
		$meta['breadcrumb_arr'] = array('PPC Usage Billing' => 'mvd/report/ppc_billing');
		$data = $this->report_m->get_ppc_billing($type);
		$data['meta'] = $meta;
		// $data['table_fields'] = $this->report_m->get_fields('api_history');
		
		$this->load->view('mvd/ppc_usage_billing_listing_v', $data);
	}
	
	public function search_ppc_billing($type = 'html', $base64 = '')
	{
		if($_POST) {
			
			$serialized['search_column'] = $this->input->post('search_column');
			$serialized['sql_sort_column'] = $this->input->post('sql_sort_column');
			$serialized['sql_sort'] = $this->input->post('sql_sort');
			$serialized['start_date'] = trim(datepicker2mysql($this->input->post('start_date')));
			$serialized['end_date'] = trim(datepicker2mysql($this->input->post('end_date')));
			
			redirect('mvd/report/search_ppc_billing/' . $type . '/' . trim(base64_encode(serialize($serialized)), "=."));
		}
		
		
		if($base64 == '')
			redirect('mvd/report/ppc_billing/');
		
		$unserialized = unserialize(base64_decode($base64));
		$meta['title'] = 'PPC Usage Billing';
		$meta['sub_title'] = ' Web Parts Price Check PPC Billing';
		$meta['breadcrumb_arr'] = array('PPC Usage Report' => 'mvd/report/ppc_billing');
		$data = $this->report_m->get_ppc_billing_search($type, $unserialized);
		
		$data['meta'] = $meta;
		
		$this->load->view('mvd/ppc_usage_billing_listing_v', $data);
	}
	
	public function transaction_details($base64 = ''){
		if($_POST){
			$system_id = trim($this->input->post('system_id'));
			$company_name = trim($this->input->post('company_name'));
			$start_date = trim($this->input->post('start_date'));
			$end_date = trim($this->input->post('end_date'));
			$data = $this->report_m->get_trasaction_details($system_id,$start_date,$end_date);
			if($data !== false){
				$data['system_id'] = $system_id;
				$data['company_name'] = $company_name;
				$vehicle_type = $this->report_m->get_name_vehicle_type($system_id,$start_date,$end_date);
				$date_range = "";
				if($start_date != "" && $end_date != "")
					$date_range = $start_date . " to " . $end_date;
				else if($start_date != "")
					$date_range = $start_date;
				$data['date_range'] = $date_range;
				$data['start_date'] = trim(datepicker2mysql($start_date));
				$data['end_date'] = trim(datepicker2mysql($end_date));
				$data['vehicle_type'] = $vehicle_type;
				$this->load->view('mvd/transaction_details_sideid', $data);
			}
			else{
				redirect($_POST['uri']);
			}
		}
	}
	
	public function ptransaction_details($base64 = ''){
		if($base64 != ''){
			$unserialized = unserialize(base64_decode($base64));
			
			$system_id = $unserialized["systemid"];
			$company_name = $unserialized["company_name"];
			$start_date = $unserialized["start_date"];
			$end_date = $unserialized["end_date"];
			$data = $this->report_m->pget_trasaction_details($system_id,$start_date,$end_date);
			if($data !== false){
				$data['system_id'] = $system_id;
				$data['company_name'] = $company_name;
				$vehicle_type = $this->report_m->get_name_vehicle_type($system_id,$start_date,$end_date);
				$date_range = "";
				if($start_date != "" && $end_date != "")
					$date_range = $start_date . " to " . $end_date;
				else if($start_date != "")
					$date_range = $start_date;
				$data['date_range'] = $date_range;
				$data['start_date'] = trim(datepicker2mysql($start_date));
				$data['end_date'] = trim(datepicker2mysql($end_date));
				$data['vehicle_type'] = $vehicle_type;
				$this->load->view('mvd/ptransaction_details_sideid', $data);
			}
		}
	}
	
	public function soa_report(){
		$this->load->library('zip');
		$this->load->helper('directory');
		
		if($_POST){
			$system_ids = $this->input->post('select_soa');
			$path = 'uploads/ppc_billing/';
			if(!is_dir($path))
			{
				mkdir($path ,0777,TRUE);
			}
			
			$list_file = directory_map($path);
			if(sizeof($list_file) > 0 ){
				foreach($list_file as $filename){
					if(file_exists($path . $filename)){
						unlink($path . $filename);
					}
				}
			}
			
			if(sizeof($system_ids) > 0){
				foreach($system_ids as $system_id){
					$data = $this->report_m->get_trasaction_data_pdf($system_id);
					if($data !== false){
						
						// get the HTML
						ob_start();
						
						// $content = ob_get_clean();
						$content = $this->load->view('mvd/pdf/soa_pdf_v',$data ,true);
						
						try
						{
							include APPPATH . 'libraries/html2pdf/html2pdf.php';
							$html2pdf = new HTML2PDF('P', 'A4', 'en');
							$html2pdf->pdf->SetDisplayMode('fullpage');
							$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
							$html2pdf->Output($path . 'PPC-Charged to ' . $data["user_details"]["company_name"] . '.pdf', 'F');
						}
						catch(HTML2PDF_exception $e) {
							echo $e;
							exit;
						}
					}
				}
			}
			$this->zip->read_dir($path, false);
			$this->zip->download("Statement of Account_" . date("Y_m_d") . ".zip"); 
		}
	}
	
	public function tax_invoice(){
		$this->load->library('zip');
		$this->load->helper('directory');
		
		if($_POST){
			
			$start_date = trim(datepicker2mysql($this->input->post('start_date')));
			$end_date = trim(datepicker2mysql($this->input->post('end_date')));
			$system_ids = $this->input->post('select_soa');
			
			if($start_date == ""){
				$start_date = "2017-05-01";
			}
			
			if($end_date == ""){
				$end_date = date("d-m-Y", strtotime("-1 months")); // will be one month before current system month 
			}
			
			//to get all month between start and end date.
			$start    = new DateTime($start_date);
			$start->modify('first day of this month');
			$end      = new DateTime($end_date);
			$end->modify('first day of next month');
			$interval = DateInterval::createFromDateString('1 month');
			$period   = new DatePeriod($start, $interval, $end);
			
			$path = 'uploads/ppc_tax_invoice/';
			if(!is_dir($path))
			{
				mkdir($path ,0777,TRUE);
			}
			
			$list_file = directory_map($path);
			if(sizeof($list_file) > 0 ){
				foreach($list_file as $filename){
					if(file_exists($path . $filename)){
						unlink($path . $filename);
					}
				}
			}
			
			if(sizeof($system_ids) > 0){
				foreach($system_ids as $system_id){
					foreach ($period as $dt) {
						$data = $this->report_m->get_tax_invoce_data($system_id,$dt->format("Y-m"));
						
						if($data !== false){
							$data['start_date'] = $start_date;
							// get the HTML
							ob_start();
							
							$content = $this->load->view('mvd/pdf/tax_pdf_v',$data ,true);
							
							try
							{
								include APPPATH . 'libraries/html2pdf/html2pdf.php';
								$html2pdf = new HTML2PDF('P', 'A4', 'en');
								$html2pdf->pdf->SetDisplayMode('fullpage');
								$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
								$html2pdf->Output($path . 'PPC-Tax Invoice to ' . $data["user_details"]["company_name"]."_".$dt->format("Y_m") . '.pdf', 'F');
							}
							catch(HTML2PDF_exception $e) {
								echo $e;
								exit;
							}
						}
					}
				}
			}
			$this->zip->read_dir($path, false);
			$this->zip->download("Tax Invoice_" . date("Y_m_d") . ".zip"); 
		}
	}
	
	
	public function print_ppc_billing($print_type = "", $system_id = "", $start_date = "", $end_date = ""){
		if($system_id != "" && in_array($print_type, array("pdf", "excel"))){
			if($print_type == "pdf"){
				include APPPATH . 'libraries/html2pdf/html2pdf.php';
				$this->report_m->generate_ppc_billing_pdf($system_id, $start_date, $end_date);
			}
			else if($print_type == "excel"){
				$this->load->library("PHPExcel/PHPExcel"); #Load PHPExcel librarry
				$this->report_m->generate_ppc_billing_excel($system_id, $start_date, $end_date);
			}
		}
	} 
}
