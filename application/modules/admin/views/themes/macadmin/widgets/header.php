<head>
	
	<meta charset="utf-8">
	<title><?php echo $site_name; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	
	<!-- Le styles -->
	<link href="<?php echo base_url();?>css/facebox.css" rel="stylesheet" />
	<link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/themes/flick/jquery-ui.css" media="screen" type="text/css" rel="stylesheet" />
	
	<link href="<?php echo base_url();?>css/bootstrap.flatly.css" rel="stylesheet">
	<link href="<?php echo base_url();?>css/macadmin/font-awesome.css" rel="stylesheet"> 
	<link href="<?php echo base_url();?>css/macadmin/jquery-ui.css" rel="stylesheet"> 
	<link href="<?php echo base_url();?>css/macadmin/bootstrap-toggle-buttons.css" rel="stylesheet">
	<link href="<?php echo base_url();?>css/macadmin/style.css" rel="stylesheet">
	<link href="<?php echo base_url();?>css/macadmin/bootstrap-responsive.css" rel="stylesheet">
	<link href="<?php echo base_url();?>css/macadmin/macadmin-custom.css" rel="stylesheet" />
	<?php echo $this->template->stylesheet; ?>
	
	<!-- Le fav icon -->
	<link rel="shortcut icon" href="<?php echo base_url().'favicon.png';?>"/>
	
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
	
	<script src="<?php echo base_url();?>js/macadmin/jquery.uniform.min.js"></script> <!-- jQuery Uniform -->
	<script src="<?php echo base_url();?>js/macadmin/custom.js"></script> <!-- Custom codes -->
	
	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<?php echo $this->template->javascript; ?>

</head>