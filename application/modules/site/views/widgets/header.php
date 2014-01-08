<head>

	<meta charset="utf-8">
	<title><?php echo $this->template->title; ?> &middot; <?php echo $site_name; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	
	<!-- Le styles -->
	<link href="<?php echo base_url();?>css/misty/misty-bootstrap.css" rel="stylesheet">
	<link href="<?php echo base_url();?>css/misty/misty-style.css" rel="stylesheet">
	<link href="<?php echo base_url();?>css/misty/misty-font-awesome.min.css" rel="stylesheet">
	<?php echo $this->template->stylesheet; ?>
	
	<!-- Le fav icon -->
	<link rel="shortcut icon" href="<?php echo base_url().'favicon.png';?>">
	
	<!-- Le scripts -->
	<script type="text/javascript">
	    var CI = {
	       'base_url': '<?php echo base_url(); ?>',
		   'page_uuid': '<?php echo isset($details->page_uuid)?$details->page_uuid:FALSE; ?>'
	    };
	</script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="<?php echo base_url();?>js/jquery-1.7.2.min.js"><\/script>')</script>
	<script src="<?php echo base_url();?>js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>js/misty/misty-jquery.prettyPhoto.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>js/misty/misty-jquery.easing.1.3.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/misty/misty-jquery.ui.totop.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>js/modernizr.custom.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>js/enquire.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>js/misty/misty-main.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>js/misty/misty-cms-analytics.js"></script>
	<?php echo $this->template->javascript; ?>
	
</head>