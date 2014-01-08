<?php
$types = $this->portfolio_model->get_all_categories_unpaginated();
	$type_array[] = '';
	foreach ($types as $type)
    	$type_array[$type->category_id] = $type->name;
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
<script>
	$(function() {
		$('.fileupload').fileupload() 
		var dates = $( "#project_date" ).datepicker({
			defaultDate: "now",
			changeMonth: true,
			numberOfMonths: 1,
			dateFormat: "yy-mm-dd",
			onSelect: function( selectedDate ) {
				var option = this.id == "project_date" ? "minDate" : "maxDate",
					instance = $( this ).data( "datepicker" ),
					date = $.datepicker.parseDate(
						instance.settings.dateFormat ||
						$.datepicker._defaults.dateFormat,
						selectedDate, instance.settings );
				dates.not( this ).datepicker( "option", option, date );
			}
		});
		$.datepicker.formatDate('yy-mm-dd');
	});
</script> 
<?php
$description = array('name'=>'description','id'=>'description','value'=>set_value('description'));
$project_title = array('name'=>'project_title','id'=>'project_title','value'=>set_value('project_title'));
$client = array('name'=>'client','id'=>'client','value'=>set_value('client'));
$live_preview = array('name'=>'live_preview','id'=>'live_preview','value'=>set_value('live_preview'));
$project_date = array('name'=>'project_date','id'=>'project_date','value'=>set_value('project_date'));
?>
<div class="widget">
	<div class="widget-head">
		<div class="pull-left"><i class="icon-camera-retro title"></i>Add project</div>
		<div class="widget-icons pull-right">
			<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
		</div>  
		<div class="clearfix"></div>
	</div>             
	<div class="widget-content">
		<div class="padd">
			<?php echo form_open_multipart($this->uri->uri_string(),"class='form-horizontal'"); ?>
				<div class="control-group">
					<label  class="control-label" for="portfolio_title">Project Title</label>
					<div class="controls">
						<?php echo form_input($project_title);?>
						<span class="help-block" id="error-span">
							<?php echo form_error('project_title');?>
						</span>
					</div>
				</div>
				<div class="control-group">
					<label  class="control-label" for="category">Project Category</label>
					<div class="controls">
						<?php echo form_dropdown('category',$type_array); ?>
						<span class="help-block" id="error-span">
							<?php echo form_error('category');?>
						</span>
					</div>
				</div>
				<div class="control-group">
					<label  class="control-label" for="client">Client</label>
					<div class="controls">
						<?php echo form_input($client);?>
						<span class="help-block" id="error-span">
							<?php echo form_error('client');?>
						</span>
					</div>
				</div>
				<div class="control-group">
					<label  class="control-label" for="content">Project Description</label>
					<div class="controls">
						<textarea name="description" id="description" style="width:100%;"><?php echo $this->input->post('description')!='<br>'?$this->input->post('description'):''; ?></textarea>
						<span class="help-block" id="error-span">
							<?php echo form_error('description');?>
						</span>
					</div>
				</div>
				<div class="control-group">
					<label  class="control-label" for="live_preview">Live Link (Optional)</label>
					<div class="controls">
						<?php echo form_input($live_preview);?>
						<span class="help-block" id="error-span">
							<?php echo form_error('live_preview');?>
						</span>
					</div>
				</div>
				<div class="control-group">
					<label  class="control-label" for="project_date">Project Date</label>
					<div class="controls">
						<?php echo form_input($project_date);?>
						<span class="help-block" id="error-span">
							<?php echo form_error('project_date');?>
						</span>
					</div>
				</div>
				<div class="control-group">
					<label  class="control-label" for="userfile">Project Image Preview</label>
					<div class="controls">
						<div class="fileupload fileupload-new" data-provides="fileupload">
							<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;"><img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" /></div>
							<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
							<div>
							<span class="btn btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" name="userfile" id="userfile" /></span>
							<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
							</div>
							<span class="help-block" id="error-span">
								<?php echo $file_error; ?>
							</span>
						</div>
					</div>						
				</div>
				<div class="form-actions">
					<button type="submit" class="btn btn-primary btn-large">Save</button>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>