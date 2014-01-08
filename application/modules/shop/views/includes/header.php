
<!-- start: Main Menu -->
<div id="navigation" class="default">
    <div class="container">
        <!--<div class="login">
            <a href="#" class="account-avatar" title="LOG IN"><img src="<?php echo base_url()."img/avatar.png";?>" alt=""/></a>
            <a href="#" class="account" data-original-title="LOG IN" data-placement="top" rel="tooltip"><i class="icon-signin"></i></a>
        </div>-->
        <div class="navbar navbar-static-top">
            <div class="navbar-inner">
                <ul class="nav pull-left">
                    <li>
                        <a href="./index.html">
                            Home</a>
                    </li>
                    <li class="dropdown active">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="<?php echo base_url()."browse/";?>">
                            Shop <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo base_url()."shop/browse/";?>">Products</a></li>
                            <li><a href="./product.html">Product details</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="./about.html">
                            Features <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="./about.html">About Us</a></li>
                            <li><a href="./services.html">Our Services</a></li>
                            <li><a href="./full-width.html">Full Width</a></li>
                            <li><a href="./font-icons.html">All Icons</a></li>
                            <li><a href="./price-table.html">Price Table</a></li>
                            <li><a href="./404.html">404 Page not found</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="docs/index.html">
                            Bootstrap <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="docs/index.html">Extend Bootstrap</a></li>
                            <li><a href="docs/scaffolding.html">Scaffolding</a></li>
                            <li><a href="docs/base-css.html">Base CSS</a></li>
                            <li><a href="docs/components.html">Components</a></li>
                            <li><a href="docs/javascript.html">JavaScript</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="./portfolio.html">
                            Portfolio <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="./portfolio.html">Portfolio</a></li>
                            <li><a href="./portfolio-single.html">Single Portfolio</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="./blog-sidebar-right.html">
                            Blog <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="./blog-sidebar-right.html">Blog</a></li>
                            <li><a href="./blog-single.html">Blog Single</a></li>
                        </ul>
                    </li>
                    <li><a href="./contact.html">Contacts</a></li>
                </ul>
                <div class="shopping-cart pull-right">
					<?php
                        if(is_array($currency)){
                            foreach($currency as $cur){
                                $name = $cur->symbol;
                                if(($name == NULL) || (empty($name))){
                                    $name = $cur->acronym;
                                }
                            }
                        }
                        else{
                            $name = "";
                        }
                    ?>
                    <a href="#" class="cart" id="cart_content">
						<span class="quantity"><?php echo $total_cart_items;?></span>
						<span class="amount"><i class="icon-shopping-cart"></i> <?php echo $name." ".$this->cart->format_number($total_cost);?></span>
                    </a>
                    <div class="cart-dropdown">
                        <h2 class="title">Cart</h2>
                        <div class="content" id="cart_content2">
							<?php 
								//$data['products'] = $cart_products;
								$data['total_cart_items'] = $total_cart_items;
								$data['total_cost'] = $total_cost;
								$data['cart_contents'] = $cart_contents;
								echo $this->view('cart/items_in_cart2.php', $data); 
							?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end: Main Menu -->

<!-- start: Page header / Breadcrumbs -->
<div id="breadcrumbs">
    <div class="container">
        <div class="breadcrumbs" id="crumbs">
        	<?php $this->load->view("includes/crumbs");?>
            <!-- <a href="./index.html">Photography</a><i class="icon-angle-right"></i>
            <a href="./index.html">Digital Cameras</a><i class="icon-angle-right"></i>
            Canon EOS Rebel T4i -->
       	</div>
    </div>
</div>
<!-- end: Page header / Breadcrumbs -->

<!-- start: Container -->
<div id="container">
    <div class="container">
        <div class="row-fluid">
        