<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"><![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"><![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"><![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"><!--<![endif]-->

<head>
    <title>Shop</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width; initial-scale=1; maximum-scale=1; minimum-scale=1; user-scalable=no;"/>
    <link rel="shortcut icon" href="<?php echo base_url()."favicon.png";?>"/>

    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()."css/menu.css";?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()."css/category_menu.css";?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()."css/bootstrap.css";?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()."css/jasny-bootstrap.css";?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()."css/style.css";?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()."css/prettyPhoto.css";?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()."css/font-awesome.min.css";?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()."css/sequencejs.css";?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()."css/jquery.countdown.css";?>"/>
    <!--[if IE 7]>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()."css/font-awesome-ie7.min.css";?>"/>
    <![endif]-->

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>

<body>
<input type="hidden" id="baseurl" value="<?php echo site_url();?>"/>
<!-- start: TOP BAR -->
<div class="topbar clearfix">
    <div class="container">
        <ul class="nav nav-pills top-contacts pull-left">
            <li><a href="#"><i class="icon-phone"></i> +1 (123) 12-12-123</a></li>
            <li><a href="#"><i class="icon-twitter"></i> Twitter</a></li>
            <li><a href="#"><i class="icon-facebook"></i> Facebook</a></li>
        </ul>
        <ul class="nav nav-pills top-menu pull-right">
            <li><a href="./blog-sidebar-right.html">Blog</a></li>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    Currency <b class="caret"></b></a>
                <ul class="dropdown-menu">
                	<?php
						if(is_array($currencies)){
							foreach($currencies as $cur){
								$id = $cur->currency_ID;
								$name = $cur->currency;
								$symbol = $cur->symbol;
								if(($symbol == NULL) || (empty($symbol))){
									$symbol = $cur->acronym;
								}
								?>
								<li><a href="<?php echo $id;?>" class="currency"><?php echo $symbol;?> <?php echo $name;?></a></li>
								<?php
							}
						}
					?>
                </ul>
            </li>
            <li><a href="./contact.html">Contact</a></li>
            <li><a href="#">Sitemap</a></li>
        </ul>
    </div>
</div>
<!-- end: TOP BAR -->