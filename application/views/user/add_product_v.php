	<?php $this->load->view('user/header'); ?>
	<!-- BEGIN PAGE LEVEL PLUGINS -->
	<link href="<?php echo base_url();?>assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url();?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
	<!-- END PAGE LEVEL PLUGINS -->
           
                        <!-- BEGIN PAGE TITLE-->
                        <h1 class="page-title"> Add Product</h1>
						<span class="help-block"> <font style="color:red;">*</font> <i>marked as mandatory</i> </span>
							<?php $error = $this->session->flashdata('error');
								if(isset($error)){
							?>
							<div class="alert bg-red-mint bg-font-red-mint alert-dismissable notify">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
								<i class="fa fa-warning"></i> <strong>Error!</strong> <?php echo $error; ?> 
							</div>
								<?php } ?>
                        <!-- END PAGE TITLE-->
						
						<div class="row">
							<div class="col-md-12">
								<div class="portlet box purple-wisteria">
									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-gift" style="color:white"></i>Product Details
											
										</div>
									</div>
									<div class="portlet-body form">
										<!-- BEGIN FORM-->
										<form action="<?php echo base_url(); ?>user/add" class="form-horizontal" method="post">
											<div class="form-body">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label col-md-4">Product Name <font style="color:red;">*</font></label>
															<div class="col-md-8">
																<input type="text" class="form-control" placeholder="Product name" name="product_name" value="<?php echo (isset($post['product_name']) ? $post['product_name'] : "") ?>">
																<!--<span class="help-block"> This is inline help </span>-->
															</div>
														</div>
													</div>
													<!--/span-->
													<div class="col-md-6">
														<div class="form-group">
															
														</div>
													</div>
													<!--/span-->
												</div>
												<!--/row-->
												<div class="row">
													<!--/span-->
													 <div class="col-md-6">
														<div class="form-group">
															<label class="control-label col-md-4">Product price (RM)<font style="color:red;">*</font></label>
															<div class="col-md-4">
																 <input type="text" class="form-control" placeholder="Product price" name="product_price" value="<?php echo (isset($post['product_price']) ? $post['product_price'] : "") ?>">
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label col-md-4">Commission (RM)<font style="color:red;">*</font></label>
															<div class="col-md-4">
																<input type="text" class="form-control"  name="product_commission" value="<?php echo (isset($post['product_commission']) ? $post['product_commission'] : "") ?>">
																<!--<span class="help-block"> This is inline help </span>-->
															</div>
														</div>
													</div>
													<!--/span-->
												</div>
												<br />
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label col-md-4"></label>
															<div class="col-md-6">
																upload button
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label col-md-4">Preview Image</label>
															<div class="col-md-6">
																	<img src="<?php echo base_url(); ?>" width="30%">
																</div>
																
															</div>
													</div>
												</div>
												<div class="row">
													
												</div>
												<!--/row-->
											</div>
											<div class="form-actions">
												<div class="row">
													<div class="col-md-6">
														<div class="row">
															<div class="col-md-offset-3 col-md-9">
																<button type="submit" class="btn green-soft"><i class="fa fa-check"></i> Submit</button>
																<button type="reset" class="btn red-thunderbird">Clear</button>
															</div>
														</div>
													</div>
												</div>
											</div>
										</form>
										<!-- END FORM-->
									</div>
									
								</div>
							</div>
						</div>
                        <!-- END PAGE HEADER-->
                    </div>
                    <!-- END CONTENT BODY -->
                </div>
                <!-- END CONTENT -->
            </div>
            <!-- END CONTAINER -->
			<?php $this->load->view('user/footer'); ?>
			<!-- BEGIN PAGE LEVEL SCRIPTS -->
			<script src="<?php echo base_url();?>assets/pages/scripts/form-samples.min.js" type="text/javascript"></script>
			<script src="<?php echo base_url();?>assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
			<!-- END PAGE LEVEL SCRIPTS -->
			<!-- BEGIN PAGE LEVEL PLUGIN -->
			
			<script src="<?php echo base_url();?>assets/global/plugins/moment.min.js" type="text/javascript"></script>
			<script src="<?php echo base_url();?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
			<script src="<?php echo base_url();?>assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
			<!-- END PAGE LEVEL PLUGIN -->
			<script>
			
			window.setTimeout(function() {
				$(".notify").fadeTo(500, 0).slideUp(500, function(){
				$(this).remove(); 
				});
			}, 2500);
			</script>
    </body>
</html>