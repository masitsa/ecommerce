<?php
class Admin_footer extends Widget 
{
    public function display()
	{
		$data['theme'] = $this->auth_model->get_auth_setting_value('admin_theme');
        $this->view("themes/".$data['theme']."/widgets/footer",$data);
    }
}