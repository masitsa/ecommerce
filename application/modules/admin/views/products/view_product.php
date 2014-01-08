<div id="content">
	<div class="content-top">
		<h3>Product Details</h3>
	</div>
	<table class="table table-striped table-hover">
		<tr>
			<td>Product Name</td>
			<td><?php echo $details->product_name; ?></td>
		</tr>
		<tr>
			<td>Product Code</td>
			<td><?php echo $details->product_code; ?></td>
		</tr>
		<tr>
			<td>Description</td>
			<td><?php echo $details->product_description; ?></td>
		</tr>
		<tr>
			<td>Selling Price</td>
			<td><?php echo $details->product_selling_price; ?></td>
		</tr>
		<tr>
			<td>Buying Price</td>
			<td><?php echo $details->product_buying_price; ?></td>
		</tr>
		<tr>
			<td>Product Image</td>
			<td><img src="<?php echo base_url().'products/thumbs/'.$details->product_image_name; ?>" alt="<?php echo $details->product_name; ?>"/></td>
		</tr>
	</table>
	<h4>Features</h4>
	<table class="table table-striped table-hover">
    	<?php if(count($features) > 0) { ?>
		<?php foreach($features as $feature) { ?>
		<tr>
			<td><?php echo $feature->feature_name; ?></td>
			<td><?php echo $feature->feature_value.' '.$feature->feature_units; ?></td>
		</tr>
		<?php } 
		}
		else{
			echo "This products has no features :-|";
		}
		?>
	</table>
	<?php //var_dump($details); ?>
	<?php // var_dump($features); ?>
</div>