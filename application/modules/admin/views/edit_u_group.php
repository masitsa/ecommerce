<script type="text/javascript">
	$(document).ready(function() {
		var parents = [];
		$("#parents").change(function() {
			var chosenoption=this.options[this.selectedIndex];
			var count = parents.indexOf(chosenoption.value);
			if(count == -1){
				$("#parents-tr").before('<div class="control-group'+chosenoption.value+'"><div class="controls"><select name="parent[]" id="parent[]"><option value="'+chosenoption.value+'">'+chosenoption.text+'</option></select></td><td><input type="button" id="remove" value="Remove" class="btn" onclick=removeOption('+chosenoption.value+');></div></div>');
				parents.push(chosenoption.value);
			}else{
				alert("Already Added");
			}
		});
	});
	function removeOption(option){
		$('.control-group'+option).remove();
	};
</script>
<?php
$id = 'id="parents"';
$id2 = 'id="existing"';
$user_group = array('name'=>'user_group','id'=>'user_group','value'=>$group[0]->user_group,'class'=>'input-1','size'=>30);
$parents = $this->admin_model->get_group_parents();
$arr = '';
foreach($parents as $parent)
	$arr[$parent->u_group_id] = $parent->user_group;
$arr[0] = 'None';
foreach($group as $l)
	$paro = json_decode($l->parent);
?>
<div class="widget">
	<div class="widget-head">
		<div class="pull-left"><i class="icon-group title"></i>Edit User Group : <?php echo $group[0]->user_group; ?></div>
		<div class="widget-icons pull-right">
			<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
		</div>  
		<div class="clearfix"></div>
	</div>             
	<div class="widget-content">
		<div class="padd">
			<?php echo form_open($this->uri->uri_string(),"class='form-horizontal'"); ?>
			<div class="control-group">
				<label class="control-label" for="user_level">User Group</label>
				<div class="controls">
					<?php echo form_input($user_group); ?>
					<span class="help-block" id="error-span">
						<?php echo form_error('user_group');?>
					</span>
				</div>
			</div>
			<?php if($paro){ for($i=0;$i<count($paro);$i++){ ?>
			<div class="control-group" id="parents-tr">
				<label class="control-label" for="parent">Add Parent</label>
				<div class="controls">
					<?php echo form_dropdown('parent[]',$arr,$paro[$i],$id2); ?>
					<span class="help-block" id="error-span">
						<?php echo form_error('parent');?>
					</span>
				</div>
			</div>
			<?php } } ?>
			<div class="control-group" id="parents-tr">
				<label class="control-label" for="parents">Add Parent</label>
				<div class="controls">
					<?php echo form_dropdown('parents',$arr,0,$id);?>
					<span class="help-block" id="error-span">
						<?php echo form_error('parent');?>
					</span>
				</div>
			</div>
			<div class="form-actions">
				<button type="submit" class="btn btn-primary btn-large">Save Changes</button>
			</div>
			<?php echo form_close();?>
		</div>
	</div>
</div>