<!DOCTYPE html>
<html lang="en" class="no-js">
	
	<?php echo $this->template->widget('site_header'); ?>

	<body>
	
		<?php echo $this->template->widget("site_navigation"); ?>
		
		<div class="container">
			
			<?php echo $this->template->widget("site_breadcrumbs_and_subnav"); ?>
			
			<?php echo $this->template->content; ?>
			
		</div>
		
		<?php echo $this->template->widget("site_footer"); ?>
	
	</body>
	
</html>