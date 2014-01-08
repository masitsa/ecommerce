<?php
$product_offer_amount = array('name'=>'product_offer_amount','id'=>'product_offer_amount','value'=>set_value('product_offer_amount'));
$end_date = array('name'=>'end_date','id'=>'datepicker','value'=>set_value('end_date'));

$product = $this->products_model->get_product_details($product_id);
$product_name = $product->product_name;
?>
<div id="content">
	<div class="content-top">
		<h3>Add offer for <?php echo $product_name;?></h3>
	</div>
	<?php echo form_open($this->uri->uri_string(),"class='form-horizontal'"); ?>
		<div class="control-group">
			<label  class="control-label" for="% off">% off</label>
			<div class="controls">
				<?php echo form_input($product_offer_amount);?>
				<span class="help-block">
					<?php echo form_error('product_offer_amount');?>
				</span>
			</div>
		</div>
		<div class="control-group">
			<label  class="control-label" for="End Date">End Date</label>
			<div class="controls">
				<?php echo form_input($end_date);?>
				<span class="help-block">
					<?php echo form_error('end_date');?>
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


     $(function() {
			$( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
			$( "#datepicker2" ).datepicker({ dateFormat: 'yy-mm-dd' });
			
		});
    
    </script>