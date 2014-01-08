<div id="content">
	<div class="content-top">
		<h3>Category Features</h3>
	</div>
	<?php
	if(is_array($categories)) {
		echo("<div class='accordion' id='accordion'>");
		echo("<div class='accordion-group'>");
		$axns = array('data'=>'Actions','colspan'=>4);
		//-- Content Rows
		foreach($categories as $role){
			$this->table->set_heading('Feature',$axns);
			$category_id = $role->category_id;
			$category_name = $role->category_name;
			
			$category_features = $this->products_model->get_all_category_features($category_id);
			
			if(is_array($category_features)){
				
				foreach($category_features as $feat){
					
					$id = $feat->id;
					$feature = $feat->feature;
					$status = $feat->status;
					
					if($status == 1){
						$act = anchor("admin/products/deactivate_category_feature/$id",img(array('src'=>base_url().'img/icons/16/deactivate.png')),array('title' => 'Deactivate'));
					}
					
					else{
						$act = anchor("admin/products/activate_category_feature/$id",img(array('src'=>base_url().'img/icons/16/activate.png')),array('title' => 'Activate'));
					}
					
					$this->table->add_row(
						$feature,
						//anchor("admin/products/add_sub_feature/$id",img(array('src'=>base_url().'img/icons/16/file.png')),array('title' => 'Add sub features')),
						anchor("admin/products/view_category_feature_value/$id",img(array('src'=>base_url().'img/icons/16/details.png')),array('title' => 'View sub features')),
						anchor("admin/products/update_category_feature/$id",img(array('src'=>base_url().'img/icons/16/form_edit.png')),array('title' => 'Edit')),
						$act
						,
						anchor("admin/products/delete_category_feature/$id",img(array('src'=>base_url().'img/icons/16/delete.gif')), array('onClick' =>  'return confirm(\'Do you really want to delete this category feature?\');', 'title' => 'Delete')));
				}
			}
					
			/*if($parents != '0'){
				$parent = json_decode($parents);
				$cp = count($parent);
				for($i=0;$i<$cp;$i++){
					$inherited[] = $this->acl_model->get_inherited_role_rules($parent[$i]);
				}
				$ci = count($inherited);
				for($i=0;$i<$ci;$i++){
					if($inherited[$i] != ''){
						foreach($inherited[$i] as $rule){
							if($rule->rule == 1){
								$this->table->add_row($rule->resource_name,$rule->url,$rule->value,$rule->user_level);
							}else{
								$this->table->add_row($rule->resource_name,$rule->url,$rule->value,$rule->user_level);
							}
						}
					}
				}
				unset($inherited);
				
			}else{
				unset($inherited);
			}*/
			echo("<div class='accordion-heading'>");
			echo("<a class='accordion-toggle' data-toggle='collapse' data-parent='#accordion' href='#collapse".$category_id."'>".$category_name."</a>");
			echo("</div>");
			echo("<div id='collapse".$category_id."' class='accordion-body collapse'>");
			echo("<div class='accordion-inner'>");
			echo $this->table->generate();
			$this->table->clear();
			echo("</div>");
			echo("</div>");
		}
		echo("</div>");
		echo("</div>");
	} 
	
	/*else {
		$this->table->add_row($rule->resource_name,$rule->url,$rule->value,$rule->user_level);
		echo $this->table->generate();
	}*/
	?>
</div>