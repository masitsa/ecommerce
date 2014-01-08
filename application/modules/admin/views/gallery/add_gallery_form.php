<?php
$types = $this->gallery_model->get_type_list();
	$type_array[] = '';
	foreach ($types as $type)
    	$type_array[$type->gallery_id] = $type->name;
?>
<script>
	$(function() {
		$('.fileupload').fileupload();
	});
</script> 
<?php
$description = array('name'=>'description','id'=>'description','value'=>set_value('description'));
$portfolio_title = array('name'=>'portfolio_title','id'=>'portfolio_title','value'=>set_value('portfolio_title'));
$client = array('name'=>'client','id'=>'client','value'=>set_value('client'));
$live_preview = array('name'=>'live_preview','id'=>'live_preview','value'=>set_value('live_preview'));
$project_date = array('name'=>'project_date','id'=>'project_date','value'=>set_value('project_date'));
?>
<div class="widget">
	<div class="widget-head">
		<div class="pull-left"><i class="icon-camera title"></i>Add Photo to a Gallery</div>
		<div class="widget-icons pull-right">
			<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
		</div>  
		<div class="clearfix"></div>
	</div>             
	<div class="widget-content">
		<div class="padd">
			<?php echo form_open_multipart($this->uri->uri_string(),"class='form-horizontal'"); ?>
				<div class="control-group">
					<label  class="control-label" for="gallery">Gallery</label>
					<div class="controls">
						<?php echo form_dropdown('gallery',$type_array); ?>
						<span class="help-block" id="error-span">
							<?php echo form_error('gallery');?>
						</span>
					</div>
				</div>
		
				<div class="control-group">
					<label  class="control-label" for="caption">Caption</label>
					<div class="controls">
						<textarea name="caption" id="caption" rows="5" style="width:50%; height: auto"><?php echo $this->input->post('caption')!='<br>'?$this->input->post('caption'):''; ?></textarea>
						<span class="help-block" id="error-span">
							<?php echo form_error('caption');?>
						</span>
					</div>
				</div>
				
				<div class="control-group">
					<label  class="control-label" for="userfile"> Image </label>
					<div class="controls">
						<div class="fileupload fileupload-new" data-provides="fileupload">
							<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;"><img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" /></div>
							<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
							<div>
							<span class="btn btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" name="userfile" id="userfile" /></span>
							<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
							</div>
							<span class="help-block" id="error-span">
								<?php echo $file_error; ?>
							</span>
						</div>
					</div>						
				</div>
				<div class="form-actions">
					<button type="submit" class="btn btn-primary btn-large">Save</button>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>