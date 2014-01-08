
                <ul class="thumbnails">
				<?php $count = 0;
				if(is_array($products)){
					foreach ($products as $prod)://loop and display all the selected products
						$count++;
						if(($count == 1) || ((count($products)) % 3 == 0)){
							$position = "first";
						}
						else{
							$position = "";
						}
						//get the items from the array
						$product_id = $prod->product_id;
						$product_name = $prod->product_name;
						$product_selling_price = $prod->product_selling_price;
						$product_balance = $prod->product_balance;
						$product_description = $prod->product_description;
						$product_image_name = $prod->product_image_name;
						$image = base_url()."assets/products/images/".$product_image_name;
	
				?>  
					<?php 
						$attributes = array('style' => 'margin:0 0 0;');
						echo form_open('shop/cart/add_cart_item3', $attributes); 
					?>
    				<li class="item span4 first">
                        <div class="thumbnail">
                        	<a href="<?php echo $product_id;?>" class="image view_product" style="height:220px; background-color:#FFF;">
                                <img src="<?php echo $image; ?>" />  
                                <span class="frame-overlay"></span>
                                <span class="price">KES <?php echo $this->cart->format_number($product_selling_price); ?></span>
                            </a>
                            <div class="inner notop nobottom">
                                <h4 class="title"><?php echo $product_name; ?></h4>
                            </div>
                        </div>
                        <div class="inner darken notop">
                			<?php //echo form_input('quantity', '1', 'maxlength="2"', "style=width:2px;"); ?>  
                			<?php echo form_hidden('quantity', '1');echo form_hidden('product_id', $product_id); ?> 	
                            <?php 
								$attributes = array("name" => "add", "value" => "Add", "style" => "margin:0 0 0;", "class" => "btn btn-add-to-cart");
								echo form_submit($attributes); 
							?>  
                            <!--<a href="#" class="btn btn-add-to-cart">Add<i class="icon-shopping-cart"></i></a>-->
                        </div>
                    </li>
                    <?php echo form_close(); ?>
    				<?php endforeach;
				}
                	else{
						echo "There are no products by that name.";
					}
				?>
                </ul>