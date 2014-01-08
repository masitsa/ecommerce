<?php
$location_name = array('name'=>'location_name','id'=>'location_name','placeholder'=>'Location Name','value'=>$details->location_name);
$address = array('name'=>'address','id'=>'address','placeholder'=>'Full Address','value'=>$details->full_address,'readonly'=>'readonly');
$latlng = array('name'=>'latlng','id'=>'latlng','placeholder'=>'LatLong','value'=>$details->lat.','.$details->lng,'readonly'=>'readonly');
$zip = array('name'=>'zip','id'=>'zip','placeholder'=>'Zip Code','value'=>$details->zip_code,'readonly'=>'readonly');
?>
<div id="map_canvas" style="width:100%;height:100%;position: absolute;left:0"></div>
<div id="show_hide" onclick="location.href='javascript:toggle();'" class="shadow1">Hide Panel</div>
<div id="side_panel" class="shadow1" style="width:300px; height:350px;">
	<div style="float:top;height:100%;margin: 10px 10px 10px 10px;font-size:13px;">
		<?php echo anchor('admin/map','View Map') ?><br /><br />
		<?php echo form_error('location_name')||form_error('address')||form_error('latlng')||form_error('zip')?'Please fill in all fields':''; ?>
		<?php echo form_open($this->uri->uri_string()); ?>
			<table style="width:100%;">
				<tr>
					<td><input type="text" id="search" size="20" placeholder="Search"/></td>
				</tr>
				<tr>
					<td><?php echo form_input($location_name); ?></td>
				</tr>
				<tr>
					<td><?php echo form_input($address); ?></td>
				</tr>
				<tr>
					<td><?php echo form_input($latlng); ?></td>
				</tr>
				<tr>
					<td><?php echo form_input($zip); ?></td>
				</tr>
				<tr>
					<td><?php echo form_submit('save','Save'); ?></td>
				</tr>
			</table>
		<?php echo form_close() ?>
	</div>
</div>