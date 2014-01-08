<?php
$parents = $this->admin_model->get_group_parents();
$arr = '';
foreach($parents as $parent)
	$arr[$parent->u_group_id] = $parent->user_group;
$arr[0] = 'Group Parent';
$user_group = array('name'=>'user_group','id'=>'user_group','value'=>'','class'=>'input-1','size'=>30);
if($this->uri->segment(3)=='e'){ ?>
<div class="alert alert-error">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<p>User group cannot be deleted because the user group has users</p>
</div>
<?php } ?>
<div class="widget">
	<div class="widget-head">
		<div class="pull-left"><i class="icon-group title"></i>User Groups</div>
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
				<?php echo form_open("admin/add_u_group","class = 'form-inline'"); ?>
				<?php echo form_input($user_group,'',"placeholder = 'Group Name'"); ?>
				<?php echo form_dropdown('parent[]',$arr,0,"placeholder = 'Parent'");?>
				<button type="submit" class="btn btn-primary">Add Group</button>
				<?php echo form_close();?>
				<?php echo form_open('admin/save_group_bulk'); ?>
				<?php echo $this->table->generate(); ?>
				<?php  echo form_close(); ?>
				<div id="pagination"><?php echo $this->pagination->create_links(); ?></div>
			<?php } ?>
		</div>
	</div>
</div>