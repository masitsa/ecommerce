<?php
	$related_products = $this->order_model->fetch_top_categories();
	
	//retrieve featured products
	$featured_products = $this->administration_model->select_limit(4, "product", "product_id > 0", "*", "product_name");
?>

<div class="row-fluid" style="margin-top:5%;">
    <h3>Top Categories</h3>
</div>


<!-- start: Top categories -->
<div class="row-fluid featured-categories">
    <ul class="thumbnails">
        <?php 
		if(is_array($related_products)){
			foreach ($related_products as $prod):
				
				$category_id = $prod->category_id;
				$category_name = $prod->category_name;
				$category_image_name = $prod->category_image_name;
				$image = base_url()."assets/categories/images/".$category_image_name;
				
				//retrieve children of this category
				$result = $this->administration_model->select_limit(4, "category", "category_parent = ".$category_id, "category_name, category_id", "category_name");
	
		?> 
        <li class="span3 item">
            <div class="thumbnail">
                <a href="<?php echo site_url()."shop/browse/open_products/".$category_id?>" class="image" style="height:100px; background-color:#FFF; padding:15%;">
                    <img src="<?php echo $image; ?>" width="150px" alt="<?php echo $category_name; ?>">
                    <span class="frame-overlay"></span>
                    <h4 class="title"><i class="icon-folder-open"></i> <?php echo $category_name; ?></h4>
                    <span class="link">view all products <i class="icon-chevron-right"></i></span>
                </a>
                <div class="inner notop">
                    <p class="description">
						<?php
                        if(is_array($result)){
							$total = 2;
							$total_count = 0;
                            foreach($result as $res){
								$total_count++;
                                $child_id = $res->category_id;
                                $child_name = $res->category_name;
                                ?>
                                    <a href="<?php echo site_url()."shop/browse/open_products/".$child_id?>"><?php echo $child_name?></a>, 
                                <?php
								if($total == $total_count){
									break;
								}
                            }
                        }
                        ?>
                        <a href="<?php echo site_url()."shop/browse/open_products/0"?>" class="see-all">See All</a>
                    </p>
                </div>
            </div>
        </li>
        <?php endforeach;
    }
    ?>
    </ul>
</div>
<!-- end: Top categories -->

<div class="row-fluid" style="margin-top:5%;">
    <h3>Featured Products</h3>
</div>
 <!-- start: Featured products -->
<div class="row-fluid featured-products">
    <ul class="thumbnails">
     <?php 
		if(is_array($featured_products)){
			foreach ($featured_products as $prod):
				
				$product_id = $prod->product_id;
				$product_name = $prod->product_name;
				$product_selling_price = $prod->product_selling_price;
				$product_image_name = $prod->product_image_name;
				$image = base_url()."assets/products/images/".$product_image_name;
	?>
        <li class="span3 item">
            <div class="thumbnail">
                <a href="#" class="image" style="height:220px; background-color:#FFF;">
                    <img src="<?php echo $image;?>" alt="<?php echo $product_name;?>">
                    <span class="frame-overlay"></span>
                    <span class="price">KES <?php echo $this->cart->format_number($product_selling_price); ?></span>
                </a>
                <div class="inner notop nobottom">
                    <h4 class="title"><?php echo $product_name;?></h4>
                    <!--<p class="description">Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>-->
                </div>
            </div>
            <div class="inner darken notop">
                <?php echo form_hidden('quantity', '1');echo form_hidden('product_id', $product_id); ?> 	
                            <?php 
								$attributes = array("name" => "add", "value" => "Add", "style" => "margin:0 0 0;", "class" => "btn btn-add-to-cart");
								echo form_submit($attributes); 
							?>  
            </div>
        </li>
    <?php endforeach; }?>
    </ul>
</div>
<!-- end: Featured products -->