<?php
class Admin_header extends Widget 
{
    public function display()
	{
		$data['site_name'] = $this->auth_model->get_site_name();
		$data['theme'] = $this->auth_model->get_auth_setting_value('admin_theme');
        $this->view("themes/".$data['theme']."/widgets/header",$data);
    }
}