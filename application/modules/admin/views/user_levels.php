<?php
$parents = $this->admin_model->get_level_parents();
$arr = '';
foreach($parents as $parent)
	$arr[$parent->u_level_id] = $parent->user_level;
$arr[0] = 'None';
$user_level = array('name'=>'user_level','id'=>'user_level','value'=>'','class'=>'input-1','size'=>30);
if($this->uri->segment(3)=='e'){?>
<div class="alert alert-error">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<p>User level cannot be deleted because the user level has users</p>
</div>
<?php } ?>
<div class="widget">
	<div class="widget-head">
		<div class="pull-left"><i class="icon-group title"></i>User Levels</div>
		<div class="widget-icons pull-right">
			<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
		</div>  
		<div class="clearfix"></div>
	</div>             
	<div class="widget-content">
		<div class="padd">
			<?php if(isset($deactivated)) { ?>
			<div class="alert alert-info">
				<p>
				<span class="ico-text ico-alert-info"></span>
				<?php echo $deactivated; ?>
				</p>
			</div>
			<?php } else { ?>
			<?php echo form_open('admin/add_u_level',"class = 'form-inline'"); ?>
				<?php echo form_input($user_level,'',"placeholder = 'Level Name'"); ?>
				<?php echo form_label('Parent'); ?>
				<?php echo form_dropdown('parent[]',$arr,0);?>
				<button type="submit" class="btn btn-primary">Add Level</button>
			<?php echo form_close();?>
				<?php echo form_open('admin/save_level_bulk'); ?>
				<?php echo $this->table->generate(); ?>
				<?php  echo form_close(); ?>
				<div id="pagination"><?php echo $this->pagination->create_links(); ?></div>
			<?php } ?>
		</div>
	</div>
</div>