<?php
$category_feature_name = array('name'=>'category_feature_name','id'=>'category_feature_name','value'=>set_value('category_feature_name'));
$feature_name = array('name'=>'category_feature_value[]');

$categories = $this->products_model->get_all_active_categories();
$categories_array = array();
$categories_array[0] = 'None';
foreach($categories as $category)
	$categories_array[$category->category_id] = $category->category_name;
	
?>
<div id="content">
	<div class="content-top">
		<h3>Add Category Feature</h3>
	</div>
	<?php echo form_open_multipart($this->uri->uri_string(),"class='form-horizontal'"); ?>
		<div class="control-group">
			<label  class="control-label" for="category_id">Product Category</label>
			<div class="controls">
				<?php echo form_dropdown('category_id',$categories_array,$this->input->post('category_id'),'id = "category_id"');?>
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
				<?php 
					$data = array("name" => "category_feature_value1", "placeholder" => "Name");
					echo form_input($data);
					
					$data = array("name" => "category_feature_value_price1", "placeholder" => "Price");
					echo form_input($data);
				?>
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
				<?php 
					$data = array("name" => "category_feature_value2", "placeholder" => "Name");
					echo form_input($data);
					
					$data = array("name" => "category_feature_value_price2", "placeholder" => "Price");
					echo form_input($data);
				?>
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
				<?php 
					$data = array("name" => "category_feature_value3", "placeholder" => "Name");
					echo form_input($data);
					
					$data = array("name" => "category_feature_value_price3", "placeholder" => "Price");
					echo form_input($data);
				?>
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
				<?php 
					$data = array("name" => "category_feature_value4", "placeholder" => "Name");
					echo form_input($data);
					
					$data = array("name" => "category_feature_value_price4", "placeholder" => "Price");
					echo form_input($data);
				?>
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
				<?php 
					$data = array("name" => "category_feature_value5", "placeholder" => "Name");
					echo form_input($data);
					
					$data = array("name" => "category_feature_value_price5", "placeholder" => "Price");
					echo form_input($data);
				?>
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
		<div class="control-group">
			<label  class="control-label" for="feature_name">Feature Value 6</label>
			<div class="controls">
				<?php 
					$data = array("name" => "category_feature_value6", "placeholder" => "Name");
					echo form_input($data);
					
					$data = array("name" => "category_feature_value_price6", "placeholder" => "Price");
					echo form_input($data);
				?>
				<div class="fileupload fileupload-new" data-provides="fileupload">
					<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;"><img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" /></div>
					<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
					<div>
					<span class="btn btn-file"><span class="fileupload-new">Select Feature Value 6 Image</span><span class="fileupload-exists">Change</span><input type="file" name="userfile6" id="userfile" /></span>
					<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
					</div>
					<span class="help-block">
						<?php echo !empty($error6)?$error6:''; ?>
					</span>
				</div>
			</div>
		</div>
		<div class="control-group">
			<label  class="control-label" for="feature_name">Feature Value 7</label>
			<div class="controls">
				<?php 
					$data = array("name" => "category_feature_value7", "placeholder" => "Name");
					echo form_input($data);
					
					$data = array("name" => "category_feature_value_price7", "placeholder" => "Price");
					echo form_input($data);
				?>
				<div class="fileupload fileupload-new" data-provides="fileupload">
					<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;"><img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" /></div>
					<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
					<div>
					<span class="btn btn-file"><span class="fileupload-new">Select Feature Value 7 Image</span><span class="fileupload-exists">Change</span><input type="file" name="userfile7" id="userfile" /></span>
					<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
					</div>
					<span class="help-block">
						<?php echo !empty($error7)?$error7:''; ?>
					</span>
				</div>
			</div>
		</div>
		<div class="control-group">
			<label  class="control-label" for="feature_name">Feature Value 8</label>
			<div class="controls">
				<?php 
					$data = array("name" => "category_feature_value8", "placeholder" => "Name");
					echo form_input($data);
					
					$data = array("name" => "category_feature_value_price8", "placeholder" => "Price");
					echo form_input($data);
				?>
				<div class="fileupload fileupload-new" data-provides="fileupload">
					<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;"><img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" /></div>
					<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
					<div>
					<span class="btn btn-file"><span class="fileupload-new">Select Feature Value 8 Image</span><span class="fileupload-exists">Change</span><input type="file" name="userfile8" id="userfile" /></span>
					<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
					</div>
					<span class="help-block">
						<?php echo !empty($error8)?$error8:''; ?>
					</span>
				</div>
			</div>
		</div>
		<div class="control-group">
			<label  class="control-label" for="feature_name">Feature Value 9</label>
			<div class="controls">
				<?php 
					$data = array("name" => "category_feature_value9", "placeholder" => "Name");
					echo form_input($data);
					
					$data = array("name" => "category_feature_value_price9", "placeholder" => "Price");
					echo form_input($data);
				?>
				<div class="fileupload fileupload-new" data-provides="fileupload">
					<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;"><img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" /></div>
					<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
					<div>
					<span class="btn btn-file"><span class="fileupload-new">Select Feature Value 9 Image</span><span class="fileupload-exists">Change</span><input type="file" name="userfile9" id="userfile" /></span>
					<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
					</div>
					<span class="help-block">
						<?php echo !empty($error9)?$error9:''; ?>
					</span>
				</div>
			</div>
		</div>
		<div class="control-group">
			<label  class="control-label" for="feature_name">Feature Value 10</label>
			<div class="controls">
				<?php 
					$data = array("name" => "category_feature_value10", "placeholder" => "Name");
					echo form_input($data);
					
					$data = array("name" => "category_feature_value_price10", "placeholder" => "Price");
					echo form_input($data);
				?>
				<div class="fileupload fileupload-new" data-provides="fileupload">
					<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;"><img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" /></div>
					<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
					<div>
					<span class="btn btn-file"><span class="fileupload-new">Select Feature Value 10 Image</span><span class="fileupload-exists">Change</span><input type="file" name="userfile10" id="userfile" /></span>
					<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
					</div>
					<span class="help-block">
						<?php echo !empty($error10)?$error10:''; ?>
					</span>
				</div>
			</div>
		</div>
		<div class="form-actions">
			<button type="submit" class="btn">Save</button>
		</div>
	<?php echo form_close(); ?>
</div>