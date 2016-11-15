	<?php $this->load->view('user/header'); ?>
	
	<script type="text/javascript">
		function delete_user(user_id) {
			if(confirm("Are you sure to delete this user?")) {
				$('#remove_user_id').val(user_id);
				$('#remove_frm').submit();	
			}
		}
	</script>			
				<!-- BEGIN PAGE TITLE-->
				<form id="remove_frm" name="remove_frm" action="<?php echo site_url()?>/user/customer_remove/" method="post">
					<input type="hidden" name="remove_user_id" id="remove_user_id" value="" />
				</form>
				<!-- END PAGE TITLE-->
				<!-- END PAGE HEADER-->
				<br />
				<br />
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box blue-soft">
							<div class="portlet-title">
								<div class="caption">
									Order Listing
								</div>
								<!--<div class="tools">
									<a href="javascript:;" class="collapse"> </a>
									<a href="#portlet-config" data-toggle="modal" class="config"> </a>
									<a href="javascript:;" class="reload"> </a>
									<a href="javascript:;" class="remove"> </a>
								</div>-->
							</div>
							
							<div class="portlet-body">
								<!--<form class="form-horizontal" action="http://mrd.my/rim_member/search/" method="POST">
									<div class="form-group table-responsive">
										<div class="col-sm-3" style="padding-left: 0;">
											&nbsp;
										</div>
										<label class="control-label col-md-2" style="padding-left: 0;">Search By</label>
										<div class="col-sm-2" style="padding-left: 0;">
											<select class="form-control" name="search_by" id="search_by">
												<option value="all" >All</option>
												<option value="ref_no" >Ref No.</option>
												<option value="company_name" >Company Name</option>
												<option value="company_reg_no" >Company Reg. No.</option>
												<option value="category_name" >Category Name</option>
												<option value="registration_status" >Registration Status</option>
												<option value="status" >Status</option>
											</select>
										</div>
										<div class="col-sm-3 search-all" style="padding-left: 0;">
											<input type="text" class="form-control" id="search_member" name="search_member" placeholder="Search" value="" />
										</div>
										<div class="col-md-3 search-reg-stat" style="padding-left: 0;" hidden>
											<select class="form-control" name="search_member" id="search_registration_status">
												<option value="pending" >Pending</option>
												<option value="not complete" >Not Complete</option>
												<option value="approved" >Approved</option>
												<option value="rejected" >Rejected</option>
											</select>
										</div>
										<div class="col-md-3 search-category-name" style="padding-left: 0;" hidden>
											<select class="form-control" name="search_member" id="search_category_name">
												<option value="A" >A</option>
												<option value="B" >B</option>
												<option value="C" >C</option>
												<option value="D" >D</option>
												<option value="E" >E</option>
												<option value="F" >F</option>
												<option value="G" >G</option>
											</select>
										</div>
										<div class="col-md-3 search-status" style="padding-left: 0;" hidden>
											<select class="form-control" name="search_member" id="search_status">
												<option value="active" >Active</option>
												<option value="inactive" >Inactive</option>
												<option value="expired" >Expired</option>
												<option value="pending" >Pending</option>
											</select>
										</div>
										<div class="col-sm-1" style="padding-left: 0;">
											<button class="btn btn-success form-control search-btn" type="submit" name="search">Search <i class="fa fa-search"></i></button>
										</div>
										<div class="col-sm-1" style="padding-left: 0;">
											<button class="btn btn-default form-control reset-btn" type="submit" name="reset" value="reset">Reset <span class="fa fa-refresh"></span></button>
										</div>
									</div>
								</form>-->
								<?php 
									$berjaya = $this->session->flashdata('success');
									$error = $this->session->flashdata('error');
								if(isset($berjaya)) { ?>
								<div class="alert bg-green-meadow bg-font-green-meadow alert-dismissable notify">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
									<i class="fa fa-check-circle"></i> <strong>Success!</strong> <?php echo $berjaya; ?>
								</div>
								<?php } 
									else if(isset($error)){ ?>
								<div class="alert bg-red-mint bg-font-red-mint alert-dismissable notify">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
									<i class="fa fa-warning"></i> <strong>Error!</strong> <?php echo $error; ?> 
								</div>
								 <?php }?>
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover table-header-fixed">
										<thead>
											<tr>
												<th> # </th>
												<th style="text-align:center" width="10%"> Order Date </th>
												<th style="text-align:center"> Customer Name </th>
												<th style="text-align:center" width="10%"> Contact No.</th>
												<!--<th> Payment Status </th>-->
												<th style="text-align:center"> Product Name </th>
												<th style="text-align:center"> Quantity</th>
												<th style="text-align:center" width="10%"> Order Status </th>
												<th style="text-align:center" width="15%"> Tracking Number </th>
												<th style="text-align:center" width="10%"> Action </th>
											</tr>
										</thead>
										<tbody>
									<?php if($cust_query->num_rows() > 0) { 
											$alt = 0;	

											foreach($cust_query->result() as $row) {
												$alt++;
												$rowspan = 1;
												$array_name = array();
												$array_quantity = array();
												
												if(isset($product_query[$row->id]) && $product_query[$row->id]->num_rows() > 0){
													$rowspan = $product_query[$row->id]->num_rows();
													foreach ($product_query[$row->id]->result() as $data_row) {
														array_push($array_quantity,$data_row->order_quantity);
														array_push($array_name,$product_query["name"][$data_row->product_id]);
													}					
												}
												else{
													array_push($array_name, "-");
													array_push($array_quantity, "-");
												}
												
												?>
												<tr>
													<td rowspan="<?php echo $rowspan; ?>"> <?php echo $alt; ?> </td>
													<?php 
														$date = explode("-",$row->customer_order_date); 
														$order_date = array($date[2], $date[1], $date[0]);
														$customer_order_date = implode("-",$order_date);
														
													?>
													<td style="text-align:center" rowspan="<?php echo $rowspan; ?>"> <?php echo $customer_order_date; ?> </td>
													<td style="text-align:left" rowspan="<?php echo $rowspan; ?>"> <?php echo $row->customer_name; ?> </td>
													<td rowspan="<?php echo $rowspan; ?>"> <?php echo $row->customer_contact_no; ?> </td>
													<!--<td> <?php// echo $row->customer_payment_status; ?> </td>-->
													<td >
														<?php echo $array_name[0]; ?>
													</td>
													<td >
														<?php echo $array_quantity[0]; ?>
													</td>
													<td style="text-align:center" rowspan="<?php echo $rowspan; ?>" > 
													<?php $orststus = $row->customer_order_status; ?>
													<?php if($orststus == "Ordered"){ ?>
														<span class="label label-sm label-success"> <?php echo $orststus; ?> </span>
													<?php } else if($orststus == "Delivered") {?>
														<span class="label label-sm label-info"> <?php echo $orststus; ?></span>
													<?php } else if($orststus == "Received"){ ?>
														<span class="label label-sm label-info"> <?php echo $orststus; ?> </span>
													<?php } ?>														
													</td>
													<td style="text-align:center" rowspan="<?php echo $rowspan; ?>"> <?php echo $row->customer_tracking_no; ?></td>
													<td style="text-align:center" rowspan="<?php echo $rowspan; ?>"> <a href="<?php echo site_url()?>user/edit/<?php echo $row->id?>" title="View/Edit" class="btn btn-sm btn-info"> <i class="fa fa-pencil"> </i> </a> <a href="#" onclick="delete_user(<?php echo $row->id?>);" title="Delete Customer" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>   </td>
												</tr>
												<?php 
														if($rowspan > 1){
															foreach($array_name as $k => $v){
																if($k != 0){
																	echo "<tr><td>" . $v . "</td>";
																	echo "<td>" .$array_quantity[$k] . "</td></tr>";
																}
															}
														}
													?>
											<?php	
											}
										} else {
										?>
										<td> No order data to be listed </td>
										<?php }?>
										</tbody>
									</table>
									<?php echo $pagination; ?> 
								</div>
							</div>
						</div>
					</div>
				</div>
				</div>
				<!-- END CONTENT BODY -->
			</div>
			<!-- END CONTENT -->
		</div>
		<!-- END CONTAINER -->
		<?php $this->load->view('user/footer'); ?>
		<script>
		window.setTimeout(function() {
			$(".notify").fadeTo(500, 0).slideUp(500, function(){
			$(this).remove(); 
			});
		}, 2500);
		</script>
    </body>
</html>