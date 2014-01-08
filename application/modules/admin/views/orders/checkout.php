<?php

$details = $this->orders_model->get_order_details($order_id);
$username = $details->first_name." ".$details->last_name;
$discount = $details->order_discount;
$instructions = $details->order_instructions;
$coupon = $details->coupon_id;

$order_instructions = array('name'=>'order_instructions','value'=>$instructions);
$order_discount = array('name'=>'order_discount','value'=>$discount);

$data['order_items'] = $this->orders_model->fetch_order_products($order_id);
$data['order_id'] = $order_id;
$data['order_type'] = $details->order_type;

?>
<p style="font-weight: bolder; font-size: 18px;">Customer: <?php echo $username;?></p>
<div class="row">
	<div id="content">
		<div class="content-top">
			<h3>Checkout</h3>
		</div>
		<div id="status">
    	<?php 
        	$this->load->view("orders/checkout_items", $data);
		?>
    	</div>
	<?php echo form_open_multipart($this->uri->uri_string(),"class='form-horizontal'"); ?>
        <div class="control-group">
			<label  class="control-label" for="order_instructions">Instructions</label>
			<div class="controls">
				<?php echo form_textarea($order_instructions);?>
				<span class="help-block">
					<?php echo form_error('order_instructions');?>
				</span>
			</div>
		</div>
        <div class="control-group">
			<label  class="control-label" for="order_coupon">Coupons</label>
			<div class="controls">
				<select name="coupon_id"><option value="0">----None----</option>
            	<?php
					if(count($coupons) > 0){
						foreach ($coupons as $cust){
							$coupon_name = $cust->coupon_name;
							$coupon_id = $cust->coupon_id;
							
							if($coupon_id == $coupon){
								echo "<option value='".$coupon_id."' selected>".$coupon_name."</option>";
							}
							else{
								echo "<option value='".$coupon_id."'>".$coupon_name."</option>";
							}
						}
					}
				?>
            </select>
				<span class="help-block">
					<?php echo form_error('coupon_id');?>
				</span>
			</div>
		</div>
        <div class="control-group">
			<label  class="control-label" for="order_disount">Discount</label>
			<div class="controls">
				<?php echo form_input($order_discount);?>
				<span class="help-block">
					<?php echo form_error('order_instructions');?>
				</span>
			</div>
		</div>
		<div class="form-actions" style="padding-left:40%;">
        	<a href="<?php echo site_url()."admin/orders/add_order_details/".$order_id;?>" class="btn btn-large btn-info">Back</a>
			<button type="submit" id="submit-1" class="btn btn-large">Confirm Order</button>
		</div>
	<?php echo form_close(); ?>
	</div>
</div>
  
 <script>
	var link = "<?php echo site_url();?>";

 function delete_cart_item(order_item_id){
	 
	 $.post(link + "shop/cart/delete_order_item/"+order_item_id,
  			function(data){
				if(data == 'true'){
					$.get(link + "shop/cart/order_cart2/<?php echo $order_id;?>", function(cart){
						$("#status").html(cart);
					});
				}
				else{
					alert(data);
				}
 		 }); 

		return false;
 }

 function update_cart(order_item_id){
	 
	 var qty = $('#quantity'+order_item_id).val();
	 
	 $.post(link + "shop/cart/update_order_item/"+order_item_id, { quantity: qty},
  			function(data){
				if(data == 'true'){
					$.get(link + "shop/cart/order_cart2/<?php echo $order_id;?>", function(cart){
						$("#status").html(cart);
					});
				}
				else{
					alert(data);
				}
 		 }); 

		return false;
 }

 function save_customization(order_item_id){
	 
	 var cust = $('#order_customization'+order_item_id).val();
	 
	 $.post(link + "admin/orders/save_customization/"+order_item_id, { customization: cust}); 

		return false;
 }
 </script>