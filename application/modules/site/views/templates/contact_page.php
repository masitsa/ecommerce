<!DOCTYPE html>
<html lang="en" class="no-js">
	
	<?php echo $this->template->widget('site_header'); ?>

	<body onload="initialize()">
	
		<?php echo $this->template->widget("site_navigation"); ?>
		
		<div class="container">
			
			<?php echo $this->template->content; ?>
			
		</div>
		
		<?php echo $this->template->widget("site_footer"); ?>
	
	</body>
	
</html>