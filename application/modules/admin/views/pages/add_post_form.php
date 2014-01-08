<script>
    $(function() {
        $('#post_position').spinner({
            min: 1,
            max: 20,
            step: 1
        });
    });
</script>
<script>
 tinymce.init({
    selector: "textarea",
	theme: "modern",
	//width: 680,
	height: 300,
    plugins: [
         "advlist autolink link image lists charmap print preview hr anchor pagebreak",
         "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
         "table contextmenu directionality emoticons paste textcolor responsivefilemanager"
   ],
   toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
   toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
   image_advtab: false,
   
   relative_urls: false,
   external_filemanager_path:CI.base_url+"/assets/filemanager/",
   filemanager_title:"Builtapp CMS Filemanager" ,
   
   external_plugins: { "filemanager" : CI.base_url+"/assets/filemanager/plugin.min.js"}
 });
</script>
<?php
$post_title = array('name'=>'post_title','id'=>'post_title','value'=>set_value('post_title'));
$post_position = array('name'=>'post_position','id'=>'post_position','value'=>set_value('post_position'));
$pages = $this->pages_model->get_all_pages_for_posts();
$pages_dropdown[0] = 'No Page';
foreach($pages as $page)
	$pages_dropdown[$page->page_uuid] = $page->page_title;
$post_columns = $this->pages_model->get_post_columns();
foreach($post_columns as $column)
	$columns_dropdown[$column->column_id] = $column->column_name;
?>
<div class="widget">
	<div class="widget-head">
		<div class="pull-left"><i class="icon-folder-close title"></i>Edit Page</div>
		<div class="widget-icons pull-right">
			<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
		</div>  
		<div class="clearfix"></div>
	</div>             
	<div class="widget-content">
		<div class="padd">
			<?php echo form_open($this->uri->uri_string(),"class='form-horizontal'"); ?>
				<div class="control-group">
					<label  class="control-label" for="post_title">Post Title</label>
					<div class="controls">
						<?php echo form_input($post_title);?>
						<span class="help-block">
							<?php echo form_error('post_title');?>
						</span>
					</div>
				</div>
				<div class="control-group">
					<label  class="control-label" for="post_position">Post Position</label>
					<div class="controls">
						<?php echo form_input($post_position);?>
						<span class="help-block">
							<?php echo form_error('post_position');?>
						</span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="page">Page</label>
					<div class="controls">
						<?php echo form_dropdown('page',$pages_dropdown,$this->input->post('page'));?>
						<span class="help-block">
							<?php echo form_error('page');?>
						</span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="column">Column</label>
					<div class="controls">
						<?php echo form_dropdown('column',$columns_dropdown,$this->input->post('column'));?>
						<span class="help-block">
							<?php echo form_error('column');?>
						</span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="active">Active</label>
					<div class="controls">
						<?php echo form_hidden('active','0'); ?>
						<?php echo form_checkbox('active','1') ?>
					</div>
				</div>
				<div class="control-group">
					<label  class="control-label" for="post_content">Post Content</label>
					<div class="controls">
						<textarea name="post_content" id="post_content" style="width:100%;"><?php echo $this->input->post('post_content')!='<br>'?$this->input->post('post_content'):''; ?></textarea>
						<span class="help-block">
							<?php echo form_error('post_content');?>
						</span>
					</div>
				</div>
				<div class="form-actions">
					<button type="submit" class="btn btn-primary btn-large">Save</button>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>