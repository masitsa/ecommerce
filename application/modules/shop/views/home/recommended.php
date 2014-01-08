
<link rel="stylesheet" type="text/css" href="<?php echo base_url()."css/recommended/demo.css";?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()."css/recommended/elastislide.css";?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()."css/recommended/custom.css";?>"/>

<!-- Elastislide Carousel -->
<div class="row-fluid">
    <h3>Recommended</h3>
</div>
<ul id="carousel" class="elastislide-list">
	<?php
	foreach($recommended as $reco):
		$product_id = $reco->product_id;
		$product_name = $reco->product_name;
		$image = base_url()."assets/products/thumbs/".$reco->product_image_name;
		?>
        <li><a href="#"><img src="<?php echo $image;?>" alt="<?php echo $product_name;?>"/></a></li>
        <?php
	endforeach;
	?>
</ul>
<!-- End Elastislide Carousel -->