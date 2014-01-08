<?php
class Recent_posts extends Widget
{
	public function display()
	{
		$this->load->model('blog_model');
		$this->load->view('widgets/recent_posts');
	}
}