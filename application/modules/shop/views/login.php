
        <!-- Display validation errors -->
        <?php  ?>
        
        <?php if(isset($error)){ echo $error;}?>
        
			<div class="row" style="margin-left:0px;">
					<div class="span12">
						<div class="accordion" id="accordion2">
							<div class="accordion-group">
								<div class="accordion-heading">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">Checkout Options</a>
								</div>
								<div id="collapseOne" class="accordion-body in collapse">
									<div class="accordion-inner">
										<div class="row-fluid">
											 <div class="span6 login_user box-4">
												<h4>Returning Customer</h4>
												<p>I am a returning customer</p>
													<?php 
														echo form_open('login/verify_login'); 
														echo form_hidden('ajax', 4); 
													?>
                                                <div id="error"></div>
													<fieldset>
														<div class="control-group">
															<label class="control-label">Email</label>
															<div class="controls">
																<input type="text" value="<?php echo set_value('email');?>" name="email" class="input-xlarge">
															</div>
														</div>
														<div class="control-group">
															<label class="control-label">Password</label>
															<div class="controls">
															<input type="password" name="pwd" class="input-xlarge">
															</div>
														</div>
                                                        <input type="submit" value="Continue" class="btn btn-inverse"/>
													</fieldset>
													<?php echo form_close(); ?>
											</div>
											<div class="span6">
												<h4>New Customer</h4>
												<p>By creating an account you will be able to shop faster, be up to date on an order's status, and keep track of the orders you have previously made.</p>
												<?php echo form_open('login/register_user'); ?>
													<fieldset>
														<label class="radio" for="register">
															<input type="radio" name="customer_type" value="1" id="register" checked="checked">Register Account
														</label>
														<label class="radio" for="guest">
															<input type="radio" name="customer_type" value="0" id="guest">Guest Checkout
														</label>
														<br>
                                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo"><button class="btn btn-inverse">Continue</button></a>
													</fieldset>
											 </div>
										</div>
									</div>
								</div>
							</div>
							<div class="accordion-group">
								<div class="accordion-heading">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">Account &amp; Billing Details</a>
								</div>
								<div id="collapseTwo" class="accordion-body collapse">
									<div class="accordion-inner">
										<div class="row-fluid">
											<div class="span6">
												<h4>Your Personal Details</h4>
												<div class="control-group">
													<label class="control-label"><span class="required">*</span>First Name</label>
													<div class="controls">
														<input type="text" value="<?php echo set_value('customer_name');?>" placeholder="" class="input-xlarge" name="customer_name">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label"><span class="required">*</span>Last Name</label>
													<div class="controls">
														<input type="text" value="<?php echo set_value('customer_lname');?>" placeholder="" class="input-xlarge" name="customer_lname">
													</div>
												</div>					  
												<div class="control-group">
													<label class="control-label"><span class="required">*</span>Email Address</label>
													<div class="controls">
														<input type="text" value="<?php echo set_value('customer_email');?>" placeholder="" class="input-xlarge" name="customer_email">
													</div>
												</div>				  
												<!--<div class="control-group">
													<label class="control-label"><span class="required">*</span>Confirm Email</label>
													<div class="controls">
														<input type="text" value="<?php echo set_value('customer_email');?>" placeholder="" class="input-xlarge" name="Confirm_email">
													</div>
												</div>-->
												<div class="control-group">
													<label class="control-label"><span class="required">*</span>Telephone</label>
													<div class="controls">
														<input type="text" value="<?php echo set_value('customer_phone');?>" placeholder="" class="input-xlarge" name="customer_phone">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Password</label>
													<div class="controls">
														<input type="password" placeholder="" class="input-xlarge" name="customer_password">
													</div>
												</div>
												<!--<div class="control-group">
													<label class="control-label">Confirm Password</label>
													<div class="controls">
														<input type="password" placeholder="" class="input-xlarge" name="Confirm_password">
													</div>
												</div>-->
											</div>
											<div class="span6">
												<h4>Your Address</h4>	  
												<div class="control-group">
													<label class="control-label"><span class="required">*</span> Address 1:</label>
													<div class="controls">
														<input type="text" value="<?php echo set_value('customer_address');?>" placeholder="" class="input-xlarge" name="customer_address">
													</div>
												</div>
												<!--<div class="control-group">
													<label class="control-label">Address 2:</label>
													<div class="controls">
														<input type="text" value="<?php echo set_value('customer_address2');?>" placeholder="" class="input-xlarge" name="customer_address2">
													</div>
												</div>-->
												<div class="control-group">
													<label class="control-label"><span class="required">*</span> Post Code:</label>
													<div class="controls">
														<input type="text" value="<?php echo set_value('customer_post_code');?>" placeholder="" class="input-xlarge" name="customer_post_code">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label"><span class="required">*</span> City:</label>
													<div class="controls">
														<input type="text" value="<?php echo set_value('customer_city');?>" placeholder="" class="input-xlarge" name="customer_city">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Country:</label>
													<div class="controls">
														<select class="input-xlarge" name="country_id">
                                                        <?php
                                                        	if(is_array($countries)){
																foreach($countries as $country){
																	echo '<option value="'.$country->country_id.'">'.$country->country_name.'</option>';
																}
															}
														?>
														</select>
													</div>
												</div>
											</div>
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree"><button class="btn btn-inverse">Continue</button></a>
										</div>
									</div>
								</div>
							</div>
							<div class="accordion-group">
								<div class="accordion-heading">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree">Confirm Order</a>
								</div>
								<div id="collapseThree" class="accordion-body collapse">
									<div class="accordion-inner">
										<div class="row-fluid">
											<div class="control-group">
                                            	
                                                <?php $this->load->view("view_cart");?>
                                                
                                                
												<label for="textarea" class="control-label">Comments</label>
												<div class="controls">
													<textarea rows="3" id="textarea" class="span12"></textarea>
												</div>
											</div>									
											<button class="btn btn-inverse pull-right confirm_order">Confirm order</button>
										</div>
									</div>
								</div>
							</div>
						</div>				
					</div>
				</div>
			<?php echo form_close(); ?>