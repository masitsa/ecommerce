<div id="content">
	<div class="content-top">
		<h3>Adverts</h3>
	</div>
	<?php echo form_open("admin/adverts/bulk_adverts/".$page); ?>
	<?php echo $this->table->generate(); ?>
	<table align="center" border="0">
		<tr>
			<td>
				<select name="options">
					<option value="0"></option>
					<option value="3">Activate</option>
					<option value="1">Deactivate</option>
					<option value="2">Delete</option>
				</select>
			</td>
			<td><input type="submit" class="btn" value="Apply"/></td>
		</tr>
	</table>
	<?php echo form_close(); ?>
	<div id="pagination"><?php echo $this->pagination->create_links(); ?></div>
</div>