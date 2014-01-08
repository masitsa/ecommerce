

<fieldset>
<legend>Items In Cart</legend>
<div>
	<a href="<?php echo site_url('shop/browse/open_products/')?>">Product List</a>
    <?php
		$total_cart_items = $this->cart->total_items();
		if($total_cart_items > 0){
			?><a href="<?php echo site_url()."/shop/browse/checkout/";?>">Checkout</a><?php 
		}
	?>
</div>
<?php 
	if($this->cart->total_items() == 0){
		echo "There are no items in the cart";
	}
	
	else{
?>
<?php echo form_open('cart/update_cart3/'); ?>

<table cellpadding="6" cellspacing="1" style="width:100%" border="0">

<tr>
  <th>Item Description</th>
  <th>QTY</th>
  <th style="text-align:right">Item Price</th>
  <th style="text-align:right">Sub-Total</th>
</tr>

<?php $i = 1; ?>

<?php foreach($this->cart->contents() as $items): ?>

	<?php echo form_hidden('rowid[]', $items['rowid']); ?>
	
	<tr>
	  <td>
		<?php echo $items['name']; ?>
					
			<?php if ($this->cart->has_options($items['rowid']) == TRUE): ?>
					
				<p>
					<?php foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value): ?>
						
						<strong><?php echo $option_name; ?>:</strong> <?php echo $option_value; ?><br />
										
					<?php endforeach; ?>
				</p>
				
			<?php endif; ?>
				
	  </td>
	  <td><?php echo form_input(array('name' => 'qty[]', 'value' => $items['qty'], 'maxlength' => '3', 'size' => '5')); ?></td>
	  <td style="text-align:right"><?php echo $this->cart->format_number($items['price']); ?></td>
	  <td style="text-align:right"><?php echo $this->cart->format_number($items['subtotal']); ?></td>
	  <td style="text-align:left"><a href="<?php echo site_url()."/cart/delete_item3/".$items['rowid'];?>">Delete</a></td>
	</tr>

<?php $i++; ?>

<?php endforeach; ?>

<tr>
  <td colspan="3" align="right"><strong>Total</strong></td>
  <td align="right"><?php echo $this->cart->format_number($this->cart->total()); ?></td>
</tr>

</table>

<p><?php echo form_submit('', 'Update your Cart'); ?></p>
<a href="<?php echo site_url()."/cart/empty_cart3/";?>">Empty Cart</a>
<?php }?>
