<?php 
$u_setting = array('name'=>'u_setting','id'=>'u_setting','value'=>$settings[0]->value);
$pages = $this->admin_model->get_all_pages();
$pag = '';
foreach($pages as $page){
	$pag[$page->page_url] = $page->page_title;	
}
?>
<div id="contentpop">
<div class="boxpop corners shadow">
	    <div class="box-headerpop">
	       <h2>Update Setting</h2>
		</div>
	    <div class="box-contentpop" id="pages-2">
			<?php echo form_open("admin/update_settings/".$this->uri->segment(3)); ?>
			   <table style="margin:2px;width:800;">
			      <tr>
			        <td><?php echo form_label($settings[0]->name); ?>&nbsp;<?php echo form_dropdown('u_setting',$pag,$settings[0]->value); ?>
					<?php if(form_error($u_setting['name']))echo '<div class="form-msg-error-advanced">'.form_error($u_setting['name']).'</div>'; ?><?php echo isset($errors[$u_setting['name']])?$errors[$u_setting['name']]:''; ?>
			        <?php echo form_submit('submit', 'Update'); ?>
			        </td>
			      </tr>
			    </table>
			<?php echo form_close();?>
	    </div>
   </div>
</div>