<div id="content">
	<div class="content-top">
		<h3>Features</h3>
	</div>
	<?php 
		echo("<div class='accordion' id='accordion'>");
			echo("<div class='accordion-group'>");
				//-- Content Rows
				$axns = array('data'=>'Actions','colspan'=>3);
				foreach($categories as $category){
					$this->table->set_heading('Feature','Units',$axns);
			
					if($features = $this->products_model->get_category_features($category->category_id)) {
						foreach($features as $feature) {
							$this->table->add_row($feature->feature_name,$feature->feature_units,
								anchor("admin/products/update_feature/$feature->feature_id",img(array('src'=>base_url().'/img/icons/16/form_edit.png')),array('title' => 'Edit'))
							);
						}
					}
					echo("<div class='accordion-heading'>");
					echo("<a class='accordion-toggle' data-toggle='collapse' data-parent='#accordion' href='#collapse".$category->category_id."'>".$category->category_name."</a>");
					echo("</div>");
					echo("<div id='collapse".$category->category_id."' class='accordion-body collapse'>");
					echo("<div class='accordion-inner'>");
					echo $this->table->generate();
					$this->table->clear();
					echo("</div>");
					echo("</div>");
				}
				echo ("<div id='pagination'>".$this->pagination->create_links()."</div>");
			echo("</div>");
		echo("</div>");
	?>
</div>