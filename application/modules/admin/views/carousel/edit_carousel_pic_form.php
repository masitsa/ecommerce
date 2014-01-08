<?php 
$submit = array('name'=>'update','value'=>'Update');
$caption = array('name'=>'caption','id'=>'caption','value'=>$details->pic_caption);
?>
<div class="widget">
	<div class="widget-head">
		<div class="pull-left"><i class="icon-picture title"></i>Edit Carousel Picture</div>
		<div class="widget-icons pull-right">
			<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
		</div>  
		<div class="clearfix"></div>
	</div>             
	<div class="widget-content">
		<div class="padd">
			<?php echo form_open_multipart($this->uri->uri_string(),"class='form-horizontal'"); ?>
			<div class="control-group">
				<label  class="control-label" for="image">Carousel Image Preview</label>
				<div class="controls">
					<div class="fileupload fileupload-new" data-provides="fileupload">
						<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;"><img src="<?php echo base_url().'assets/carousel/original/'.$details->pic_name; ?>" /></div>
						<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
						<div>
						<span class="btn btn-file"><span class="fileupload-new">Select image to replace</span><span class="fileupload-exists">Change</span><input type="file" name="userfile" id="userfile" /></span>
						<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
						</div>
						<span class="help-block" id="error-span">
							<?php echo !empty($error)?$error:''; ?>
						</span>
					</div>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="caption">Caption</label>
				<div class="controls">
					<?php echo form_input($caption); ?>
					<span class="help-block" id="error-span">
						<?php echo form_error('caption');?>
					</span>
				</div>
			</div>
			<div class="form-actions">
				<button type="submit" class="btn btn-primary btn-large">Edit</button>
			</div>
			<?php echo form_close(); ?>
				</div>
	</div>
</div>