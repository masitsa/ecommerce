<div class="row">

	<section class="pull-left span8" id="page-sidebar">
	
		<!-- start: posts-->
		<?php foreach($posts as $post) { ?>
		<article class="blog-post">
			<h3 class="post-title"><?php echo anchor("blog/post/$post->post_url",$post->post_title); ?></h3>
			<div class="blog-post-inner">
				<div class="post-content">
					<div class="row-fluid">
						<ul class="post-meta">
							<li>
								<a class="post-meta-date" href="#">
								<span class="line1"><?php echo date('jS M Y',strtotime($post->submitted)); ?></span>
								</a>
							</li>
							<li><span class="post-meta-label"><i class="icon-user"></i>:</span> <a href="#"><?php echo $post->fullname; ?></a></li>
						</ul>
						<blockquote><?php echo $content = substr($post->post_content,0,500).' ... '.anchor("blog/post/$post->post_url",'read more'); ?></blockquote>
					</div>
				</div>
			</div>
		</article>
		<?php } ?>
		<!-- end: posts-->
		
		<div class="pagination pagination-centered"><?php echo $this->pagination->create_links(); ?></div>
		
	</section>
	
	<?php echo $this->template->widget("post_archives"); ?>
	
</div>