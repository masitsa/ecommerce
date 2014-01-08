

<fieldset>
<legend>Items In Cart</legend>
<div>
	<a href="<?php echo site_url('orders/open_products/'.$order_id)?>">Product List</a>
</div>
<!--
	Customer Details
-->
<table align="center" border="0">
		<tr>
			<td>
            	<?php
					if(count($customer) > 0){
						foreach ($customer as $cust){
							$customer_name = $cust->customer_name;
							$customer_phone = $cust->customer_phone;
							$customer_address = $cust->customer_address;
							
							echo "<strong>Customer: </strong>".$customer_name." <strong>Phone: </strong>".$customer_phone." <strong>Address </strong>".$customer_address;
						}
					}
				?>
            </td>
		</tr>
	</table>
<!--
	Cart Details
-->

<table border="1" align="center">

<tr>
  <th>#</th>
  <th>QTY</th>
  <th>Item Description</th>
  <th style="text-align:right">Item Price</th>
  <th style="text-align:right">Sub-Total</th>
</tr>

<?php $i = 1; ?>

<?php foreach($this->cart->contents() as $items): ?>

	<?php echo form_hidden('rowid[]', $items['rowid']); ?>
	
	<tr>
	  <td><?php echo $i; ?></td>
	  <td><?php echo $items['qty']; ?></td>
	  <td><?php echo $items['name']; ?></td>
	  <td style="text-align:right"><?php echo $this->cart->format_number($items['price']); ?></td>
	  <td style="text-align:right"><?php echo $this->cart->format_number($items['subtotal']); ?></td>
	</tr>

<?php $i++; ?>

<?php endforeach; ?>

<tr>
  <td colspan="4" align="right"><strong>Total</strong></td>
  <td align="right"><?php echo $this->cart->format_number($this->cart->total()); ?></td>
</tr>
</table>
<!--
	Order Description
-->
<?php echo form_open('orders/add_order_description/'.$order_id); ?><?php
			if(count($orders) > 0){
				foreach ($orders as $cust){
					$order_instructions = $cust->order_instructions;
					$coupon_id2 = $cust->coupon_id;
					$order_discount = $cust->order_discount;
				}
			}
			else{
				$coupon_id2 = NULL;
			}
		?>
<table border="0" align="center">
	<tr>
    	<td>Instructions</td>
    	<td><textarea name="order_instructions"><?php echo $order_instructions;?></textarea></td>
	</tr>
	<tr>
    	<td>Coupon</td>
    	<td><select name="coupon_id"><option value="0">----None----</option>
            	<?php
					if(count($coupons) > 0){
						foreach ($coupons as $cust){
							$coupon_name = $cust->coupon_name;
							$coupon_id = $cust->coupon_id;
							
							if($coupon_id == $coupon_id2){
								echo "<option value='".$coupon_id."' selected>".$coupon_name."</option>";
							}
							else{
								echo "<option value='".$coupon_id."'>".$coupon_name."</option>";
							}
						}
					}
				?>
            </select></td>
	</tr>
	<tr>
    	<td>Discount (KES)</td>
    	<td><input type="text" name="order_discount" value="<?php echo $order_discount;?>"/></td>
	</tr>
    <tr>
    	<td colspan="2" align="center"><?php echo form_submit('', 'Save'); ?></td>
    </tr>
</table>

<a href="<?php echo site_url()."/orders/view_shop/cart/".$order_id;?>">Edit Order</a>
