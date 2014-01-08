<?php $this->load->view('includes/header_login'); 
if(!isset($key))
	$key = $this->uri->segment(3);
?>
<?php if(isset($new_password)) { ?>
	<div class="container">
		<?php echo form_open($this->uri->uri_string(),"class='form-signin'"); ?>
			<h2 class="form-signin-heading">Change Password</h2>
			<div class="error">
				<p>
					<?php echo form_error('password'); ?>
					<?php echo form_error('confirm_password'); ?>
					<?php echo isset($error)?$error:''; ?>
				</p>
			</div>
			<div class="message">
				<?php echo isset($message)?$message:''; ?>
			</div>
			<input name="password" type="password" class="input-block-level" placeholder="New Password">
			<input name="confirm_password" type="password" class="input-block-level" placeholder="Confirm Password">
			<button class="btn btn-large btn-primary" type="submit">Change</button>
		<?php echo form_close();?>
	</div>
<?php } elseif(isset($resend_activation)) { ?>
	<div class="container">
		<?php echo form_open($this->uri->uri_string(),"class='form-signin'"); ?>
			<h2 class="form-signin-heading">Resend Activation Link</h2>
			<p>Your account doesnt seem to be activated. Please enter your email address to resend the activation link.</p>
			<div class="error">
				<p>
					<?php echo form_error('email'); ?>
					<?php echo isset($error)?$error:''; ?>
				</p>
			</div>
			<input name="email" type="text" class="input-block-level" placeholder="Email Address"/>
			<button class="btn btn-large btn-primary" type="submit">Send</button>
		<?php echo form_close();?>
	</div>
<?php } elseif(isset($forgot_password)) { ?>
	<?php echo form_open($this->uri->uri_string(),"class='form-signin'"); ?>
		<h2 class="form-signin-heading">Forgot Password</h2>
		<p>Forgot your password? No problem, just enter your email address below and we'll recover it for you.</p>
		<div class="error">
			<p>
				<?php echo form_error('email'); ?>
				<?php echo isset($error)?$error:''; ?>
			</p>
		</div>
		<input name="email" type="text" class="input-block-level" placeholder="Email Address"/>
		<button class="btn btn-large btn-primary" type="submit">Recover</button>
	<?php echo form_close(); ?>
<?php } else { ?>
	<div class="container">
		<?php echo form_open('auth/login',"class='form-signin'"); ?>
			<h2 class="form-signin-heading">CMS Admin Login</h2>
			<?php if($this->auth_model->get_auth_setting_value('allow_registration')) { ?>
				<p>No Account? <a href="<?php echo base_url();?>auth/register">Sign Up</a></p>
			<?php } ?>
			<div class="error">
				<p>
					<?php echo form_error('login'); ?>
					<?php echo form_error('password'); ?>
					<?php echo isset($error)?$error:''; ?>
				</p>
			</div>
			<div class="message">
				<?php echo isset($message)?$message:''; ?>
			</div>
			<input name="login" type="text" class="input-block-level" placeholder="<?php echo $login_label; ?>"/>
			<input name="password" type="password" class="input-block-level" placeholder="Password"/>
			<label class="checkbox"><input name="remember_me" type="checkbox" class=input-block-level" value="TRUE"/>Remember Me</label>
			<span class="help-block">
				<a href="<?php echo base_url();?>auth/forgot_password">Forgot Password?</a>&nbsp;&nbsp;
			</span>
			<button class="btn btn-large btn-primary" type="submit">Sign in</button>
		<?php echo form_close(); ?>
	</div>
<?php } ?>