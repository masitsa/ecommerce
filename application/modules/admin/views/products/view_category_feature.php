<div id="content">
	<div class="content-top">
		<h3>Category Feature Values</h3>
	</div>
    <?php echo form_open("admin/products/update_category_feature_value/".$category_feature_id,"class='form-horizontal'");?>
	<table class="table table-striped table-hover">
		<tr>
        	<th>Image</th>
        	<th>Name</th>
        	<th>Price</th>
        	<th>Delete</th>
		</tr>
    	<?php if(count($details) > 0) { ?>
		<?php foreach($details as $feature) { ?>
		<tr>
        	<td><img src="<?php echo base_url().'assets/features/thumbs/'.$feature->category_feature_value_image; ?>" alt="<?php echo $feature->category_feature_value; ?>"/></td>
			<td><input type="text" name="feature_name<?php echo $feature->category_feature_value_id; ?>" value="<?php echo $feature->category_feature_value; ?>" /></td>
			<td><input type="text" name="feature_price<?php echo $feature->category_feature_value_id; ?>" value="<?php echo $feature->category_feature_value_price; ?>" /></td>
			<td><?php echo anchor("admin/products/delete_category_feature_value/$feature->category_feature_value_id/$category_feature_id",img(array('src'=>base_url().'/img/icons/16/delete.gif')), array('onClick' =>  'return confirm(\'Do you really want to delete this value?\');', 'title' => 'Delete')); ?></td>
		</tr>
		<?php } 
		}
		else{
			echo "This category feature has no values :-|";
		}
		?>
	</table>
    <table align="center">
    	<tr>
        	<td><button type="submit" class="btn">Update</button></td>
        </tr>
    </table>
	<?php form_close();//var_dump($details); ?>
	<?php // var_dump($features); ?>
</div>
<script type="text/javascript">
	
	function save_price(category_feature_value_id){alert("here");
    	var price = $("#feature_price"+category_feature_value_id).val();
		alert(<?php echo base_url()?> + "admin/products/save_feature_price/"+category_feature_value_id+"/"+price);
		$.post(<?php echo base_url()?> + "admin/products/save_feature_price/"+category_feature_value_id+"/"+price,
  			function(data){
    		
 		 });
	}
</script>