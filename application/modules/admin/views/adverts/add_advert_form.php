<?php
$advert_name = array('name'=>'advert_name','id'=>'advert_name','value'=>set_value('advert_name'));

$categories = $this->adverts_model->get_all_active_categories();
$categories_array = array();
$categories_array[0] = 'None';
foreach($categories as $category)
	$categories_array[$category->category_id] = $category->category_name;

$positions = $this->adverts_model->get_all_active_positions();
$positions_array = array();
$positions_array[0] = 'None';
foreach($positions as $position)
	$positions_array[$position->ad_position_id] = $position->ad_position_name;
	
?>
<div id="content">
	<div class="content-top">
		<h3>Add Advert</h3>
	</div>
	<?php echo form_open_multipart($this->uri->uri_string(),"class='form-horizontal'"); ?>
		<div class="control-group">
			<label  class="control-label" for="category_id">Advert Position</label>
			<div class="controls">
				<?php echo form_dropdown('ad_position_id',$positions_array,$this->input->post('ad_position_id'),'id = "ad_position_id"');?>
				<span class="help-block">
					<?php echo form_error('ad_position_id');?>
				</span>
			</div>
		</div>
        
		<div class="control-group">
			<label  class="control-label" for="category_id">Advert Category</label>
			<div class="controls">
				<?php echo form_dropdown('category_id',$categories_array,$this->input->post('category_id'),'id = "category_id"');?>
				<span class="help-block">
					<?php echo form_error('category_id');?>
				</span>
			</div>
		</div>
        
		<div class="control-group">
			<label  class="control-label" for="product_name">Advert Name</label>
			<div class="controls">
				<?php echo form_input($advert_name);?>
				<span class="help-block">
					<?php echo form_error('advert_name');?>
				</span>
			</div>
		</div>
        
		<div class="control-group">
			<label class="control-label" for="image">Advert Poster</label>
			<div class="controls">
				<div class="fileupload fileupload-new" data-provides="fileupload">
					<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;"><img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" /></div>
					<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
					<div>
					<span class="btn btn-file"><span class="fileupload-new">Select image to upload</span><span class="fileupload-exists">Change</span><input type="file" name="userfile" id="userfile" /></span>
					<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
					</div>
					<span class="help-block">
						<?php echo !empty($error)?$error:''; ?>
					</span>
				</div>
			</div>
		</div>
        
		<div class="form-actions">
			<button type="submit" class="btn">Save</button>
		</div>
	<?php echo form_close(); ?>
</div>