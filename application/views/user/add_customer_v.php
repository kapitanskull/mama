	<?php $this->load->view('user/header'); ?>
	<!-- BEGIN PAGE LEVEL PLUGINS -->
	<link href="<?php echo base_url();?>assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url();?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
	<!-- END PAGE LEVEL PLUGINS -->
           
                        <!-- BEGIN PAGE TITLE-->
                        <h1 class="page-title"> Register Customer</h1>
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
						
						<input type="hidden" class="form-control current_process" value="<?php echo $current_process ?>" >
						<div class="row">
							<div class="col-md-12">
								<div class="portlet box purple-wisteria">
									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-gift" style="color:white"></i>Customer Form
											
										</div>
									</div>
									<div class="portlet-body form">
										<!-- BEGIN FORM-->
										<form action="<?php echo base_url(); ?>user/add" class="form-horizontal" method="post">
											<div class="form-body">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label col-md-4">Full Name <font style="color:red;">*</font></label>
															<div class="col-md-8">
																<input type="text" class="form-control" placeholder="Full name" name="customer_name" value="<?php echo (isset($post['customer_name']) ? $post['customer_name'] : "") ?>">
																<!--<span class="help-block"> This is inline help </span>-->
															</div>
														</div>
													</div>
													<!--/span-->
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label col-md-4">Contact Number <font style="color:red;">*</font></label>
															<div class="col-md-8">
																 <input type="text" class="form-control" placeholder="Contact number without '-'" name="customer_contact_no" value="<?php echo (isset($post['customer_contact_no']) ? $post['customer_contact_no'] : "") ?>">
																<!--<select name="foo" class="form-control">
																	<option value="1">Option 1</option>
																	<option value="1">Option 2</option>
																	<option value="1">Option 3</option>
																</select>-->
															</div>
														</div>
													</div>
													<!--/span-->
												</div>
												<!--/row-->
												<div class="row">
													<!--/span-->
													 <div class="col-md-6">
														<div class="form-group">
															<label class="control-label col-md-4">Order Date <font style="color:red;">*</font></label>
															<div class="col-md-8">
																<input type="text" class="form-control date-picker" placeholder="dd-mm-yyyy" name="customer_order_date" value="<?php echo (isset($post['customer_order_date']) ? $post['customer_order_date'] : "") ?>"  data-date-format = "dd-mm-yyyy" data-date="<?php echo date("d-m-Y"); ?>"> 
															</div>
														</div>
													</div>
													<!--/span-->
												</div>
												<div class="row">
													<!--/span-->
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label col-md-4">Payment (RM)<font style="color:red;">*</font></label>
															<div class="col-md-4">
																<input type="text" class="form-control"  name="customer_total_payment" value="<?php echo (isset($post['customer_total_payment']) ? $post['customer_total_payment'] : "") ?>">
																<!--<span class="help-block"> This is inline help </span>-->
															</div>
														</div>
													</div>
													<div class="col-md-6 order_status" style="display:none;">
														<div class="form-group">
															<label class="control-label col-md-4">Order Status </label>
															<div class="col-md-4">
																<select class="form-control order_status_select" data-placeholder="Choose order status" name="customer_order_status">
																	<option value="Ordered">Ordered</option>
																	<option value="Delivered">Delivered</option>
																	<option value="Received">Received</option>
																</select>
															</div>
														</div>
													</div>
													<!--/span-->
												</div>
												<div class="row track_number" style="display:none;">
													<!--/span-->
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label col-md-4">Tracking number</label>
															<div class="col-md-8">
																<input type="text" class="form-control track_no_input"  name="customer_tracking_no" value="<?php echo (isset($post['customer_tracking_no']) ? $post['customer_total_payment'] : "") ?>" >
																<!--<span class="help-block"> This is inline help </span>-->
															</div>
														</div>
													</div>
													<!--/span-->
												</div>
												<!--/row-->
												<h3 class="form-section">Address</h3>
												<!--/row-->
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label col-md-3">Address <font style="color:red;">*</font></label>
															<div class="col-md-9">
																<input type="text" class="form-control" name="customer_address" value="<?php echo (isset($post['customer_address']) ? $post['customer_address'] : "") ?>"> 
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label col-md-3">City <font style="color:red;">*</font></label>
															<div class="col-md-9">
																<input type="text" class="form-control" name="customer_city" value="<?php echo (isset($post['customer_city']) ? $post['customer_city'] : "") ?>">
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<!--/span-->
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label col-md-3">State <font style="color:red;">*</font></label>
															<div class="col-md-5">
																<input type="text" class="form-control" name="customer_state" value="<?php echo (isset($post['customer_state']) ? $post['customer_state'] : "") ?>"> 
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label col-md-3">Post Code <font style="color:red;">*</font></label>
															<div class="col-md-4">
																<input type="text" class="form-control" name="customer_postcode" value="<?php echo (isset($post['customer_postcode']) ? $post['customer_postcode'] : "") ?>"> 
															</div>
														</div>
													</div>
													<!--/span-->
												</div>
												<!--/row-->
												<div class="row">
													<!--/span-->
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label col-md-3">Country <font style="color:red;">*</font></label>
															<div class="col-md-6">
																<select class="form-control" name="customer_country" >
																	<option value=""></option>
																	<option value="Malaysia" <?php if (isset($post['customer_country']) && ($post['customer_country']=="Malaysia")) echo 'selected="selected"';?>>Malaysia</option>
																	<option value="Singapura" <?php if (isset($post['customer_country']) && ($post['customer_country']=="Singapura")) echo 'selected="selected"';?>>Singapura</option>
																	<option value="Indonesia" <?php if (isset($post['customer_country']) && ($post['customer_country']=="Indonesia")) echo 'selected="selected"';?>>Indonesia</option>
																</select>
															</div>
														</div>
													</div>
													<!--/span-->
												</div>
												<!--/row-->
													<h3 class="form-section">Order Product</h3>
												<div class="table-responsive">
													<table class="table table-hover table-bordered ">
														<thead>
															<tr align="left">
															<th  width="2%">#</th>
															<th width="20%">Product Name</th>
															<th>Price </th>
															<th width="8%">Size</th>
															<th width="15%">Colour </th>
															<th width="10%">Quantity </th>
															<th class="control-label" width="5%">
																<a class="btn btn-info button_add_order_product"><span class="fa fa-plus"></span></a>
															</th>
															</tr>
														</thead>
														<tbody class="add-order">
														<?php
															if(isset($post['customer_order_product_id']) && count($post['customer_order_product_id']) > 0){
																$total = count($post['customer_order_product_id']);
															}
															else{
																$total = 1;
															}
															for($i = 0 ; $i < $total; $i++ ){
														?>
															<tr class="product-order">
																<td class="product_numbering">1. </td>
																<td class="form-group">
																	<select class="form-control product_id" data-placeholder="Choose a Category" name="customer_order_product_id[]" required>
																		<option value="">Select Product</option>
																		   <?php if($product_query->num_rows() > 0){  
																					foreach($product_query->result() as $product){ ?> 
																						<option value="<?php echo $product->id?>" <?php if (isset($post['customer_order_product_id[]']) && ($post['customer_order_product_id'] == $product->id ))echo 'selected="selected"';?> ><?php echo $product->product_name?></option>
																			<?php 	} 
																				} ?>
																	</select>
																</td>
																<td class="form-group product_price col-md-2">
																	<input type="text" class="form-control product_price"  name="product_price[]" 
																	value="<?php echo isset($post['product_price'][$i]) ? $post['product_price'][$i] : ''; ?>" readonly required>
																</td>
																<td class="form-group product_size">
																	<select class="form-control" name="customer_country" >
																		<option value=""></option>
																		<option value="xs" >XS</option>
																		<option value="s" >S</option>
																		<option value="m" >M</option>
																		<option value="l" >L</option>
																		<option value="xl" >XL</option>
																	</select>
																</td>
																<td class="form-group product_price">
																	
																</td>
																<td><input type="text" class="form-control product_quantity"  name="product_quantity[]" 
																value="<?php echo isset($post['product_quantity'][$i]) ? $post['product_quantity'][$i] : '1';?>" style="max-width:50%;" required></td>
																<td class="control-label">
																	<a class="btn btn-danger button_remove_rowOrder"><span class="fa fa-times"></span></a>
																</td>
															</tr>
															<?php
																}
															?>
														</tbody> 
													</table>
												</div>
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
			
			//to hide or unhide certain input for add and edit because using same form view.
			function edit_process(){
				current_function = $('.current_process').val();
			
				if(current_function == "add"){
					$('.order_status').hide();
					$('.track_number').hide();
					$('.order_status_select').attr('disabled',true);
					$('.track_no_input').attr('disabled',true);
				}
				
				else if(current_function == "edit"){
					$('.order_status').show();
					$('.track_number').show();
					$('.order_status_select').attr('disabled',false);
					$('.track_no_input').attr('disabled',false);
					$('.page-title').html("Update Customer")
				}
			}
			
			//to get price of product
			function getProductPrice(element){
				product_id = $(element).val();
				
				if(product_id != ''){
					$.ajax({
						url: base_url + "user/getProductPrice",
						data: {'product_id' : product_id},
						type: 'POST',
						success: function(result){
							console.log(result);
							$(element).closest('.product-order').find('.product_price').val(result);
						}							
					});
				}
				else{
					$(element).closest('.product-order').find('.product_price').val("");
					$(element).closest('.product-order').find('.product_quantity').val("1");
				}
			}

			//to able and disabled remove button
			function removeBtnChck(){
				if($('.product-order').length > 1){
					$('.product-order .button_remove_rowOrder').removeClass("disabled");
				}
				else if($('.product-order').length === 1){
					$('.product-order .button_remove_rowOrder').addClass("disabled");
				}
			}
			
			//to do the numbering for order row
			function rowNumbering(rowClass){
				totalRow = $(rowClass).length;
				$.each($(rowClass), function(key, value){
					$($(rowClass)[key]).find('.product_numbering').html(key+1+".");
				});
			}
			
			$(function()
			{ 
				edit_process();
				
				$('.add-order').on('change', '.product_id', function(){
					getProductPrice(this);
				});
				
				//to add row
				removeBtnChck();	
				$('.button_add_order_product').on('click', function(){
					
					var newOrderRow = $($('.product-order')[0]).clone();
					newOrderRow.find('input[name="customer_order_product_id[]"]').val("");
					newOrderRow.find('input[name="product_price[]"]').val("");
					newOrderRow.find('input[name="product_quantity[]"]').val("1");
					
					$('.add-order').append(newOrderRow);
					removeBtnChck();
					rowNumbering('.product-order');
				});
				
				//remove row
				$('.add-order').on("click", ".button_remove_rowOrder", function(){
					$(this).closest('.product-order').remove();
					removeBtnChck();
					rowNumbering('.product-order');
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