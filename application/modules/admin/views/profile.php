<div class="widget">
	<div class="widget-head">
		<div class="pull-left"><i class="icon-user title"></i>Users</div>
		<div class="widget-icons pull-right">
			<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
		</div>  
		<div class="clearfix"></div>
	</div>             
	<div class="widget-content">
		<div class="padd">
			<?php echo form_open('admin/search_user',"class='form-horizontal'");?>
			<input type="text" name="searcht" placeholder="Search Users..." id="search_input">
			<div id="search_submit"  onclick="$(this).closest('form').submit();"> </div>         
			<?php  echo form_close(); ?>
		
			<?php echo form_open('admin/save_bulk/'.$this->uri->segment(3),"class='form-horizontal'"); ?>
			<?php echo $this->table->generate(); ?>
			<?php  echo form_close(); ?>
			<div id="pagination"><?php echo $this->pagination->create_links(); ?></div>
		</div>
	</div>
</div>