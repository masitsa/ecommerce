<?php
//$this->cart->destroy();
	$cart_products = 0;
	$required_total = 0;
	$total_cart_items = count($order_items);
	
	if($total_cart_items > 0){
?>
			<!-- Table -->
              <table class="table table-striped table-hover table-responsive">
                  <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Code</th>
                    <th>Name</th>
                    <?php
                    	if($order_type == 1){
							echo "<th>Customization</th>";
						}
					?>
                    <th>Stock Level</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                  </tr>
                <?php 
					$count = 0;
					$total = 0;
					
					foreach($order_items as $items): 
					
					if($cart_products == $required_total){
						$total2 = 0;
						$balance = $items->product_balance;
						$code = $items->product_code;
						$quantity = $items->order_item_quantity;
						$price = $items->order_item_price;
						$product = $items->product_name;
						$order_item_id = $items->order_item_id;
						$image = $items->product_image_name;
						$total2 = $quantity * $price;
						$total += $total2;
						$count++;
				?>
	
				<tr>
                    <!-- Index -->
                    <td><?php echo $count;?></td>
                    <td><?php echo img(array('src'=>base_url().'products/thumbs/'.$image,'alt'=>$product, 'class' => 'img-responsive'));?></td>
                    <td><?php echo $code;?></td>
                    <td><?php echo $product;?></td>
                    <?php
                    	if($order_type == 1){
							$order_customization = array(
								'name'=>'order_customization'.$order_item_id, 
								'id'=>'order_customization'.$order_item_id, 
								'value'=>$items->order_item_customization, 
								'onChange'=>'save_customization('.$order_item_id.')');
							echo "<td>".form_textarea($order_customization)."</td>";
						}
					?>
                    <td><?php echo $balance;?></td>
                    <td>
						<div class="input-append cart-quantity" style="color:#fff;">
                        	<input type="text" value="<?php echo $quantity;?>" id="quantity<?php echo $order_item_id;?>" style="width:20px;">
                        	<button class="btn" type="button" onClick="update_cart('<?php echo $order_item_id;?>')"><i class="icon-refresh"></i></button>
                        
                        	<button onClick="delete_cart_item('<?php echo $order_item_id;?>')" class="btn" type="button"><i class="icon-remove"></i>
                      </div>
                    </td>
                    <td><?php echo $price;?></td>
                    <td><?php echo $this->cart->format_number($total2);?></td>
				</tr>
                
			<?php 
					}
			endforeach; 
			?>                    
                  <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
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