<!DOCTYPE html>
<html lang="en">
	
	<?php echo $this->template->widget('admin_header'); ?>
	<script type="text/javascript">
		$('document').ready(function(){
			window.onload = initialize();
		});
	</script>
	
	<body>
		
		<?php if(!empty($message)) { ?>
			<div class="alert alert-error">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<?php echo $message; ?>
			</div>
		<?php } if($this->session->flashdata('message')) { ?>
			<div class="alert alert-success">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<?php echo $this->session->flashdata('message'); ?>
			</div>
		<?php } if($this->session->flashdata('error')) { ?>
			<div class="alert alert-error">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php echo $this->session->flashdata('error'); ?>
			</div>
		<?php } ?>
		
		<?php echo $this->template->widget('admin_top_bar'); ?>
		
		<!-- Main content starts -->
		<div class="content">
			<?php echo $this->template->widget("admin_navigation"); ?>
			
			<!-- Main bar -->
			<div class="mainbar">
				<!-- Matter -->
				<div class="matter">
					<div class="container-fluid">
						
						<div class="row-fluid">
							<div class="span12">
								<?php echo $this->template->content; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
		<!-- Content ends -->
		
		<?php echo $this->template->widget('admin_footer'); ?>
		
	</body>
</html>