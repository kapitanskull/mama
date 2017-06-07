<?php

/**
 * @package			MRC iCap
 * @author			Affendy Iskandar <fendy@logicwise.com.my> (012-2886342) LOGICWISE SDN BHD www.logicwise.com.my fb.com/logicwise instagram.com/logicwise
 * @date			17 Feb 2017
 * @copyright		Copyright (c) Motordata Research Consortium Malaysia
 * @modified by		Fendy Ahmad
 * @modified date	1 March 2017
 * @filesource
 */
 
class Report_m extends CI_Model {
	var $mvd_db;
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		
		$this->mvd_db = $this->load->database('mvd_web', TRUE);
    }
	
	
	function get_api_usage($type)
	{
		$where_sql = '';
		
		$query = $this->mvd_db->query("SELECT COUNT(id) AS total FROM api_history WHERE 1 $where_sql")->row();
		$data['total_rows'] = $query->total;

		$config['base_url'] = base_url() . 'mvd/report/api_usage/' . $type;
		$config['uri_segment'] = 5;
		$config['total_rows'] = $data['total_rows'];
		$config['per_page'] = 50;

		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();

		$sql = '';
		if($data['total_rows'] > 0 AND $type == 'html') {
			if($this->uri->segment($config['uri_segment']) && is_numeric($this->uri->segment($config['uri_segment'])))
			{
				$sql = ' LIMIT ' . $this->uri->segment($config['uri_segment']) . ', ' . $config['per_page'];
			}
			else
			{
				$sql = ' LIMIT 0, ' . $config['per_page'];
			}
		}
		$data['query'] = $this->mvd_db->query("SELECT *, DATE_FORMAT(create_date, '%d/%m/%Y') as created_date, DATE_FORMAT(create_date, '%r') as created_time FROM api_history WHERE 1 $where_sql ORDER BY id ASC $sql");
		$data['search_column'] = $unserialized['search_column'];
		
		return $data;
	}
	
	function get_api_usage_search($type, $unserialized)
	{
		$search_str = trim($unserialized['search_str']);
		$sql_sort_column = trim($unserialized['sql_sort_column']);
		$sql_sort = trim($unserialized['sql_sort']);
		$search_column = trim($unserialized['search_column']);
		$start_date = trim($unserialized['start_date']);
		$end_date = trim($unserialized['end_date']);
		
		if($sql_sort_column != '') {
			if($sql_sort == 'ASC')
				$sql_sort = "DESC";
			else
				$sql_sort = 'ASC';
			
			$order_by = "ORDER BY " . $this->db->protect_identifiers($sql_sort_column) . " " . $sql_sort;
		}
		
		$where_sql = '1';
		
		if($search_str != '') {
			if($unserialized['search_column'] == 'all') {
				$fields = $this->get_fields('api_history');
				$where_sql = '';
				
				foreach($fields as $field) {
					$where_sql .= " OR $field LIKE " . $this->db->escape($unserialized['search_str']);
				}	

				$where_sql = "(" . ltrim($where_sql, " OR" ) . ")";
			}
			else 
				$where_sql = $unserialized['search_column'] . " LIKE " . $this->db->escape($unserialized['search_str']);
		}
		
		if($start_date != '') 
			$where_sql .= " AND create_date >= " . $this->db->escape($start_date . ' 00:00:00');
		
		if($end_date != '') 
			$where_sql .= " AND create_date <= " . $this->db->escape($end_date . ' 23:59:59');
			
		$where_sql = ltrim($where_sql, " AND" );
		
		$query = $this->mvd_db->query("SELECT COUNT(id) AS total FROM api_history WHERE $where_sql")->row();
		$data['total_rows'] = $query->total;

		$config['base_url'] = base_url() . 'mvd/report/search_api_usage/' . $type . '/' . $this->uri->segment(5);
		$config['uri_segment'] = 6;
		$config['total_rows'] = $data['total_rows'];
		$config['per_page'] = 50;

		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();

		$sql = '';
		if($data['total_rows'] > 0 AND $type == 'html') {
			if($this->uri->segment($config['uri_segment']) && is_numeric($this->uri->segment($config['uri_segment'])))
			{
				$sql = ' LIMIT ' . $this->uri->segment($config['uri_segment']) . ', ' . $config['per_page'];
			}
			else
			{
				$sql = ' LIMIT 0, ' . $config['per_page'];
			}
		} 
		$data['query'] = $this->mvd_db->query("SELECT *, DATE_FORMAT(create_date, '%d/%m/%Y') as created_date, DATE_FORMAT(create_date, '%r') as created_time FROM api_history WHERE $where_sql $order_by $sql");
		
		$data['sql_sort'] = $sql_sort;
		$data['search_str'] = $search_str;
		$data['search_column'] = $search_column;
		$data['start_date'] = $start_date;
		$data['end_date'] = $end_date;

		return $data;
	}
	
	function get_ppc_usage($type)
	{
		$where_sql = '';
		
		$query = $this->mvd_db->query("SELECT COUNT(a.id) AS total FROM api_ppc_history a WHERE 1 $where_sql")->row();
		$data['total_rows'] = $query->total;

		$config['base_url'] = base_url() . 'mvd/report/ppc_usage/' . $type;
		$config['uri_segment'] = 5;
		$config['total_rows'] = $data['total_rows'];
		$config['per_page'] = 50;

		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();

		$sql = '';
		if($data['total_rows'] > 0 AND $type == 'html') {
			if($this->uri->segment($config['uri_segment']) && is_numeric($this->uri->segment($config['uri_segment'])))
			{
				$sql = ' LIMIT ' . $this->uri->segment($config['uri_segment']) . ', ' . $config['per_page'];
			}
			else
			{
				$sql = ' LIMIT 0, ' . $config['per_page'];
			}
		}
		$data['query'] = $this->mvd_db->query("SELECT a.*, DATE_FORMAT(create_date, '%d/%m/%Y') as created_date, DATE_FORMAT(create_date, '%r') as " .
												"created_time FROM api_ppc_history a WHERE 1" . 
												"$where_sql ORDER BY a.id ASC $sql");
												
		$data['search_column'] = $unserialized['search_column'];
		
		return $data;
	}
	
	function get_ppc_usage_search($type, $unserialized)
	{
		$search_str = trim($unserialized['search_str']);
		$sql_sort_column = trim($unserialized['sql_sort_column']);
		$sql_sort = trim($unserialized['sql_sort']);
		$search_column = trim($unserialized['search_column']);
		$start_date = trim($unserialized['start_date']);
		$end_date = trim($unserialized['end_date']);
		$industry_type = trim($unserialized['industry_type']);
		
		if($sql_sort_column != '') {
			if($sql_sort == 'ASC')
				$sql_sort = "DESC";
			else
				$sql_sort = 'ASC';
			
			$order_by = "ORDER BY " . $this->db->protect_identifiers($sql_sort_column) . " " . $sql_sort;
		}
		
		$where_sql = '1';
		if($industry_type > 0) {
			$industry_type_where_sql = " AND (b.industry_type = " . $this->db->escape($industry_type) . " AND b.api_user_id = c.id AND c.systemid = a.systemid AND c.login_type = 2)";
			$industry_from_sql = ', api_ppc_user_details b, api_users c';
		}
		
		if($search_str != '') {
			if($unserialized['search_column'] == 'all') {
				$fields = $this->get_fields('api_ppc_history');
				$where_sql = '';
				
				foreach($fields as $field) {
					$where_sql .= " OR a.$field LIKE " . $this->db->escape($unserialized['search_str']);
				}	

				$where_sql = "(" . ltrim($where_sql, " OR" ) . ")";
			}
			else 
				$where_sql = 'a.' . $unserialized['search_column'] . " LIKE " . $this->db->escape($unserialized['search_str']);
		}
		
		if($start_date != '') 
			$where_sql .= " AND create_date >= " . $this->db->escape($start_date . ' 00:00:00');
		
		if($end_date != '') 
			$where_sql .= " AND create_date <= " . $this->db->escape($end_date . ' 23:59:59');
			
		$where_sql = ltrim($where_sql, " AND" );
		
		// echo ("SELECT COUNT(a.id) AS total FROM api_ppc_history a $industry_from_sql WHERE $where_sql $industry_type_where_sql"); exit();
		
		$query = $this->mvd_db->query("SELECT COUNT(a.id) AS total FROM api_ppc_history a $industry_from_sql WHERE $where_sql $industry_type_where_sql")->row();
		$data['total_rows'] = $query->total;

		$config['base_url'] = base_url() . 'mvd/report/search_ppc_usage/' . $type . '/' . $this->uri->segment(5);
		$config['uri_segment'] = 6;
		$config['total_rows'] = $data['total_rows'];
		$config['per_page'] = 50;

		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();

		$sql = '';
		if($data['total_rows'] > 0 AND $type == 'html') {
			if($this->uri->segment($config['uri_segment']) && is_numeric($this->uri->segment($config['uri_segment'])))
			{
				$sql = ' LIMIT ' . $this->uri->segment($config['uri_segment']) . ', ' . $config['per_page'];
			}
			else
			{
				$sql = ' LIMIT 0, ' . $config['per_page'];
			}
		} 
		$data['query'] = $this->mvd_db->query("SELECT *, DATE_FORMAT(create_date, '%d/%m/%Y') as created_date, DATE_FORMAT(create_date, '%r') as created_time 
												FROM api_ppc_history a $industry_from_sql WHERE 
												$where_sql $industry_type_where_sql $order_by $sql");
		
		$data['sql_sort'] = $sql_sort;
		$data['search_str'] = $search_str;
		$data['search_column'] = $search_column;
		$data['start_date'] = $start_date;
		$data['end_date'] = $end_date;
		$data['industry_type'] = $industry_type;

		return $data;
	}
	function get_billing($type)
	{
		$where_sql = '';
		
		$query = $this->mvd_db->query("SELECT COUNT(id) AS total FROM api_history WHERE 1 $where_sql GROUP BY ClaimID")->row();
		$data['total_rows'] = $query->total;

		$config['base_url'] = base_url() . 'mvd/report/billing/' . $type;
		$config['uri_segment'] = 5;
		$config['total_rows'] = $data['total_rows'];
		$config['per_page'] = 50;

		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();

		$sql = '';
		if($data['total_rows'] > 0 AND $type == 'html') {
			if($this->uri->segment($config['uri_segment']) && is_numeric($this->uri->segment($config['uri_segment'])))
			{
				$sql = ' LIMIT ' . $this->uri->segment($config['uri_segment']) . ', ' . $config['per_page'];
			}
			else
			{
				$sql = ' LIMIT 0, ' . $config['per_page'];
			}
		}
		$data['query'] = $this->mvd_db->query("SELECT *, DATE_FORMAT(create_date, '%d/%m/%Y') as created_date, DATE_FORMAT(create_date, '%r') as created_time FROM api_history WHERE 1 $where_sql GROUP BY ClaimID ORDER BY id ASC $sql");
		$data['search_column'] = $unserialized['search_column'];
		
		return $data;
	}
	
	function get_billing_search($type = 'html', $unserialized)
	{
		$claim_type = trim($unserialized['claim_type']);
		$software_house = trim($unserialized['software_house']);
		$start_date = trim($unserialized['start_date']);
		$end_date = trim($unserialized['end_date']);
		
		$where_sql = '1';
		
		if($start_date != '') 
			$where_sql .= " AND create_date >= " . $this->db->escape($start_date . ' 00:00:00');
		
		if($end_date != '') 
			$where_sql .= " AND create_date <= " . $this->db->escape($end_date . ' 23:59:59');
		
		if($software_house != '') 
			$where_sql .= " AND systemid = " . $this->db->escape($software_house);
		
		if($claim_type != '') 
			$where_sql .= " AND ClaimType = " . $this->db->escape($claim_type);
			
		$where_sql = ltrim($where_sql, " AND" );
		
		$query = $this->mvd_db->query("SELECT * FROM (SELECT * FROM api_history GROUP BY ClaimID ORDER BY id ASC) a WHERE $where_sql");
		$data['total_rows'] = $query->num_rows();

		$config['base_url'] = base_url() . 'mvd/report/search_billing/' . $type . '/' . $this->uri->segment(5);
		$config['uri_segment'] = 6;
		$config['total_rows'] = $data['total_rows'];
		$config['per_page'] = 50;

		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();

		$sql = '';
		if($data['total_rows'] > 0 AND $type == 'html') {
			if($this->uri->segment($config['uri_segment']) && is_numeric($this->uri->segment($config['uri_segment'])))
			{
				$sql = ' LIMIT ' . $this->uri->segment($config['uri_segment']) . ', ' . $config['per_page'];
			}
			else
			{
				$sql = ' LIMIT 0, ' . $config['per_page'];
			}
		} 
		$data['query'] = $this->mvd_db->query("SELECT *, DATE_FORMAT(create_date, '%d/%m/%Y') as created_date, DATE_FORMAT(create_date, '%r') as created_time FROM (SELECT * FROM api_history  GROUP BY ClaimID ORDER BY id ASC) a WHERE $where_sql $sql");

		$data['claim_type'] = $claim_type;
		$data['software_house'] = $software_house;
		$data['start_date'] = $start_date;
		$data['end_date'] = $end_date;
		$data['billed_type'] = trim($unserialized['billed_type']);

		return $data;
	}
	
	function get_billing_statement_by_id($id) 
	{
		#Get the generated date and time
		$sql = "SELECT *, DATE_FORMAT(create_date, '%d-%m-%Y_%h%i%p') as created_date FROM api_billing_statement WHERE id = " . $this->db->escape($id);
		$query_st = $this->mvd_db->query($sql);
		
		if($query_st->num_rows() > 0) {
			$row_st = $query_st->row();
			
			return $row_st;
		}
			
		return false;
	}
	function confirm_billing($type, $unserialized)
	{
		$data = $this->get_billing_search($type, $unserialized);
		$group_id = uniqid();
		
		if($data['query']->num_rows() > 0) {
			
			#Create the billing statement
			$StatementdbData['group_id'] = $group_id;
			$StatementdbData['process_status'] = 'processing';
			$StatementdbData['create_date'] = date("Y-m-d H:i:s");
			
			$rs = $this->mvd_db->insert('api_billing_statement', $StatementdbData); 
			$statement_id = $this->mvd_db->insert_id();
			
			audit_trail($this->mvd_db->last_query(), 'report_m.php', 'confirm_billing()', 'Create Billing Statement');
			
			$table_fields = $this->get_fields('api_history');
			$this->generate_billing_xls($data['query'], $table_fields, $statement_id, $unserialized);
			
			$sql = "UPDATE api_billing_statement SET process_status = 'completed' WHERE id = " . $this->db->escape($statement_id);
			$this->mvd_db->query($sql);
			audit_trail($this->mvd_db->last_query(), 'report_m.php', 'confirm_billing()', 'Update Billing Statement as Completed');
			
			foreach($data['query']->result() as $row) {
				
				$sql = "SELECT * FROM api_billing WHERE systemid = " . $this->db->escape($row->systemid) . " AND ClaimID = " . $this->db->escape($row->ClaimID) . " AND cDerivativeCode = " . $this->db->escape($row->cDerivativeCode) . " LIMIT 1";
				$query_b = $this->mvd_db->query($sql);
				
				#If this hasn't been billed before, we add into the table
				if($query_b->num_rows() == 0) {
					
					$dbData['group_id'] = $group_id;
					$dbData['api_history_id'] = $row->id;
					$dbData['systemid'] = $row->systemid;
					$dbData['SiteID'] = $row->SiteID;
					$dbData['ClaimID'] = $row->ClaimID;
					$dbData['ClaimType'] = $row->ClaimType;
					$dbData['cDerivativeCode'] = $row->cDerivativeCode;
					$dbData['VehicleRegNo'] = $row->VehicleRegNo;
					$dbData['AccidentDate'] = $row->AccidentDate;
					$dbData['MRCdbRevisionRequested'] = $row->MRCdbRevisionRequested;
					$dbData['MRCdbRevision'] = $row->MRCdbRevision;
					$dbData['total_parts'] = $row->total_parts;
					$dbData['create_date'] = date("Y-m-d H:i:s");
					
					#Get the current rate
					$sql = "SELECT * FROM api_billing_rate ORDER BY id DESC LIMIT 1";
					$query = $this->mvd_db->query($sql);
					
					if($query->num_rows() > 0) {
						$row_r = $query->row();
						$dbData['rate'] = $row_r->rate;
					}
					
					$rs = $this->mvd_db->insert('api_billing', $dbData); 
					audit_trail($this->mvd_db->last_query(), 'report_m.php', 'confirm_billing()', 'Create Billed API');
				}
			}
		}
		
		return false;
	}
	
	function generate_billing_xls($query, $table_fields, $statement_id, $unserialized)
	{
		require_once './assets/phpexcel/Classes/PHPExcel.php';

		$objPHPExcel = new PHPExcel();
		
		$table_fields = array(
						"Billing ID", "Software House", "Claim ID", "Claim Type",
						"Accident Date", "Vehichle Reg No", "Make/Model/Type", "First Retrieval Date",
						"Total Retrieval", "Rate"
		);
				
		$col = 0;
		foreach ($table_fields as $field) {
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col++, 1, $field);
		}
		
		$row = 2;
		foreach($query->result() as $data)
		{
			$billed_before = $this->check_billed_before($data);
			
			if($billed_before == 'Y') 
				continue;
			
			$shouse = $this->get_software_house($data->systemid)->row();
			$claim_type = $this->claim_type();
			$dCode = $this->get_derivative($data->cDerivativeCode);
			$first_claim = $this->get_first_claimid($data->ClaimID);
			$total_parts = $this->get_total_parts_billing($data->ClaimID);
			$billing_rate = $this->report_m->get_billing_rate($data);
			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data->systemid . $data->ClaimID . $data->cDerivativeCode);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $shouse->full_name);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, (string) $data->ClaimID . ' ');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $claim_type[$data->ClaimType]);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data->AccidentDate);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data->VehicleRegNo);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $dCode->descManu . $dCode->descModel . $dCode->cTrimLevel);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $first_claim->created_date);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $total_parts);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $billing_rate);
			
			$row++;
		}
		
		#If the excel is empty, just stop the processing, remove the statement and redirect with message
		if($row == 2) {
			$sql = "DELETE FROM api_billing_statement WHERE id = " . $this->db->escape($statement_id) . " LIMIT 1";
			$this->mvd_db->query($sql);
			audit_trail($this->mvd_db->last_query(), 'report_m.php', 'generate_billing_xls()', 'Remove generated billing due to empty XLS');
			
			set_message("The current listing does not have any transaction to be Billed.", 'danger');
			redirect('mvd/report/search_billing/' . $this->uri->segment(4) . '/' . $this->uri->segment(5));
			exit();
		}

		for ($i = 'A'; $i !=  $objPHPExcel->getActiveSheet()->getHighestColumn(); $i++) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE); //Resize the column
			$objPHPExcel->getActiveSheet()->getStyle($i . '1:' . $i . $row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT); //Set all columns as string
		}
		$objPHPExcel->getActiveSheet()->getStyle('A1:' . $i . '1')->getFont()->setBold(true);
		
		$objPHPExcel->getActiveSheet()->setTitle('Parts Price Retrieval Billing');
		$objPHPExcel->setActiveSheetIndex(0);

		#Get the generated date and time
		$sql = "SELECT *, DATE_FORMAT(create_date, '%d-%m-%Y_%h%i%p') as created_date FROM api_billing_statement WHERE id = " . $this->db->escape($statement_id);
		$query_st = $this->mvd_db->query($sql);
		
		if($query_st->num_rows() > 0) {
			$row_st = $query_st->row();
			$generated_date = $row_st->created_date;
			
		}
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Parts_Price_Retrieval_Billing_' . $generated_date . '.xls"');
		header('Cache-Control: max-age=0');
		header('Cache-Control: max-age=1');

		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		
		if($statement_id != '') {
			$path = './uploads/billing/' . $statement_id . '.xls';
			$objWriter->save($path);
		}
		
		$objWriter->save('php://output');
	}
	
	function check_billed_before($row)
	{
		$sql = "SELECT * FROM api_billing WHERE systemid = " . $this->db->escape($row->systemid) . " AND ClaimID = " . $this->db->escape($row->ClaimID) . " AND cDerivativeCode = " . $this->db->escape($row->cDerivativeCode) . " LIMIT 1";
		$query = $this->mvd_db->query($sql);
		
		if($query->num_rows() > 0) {
			return 'Y';
		}
		
		return 'N';
	}
	
	function get_billing_rate($row = null)
	{
		$sql = "SELECT * FROM api_billing WHERE systemid = " . $this->db->escape($row->systemid) . " AND ClaimID = " . $this->db->escape($row->ClaimID) . " AND cDerivativeCode = " . $this->db->escape($row->cDerivativeCode) . " LIMIT 1";
		$query = $this->mvd_db->query($sql);
		
		#If found, we returned the assigned rate
		if($query->num_rows() > 0) {
			$row_a = $query->row();
			return $row_a->rate;
		}
		
		#Otherwise get the default rate
		$sql = "SELECT * FROM api_billing_rate ORDER BY id DESC LIMIT 1";
		$query = $this->mvd_db->query($sql);
		
		if($query->num_rows() > 0) {
			$row_r = $query->row();
			return $row_r->rate;
		}
	}
	
	function get_billing_statement()
	{
		$where_sql = '';
		
		$query = $this->mvd_db->query("SELECT COUNT(id) AS total FROM api_billing_statement WHERE 1 $where_sql")->row();
		$data['total_rows'] = $query->total;

		$config['base_url'] = base_url() . 'mvd/report/billing_statement/' . $type;
		$config['uri_segment'] = 5;
		$config['total_rows'] = $data['total_rows'];
		$config['per_page'] = 50;

		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();

		$sql = '';
		if($data['total_rows'] > 0 AND $type == 'html') {
			if($this->uri->segment($config['uri_segment']) && is_numeric($this->uri->segment($config['uri_segment'])))
			{
				$sql = ' LIMIT ' . $this->uri->segment($config['uri_segment']) . ', ' . $config['per_page'];
			}
			else
			{
				$sql = ' LIMIT 0, ' . $config['per_page'];
			}
		}
		$data['query'] = $this->mvd_db->query("SELECT *, DATE_FORMAT(create_date, '%d/%m/%Y') as created_date, DATE_FORMAT(create_date, '%r') as created_time FROM api_billing_statement WHERE 1 $where_sql ORDER BY id DESC $sql");
		$data['search_column'] = $unserialized['search_column'];
		
		return $data;
		
	}
	
	function add_rate()
	{
		$rate = $this->input->post('rate');
		
		$sql = "INSERT INTO api_billing_rate SET `rate` = " . $this->db->escape($rate) . ", create_date = " . $this->db->escape(date('Y-m-d H:i:s'));
		$query = $this->mvd_db->query($sql);
		
		audit_trail($this->mvd_db->last_query(), 'report_m.php', 'add_rate()', 'Add Billing Rate');
	}
	
	function claim_type()
	{
		$claim_arr = array(
			1 => 'Own Damage (OD)',
			2 => 'Own Damage – KFK (OD-KFK)',
			3 => 'Own Damage – Theft from Vehicle (OD-TFV)',
			4 => 'Own Damage – Theft Recovered (OD-TFR)',
			5 => 'Third Party – KFK (TP-KFK)',
			6 => 'ODKFK/Third Party – Uninsured Losses (TP-UL)',
			7 => 'Third Party - Vehicle Damage (TP-VD)',
			8 => 'Third Party Property Damage (TP-PD)',
			9 => 'Third Party Bodily Injury (TP-BI)',
			10 => 'Third Party – Subrogation (TP-SB)',
			11 => 'Windscreen (WS)',
			12 => 'Extended Warranty (EXW)',
			13 => 'Loss-of-Use (LOU)',
			14 => 'Theft of Vehicle (TOV)'
		);
		
		return $claim_arr;
	}
	
	function get_fields($table_name)
	{
		
		$fields = $this->mvd_db->list_fields($table_name);
		
		return $fields;
	}
	
	function total()
	{
		$sql = "SELECT * FROM api_history";
		$query = $this->mvd_db->query($sql);
		
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$sql = "SELECT * FROM api_history_parts WHERE history_id = " . $this->db->escape($row->id);
				$query2 = $this->mvd_db->query($sql);
				
				if($query2->num_rows() > 0) {
					$sql = "UPDATE api_history SET total_parts = " . $this->db->escape($query2->num_rows()) . " WHERE id = " . $this->db->escape($row->id) . " LIMIT 1";
					$this->mvd_db->query($sql);
				}
				
			}
		}
	}

	function get_parts_details($id, $type)
	{
		if($id > 0) {
			$where_sql = 'history_id = ' . $this->db->escape($id);
		
			$query = $this->mvd_db->query("SELECT COUNT(id) AS total FROM api_history_parts WHERE $where_sql")->row();
			$data['total_rows'] = $query->total;

			$config['base_url'] = base_url() . 'mvd/report/total_parts/' .  '/'	 . $id . '/' . $type;
			$config['uri_segment'] = 6;
			$config['total_rows'] = $data['total_rows'];
			$config['per_page'] = 10;
			$config['attributes'] = array('class' => 'pagination-links');

			$this->pagination->initialize($config);

			$data['pagination'] = $this->pagination->create_links();

			$sql = '';
			if($data['total_rows'] > 0 AND $type == 'html') {
				if($this->uri->segment($config['uri_segment']) && is_numeric($this->uri->segment($config['uri_segment'])))
				{
					$sql = ' LIMIT ' . $this->uri->segment($config['uri_segment']) . ', ' . $config['per_page'];
				}
				else
				{
					$sql = ' LIMIT 0, ' . $config['per_page'];
				}
			}
			$data['query'] = $this->mvd_db->query("SELECT *, DATE_FORMAT(create_date, '%d/%m/%Y') as created_date, DATE_FORMAT(create_date, '%r') as created_time FROM api_history_parts WHERE $where_sql ORDER BY id ASC $sql");
			
			return $data;
		}
		
		return array();
	}
	
	function get_derivative($dCode = null)
	{
		$sql = "SELECT *, b.cDescription as descModel, c.cDescription as descManu FROM derivative_details_231 a LEFT JOIN model_247 b ON a.nModelID = b.nModelID LEFT JOIN manufacturer_248 c ON a.nManufacturerID = c.nManufacturerID WHERE a.cDerivativeCode = " . $this->db->escape($dCode);
		$query = $this->mvd_db->query($sql);
		
		if($query->num_rows() > 0) {
			
			return $query->row();
		}
		
		return (object) array();
	}
	
	function get_all_retrieval_date($claim_id = null) 
	{
		$sql = "SELECT *, DATE_FORMAT(create_date, '%d/%m/%Y') as created_date, DATE_FORMAT(create_date, '%r') as created_time FROM api_history WHERE ClaimID = " . $this->db->escape($claim_id) . " GROUP BY created_date ORDER BY id";
		$query = $this->mvd_db->query($sql);
		
		return $query;
	}
	
	function get_first_claimid($claim_id = null)
	{
		$sql = "SELECT *, DATE_FORMAT(create_date, '%d/%m/%Y') as created_date, DATE_FORMAT(create_date, '%r') as created_time FROM api_history WHERE ClaimID = " . $this->db->escape($claim_id) . " ORDER BY id ASC LIMIT 1";
		$query = $this->mvd_db->query($sql);
		
		if($query->num_rows() > 0) {
			
			return $query->row();
		}
		
		return (object) array();
	}
	
	function get_total_parts_billing($claim_id = null)
	{
		$sql = "SELECT SUM(total_parts) as total_parts_by_claimid FROM api_history WHERE ClaimID = " . $this->db->escape($claim_id);
		$query = $this->mvd_db->query($sql);
		
		if($query->num_rows() > 0) {
			
			return $query->row()->total_parts_by_claimid;
		}
		
		return 0;
	}
	
	function get_software_house($id = null)
	{
		$where_sql = '';
		if($id != '') {
			$where_sql = "AND systemid = " . $this->db->escape($id);
		}
		
		$sql = "SELECT * FROM api_users WHERE login_type = '1' " . $where_sql;
		$query = $this->mvd_db->query($sql);
		
		return $query;
	}
	
	// Get Data Software House Data
	function get_software_house_data($timedate = "",$start_date = "", $end_date = ""){
		// Get value $where from date
		if($start_date == date("Y-m-d")){ 
			// Today: today `date` And Time
			$where = "WHERE DATE(`a`.`create_date`) = DATE(CURDATE()) AND `a`.`create_date` <= '" . date("Y-m-d") . " " . $timedate . "'";
		}
		else if($end_date == "" && $start_date != ""){
			// Day: `date` start date only
			$where = " WHERE  ( `a`.`create_date` LIKE " . $this->db->escape($start_date. "%") . ")";
		}
		else if($start_date != ""){
			// Range Day: `date` start date and end date
			$end_date2 = date("Y-m-d", strtotime($end_date . " + 1 days"));
			$where .= ($where == "" ? " WHERE " : " AND ") . " (`a`.`create_date` BETWEEN " . $this->db->escape($start_date) . " 
						AND " . $this->db->escape($end_date2) . ")";
		}
		
		$sql = "SELECT * FROM `api_users` WHERE `login_type` = '1' AND `id` != '1'";
		$query = $this->mvd_db->query($sql);
		$num_rows = $query->num_rows();
		$alldata  = array();
		if($num_rows > 0){
			$systemidArr = array();
			$systemidArr2 = array();
			$userArr = array();
			$useridArr = array();
			foreach($query->result() as $row){
				$id = $row->id;
				$systemid = $row->systemid;
				$full_name = $row->full_name;
				$useridArr[$systemid] = $id;
				$userArr[$systemid] = $full_name;
				array_push($systemidArr, $this->db->escape($systemid));
			}
			
			// $query2 = $this->mvd_db->query("SELECT COUNT(DISTINCT `id`) AS `total_connection`, COUNT(DISTINCT `systemid`, `ClaimID`,`SiteID`) AS `total_connection_unique`, `systemid` FROM `api_history` $where
			// AND `systemid` IN (" . implode(", ", $systemidArr) . ")  GROUP BY `systemid` ORDER BY `total_connection` DESC LIMIT 10");
			
			$sql2 = "SELECT `a`.`systemid` AS `systemid`, COUNT(`a`.`id`) AS `total`, COUNT(DISTINCT `a`.`systemid`, `a`.`ClaimID`, `a`.`SiteID`) AS `total_unique`,
				COUNT(DISTINCT `a`.`cDerivativeCode`,`b`.`cPartCode`) AS `unique_parts`, 
				COUNT(DISTINCT `a`.`systemid`, `a`.`ClaimID`, `a`.`SiteID`, `a`.`cDerivativeCode`,`b`.`cPartCode`) AS `total_parts_retrieval`
				FROM `api_history` AS `a`, `api_history_parts` AS `b`
				$where AND `a`.`id` = `b`.`history_id` AND `a`.`systemid` IN (" . implode(", ", $systemidArr) . ")
				GROUP BY `systemid` ORDER BY `total_parts_retrieval` DESC";
			$query2 = $this->mvd_db->query($sql2);	
			if($query2->num_rows() > 0) {
				foreach($query2->result() as $row2){
					$total_parts_unique = $total_parts_retrieval = $total_connection = $total_connection_unique = 0;
					$syid = $row2->systemid;
					$total_connection = $row2->total;
					$total_connection_unique = $row2->total_unique;
					$total_parts_unique = $row2->unique_parts;
					$total_parts_retrieval = $row2->total_parts_retrieval;
					array_push($systemidArr2, $this->db->escape($syid));
					$data = array(
						'id' => $useridArr[$syid],
						'systemid' => $syid,
						'full_name' => $userArr[$syid],
						'total_connection_unique' => $total_connection_unique,	// Unique Connections
						'total_connection' => $total_connection,				// Accumulative Connections
						'total_parts_unique' => $total_parts_unique,			// Unique Parts
						'total_parts_retrieval' => $total_parts_retrieval,		// Total Parts Retrieval
					);
					array_push($alldata, $data);
				}
			}
			$maxtotal = 10 - count($systemidArr2);
			$resultArr = array_diff($systemidArr,$systemidArr2);
			if(count($resultArr) > 0){
				$x = 0;
				foreach ($resultArr as $key => $value) {
					if($x < $maxtotal){
						$syid = str_replace("'","",$value);
						$data = array(
							'id' => $useridArr[$syid],
							'systemid' => $syid,
							'full_name' => $userArr[$syid],
							'total_connection_unique' => 0,
							'total_connection' => 0,
							'total_parts_unique' => 0,
							'total_parts_retrieval' => 0,
						);
						array_push($alldata, $data);
					}
					$x++;
				}
			}
			
		}
		return $alldata;
	}
	
	function management_SHouse_details($code = "",$timedate = "",$where = ""){
		if($where != ""){
			$where_sql = $where;
		}
		else{
			$where_sql = $where_sql .= ($where_sql == "" ? " WHERE " : " AND ") . " DATE(`create_date`) = DATE(CURDATE()) AND
						`create_date` <= '" . date("Y-m-d") . " " . $timedate . "' ";
		}
		
		$sql = "SELECT * FROM `api_users` WHERE `login_type` = '1' AND `systemid` != '00'";
		$query = $this->mvd_db->query($sql);
		$num_rows = $query->num_rows();
		$alldata  = array();
		if($num_rows > 0){
			$systemidArr = array();
			$systemidArr2 = array();
			$userArr = array();
			$useridArr = array();
			foreach($query->result() as $row){
				$id = $row->id;
				$systemid = $row->systemid;
				$full_name = $row->full_name;
				$useridArr[$systemid] = $id;
				$userArr[$systemid] = $full_name;
				array_push($systemidArr, $this->db->escape($systemid));
			}
			$query2 = $this->mvd_db->query("SELECT COUNT(DISTINCT `systemid`, `ClaimID`,`SiteID`) AS `total`, `systemid` FROM `api_history_parts` $where_sql 
			AND `systemid` IN (" . implode(", ", $systemidArr) . ")  AND `cCategoryCode` = " . $this->db->escape($code) . " 
			GROUP BY `systemid` ORDER BY total DESC LIMIT 10");
			if($query2->num_rows() > 0) {
				foreach($query2->result() as $row2){
					$syid = $row2->systemid;
					array_push($systemidArr2, $this->db->escape($syid));
					$data = array(
						'id' => $useridArr[$syid],
						'systemid' => $syid,
						'full_name' => $userArr[$syid],
						'total_parts' =>  $row2->total,
					);
					array_push($alldata, $data);
				}
			}
			$maxtotal = 10 - count($systemidArr2);
			$resultArr = array_diff($systemidArr,$systemidArr2);
			if(count($resultArr) > 0){
				$x = 0;
				foreach ($resultArr as $key => $value) {
					if($x < $maxtotal){
						$syid = str_replace("'","",$value);
						$data = array(
							'id' => $useridArr[$syid],
							'systemid' => $syid,
							'full_name' => $userArr[$syid],
							'total_parts' => 0,
						);
						array_push($alldata, $data);
					}
					$x++;
				}
			}
			
		}
		return $alldata;
	}
	
	function management_PartManDesc_details($code = "",$timedate = "",$where = ""){
		if($where != ""){
			$where_sql = $where;
		}
		else{
			$where_sql = $where_sql .= ($where_sql == "" ? " WHERE " : " AND ") . " DATE(`a`.`create_date`) = DATE(CURDATE()) 
						AND `a`.`create_date` <= '" . date("Y-m-d") . " " . $timedate . "' ";
		}
		$alldata  = array();
		$query2 = $this->mvd_db->query("SELECT COUNT(DISTINCT `a`.`cDerivativeCode`,`b`.`cPartCode`) AS `unique_parts`, `b`.`cPartManDesc`,
			COUNT(DISTINCT `a`.`systemid`, `a`.`ClaimID`, `a`.`SiteID`, `a`.`cDerivativeCode`,`b`.`cPartCode`) AS `total_parts_retrieval`
		FROM `api_history` AS `a`, `api_history_parts` AS `b` 
		$where_sql AND `a`.`id` = `b`.`history_id` AND `b`.`cCategoryCode` = " . $this->db->escape($code) . " 
		GROUP BY `cPartManDesc` ORDER BY `total_parts_retrieval` DESC");
		if($query2->num_rows() > 0) {
			foreach($query2->result() as $row2){
				$unique_parts = 0;
				$cPartManDesc = $row2->cPartManDesc;
				$unique_parts = $row2->unique_parts;
				$total_parts_retrieval = $row2->total_parts_retrieval;
				
				$data = array(
					'cPartManDesc' => $cPartManDesc,
					'unique_parts' => $unique_parts,
					'total_parts_retrieval' => $total_parts_retrieval,
				);
				array_push($alldata, $data);
			}
		}
		return $alldata;
	}
	
	// Get Data Parts Caregory
	function get_parts_category_data($timedate = "",$start_date = "", $end_date = ""){
		// Get value $where from date
		if($start_date == date("Y-m-d")){ 
			// Today: today `date` And Time
			$where = "WHERE DATE(`a`.`create_date`) = DATE(CURDATE()) AND `a`.`create_date` <= '" . date("Y-m-d") . " " . $timedate . "'";
		}
		else if($end_date == "" && $start_date != ""){
			// Day: `date` start date only
			$where = " WHERE  ( `a`.`create_date` LIKE " . $this->db->escape($start_date. "%") . ")";
		}
		else if($start_date != ""){
			// Range Day: `date` start date and end date
			$end_date2 = date("Y-m-d", strtotime($end_date . " + 1 days"));
			$where .= ($where == "" ? " WHERE " : " AND ") . " ( `a`.`create_date` BETWEEN " . $this->db->escape($start_date) . " 
						AND " . $this->db->escape($end_date2) . ")";
		}
		
		$sql = "SELECT `b`.`cCategoryCode` AS `cCategoryCode`, COUNT(DISTINCT `a`.`cDerivativeCode`, `b`.`cPartCode`) AS `unique_parts`,
				COUNT(DISTINCT `a`.`systemid`, `a`.`ClaimID`, `a`.`SiteID`, `a`.`cDerivativeCode`,`b`.`cPartCode`) AS `total_parts_retrieval`
				FROM `api_history` AS `a`, `api_history_parts` AS `b`
				$where AND `a`.`id` = `b`.`history_id`
				GROUP BY `cCategoryCode` ORDER BY `total_parts_retrieval` DESC";
		$query = $this->mvd_db->query($sql);
		
		$num_rows = $query->num_rows();
		$alldata  = array();
		if($num_rows > 0){
			foreach($query->result() as $row){
				$unique_parts = $row->unique_parts;
				$total_parts_retrieval = $row->total_parts_retrieval;
				$code = $row->cCategoryCode;
				$description = $id_code = "";
				$sql2 = "SELECT * FROM `category_232` WHERE `cCategoryCode` = " . $this->db->escape($code) . " LIMIT 1";
				$query2 = $this->mvd_db->query($sql2);
				if($query2->num_rows() > 0){
					$row2 = $query2->row();
					$description = $row2->cDescription;
					$id_code = $row2->id;
				}
				$data = array(
					'id' => $id_code,
					'code' => $code,
					'description' => $description,
					'unique_parts' => $unique_parts,
					'total_parts_retrieval' => $total_parts_retrieval,
				);
				array_push($alldata, $data);
			}
		}
		return $alldata;
	}
	
	// Get Repairers siteid
	function get_repairers_siteid(){
		// Get only `repairer` from table `siteid`
		$sql = "SELECT `siteid` FROM `site_id` WHERE `industry` = 'R' ";
		$query = $this->mvd_db->query($sql);
		$valid_id = array();
		if($query->num_rows() > 0){
			foreach($query->result() as $row){
				array_push($valid_id, $row->siteid);
			}
		}
		return $valid_id;
	}
	
	// Get Insurers siteid
	function get_insurers_siteid(){
		// Get only `repairer` from table `siteid`
		$sql = "SELECT `siteid` FROM `site_id` WHERE `industry` = 'I' ";
		$query = $this->mvd_db->query($sql);
		$valid_id = array();
		if($query->num_rows() > 0){
			foreach($query->result() as $row){
				array_push($valid_id, $row->siteid);
			}
		}
		return $valid_id;
	}
	
	// Get Adjusters siteid
	function get_adjusters_siteid(){
		// Get only `repairer` from table `siteid`
		$sql = "SELECT `siteid` FROM `site_id` WHERE `industry` = 'A' ";
		$query = $this->mvd_db->query($sql);
		$valid_id = array();
		if($query->num_rows() > 0){
			foreach($query->result() as $row){
				array_push($valid_id, $row->siteid);
			}
		}
		return $valid_id;
	}
	
	// Get Francises siteid
	function get_francises_siteid(){
		// Get only `repairer` from table `siteid`
		$sql = "SELECT `siteid` FROM `site_id` WHERE `industry` = 'F' ";
		$query = $this->mvd_db->query($sql);
		$valid_id = array();
		if($query->num_rows() > 0){
			foreach($query->result() as $row){
				array_push($valid_id, $row->siteid);
			}
		}
		return $valid_id;
	}
	
	// Get Motorcars siteid
	function get_motorcars_siteid(){
		// Get only `repairer` from table `siteid`
		$sql = "SELECT `siteid` FROM `site_id` WHERE `industry` = 'M' ";
		$query = $this->mvd_db->query($sql);
		$valid_id = array();
		if($query->num_rows() > 0){
			foreach($query->result() as $row){
				array_push($valid_id, $row->siteid);
			}
		}
		return $valid_id;
	}
	
	// Get Lawyers siteid
	function get_lawyers_siteid(){
		// Get only `repairer` from table `siteid`
		$sql = "SELECT `siteid` FROM `site_id` WHERE `industry` = 'L' ";
		$query = $this->mvd_db->query($sql);
		$valid_id = array();
		if($query->num_rows() > 0){
			foreach($query->result() as $row){
				array_push($valid_id, $row->siteid);
			}
		}
		return $valid_id;
	}
	
	// Get Vendors siteid
	function get_vendors_siteid(){
		// Get only `repairer` from table `siteid`
		$sql = "SELECT `siteid` FROM `site_id` WHERE `industry` = 'V' ";
		$query = $this->mvd_db->query($sql);
		$valid_id = array();
		if($query->num_rows() > 0){
			foreach($query->result() as $row){
				array_push($valid_id, $row->siteid);
			}
		}
		return $valid_id;
	}
	
	function get_data_from_site_id($timedate = "",$start_date = "", $end_date = "", $valid_id = array()){
		// run if only have site_id
		if(count($valid_id) > 0){
			// Get value $where from date
			if($start_date == date("Y-m-d")){ 
				// Today: today `date` And Time
				$where = "WHERE DATE(`a`.`create_date`) = DATE(CURDATE()) AND `a`.`create_date` <= '" . date("Y-m-d") . " " . $timedate . "'";
			}
			else if($end_date == "" && $start_date != ""){
				// Day: `date` start date only
				$where = " WHERE  ( `a`.`create_date` LIKE " . $this->db->escape($start_date. "%") . ")";
			}
			else if($start_date != ""){
				// Range Day: `date` start date and end date
				$end_date2 = date("Y-m-d", strtotime($end_date . " + 1 days"));
				$where .= ($where == "" ? " WHERE " : " AND ") . " ( `a`.`create_date` BETWEEN " . $this->db->escape($start_date) . " 
							AND " . $this->db->escape($end_date2) . ")";
			}
			
			// Get total sum `total_parts` , count row, for `SiteID`
			// $sql = "SELECT SUM(total_parts) AS `sum_part`, COUNT(`id`) AS `total`, COUNT(DISTINCT `systemid`, `ClaimID`, `SiteID`) AS `total_unique`,`SiteID` FROM `api_history` $where 
					// AND `SiteID` IN (" . implode(", ", $valid_id) . ") GROUP BY `SiteID` ORDER BY sum_part DESC";
			
			
			$sql = "SELECT `a`.`SiteID` AS `SiteID`, COUNT(`a`.`id`) AS `total`, COUNT(DISTINCT `a`.`systemid`, `a`.`ClaimID`, `a`.`SiteID`) AS `total_unique`,
				COUNT(DISTINCT `a`.`cDerivativeCode`, `b`.`cPartCode`) AS `unique_parts`,
				COUNT(DISTINCT `a`.`systemid`, `a`.`ClaimID`, `a`.`SiteID`, `a`.`cDerivativeCode`,`b`.`cPartCode`) AS `total_parts_retrieval`
				FROM `api_history` AS `a`, `api_history_parts` AS `b`
				$where AND `a`.`id` = `b`.`history_id` AND `a`.`SiteID` IN (" . implode(", ", $valid_id) . ") 
				GROUP BY `SiteID` ORDER BY `total_parts_retrieval` DESC";
			
			$query = $this->mvd_db->query($sql);
			
			$num_rows = $query->num_rows();
			$alldata  = array();
			if($num_rows > 0){
				foreach($query->result() as $row){
					$total_unique = $row->total_unique;
					$unique_parts = $row->unique_parts;
					$total_parts_retrieval = $row->total_parts_retrieval;
					$total = $row->total;
					$code1 = $row->SiteID;
					$company_name = "";
					$id_siteid = "";
					$sql2 = "SELECT * FROM `site_id` WHERE `siteid` = " . $this->db->escape($code1) . " LIMIT 1";
					$query2 = $this->mvd_db->query($sql2);
					if($query2->num_rows() > 0){
						$row2 = $query2->row();
						$company_name = $row2->company_name;
						$id_siteid = $row2->id;
					}
					$data = array(
						'id' => $id_siteid,
						'SiteID' => $code1,
						'company_name' => $company_name,
						'unique_parts' => $unique_parts,	// Unique Parts
						'total_unique' => $total_unique,	// Unique Connections
						'total' => $total,					// Accumulative Connections
						'total_parts_retrieval' => $total_parts_retrieval,
					);
					array_push($alldata, $data);
				}
			}
			return $alldata;
		}
		return false;
	}
	
	// Get Data Unside ID
	function get_un_siteid($timedate = "",$start_date = "", $end_date = ""){
		// Get value $where from date
		if($start_date == date("Y-m-d")){ 
			// Today: today `date` And Time
			$where = "WHERE DATE(`a`.`create_date`) = DATE(CURDATE()) AND `a`.`create_date` <= '" . date("Y-m-d") . " " . $timedate . "'";
		}
		else if($end_date == "" && $start_date != ""){
			// Day: `date` start date only
			$where = " WHERE  ( `a`.`create_date` LIKE " . $this->db->escape($start_date. "%") . ")";
		}
		else if($start_date != ""){
			// Range Day: `date` start date and end date
			$end_date2 = date("Y-m-d", strtotime($end_date . " + 1 days"));
			$where .= ($where == "" ? " WHERE " : " AND ") . " ( `a`.`create_date` BETWEEN " . $this->db->escape($start_date) . " 
						AND " . $this->db->escape($end_date2) . ")";
		}
		
		$sql = "SELECT `a`.`SiteID` AS `SiteID`, COUNT(`a`.`id`) AS `total`, COUNT(DISTINCT `a`.`systemid`, `a`.`ClaimID`, `a`.`SiteID`) AS `total_unique`,
				COUNT(DISTINCT `a`.`cDerivativeCode`, `b`.`cPartCode`) AS `unique_parts`,
				COUNT(DISTINCT `a`.`systemid`, `a`.`ClaimID`, `a`.`SiteID`, `a`.`cDerivativeCode`,`b`.`cPartCode`) AS `total_parts_retrieval`
				FROM `api_history` AS `a`, `api_history_parts` AS `b`
				$where AND `a`.`id` = `b`.`history_id` AND `a`.`SiteID` NOT IN (SELECT `siteid` FROM `site_id`) 
				GROUP BY `SiteID` ORDER BY `total_parts_retrieval` DESC";
		$query = $this->mvd_db->query($sql);
		
		$num_rows = $query->num_rows();
		$alldata  = array();
		if($num_rows > 0){
			foreach($query->result() as $row){
				$total_unique = $row->total_unique;
				$unique_parts = $row->unique_parts;
				$total_parts_retrieval = $row->total_parts_retrieval;
				$total = $row->total;
				$code1 = $row->SiteID;
				
				$data = array(
					'SiteID' => $code1,
					'unique_parts' => $unique_parts,
					'total_parts_retrieval' => $total_parts_retrieval,
					'total_unique' => $total_unique,
					'total' => $total,
				);
				array_push($alldata, $data);
			}
		}
		return $alldata;
	}
	
	// Get Data Claim Type
	function get_claimtype($timedate = "",$start_date = "", $end_date = ""){
		// Get value $where from date
		if($start_date == date("Y-m-d")){ 
			// Today: today `date` And Time
			$where = "WHERE DATE(`a`.`create_date`) = DATE(CURDATE()) AND `a`.`create_date` <= '" . date("Y-m-d") . " " . $timedate . "'";
		}
		else if($end_date == "" && $start_date != ""){
			// Day: `date` start date only
			$where = " WHERE  ( `a`.`create_date` LIKE " . $this->db->escape($start_date. "%") . ")";
		}
		else if($start_date != ""){
			// Range Day: `date` start date and end date
			$end_date2 = date("Y-m-d", strtotime($end_date . " + 1 days"));
			$where .= ($where == "" ? " WHERE " : " AND ") . " ( `a`.`create_date` BETWEEN " . $this->db->escape($start_date) . " 
						AND " . $this->db->escape($end_date2) . ")";
		}
		// Systemid != 00
		// $where .= ($where == "" ? " WHERE " : " AND ") . " `systemid` != '00'";
		
		// Get total sum `total_parts` , count row, for `SiteID`
		$sql = "SELECT SUM(total_parts) AS `sum_part`, COUNT(DISTINCT `systemid`, `ClaimID`, `SiteID`) AS `total`, COUNT(DISTINCT `ClaimID`) AS `total_unique`, `ClaimType` FROM `api_history` $where
				GROUP BY `ClaimType` ORDER BY sum_part DESC";
				
		$sql = "SELECT `a`.`ClaimType` AS `ClaimType`, COUNT(`a`.`id`) AS `total`, COUNT(DISTINCT `a`.`systemid`, `a`.`ClaimID`, `a`.`SiteID`) AS `total_unique`,
				COUNT(DISTINCT `a`.`cDerivativeCode`, `b`.`cPartCode`) AS `unique_parts`,
				COUNT(DISTINCT `a`.`systemid`, `a`.`ClaimID`, `a`.`SiteID`, `a`.`cDerivativeCode`,`b`.`cPartCode`) AS `total_parts_retrieval`
				FROM `api_history` AS `a`, `api_history_parts` AS `b`
				$where AND `a`.`id` = `b`.`history_id`
				GROUP BY `ClaimType` ORDER BY `total_parts_retrieval` DESC";
				
		$query = $this->mvd_db->query($sql);
		$claim = $this->claim_type();
		
		$num_rows = $query->num_rows();
		$alldata  = array();
		if($num_rows > 0){
			foreach($query->result() as $row){
				$total = $row->total;
				$total_unique = $row->total_unique;
				$unique_parts = $row->unique_parts;
				$total_parts_retrieval = $row->total_parts_retrieval;
				$code1 = $row->ClaimType;
				
				$name = $claim[$code1];
				$data = array(
					'ClaimType' => $code1,
					'name' => $name,
					'unique_parts' => $unique_parts,	// Unique Parts
					'total_unique' => $total_unique,	// Unique Connections
					'total' => $total,					// Accumulative Connections
					'total_parts_retrieval' => $total_parts_retrieval,
				);
				array_push($alldata, $data);
			}
		}
		return $alldata;
	}
	
	function get_category_232_details($name_page = "", $code1 = "",$timedate = "",$where = ""){
		if($code1 != ""){
			if($where != ""){
				$where_sql = $where;
			}
			else{
				$where_sql = $where_sql .= ($where_sql == "" ? " WHERE " : " AND ") . " DATE(`a`.`create_date`) = DATE(CURDATE()) 
							AND `a`.`create_date` <= '" . date("Y-m-d") . " " . $timedate . "'";
			}
			if($name_page == "Claim Type"){
				$sql232 = "SELECT COUNT(DISTINCT `a`.`cDerivativeCode`, `b`.`cPartCode`) AS `unique_parts`, `b`.`cCategoryCode` AS `cCategoryCode`,
						COUNT(DISTINCT `a`.`systemid`, `a`.`ClaimID`, `a`.`SiteID`, `a`.`cDerivativeCode`,`b`.`cPartCode`) AS `total_parts_retrieval`
						FROM `api_history` AS `a`, `api_history_parts` AS `b`
						$where_sql AND `a`.`id` = `b`.`history_id` AND `a`.`ClaimType` = " . $this->db->escape(trim($code1)) . " 
						GROUP BY `cCategoryCode` ORDER BY `total_parts_retrieval` DESC";
			}
			else if($name_page == "Software House"){
				$sql232 = "SELECT COUNT(DISTINCT `a`.`cDerivativeCode`, `b`.`cPartCode`) AS `unique_parts`, `b`.`cCategoryCode` AS `cCategoryCode`,
						COUNT(DISTINCT `a`.`systemid`, `a`.`ClaimID`, `a`.`SiteID`, `a`.`cDerivativeCode`,`b`.`cPartCode`) AS `total_parts_retrieval`
						FROM `api_history` AS `a`, `api_history_parts` AS `b`
						$where_sql AND `a`.`id` = `b`.`history_id` AND `b`.`systemid` = " . $this->db->escape(trim($code1)) . " 
						GROUP BY `cCategoryCode` ORDER BY `total_parts_retrieval` DESC";
			}
			else{
				$sql232 = "SELECT COUNT(DISTINCT `a`.`cDerivativeCode`, `b`.`cPartCode`) AS `unique_parts`, `b`.`cCategoryCode` AS `cCategoryCode`,
						COUNT(DISTINCT `a`.`systemid`, `a`.`ClaimID`, `a`.`SiteID`, `a`.`cDerivativeCode`,`b`.`cPartCode`) AS `total_parts_retrieval`
						FROM `api_history` AS `a`, `api_history_parts` AS `b`
						$where_sql AND `a`.`id` = `b`.`history_id` AND `b`.`SiteID` = " . $this->db->escape(trim($code1)) . " 
						GROUP BY `cCategoryCode` ORDER BY `total_parts_retrieval` DESC";
			}
			$query = $this->mvd_db->query($sql232 );
			$num_rows = $query->num_rows();
			$alldata  = array();
			if($num_rows > 0){
				foreach($query->result() as $row){
					$code = $row->cCategoryCode;
					$unique_parts = $row->unique_parts;
					$total_parts_retrieval = $row->total_parts_retrieval;
					$description = $id_code = "";
					$sql2 = "SELECT * FROM `category_232` WHERE `cCategoryCode` = " . $this->db->escape($code) . " LIMIT 1";
					$query2 = $this->mvd_db->query($sql2);
					if($query2->num_rows() > 0){
						$row2 = $query2->row();
						$description = $row2->cDescription;
						$id_code = $row2->id;
					}
					$data = array(
						'id' => $id_code,
						'code' => $code,
						'unique_parts' => $unique_parts,
						'total_parts_retrieval' => $total_parts_retrieval,
						'description' => $description,
					);
					array_push($alldata, $data);
				}
			}
			return $alldata;
		}
	}
	
	function get_data_parts_details($name_page = "",$code1 = "",$code = "", $timedate = "",$where = ""){
		if($code1 != ""){
			if($where != ""){
				$where_sql = $where;
			}
			else{
				$where_sql = $where_sql .= ($where_sql == "" ? " WHERE " : " AND ") . " DATE(`create_date`) = DATE(CURDATE()) 
							AND `create_date` <= '" . date("Y-m-d") . " " . $timedate . "'";
			}
			if($name_page == "Claim Type"){
				$sqldetails = "SELECT COUNT(DISTINCT `a`.`cDerivativeCode`, `b`.`cPartCode`) AS `unique_parts`, `b`.`cPartManDesc` AS `cPartManDesc`,
						COUNT(DISTINCT `a`.`systemid`, `a`.`ClaimID`, `a`.`SiteID`, `a`.`cDerivativeCode`,`b`.`cPartCode`) AS `total_parts_retrieval`
						FROM `api_history` AS `a`, `api_history_parts` AS `b`
						$where_sql AND `a`.`id` = `b`.`history_id` AND `a`.`ClaimType` = " . $this->db->escape(trim($code1)) . " 
						AND `b`.`cCategoryCode` = " . $this->db->escape($code) . " 
						GROUP BY `cPartManDesc` ORDER BY `total_parts_retrieval` DESC";
			}
			else if($name_page == "Software House"){
				$sqldetails = "SELECT COUNT(DISTINCT `a`.`cDerivativeCode`, `b`.`cPartCode`) AS `unique_parts`, `b`.`cPartManDesc` AS `cPartManDesc`,
						COUNT(DISTINCT `a`.`systemid`, `a`.`ClaimID`, `a`.`SiteID`, `a`.`cDerivativeCode`,`b`.`cPartCode`) AS `total_parts_retrieval`
						FROM `api_history` AS `a`, `api_history_parts` AS `b`
						$where_sql AND `a`.`id` = `b`.`history_id` AND `b`.`systemid` = " . $this->db->escape(trim($code1)) . " 
						AND `b`.`cCategoryCode` = " . $this->db->escape($code) . " 
						GROUP BY `cPartManDesc` ORDER BY `total_parts_retrieval` DESC";
				
			}
			else{
				$sqldetails = "SELECT COUNT(DISTINCT `a`.`cDerivativeCode`, `b`.`cPartCode`) AS `unique_parts`, `b`.`cPartManDesc` AS `cPartManDesc`,
						COUNT(DISTINCT `a`.`systemid`, `a`.`ClaimID`, `a`.`SiteID`, `a`.`cDerivativeCode`,`b`.`cPartCode`) AS `total_parts_retrieval`
						FROM `api_history` AS `a`, `api_history_parts` AS `b`
						$where_sql AND `a`.`id` = `b`.`history_id` AND `a`.`SiteID` = " . $this->db->escape(trim($code1)) . " 
						AND `b`.`cCategoryCode` = " . $this->db->escape($code) . " 
						GROUP BY `cPartManDesc` ORDER BY `total_parts_retrieval` DESC";
			}
			$alldata  = array();
			$query2 = $this->mvd_db->query($sqldetails);
			if($query2->num_rows() > 0) {
				foreach($query2->result() as $row2){
					$cPartManDesc = $row2->cPartManDesc;
					$unique_parts = $row2->unique_parts;
					$total_parts_retrieval = $row2->total_parts_retrieval;
					$data = array(
						'cPartManDesc' => $cPartManDesc,
						'unique_parts' => $unique_parts,
						'total_parts_retrieval' => $total_parts_retrieval,
					);
					array_push($alldata, $data);
				}
			}
			return $alldata;
		}
	}
	
	// Get Data Vehicle Make
	function get_parts_vehicle_make($timedate = "",$start_date = "", $end_date = ""){
		// Get value $where from date
		if($start_date == date("Y-m-d")){ 
			// Today: today `date` And Time
			$where = "WHERE DATE(`a`.`create_date`) = DATE(CURDATE()) AND `a`.`create_date` <= '" . date("Y-m-d") . " " . $timedate . "'";
		}
		else if($end_date == "" && $start_date != ""){
			// Day: `date` start date only
			$where = " WHERE  ( `a`.`create_date` LIKE " . $this->db->escape($start_date. "%") . ")";
		}
		else if($start_date != ""){
			// Range Day: `date` start date and end date
			$end_date2 = date("Y-m-d", strtotime($end_date . " + 1 days"));
			$where .= ($where == "" ? " WHERE " : " AND ") . " ( `a`.`create_date` BETWEEN " . $this->db->escape($start_date) . " 
						AND " . $this->db->escape($end_date2) . ")";
		}
		
		$sql_facturer = "SELECT * FROM `manufacturer_248`";
		$query_facturer = $this->mvd_db->query($sql_facturer);
		$ManufacturerArr = array();
		if($query_facturer->num_rows() > 0){
			foreach($query_facturer->result() as $row_facturer){
				$ManufacturerArr[$row_facturer->nManufacturerID] = $row_facturer->cDescription;
			}
		}
		$sqlfinal = "SELECT `c`.`nManufacturerID` AS `facturerID`, `a`.`cDerivativeCode` AS `cdcode`, COUNT(DISTINCT `a`.`cDerivativeCode`, `b`.`cPartCode`) AS `unique_parts`,
			COUNT(DISTINCT `a`.`systemid`, `a`.`ClaimID`, `a`.`SiteID`, `a`.`cDerivativeCode`,`b`.`cPartCode`) AS `total_parts_retrieval`
			FROM `api_history` AS `a`, `api_history_parts` AS `b`, `derivative_details_231` AS `c`
			$where AND `a`.`id` = `b`.`history_id` AND `a`.`cDerivativeCode` = `c`.`cDerivativeCode`
			GROUP BY `facturerID` ORDER BY `total_parts_retrieval` DESC";
		$query = $this->mvd_db->query($sqlfinal);
		$num_rows = $query->num_rows();
		$vehicleArr  = array();
		if($num_rows > 0){
			foreach($query->result() as $row){
				$code = $row->cdcode;
				$total_unique = $row->unique_parts;
				$total_parts_retrieval = $row->total_parts_retrieval;
				$facturerID = $row->facturerID;
				
				$data = array(
					'ManufacturerID' => $facturerID,
					'cDescription' => $ManufacturerArr[$facturerID],
					'total_unique' => $total_unique,
					'total_parts_retrieval' => $total_parts_retrieval,
				);
				array_push($vehicleArr, $data);
			}
		}
		return $vehicleArr;
	}
	
	function vehicle_make_type($code = "",$timedate = "",$where = ""){
		if($where != ""){
			$where_sql = $where;
		}
		else{
			$where_sql = $where_sql .= ($where_sql == "" ? " WHERE " : " AND ") . " DATE(`a`.`create_date`) = DATE(CURDATE()) 
						AND `a`.`create_date` <= '" . date("Y-m-d") . " " . $timedate . "' ";
		}
		
		$sql_facturer = "SELECT * FROM `model_247`";
		$query_model = $this->mvd_db->query($sql_facturer);
		$modelArr = array();
		if($query_model->num_rows() > 0){
			foreach($query_model->result() as $row_model){
				$modelArr[$row_model->nModelID] = $row_model->cDescription;
			}
		}
		
		$sql_body = "SELECT * FROM `body_shape_249`";
		$query_body = $this->mvd_db->query($sql_body);
		$bodyArr = array();
		if($query_body->num_rows() > 0){
			foreach($query_body->result() as $row_body){
				$bodyArr[$row_body->nBodyShapeID] = $row_body->cDescription;
			}
		}
		
		$sql = "SELECT `c`.`id` AS `dbID`, `c`.`cEngineSize` AS `cEngineSize`, `c`.`nManufacturerID` AS `nManufacturerID`, `c`.`cTrimLevel` AS `cTrimLevel`, 
			`c`.`dStartDate` AS `dStartDate`, `c`.`nModelID` AS `nModelID`, `c`.`nBodyShapeID` AS `nBodyShapeID`,
			`a`.`cDerivativeCode` AS `cdcode`, COUNT(DISTINCT `a`.`cDerivativeCode`,`b`.`cPartCode`) AS `unique_parts`,
			COUNT(DISTINCT `a`.`systemid`, `a`.`ClaimID`, `a`.`SiteID`, `a`.`cDerivativeCode`,`b`.`cPartCode`) AS `total_parts_retrieval`
			FROM `api_history` AS `a`, `api_history_parts` AS `b`, `derivative_details_231` AS `c`
			$where_sql AND `a`.`id` = `b`.`history_id` AND `a`.`cDerivativeCode` = `c`.`cDerivativeCode` AND `c`.`nManufacturerID` = " . $this->db->escape($code) . "
			GROUP BY `cdcode` ORDER BY `total_parts_retrieval` DESC";
			
		$query = $this->mvd_db->query($sql);
		$num_rows = $query->num_rows();
		$vehicleArr = array();
		$typeArr = array();
		if($num_rows > 0){
			foreach($query->result() as $row){
				$cdcode = $row->cdcode;
				$dbID = $row->dbID;
				$cEngineSize = $row->cEngineSize;
				$vehicletype = $row->cTrimLevel;
				$dStartDate = date('d/m/Y', strtotime($row->dStartDate));
				$nModelId = $row->nModelID;
				$nBodyShapeID =  $row->nBodyShapeID;
				$total_unique = $row->unique_parts;
				$total_parts_retrieval = $row->total_parts_retrieval;
				$text_graphs = $modelArr[$nModelId] . "," . $bodyArr[$nBodyShapeID] . "," . $cEngineSize . " " . $vehicletype . " - " . $dStartDate;
				
				$data = array(
					'dbID' => $dbID,
					'cdcode' => $cdcode,
					'description' => $text_graphs,
					'unique_parts' => $total_unique,
					'total_parts_retrieval' => $total_parts_retrieval,
				);
				array_push($vehicleArr, $data);
			}
		}
		return $vehicleArr;
	}
	
	function part_category_vehicle_make($code = "",$timedate = "",$where = ""){
		if($where != ""){
			$where_sql = $where;
		}
		else{
			$where_sql = $where_sql .= ($where_sql == "" ? " WHERE " : " AND ") . " DATE(`a`.`create_date`) = DATE(CURDATE()) 
						AND `a`.`create_date` <= '" . date("Y-m-d") . " " . $timedate . "' ";
		}
		$sql = "SELECT `b`.`cCategoryCode` AS `cCategoryCode`, COUNT(DISTINCT `a`.`cDerivativeCode`, `b`.`cPartCode`) AS `unique_parts`,
			COUNT(DISTINCT `a`.`systemid`, `a`.`ClaimID`, `a`.`SiteID`, `a`.`cDerivativeCode`,`b`.`cPartCode`) AS `total_parts_retrieval`
			FROM `api_history` AS `a`, `api_history_parts` AS `b`, `derivative_details_231` AS `c`
			$where_sql AND `a`.`id` = `b`.`history_id` AND `a`.`cDerivativeCode` = `c`.`cDerivativeCode` AND `c`.`nManufacturerID` = " . $this->db->escape($code) . "
			GROUP BY `cCategoryCode` ORDER BY `total_parts_retrieval` DESC";
			
		$query = $this->mvd_db->query($sql);
		$num_rows = $query->num_rows();
		$alldata  = array();
		if($num_rows > 0){
			foreach($query->result() as $row){
				$unique_parts = $row->unique_parts;
				$total_parts_retrieval = $row->total_parts_retrieval;
				$code = $row->cCategoryCode;
				$description = $id_code = "";
				$sql2 = "SELECT * FROM `category_232` WHERE `cCategoryCode` = " . $this->db->escape($code) . " LIMIT 1";
				$query2 = $this->mvd_db->query($sql2);
				if($query2->num_rows() > 0){
					$row2 = $query2->row();
					$description = $row2->cDescription;
					$id_code = $row2->id;
				}
				$data = array(
					'id' => $id_code,
					'code' => $code,
					'description' => $description,
					'unique_parts' => $unique_parts,
					'total_parts_retrieval' => $total_parts_retrieval,
				);
				array_push($alldata, $data);
			}
		}
		return $alldata;
	}
	
	function part_code_vehicle_make($code = "",$timedate = "",$where = ""){
		if($where != ""){
			$where_sql = $where;
		}
		else{
			$where_sql = $where_sql .= ($where_sql == "" ? " WHERE " : " AND ") . " DATE(`a`.`create_date`) = DATE(CURDATE()) 
						AND `a`.`create_date` <= '" . date("Y-m-d") . " " . $timedate . "' ";
		}
				
		$sql = "SELECT `b`.`cPartManDesc` AS `cPartManDesc`, COUNT(DISTINCT `a`.`cDerivativeCode`, `b`.`cPartCode`) AS `unique_parts`,
			COUNT(DISTINCT `a`.`systemid`, `a`.`ClaimID`, `a`.`SiteID`, `a`.`cDerivativeCode`,`b`.`cPartCode`) AS `total_parts_retrieval`
			FROM `api_history` AS `a`, `api_history_parts` AS `b`, `derivative_details_231` AS `c`
			$where_sql AND `a`.`id` = `b`.`history_id` AND `a`.`cDerivativeCode` = `c`.`cDerivativeCode` AND `c`.`nManufacturerID` = " . $this->db->escape($code) . "
			GROUP BY `cPartManDesc` ORDER BY `total_parts_retrieval` DESC";
		$query = $this->mvd_db->query($sql);
		$num_rows = $query->num_rows();
		$alldata  = array();
		if($num_rows > 0){
			foreach($query->result() as $row){
				$unique_parts = $row->unique_parts;
				$total_parts_retrieval = $row->total_parts_retrieval;
				$code = $row->cPartManDesc;
				$data = array(
					'id' => $id_code,
					'code' => $code,
					'unique_parts' => $unique_parts,
					'total_parts_retrieval' => $total_parts_retrieval,
				);
				array_push($alldata, $data);
			}
		}
		return $alldata;
	}
	
	function part_code_vehicle_make2($code = "",$vehicle_code = "", $timedate = "",$where = ""){
		if($where != ""){
			$where_sql = $where;
		}
		else{
			$where_sql = $where_sql .= ($where_sql == "" ? " WHERE " : " AND ") . " DATE(`a`.`create_date`) = DATE(CURDATE()) 
						AND `a`.`create_date` <= '" . date("Y-m-d") . " " . $timedate . "' ";
		}
		$sql = "SELECT `b`.`cPartManDesc` AS `cPartManDesc`, COUNT(DISTINCT `a`.`cDerivativeCode`, `b`.`cPartCode`) AS `unique_parts`,
			COUNT(DISTINCT `a`.`systemid`, `a`.`ClaimID`, `a`.`SiteID`, `a`.`cDerivativeCode`,`b`.`cPartCode`) AS `total_parts_retrieval`
			FROM `api_history` AS `a`, `api_history_parts` AS `b`, `derivative_details_231` AS `c`
			$where_sql AND `a`.`id` = `b`.`history_id` AND `a`.`cDerivativeCode` = `c`.`cDerivativeCode` 
			AND `c`.`nManufacturerID` = " . $this->db->escape($vehicle_code) . " AND `b`.`cCategoryCode` = " . $this->db->escape($code) . " 
			GROUP BY `cPartManDesc` ORDER BY `total_parts_retrieval` DESC";
		$query = $this->mvd_db->query($sql);
		$num_rows = $query->num_rows();
		$alldata  = array();
		if($num_rows > 0){
			foreach($query->result() as $row){
				$unique_parts = $row->unique_parts;
				$total_parts_retrieval = $row->total_parts_retrieval;
				$code = $row->cPartManDesc;
				$data = array(
					'id' => $id_code,
					'code' => $code,
					'unique_parts' => $unique_parts,
					'total_parts_retrieval' => $total_parts_retrieval,
				);
				array_push($alldata, $data);
			}
		}
		return $alldata;
	}
	
	// Get Data Vehicle Type
	function get_parts_vehicle_type($timedate = "",$start_date = "", $end_date = ""){
		// Get value $where from date
		if($start_date == date("Y-m-d")){ 
			// Today: today `date` And Time
			$where = "WHERE DATE(`a`.`create_date`) = DATE(CURDATE()) AND `a`.`create_date` <= '" . date("Y-m-d") . " " . $timedate . "'";
		}
		else if($end_date == "" && $start_date != ""){
			// Day: `date` start date only
			$where = " WHERE  ( `a`.`create_date` LIKE " . $this->db->escape($start_date. "%") . ")";
		}
		else if($start_date != ""){
			// Range Day: `date` start date and end date
			$end_date2 = date("Y-m-d", strtotime($end_date . " + 1 days"));
			$where .= ($where == "" ? " WHERE " : " AND ") . " ( `a`.`create_date` BETWEEN " . $this->db->escape($start_date) . " 
						AND " . $this->db->escape($end_date2) . ")";
		}
		
		$sql_facturer = "SELECT * FROM `manufacturer_248`";
		$query_facturer = $this->mvd_db->query($sql_facturer);
		$ManufacturerArr = array();
		if($query_facturer->num_rows() > 0){
			foreach($query_facturer->result() as $row_facturer){
				$ManufacturerArr[$row_facturer->nManufacturerID] = $row_facturer->cDescription;
			}
		}
		
		$sql_facturer = "SELECT * FROM `model_247`";
		$query_model = $this->mvd_db->query($sql_facturer);
		$modelArr = array();
		if($query_model->num_rows() > 0){
			foreach($query_model->result() as $row_model){
				$modelArr[$row_model->nModelID] = $row_model->cDescription;
			}
		}
		
		$sql_body = "SELECT * FROM `body_shape_249`";
		$query_body = $this->mvd_db->query($sql_body);
		$bodyArr = array();
		if($query_body->num_rows() > 0){
			foreach($query_body->result() as $row_body){
				$bodyArr[$row_body->nBodyShapeID] = $row_body->cDescription;
			}
		}
		
		$sqlfinal = "SELECT `a`.`cDerivativeCode` AS `cdcode`, COUNT(DISTINCT `a`.`cDerivativeCode`,`b`.`cPartCode`) AS `unique_parts`,
			COUNT(DISTINCT `a`.`systemid`, `a`.`ClaimID`, `a`.`SiteID`, `a`.`cDerivativeCode`,`b`.`cPartCode`) AS `total_parts_retrieval`
			FROM `api_history` AS `a`, `api_history_parts` AS `b`
			$where AND `a`.`id` = `b`.`history_id` 
			GROUP BY `cdcode` ORDER BY `total_parts_retrieval` DESC";
			
		$query = $this->mvd_db->query($sqlfinal);
		$num_rows = $query->num_rows();
		$vehicleArr = array();
		$typeArr = array();
		if($num_rows > 0){
			foreach($query->result() as $row){
				$cDerivativeCode = $row->cdcode;
				$total_unique = $row->unique_parts;
				$total_parts_retrieval = $row->total_parts_retrieval;
				$nBodyShapeID = $nModelId = $dStartDate = $id_code = $ManufacturerID = $vehicletype = $cEngineSize = "";
				$sql2 = "SELECT * FROM `derivative_details_231` WHERE `cDerivativeCode` = " . $this->db->escape($cDerivativeCode) . " LIMIT 1";
				$query_derivative = $this->mvd_db->query($sql2);
				if($query_derivative->num_rows() > 0){
					$row2 = $query_derivative->row();
					$cEngineSize = $row2->cEngineSize;
					$ManufacturerID = $row2->nManufacturerID;
					$vehicletype = $row2->cTrimLevel;
					$dStartDate = date('d/m/Y', strtotime($row2->dStartDate));
					$id_code = $row2->id;
					$nModelId = $row2->nModelID;
					$nBodyShapeID =  $row2->nBodyShapeID;
				}
				$dataA = array(
					'id' => $id_code,
					'code' => $cDerivativeCode,
					'text_graphs' => $ManufacturerArr[$ManufacturerID] . "," . $modelArr[$nModelId] . "," . $bodyArr[$nBodyShapeID] . "," . $cEngineSize . " " . $vehicletype . " - " . $dStartDate,
					'vehicletype' => $vehicletype,
					'total_unique' => $total_unique,
					'total_parts_retrieval' => $total_parts_retrieval,
				);
				array_push($vehicleArr, $dataA);
			}
		}
		
		return $vehicleArr;
	}
	
	function part_category_vehicle_type($code = "",$timedate = "",$where = ""){
		if($where != ""){
			$where_sql = $where;
		}
		else{
			$where_sql = $where_sql .= ($where_sql == "" ? " WHERE " : " AND ") . " DATE(`a`.`create_date`) = DATE(CURDATE()) 
						AND `a`.`create_date` <= '" . date("Y-m-d") . " " . $timedate . "' ";
		}
		
		$sql = "SELECT `b`.`cCategoryCode` AS `cCategoryCode`, COUNT(DISTINCT `a`.`cDerivativeCode`, `b`.`cPartCode`) AS `unique_parts`,
			COUNT(DISTINCT `a`.`systemid`, `a`.`ClaimID`, `a`.`SiteID`, `a`.`cDerivativeCode`,`b`.`cPartCode`) AS `total_parts_retrieval`
			FROM `api_history` AS `a`, `api_history_parts` AS `b`, `derivative_details_231` AS `c`
			$where_sql AND `a`.`id` = `b`.`history_id` AND `a`.`cDerivativeCode` = `c`.`cDerivativeCode` 
			AND `a`.`cDerivativeCode` = " . $this->db->escape($code) . " 
			GROUP BY `cCategoryCode` ORDER BY `total_parts_retrieval` DESC";
			
		$query = $this->mvd_db->query($sql);
		$num_rows = $query->num_rows();
		$alldata  = array();
		if($num_rows > 0){
			foreach($query->result() as $row){
				$unique_parts = $row->unique_parts;
				$total_parts_retrieval = $row->total_parts_retrieval;
				$code2 = $row->cCategoryCode;
				$description = $id_code = "";
				$sql2 = "SELECT * FROM `category_232` WHERE `cCategoryCode` = " . $this->db->escape($code2) . " LIMIT 1";
				$query2 = $this->mvd_db->query($sql2);
				if($query2->num_rows() > 0){
					$row2 = $query2->row();
					$description = $row2->cDescription;
					$id_code = $row2->id;
				}
				$data = array(
					'id' => $id_code,
					'code' => $code2,
					'description' => $description,
					'unique_parts' => $unique_parts,
					'total_parts_retrieval' => $total_parts_retrieval,
				);
				array_push($alldata, $data);
			}
		}
		return $alldata;
	}
	
	function part_code_vehicle_type($code = "",$timedate = "",$where = ""){
		if($where != ""){
			$where_sql = $where;
		}
		else{
			$where_sql = $where_sql .= ($where_sql == "" ? " WHERE " : " AND ") . " DATE(`a`.`create_date`) = DATE(CURDATE()) 
						AND `a`.`create_date` <= '" . date("Y-m-d") . " " . $timedate . "' ";
		}
				
		$sql = "SELECT `b`.`cPartManDesc` AS `cPartManDesc`, COUNT(DISTINCT `a`.`cDerivativeCode`, `b`.`cPartCode`) AS `unique_parts`,
			COUNT(DISTINCT `a`.`systemid`, `a`.`ClaimID`, `a`.`SiteID`, `a`.`cDerivativeCode`,`b`.`cPartCode`) AS `total_parts_retrieval`
			FROM `api_history` AS `a`, `api_history_parts` AS `b`, `derivative_details_231` AS `c`
			$where_sql AND `a`.`id` = `b`.`history_id` AND `a`.`cDerivativeCode` = `c`.`cDerivativeCode` 
			AND `a`.`cDerivativeCode` = " . $this->db->escape($code) . " 
			GROUP BY `cPartManDesc` ORDER BY `total_parts_retrieval` DESC";
		$query = $this->mvd_db->query($sql);
		$num_rows = $query->num_rows();
		$alldata  = array();
		if($num_rows > 0){
			foreach($query->result() as $row){
				$unique_parts = $row->unique_parts;
				$total_parts_retrieval = $row->total_parts_retrieval;
				$code = $row->cPartManDesc;
				$data = array(
					'id' => $id_code,
					'code' => $code,
					'unique_parts' => $unique_parts,
					'total_parts_retrieval' => $total_parts_retrieval,
				);
				array_push($alldata, $data);
			}
		}
		return $alldata;
	}
	
	function part_code_vehicle_type2($code = "",$type_code = "", $timedate = "",$where = ""){
		if($where != ""){
			$where_sql = $where;
		}
		else{
			$where_sql = $where_sql .= ($where_sql == "" ? " WHERE " : " AND ") . " DATE(`a`.`create_date`) = DATE(CURDATE()) 
						AND `a`.`create_date` <= '" . date("Y-m-d") . " " . $timedate . "' ";
		}
		
		$sql = "SELECT `b`.`cPartManDesc` AS `cPartManDesc`, COUNT(DISTINCT `a`.`cDerivativeCode`, `b`.`cPartCode`) AS `unique_parts`,
			COUNT(DISTINCT `a`.`systemid`, `a`.`ClaimID`, `a`.`SiteID`, `a`.`cDerivativeCode`,`b`.`cPartCode`) AS `total_parts_retrieval`
			FROM `api_history` AS `a`, `api_history_parts` AS `b`, `derivative_details_231` AS `c`
			$where_sql AND `a`.`id` = `b`.`history_id` AND `a`.`cDerivativeCode` = `c`.`cDerivativeCode` 
			AND `b`.`cCategoryCode` = " . $this->db->escape($code) . " AND `a`.`cDerivativeCode`  = " . $this->db->escape($type_code) . "
			GROUP BY `cPartManDesc` ORDER BY `total_parts_retrieval` DESC";
			
		$query = $this->mvd_db->query($sql);
		$num_rows = $query->num_rows();
		$alldata  = array();
		if($num_rows > 0){
			foreach($query->result() as $row){
				$unique_parts = $row->unique_parts;
				$total_parts_retrieval = $row->total_parts_retrieval;
				$code = $row->cPartManDesc;
				$data = array(
					'id' => $id_code,
					'code' => $code,
					'unique_parts' => $unique_parts,
					'total_parts_retrieval' => $total_parts_retrieval,
				);
				array_push($alldata, $data);
			}
		}
		return $alldata;
	}
	
	function get_ppc_industry_type()
	{
		$sql = "SELECT * FROM ppc_industry_type";
		$query = $this->mvd_db->query($sql);
		
		return $query;
	}
	function clear_dbcache()
	{
		$this->mvd_db->cache_delete_all();
	}
	
	function get_ppc_billing($type)
	{
		$where_sql .= ($where_sql == "" ? " WHERE " : " AND ") . " a.systemid = b.siteid ";
		$query = $this->mvd_db->query("SELECT COUNT(DISTINCT a.systemid) AS total FROM api_ppc_history a, site_id b " . $where_sql)->row();
		$data['total_rows'] = $query->total;
		
		$config['base_url'] = base_url() . 'mvd/report/ppc_billing/' . $type;
		$config['uri_segment'] = 5;
		$config['total_rows'] = $data['total_rows'];
		$config['per_page'] = 50;

		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();

		$sql = '';
		if($data['total_rows'] > 0 AND $type == 'html') {
			if($this->uri->segment($config['uri_segment']) && is_numeric($this->uri->segment($config['uri_segment'])))
			{
				$sql = ' LIMIT ' . $this->uri->segment($config['uri_segment']) . ', ' . $config['per_page'];
			}
			else
			{
				$sql = ' LIMIT 0, ' . $config['per_page'];
			}
		}
		$data['query'] = $this->mvd_db->query("SELECT a.systemid, b.company_name FROM api_ppc_history a , site_id b " . $where_sql . "GROUP BY a.systemid ORDER BY a.systemid ASC" . $sql );
		$data['search_column'] = $unserialized['search_column'];
		
		return $data;
	}
	
	
	function get_ppc_billing_search($type, $unserialized)
	{
		$sql_sort_column = trim($unserialized['sql_sort_column']);
		$sql_sort = trim($unserialized['sql_sort']);
		$search_column = trim($unserialized['search_column']);
		$start_date = trim($unserialized['start_date']);
		$end_date = trim($unserialized['end_date']);
		
		if($sql_sort_column != '') {
			if($sql_sort == 'ASC')
				$sql_sort = "DESC";
			else
				$sql_sort = 'ASC';
			
			$order_by = "ORDER BY " . $this->db->protect_identifiers($sql_sort_column) . " " . $sql_sort;
		}
		
		$where_sql = "";
		if($start_date != '') 
			$where_sql .= ($where_sql == "" ? " WHERE " : " AND ") . " a.create_date >="  . $this->db->escape($start_date . ' 00:00:00');
		
		if($end_date != '') 
			$where_sql .= ($where_sql == "" ? " WHERE " : " AND ") . " a.create_date <= " . $this->db->escape($end_date . ' 23:59:59');
		
		$where_sql .= ($where_sql == "" ? " WHERE " : " AND ") . " a.systemid = b.siteid ";
		
		$sql2 ="SELECT COUNT(DISTINCT a.systemid) AS total FROM api_ppc_history a, site_id b " . $where_sql;
		
		$query = $this->mvd_db->query($sql2)->row();
		$data['total_rows'] = $query->total;
		
		$config['base_url'] = base_url() . 'mvd/report/search_ppc_billing/' . $type . '/' . $this->uri->segment(5);
		$config['uri_segment'] = 6;
		$config['total_rows'] = $data['total_rows'];
		$config['per_page'] = 50;

		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();

		$limit = '';
		if($data['total_rows'] > 0 AND $type == 'html') {
			if($this->uri->segment($config['uri_segment']) && is_numeric($this->uri->segment($config['uri_segment'])))
			{
				$limit = ' LIMIT ' . $this->uri->segment($config['uri_segment']) . ', ' . $config['per_page'];
			}
			else
			{
				$limit = ' LIMIT 0, ' . $config['per_page'];
			}
		} 
		
		
		$data['query'] = $this->mvd_db->query("SELECT a.systemid, b.company_name FROM api_ppc_history a , site_id b " . $where_sql . " GROUP BY a.systemid ".$order_by . $limit);
		
		$data['sql_sort'] = $sql_sort;
		$data['search_str'] = $search_str;
		$data['search_column'] = $search_column;
		$data['start_date'] = $start_date;
		$data['end_date'] = $end_date;

		return $data;
	}
	
	function get_trasaction_details($system_id = "",$start_date = "", $end_date = ""){
		if($system_id != ""){
			$start_date = trim(datepicker2mysql($start_date ));
			$end_date = trim(datepicker2mysql($end_date));
			
			$where_sql = "";
			if($system_id != ""){
				$where_sql .= ($where_sql == "" ? " WHERE " : " AND ") . " `systemid` = "  . $this->db->escape($system_id);
			}
			
			if($start_date != ""){
				$where_sql .= ($where_sql == "" ? " WHERE " : " AND ") . " `create_date` >="  . $this->db->escape($start_date . ' 00:00:00');
			}
			
			if($end_date != ""){
				$where_sql .= ($where_sql == "" ? " WHERE " : " AND ") . " `create_date` <="  . $this->db->escape($end_date . ' 23:59:59');
			}
			
			$sql2 ="SELECT COUNT(DISTINCT a.cDerivativeCode, DATE(a.create_date)) AS total FROM api_ppc_history a " . $where_sql;
			$query = $this->mvd_db->query($sql2)->row();
			$data['total_rows'] = $query->total;
			
			$config['base_url'] = base_url() . 'mvd/report/trasaction_details/';
			$config['uri_segment'] = 4;
			$config['total_rows'] = $data['total_rows'];
			$config['per_page'] = 5;

			$this->pagination->initialize($config);

			$data['pagination'] = $this->pagination->create_links();

			$limit = '';
			if($data['total_rows'] > 0) {
				if($this->uri->segment($config['uri_segment']) && is_numeric($this->uri->segment($config['uri_segment']))){
					$limit = ' LIMIT ' . $this->uri->segment($config['uri_segment']) . ', ' . $config['per_page'];
				}
				else{
					$limit = ' LIMIT 0, ' . $config['per_page'];
				}
			}
			
			$sql = "SELECT `cDerivativeCode`, DATE(`create_date`) AS `cdate` FROM `api_ppc_history` " . $where_sql . " GROUP BY `cDerivativeCode`, DATE(`create_date`)" . $limit ;
			$data['q'] = $this->mvd_db->query($sql);
			$data['detail'] = array();
			if($data['q']->num_rows() > 0) {
				foreach($data['q']->result() as $row) {
					$dcode = $row->cDerivativeCode;
					$cdate = $row->cdate;
					$sqlchargeable = "SELECT `chargeable`, `create_date` FROM `api_ppc_history` WHERE `systemid` = "  . $this->db->escape($system_id) . " 
					AND `cDerivativeCode` = " . $this->db->escape($dcode) . "
					AND DATE(`create_date`) = " . $this->db->escape($cdate) . "";
					$data['detail'][$dcode . $cdate] = $this->mvd_db->query($sqlchargeable);
				}
			}
			
			return $data;
		}
		
		return false;
	}
	
	function get_name_vehicle_type($system_id = "",$start_date = "", $end_date = ""){
		if($system_id != ""){
			$start_date = trim(datepicker2mysql($start_date ));
			$end_date = trim(datepicker2mysql($end_date));
			
			$where_sql = "";
			if($system_id != ""){
				$where_sql .= ($where_sql == "" ? " WHERE " : " AND ") . " `systemid` = "  . $this->db->escape($system_id);
			}
			
			if($start_date != ""){
				$where_sql .= ($where_sql == "" ? " WHERE " : " AND ") . " `create_date` >="  . $this->db->escape($start_date . ' 00:00:00');
			}
			
			if($end_date != ""){
				$where_sql .= ($where_sql == "" ? " WHERE " : " AND ") . " `create_date` <="  . $this->db->escape($end_date . ' 23:59:59');
			}
			
			// GET NAME VEHICLE TYPE !!
			$sql_facturer = "SELECT * FROM `manufacturer_248`";
			$query_facturer = $this->mvd_db->query($sql_facturer);
			$ManufacturerArr = array();
			if($query_facturer->num_rows() > 0){
				foreach($query_facturer->result() as $row_facturer){
					$ManufacturerArr[$row_facturer->nManufacturerID] = $row_facturer->cDescription;
				}
			}
			
			$sql_facturer = "SELECT * FROM `model_247`";
			$query_model = $this->mvd_db->query($sql_facturer);
			$modelArr = array();
			if($query_model->num_rows() > 0){
				foreach($query_model->result() as $row_model){
					$modelArr[$row_model->nModelID] = $row_model->cDescription;
				}
			}
			
			$sql_body = "SELECT * FROM `body_shape_249`";
			$query_body = $this->mvd_db->query($sql_body);
			$bodyArr = array();
			if($query_body->num_rows() > 0){
				foreach($query_body->result() as $row_body){
					$bodyArr[$row_body->nBodyShapeID] = $row_body->cDescription;
				}
			}
		
			$sql2 = "SELECT * FROM api_ppc_history a " . $where_sql . " GROUP BY a.cDerivativeCode  ";
			$q2 = $this->mvd_db->query($sql2);
			$typeArr = array();
			if($q2->num_rows() > 0){
				foreach($q2->result() as $row){
					$code = $row->cDerivativeCode;
					$sql2 = "SELECT * FROM `derivative_details_231` WHERE `cDerivativeCode` = " . $this->db->escape($code) . " LIMIT 1";
					$query_derivative = $this->mvd_db->query($sql2);
					if($query_derivative->num_rows() > 0){
						$row2 = $query_derivative->row();
						$cEngineSize = $row2->cEngineSize;
						$ManufacturerID = $row2->nManufacturerID;
						$vehicletype = $row2->cTrimLevel;
						$dStartDate = date('d/m/Y', strtotime($row2->dStartDate));
						$id_code = $row2->id;
						$nModelId = $row2->nModelID;
						$nBodyShapeID =  $row2->nBodyShapeID;
					}
					$typeArr[$code] = $code . " - " . $ManufacturerArr[$ManufacturerID] . " " . $modelArr[$nModelId] . " " . $bodyArr[$nBodyShapeID] . "," . $cEngineSize . " " . $vehicletype . " - " . $dStartDate;
				}
			}
			return $typeArr;
		}
		return false;
	}
	
	function get_trasaction_data_pdf($system_id = ""){
		if($system_id != ""){
			$start_date = trim(datepicker2mysql($this->input->post('start_date')));
			$end_date = trim(datepicker2mysql($this->input->post('end_date')));
			
			$where_sql = " WHERE `systemid` = "  . $this->db->escape($system_id);
			if($start_date != ""){
				$where_sql .= ($where_sql == "" ? " WHERE " : " AND ") . " `create_date` >="  . $this->db->escape($start_date . ' 00:00:00');
			}
			
			if($end_date != ""){
				$where_sql .= ($where_sql == "" ? " WHERE " : " AND ") . " `create_date` <="  . $this->db->escape($end_date . ' 23:59:59');
			}
			$sql = "SELECT * FROM api_ppc_history a " . $where_sql . " GROUP by `systemid`,`cDerivativeCode`, DATE(`create_date`) ORDER BY DATE(`create_date`)";
			
			$q = $this->mvd_db->query($sql);
			
			if($q->num_rows() > 0 ){
				$res['system_id'] = $system_id;
				$res['start_date'] = $start_date;
				$res['end_date'] = $end_date;
				$res['query_transaction'] = $q;
				$res['user_details'] = $this->get_user_details($system_id);
				$res['state_details'] = $this->get_state_name($res['user_details']['company_state']);
				
				return $res;
			}
		}
		
		return false;
	}
	
	function get_tax_invoce_data($system_id = "",$monthyear =""){
		if($system_id != ""){
			$where_sql = " WHERE `systemid` = "  . $this->db->escape($system_id);
			if($monthyear != ""){
				$where_sql .= ($where_sql == "" ? " WHERE " : " AND ") . " MONTH(`create_date`) = "  . $this->db->escape(date('m', strtotime($monthyear))) . " AND YEAR(`create_date`) = " . $this->db->escape(date('Y', strtotime($monthyear)));
			}
			$sql = "SELECT * FROM api_ppc_history a " . $where_sql . " GROUP by `systemid`,`cDerivativeCode`, DATE(`create_date`) ORDER BY DATE(`create_date`)";
			
			$q = $this->mvd_db->query($sql);
			
			if($q->num_rows() > 0 ){
				$res['system_id'] = $system_id;
				$res['monthyear'] = $monthyear;
				// $res['monthyear'] = date("F, Y", strtotime($monthyear));
				$res['query_transaction'] = $q;
				$res['user_details'] = $this->get_user_details($system_id);
				$res['state_details'] = $this->get_state_name($res['user_details']['company_state']);
				
				return $res;
			}
		}
		
		return false;
	}
	
	
	private function get_user_details($system_id = ""){
		if($system_id != ""){
			$sql = "SELECT * FROM `api_ppc_user_details` WHERE `api_user_id` = (SELECT `id` FROM `api_users` WHERE `systemid` = " . $this->db->escape($system_id) . ")";
			$q = $this->mvd_db->query($sql);
			if($q->num_rows() > 0){
				return $q->row_array();
			}
		}
		
		return false;
	}

	private function get_state_name($state_id = 0){
		if(is_numeric($state_id) AND $state_id > 0 ){
			$sql = "SELECT * FROM `ppc_company_state` WHERE `id` = " . $this->db->escape($state_id);
			$q = $this->mvd_db->query($sql);
			if($q->num_rows() > 0){
				return $q->row_array();
			}
		}
		
		return false;
	}

	function generate_ppc_billing_excel($system_id, $start_date = "", $end_date = ""){
		$sql_company = "SELECT * FROM `site_id` WHERE `siteid` = " . $this->db->escape($system_id) . " LIMIT 1";
		$company_name = $this->mvd_db->query($sql_company)->row()->company_name;
		$where = " WHERE `systemid` = " . $this->db->escape($system_id);
		if($start_date != ""){
			$where .= ($where == "" ? " WHERE " : " AND ") . " `create_date` >="  . $this->db->escape($start_date . ' 00:00:00');
		}
		
		if($end_date != ""){
			$where .= ($where == "" ? " WHERE " : " AND ") . " `create_date` <="  . $this->db->escape($end_date . ' 23:59:59');
		}
		
		$query = $this->mvd_db->query("SELECT * FROM `api_ppc_history` " . $where . " ORDER BY `cDerivativeCode`, `create_date`");
		$objPHPExcel = new PHPExcel();
		
		$objPHPExcel->setActiveSheetIndex(0);    #Set Active Sheet
		$sheet = $objPHPExcel->getActiveSheet(); #Get Active Sheet
		
		$sheet->SetCellValue('A1', "PPC - Usage Transactions Details Listing for Site ID " . $system_id . " - " . $company_name);
		$sheet->getColumnDimension('A')->setWidth(20);
		$sheet->getColumnDimension('B')->setWidth(20);
		$sheet->getColumnDimension('C')->setWidth(20);
		$sheet->getColumnDimension('D')->setWidth(20);
		$sheet->getColumnDimension('E')->setWidth(20);
		$sheet->getColumnDimension('F')->setWidth(20);
		$sheet->getColumnDimension('G')->setWidth(20);
		$sheet->SetCellValue('A3', 'Site ID');
		$sheet->SetCellValue('B3', 'Claim ID');
		$sheet->SetCellValue('C3', 'Claim Type');
		$sheet->SetCellValue('D3', 'Derivative Code');
		$sheet->SetCellValue('E3', 'Vehicle Reg No.');
		$sheet->SetCellValue('F3', 'Accident Date');
		$sheet->SetCellValue('G3', 'Retrieval Date & Time');
		$sheet->getStyle('A3:G3')->getAlignment()->setWrapText(true); 
		$sheet->getStyle('A3:G3')->getFont()->setBold(true)->setSize(11);
		$sheet->getStyle('A3:G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('A3:G3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$styleArrayHeader = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'ffebcd')));
		$sheet->getStyle('A3:G3')->applyFromArray($styleArrayHeader);
		
		$row_excel = 3;
		#Retrieve ror by row
		if($query->num_rows() > 0){
			$claim_type_desc = $this->claim_type();
			$content_bg_color = 'background-color:'.$content_color.';';
			$tmp_dcode = '';
			foreach($query->result() as $row){
				$row_excel++;
				if($tmp_dcode != $row->cDerivativeCode){
					if($content_bg_color == ''){
						$content_bg_color = 'background-color:'.$content_color.';';
					}
					else{
						$content_bg_color = '';
					}
					$tmp_dcode = $row->cDerivativeCode;
				}
				if($content_bg_color != ''){
					$styleArrayHeader = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'e0ffff')));
					$sheet->getStyle('A'.$row_excel.':G'.$row_excel)->applyFromArray($styleArrayHeader);
				}
				$sheet->SetCellValue('A'.$row_excel, $row->systemid);
				$sheet->SetCellValue('B'.$row_excel, $row->ClaimID);
				$sheet->SetCellValue('C'.$row_excel, $claim_type_desc[$row->ClaimType]);
				$sheet->SetCellValue('D'.$row_excel, $row->cDerivativeCode);
				$sheet->SetCellValue('E'.$row_excel, $row->VehicleRegNo);
				$sheet->SetCellValue('F'.$row_excel, $row->AccidentDate);
				$sheet->SetCellValue('G'.$row_excel, $row->create_date);
			}
		}
		
		#HORIZONTAL ALIGNMENT SETTINGS
		$sheet->getStyle('A4:G'.$row_excel)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		#VERTICLE ALIGNMENT SETTINGS
		$sheet->getStyle('A4:G'.$row_excel)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		#BORDER SETTINGS
		$styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))); #Set Border style
		$sheet->getStyle('A3:G'.$row_excel)->applyFromArray($styleArray);
		#TEXT WRAPPING SETTINGS
		$sheet->getStyle('A3:G'.$row_excel)->getAlignment()->setWrapText(true);
		
		#Write output
		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header("Content-Disposition: attachment; filename=\"Report.xlsx\"");
		header("Cache-Control: max-age=0");
		$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
		$cacheSettings = array( ' memoryCacheSize ' => '100MB');
		PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
		$objWriter->save("php://output");
	}
	
	function generate_ppc_billing_pdf($system_id, $start_date = "", $end_date = ""){
		$data = $this->get_transaction_details2($system_id, $start_date, $end_date);
		if($data !== false){
			$data['system_id'] = $system_id;
			$data['start_date'] = $start_date;
			$data['end_date'] = $end_date;
			$sql_company = "SELECT * FROM `site_id` WHERE `siteid` = " . $this->db->escape($system_id) . " LIMIT 1";
			$data['company_name'] = $this->mvd_db->query($sql_company)->row()->company_name;
			$data['vehicle_type'] = $this->report_m->get_name_vehicle_type($system_id,$start_date,$end_date);
			
			$data['header_color'] = "BlanchedAlmond";
			$data['content_color'] = "LightCyan";
			
			$html = $this->load->view('mvd/ppc_usage_pdf1', $data, true);
			
			$html2pdf = new HTML2PDF('P','A4','en');
			$html2pdf->pdf->SetDisplayMode('fullpage');
			$html2pdf->WriteHTML($html);
			$html2pdf->Output('Report.pdf');
		}
		else{
			echo "Error has been occured. Please try again later.";
		}
	}
	
	/* For generate pdf */
	private function get_transaction_details2($system_id = "",$start_date = "", $end_date = ""){
		$where = " WHERE `systemid` = " . $this->db->escape($system_id);
		if($start_date != ""){
			$where .= ($where == "" ? " WHERE " : " AND ") . " `create_date` >="  . $this->db->escape($start_date . ' 00:00:00');
		}
		if($end_date != ""){
			$where .= ($where == "" ? " WHERE " : " AND ") . " `create_date` <="  . $this->db->escape($end_date . ' 23:59:59');
		}
		
		$sql = "SELECT `cDerivativeCode`, DATE(`create_date`) AS `cdate` FROM `api_ppc_history` " . $where . " GROUP BY `cDerivativeCode`, DATE(`create_date`)";
		$data['query'] = $this->mvd_db->query($sql);
		$data['detail'] = array();
		if($data['query']->num_rows() > 0) {
			foreach($data['query']->result() as $row) {
				$dcode = $row->cDerivativeCode;
				$cdate = $row->cdate;
				$sqlchargeable = "SELECT `chargeable`, `create_date` FROM `api_ppc_history` WHERE `systemid` = "  . $this->db->escape($system_id) . " "
								."AND `cDerivativeCode` = " . $this->db->escape($dcode) . " "
								."AND DATE(`create_date`) = " . $this->db->escape($cdate);
				$data['detail'][$dcode . $cdate] = $this->mvd_db->query($sqlchargeable);
			}
		}
		
		return $data;
	}
	
}