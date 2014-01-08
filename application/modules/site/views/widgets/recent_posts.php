<aside>
	<section class="widget popular-posts">
		<h3 class="widget-title">Recent Posts</h3>
			<?php 
			$items = $this->blog_model->get_latest_posts();
			foreach($items as $item){
				$content = substr(preg_replace('/<([^>]+)>/','',$item->post_content),0,40);
				echo("<div class='widget-inner'><div class='media'><div class='media-body'>");
				echo("<h4 class='media-heading'>".anchor('blog/post/'.$item->post_url,$item->post_title)."</h4>");
				echo("<small><em> By ".$item->fullname."</em> on ".date('jS M Y',strtotime($item->submitted))."</small><br\>");
				echo("<p>".$content."... ".anchor("blog/post/$item->post_url",'read more')."</p>");
				echo("</div></div></div>");
			}
			?>
	</section>
</aside>