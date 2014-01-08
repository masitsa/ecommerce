<?php

class Shop extends MX_Controller {   

	public function __construct() {
        parent:: __construct();
		
		redirect('shop/browse/');
    }
}