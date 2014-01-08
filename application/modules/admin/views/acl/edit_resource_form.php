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
$r_id = $this->uri->segment(3);
$resource_type = array('name'=>'resource_type','id'=>'resource_type','value'=>$details[0]->type_id);
$resource_name = array('name'=>'resource_name','id'=>'resource_name','value'=>$details[0]->resource_name);
$parent = array('name'=>'parent_id','id'=>'parent_id','value'=>$details[0]->parent_id);
$url = array('name'=>'url','id'=>'url','value'=>$details[0]->url);
$position = array('name'=>'position','id'=>'position','value'=>$details[0]->position);
if($details[0]->menu == 1)
	$menu = array('name'=>'menu','id'=>'menu','value'=>1,'checked'=>TRUE);
else
	$menu = array('name'=>'menu','id'=>'menu','value'=>1,'checked'=>FALSE);

$types = $this->acl_model->get_all_resource_types();
$arr[] = '';
foreach($types as $type)
	$arr[$type->type_id] = $type->type_name;
$parents = $this->acl_model->get_resource_parents();
$arr2[] = '';
$arr2[0] = 'None';
foreach($parents as $parent)
	$arr2[$parent->resource_id] = $parent->resource_name;
?>
<div class="widget">
	<div class="widget-head">
		<div class="pull-left"><i class="icon-key title"></i>Edit Resource</div>
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
					<?php echo form_dropdown('resource_type',$arr,$details[0]->type_id,'disabled="disabled"');?>
					<span class="help-block" id="error-span">
						<?php echo form_error('resource_type');?>
					</span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="parent">Parent</label>
				<div class="controls">
					<?php echo form_dropdown('parent_id',$arr2,$details[0]->parent_id,'disabled="disabled"');?>
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
			<div class="control-group">
				<label class="control-label" for="position">Menu Position</label>
				<div class="controls">
					<?php echo form_input($position); ?>
					<span class="help-block" id="error-span">
						<?php echo form_error('position'); ?>
					</span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="menu">Menu?</label>
				<div class="controls">
					<?php echo form_hidden('menu',0);?>
					<?php echo form_checkbox($menu);?>
				</div>
			</div>
			<div class="form-actions">
				<button type="submit" class="btn btn-primary btn-large">Save</button>
			</div>
			<?php echo form_close();?>
		</div>
	</div>
</div>