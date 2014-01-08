<div class="navbar  navbar-fixed-top">
	<div class="navbar-inner">
		<?php
				if($this->session->userdata('logged_in')) {
					echo('<ul class="nav pull-right">');
					echo '<li class="hidden-desktop">'.anchor('site/auth/logout','<i class="icon-signout icon-white" style="font-size:1.5em;"></i>',array('class'=>'pull-right','title'=>'Logout')).'</li>';
					echo '<li class="dropdown visible-desktop">'.anchor($this->session->userdata('logout'),'<b class="caret"></b>'.ucfirst($this->session->userdata('username')),array('class'=>'pull-right','data-toggle'=>'dropdown'));
					echo("<ul class='dropdown-menu'>");
						echo '<li class="dropdown">'.anchor($this->session->userdata('logout'),'<i class="icon-user icon-white"></i>Logout',array('class'=>'pull-right')).'</li>';
					echo('</ul></li></ul>');
				} else {
					echo('<ul class="nav pull-right">');
					echo '<li>'.anchor('site/auth/login','Login',array('class'=>'pull-right','style'=>'font-size:0.9em;')).'</li>';
					echo('</ul>');
				}
				?>
		<div class="container">
			
			<div class="row-fluid">
				<a class="brand hidden-tablet hidden-phone" href="<?php echo base_url(); ?>"><?php echo $site_name; ?></a>
				<a class="brand hidden-desktop" href="<?php echo base_url(); ?>" style="text-align: center;"><?php echo $site_name; ?></a>
				<button type="button" class="btn btn-navbar collapsed hidden-desktop" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				
				<div class="nav-collapse" style="height: 0px;">
					<ul class="nav pull-right">
						<?php echo modules::run('site/show_main_nav'); ?>
					</ul>
				</div>
				
				
			</div>
		</div>
	</div>
</div>