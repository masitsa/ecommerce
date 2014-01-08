
<input type="hidden" value="<?php echo site_url();?>" id="baseurl"/>
<input type="hidden" value="<?php echo $page;?>" id="page"/>
 <!-- start: Page section -->
        <section class="span9 page-sidebar pull-right">
            	
            <div id="dl-menu" class="dl-menuwrapper">
                Categories <button class="dl-trigger" id="pull"></button>
                <ul class="dl-menu">
                    <?php 
                        $this->load->view("includes/pull_left3");
                    ?>
                </ul>
            </div>
        	<div id="sub_margin">
                <div id="sequence-theme">
                    <div id="sequence">
                        <ul>
                            
                        </ul>
                    </div>
                </div></div>
            <!--<section id="slider">
            </section>-->
            <div id="top">
			<?php 
				$data['first'] = $first;
				$data['last'] = $last;
				$data['total'] = $total;
				$this->load->view("top", $data);
			?>
            </div>
            
            <!-- start: products listing -->
            <div class="row-fluid shop-products box-1" id="products">
            	<?php 
					$data['products'] = $products;
					$this->load->view("products", $data);
				?>
            </div>
            <!-- end: products listing -->
			
            <div id="pagination">
            	<?php 
					$data['links'] = $links;
					$this->load->view("pagination", $data);
				?>
            </div>

        </section>
        <!-- end: Page section -->