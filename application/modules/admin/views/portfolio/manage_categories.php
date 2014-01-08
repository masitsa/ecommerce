<?php
$category = array('name'=>'category','id'=>'category');
?>
<div class="widget">
	<div class="widget-head">
		<div class="pull-left"><i class="icon-camera-retro title"></i>Manage Project Categories</div>
		<div class="widget-icons pull-right">
			<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
		</div>  
		<div class="clearfix"></div>
	</div>             
	<div class="widget-content">
		<div class="padd">
			<?php echo form_open_multipart("admin/portfolio/add_category","class='form-horizontal'"); ?>
			<div class="control-group">
				<label class="control-label" for="date_start">Project Category</label>
				<div class="controls">
					<?php echo form_input($category); ?><span style="padding-left: 30px;"></span><button type="submit" class="btn btn-primary btn-large">Add Project Category</button>
					<span class="help-block" id="error-span">
						<?php echo form_error('category');?>
					</span>
				</div>
				<div class="form-actions" style="padding-left: 0px;">
					<?php echo $this->table->generate(); ?>
					<div id="pagination"><?php echo $this->pagination->create_links(); ?></div>
				</div>
			
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>