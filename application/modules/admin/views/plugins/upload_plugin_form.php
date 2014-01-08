<div class="widget">
	<div class="widget-head">
		<div class="pull-left"><i class="icon-cog title"></i>Install Plugin</div>
		<div class="widget-icons pull-right">
			<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
		</div>  
		<div class="clearfix"></div>
	</div>             
	<div class="widget-content">
		<div class="padd">
			<?php echo form_open_multipart($this->uri->uri_string(),"class='form-horizontal'");?>
				<div class="control-group">
					<label class="control-label" for="plugin">Plugin</label>
					<div class="controls">
						<input type="file" name="plugin" id="plugin"/>
						<span class="help-block" id="error-span">
							<?php echo $this->upload->display_errors(); ?>
							<?php echo form_error('plugin');?>
						</span>
					</div>
				</div>
				<div class="form-actions">
					<button type="submit" class="btn btn-primary btn-large">Install</button>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>