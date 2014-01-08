<div class="widget">
	<div class="widget-head">
		<div class="pull-left"><i class="icon-key title"></i>Resources</div>
		<div class="widget-icons pull-right">
			<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
		</div>  
		<div class="clearfix"></div>
	</div>             
	<div class="widget-content">
		<div class="padd">
			<?php 
			echo("<div class='accordion' id='accordion'>");
			echo("<div class='accordion-group'>");
			//-- Content Rows
			$axns = array('data'=>'Actions','colspan'=>3);
			foreach($parents as $parent){
				$this->table->set_heading('Resource Name','Resource Type','URL',$axns);
				$name = $parent->resource_name;
				$children = $this->acl_model->get_resource_children($parent->resource_id);
				if($parent->active == 1){
					$this->table->add_row($parent->resource_name,$parent->type_name,$parent->url,
						anchor("admin/acl/edit_resource/$parent->resource_id",'<i class="icon-edit butn butn-success"></i>',array('title'=>'Edit')),
						anchor("admin/acl/deactivate_resource/$parent->resource_id",'<i class="icon-lock butn butn-info"></i>',array('title'=>'Deactivate')));
				}else{
					$this->table->add_row($parent->resource_name,$parent->type_name,$parent->url,
						anchor("admin/acl/edit_resource/$parent->resource_id",'<i class="icon-edit butn butn-success"></i>',array('title'=>'Edit')),
						anchor("admin/acl/activate_resource/$parent->resource_id",'<i class="icon-unlock butn butn-info"></i>',array('title'=>'Activate')));
				}
				if($this->acl_model->has_children($parent->resource_id)){
					foreach($children as $child){
						if($child->active == 1){
							$this->table->add_row($child->resource_name,$child->type_name,$child->url,
								anchor("admin/acl/edit_resource/$child->resource_id",'<i class="icon-edit butn butn-success"></i>',array('title'=>'Edit')),
								anchor("admin/acl/deactivate_resource/$child->resource_id",'<i class="icon-lock butn butn-info"></i>',array('title'=>'Deactivate')));
						}else{
							$this->table->add_row($child->resource_name,$child->type_name,$child->url,
								anchor("admin/acl/edit_resource/$child->resource_id",'<i class="icon-edit butn butn-success"></i>',array('title'=>'Edit')),
								anchor("admin/acl/activate_resource/$child->resource_id",'<i class="icon-unlock butn butn-info"></i>',array('title'=>'Activate')));
						}
					}
				}
				echo("<div class='accordion-heading'>");
				echo("<a class='accordion-toggle' data-toggle='collapse' data-parent='#accordion' href='#collapse".$parent->resource_id."'>".$name."</a>");
				echo("</div>");
				echo("<div id='collapse".$parent->resource_id."' class='accordion-body collapse'>");
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
	</div>
</div>