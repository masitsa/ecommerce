<?php
if(isset($_SESSION['brand_id'])){
	
	$total_brands = count($_SESSION['brand_id']);
}
else{
	$total_brands = 0;
}

if(count($brands) > 0){
?>
<section class="widget inner price-widget">
    <h3 class="widget-title">Brand</h3>
    <ul class="unstyled clearfix">
            <?php
            foreach ($brands as $cust){
                $brand_name = $cust->brand_name;
                $brand_id = $cust->brand_id;
                $total_products = $this->products_model->count_all_brands($brand_id);
                
                if($total_brands > 0){

                    for($r = 0; $r < $total_brands; $r++){
                        
                        if(!empty($_SESSION['brand_id'][$r])){
                            if($_SESSION['brand_id'][$r] == $brand_id){
                                //echo $_SESSION['brand_id'][$r]."".$brand_id;
                                $checked = "checked";
                                break;
                            }
                        }
                        else{
                            $checked = "";
                        }
                    }
                }
                else{
                    $checked = "";
                }
                echo '
                <li>
                    <input type="checkbox" id="checkbox-1-'.$brand_id.'" value="'.$brand_id.'" class="regular-checkbox" '.$checked.'/>
                    <label for="checkbox-1-'.$brand_id.'"></label>
                    '.$brand_name.'
                    ('.$total_products.')
                </li>';
            }
            ?>
            
    </ul>
</section>
            <?php
        }
    ?>
<input type="hidden" id="current_category_id" value="<?php echo $current_category_id;?>"/>