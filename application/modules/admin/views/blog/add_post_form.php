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
   external_filemanager_path: CI.base_url+"/assets/filemanager/",
   filemanager_title:"Builtapp CMS Filemanager" ,
   
   external_plugins: { "filemanager" : CI.base_url+"/assets/filemanager/plugin.min.js"}
 });
</script>
<div class="widget">
	<div class="widget-head">
		<div class="pull-left"><i class="icon-pushpin title"></i>Add Post</div>
		<div class="widget-icons pull-right">
			<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
		</div>  
		<div class="clearfix"></div>
	</div>             
	<div class="widget-content">
		<div class="padd">
			<?php echo form_open($this->uri->uri_string(),"class='form-horizontal'"); ?>
			<div class="control-group">
				<label class="control-label" for="post_title">Post Title</label>
				<div class="controls">
					<?php echo form_input('post_title',$this->input->post('post_title')); ?>
					<span class="help-block" id="error-span">
						<?php echo form_error('post_title');?>
						<?php echo isset($post_title_error)?$post_title_error:''; ?>
					</span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="post_content">Post Content</label>
				<div class="controls">
					<textarea name="post_content" id="post_content" style="width:100%;"><?php echo $this->input->post('post_content')!='<br>'?$this->input->post('post_content'):''; ?></textarea>
					<span class="help-block" id="error-span">
						<?php echo form_error('post_content');?>
					</span>
				</div>
			</div>
			<div class="form-actions">
				<button type="submit" class="btn btn-primary btn-large">Submit</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>