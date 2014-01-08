<?php
$username = array('name'=>'username','id'=>'username','value'=>set_value('username'),'class'=>'input-1','maxlength'=>$this->config->item('username_max_length','tank_auth'),'size'=>30);
$fullname = array('name'=>'fullname','id'=>'fullname','value'=>set_value('fullname'),'class'=>'input-1','size'=>30);
$email = array('name'=>'email','id'=>'email','value'=>set_value('email'),'class'=>'input-1','maxlength'=>80,'size'=>30);
$password = array('name'=>'password','id'=>'password','class'=>'input-1','value'=>set_value('password'),'maxlength'=>$this->config->item('password_max_length', 'tank_auth'),'size'=>30);
$confirm_password = array('name'=>'confirm_password','id'=>'confirm_password','class'=>'input-1','value'=>set_value('confirm_password'),'maxlength'=>$this->config->item('password_max_length','tank_auth'),'size'=>30);
$role = array('name'=>'u_level_id','id'=>'u_level_id','class'=>'input-1');
$group = array('name'=>'u_group_id','id'=>'u_group_id','class'=>'input-1');
$groupvalue = array('value' => set_value('rolesID'));
$options = $this->admin_model->get_user_levels();
$arr1[] = '';
foreach ($options as $row)
	$arr1[$row->u_level_id] = $row->user_level;
$option = $this->admin_model->get_user_groups();
$arr[] = '';
foreach ($option as $row)
	$arr[$row->u_group_id] = $row->user_group;
?>
<div class="widget">
	<div class="widget-head">
		<div class="pull-left"><i class="icon-user title"></i>Add New User</div>
		<div class="widget-icons pull-right">
			<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
		</div>  
		<div class="clearfix"></div>
	</div>             
	<div class="widget-content">
		<div class="padd">
			<?php echo form_open($this->uri->uri_string(),"class='form-horizontal'"); ?>
			<div class="control-group">
				<label class="control-label" for="username">Username</label>
				<div class="controls">
					<?php echo form_input($username); ?>
					<span class="help-block">
						<?php echo form_error('username');?>
						<?php echo isset($username_error)?$username_error:''; ?>
					</span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="fullname">Full Name</label>
				<div class="controls">
					<?php echo form_input($fullname); ?>
					<span class="help-block">
						<?php echo form_error('fullname');?>
					</span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="email">Email Address</label>
				<div class="controls">
					<?php echo form_input($email); ?>
					<span class="help-block">
						<?php echo form_error('email');?>
						<?php echo isset($email_error)?$email_error:''; ?>
					</span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="u_level_id">User Level</label>
				<div class="controls">
					<?php echo form_dropdown('u_level_id',$arr1,$this->input->post('u_level_id'));?>
					<span class="help-block">
						<?php echo form_error('u_level_id');?>
					</span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="u_group_id">User Group</label>
				<div class="controls">
					<?php echo form_dropdown('u_group_id',$arr,$this->input->post('u_group_id')); ?>
					<span class="help-block">
						<?php echo form_error('u_group_id');?>
					</span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="password">Password</label>
				<div class="controls">
					<?php echo form_password($password); ?>
					<span class="help-block">
						<?php echo form_error('password');?>
					</span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="password">Confirm Password</label>
				<div class="controls">
					<?php echo form_password($confirm_password); ?>
					<span class="help-block">
						<?php echo form_error('confirm_password');?>
					</span>
				</div>
			</div>
			<div class="form-actions">
				<button type="submit" class="btn btn-primary btn-large">Create</button>
			</div>
			<?php echo form_close();?>
		</div>
	</div>
</div>