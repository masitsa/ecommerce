<div class="widget">
	<div class="widget-head">
		<div class="pull-left"><i class="icon-cog title"></i>Plugins</div>
		<div class="widget-icons pull-right">
			<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
		</div>  
		<div class="clearfix"></div>
	</div>             
	<div class="widget-content">
		<div class="padd">
			<?php
			if(!empty($plugins)) {
				$axns = array('data'=>'Actions','colspan'=>3);
				$this->table->set_heading('Plugin Name','Plugin URL',$axns);
				foreach($plugins as $plugin) {
					if($plugin->active == 1)
						$status = anchor("admin/plugins/deactivate/$plugin->resource_id",img(array('src'=>base_url().'img/icons/16/disabled.png')),array('title'=>'Deactivate'));
					else
						$status = anchor("admin/plugins/activate/$plugin->resource_id",img(array('src'=>base_url().'img/icons/16/enabled.png')),array('title'=>'Activate'));
					if($this->plugins_model->plugin_has_pages($plugin->resource_id))
						$pages = anchor("admin/plugins/plugin_pages/$plugin->resource_id",img(array('src'=>base_url().'img/icons/16/ascii.png')),array('title'=>'Pages'));
					else
						$pages = '';
					$this->table->add_row($plugin->resource_name,$plugin->url,
						anchor("admin/plugins/uninstall_plugin/$plugin->resource_id",img(array('src'=>base_url().'img/icons/16/cancelled.png')),
							   array('onClick' =>  'return confirm(\'Do you really want to uninstall this plugin?\');', 'title' => 'Uninstall')),
						$pages,$status
					);
				}
			} else {
				$this->table->add_row('There are no plugins installed yet :-|');
			}
			echo $this->table->generate();
			?>
		</div>
	</div>
</div>