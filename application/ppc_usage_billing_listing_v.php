<?php
// echo ;
// exit();
if($this->uri->segment(4) == 'xls') { 
	require_once './assets/phpexcel/Classes/PHPExcel.php';

	$objPHPExcel = new PHPExcel();
	
	$fieldmap_arr = array(
					"id" => 'DB ID',
					"systemid" => 'iCap System ID',
					"SiteID" => 'Site ID',
					"ClaimID" => 'Claim ID',
					"ClaimType" => 'Claim Type',
					"cDerivativeCode" => 'Derivative Code',
					"VehicleRegNo" => 'Vehicle Reg No',
					"AccidentDate" => 'Accident Date',
					"MRCdbRevisionRequested" => 'MRCdb Requested',
					"MRCdbRevision" => 'MRCdb Output',
					"total_parts" => 'Total Parts Requested',
					"cPartManDesc" => 'Parts Description',
					"create_date" => 'Retrieval Date & Time'
			);
			
	$col = 0;
	foreach ($table_fields as $field) {
		if($field == 'history_id') continue;
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col++, 1, $fieldmap_arr[$field]);
	}
	
	$row = 2;
	foreach($query->result() as $data)
	{
		$col = 0;
		foreach ($table_fields as $field) {
			// $date_columns = array('AccidentDate', 'create_date');
			// if(in_array($field, $date_columns))
				// $field_data = convert_mvd_date($data->$field, true);
			// else
				$field_data = $data->$field;
			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $field_data);
		}
			
		$row++;
	}

	for ($i = 'A'; $i !=  $objPHPExcel->getActiveSheet()->getHighestColumn(); $i++) {
		$objPHPExcel->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE); //Resize the column
		$objPHPExcel->getActiveSheet()->getStyle($i . '1:' . $i . $row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT); //Set all columns as string
	}
	$objPHPExcel->getActiveSheet()->getStyle('A1:' . $i . '1')->getFont()->setBold(true);
	
	$objPHPExcel->getActiveSheet()->setTitle('Parts Price Retrieval Log');
	$objPHPExcel->setActiveSheetIndex(0);

	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="Parts_Price_Retrieval_Log.xls"');
	header('Cache-Control: max-age=0');
	header('Cache-Control: max-age=1');

	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit();
}
?>

<?php
$this->load->view('header_v')?>
			<style type="text/css"> @import url("<?php echo base_url()?>assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css"); </style>
			<style type="text/css"> @import url("<?php echo base_url()?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css"); </style>
			<?php get_message(); ?>
			<div class="row">
				 <div class="col-md-12">
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption font-green-sharp">
								<i class="icon-list font-green-sharp"></i>
								<span class="caption-subject bold uppercase">Retrieval Listing</span>
							</div>
							<div class="actions">
								<button type="button" class="btn green tax_report"> <i class="fa fa-file-pdf-o"></i> Generate Tax Invoice (PDF)</button>
								<button type="button" class="btn red-mint soa_report"> <i class="fa fa-file-pdf-o"></i> Generate SOA (PDF)</button>
							</div>
						</div>
						<form action="<?php echo base_url()?>mvd/report/search_ppc_billing/html/<?php echo $this->uri->segment(5)?>" method="post" class="search-form"> 
						<div class="row">
							<div class="form-body">
								<div class="col-md-5">
									<!--<input type="text" class="form-control" name="search_str" value="<?php echo $search_str?>" placeholder="Search">-->
									<input type="hidden" name="sql_sort" value="<?php echo $sql_sort?>" />
									<input type="hidden" name="sql_sort_column" value="" class="sql-sort-column" />
								</div>
								
							</div>
						</div>
						<br />
						<div class="row">
							<div class="form-body">
								<div class="col-md-5">
									<div class="form-group">
									<?php
									$start_date = isset($start_date) && $start_date != "0000-00-00" && $start_date != "" ? date("d/m/Y", strtotime($start_date)) : '';
									$end_date = isset($end_date) && $end_date != "0000-00-00" && $end_date != "" ? date("d/m/Y", strtotime($end_date)) : '';
									?>
									<label class="control-label">Date Range</label>
										<div class="input-group date-picker input-daterange" data-date-format="dd/mm/yyyy">
											<input type="text" class="form-control startDate" name="start_date"
											value="<?php echo $start_date; ?>"
											placeholder="dd/mm/yyyy" />
											<span class="input-group-addon"> to </span>
											<input type="text" class="form-control endDate" name="end_date"
											value="<?php echo $end_date; ?>"
											placeholder="dd/mm/yyyy" />
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="clear">&nbsp;</div>
						<div class="row">
							<div class="col-md-2">
								<input type="submit" class="btn green" name="search_btn" value="Search" />
								<a href="<?php echo base_url()?>mvd/report/ppc_billing" class="btn blue">Reset</a>
							</div>
						</div>
						</form>
						
						<div class="portlet-body">
							<small>Total Results: <?php echo $total_rows?></small>
							<div class="table-responsive">
							<form action="" id="genrate_soa_report" method="post" target='_blank'>
							<input type="hidden" class="" name="start_date"
									value="<?php echo $start_date; ?>" />
									<input type="hidden" name="end_date"
									value="<?php echo $end_date; ?>" />
											
								<table class="table table-striped table-bordered table-hover">
									<input name="uri" type="hidden" value="<?php echo $this->uri->uri_string(); ?>" />
									<thead>
										<tr>
											<th width="1%"> <a href="#" class="tbl-sorting" data-col="" >No</a> </th>
											<th width="20%" nowrap> <a href="#" class="tbl-sorting" data-col="systemid" >Site ID</a><br /><a href="#" class="tbl-sorting" data-col="SiteID">Company Name</a> </th>
											<th width="20%">Statement of Account(SOA)
												<br />
												<label class="mt-checkbox mt-checkbox-outline" style="margin-bottom: -5px !important;">
													<input type="checkbox" class="select-all"> Select All
													<span></span>
												</label>
											</th>
											<th width="20%">Transaction Details</th>
										</tr>
									</thead>
									<tbody>
										<?php
											$count = 0;
											if($this->uri->segment(5) != "" AND is_numeric($this->uri->segment(5))){
												$count = $this->uri->segment(5);
											}
											else if($this->uri->segment(6) != "" AND is_numeric($this->uri->segment(6))){
												$count = $this->uri->segment(6);
											}
											if($query->num_rows() > 0) {
												foreach($query->result() as $row) {
													$count++;
													$row = (object) html_escape((array) $row);
													
										?>
											<tr class="tbl_ppc_billing">
												<td nowrap> <?php echo $count; ?> </td>
												<td nowrap> <?php echo $row->systemid; ?><br /><?php echo $row->company_name ?> </td>
												<td nowrap> 
													<label class="mt-checkbox mt-checkbox-outline" style="margin-bottom: -5px !important;">
														<input type="checkbox" class="select-soa" value="<?php echo $row->systemid?>" name="select_soa[]">Select
														<span></span>
													</label>  
												</td>
											<td nowrap>
											<?php
											$rowdata['systemid'] = $row->systemid;
											$rowdata['company_name'] = $row->company_name;
											$rowdata['start_date'] = $start_date;
											$rowdata['end_date'] = $end_date;
											
											$pbase64 = trim(base64_encode(serialize($rowdata)), "=.");
											?>
											<a href="javascript:;" class="open-total" data-system-id="<?php echo $row->systemid?>" data-company-name="<?php echo htmlentities($row->company_name); ?>" 
											 data-pbase64="<?php echo $pbase64; ?>" ><i class="fa fa-external-link"></i></a> 
											</td>
											</tr>
										<?php 	 }
											}
											else {
												 ?>
												 
											<tr>
												<td nowrap colspan="4"><center> No data in this period</center></td>
											</tr>
										<?php 
											}
										?>
									</tbody>
								</table>
							</form>
								<input class="system_id" type="hidden"/><input class="company-name" type="hidden"/>
							</div>
							<?php echo $pagination?>
						</div>
					</div>
				</div>
			</div>
			<div class="modal fade bs-modal-lg transaction-detail-modal" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title">Transaction Details</h4>
						</div>
						<div class="modal-body transaction-detail"> Loading ... </div>
						<div class="modal-footer">
							<!--<a href="<?php echo base_url() . $this->uri->segment(1) . '/' . $this->uri->segment(2) . '/total_parts/' . $this->uri->segment(4) . '/xls/'?>" class="btn green-meadow parts-detail-xls">
									<i class="fa fa-file-excel-o"></i> XLS </a>-->
							<button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
			
			 <div class="modal fade bs-modal-sm soa_notify_modal" id="small" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header bg-red-thunderbird bg-font-red-thunderbird">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title"><i class="fa fa-warning"></i> Alert</h4>
						</div>
						<div class="modal-body error_modal">  </div>
						<div class="modal-footer">
							<button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
		
			<?php $this->load->view('footer_v')?>
			
            <!-- BEGIN PAGE LEVEL PLUGINS -->
           <script src="<?php echo base_url()?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
		   <script src="<?php echo base_url()?>assets/global/scripts/jquery.form.js"></script>
		   
		   <!-- DATE PLUGINS -->
			<script src="<?php echo base_url()?>assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
			<script src="<?php echo base_url()?>assets/pages/scripts/components-date-time-pickers.js" type="text/javascript"></script>
            <!-- END PAGE LEVEL PLUGINS -->
            <script>
			var base_url = "<?php echo base_url(); ?>";

			$(function() {
				var start_date = $('.startDate').val();
				var end_date = $('.endDate').val();
					
				//Sorting the table when clicked at the each column
				$(".tbl-sorting").click(function() {
					$(".sql-sort-column").val($(this).attr('data-col'));
					$('.search-form').submit();
				});
				
				//When clicked to see the transaction details info
				$(".open-total").mousedown(function(e) {
					var mouse = e.button;
					if(mouse == 0){
						if(system_id != ""){
							var system_id = $(this).data('system-id');
							var company_name = $(this).data('company-name');
							$('.system_id').val(system_id);
							$('.company-name').val(company_name);
							$.ajax({
								method: "POST",
								data:{'system_id':system_id,'company_name':company_name,'start_date':start_date, 'end_date':end_date},
								url: base_url + "mvd/report/transaction_details/",
								success: function(data){
									$(".transaction-detail").html(data);
									$('.transaction-detail-modal').modal('show');
								}
							})
						}
					}
					else if(mouse == 2){
						var pbase64 = $(this).data('pbase64');
						window.open(base_url + "mvd/report/ptransaction_details/"+ pbase64, '_blank');
					}
					
				});
				
				//click pagination in modal trasaction details
				$('.transaction-detail-modal').on('click', '.pagination a', function(e){
					e.preventDefault();
					var url = $.trim($(this).attr('href'));
					var system_id = $('.system_id').val();
					var company_name =$('.company-name').val();
					
					if(url != '#'){
						console.log(system_id);
						App.blockUI({
							target: '.table-transaction',
							animate: true
						});
						$.ajax({
							url: url,
							type: 'POST',
							data:{'system_id':system_id,'company_name':company_name, 'start_date':start_date, 'end_date':end_date},
							success: function(data){
								$(".transaction-detail").html(data);
								$('.transaction-detail-modal').modal('show');
							},error: function (textStatus, errorThrown) {
								App.unblockUI('.table-transaction');
							}
						}).done(function(){
							App.unblockUI('.table-transaction');
						});
					}
				});
				
				//generate report SOA
				$('.soa_report').on('click', function(){
					tbllength = $('.tbl_ppc_billing').length;
					has_check = false;
					for(i=0; i<tbllength; i++){
						check = $($('.tbl_ppc_billing')[i]).find('.select-soa').is(':checked');
						if(check){
							has_check = true;
							break;
						}
					}
					if(has_check == true){
						$('#genrate_soa_report').attr('action', '<?php echo base_url() ?>mvd/report/soa_report');
						$('#genrate_soa_report').submit();
					}
					else{
						$('.error_modal').html("Please select data to generate SOA report")
						$('.soa_notify_modal').modal('show');
					}
				});
				
				//generate tax_invoice
				$('.tax_report').on('click', function(){
					tbllength = $('.tbl_ppc_billing').length;
					has_check = false;
					for(i=0; i<tbllength; i++){
						check = $($('.tbl_ppc_billing')[i]).find('.select-soa').is(':checked');
						if(check){
							has_check = true;
							break;
						}
					}
					if(has_check == true){
						$('#genrate_soa_report').attr('action', '<?php echo base_url() ?>mvd/report/tax_invoice');
						$('#genrate_soa_report').submit();
					}
					else{
						$('.error_modal').html("Please select data to generate Tax Invoice")
						$('.soa_notify_modal').modal('show');
					}
				});
				
				$('.select-all').on('change', function(){
					check = $('.select-all').is(':checked');
					if(check){
						$('.select-soa').prop('checked', true);
					}
					else{
						$('.select-soa').prop('checked', false);
					}
					
				});
				
			});
			</script>
    </body>

</html>