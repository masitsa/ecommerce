<?php
$category_feature_name = array('name'=>'category_feature_name','id'=>'category_feature_name','value'=>$category_features->category_feature_name);
$feature_name = array('name'=>'category_feature_value[]');

$details = $this->products_model->products_model->get_category_feature_values($category_features->category_feature_id);
$categories = $this->products_model->get_all_active_categories();
?>
<div id="content">
	<div class="content-top">
		<h3>Edit Feature</h3>
	</div>
	<?php echo form_open_multipart($this->uri->uri_string(),"class='form-horizontal'"); ?>
		<div class="control-group">
			<label  class="control-label" for="category_id">Category</label>
			<div class="controls">
            	<select name="category_id">
				<?php 
				
				foreach($categories as $category){
					
					if($category->category_id === $category_features->category_id){
						echo "<option selected value='".$category->category_id."'>".$category->category_name."</option>";
					}
					
					else{
						echo "<option value='".$category->category_id."'>".$category->category_name."</option>";
					}
	
				}
				?>
                </select>
				<span class="help-block">
					<?php echo form_error('category_id');?>
				</span>
			</div>
		</div>
		<div class="control-group">
			<label  class="control-label" for="feature_name">Category Feature Name</label>
			<div class="controls">
				<?php echo form_input($category_feature_name);?>
				<span class="help-block">
					<?php echo form_error('category_feature_name');?>
				</span>
			</div>
		</div>
		<div class="control-group">
			<label  class="control-label" for="feature_name">Feature Value 1</label>
			<div class="controls">
				<?php echo form_input("category_feature_value1");?>
				<div class="fileupload fileupload-new" data-provides="fileupload">
					<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;"><img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" /></div>
					<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
					<div>
					<span class="btn btn-file"><span class="fileupload-new">Select Feature Value 1 Image</span><span class="fileupload-exists">Change</span><input type="file" name="userfile1" id="userfile" /></span>
					<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
					</div>
					<span class="help-block">
						<?php echo !empty($error1)?$error1:''; ?>
					</span>
				</div>
			</div>
		</div>
		<div class="control-group">
			<label  class="control-label" for="feature_name">Feature Value 2</label>
			<div class="controls">
				<?php echo form_input("category_feature_value2");?>
				<div class="fileupload fileupload-new" data-provides="fileupload">
					<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;"><img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" /></div>
					<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
					<div>
					<span class="btn btn-file"><span class="fileupload-new">Select Feature Value 2 Image</span><span class="fileupload-exists">Change</span><input type="file" name="userfile2" id="userfile" /></span>
					<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
					</div>
					<span class="help-block">
						<?php echo !empty($error2)?$error2:''; ?>
					</span>
				</div>
			</div>
		</div>
		<div class="control-group">
			<label  class="control-label" for="feature_name">Feature Value 3</label>
			<div class="controls">
				<?php echo form_input("category_feature_value3");?>
				<div class="fileupload fileupload-new" data-provides="fileupload">
					<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;"><img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" /></div>
					<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
					<div>
					<span class="btn btn-file"><span class="fileupload-new">Select Feature Value 3 Image</span><span class="fileupload-exists">Change</span><input type="file" name="userfile3" id="userfile" /></span>
					<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
					</div>
					<span class="help-block">
						<?php echo !empty($error3)?$error3:''; ?>
					</span>
				</div>
			</div>
		</div>
		<div class="control-group">
			<label  class="control-label" for="feature_name">Feature Value 4</label>
			<div class="controls">
				<?php echo form_input("category_feature_value4");?>
				<div class="fileupload fileupload-new" data-provides="fileupload">
					<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;"><img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" /></div>
					<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
					<div>
					<span class="btn btn-file"><span class="fileupload-new">Select Feature Value 4 Image</span><span class="fileupload-exists">Change</span><input type="file" name="userfile4" id="userfile" /></span>
					<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
					</div>
					<span class="help-block">
						<?php echo !empty($error4)?$error4:''; ?>
					</span>
				</div>
			</div>
		</div>
		<div class="control-group">
			<label  class="control-label" for="feature_name">Feature Value 5</label>
			<div class="controls">
				<?php echo form_input("category_feature_value5");?>
				<div class="fileupload fileupload-new" data-provides="fileupload">
					<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;"><img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" /></div>
					<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
					<div>
					<span class="btn btn-file"><span class="fileupload-new">Select Feature Value 5 Image</span><span class="fileupload-exists">Change</span><input type="file" name="userfile5" id="userfile" /></span>
					<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
					</div>
					<span class="help-block">
						<?php echo !empty($error5)?$error5:''; ?>
					</span>
				</div>
			</div>
		</div>
		<div class="form-actions">
			<button type="submit" class="btn">Update</button>
		</div>
	<?php echo form_close(); ?>
</div>