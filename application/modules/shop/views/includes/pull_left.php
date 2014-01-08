

<!-- start: Sidebar -->
        <aside class="span3 sidebar pull-left">

            <!--<section class="widget inner shopping-cart-widget">
                <h3 class="widget-title">Shopping Cart</h3>
                <div id="cart_content3">
                            <div class="products">
<?php
$this->load->model("admin/products_model");

/*
	-----------------------------------------------------------------------------------------
	Retrieve advert
	-----------------------------------------------------------------------------------------
*/

$table = "advert"; 
$where = "ad_position_id = 2";
$items = "advert_poster";
$order = "advert_poster";
$poster = $this->order_model->select_entries_where($table, $where, $items, $order);

if(count($poster) > 0){
	foreach($poster as $pos){
		$advert_poster2 = base_url().'assets/adverts/images/'.$pos->advert_poster;
	}
}
else{
	$advert_poster2 = '';
}

/*
	-----------------------------------------------------------------------------------------
	Retrieve brands
	-----------------------------------------------------------------------------------------
*/
if(empty($current_category_id)){
	$brands = $this->products_model->get_all_active_brands();
}
			
else{
	$category_children = $this->order_model->select_entries_where("category", "(category_status = 1) AND (category_parent = ".$current_category_id.")", "category_id", "category_id");
	
	if(count($category_children) > 0){
			
		$first_child = "OR (category.category_parent IN (";
				
		$count = 0;
		$total_children = count($category_children);
				
		foreach($category_children as $child){
			$count++;
			if($total_children == $count){
				$first_child .= $child->category_id;
			}
			
			else{
				$first_child .= $child->category_id.", ";
			}
		}
			
		$first_child .= "))";
	}
	else{
		$first_child = "";
	}
			
	$category = " AND ((category.category_id = ".$current_category_id.") OR (category.category_parent = ".$current_category_id.")  ".$first_child.")";
	
	$brands = $this->products_model->get_active_category_brands($category);
}
?>
<!-- start: Categories -->
<section class="widget inner darken categories-widget" style="padding:0;">
    <h3 class="widget-title" style="padding:15px; padding-bottom:0; margin-bottom:5px;">Categories</h3>
    <div class="field" id='cssmenu'>
        
        <ul>
        <?php
        //display parents
		if(is_array($categories)){
			foreach($categories as $cat){
				$category_id = $cat->category_id;
				$category_name = $cat->category_name;
				$category_parent = $cat->category_parent;
				$category_image_name = $cat->category_image_name;
				$image = base_url()."assets/categories/images/".$category_image_name;
								
				//select the poster of the category
				$table = "advert"; 
				$where = "advert_status = 1 AND category_id = ".$category_id;
				$items = "advert_poster";
				$order = "advert_poster";
				$poster = $this->order_model->select_entries_where($table, $where, $items, $order);
				
				if(count($poster) > 0){
					foreach($poster as $pos){
						$advert_poster = '<a href="#"><img src="'.base_url().'assets/adverts/images/'.$pos->advert_poster.'"></a>';
					}
				}
				else{
					$advert_poster = '';
				}
				
				//display categories with no parent
				if(($category_parent == 0) || (empty($category_parent))){
				?>
					<li class='has-sub'>
						<a href="<?php echo $category_id;?>" class="sort_category">
							<span><?php echo $category_name;?></span>
						</a>
                        <div class="hint" style="margin-left:0px">
                        <div class="row">
                            <div class="span4" style="float:right">
                            	<?php echo $advert_poster;?>
                            </div>
                            <div class="span8">
                            	<div class="row" style="margin-left:10px;">
						<?php 
						//retrieve & display children
						$category_children = $this->order_model->select_entries_where("category", "(category_status = 1) AND (category_parent > 0) AND (category_parent = ".$category_id.")", "*", "category_parent, category_name");
								
						//display category children
						if(is_array($category_children)){
							$count = 0;
							foreach($category_children as $cat2){
										
								$category_id2 = $cat2->category_id;
								$category_name2 = $cat2->category_name;
								$category_parent2 = $cat2->category_parent;
								$category_image_name2 = $cat2->category_image_name;
								$image2 = base_url()."assets/categories/thumbs/".$category_image_name2;
								$count++;
								if(($count % 4) == 0){
									?>
                                    </div>
                        			<div class="row" style="margin-left:10px;">
                                    <?php
								}
								?>
                                <div class="span4">
                                <!--<li class='has-sub'>-->
                                    <a href="<?php echo $category_id2;?>" class="sort_category">
                                        <span><?php echo $category_name2;?></span>
                                    </a>
									<ul>
                                    <?php 
                                    //retrieve & display children
                                    $category_children2 = $this->order_model->select_entries_where("category", "(category_status = 1) AND (category_parent > 0) AND (category_parent = ".$category_id2.")", "*", "category_parent, category_name");
								
									//display category children
									if(is_array($category_children2)){
										foreach($category_children2 as $cat3){
													
											$category_id3 = $cat3->category_id;
											$category_name3 = $cat3->category_name;
											$category_parent3 = $cat3->category_parent;
											$category_image_name3 = $cat3->category_image_name;
											$image3 = base_url()."assets/categories/thumbs/".$category_image_name2;
											
											?>
											<li>
												<a href="<?php echo $category_id3;?>" class="sort_category">
													<span><?php echo $category_name3;?></span>
												</a>
                                            </li>
                                            <?php
										}
									}
									?>
									</ul>
                                
                                </div>
                                <?php
								
								if($count == count($category_children)){
									?>
                                    </div>
                                    <?php
								}
							}
						}
						?>
                        </div>
                        </div>
                        
					</li>
				<?php
				}
			}
		}
		?>
        </ul>

    </div>
</section>
<!-- end: Categories -->

<!-- start: Filter by -->
<section class="widget inner">
    <h3 class="widget-title">Filter by</h3>
    <ul class="icons check clearfix">
        <li class="on"><a href="#">Free Shipping</a> (775)</li>
        <li><a href="#">Rebates</a> (297)</li>
        <li><a href="#">In stock</a> (548)</li>
        <li><a href="#">New Release</a> (79)</li>
    </ul>
</section>
<!-- end: Filter by -->

<!-- start: Filter by Brand -->
<div id="brands_list">
    <?php
    if(isset($_SESSION['category_id'])){
        $data['brands'] = $brands;
        $data['current_category_id'] = $current_category_id;
        $this->load->view("includes/load_brands", $data);
    }
    ?>
</div>
<!-- end: Filter by Brand -->

<!-- start: Filter by price -->
<section class="widget inner price-widget">
    <h3 class="widget-title">Price</h3>
    <ul class="unstyled clearfix">
        <li><a href="#">$0 to $249.99</a> (251)</li>
        <li><a href="#">$250 to $499.99</a> (169)</li>
        <li><a href="#">$500 to $749.99</a> (195)</li>
        <li><a href="#">$750 to $999.99</a> (65)</li>
        <li><a href="#">$1000 to $1499.99</a> (40)</li>
        <li><a href="#">$1500 to $1999.99</a> (18)</li>
        <li><a href="#">$2000 to $2999.99</a> (20)</li>
        <li><a href="#">$3000 to $3999.99</a> (13)</li>
        <li><a href="#">$4000 to $4999.99</a> (2)</li>
        <li><a href="#">$5000 to $9999.99</a> (11)</li>
        <li><a href="#">$10000 and up</a> (28)</li>
    </ul>
    <div class="controls controls-row">
        <form>
            <input type="text" placeholder="from" class="span4">
            <input type="text" placeholder="to" class="span4">
            <a href="#" class="btn btn-inverse">GO</a>
        </form>
    </div>
</section>
<!-- end: Filter by price -->

<!-- start: Text Widget 
<section class="widget inner">
    <h3 class="widget-title">Ready to Purchase</h3>
    <p>Lorem ipsum dolor sit amet, consect <a href="#">etuer adipi scing</a> elit, sed diam nonummy nibh euis mod tinci dunt ut laoreet dolore magna</p>
    <a href="#" class="btn btn-large btn-danger">Purchase</a>
</section>
<!-- end: Text Widget -->

<!-- start: Text Widget -->
<section class="widget inner">
    <a href="<?php echo $advert_poster2; ?>" class="image" rel="prettyPhoto[product]"><img src="<?php echo $advert_poster2; ?>" alt="" /></a>
    <?php //echo $advert_poster2;?>
</section>
<!-- end: Text Widget -->


</aside>
<!-- end: Sidebar -->