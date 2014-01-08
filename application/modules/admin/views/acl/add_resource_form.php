<script>
    $(function() {
        $('#position').spinner({
            min: 1,
            max: 20,
            step: 1
        });
    });
</script>
<?php 
$resource_name = array('name'=>'resource_name','id'=>'resource_name','value'=>set_value('resource_name'));
$url = array('name'=>'url','id'=>'url','value'=>set_value('url'));
$types = $this->acl_model->get_all_resource_types();
$arr[] = '';
foreach($types as $type)
	$arr[$type->type_id] = $type->type_name;
$parents = $this->acl_model->get_resource_parents();
$arr2[] = '';
foreach($parents as $parent)
	$arr2[$parent->resource_id] = $parent->resource_name;
?>
<div class="widget">
	<div class="widget-head">
		<div class="pull-left"><i class="icon-folder-close title"></i>Edit Page</div>
		<div class="widget-icons pull-right">
			<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
		</div>  
		<div class="clearfix"></div>
	</div>             
	<div class="widget-content">
		<div class="padd">
			<?php echo form_open($this->uri->uri_string(),"class='form-horizontal'");?>
				<div class="control-group">
					<label class="control-label" for="resource_type">Resource Type</label>
					<div class="controls">
						<?php echo form_dropdown('resource_type',$arr,$this->input->post('resource_type'));?>
						<span class="help-block" id="error-span">
							<?php echo form_error('resource_type');?>
						</span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="parent">Parent</label>
					<div class="controls">
						<?php echo form_dropdown('parent_id',$arr2,$this->input->post('parent_id'));?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="position">Menu Position</label>
					<div class="controls">
						<input id="position" name="position"/>
						<span class="help-block" id="error-span">
							<?php echo form_error('position'); ?>
						</span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="menu">Menu?</label>
					<div class="controls">
						<?php echo form_hidden('menu',0);?>
						<?php echo form_checkbox('menu',1);?>
					</div>
				</div>
				<div class="control-group">
					<label  class="control-label" for="resource_name">Resource Name</label>
					<div class="controls">
						<?php echo form_input($resource_name);?>
						<span class="help-block" id="error-span">
							<?php echo form_error('resource_name');?>
						</span>
					</div>
				</div>
				<div class="control-group">
					<label  class="control-label" for="url">URL</label>
					<div class="controls">
						<?php echo form_input($url);?>
						<span class="help-block" id="error-span">
							<?php echo form_error('url');?>
						</span>
					</div>
				</div>
				<div class="form-actions">
					<button type="submit" class="btn btn-primary btn-large">Save</button>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>