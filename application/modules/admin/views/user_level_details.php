<?php 
foreach($details as $detail)
	$parents = json_decode($detail->parent);
?>
<div id="contentpop">
	<div class="boxpop corners shadow">
		<div class="box-contentpop" id="pages-2">
		<table id="box-table-a" style="width:95%">
		<tr>
			<td>Name:</td>
			<td><?php echo $detail->user_level; ?></td>
		</tr>
		<tr>
			<td>ID:</td>
			<td><?php echo $detail->u_level_id; ?></td>
		</tr>
		<tr>
			<td>Active:</td>
			<td><?php echo $detail->activate; ?></td>
		</tr>
		<?php 	if($parents){ for($i=0;$i<count($parents);$i++){
			$name = $this->admin_model->get_level_parent($parents[$i]);
		?>
		<tr>
			<td>Parent:</td>
			<td><?php echo $name; ?></td>
		</tr>
		<?php } } ?> 
		</table>
		</div>
	</div>
</div>