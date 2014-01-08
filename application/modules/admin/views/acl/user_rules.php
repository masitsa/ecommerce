<div class="widget">
	<div class="widget-head">
		<div class="pull-left"><i class="icon-key title"></i>User Rules</div>
		<div class="widget-icons pull-right">
			<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
		</div>  
		<div class="clearfix"></div>
	</div>             
	<div class="widget-content">
		<div class="padd">
			<?php
			if(!empty($users)){
				echo("<div class='accordion' id='accordion'>");
				echo("<div class='accordion-group'>");
				$axns = array('data'=>'Actions','colspans'=>3);
				$this->table->set_heading('Resource','URL','Rule',$axns);
				//-- Content Rows
				foreach($users as $user){
					$rules = $this->acl_model->get_user_rules($user->user_id);
					foreach($rules as $rule){
						if($rule->rule == 1){
							$this->table->add_row($rule->resource_name,$rule->url,$rule->value,
							anchor("admin/acl/deny_user_rule/$rule->rule_id",img(array('src'=>base_url().'/img/icons/16/cancelled.png')),array('title' => 'Deny')),
							anchor("admin/acl/delete_rule/$rule->rule_id",'<i class="icon-trash butn butn-danger"></i>',array('onClick'=>'return confirm(\'Do you really want to delete this rule?\');', 'title'=>'Delete')));
						}else{
							$this->table->add_row($rule->resource_name,$rule->url,$rule->value,
							anchor("admin/acl/allow_user_rule/$rule->rule_id",img(array('src'=>base_url().'img/icons/16/accept.png')),array('title' => 'Allow')),
							anchor("admin/acl/delete_rule/$rule->rule_id",'<i class="icon-trash butn butn-danger"></i>',array('onClick'=>'return confirm(\'Do you really want to delete this rule?\');','title'=>'Delete')));
						}
					}
					echo("<h3 align='left'><a href='#'>".$user->fullname."</a></h3>");
					echo("<p>".$this->table->generate()."</p>");
					$this->table->clear();
					echo("<div class='accordion-heading'>");
					echo("<a class='accordion-toggle' data-toggle='collapse' data-parent='#accordion' href='#collapse".$user->user_id."'>".$user->fullname."</a>");
					echo("</div>");
					echo("<div id='collapse".$user->user_id."' class='accordion-body collapse'>");
					echo("<div class='accordion-inner'>");
					echo $this->table->generate();
					$this->table->clear();
					echo("</div>");
					echo("</div>");
				}
			}else{
				$this->table->add_row("Nothing to see here :-|");
				echo $this->table->generate();
			}
			echo("</div>");
			echo("</div>");
			?>
		</div>
	</div>
</div>