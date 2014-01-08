
<?php if($total_cart_items > 0){?>

            <!-- start: products listing -->
            <div class="row-fluid shop-products">
            
            
<!-- Cart starts -->
        <!-- Title with number of items in shopping kart -->
          <h3 class="title"><i class="icon-shopping-cart"></i> Items in your cart [<span class="color"><?php echo $total_cart_items;?></span>]</h3>
            <br />

            <!-- Table -->
              <table class="table table-striped tcart">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Quantity</th>
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
                    <td><a href="<?php echo $product_id;?>" class="view_product"><?php echo $product; ?></a></td>
                    <!-- Product image -->
                    <td><a href="<?php echo $product_id;?>" class="view_product"><img class="media-object" src="<?php echo $image;?>" alt="<?php echo $product; ?>"/></a></td>
                    <!-- Quantity with refresh and remove button -->
                    <td>
                      <div class="input-append cart-quantity">
						<?php echo form_open('shop/cart/update_cart3'); ?>
						<?php echo form_hidden('rowid', $id); ?>
						<?php echo form_hidden('ajax', 4); ?>
                        <input type="text" value="<?php echo $quantity;?>" name="quantity" style="width:20px;">
                        <button class="btn btn-inverse" type="submit"><i class="icon-refresh"></i></button>
                        <button class="delete btn btn-inverse" type="button" value="<?php echo $id;?>"><i class="icon-remove"></i></button>  
	  					<!--<td><a href="<?php echo site_url()."/shop/cart/delete_item3/".$id;?>">Delete</a></td>--> 
                   		<?php echo form_close(); ?>    
                      </div>
                    </td>
                    
                    <!-- Unit price -->
                    <td>KES <?php echo $this->cart->format_number($price); ?></td>
                    <!-- Total cost -->
                    <td>KES <?php echo $this->cart->format_number($total2); ?></td>
				</tr>

			<?php endforeach; ?>                    
                  <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Total</th>
                    <th>KES <?php echo $this->cart->format_number($total); ?></th>
                  </tr>
                </tbody>
              </table>


              <form class="form-search">
              <!-- Discount Coupen -->
                <h5 class="title">Discount Coupon</h5>
                <div class="input-append">
                  <input style="width:220px;" id="appendedInputButton1" type="text" placeholder="Enter your discount coupon">
                  <button class="btn btn-inverse" type="button">Apply</button>
                </div>
                <br />
                <br />
                <!-- Gift coupen -->
                <h5 class="title">Gift Coupon</h5>
                <div class="input-append">
                  <input style="width:220px;" id="appendedInputButton2" type="text" placeholder="Enter your gift coupon">
                  <button class="btn btn-inverse" type="button">Apply</button>
                </div>                
              </form>  
               
               <!-- Button s-->
              <div class="row">
                <div class="span4 offset8">
                  <div class="pull-right">
                    <a href="<?php echo base_url()."browse";?>" class="btn btn-inverse">Continue Shopping</a>
                    <a href="checkout.html" class="btn btn-danger">CheckOut</a>
                  </div>
                </div>
              </div>

<!-- Cart ends -->
                
            </div>
            <!-- end: products listing -->

            <!-- start: Pagination -->
            <div class="pagination pagination-centered">
                <ul>
                    <li class="disabled"><a href="#"><i class="icon-double-angle-left"></i></a></li>
                    <li class="active"><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li><a href="#">5</a></li>
                    <li><a href="#"><i class="icon-double-angle-right"></i></a></li>
                </ul>
            </div>
            <!-- end: Pagination -->
	<?php }
	
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