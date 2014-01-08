<?php
class Error_header extends Widget 
{
    public function display()
	{
		$data['site_name'] = $this->site_model->get_site_name();
        $this->view('widgets/error_header',$data);
    }
}