<?php
class Site_navigation extends Widget 
{
    public function display()
	{
		$data['site_name'] = $this->site_model->get_site_name();
        $this->view('widgets/navigation',$data);
    }
}