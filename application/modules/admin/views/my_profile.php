<?php
$member_since = $this->admin_model->get_user_reg_date($this->session->userdata('user_id'));
$last_login = $this->admin_model->get_user_last_login($this->session->userdata('user_id'));
$old_password = array('name'=>'old_password','id'=>'old_password','value'=>set_value('old_password'));
$new_password = array('name'=>'new_password','id'=>'new_password','value'=>set_value('new_password'),);
$confirm_new_password = array('name'=>'confirm_new_password','id'=>'confirm_new_password');
$email = array('name' => 'email', 'value' => $user->email , 'id'=>'email');
$fullname = array('name' => 'fullname', 'value' => $user->fullname ,'id'=>'fullame');
$username = array('name' => 'username', 'value' => $user->username ,'readonly'=>'readonly','id'=>'username');
$submit = array('name' => 'submit', 'value' => 'Update');
?>
<div class="widget">
	<div class="widget-head">
		<div class="pull-left"><i class="icon-credit-card title"></i><?php echo $user->fullname; ?></div>
		<div class="widget-icons pull-right">
			<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
		</div>  
		<div class="clearfix"></div>
	</div>             
	<div class="widget-content">
		<div class="padd">
			<div style="padding:20px;">
		<div class="row">
			<div class="span2">
				<strong><?php echo date("jS M Y",strtotime($member_since)); ?></strong><br/><small>A member since</small><br/>
			</div>
			<div class="span2">
			<strong><?php echo date("jS M Y",strtotime($last_login)); ?></strong><br/><small>Last login</small><br/>
			</div>
		</div>
		<h5>Personal Details</h5>
		<div style="padding:20px; border: 1px solid #E3E3E3; margin-bottom:20px;">
			<p>Username : <?php echo $user->username; ?></p>
			<p>Full Name : <?php echo $user->fullname; ?></p>
			<p>Email : <?php echo $user->email; ?></p>
		</div>
		<?php echo form_open($this->uri->uri_string(),"class='form-horizontal'"); ?>
		<div class="control-group">
			<label class="control-label" for="new_password">New Password</label>
			<div class="controls">
				<?php echo form_password($new_password); ?>
				<span class="help-block" id="error-span">
					<?php echo form_error('new_password');?>
				</span>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="password">Confirm Password</label>
			<div class="controls">
				<?php echo form_password($confirm_new_password); ?>
				<?php echo form_hidden('email',$user->email); ?>
				<span class="help-block" id="error-span">
					<?php echo form_error('confirm_new_password');?>
				</span>
			</div>
		</div>
		<div class="form-actions">
			<button type="submit" class="btn btn-primary btn-large">Change Password</button>
		</div>
		<?php  echo form_close(); ?>
	</div>
		</div>
	</div>
</div>