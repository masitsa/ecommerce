<div id="map_canvas" style="width:100%;height:90%;position: absolute;left:0"></div>
<div id="show_hide" onclick="location.href='javascript:toggle();'" class="shadow1">Hide Locations</div>
<div id="side_panel" class="shadow1">
	<div style="float:top;height:100%;margin: 10px 10px 10px 10px;font-size:13px;">
		<?php echo anchor('admin/map/add_location','Add a new location') ?><br /><br />
		<input type="text" id="search" size="20" placeholder="Find a site" style="width:75%; margin:0;"/>
		<div id="list">
			<?php echo modules::run('admin/map/build_admin_map_sidebar'); ?>
		</div>
	</div>
</div>