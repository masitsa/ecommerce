<?php
$name = array('type'=>'text','name'=>'name','id'=>'name','class'=>'span8','placeholder'=>'Name','value'=>set_value('name'));
$email = array('type'=>'text','name'=>'email','id'=>'email','class'=>'span8','placeholder'=>'Email','value'=>set_value('email'));
$subject = array('type'=>'text','name'=>'subject','id'=>'subject','class'=>'span8','placeholder'=>'Subject','value'=>set_value('subject'));
$bot_honey = array('name'=>'bot_honey','id'=>'bot_honey','class'=>'bot-honey','type'=>'bot','placeholder'=>'Leave Blank');
?>
<?php if(!empty($message)) { ?>
<div class="alert alert-error">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?php echo $message; ?>
</div>
<?php } ?>
<div class="row">
	<!-- start: Page section -->
    <section class="span12 contact-map">
    	<div class="page-inner">
			<div class="row-fluid">
                <div class="map-container">
					<div id="show_hide" onclick="location.href='javascript:toggle();'" class="shadow1">Hide Locations</div>
					<div id="side_panel" class="shadow1">
						<div style="height:100%;margin: 10px 10px 10px 10px;font-size:13px;">
							<input type="text" id="search" size="20" placeholder="Find a location" style="width:90%; margin:0;"/>
							<h3>Our Locations</h3>
							<div id="list"></div>
						</div>
					</div>
					<div id="map_canvas" class="map-canvas"></div>
                </div>
            </div>
			<div class="sub-inner">
				<div class="row-fluid">
					<div class="span8">
						<h1>Contact</h1>
						<?php if($this->session->flashdata('message')) { ?>
						<div class="alert alert-success">
						    <button type="button" class="close" data-dismiss="alert">&times;</button>
						    <?php echo $this->session->flashdata('message'); ?>
						</div>
						<?php } if($this->session->flashdata('error')) { ?>
						<div class="alert alert-error">
						    <button type="button" class="close" data-dismiss="alert">&times;</button>
						    <?php echo $this->session->flashdata('error'); ?>
						</div>
						<?php } ?>
						<?php echo form_open($this->uri->uri_string(),"class='af-form'"); ?>
							<?php echo form_input($name); ?><br/>
							<span id="error-span" class="name-error help-block"><?php  echo form_error('name'); ?></span>
							<?php echo form_input($email); ?><br/>
							<span id="error-span" class="email-error help-block"><?php  echo form_error('email'); ?></span>
							<?php echo form_input($subject); ?><br/>
							<span id="error-span" class="subject-error help-block"><?php  echo form_error('subject'); ?></span>
							<textarea name="message" id="message" class="span8" placeholder="Message"><?php echo $this->input->post('message'); ?></textarea>
							<span id="error-span" class="message-error help-block"><?php  echo form_error('message'); ?></span>
							<span class="help-block"><?php echo form_input($bot_honey); ?></span>
							
							<button type="submit" class="btn form-button btn-large btn-primary">Send Enquiry</button>
						<?php echo form_close(); ?>
					</div>
					<div class="span4">
                        <section>
                            <h3>Address</h3>
                            <?php $this->load->view('address_widget'); ?>
                        </section>
                        <section>
                            <h3>Business Hours</h3>
                            <ul class="unstyled">
                                <li class="clearfix">Monday - Friday: 9 am  to 6 pm</li>
                                <li class="clearfix">Saturday: 10 am  to 4 pm</li>
                                <li class="clearfix">Sunday: Closed</li>
                            </ul>
                        </section>
                    </div>
				</div>
			</div>
		</div>
	</section>
</div>