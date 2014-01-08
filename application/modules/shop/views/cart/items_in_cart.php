<?php
	if(is_array($currency)){
		foreach($currency as $cur){
			$name = $cur->symbol;
			if(($name == NULL) || (empty($name))){
				$name = $cur->acronym;
			}
		}
	}
	else{
		$name = "ss";
	}
?>
<span class="quantity"><?php echo $total_cart_items;?></span>
<span class="amount"><i class="icon-shopping-cart"></i><?php echo $name." ".$this->cart->format_number($total_cost);?></span>