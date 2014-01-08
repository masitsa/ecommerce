
            <!-- start: Page title -->
            <div class="row-fluid page-title">
                <div class="inner">
                    <div class="page-header" id="shop_page">
                        <h1>Shop page <small><i class="icon-angle-right "></i> all products</small></h1>
                    </div>
                </div>
            </div>
            <!-- end: Page title -->

            <!-- start: Results -->
            <div class="row-fluid shop-result">
                <div class="inner darken clearfix">
                    <div id="products_count">
            			<?php 
							$data['first'] = $first;
							$data['last'] = $last;
							$data['total'] = $total;
							$this->load->view("products_count", $data);
						?>
                    </div>
                    <div id="ordering">
            			<?php 
							$this->load->view("ordering", $data);
						?>
                    </div>
                </div>
            </div>
            <!-- end: Results -->