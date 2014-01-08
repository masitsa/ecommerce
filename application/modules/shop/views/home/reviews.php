<div class="row-fluid white_background">
    <div class="span12"><h3 class="saying">What our Customers are saying</h3></div>
    
    <div class="row-fluid review_product">
        
        <?php
		$total = 4;
		$total_count = 0;
			foreach($reviews as $rev):
				$total_count++;
				$image = base_url()."assets/products/images/".$rev->product_image_name;
				$product_name = $rev->product_name;
				$review_date = $rev->review_date;
				$review_reviewer = $rev->review_reviewer;
				$review_rating = $rev->review_rating;
				$review_name = $rev->review_name;
				//$review_date = date("D M Y", strtotime($rev->review_date, "Y-m-d"));
			?>
			<div class="row-fluid">
				<div class="media" style="overflow:inherit;">
					<div class="span3">
					<a href="#" class="pull-left"><img class="media-object" src="<?php echo $image;?>" alt="<?php echo $product_name;?>" width="260px"/></a>
					</div>
					<div class="span6">
					<div class="media-body">
						<div class="inner" style="padding:0px;">
							<h4 class="product_name"><?php echo $product_name;?></h4>
							<h4 class="media-heading review_date">Posted: <?php echo $review_date;?></h4>
							<h4 class="media-heading review_date">Reviewer: <?php echo $review_reviewer;?></h4>
							<span class="rated">
								<?php 
									$count = 0;
									$count2 = 0;
									
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
								<p><?php echo $review_name;?></p>
							</div>
						</div>
					</div>
					</div>
				</div>
			</div>
			<?php
			if($total_count == $total){
				break;
			}
			endforeach;
			?>
    </div>
</div>