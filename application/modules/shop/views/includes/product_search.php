
<!-- start: Header -->
<div id="header">
    <div class="container">
        <div class="row-fluid">
            <div class="span3 logo">
            	<a href="<?php echo base_url();?>"><img src="<?php echo base_url();?>img/logo.png" alt="Logo"/></a>
                <!--<h1 class="site-name"></h1>
                <h2 class="site-slogan">all for your studio</h2>-->
            </div>
            
            <?php 
				$attributes = array("style" => "margin:0 0 0;", "class" => "search_form");
				echo form_open("shop/browse/search_product/", $attributes);
				
				if(isset($search_data)){
					form_hidden("search_data", $search_data);
					form_hidden("search_data2", $search_data2);
					form_hidden("category_id_session", $_SESSION['category_id']);
				}
			?>
            <div class="span9 search_border">
            	<input type="text" name="search" placeholder="Search for products" class="search_bar"/>
                <select name="category_id" class="search_categories">
                	<option value="0">-----All Categories-----</option>
                    <?php
						if(is_array($categories)){
							foreach($categories as $cat){
								$name = $cat->category_name;
								$id = $cat->category_id;
								
								echo "<option value='".$id."'>".$name."</option>";
							}	
						}
					?>
                </select>
                <button type="submit" class="search_button"><i class="icon-search icon-2x"></i></button>
            </div>
			<?php echo form_close();?>
        </div>
    </div>
</div>
<!-- end: Header -->
