
<?php

	if($last > 0){
?>
                    <div class="span6 result-ordering">
                        <div class="pull-right">
                            <select id="sorting" class="sort_order">
                                <option value="product_name">Sort By</option>
                                <option value="product_name">Default sorting</option>
                                <option value="product_rating">Sort by popularity</option>
                                <option value="product_rating">Sort by average rating</option>
                                <option value="product_date">Sort by newness</option>
                                <option value="product_selling_price">Sort by price: low to high</option>
                                <option value="product_selling_price2">Sort by price: high to low</option>
                            </select>
                        </div>
                    </div>
<?php
	}
?>