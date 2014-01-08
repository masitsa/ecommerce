<?php
$this->load->helper('html');
echo link_tag('css/custom-theme/jqueryui.css');
?>

<fieldset>
<legend>Product Image</legend>
<div>
	<a href="<?php echo site_url('shop/browse/')?>">Products</a>
</div>
<table align="center" border="0">
<?php
	if(count($products) > 0){//if there are products in the database display them
		$count = 0;//the product number
		foreach ($products as $cat){//loop and display all the selected products
			$count ++;
			//get the items from the array
			$product_id = $cat->product_id;
			$image = base_url()."img/gallery0.php?product_id=".$product_id;
			
			//display the image
			echo '<img src="'.$image.'" width="500"/>';
		}
	}
?>
</table>
