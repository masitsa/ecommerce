<!DOCTYPE html>
<html lang="en" class="no-js">

	<?php echo $this->template->widget('site_header'); ?>

		<script>
			$('.carousel').carousel({
			  interval: 5000
			})
			
			$(document).ready(function(){
		        $('#layerslider').layerSlider({
		            skinsPath : '<?php echo base_url();?>css/misty/skins/',
		            skin : 'fullwidth',
		            thumbnailNavigation : 'hover',
		            hoverPrevNext : false
		        });
	    	});
		</script>

	<body>
	
		<?php echo $this->template->widget("site_navigation"); ?>
		
		<div class="container">
			
			<section class="slider-wrap" style="width: 100%;">
			   <div id="layerslider" style="width: 100%; height: 450px; margin: 0px auto; ">
					<?php echo modules::run('site/carousel/build_carousel'); ?>
				</div>
			</section>
			
			<?php echo $this->template->content; ?>
			
		</div>
		
		<?php echo $this->template->widget("site_footer"); ?>
		
	</body>
	
</html>