<?php
$category_name = array('name'=>'category_name','id'=>'category_name','value'=>set_value('category_name'));
$category_preffix = array('name'=>'category_preffix','id'=>'category_preffix','value'=>set_value('category_preffix'),'readonly'=>'readonly');
$category_status = array('name'=>'category_status','id'=>'category_status','value'=>1,'checked'=>'checked');

$categories = $this->products_model->get_all_active_categories();
$categories_array = array();
$categories_array[0] = 'None';
foreach($categories as $category)
	$categories_array[$category->category_id] = $category->category_name;
	
?>
<script type="text/javascript">
	var validateTimer
	var global = {};
	$(document).ready(function() {
		$('input#category_name').bind('input',function() {
			//console.log('Input Changed');
			var prefix = $(this).val().replace(/a|e|i|o|u| /gi,'').substr(0,4);
			if (prefix.length > 0) {
				$('input#category_preffix').val(prefix.toUpperCase());
				clearTimeout(validateTimer);
				validateTimer = setTimeout("validatePrefix()", 1000);
			} else {
				$('input#category_preffix').next('span.help-block').fadeOut('slow');
				$('input#category_preffix').val(prefix.toUpperCase());
				clearTimeout(validateTimer);
			}
			
		});
	});
	
	function validatePrefix() {
		var formdata = false;
		if (window.FormData) {
			global.formdata = new FormData();
		}
		
		global.formdata.append($('input#category_preffix').attr('name'),$('input#category_preffix').val());
		var preffix = $('input#category_preffix').val();
		//alert('/index.php/admin/products/ajax_unique_prefix/'+preffix);
		var request = $.ajax(
		{
			type: "POST",
			url: '<?php echo site_url();?>/index.php/admin/products/ajax_unique_prefix/'+preffix,
			processData: false,
			contentType: false,
			data: global.formdata,
		});
		
		request.done(function(message) {
			console.log(message);
			if (message == 0) {
				$('input#category_preffix').next('span.help-block').fadeIn('slow').css('color','red').html('This prefix is already in use. Please change the category name.');
			} else {
				$('input#category_preffix').next('span.help-block').fadeIn('slow').css('color','green').html('This prefix is available.');
			}
		});
		
		request.fail(function( jqXHR, textStatus ) {});
	}
</script>
<div id="content">
	<div class="content-top">
		<h3>Add Product Category</h3>
	</div>
	<?php echo form_open_multipart($this->uri->uri_string(),"class='form-horizontal'"); ?>
		<div class="control-group">
			<label  class="control-label" for="category_name">Category Name</label>
			<div class="controls">
				<?php echo form_input($category_name);?>
				<span class="help-block">
					<?php echo form_error('category_name');?>
				</span>
			</div>
		</div>
		<div class="control-group">
			<label  class="control-label" for="category_preffix">Category Preffix</label>
			<div class="controls">
				<?php echo form_input($category_preffix);?>
				<span class="help-block">
					<?php echo form_error('category_preffix');?>
				</span>
			</div>
		</div>
		<div class="control-group">
			<label  class="control-label" for="category_parent">Parent Category</label>
			<div class="controls">
				<?php echo form_dropdown('category_parent',$categories_array,$this->input->post('category_parent'),'id = "category_parent"');?>
				<span class="help-block">
					<?php echo form_error('category_parent');?>
				</span>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="category_status">Active</label>
			<div class="controls">
				<?php echo form_hidden('category_status',0); ?>
				<?php echo form_checkbox($category_status);?>
				<span class="help-block">
					<?php echo form_error('category_status');?>
				</span>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="image">Category Image</label>
			<div class="controls">
				<div class="fileupload fileupload-new" data-provides="fileupload">
					<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;"><img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" /></div>
					<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
					<div>
					<span class="btn btn-file"><span class="fileupload-new">Select image to upload</span><span class="fileupload-exists">Change</span><input type="file" name="userfile" id="userfile" /></span>
					<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
					</div>
					<span class="help-block">
						<?php echo !empty($error)?$error:''; ?>
					</span>
				</div>
			</div>
		</div>
		<div class="form-actions">
			<button type="submit" class="btn">Save</button>
		</div>
	<?php echo form_close(); ?>
</div>