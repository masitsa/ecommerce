<?php 
if(!$this->session->userdata('logged_in')) 
	redirect('auth/logout');
$site_name = $this->admin_model->get_site_name();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo $site_name; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="<?php echo base_url();?>css/bootstrap.united.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>css/custom.css" rel="stylesheet">
    <link href="<?php echo base_url();?>css/facebox.css" rel="stylesheet">
	<link href="<?php echo base_url();?>css/bootstrap-responsive.css" rel="stylesheet">
	<link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/themes/flick/jquery-ui.css" media="screen" type="text/css" rel="stylesheet" />
	
	<!-- Le fav icon -->
	<link rel="shortcut icon" href="<?php echo base_url().'favicon.png';?>">
	
	<!-- Le scripts -->
	<script type="text/javascript">
	    var CI = {
	       'base_url': '<?php echo base_url(); ?>'
	    };
    </script>
	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="<?php echo base_url();?>js/jquery-1.7.2.min.js"><\/script>')</script>
	<script src="<?php echo base_url();?>js/bootstrap.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js"></script>
	<script src="<?php echo base_url();?>js/facebox.js"></script>
	<script type="text/javascript">
		$(document).ready(function($) {
		  $('a[rel*=facebox]').facebox()
		}) 
	</script> 

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

  </head>
  <body>
  <div class="container">
	<div class="navbar  navbar-fixed-top" >
		<div class="navbar-inner">
			<div class="container">
				<button type="button" class="btn btn-navbar collapsed hidden-desktop" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="brand" href="<?php echo base_url().'admin/index'; ?>"><?php echo $site_name; ?></a>
				<p class="navbar-text pull-right">
					<a href="<?php echo base_url(); ?>" target="frontend" title="View Frontend"><i class="icon-eye-open icon-white"></i></a>
	              	<a href="<?php echo base_url().'admin/my_profile';?>" class="navbar-link"><i class="icon-user icon-white"></i><?php echo ucfirst($this->session->userdata('username')); ?></a>
					<a href="<?php echo base_url().'auth/logout'; ?>" class="navbar-link"><i class="icon-off icon-white"></i>Logout</a>
	            </p>
				<div class="nav-collapse collapse hidden-desktop" style="height: 0px;">
					<ul class="nav">
						<?php echo modules::run('admin/acl/show_main_menu'); ?>
					</ul>
				</div>
				<div class="nav-collapse collapse hidden-tablet hidden-phone" style="height: 0px;">
					<ul class="nav">
						<?php echo modules::run('admin/acl/show_fancy_main_menu'); ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<?php if(!empty($message)) { ?>
	<div class="alert alert-error">
		<button type="button" class="close" data-dismiss="alert">×</button>
  		<?php echo $message; ?>
	</div>
	<?php } if($this->session->flashdata('message')) { ?>
	<div class="alert alert-success">
	    <button type="button" class="close" data-dismiss="alert">×</button>
	    <?php echo $this->session->flashdata('message'); ?>
	</div>
	<?php } if($this->session->flashdata('error')) { ?>
	<div class="alert alert-error">
	    <button type="button" class="close" data-dismiss="alert">×</button>
	    <?php echo $this->session->flashdata('error'); ?>
	</div>
	<?php } ?>