<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<!-- Menu button for smallar screens -->
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span>Menu</span>
			</a>
			<!-- Site name for smaller screens -->
			<?php echo anchor('admin/index',$site_name,array('class'=>'brand')); ?>
			<!-- Menu starts -->
			<div class="nav-collapse collapse visible-desktop">
				<!-- Links -->
				<ul class="nav pull-right">
					<li class="dropdown pull-right">            
						<a data-toggle="dropdown" class="dropdown-toggle hidden-phone hidden-tablet" href="#">
							<i class="icon-user"></i><?php echo ucfirst($this->session->userdata('username')); ?><b class="caret"></b>              
						</a>
						<!-- Dropdown menu -->
						<ul class="dropdown-menu">
							<li><?php echo anchor('admin/my_profile','<i class="icon-user"></i> Profile'); ?></li>
							<li><?php echo anchor('admin/settings','<i class="icon-cogs"></i> Settings'); ?></li>
							<li><?php echo anchor('','<i class="icon-eye-open"></i> Frontend',array('target'=>'frontend')); ?></li>
							<li><?php echo anchor('auth/logout','<i class="icon-off"></i> Logout'); ?></li>
						</ul>
					</li>
				</ul>
			</div>
			<div class="nav-collapse collapse hidden-desktop">
				<!-- Links -->
				<ul class="nav pull-right">
					<li><?php echo anchor('admin/my_profile','<i class="icon-user"></i> Profile'); ?></li>
					<li><?php echo anchor('admin/settings','<i class="icon-cogs"></i> Settings'); ?></li>
					<li><?php echo anchor('','<i class="icon-eye-open"></i> Frontend',array('target'=>'frontend')); ?></li>
					<li><?php echo anchor('auth/logout','<i class="icon-off"></i> Logout'); ?></li>
				</ul>
			</div>
		</div>
	</div>
</div>