<div class="widget">
	<div class="widget-head">
		<div class="pull-left"><i class="icon-folder-close title"></i>Posts</div>
		<div class="widget-icons pull-right">
			<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
		</div>  
		<div class="clearfix"></div>
	</div>             
	<div class="widget-content">
		<div class="padd">
			<?php echo $this->table->generate(); ?>
			<div id="pagination"><?php echo $this->pagination->create_links(); ?></div>
		</div>
	</div>
</div>