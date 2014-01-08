<div class="widget">
	<div class="widget-head">
		<div class="pull-left"><i class="icon-key title"></i>Role Rules</div>
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
			$axns = array('data'=>'Actions','colspan'=>4);
			//-- Content Rows
			foreach($roles as $role){
				$this->table->set_heading('Resource','URL','Rule','Inherited From',$axns);
				$rules = $this->acl_model->get_role_rules($role->role_id);
				$parents = $this->acl_model->get_role_parents($role->role_id);
				foreach($rules as $rule){
					if($rule->rule == 1){
						$this->table->add_row($rule->resource_name,$rule->url,$rule->value,'-',
						anchor("admin/acl/deny_role_rule/$rule->rule_id",img(array('src'=>base_url().'/img/icons/16/cancelled.png')),array('title' => 'Deny')),
						anchor("admin/acl/delete_rule/$rule->rule_id",'<i class="icon-trash butn butn-danger"></i>',array('onClick'=>'return confirm(\'Do you really want to delete this rule?\');','title'=>'Delete')));
					}else{
						$this->table->add_row($rule->resource_name,$rule->url,$rule->value,'-',
						anchor("admin/acl/allow_role_rule/$rule->rule_id",img(array('src'=>base_url().'/img/icons/16/accept.png')),array('title' => 'Allow')),
						anchor("admin/acl/delete_rule/$rule->rule_id",'<i class="icon-trash butn butn-danger"></i>', array('onClick' =>  'return confirm(\'Do you really want to delete this rule?\');', 'title'=>'Delete')));
					}
				}
				if($parents != '0'){
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
				}
				echo("<div class='accordion-heading'>");
				echo("<a class='accordion-toggle' data-toggle='collapse' data-parent='#accordion' href='#collapse".$role->role_id."'>".$role->user_level."</a>");
				echo("</div>");
				echo("<div id='collapse".$role->role_id."' class='accordion-body collapse'>");
				echo("<div class='accordion-inner'>");
				echo $this->table->generate();
				$this->table->clear();
				echo("</div>");
				echo("</div>");
			}
			echo("</div>");
			echo("</div>");
			?>
		</div>
	</div>
</div>