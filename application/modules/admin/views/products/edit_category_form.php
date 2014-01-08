<?php
$category_name = array('name'=>'category_name','id'=>'category_name','value'=>$details->category_name,'readonly'=>'readonly');
$category_prefix = array('name'=>'category_preffix','id'=>'category_preffix','value'=>$details->category_preffix,'readonly'=>'readonly');

$categories = $this->products_model->get_all_active_categories();
$categories_array = array();
$categories_array[0] = 'None';
foreach($categories as $category)
	$categories_array[$category->category_id] = $category->category_name;
unset($categories_array[$details->category_id]);
?>
<div id="content">
	<div class="content-top">
		<h3>Edit Product Category</h3>
	</div>
	<?php echo form_open($this->uri->uri_string(),"class='form-horizontal'"); ?>
		<div class="control-group">
			<label  class="control-label" for="category_name">Category Name</label>
			<div class="controls">
				<?php echo form_input($category_name);?>
				<span class="help-block">
					<?php echo form_error('category_name');?>
				</span>
			</div>
		</div>
		<div class="control-group">
			<label  class="control-label" for="category_prefix">Category Prefix</label>
			<div class="controls">
				<?php echo form_input($category_prefix);?>
				<span class="help-block">
					<?php echo form_error('category_prefix');?>
				</span>
			</div>
		</div>
		<div class="control-group">
			<label  class="control-label" for="category_parent">Parent Category</label>
			<div class="controls">
				<?php echo form_dropdown('category_parent',$categories_array,$details->category_parent,'id = "category_parent"');?>
				<span class="help-block">
					<?php echo form_error('category_parent');?>
				</span>
			</div>
		</div>
		<div class="form-actions">
			<button type="submit" class="btn">Save</button>
		</div>
	<?php echo form_close(); ?>
</div>