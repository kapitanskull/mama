	<?php $this->load->view('user/header'); ?>
	<!-- BEGIN PAGE LEVEL PLUGINS -->
	<link href="<?php echo base_url();?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
	
	<!-- END PAGE LEVEL PLUGINS -->
           
                        <!-- BEGIN PAGE TITLE-->
                        <h1 class="page-title"> <?php echo $title_page; ?></h1>
						<span class="help-block"> <font style="color:red;">*</font> <i>marked as mandatory</i> </span>
							<?php $error = $this->session->flashdata('error_p');
								  $berjaya = $this->session->flashdata('success_p');
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
										<form action="<?php echo base_url(); ?>product/add_product" class="form-horizontal" method="post" enctype="multipart/form-data" accept-charset="utf-8">
											<div class="form-body">
												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label class="control-label col-md-2">Product Name <font style="color:red;">*</font></label>
															<div class="col-md-8">
																<input type="hidden" class="form-control" name="product_id" value="<?php echo (isset($post['id']) ? $post['id'] : "") ?>">
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
																 <input type="text" class="form-control" placeholder="Eg: 40.00" name="product_price" value="<?php echo (isset($post['product_price']) ? $post['product_price'] : "") ?>">
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label col-md-4">Commission (RM)<font style="color:red;">*</font></label>
															<div class="col-md-4">
																<input type="text" class="form-control"  placeholder="Eg: 20.00" name="product_commission" value="<?php echo (isset($post['product_commission']) ? $post['product_commission'] : "") ?>">
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
															<label class="control-label col-md-4">Product Image</label>
															<div class="col-md-4">
																<div class="fileinput fileinput-new" data-provides="fileinput">
																	<div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
																			<?php if( isset($post['product_image_path']) && $post['product_image_path'] != ''){ ?>
																				<img src="<?php echo base_url().$post['product_image_path']?>" alt="">
																			<?php } else { ?>
																				 <img src="" alt="">
																			<?php }?>
																	</div>
																	<div>
																		<span class="btn purple-seance btn-outline btn-file">
																		<span class="fileinput-new"><i class="fa fa-upload"></i> Select image </span>
																		<span class="fileinput-exists"> Change </span>
																		<input type="file" name="product_image"> </span>
																		<a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="available_color">
															<?php if(isset($post['colour_name']) && count($post['colour_name']) > 0){
																	$total = count($post['colour_name']);
																}
																else{
																	$total = 1;
																} 
																for($i = 0 ; $i < $total; $i++ ){
																?>
																	<div class="form-group product_color">
																		<label class="control-label col-md-4 label_color">Available Color</label>
																		<div class="col-md-4 ">
																			<input type="text" class="form-control"  placeholder="" name="colour_name[]" 
																			value="<?php echo isset($post['colour_name'][$i]) ? $post['colour_name'][$i] : ''; ?>">
																		</div>
																		<a class="btn btn-danger button_remove_rowcolor"><span class="fa fa-times"></span></a>
																	</div>
															<?php
																}
																?>
														</div>
														<div class="form-group">
															<div class="col-md-4 ">
																
															</div>
															<div class="col-md-4 ">
																<a class="btn btn-info button_add_product_color form-control"> <span class="fa fa-plus"></span> </a>
															</div>
														</div>
													</div>
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
		<script src="<?php echo base_url();?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
		<!-- END PAGE LEVEL SCRIPTS -->
		<!-- BEGIN PAGE LEVEL PLUGIN -->
		
		<script src="<?php echo base_url();?>assets/global/plugins/moment.min.js" type="text/javascript"></script>
		
		<!-- END PAGE LEVEL PLUGIN -->
		<script>
		
		function removeBtnChck(){
			if($('.product_color').length > 1){
				$($('.label_color')[0]).html("Available Color"); //to maintain the label if first row been click for remove
				$('.product_color .button_remove_rowcolor').removeClass("disabled");
			}
			else if($('.product_color').length === 1){
				$('.label_color').html("Available Color");
				$('.product_color .button_remove_rowcolor').addClass("disabled");
			}
		}
		
		function newrowColor(){
			total_row = $('.product_color').length;
			if(total_row > 0 ){
				for(i = 1; i <total_row; i++){
					rowcolor = $($('.product_color')[i]);
					rowcolor.find('.label_color').html("");
					
				}
			}
		}
		
		$(function()
		{
			newrowColor();
			removeBtnChck();
			
			$('.button_add_product_color').on('click', function(){
					
				var newOrderRow = $($('.product_color')[0]).clone();
				newOrderRow.find('input[name="colour_name[]"]').val("");
				newOrderRow.find('.label_color').html("");
				
				$('.available_color').append(newOrderRow);
				removeBtnChck();
			});
			
			$('.available_color').on("click", ".button_remove_rowcolor", function(){
				$(this).closest('.product_color').remove();
				removeBtnChck();
			});
		});
		
		window.setTimeout(function() {
			$(".notify").fadeTo(500, 0).slideUp(500, function(){
			$(this).remove(); 
			});
		}, 2500);
		</script>
    </body>
</html>