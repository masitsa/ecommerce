<?php $gallery = array("name" => "gallery"); ?>
<div id="content">
	<div class="content-top">
		<h3></h3>
	</div>
	
</div>
<div class="widget">
	<div class="widget-head">
		<div class="pull-left"><i class="icon-camera title"></i>Manage Galleries</div>
		<div class="widget-icons pull-right">
			<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
		</div>  
		<div class="clearfix"></div>
	</div>             
	<div class="widget-content">
		<div class="padd">
			<?php echo form_open_multipart("admin/gallery/add_gallery_category","class='form-horizontal'"); ?>
				<div class="control-group">
					<label class="control-label" for="gallery">Gallery</label>
					<div class="controls">
						<?php echo form_input($gallery); ?><span style="padding-left: 30px;"></span><button type="submit" class="btn btn-primary btn-large">Add Gallery</button>
						<span class="help-block" id="error-span">
							<?php echo form_error('gallery');?>
						</span>
					</div>
				</div>
				<div class="form-actions" style="padding-left: 0px;">
					<?php echo $this->table->generate(); ?>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>