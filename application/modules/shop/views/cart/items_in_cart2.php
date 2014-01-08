<?php
	$cart_products = 0;
	$required_total = 3;
	
	if($total_cart_items > 0){
	
		$cart_products++;
		if($cart_products < $required_total){
?>
			<!-- Table -->
            <table class="table table-striped tcart">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>
                <?php 
					$count = 0;
					$total = 0;
					
					foreach($cart_contents as $items): 
						$id = $items->order_item_id;
						$quantity = $items->order_item_quantity;
						$price = $items->order_item_price;
						$product = $items->product_name;
						$product_image_name = $items->product_image_name;
						$product_id = $items->product_id;
						$total2 = $quantity * $price;
						$total += $total2;
						$count++;
						$image = base_url()."assets/products/thumbs/".$product_image_name;
				?>
				<tr>
                    <!-- Index -->
                    <td><?php echo $count;?></td>
                    <!-- Product  name -->
                    <td>
                    	<a href="<?php echo $product_id;?>" class="view_product"><?php echo $product; ?></a>
                    <!-- Quantity with refresh and remove button -->
                      <div class="input-append cart-quantity" style="display:block;">
						<?php echo form_open('shop/cart/update_cart3'); ?>
						<?php echo form_hidden('order_item', $id); ?>
						<?php echo form_hidden('ajax', 4); ?>
                        <input type="text" value="<?php echo $quantity;?>" name="quantity" style="width:20px;">
                        <button class="btn btn-inverse" type="submit"><i class="icon-refresh"></i></button>
                        <button class="delete btn btn-inverse" type="button" value="<?php echo $id;?>"><i class="icon-remove"></i></button>  
	  					<!--<td><a href="<?php echo site_url()."/shop/cart/delete_item3/".$id;?>">Delete</a></td>--> 
                   		<?php echo form_close(); ?>    
                      </div>
                    </td>
                    <!-- Product image -->
                    <td><a href="<?php echo $product_id;?>" class="view_product"><img class="media-object" src="<?php echo $image;?>" alt="<?php echo $product; ?>"/></a>
                    </td>
                    <!-- Unit price -->
                    <td><?php echo $this->cart->format_number($price); ?></td>
                    <!-- Total cost -->
                    <td><?php echo $this->cart->format_number($total2); ?></td>
				</tr>

			<?php endforeach; ?>                    
                  <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Total</th>
                    <th>KES <?php echo $this->cart->format_number($total); ?></th>
                  </tr>
                </tbody>
              </table>
              
                <p class="buttons">
                    <a class="btn btn-inverse viewcart" href="#">View Cart</a>
                    <a href="<?php echo $_SESSION['category_id'];?>" class="btn btn-danger checkout">Checkout &rarr;</a>
                </p>
        <?php
		}
	}
	
	else{
		echo '
            <div class="row-fluid shop-result">
                <div class="inner darken clearfix">
                    <div class="span6 result-count">
                       There are no items in your cart.
                    </div>
                </div>
            </div>';
	}