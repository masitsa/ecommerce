<div class="row-fluid">
	<!-- start: Page section -->
    <section id="page-sidebar" class="span4 pull-left left-col">
    	<div class="page-inner">
			<div class="sub-inner">
				<div class="row-fluid">
					<div id="content">
						<div class="content-bottom">
						
							<?php
								if(!$details->post_page)
									echo $details->page_content_left;
								else
									echo modules::run('site/get_page_column_posts',$details->page_uuid,'Left');
							?>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- end: Page section -->
	
	<!-- start: Page section -->
    <section id="page-sidebar" class="span4 mid-col">
    	<div class="page-inner">
			<div class="sub-inner">
				<div class="row-fluid">
					<div id="content">
						<div class="content-bottom">
						
							<?php
								if(!$details->post_page)
									echo $details->page_content_middle;
								else
									echo modules::run('site/get_page_column_posts',$details->page_uuid,'Middle');
							?>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- end: Page section -->
	
	<!-- start: Page section -->
    <section id="page-sidebar" class="span4 pull-right right-col">
    	<div class="page-inner">
			<div class="sub-inner">
				<div class="row-fluid">
					<div id="content">
						<div class="content-bottom">
						
							<?php
								if(!$details->post_page)
									echo $details->page_content_right;
								else
									echo modules::run('site/get_page_column_posts',$details->page_uuid,'Right');
							?>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- end: Page section -->
</div>