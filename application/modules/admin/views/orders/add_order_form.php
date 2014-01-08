<?php
$customer_name = array('name'=>'customer_name','id'=>'customer_name','value'=>set_value('customer_name'));
$delivery_date = array('name'=>'delivery_date','id'=>'datepicker','value'=>set_value('delivery_date'));

/**
* Create an array of active customers
*/
$customers = $this->products_model->get_all_active_customers();
$cust = "'";
$total_cust = count($customers);
$count = 0;

if(is_array($customers)){
	foreach($customers as $customer)
	{
		$count++;
		if($total_cust == $count){
			$cust .= $customer->username."'";
		}
		else{
			$cust .= $customer->username."',";
		}
	}
}

/**
* Retrieve order methods
*/
$order_methods = $this->products_model->get_all_active_order_methods();
$order_methods_array = array();
$order_methods_array[0] = 'None';
foreach($order_methods as $method)
	$order_methods_array[$method->order_method_id] = $method->order_method_name;
	
?>
<div id="content">
	<div class="content-top">
		<h3>Add Feature</h3>
	</div>
	<?php echo form_open($this->uri->uri_string(),"class='form-horizontal'"); ?>
		<div class="control-group">
			<label  class="control-label" for="customer_name">Customer</label>
            <div class="controls">
				<?php echo form_input($customer_name);?>
                <span class="help-block">
					<?php echo form_error('customer_name');?>
				</span>
			</div>
		</div>
		<div class="control-group">
			<label  class="control-label" for="order_method_id">Order Method</label>
			<div class="controls">
				<?php echo form_dropdown('order_method_id',$order_methods_array,$this->input->post('order_method_id'),'id = "order_method_id"');?>
				<span class="help-block">
					<?php echo form_error('order_method_id');?>
				</span>
			</div>
		</div>
		<div class="control-group">
			<label  class="control-label" for="delivery_date">Delivery Date</label>
			<div class="controls">
				<?php echo form_input($delivery_date);?>
				<span class="help-block">
					<?php echo form_error('delivery_date');?>
				</span>
			</div>
		</div>
		<div class="form-actions">
			<button type="submit" class="btn">Save</button>
		</div>
	<?php echo form_close(); ?>
</div>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/combobox.css">
    <script type="text/javascript" src="<?php echo base_url();?>js/orders/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/orders/jquery.combobox.js"></script>
	<link rel="stylesheet" href="<?php echo base_url();?>css/jquery.ui.theme.css" />
	<script src="<?php echo base_url();?>js/jquery-ui.js"></script>
    <script type="text/javascript">

    jQuery(function () {

        jQuery('#customer_name').combobox([
            <?php echo $cust;?>
        ]);
        
    });
     $(function() {
			$( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
			$( "#datepicker2" ).datepicker({ dateFormat: 'yy-mm-dd' });
		});
    
    </script>