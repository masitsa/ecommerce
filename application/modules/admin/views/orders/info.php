<div id="contentpop">
	<div class="boxpop corners shadow">
	    <div class="box-headerpop">
	       <h3>Order Details</h3>
		 </div>
	    <div class="box-contentpop">
		    <table style="width: 100%;">
				<tr>
					<td>Order Placed By</td>
					<td><?php echo $details->first_name.' '.$details->last_name; ?></td>
				</tr>
				<tr>
					<td>Order Date</td>
					<td><?php echo date('jS M Y H:i a',strtotime($details->order_date)); ?></td>
				</tr>
				<tr>
					<td>Order Method</td>
					<td><?php echo $details->order_method_name; ?></td>
				</tr>
				<tr>
					<td>Comments</td>
					<td><?php echo $details->order_instructions; ?></td>
				</tr>
				<tr>
					<td>Order Status</td>
					<td><?php echo $details->status_name; ?></td>
				</tr>
				<tr>
					<td colspan="2"><h4>Products</h4></td>
				</tr>
				<?php
					$total_price = 0;
					if(is_array($items)){
					foreach($items as $item):
					$total_price += $item->order_item_price*$item->order_item_quantity;
				?>
				<tr>
					<td colspan="2"><?php echo img(array('src'=>base_url().'products/thumbs/'.$item->product_image_name,'alt'=>$item->product_name, 'class' => 'img-responsive')); ?></td>
				</tr>
				<tr>
					<td>Product Name</td>
					<td><?php echo $item->product_name; ?></td>
				</tr>
				<tr>
					<td>Customization</td>
					<td><?php echo $item->order_item_customization; ?></td>
				</tr>
				<tr>
					<td>Product Code</td>
					<td><?php echo $item->product_code; ?></td>
				</tr>
				<tr>
					<td>Quantity</td>
					<td><?php echo $item->order_item_quantity; ?></td>
				</tr>
				<tr>
					<td>Price</td>
					<td><?php echo $this->cart->format_number($item->order_item_price); ?></td>
				</tr>
				<tr>
					<td>Subtotal</td>
					<td><?php echo $this->cart->format_number($item->order_item_price*$item->order_item_quantity); ?></td>
				</tr>
				<?php endforeach; ?>
                
				<tr>
					<td colspan="2"><hr/></td>
				</tr>
				<tr>
					<td>Total Order Price</td>
					<td><?php echo $this->cart->format_number($total_price); ?></td>
				</tr>
                <?php }
				else{ echo "<tr><td colspan='2'>There are no products for this order :-|</td></tr>";}
				?>
		    </table>
		</div>
	</div>
</div>