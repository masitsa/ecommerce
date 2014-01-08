<script type="text/javascript">
	$(document).ready(function() {
		var groups = [];
		$("#groups").change(function() {
			var chosenoption=this.options[this.selectedIndex];
			var count = groups.indexOf(chosenoption.value);
			if(count == -1){
				$("#groups-tr").before('<div class="control-group'+chosenoption.value+'"><div class="controls"><select name="group[]" id="group[]"><option value="'+chosenoption.value+'">'+chosenoption.text+'</option></select><input type="button" id="remove" value="Remove" onclick=removeOption('+chosenoption.value+');></div></div>');
				groups.push(chosenoption.value);
			}else{
				alert("Already Added");
			}
		});
	});
	function removeOption(option){
		$('.control-group'+option).remove();
	};
</script>
<?php
$id = 'id="groups"';
$new_password = array('name'=>'new_password','id'=>'new_password','value'=>set_value('new_password'));
$confirm_new_password = array('name'=>'confirm_new_password','id'=>'confirm_new_password');
$submit = array('name'=>'submit','value'=>'Update');
$email = array('name'=>'email','value'=>$user->email,'id'=>'email');
$fullname = array('name'=>'fullname','value'=>$user->fullname,'id'=>'fullname');
$username = array('name'=>'username','value'=>$user->username ,'readonly'=>'readonly');
$groups = json_decode($user->u_group_id);
$cg = count($groups);
$options = $this->admin_model->get_user_levels();
foreach ($options as $rows)
	$arr1[$rows->u_level_id] = $rows->user_level;
$arr1[0] = 'None';
$option = $this->admin_model->get_user_groups();
foreach ($option as $group)
	$arr[$group->u_group_id] = $group->user_group;
$arr[0] = 'None';
?>
<div class="widget">
	<div class="widget-head">
		<div class="pull-left"><i class="icon-edit title"></i>Edit User</div>
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
					</span>
				</div>
			</div>
			<?php if($this->admin_model->get_setting_value('user_level') == 1){?>
				<div class="control-group">
					<label class="control-label" for="u_level_id">User Level</label>
					<div class="controls">
						<?php echo form_dropdown('u_level_id',$arr1,$user->u_level_id); ?>
						<span class="help-block">
							<?php echo form_error('u_level_id');?>
						</span>
					</div>
				</div>
			<?php } if($this->admin_model->get_setting_value('user_group') == 1) { ?>
				<?php if(is_array($groups)){ for($i=0;$i<$cg;$i++) { ?>
					<div class="control-group">
						<label class="control-label" for="group[]">User Group</label>
						<div class="controls">
							<?php echo form_dropdown('group[]',$arr,$groups[$i]);?>
						</div>
					</div>	
				<?php }  ?>
					<div class="control-group" id="groups-tr">
						<label class="control-label" for="u_group_id">Add User Group</label>
						<div class="controls">
							<?php echo form_dropdown('u_group_id',$arr,0,$id); ?>
						</div>
						<span class="help-block">
							<?php echo form_error('u_group_id');?>
						</span>
					</div>
				<?php } else { ?>
					<div class="control-group" id="groups-tr">
						<label class="control-label" for="u_group_id">User Group</label>
						<div class="controls">
							<?php echo form_dropdown('u_group_id',$arr,$user->u_group_id,$id); ?>
						</div>
						<span class="help-block">
							<?php echo form_error('group[]');?>
						</span>
					</div>
				<?php } } ?>
				<div class="control-group">
					<label class="control-label" for="password">New Password</label>
					<div class="controls">
						<?php echo form_password($new_password); ?>
						<span class="help-block">
							<?php echo form_error('new_password');?>
						</span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="password">Confirm New Password</label>
					<div class="controls">
						<?php echo form_password($confirm_new_password); ?>
						<span class="help-block">
							<?php echo form_error('confirm_new_password');?>
						</span>
					</div>
				</div>
				<div class="form-actions">
					<button type="submit" class="btn btn-primary btn-large">Save Changes</button>
				</div>
			<?php echo form_close();?>
		</div>
	</div>
</div>