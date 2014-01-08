<script>
    $(function() {
        $('#page_order').spinner({
            min: 1,
            max: 20,
            step: 1
        });
    });
</script>
<?php
$page_order = array('name'=>'page_order','id'=>'page_order','value'=>$details->page_order);
?>
<div class="widget">
	<div class="widget-head">
		<div class="pull-left"><i class="icon-cog title"></i>Edit Page</div>
		<div class="widget-icons pull-right">
			<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
		</div>  
		<div class="clearfix"></div>
	</div>             
	<div class="widget-content">
		<div class="padd">
			<?php echo form_open($this->uri->uri_string(),"class='form-horizontal'"); ?>
			<div class="control-group">
				<label  class="control-label" for="page_title">Page Title</label>
				<div class="controls">
					<?php echo form_input('page_title',$details->page_title);?>
					<span class="help-block" id="error-span">
						<?php echo form_error('page_title');?>
					</span>
				</div>
			</div>
			<div class="control-group">
				<label  class="control-label" for="page_order">Page Order</label>
				<div class="controls">
					<?php echo form_input($page_order);?>
					<span class="help-block" id="error-span">
						<?php echo form_error('page_order');?>
					</span>
				</div>
			</div>
			<div class="form-actions">
				<button type="submit" class="btn btn-primary btn-large">Save</button>
			</div>
		<?php echo form_close(); ?>
		</div>
	</div>
</div>