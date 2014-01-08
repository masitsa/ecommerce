<?php
require_once "./application/modules/site/controllers/site.php";
class Carousel extends Site
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('admin/carousel_model');
	}
	
	/**
	* Build the carousel from uploaded pictures
	*
	*/
	function build_carousel()
	{
		$pics = $this->carousel_model->get_carousel_pictures();
		$no_of_pics = count($pics);
		for($i=0;$i<$no_of_pics;$i++){
			echo('<div id="layerslider" style="width: 100%; height: 400px; margin: 0px auto; "><div class="ls-layer" style="slidedirection: top; slidedelay: 4000; durationin: 1500; durationout: 1500; delayout: 500;">');
            echo('<img src="'.base_url().'assets/carousel/original/'.$pics[$i]->pic_name.'"  class="ls-bg" alt="" style="width: 100%;"">');
            echo('<p class="ls-s2 l3-s1" style="position: absolute; top:240px; left: 50%; slidedirection : bottom; slideoutdirection : left; durationin : 3000; durationout : 750; easingin : easeOutElastic; easingout : easeInBack; delayin : 1000;">');
            echo($pics[$i]->pic_caption);
            echo('</p></div>');
		}
	}
}