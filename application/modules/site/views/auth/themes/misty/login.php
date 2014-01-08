<!-- start: Page header / Breadcrumbs -->
<section class="breadcrumbs">
	<div class="breadcrumbs">
		You are here: <a href="<?php echo base_url().'site/home';?>">Home</a><i class="icon-angle-right "></i><?php echo anchor('site/auth/login','Login'); ?>
	</div>
</section>
<!-- end: Page header / Breadcrumbs -->
<div class="row">
	<!-- Login starts -->
	<section id="page-sidebar" class="span4 offset4">
		<?php echo form_open($this->uri->uri_string(),"class='form-signin'"); ?>
			<h2 class="form-signin-heading">Login</h2>
			<div class="error">
				<?php echo form_error('login'); ?>
				<?php echo form_error('password'); ?>
				<?php echo isset($error)?$error:''; ?>
			</div>
			<input name="login" type="text" class="input-block-level" placeholder="<?php echo $login_label; ?>">
			<input name="password" class="input-block-level" type="password"  placeholder="Password">
			<label class="checkbox"><input name="remember_me" type="checkbox" class=input-block-level" value="TRUE"/>Remember Me</label>
			<button type="submit" class="btn btn-large btn-primary">Login</button>
			
			<?php if($this->auth_model->get_auth_setting_value('facebook_connect_active') == 1 && isset($facebook_login_url)){
				echo anchor($facebook_login_url,img(array('src'=>base_url().'/img/fb-connect-large.png')), array('title' => 'Login Using facebook','class'=>'input-block-level fb-login-button'));
			} ?>
			<?php 
			if($this->auth_model->get_auth_setting_value('allow_registration')) { ?>
			<div>
				Don't Have An Account? <?php echo anchor('site/auth/register','Register'); ?>
			</div>
			<?php } ?>
		<?php echo form_close(); ?>
	</section>            
	<!-- Login ends -->
</div>