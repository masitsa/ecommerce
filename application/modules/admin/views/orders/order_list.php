<?php
	$customer = array('style'=>'width:160px;','name'=>'customer_name','id'=>'customer_name','value'=>set_value('customer_name'));
	$order_date = array('style'=>'width:160px;','name'=>'order_date','id'=>'datepicker','value'=>set_value('order_date'));
	$delivery_date = array('style'=>'width:160px;','name'=>'delivery_date','id'=>'datepicker2','value'=>set_value('delivery_date'));
	$product_name = array('style'=>'width:160px;','name'=>'prod','id'=>'product_name','value'=>set_value('product_name'));
/**
* Create an array of products
*/
$products = $this->products_model->get_all_active_products();
$cust = "'";
$total_cust = count($products);
$count = 0;

if(is_array($products)){
	foreach($products as $product)
	{
		$count++;
		if($total_cust == $count){
			$cust .= $product->product_name."'";
		}
		else{
			$cust .= $product->product_name."',";
		}
	}
}
?>
<div id="content">
	<div class="content-top">
		<h3>Orders</h3>
	</div>
    <?php echo form_open('admin/orders/search_order/'.$order_type, "class='form-horizontal'"); ?>
    <div class="row-fluid">
    	<div class="span4">
        	<label  class="control-label">Customer</label>
			<?php echo form_input($customer);?>
        </div>
        
        
    	<div class="span4">
			<label  class="control-label">Order Date</label>
			<?php echo form_input($order_date);?>
        </div>
        
        
    	<div class="span4">
			<label  class="control-label">Delivery Date</label>
			<?php echo form_input($delivery_date);?>
			<?php echo form_hidden($product_name);?>
        </div>
        
        
    	<!--<div class="span3">
			<label  class="control-label">Product</label>
			<?php echo form_input($product_name);?>
        </div>-->
    </div>
		<div class="form-actions" style="padding-left:40%;">
			<button type="submit" class="btn btn-large">Search</button>
		</div>
	<?php echo form_close(); ?>
	<?php echo $this->table->generate(); ?>
	<div id="pagination"><?php echo $this->pagination->create_links(); ?></div>
</div>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/combobox.css">
    <script type="text/javascript" src="<?php echo base_url();?>js/orders/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/orders/jquery.combobox.js"></script>
	<link rel="stylesheet" href="<?php echo base_url();?>css/jquery.ui.theme.css" />
	<script src="<?php echo base_url();?>js/jquery-ui.js"></script>
    <script type="text/javascript">


     $(function() {
			$( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
			$( "#datepicker2" ).datepicker({ dateFormat: 'yy-mm-dd' });
			
		});
    
    </script>