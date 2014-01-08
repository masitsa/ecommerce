<script type="text/javascript">
	$(document).ready(function() {
		var parents = [];
		$("#parents").change(function() {
			var chosenoption=this.options[this.selectedIndex];
			var count = parents.indexOf(chosenoption.value);
			if(count == -1){
				$("#parents-tr").before('<div class="control-group'+chosenoption.value+'"><div class="controls"><select name="parent[]" id="parent[]"><option value="'+chosenoption.value+'">'+chosenoption.text+'</option></select><input type="button" id="remove" value="Remove" class="btn" onclick=removeOption('+chosenoption.value+');></div></div>');
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
$user_level = array('name'=>'user_level','id'=>'user_level','value'=>$level[0]->user_level);
$parents = $this->admin_model->get_level_parents();
$arr = '';
foreach($parents as $parent)
	$arr[$parent->u_level_id] = $parent->user_level;
$arr[0] = 'None';
unset($arr[$this->uri->segment(3)]);
foreach($level as $l)
	$paro = json_decode($l->parent);
if($paro)
	$cp = count($paro);
?>
<div class="widget">
	<div class="widget-head">
		<div class="pull-left"><i class="icon-group title"></i>Edit User Level : <?php echo $level[0]->user_level; ?></div>
		<div class="widget-icons pull-right">
			<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
		</div>  
		<div class="clearfix"></div>
	</div>             
	<div class="widget-content">
		<div class="padd">
			<?php echo form_open($this->uri->uri_string(),"class='form-horizontal'"); ?>
			<div class="control-group">
				<label class="control-label" for="user_level">User Level</label>
				<div class="controls">
					<?php echo form_input($user_level); ?>
					<span class="help-block" id="error-span">
						<?php echo form_error('user_level');?>
					</span>
				</div>
			</div>
			<?php if($paro){ for($i=0;$i<$cp;$i++){ ?>
				<div class="control-group" id="parents-tr">
					<label class="control-label" for="parents">Parent</label>
					<div class="controls">
						<?php echo form_dropdown('parent[]',$arr,$paro[$i],$id2); ?>
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