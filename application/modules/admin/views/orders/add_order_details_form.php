<?php

$details = $this->orders_model->get_order_details($order_id);
$username = $details->first_name." ".$details->last_name;

$data['order_items'] = $this->orders_model->get_order_items($order_id);
$data['order_id'] = $order_id;

?>
<p style="font-weight: bolder; font-size: 18px;">Customer: <?php echo $username;?></p>
<div class="row">
	<div class="span5" id="content">
		<div class="content-top">
			<h3>Select Products</h3>
		</div>
		<?php echo form_open("shop/cart/add_bulk_cart_item/".$order_id, array("id" => "my-form")); ?>
		<?php echo $this->table->generate(); ?>
        
		<?php echo form_close(); ?>
		<div id="pagination"><?php echo $this->pagination->create_links(); ?></div>
	</div>

	<div class="span3" id="content">
		<div class="content-top">
			<h3>Selected Items</h3>
		</div>
    	<div id="status">
    	<?php 
        	$this->load->view("orders/items", $data);
		?>
    	</div>
    	<div id="select_error"></div>
	</div>
</div>
  
 <script>
	var link = "<?php echo site_url();?>";

	function save_order_item(id){
		
		var qty = 1;
		$.post(link + "shop/cart/add_order_item/<?php echo $order_id;?>", { product_id: id, quantity: qty},
			
			function(data){
					
				if(data == 'true'){
						
					$.get(link + "shop/cart/order_cart/<?php echo $order_id;?>", function(cart){
						$("#status").html(cart);
					});
				}
				else{
					alert(data);
				}
    		
 		 	}
		);
	}

 function delete_cart_item(order_item_id){
	 
	 $.post(link + "shop/cart/delete_order_item/"+order_item_id,
  			function(data){
				if(data == 'true'){
					$.get(link + "shop/cart/order_cart/<?php echo $order_id;?>", function(cart){
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
					$.get(link + "shop/cart/order_cart/<?php echo $order_id;?>", function(cart){
						$("#status").html(cart);
					});
				}
				else{
					alert(data);
				}
 		 }); 

		return false;
 }
 </script>