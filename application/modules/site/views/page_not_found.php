<!DOCTYPE html>
<html lang="en">

	<?php echo $this->template->widget('error_header'); ?>

	<body>
	
		<?php echo $this->template->widget("site_navigation"); ?>
		
		<div class="container">
		
			<div class="hero-unit">
				<h2>Page Not Found</h2>	
				<p>Sorry, we could not locate the page you are looking for.</p>
				<p>Make sure you typed the URL in correctly.</p>
				<a href="javascript:history.go(-1);">Go back from whence you came</a>
			</div>
			
		</div>
		
	</body>
	
</html>