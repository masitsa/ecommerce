<?php
$carousel = $this->administration_model->select_entries_where("carousel_pictures", "pic_id > 0", "pic_name, pic_caption, pic_id", "pic_id");

?>
<!-- start: Slider -->
<section id="slider">
    <div class="row" style="margin-left:0px;">
        
        <!-- start: Categories -->
        <div class="span3">
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
                                    <a href="<?php echo site_url()."shop/browse/open_products/".$category_id;?>" class="sort_category">
                                        <span><?php echo $category_name;?></span>
                                    </a>
                                    <div class="hint" style="margin-left:0px">
                                    <div class="row">
                                        <div class="span4" style="float:right">
                                            <?php echo $advert_poster;?>
                                        </div>
                                        <div class="span8" style="margin-top:20px;">
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
                                                <a href="<?php echo site_url()."shop/browse/open_products/".$category_id2;?>" class="sort_category">
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
                                                            <a href="<?php echo site_url()."shop/browse/open_products/".$category_id3;?>">
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
            <div id="dl-menu" class="dl-menuwrapper">
                Categories <button class="dl-trigger" id="pull"></button>
                <ul class="dl-menu">
                    <?php 
                        $this->load->view("includes/pull_left2");
                    ?>
                </ul>
            </div>
        	<div id="sub_margin"></div>
            <!-- end: Categories -->
            
        </div>
        
        <!-- start: Slider -->
        <div class="span6">
            <section id="slider">
                <div id="sequence-theme">
                    <div id="sequence">
                        <div class="prev"><i class="icon-chevron-left"></i></div>
                        <div class="next"><i class="icon-chevron-right"></i></div>
                        <ul>
                        <?php
							if(count($carousel) > 0){
								foreach($carousel as $car){
									$caption = $car->pic_caption;
									$pic = base_url()."assets/carousel/original/".$car->pic_name;
						?>
                            <li>
                                <div class="text">
                                    <h3 class="subtitle"><span><?php echo $caption;?></span></h3>
                                </div>
                                <img class="image" src="<?php echo $pic;?>"/>
                            </li>
                        <?php
								}
							}
						?>
                        </ul>
                    </div>
                </div>
            </section>
        </div>
        <!-- end: Slider -->
        
        <!-- Start: Countdown -->
        <div class="span3">
        	<div class="thumbnail" style="padding-top:22%; text-align:center; background-color:#fff;">
			<?php 
            if(count($product_offer) > 0){
                
                foreach($product_offer as $prod):
                    $product_id = $prod->offer;
                    $product_name = $prod->product_name;
                    $image = base_url()."assets/products/images/".$prod->product_image_name;
                endforeach;
                ?>
            
                <a href="<?php echo site_url()."shop/browse/open_products/".$product_id?>" class="image" style="height:150px; background-color:#FFF;">
                    <img src="<?php echo $image;?>" alt="<?php echo $product_name; ?>">
                    <span class="frame-overlay"></span>
                    <h4 class="title"> <?php echo $product_name;?></h4>
                </a>
        	<div id="countdown"></div>

			<p id="note"></p>
            <?php
		}
		?>
        </div>
        <!-- end: Countdown -->
    </div>
</section>
<!-- end: Slider -->