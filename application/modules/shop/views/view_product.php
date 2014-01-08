
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()."css/menu.css";?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()."css/category_menu.css";?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()."css/bootstrap.css";?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()."css/jasny-bootstrap.css";?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()."css/style.css";?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()."css/prettyPhoto.css";?>"/>
<?php
	
	/*
		-----------------------------------------------------------------------------------------
		Retrieve shopping cart total amount and number of items
		-----------------------------------------------------------------------------------------
	*/
	$total_cost = $this->cart->total();
	$total_cart_items = $this->cart->total_items();
	
	if(count($products) > 0){//if the product exists display it
		
		foreach ($products as $prod){//loop and display all the selected products
			
			//get the items from the array
			$product_id = $prod->product_id;
			$product_code = $prod->product_code;
			$product_name = $prod->product_name;
			$product_selling_price = $prod->product_selling_price;
			$product_buying_price = $prod->product_buying_price;
			$product_balance = $prod->product_balance;
			$category_name = $prod->category_name;
			$category_id = $prod->category_id;
			$product_description = $prod->product_description;
			$product_image_name = $prod->product_image_name;
			$image = base_url()."assets/products/images/".$product_image_name;
			$image2 = base_url()."assets/products/thumbs/".$product_image_name;
		}
	}
	/*
		-----------------------------------------------------------------------------------------
		Retrieve current category features
		-----------------------------------------------------------------------------------------
	*/
	$category_features = $this->products_model->get_all_category_features($category_id);
	
	/*
		-----------------------------------------------------------------------------------------
		Count total Number of reviews
		-----------------------------------------------------------------------------------------
	*/
	$no_reviews = count($reviews);
?>

            <div class="row-fluid">
                <!-- start: Product image -->
                <div class="span6 product-images">

                    <div class="thumbnail big">
                        <a href="<?php echo $image; ?>" class="image" rel="prettyPhoto[product]"><img src="<?php echo $image; ?>" alt="" />
                            <span class="frame-overlay"></span>
                        </a>
                    </div>

                    <div class="row-fluid small">
                        <?php
         
		 					if(is_array($product_images)){
			 					foreach($product_images as $prod){
				 					$id = $prod->product_image_id;
				 					$image = base_url()."assets/products/gallery/".$prod->product_image_name;
				 					$thumb = base_url()."assets/products/gallery/".$prod->product_image_thumb;
				 		?>
                 		<div class="span3 thumbnail">
                            <a href="<?php echo $image; ?>" class="image" rel="prettyPhoto[product]"><img src="<?php echo $thumb; ?>" alt="" />
                                <span class="frame-overlay"></span>
                            </a>
                        </div>
                 		
					<?php
			 					}
		 					}
		 
					 ?>
                    </div>

                </div>
                <!-- end: Product image -->
                <!-- start: Product title -->
                <div class="span6 product-info">

                    <div class="inner product-title">
                        <div class="row-fluid">
                            <div class="span8 title"><h1><?php echo $product_name; ?></h1></div>
                            <div class="span4 price">KES <?php echo $this->cart->format_number($product_selling_price); ?></div>
                        </div>
                    </div>
					<ul class="thumbnails">
					<?php 
						$attributes = array("class" => "form-inline");
						echo form_open('shop/cart/add_cart_item3'); 
					?>
                    <div class="inner nobottom product-cart">
                            <label>Qty:</label>
                            <input type="text" name="quantity" value="1" style="margin:0px;">
					<?php echo form_hidden('product_id', $product_id); ?>
                    <?php
                    	$attributes = array("name" => "add", "value" => "Add",  "class" => "btn btn-inverse");
						echo form_submit($attributes); 
					?>
                    </div>
                    <?php echo form_close(); ?>
                    
                    <div class="inner nobottom">
                    	<input type="hidden" id="current_product" value="<?php echo $product_id?>">
                    	<?php 
							if(is_array($category_features)){
				
								foreach($category_features as $feat){
					
									$id = $feat->id;
									$feature = $feat->feature;
									?>
                                    <div class="row">
                                    <div class="span12 category_features">
                                    <p><?php echo $feature;?></p>
                                    </div>
                                    </div>
                                    
                                    <div class="row">
                                    <div class="span12 category_features">
                                    <ul>
                                    <?php
									
									//retrieve category feature values
									$details = $this->products_model->get_category_feature_values($id);
									
									if(count($details) > 0) { 
										foreach($details as $feat) {
											$cat_id = $feat->category_feature_value_id;
											$name = $feat->category_feature_value;
											$image = $feat->category_feature_value_image;
											
											//if there is an image
											if(!empty($image)){
												?>
                                                <li>
                                                <a href="<?php echo $cat_id?>" class="set_category_feature">
                                                <img src="<?php echo base_url().'features/thumbs/'.$feat->category_feature_value_image; ?>" alt="<?php echo $feat->category_feature_value; ?>" width="20px"/>
                                                </a>
                                                </li>
                                                <?php
											}
											
											else{
												?>
                                                <li>
                                                <a href="<?php echo $cat_id?>" class="set_category_feature">
												<?php echo $feat->category_feature_value; ?>
                                                </a>
                                                </li>
                                                <?php
											}
										}
									}
									
									?></ul>
                                    </div>
                                    </div><?php
								}
							}
						?>
                        
                        <div class="row">
                        	<div class="span12 category_features cat_border" style="margin-left:6%;">
                        		<p>Selected Features</p>
                                <div id="selected_features" class="box-2">
                                	<?php 
										$data['product_id'] = $product_id;
										$this->load->view("category_feature_value", $data);
									?>
                                </div>
                        	</div>
                        </div>
                    </div>
                    
                    </ul>
                    <div class="inner notop darken product-ratings">
                        <div class="row-fluid">
                            <div class="review-count span6">
                                <a href="#"><i class="icon-comment"></i> <?php echo $no_reviews;?> reviews</a>
                            </div>
                            <div class="review-stars span6">
                                <span class="rated">
                                    <?php 
									$count = 0;
									$count2 = 0;
                                	
									while($count < $review_average){
										$count++;
										$count2++;
								?>
                                <span class="star solid"></span>
                                <?php }?>
                            	<?php
                                	while($count2 < 5){
										$count2++;
								?>
                                <span class="star"></span>
                                <?php }?>
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- end: Product title -->
            </div>

            <div class="row-fluid">

                <div class="row-fluid product-tabs">
                    <section class="widget">

                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#description" data-toggle="tab">Description</a></li>
                            <li><a href="#additional" data-toggle="tab">Features</a></li>
                        </ul>

                        <div class="tab-content">

                            <div class="tab-pane inner notop active" id="description">
                                <h4>General Description</h4>
                                <p><?php echo $product_description?></p>
                            </div>

                            <div class="tab-pane inner notop" id="additional">
								<?php
									if(is_array($product_features)){
										foreach ($product_features as $pf){//loop through the selected product features
											$feat_id = $pf->feature_id;
											$product_feature_name = $pf->feature_value;
											$feature_name = $pf->feature_name;
											$units = $pf->feature_units;
											echo 
											"
                                				<h4>".$feature_name."</h4>
                                				<p>".$product_feature_name." ".$units."</p>
											"
											;
										}
									}
								?>
                            </div>

                        </div>
                    </section>
                </div>
            </div>

            <hr>

            <!-- start: Reviews -->
            <div class="row-fluid reviews">
			<div id="reviews">
                <?php
					
				?>
                
                <div class="row-fluid reviews-title">
                    <div class="span8 title"><h3><?php echo $no_reviews;?> reviews for <?php echo $product_name; ?></h3></div>
                    <div class="span4 stars">
                        <span class="rated">
								<?php 
									$count = 0;
									$count2 = 0;
                                	
									while($count < $review_average){
										$count++;
										$count2++;
								?>
                                <span class="star solid"></span>
                                <?php }?>
                            	<?php
                                	while($count2 < 5){
										$count2++;
								?>
                                <span class="star"></span>
                                <?php }?>
                        </span>
                    </div>
                </div>
				
                <?php if($no_reviews > 0){ 
				
				foreach($reviews as $rev){
					
					$review = $rev->review_name;
					$reviewer = $rev->review_reviewer;
					$review_date = $rev->review_date;
					$review_rating = $rev->review_rating;
					$count = 0;
					$count2 = 0;
				?>
                
                <div class="media">
                    <a href="#" class="pull-left"><img class="media-object" src="<?php echo base_url();?>img/avatar.png" alt=""/></a>
                    <div class="media-body">
                        <div class="inner">
                            <h4 class="media-heading"><?php echo $reviewer;?> - <?php echo $review_date;?>:</h4>
                            <span class="rated">
                            	<?php
                                	while($count < $review_rating){
										$count++;
										$count2++;
								?>
                                <span class="star solid"></span>
                                <?php }?>
                            	<?php
                                	while($count2 < 5){
										$count2++;
								?>
                                <span class="star"></span>
                                <?php }?>
                            </span>
                            <div class="description">
                                <p><?php echo $review;?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php } }?>
			</div>
                <div class="row-fluid add-review">
                    <h3>Add Review</h3>
                    <?php 
						echo form_open("shop/browse/add_review");
						echo form_hidden("ajax", "4");
						echo form_hidden("product_id", $product_id);
					?>
                        <div class="controls controls-row">
                            <input class="span4" type="text" placeholder="Name" name="reviewer"/>
                            <input class="span4" type="text" placeholder="Email" name="email"/>
                            
                            
                            <select name="rating" class="span4">
                            	<option selected value="1">--------Rating--------</option>
                            	<option value="1">1</option>
                            	<option value="2">2</option>
                            	<option value="3">3</option>
                            	<option value="4">4</option>
                            	<option value="5">5</option>
                            </select>
                            <div id="star"></div>
                            <!--<div class="span4 stars">
                                <span class="rating">
                                    <span class="star"></span>
                                    <span class="star"></span>
                                    <span class="star"></span>
                                    <span class="star"></span>
                                    <span class="star"></span>
                                </span>
                            </div>-->
                        </div>

                        <textarea class="span12" cols="30" rows="10" placeholder="Your Review" name="review"></textarea>
                        <button type="submit" class="btn btn-inverse btn-submit-review">Submit Review</button>
                    <?php echo form_close();?>
                </div>

            </div>
            <!-- end: Reviews -->

            <hr>

            <div class="row-fluid">
                <h3>Related Products</h3>
            </div>

            <!-- start: products listing -->
            <div class="row-fluid shop-products">
                <ul class="thumbnails">
                    <?php $count = 0;
				if(is_array($related_products)){
					foreach ($related_products as $prod)://loop and display all the selected products
						$count++;
						if(($count == 1) || ((count($products)) % 3 == 0)){
							$position = "first";
						}
						else{
							$position = "";
						}
						//get the items from the array
						$product_id = $prod->product_id;
						$product_name = $prod->product_name;
						$product_selling_price = $prod->product_selling_price;
						$product_balance = $prod->product_balance;
						$product_description = $prod->product_description;
						$product_image_name = $prod->product_image_name;
						$image = base_url()."assets/products/images/".$product_image_name;
	
				?>  
					<?php 
						$attributes = array('style' => 'margin:0 0 0;');
						echo form_open('shop/cart/add_cart_item3', $attributes); 
					?>
    				<li class="item span4 first">
                        <div class="thumbnail" style="height:220px;">
                        	<a href="<?php echo $product_id;?>" class="view_product" class="image" style="background-color:#FFF;">
                                <img src="<?php echo $image; ?>"/>  
                                <span class="frame-overlay"></span>
                                <span class="price">KES <?php echo $this->cart->format_number($product_selling_price); ?></span>
                            </a>
                            <div class="inner notop nobottom">
                                <h4 class="title"><?php echo $product_name; ?></h4>
                            </div>
                        </div>
                        <div class="inner darken notop">
                			<?php //echo form_input('quantity', '1', 'maxlength="2"', "style=width:2px;"); ?>  
                			<?php echo form_hidden('quantity', '1');echo form_hidden('product_id', $product_id); ?> 	
                            <?php 
								$attributes = array("name" => "add", "value" => "Add", "style" => "margin:0 0 0;", "class" => "btn btn-add-to-cart");
								echo form_submit($attributes); 
							?>  
                            <!--<a href="#" class="btn btn-add-to-cart">Add<i class="icon-shopping-cart"></i></a>-->
                        </div>
                    </li>
                    <?php echo form_close(); ?>
    				<?php endforeach;
				}
                	else{
						echo "There are no products by that name.";
					}
				?>
                </ul>
            </div>
            <!-- end: products listing -->

<script type="text/javascript" src="<?php echo base_url()."js/cart/jquery.prettyPhoto.js";?>"></script>
<script type="text/javascript" src="<?php echo base_url()."js/cart/jquery.elastislide.js";?>"></script>
<script type="text/javascript" src="<?php echo base_url()."js/cart/jquery.tweet.js";?>"></script>
<script type="text/javascript" src="<?php echo base_url()."js/cart/smoothscroll.js";?>"></script>
<script type="text/javascript" src="<?php echo base_url()."js/cart/jquery.ui.totop.js";?>"></script>
<script type="text/javascript" src="<?php echo base_url()."js/cart/ajax-mail.js";?>"></script>
<script type="text/javascript" src="<?php echo base_url()."js/cart/main.js";?>"></script>