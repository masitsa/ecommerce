<!DOCTYPE html>
<html lang="en">
	<?php echo $this->template->widget('admin_header'); ?>
	
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
		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<!-- Menu button for smallar screens -->
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span>Menu</span>
					</a>
					<!-- Site name for smallar screens -->
					<a href="index.html" class="brand hidden-desktop">MacBeath</a>
					<!-- Navigation starts -->
					<div class="nav-collapse collapse">
						<!-- Links -->
						<ul class="nav pull-right">
							<li class="dropdown pull-right">            
								<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="icon-user"></i> Admin <b class="caret"></b>              
								</a>
								<!-- Dropdown menu -->
								<ul class="dropdown-menu">
									<li><a href="#"><i class="icon-user"></i> Profile</a></li>
									<li><a href="#"><i class="icon-cogs"></i> Settings</a></li>
									<li><a href="login.html"><i class="icon-off"></i> Logout</a></li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<!-- Main content starts -->
		<div class="content">
			<?php echo $this->template->widget("admin_navigation"); ?>
			
			<!-- Main bar -->
			<div class="mainbar">
				<!-- Page heading -->
				<div class="page-head">
					<h2 class="pull-left"><i class="icon-home"></i> Dashboard</h2>
					<!-- Breadcrumb -->
					<div class="bread-crumb pull-right">
						<a href="index.html"><i class="icon-home"></i> Home</a> 
						<!-- Divider -->
						<span class="divider">/</span> 
						<a href="#" class="bread-current">Dashboard</a>
					</div>
					<div class="clearfix"></div>
				</div>
				<!-- Page heading ends -->
				<!-- Matter -->
				<div class="matter">
					<div class="container-fluid">
						<!-- Today status. jQuery Sparkline plugin used. -->
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