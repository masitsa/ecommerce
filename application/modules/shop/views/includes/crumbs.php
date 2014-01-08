<?php

	/*
		-----------------------------------------------------------------------------------------
		Display the all product crumb
		-----------------------------------------------------------------------------------------
	*/
	if(isset($_SESSION['crumb1'])){?>
            <a href="<?php echo base_url()."shop/browse/open_products/0";?>">
				<?php echo $_SESSION['crumb1']?>
            </a>
            <i class="icon-angle-right"></i>
            <?php 
	}
	
	/*
		-----------------------------------------------------------------------------------------
		If current category exists display breadcrumbs
		-----------------------------------------------------------------------------------------
	*/
	if(isset($_SESSION['category_id'])){
	
		/*
			-----------------------------------------------------------------------------------------
			Check if category has a parent
			-----------------------------------------------------------------------------------------
		*/
		$parent = $this->order_model->select_entries_where("category", "category_id = ".$_SESSION['category_id'], "category_parent, category_name, category_id", "category_parent");
		
		foreach ($parent as $par){
			
			$id = $par->category_id;
			$name = $par->category_name;
			$parent_id = $par->category_parent;
			
			if($parent_id != 0){//if parent exists
				
				$parent_details = $this->order_model->select_entries_where("category", "category_id = ".$parent_id, "category_parent, category_name, category_id", "category_parent");
				
				foreach ($parent_details as $par2){
			
					$id2 = $par2->category_id;
					$name2 = $par2->category_name;
					$parent_id2 = $par2->category_parent;
					
					if($parent_id2 != 0){//if parent exists
						
						$parent_details2 = $this->order_model->select_entries_where("category", "category_id = ".$parent_id2, "category_parent, category_name, category_id", "category_parent");
						
						foreach ($parent_details2 as $par3){
					
							$id3 = $par3->category_id;
							$name3 = $par3->category_name;
							
							?>
							<a href="<?php echo base_url()."shop/browse/remove_brand/".$id3;?>">
								<?php echo $name3;?>
							</a>
                        	<i class="icon-angle-right"></i>
							<a href="<?php echo base_url()."shop/browse/remove_brand/".$id2;?>">
								<?php echo $name2;?>
							</a>
                        	<i class="icon-angle-right"></i>
							<a href="<?php echo base_url()."shop/browse/remove_brand/".$id;?>">
								<?php echo $name;?>
							</a>
							<?php
						}
					}
					
					else{
							
						?>
						<a href="<?php echo base_url()."shop/browse/remove_brand/".$id2;?>">
							<?php echo $name2;?>
						</a>
                        <i class="icon-angle-right"></i>
						<a href="<?php echo base_url()."shop/browse/remove_brand/".$id;?>">
							<?php echo $name;?>
						</a>
						<?php
					}
				}
			}
			
			else{
							
				?>
				<a href="<?php echo base_url()."shop/browse/remove_brand/".$id;?>">
					<?php echo $name;?>
				</a>
				<?php
			}
		}
	}
?>