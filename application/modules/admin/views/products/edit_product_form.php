<?php
$product_name = array('name'=>'product_name','id'=>'product_name','value'=>$details->product_name);
$product_selling_price = array('name'=>'product_selling_price','id'=>'product_selling_price','value'=>$details->product_selling_price);
$product_buying_price = array('name'=>'product_buying_price','id'=>'product_buying_price','value'=>$details->product_buying_price);
$product_description = array('name'=>'product_description','id'=>'product_description','value'=>$details->product_description,'style'=>'width:90%;');
$product_balance = array('name'=>'product_balance','id'=>'product_balance','value'=>$details->product_balance);
$product_code = array('name'=>'product_code','id'=>'product_code','value'=>$details->product_code,'readonly'=>'readonly');

$categories = $this->products_model->get_all_active_categories();
$categories_array = array();
$categories_array[0] = 'None';

$brands = $this->products_model->get_all_active_brands();
$brands_array = array();
$brands_array[0] = 'None';
foreach($brands as $brand)
	$brands_array[$brand->brand_id] = $brand->brand_name;

if(is_array($categories)){
	foreach($categories as $category){
		$categories_array[$category->category_id] = $category->category_name;
	}
}
if(is_array($features)){
	foreach($features as $feature) {
		$values[$feature->feature_id] = $feature->feature_value;
	}
}
?>
<script type="text/javascript">
	$(document).ready(function() {
		if ($('select#category_id').val() > 0) {
			var features = $.ajax(
			{
				url: '<?php echo site_url();?>/index.php/admin/products/get_category_features/'+$('select#category_id').val(),
				processData: false,
				contentType: false,
				cache: true
			});
			features.done(function(code) {
				$('div.features').fadeIn('slow').html(code);
			});
		} else {
			$('div.features').fadeIn('slow').html('');
			$('input#product_code').val('');
		}
		$('select#category_id').unbind('change').bind('change',function() {
			console.log('Select Changed');
			if ($(this).val() > 0) {
				var code = $.ajax(
				{
					url: '<?php echo site_url();?>/index.php/admin/products/build_new_product_code/'+$(this).val(),
					processData: false,
					contentType: false,
					cache: true
				});
				code.done(function(code) {
					$('input#product_code').val(code);
				});
				var features = $.ajax(
				{
					url: '<?php echo site_url();?>/index.php/admin/products/get_category_features/'+$(this).val(),
					processData: false,
					contentType: false,
					cache: true
				});
				features.done(function(code) {
					$('div.features').fadeIn('slow').html(code);
				});
			} else {
				$('div.features').fadeIn('slow').html('');
				$('input#product_code').val('');
			}
		});
	});
</script>
<div id="content">
	<div class="content-top">
		<h3>Edit Product</h3>
	</div>
	<?php echo form_open_multipart($this->uri->uri_string(),"class='form-horizontal'"); ?>
		<div class="control-group">
			<label  class="control-label" for="brand_id">Product Brand</label>
			<div class="controls">
				<select name="brand_id"><option value="0">----None----</option>
            	<?php
					if(count($brands) > 0){
						foreach ($brands as $cust){
							$brand_name = $cust->brand_name;
							$brand_id = $cust->brand_id;
							
							if($brand_id == $details->brand_id){
								echo "<option value='".$brand_id."' selected>".$brand_name."</option>";
							}
							else{
								echo "<option value='".$brand_id."'>".$brand_name."</option>";
							}
						}
					}
				?>
            </select>
				<span class="help-block">
					<?php echo form_error('brand_id');?>
				</span>
			</div>
		</div>
		<div class="control-group">
			<label  class="control-label" for="category_id">Product Category</label>
			<div class="controls">
				<?php echo form_dropdown('category_id',$categories_array,$details->category_id,'id = "category_id"');?>
				<span class="help-block">
					<?php echo form_error('category_id');?>
				</span>
			</div>
		</div>
		<div class="control-group">
			<label  class="control-label" for="product_code">Product Code</label>
			<div class="controls">
				<?php echo form_input($product_code);?>
				<span class="help-block">
					<?php echo form_error('product_code');?>
				</span>
			</div>
		</div>
		<div class="features">
			
		</div>
		<div class="control-group">
			<div class="controls">
				<span class="help-block">
					<?php echo form_error('feature[]');?>
				</span>
			</div>
		</div>
		<div class="control-group">
			<label  class="control-label" for="product_name">Product Name</label>
			<div class="controls">
				<?php echo form_input($product_name);?>
				<span class="help-block">
					<?php echo form_error('product_name');?>
				</span>
			</div>
		</div>
		<div class="control-group">
			<label  class="control-label" for="product_selling_price">Selling Price</label>
			<div class="controls">
				<?php echo form_input($product_selling_price);?>
				<span class="help-block">
					<?php echo form_error('product_selling_price');?>
				</span>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="product_buying_price">Buying Price</label>
			<div class="controls">
				<?php echo form_input($product_buying_price);?>
				<span class="help-block">
					<?php echo form_error('product_buying_price');?>
				</span>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="product_description">Description</label>
			<div class="controls">
				<?php echo form_textarea($product_description);?>
				<span class="help-block">
					<?php echo form_error('product_description');?>
				</span>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="product_balance">Stock Level</label>
			<div class="controls">
				<?php echo form_input($product_balance);?>
				<span class="help-block">
					<?php echo form_error('product_balance');?>
				</span>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="image">Product Image</label>
            <?php echo form_hidden("image", $details->product_image_name);?>
			<div class="controls">
				<div class="fileupload fileupload-new" data-provides="fileupload">
					<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;"><?php echo img(array('src'=>base_url().'assets/products/images/'.$details->product_image_name,'alt'=>'Product Image')); ?></div>
					<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
					<div>
					<span class="btn btn-file"><span class="fileupload-new">Select image to replace</span><span class="fileupload-exists">Change</span><input type="file" name="userfile" id="userfile" /></span>
					<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
					</div>
					<span class="help-block">
						<?php echo !empty($error)?$error:''; ?>
					</span>
				</div>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="image">Gallery Images</label>
			<div class="controls">
				<?php echo form_upload(array( 'name'=>'gallery[]', 'multiple'=>true ));?>
            </div>
        </div>
		<div class="form-actions">
			<button type="submit" class="btn">Save</button>
		</div>
		<div class="control-group">
			<label class="control-label" for="image">Gallery</label>
			<div class="controls">
	<?php echo form_close(); ?>
		
		<?php
         
		 if(is_array($product_images)){
			 foreach($product_images as $prod){
				 $id = $prod->product_image_id;
				 $image = $prod->product_image_name;
				 $thumb = $prod->product_image_thumb;
				 ?>
                 <div style="float:left">
                	<img src="<?php echo base_url();?>assets/products/gallery/<?php echo $thumb;?>" alt="<?php echo $thumb;?>"/>
                 	<a href="<?php echo site_url("admin/products/delete_gallery_image/".$id."/".$product_id."/".$page)?>">Delete</a>
                 </div>
		<?php
			 }
		 }
		 
		 ?>
         </div>
</div>