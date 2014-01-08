<!-- start: Sidebar -->
<aside class="span3 sidebar pull-left">
<?php
$this->load->model("admin/products_model");
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
                                    <div class="row" style="margin-left:0px;">
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
                                        <div class="row" style="margin-left:0px;">
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
               <!--<li class='active'><a href='index.html'><span>Home</span></a></li>
               <li class='has-sub'><a href='#'><span>Products</span></a>
                  <ul>
                     <li class='has-sub'><a href='#'><span>Product 1</span></a>
                        <ul>
                           <li><a href='#'><span>Sub Item</span></a></li>
                           <li class='last'><a href='#'><span>Sub Item</span></a></li>
                        </ul>
                     </li>
                     <li class='has-sub'><a href='#'><span>Product 2</span></a>
                        <ul>
                           <li><a href='#'><span>Sub Item</span></a></li>
                           <li class='last'><a href='#'><span>Sub Item</span></a></li>
                        </ul>
                     </li>
                  </ul>
               </li>
               <li><a href='#'><span>About</span></a></li>
               <li class='last'><a href='#'><span>Contact</span></a></li>-->
            </ul>
    
        </div>
    </section>
    <!-- end: Categories -->

</aside>
<!-- end: Sidebar -->
        