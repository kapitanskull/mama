	<?php $this->load->view('user/header'); ?>
	<link href="<?php echo base_url();?>assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css" />
	
	<!-- this plugin.min.css must be below than fancybox.css so this file repeatitive in this php file because it already been 
	declare in header.php which are been use by other file-->
	<link href="<?php echo base_url();?>assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" /> 
	
	<script type="text/javascript">
		function delete_product(product_id) {
			if(confirm("Are you sure to delete this product?")) {
				$('#remove_product_id').val(product_id);
				$('#remove_frm').submit();	
			}
		}
	</script>			
				<!-- BEGIN PAGE TITLE-->
				<form id="remove_frm" name="remove_frm_product" action="<?php echo site_url()?>/user/product_remove/" method="post">
					<input type="hidden" name="remove_product_id" id="remove_product_id" value="" />
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
									Product Listing
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
												<th width="4%"> # </th>
												<th style="text-align:center" width="15%">Image </th>
												<th> Product Name </th>
												<th> Price </th>
												<th> Commision</th>
												<th style="text-align:center" width="10%"> Action</th>
											</tr>
										</thead>
										<tbody>
									<?php if($product_query->num_rows() > 0) { 
											$alt = 0;	

											foreach($product_query->result() as $row) {
												$alt++;
											?>
												<tr>
													<td> <?php echo $alt; ?> </td>
													<?php if($row->product_image_path != '') { ?>
														<td>															
															<a href="<?php echo base_url() ?>uploads/<?php echo $row->product_image_path; ?>" class="fancybox-button" data-rel="fancybox-button">
                                                            <img class="img-responsive" src="<?php echo base_url() ?><?php echo $row->product_image_path; ?>" alt=""> </a>
														</td>
													<?php } else{ ?>
														<td> 
                                                            <img class="img-responsive" src="<?php echo base_url() ?>uploads/no_img.png" alt=""> </a> </td>
													<?php } ?>
													
													<td> <?php echo $row->product_name; ?> </td>
													<td> <?php echo $row->product_price; ?> </td>
													<td> <?php echo $row->product_commision; ?> </td>
													
													<td style="text-align:center"> <!--<a href="<?php echo site_url()?>user/edit/<?php echo $row->id?>" title="View/Edit" class="btn btn-icon-only blue"> <i class="fa fa-pencil"> </i> </a>--> <a href="#" onclick="delete_product(<?php echo $row->id?>);" title="Delete Product" class="btn btn-icon-only red"><i class="fa fa-trash"></i></a></td>
												</tr>
											<?php	
											}
										}else {
										?>
										<td colspan="6" style="text-align:center"> No product to be listed </td>
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
		<script src="<?php echo base_url();?>assets/global/plugins/fancybox/source/jquery.fancybox.pack.js" type="text/javascript"></script>
	
    </body>
</html>