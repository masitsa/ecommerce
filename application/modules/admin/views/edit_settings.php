<?php 
$setting_value= array('name'=>'setting_value','id'=>'setting_value','value'=>$setting->setting_value);
?>
<div id="contentpop">
<div class="boxpop corners shadow">
	    <div class="box-headerpop">
	       <h2>Update Setting</h2>
		</div>
	    <div class="box-contentpop" id="pages-2">
			<?php echo form_open("admin/update_setting/".$this->uri->segment(3)); ?>
			   <table style="margin:2px;width:800;">
			      <tr>
			        <td><?php echo form_label('Value'); ?>&nbsp;<?php echo form_input($setting_value); ?>
					<?php if(form_error($setting_value['name']))echo '<div class="form-msg-error-advanced">'.form_error($setting_value['name']).'</div>'; ?><?php echo isset($errors[$setting_value['name']])?$errors[$setting_value['name']]:''; ?>
			        <?php echo form_submit('submit', 'Update'); ?>
			        </td>
			      </tr>
			    </table>
			<?php echo form_close();?>
	    </div>
   </div>
</div>