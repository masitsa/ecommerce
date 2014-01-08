<?php
$submit = array('name'=>'submit','value'=>'Update');
$category = array('name'=>'category','value'=>$details->name,'id'=>'category');
?>
<div class="container">
	<div id="content">
		<div class="content-top">
			<h3>Edit Gallery</h3>
		</div>
		<?php echo form_open('admin/gallery/edit_gallery_type/'.$this->uri->segment(4),"class='form-horizontal'"); ?>
		<div class="control-group">
			<label class="control-label" for="name">Gallery</label>
			<div class="controls">
				<?php echo form_input($category); ?>
				<span class="help-block" id="error-span">
					<?php echo form_error('category');?>
				</span>
			</div>
		</div>
			<div class="form-actions">
				<button type="submit" class="btn btn-primary btn-large">Save Changes</button>
			</div>
		<?php echo form_close();?>
	</div>
</div>