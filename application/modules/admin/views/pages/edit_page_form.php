<script>
    $(function() {
        $('#page_order').spinner({
            min: 1,
            max: 20,
            step: 1
        });
    });
	$(document).ready(function(){
		if($('input#post_page').is(':checked')) {
			$('div.page-content-left,div.page-content-right,div.page-content-middle').hide();
		} else {
			if($('select#page_layout').val() == 4) {
				if (!$('div.page-content-middle').is(':visible')) {
					$('div.page-content-middle').show();
				}
			} else {
				if ($('div.page-content-middle').is(':visible')) {
					$('div.page-content-middle').hide();
				}
			}
		}
		
		$('input#post_page').on('click',function(event) {
			if ($(this).is(':checked')) {
				$('div.page-content-left,div.page-content-right,div.page-content-middle').slideUp();
			} else {
				$('div.page-content-left,div.page-content-right').slideDown();
				if($('select#page_layout').val() == 4) {
					if (!$('div.page-content-middle').is(':visible')) {
						$('div.page-content-middle').show();
					}
				} else {
					if ($('div.page-content-middle').is(':visible')) {
						$('div.page-content-middle').hide();
					}
				}
			}
		});
		
		$('select#page_layout').on('change',function() {
			if($('input#post_page').is(':checked')) {
				// Do nothing if post_page is checked
			} else {
				if($(this).val() == 4) {
					if (!$('div.page-content-middle').is(':visible')) {
						$('div.page-content-middle').slideDown();
					}
				} else {
					if ($('div.page-content-middle').is(':visible')) {
						$('div.page-content-middle').slideUp();
					}
				}
			}
		});
	});
</script>
<?php
$page_order = array('name'=>'page_order','id'=>'page_order','value'=>$details->page_order);
$layouts = $this->pages_model->get_page_layouts();
$parents = $this->pages_model->get_parent_pages();
$layouts_dropdown = array();
$parents_dropdown = array();
$parents_dropdown[0] = 'No Parent';
foreach($layouts as $layout)
	$layouts_dropdown[$layout->layout_id] = $layout->layout_label;
foreach($parents as $parent)
	if($parent->page_id != $details->page_id)
		$parents_dropdown[$parent->page_id] = $parent->page_title;
$post_page = array('name'=>'post_page','id'=>'post_page','value'=>1,'checked'=>$details->post_page);
?>
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
					<label  class="control-label" for="page_title">Page Title</label>
					<div class="controls">
						<?php echo form_input('page_title',$details->page_title);?>
						<span class="help-block">
							<?php echo form_error('page_title');?>
							<?php echo isset($page_title_error)?$page_title_error:''; ?>
						</span>
					</div>
				</div>
				<div class="control-group">
					<label  class="control-label" for="page_order">Page Order</label>
					<div class="controls">
						<?php echo form_input($page_order);?>
						<span class="help-block">
							<?php echo form_error('page_order');?>
						</span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="resource_type">Page Layout</label>
					<div class="controls">
						<?php echo form_dropdown('page_layout',$layouts_dropdown,$details->page_layout,'class="span4" id="page_layout"');?>
						<span class="help-block">
							<?php echo form_error('page_layout');?>
						</span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="resource_type">Page Parent</label>
					<div class="controls">
						<?php echo form_dropdown('parent',$parents_dropdown,$details->parent,'class="span4"');?>
						<span class="help-block">
							<?php echo form_error('parent');?>
						</span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="post_page">Page Holds Posts?</label>
					<div class="controls">
						<?php echo form_hidden('post_page','0'); ?>
						<?php echo form_checkbox($post_page); ?>
					</div>
				</div>
				<div class="control-group page-content-left">
					<label  class="control-label" for="content">Left Column Content</label>
					<div class="controls">
						<textarea name="page_content_left" id="page_content_left" style="width:100%;"><?php echo $details->page_content_left !='<br>'?$details->page_content_left:''; ?></textarea>
						<span class="help-block">
							<?php echo form_error('page_content_left');?>
						</span>
					</div>
				</div>
				<div class="control-group page-content-middle">
					<label  class="control-label" for="content">Middle Column Content</label>
					<div class="controls">
						<textarea name="page_content_middle" id="page_content_middle" class="span12"><?php echo $details->page_content_middle !='<br>'?$details->page_content_middle:''; ?></textarea>
						<span class="help-block">
							<?php echo form_error('page_content_middle');?>
						</span>
					</div>
				</div>
				<div class="control-group page-content-right">
					<label  class="control-label" for="content">Right Column Content</label>
					<div class="controls">
						<textarea name="page_content_right" id="page_content_right" style="width:100%;"><?php echo $details->page_content_right !='<br>'?$details->page_content_right:''; ?></textarea>
						<span class="help-block">
							<?php echo form_error('page_content_right');?>
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