<div id="contentpop">
	<div class="boxpop corners shadow">
	    <div class="box-headerpop">
	       <h2><?php echo ucfirst($profile)?>'s info</h2>
		 </div>
	    <div class="box-contentpop" id="pages-2">
		    <?php foreach($users as $user){	?>
			<?php $groups = json_decode($user->u_group_id);
				$cg = count($groups); ?>
		    <table id="box-table-a" style="width:95%">
				<tr>
					<td>Username</td>
					<td><?php echo $user->username; ?></td>
				</tr>
				<tr>
					<td>Full Name</td>
					<td><?php echo ucfirst($user->fullname)?></td>
				</tr>
				<tr>
					<td>Email Address</td>
					<td><?php echo $user->email?></td>
				</tr>
				<?php if($this->auth_model->get_auth_setting_value('user_groups_active')==1){?>
					<?php if(is_array($groups)){ for($i=0;$i<$cg;$i++){ ?>
					<tr>
						<td>User Group</td>
						<td><?php echo ucfirst($this->admin_model->get_group($groups[$i]));?></td>
					</tr>
					<?php } } ?>
				<?php }?>
				<?php if($this->auth_model->get_auth_setting_value('user_levels_active')==1){?>
				<tr>
					<td>User Level</td>
					<td><?php echo ucfirst($this->admin_model->get_level($user->u_level_id));?></td>
				</tr>
				<?php }?>
				<tr>
					<td>User Status</td>
					<td><?php echo ucfirst($this->admin_model->get_status($user->activated)."d");?></td>
				</tr>
				<tr>
					<td>A Member Since</td>
					<td><?php echo $user->created;?></td>
				</tr>
				<tr>
					<td>Account Last Updated on</td>
					<td><?php echo $user->modified;?></td>
				</tr>
		    </table>
		    <?php }?>
		</div>
	</div>
</div>