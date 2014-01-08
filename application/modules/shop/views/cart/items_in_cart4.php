<?php
//$this->cart->destroy();
	$cart_products = 0;
	$required_total = 7;
	$total_cart_items = count($order_items);
	
	if($total_cart_items > 0){
?>
			<!-- Table -->
              <table class="table table-striped table-hover table-responsive">
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Total</th>
                  </tr>
                <?php 
					$count = 0;
					$total = 0;
					
					foreach($order_items as $items): 
					$cart_products++;
					if($cart_products < $required_total){
						$quantity = $items->order_item_quantity;
						$price = $items->order_item_price;
						$product = $items->product_name;
						$order_item_id = $items->order_item_id;
						$total2 = $quantity * $price;
						$total += $total2;
						$count++;
				?>
	
				<tr>
                    <!-- Index -->
                    <td><?php echo $count;?></td>
                    <!-- Product  name -->
                    <td>
                    	<?php echo $product; ?><br/>
                      @ <?php echo $this->cart->format_number($price); ?>
                    <!-- Quantity with refresh and remove button -->
                      <div class="input-append cart-quantity" style="color:#fff;">
                        <input type="text" value="<?php echo $quantity;?>" id="quantity<?php echo $order_item_id;?>" style="width:20px;">
                        <button class="btn" type="button" onClick="update_cart('<?php echo $order_item_id;?>')"><i class="icon-refresh"></i></button>
                        
                        <button onClick="delete_cart_item('<?php echo $order_item_id;?>')" class="btn" type="button"><i class="icon-remove"></i>
                      </div>
                    </td>
                    <!-- Total cost -->
                    <td><?php echo $this->cart->format_number($total2); ?></td>
				</tr>
                
			<?php 
					}
			endforeach; 
			?>                    
                  <tr>
                    <th></th>
                    <th>Total</th>
                    <th>KES <?php echo $this->cart->format_number($total); ?></th>
                  </tr>
              </table>
        <?php
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
	?>
	<div class="form-actions" style="margin-left:23%;">
		<a href="<?php echo site_url()."admin/orders/checkout/".$order_id;?>" class="btn btn-large">Checkout</a>
	</div>