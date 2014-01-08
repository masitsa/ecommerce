
        <!-- start: Page section -->
        <section class="span12 page-sidebar">

            <!-- start: Hero unit -->
            	<?php 
					$data['categories'] = $categories;
					
					$this->load->view("home/slideshow", $data);
					$this->load->view("home/featured");
					$this->load->view("home/recommended");
					$this->load->view("home/top_categories");
					$this->load->view("home/reviews");
					$this->load->view("home/blog");
				?>
            <!-- end: Hero unit -->

        </section>
        <!-- end: Page section -->