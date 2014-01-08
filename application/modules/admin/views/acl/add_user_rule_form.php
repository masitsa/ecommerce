<?php
$id = 'id="resource"';
$id2 = 'id="user_id"';
$user_id[] = '';
foreach($users as $user)
	$user_id[$user->user_id] = $user->fullname;
$resource_id[] = '';
$resources = $this->acl_model->get_resource_parents();
if($this->uri->segment(4) != '' AND $this->uri->segment(5) != ''){
	$user = $this->uri->segment(4);
	$parent_id = $this->uri->segment(5);
	$resource_children = $this->acl_model->get_resource_children($parent_id);
}
foreach($resources as $resource)
	$resource_id[$resource->resource_id] = $resource->resource_name;
$rules = array('1'=>'Allow','0'=>'Deny');
?> 
<script type="text/javascript">
	$(document).ready(function() {
		resources = [];
		$("#resource").change(function() {
			var chosenoption=this.options[this.selectedIndex];
			var redirect = CI.base_url+'admin/acl/add_user_rule/'+document.getElementById("user_id").value+'/'+chosenoption.value;
			 if (chosenoption.value!="nothing"){
				location.href = redirect; 
			}
		});
	});
</script>
<div class="widget">
	<div class="widget-head">
		<div class="pull-left"><i class="icon-key title"></i>Add User Rule</div>
		<div class="widget-icons pull-right">
			<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
		</div>  
		<div class="clearfix"></div>
	</div>             
	<div class="widget-content">
		<div class="padd">
			<?php echo form_open($this->uri->uri_string(),"class='form-horizontal'");?>
			<div class="control-group">
				<label class="control-label" for="user_id">User</label>
				<div class="controls">
					<?php echo form_dropdown('user_id',$user_id,$user,$id2);?>
					<span class="help-block" id="error-span">
						<?php echo form_error('user_id');?>
					</span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="resource_id">Resource:</label>
				<div class="controls">
					<?php echo form_dropdown('resource_id[]',$resource_id,isset($parent_id)?$parent_id:'',$id);?>
					<span class="help-block" id="error-span">
						<?php echo form_error('resource_id');?>
					</span>
				</div>
			</div>
			<?php if(!empty($resource_children)) { foreach($resource_children as $kid) { if($this->acl_model->user_has_access($kid->url,$user)){ ?>
			<div class="control-group">
				<label class="control-label" for="resource_id[]"><?php echo $kid->resource_name; ?>:</label>
				<div class="controls">
					<?php echo form_checkbox('resource_id[]',$kid->resource_id,TRUE);?>
				</div>
			</div>	
			<?php }else{ ?>
			<div class="control-group">
				<label class="control-label" for="resource_id[]"><?php echo $kid->resource_name; ?>:</label>
				<div class="controls">
					<?php echo form_checkbox('resource_id[]',$kid->resource_id);?>
				</div>
			</div>
			<?php } } } ?>
			<div class="control-group">
				<label class="control-label" for="rule">Rule:</label>
				<div class="controls">
					<?php echo form_dropdown('rule',$rules,$this->input->post('rule'));?>
				</div>
				<span class="help-block" id="error-span">
						<?php echo form_error('rule');?>
				</span>
			</div>
			<div class="form-actions">
				<button type="submit" class="btn btn-primary btn-large">Save</button>
			</div>
		<?php echo form_close();?>
		</div>
	</div>
</div>