<?php
	if(is_array($categories)){
		foreach($categories as $cat){
			$category_id = $cat->category_id;
			$category_name = $cat->category_name;
			$category_parent = $cat->category_parent;
			$category_image_name = $cat->category_image_name;
			
			//display categories with no parent
			if(($category_parent == 0) || (empty($category_parent))){
				?>
                <li>
                	<p></p>
                    <a onClick="alert_here(<?php echo $category_id;?>)" href="<?php echo site_url()."shop/browse/open_products/".$category_id;?>"><?php echo $category_name;?></a>
                    <ul class="dl-submenu">
                    <?php
					
					//Retrieve first children of the parent
					if(is_array($category_children)){
						$prev_child = "1";
	
						foreach($category_children as $cat2){
		
							$category_id2 = $cat2->category_id;
							$category_name2 = $cat2->category_name;
							$category_parent2 = $cat2->category_parent;
							
							//Check to see if is a child of the parent category
							if($category_parent2 == $category_id){
								?>
                                <li>
                                	<p></p>
                                	<a  onClick="alert_here(<?php echo $category_id2;?>)"  href="<?php echo site_url()."shop/browse/open_products/".$category_id2;?>"><?php echo $category_name2;?></a>
                                    <ul class="dl-submenu">
                                <?php
								
									//Retrieve second children of the parent
									$prev_child = $category_id2;
									$count2 = 0;
			
										foreach($category_children as $cat3){
				
											$category_id3 = $cat3->category_id;
											$category_name3 = $cat3->category_name;
											$category_parent3 = $cat3->category_parent;
											
											//Check to see if is a child of the first child
											if($category_id2 == $category_parent3){
												?>
                                                <li>
                                                	<p></p>
                                                	<a onClick="alert_here(<?php echo $category_id3;?>)"  href="<?php echo site_url()."shop/browse/open_products/".$category_id3;?>">
														<?php echo $category_name3;?>
                                                    </a>
                                                </li>
                                                <?php
											}
										}
										?>
									</ul>
								</li>
								<?php
							}
						}
					}
					?>
					</ul>
				</li>
					<?php
			}
		}
	}
?>