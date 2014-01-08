<aside id="sidebar" class="pull-right span4">
	<section class="widget">
		<h3 class="widget-title">Post Archives</h3>
		<div id="accordion2" class="accordion">
			<?php 
			$months = $this->blog_model->get_post_months();
			$no_of_months = count($months);
			for($i=0;$i<$no_of_months;$i++) {
				$posts = $this->blog_model->get_posts_in_month($months[$i]);
				echo('<div class="accordion-group">');
				echo('<div class="accordion-heading">');
				echo('<a href="#collapse'.$i.'" data-parent="#accordion2" data-toggle="collapse" class="accordion-toggle">');
				echo('<i class="icon-minus"></i>'.$months[$i].'</a></div>');
				echo('<div class="accordion-body collapse" id="collapse'.$i.'">');
				echo('<div class="accordion-inner">');
				echo('<ul class="icons">');
				foreach($posts as $post)
					echo('<li><i class="icon-chevron-right"></i>'.anchor("blog/post/$post->post_url",$post->post_title).'</li>');
				echo('</ul></div></div></div>');
			}
			?>
		</div>
	</section>
</aside>