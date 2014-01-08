<?php 
$form_attributes = array('id'	=> 'fupload','class'=>'form-horizontal');
$userfile = array('name'=>'userfile','id'=>'userfile');
$submit = array('name'=>'btn_upload','value'=>'Upload');
$caption = array('name'=>'caption','id'=>'caption','value'=>set_value('caption'));
?>
<div class="widget">
	<div class="widget-head">
		<div class="pull-left"><i class="icon-picture title"></i>Upload Carousel Picture</div>
		<div class="widget-icons pull-right">
			<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
		</div>  
		<div class="clearfix"></div>
	</div>             
	<div class="widget-content">
		<div class="padd">
			<?php echo form_open_multipart($this->uri->uri_string(),"class='form-horizontal'"); ?>
			<div class="control-group">
				<label class="control-label" for="image">Carousel Picture Preview</label>
				<div class="controls">
					<div class="fileupload fileupload-new" data-provides="fileupload">
						<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;"></div>
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
			<div class="control-group">
				<label class="control-label" for="caption">Caption</label>
				<div class="controls">
					<?php echo form_input($caption); ?>
					<span class="help-block">
						<?php echo form_error('caption');?>
					</span>
				</div>
			</div>
			<div class="form-actions">
				<button type="submit" class="btn btn-primary btn-large">Upload</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>