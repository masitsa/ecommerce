<script type="text/javascript">
//<!--
	$(document).ready(function() {
		$('a.more-stats-link').unbind('click').click(function() {
			if($(this).children('i').hasClass('icon-double-angle-down')) {
				$(this).html('Less <i class="icon-double-angle-up"></i>');
				$(this).parents('div.page-views,div.post-views').css('height','100%').css('max-height','100%').css('overflow','visible');
			} else {
				$(this).html('More <i class="icon-double-angle-down"></i>');
				$(this).parents('div.page-views,div.post-views').css('height','115px').css('max-height','115px').css('overflow','hidden');
			}
		});
	});
//-->
</script>
<div class="padd">
	<div class="row-fluid">
		<div class="stat-box span3">
			<i class="icon-group pull-left stat-title-icon"></i><h3>Users</h3>
			<p>
				<span class="stats"><?php echo $this->admin_model->get_no_of_users(); ?></span>Total<br />
				<span class="stats"><?php echo $this->admin_model->get_no_of_deactivated_users(); ?></span>Deactivated<br />
				<span class="stats"><?php echo $this->admin_model->no_of_admins(); ?></span>Administrators
			</p>
		</div>
		<div class="stat-box page-views span3">
			<a href="#" class="more-stats-link pull-right">More <i class="icon-double-angle-down"></i></a>
			<i class="icon-desktop pull-left stat-title-icon"></i><h3>Page Views</h3>
			<p>
				<?php echo modules::run('admin/page_view_stats'); ?>
			</p>
		</div>
		<div class="stat-box post-views span3">
			<a href="#" class="more-stats-link pull-right">More <i class="icon-double-angle-down"></i></a>
			<i class="icon-desktop pull-left stat-title-icon"></i><h3>Post Views</h3>
			<p>                        
				<?php echo modules::run('admin/post_view_stats'); ?>
			</p>
		</div>
		<div class="stat-box span3">
			<i class="icon-signin pull-left stat-title-icon"></i><h3>Recent Logins</h3>
			<p>                        
				<?php echo modules::run('admin/recent_logins'); ?>
			</p>
		</div>
	</div>
</div>
<div class="widget">
	<div class="widget-head">
		<div class="pull-left"><i class="icon-edit title"></i>Pages</div>
		<div class="widget-icons pull-right">
			<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a>
		</div>  
		<div class="clearfix"></div>
	</div>             
	<div class="widget-content">
		<div class="padd">
			<?php echo modules::run('admin/pages/dashboard_widget'); ?>
		</div>
	</div>
</div>
<div class="widget">
	<div class="widget-head">
		<div class="pull-left"><i class="icon-pushpin title"></i>Blog Posts</div>
		<div class="widget-icons pull-right">
			<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a>
		</div>  
		<div class="clearfix"></div>
	</div>             
	<div class="widget-content">
		<div class="padd">
			<?php echo modules::run('admin/blog/dashboard_widget'); ?>
		</div>
	</div>
</div>