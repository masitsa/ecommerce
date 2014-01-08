<!-- Sidebar -->
<div class="sidebar">
	<div class="sidebar-dropdown"><a href="#">Navigation</a></div>
			<ul id="nav">
				<?php
				$data = modules::run('admin/acl/show_fancy_main_menu');
				$url = $data['url'];
				foreach($data['main_menu'] as $paths) {
					$path = explode("/",$paths->url);
					$modules[] = $path[1];
				}
				foreach($data['main_menu'] as $main_menu_item)
				{
					$menu_id = $main_menu_item->resource_id;
					$item = explode("/",$main_menu_item->url);
					if($this->acl_model->has_access($main_menu_item->url)) {
						if($url[1] == $item[1]) {
							echo("<li><a class='open subdrop' href='#'>".$main_menu_item->resource_name."<span class='pull-right'><i class='icon-chevron-right'></i></span></a>");
							echo("<ul style='display:block;'>");
							echo("<li>".anchor($main_menu_item->url,$main_menu_item->resource_name)."</li>");
							if($this->acl_model->has_children($menu_id)) {
								$submenu_items = $this->acl_model->get_submenus($menu_id);
								foreach($submenu_items as $submenu_item) {
									if($this->acl_model->has_access($submenu_item->url))
										echo("<li class='has_sub'>".anchor($submenu_item->url,$submenu_item->resource_name)."</li>");                
								}
							}
							echo("</li></ul>");
						} elseif(!in_array($url[1],$modules) && $item[0].'/'.$item[1] == 'admin/index') {
							echo("<li><a href='#' class='open subdrop'>".$main_menu_item->resource_name."<span class='pull-right'><i class='icon-chevron-right'></i></span></a>");
							echo("<ul style='display:block;'>");
							echo("<li>".anchor($main_menu_item->url,$main_menu_item->resource_name)."</li>");
							if($this->acl_model->has_children($menu_id)) {
								$submenu_items = $this->acl_model->get_submenus($menu_id);
								foreach($submenu_items as $submenu_item) {
									if($this->acl_model->has_access($submenu_item->url))
										echo("<li class='has_sub'>".anchor($submenu_item->url,$submenu_item->resource_name)."</li>");                
								}
							}
							echo("</li></ul>");
						} else {
							echo("<li><a href='#'>".$main_menu_item->resource_name."<span class='pull-right'><i class='icon-chevron-right'></i></span></a>");
							echo("<ul>");
							echo("<li>".anchor($main_menu_item->url,$main_menu_item->resource_name.' Dashboard')."</li>");
							if($this->acl_model->has_children($menu_id)) {
								$submenu_items = $this->acl_model->get_submenus($menu_id);
								foreach($submenu_items as $submenu_item) {
									if($this->acl_model->has_access($submenu_item->url))
										echo("<li class='has_sub'>".anchor($submenu_item->url,$submenu_item->resource_name)."</li>");                
								}
							}
							echo("</li></ul>");
						}
					}
				}
				?>
			</ul>
	</div>
</div>