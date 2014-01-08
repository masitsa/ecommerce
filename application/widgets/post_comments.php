<?php
class Post_comments extends Widget
{
	public function display($post_uuid)
	{
		$data['post_uuid'] = $post_uuid;
		$this->load->view('widgets/post_comments',$data);
	}
}