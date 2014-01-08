<?php $comments = $this->blog_model->get_post_comments($post_uuid); ?>
<h3><?php echo count($comments); ?> Comments</h3>
<div class="page-inner">
    <div class="comments" id="comments">
        <ul class="comments-list">
        	<?php
			if($comments)	{
				foreach($comments as $comment) {
					echo('<div class="comment clearfix">');
		                echo('<img src="'.base_url().'img/misty/avatar.png'.'" alt="avatar" class="avatar"/>');
		                echo('<p class="meta">'.$comment->commenter_name.'<br/><small>'.date('jS M Y',strtotime($comment->date_submitted)).'</small></p>');
		                echo('<div class="textarea">');
		                    echo('<p>'.$comment->comment_text.'</p>');
		                echo('</div>');
		            echo('</div>');
				}
			}
			?>
        </ul>
    </div>
</div>