<?php
$brand_name = array('name'=>'brand_name','id'=>'brand_name','value'=>$details->brand_name);

$categories = $this->products_model->get_all_active_categories();
$categories_array = array();
foreach($categories as $category)
	$categories_array[$category->category_id] = $category->category_name;

?>
<div id="content">
	<div class="content-top">
		<h3>Edit Brand</h3>
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
			<label  class="control-label" for="category_id">Brand Name</label>
			<div class="controls">
				<?php echo form_input($brand_name);?>
				<span class="help-block">
					<?php echo form_error('brand_name');?>
				</span>
			</div>
		</div>
		<div class="form-actions">
			<button type="submit" class="btn">Update</button>
		</div>
	<?php echo form_close(); ?>
</div>