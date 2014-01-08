<?php
$submit = array('name'=>'submit','value'=>'Update');
$category= array('name'=>'category','value'=>$details->name,'id'=>'category');
?>
<div class="widget">
	<div class="widget-head">
		<div class="pull-left"><i class="icon-camera-retro title"></i>Edit Project Category</div>
		<div class="widget-icons pull-right">
			<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
		</div>  
		<div class="clearfix"></div>
	</div>             
	<div class="widget-content">
		<div class="padd">
			<?php echo form_open($this->uri->uri_string(),"class='form-horizontal'"); ?>
			<div class="control-group">
				<label class="control-label" for="name">Portfolio Category</label>
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
</div>