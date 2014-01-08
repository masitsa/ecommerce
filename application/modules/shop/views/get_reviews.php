<?php

	if(count($products) > 0){//if the product exists display it
		
		foreach ($products as $prod){//loop and display all the selected products
			
			//get the items from the array
			$product_id = $prod->product_id;
			$product_name = $prod->product_name;
		}
	}
?>
<?php
					$no_reviews = count($reviews);
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