<?php $this->load->model("shop/order_model");
$current_category_id = 2;
$category_children = $this->order_model->select_entries_where("category", "(category_status = 1) AND (category_parent = ".$current_category_id.")", "category_id", "category_id");
			
	$first_child = "(";
	
	if(is_array($category_children)){
				
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
	}
			
	$first_child .= ")";
			
	$category = " AND ((category.category_id = ".$current_category_id.") OR (category.category_parent = ".$current_category_id.") OR (category.category_parent IN ".$first_child."))";
	
	$brands = $this->products_model->get_active_category_brands($category);
    ?>