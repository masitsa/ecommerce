
<?php

	if(isset($_SESSION['order_id'])){
		
		//retrieve order item features
		$items = "category_feature_value, category_feature_name, order_item_feature_id";
		$table = "order_item, order_item_feature, category_feature_value, category_feature";
		$where = "category_feature.category_feature_id = category_feature_value.category_feature_id
		AND category_feature_value.category_feature_value_id = order_item_feature.category_feature_value_id
		AND order_item_feature.order_item_id = order_item.order_item_id AND order_item.order_id = ".$_SESSION['order_id']." AND order_item.product_id = ".$product_id;
		$order = "category_feature_name, category_feature_value";
		
		$data = $this->order_model->select_entries_where($table, $where, $items, $order);
		
		if(count($data) > 0){
			?>
    		<table class="table table-condensed table-striped">
    			<tr>
        			<th>Name</th>
        			<th>Value</th>
        		</tr>
        	<?php
			
			foreach($data as $da){
				$name = $da->category_feature_name;
				$value = $da->category_feature_value;
				?>
    			<tr>
        			<td><?php echo $name;?></td>
        			<td><?php echo $value;?></td>
        		</tr>
    			<?php
			}
			?>
    		</table>
    		<?php
		}
		else{
			echo "No selected features";
		}
	}

	else{
		echo "No selected features";
	}
?>