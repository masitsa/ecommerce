<?php
$feature_name = array('name'=>'feature_name','id'=>'feature_name','value'=>set_value('feature_name'));
$feature_units = array('name'=>'feature_units','id'=>'feature_units','value'=>set_value('feature_units'));

$categories = $this->products_model->get_all_active_categories();
$categories_array = array();
$categories_array[0] = 'None';
foreach($categories as $category)
	$categories_array[$category->category_id] = $category->category_name;
	
?>
<div id="content">
	<div class="content-top">
		<h3>Add Feature</h3>
	</div>
	<?php echo form_open($this->uri->uri_string(),"class='form-horizontal'"); ?>
		<div class="control-group">
			<label  class="control-label" for="category_id">Product Category</label>
			<div class="controls">
				<?php echo form_dropdown('category_id',$categories_array,$this->input->post('category_id'),'id = "category_id"');?>
				<span class="help-block">
					<?php echo form_error('category_id');?>
				</span>
			</div>
		</div>
		<div class="control-group">
			<label  class="control-label" for="feature_name">Feature Name</label>
			<div class="controls">
				<?php echo form_input($feature_name);?>
				<span class="help-block">
					<?php echo form_error('feature_name');?>
				</span>
			</div>
		</div>
		<div class="control-group">
			<label  class="control-label" for="feature_units">Units</label>
			<div class="controls">
				<?php echo form_input($feature_units);?>
				<span class="help-block">
					<?php echo form_error('feature_units');?>
				</span>
			</div>
		</div>
		<div class="form-actions">
			<button type="submit" class="btn">Save</button>
		</div>
	<?php echo form_close(); ?>
</div>