<?php 
if(!$this->auth_model->get_setting_value('signup'))
	redirect('auth/login');
?>
<?php
$username = array('name'=>'username','id'=>'username','value'=>set_value('username'),'class'=>'input-block-level','placeholder'=>'Username');
$fullname = array('name'=>'fullname','id'=>'fullname','value'=>set_value('fullname'),'class'=>'input-block-level','placeholder'=>'Fullname');
$email = array('name'=>'email','id'=>'email','value'=>set_value('email'),'class'=>'input-block-level','maxlength'=>80,'placeholder'=>'Email Address');
$password = array('name'=>'password','id'=>'password','class'=>'input-block-level','placeholder'=>'Password');
$confirm_password = array('name'=>'confirm_password','id'=>'confirm_password','class'=>'input-block-level','value'=>'','placeholder'=>'Confirm Password');
$bot_honey = array('name'=>'bot_honey','id'=>'bot_honey','class'=>'bot-honey','type'=>'bot','placeholder'=>'Leave Blank');
?>
<!-- start: Page header / Breadcrumbs -->
<section class="breadcrumbs">
	<div class="breadcrumbs">
		You are here: <a href="<?php echo base_url().'site/home';?>">Home</a><i class="icon-angle-right "></i><?php echo anchor('site/auth/register','Register'); ?>
	</div>
</section>
<!-- end: Page header / Breadcrumbs -->
<div class="row">
	<!-- Login starts -->
	<section id="page-sidebar" class="span4 offset4">
		<?php echo form_open('site/auth/register',"class='form-signin'"); ?>
	<?php if(isset($message) && strlen($message)>0) { ?>
		<h2 class="form-signin-heading">Status</h2>
		<div class="message">
			<p><?php echo $message; ?></p>
		</div>
	<?php } else { ?>
        <h2 class="form-signin-heading">Register</h2>
		<p>
			Fill in the form below to register on the system
		</p>
		<div class="error">
			<?php
			echo form_error('username');
			echo form_error('fullname');
			echo form_error('email');
			echo form_error('password');
			echo form_error('confirm_password');
			echo isset($error)?$error:'';
			echo isset($username_error)?$username_error:'';
			echo isset($email_error)?$email_error:'';
			?>
		</div>
        <?php echo form_input($username); ?>
        <?php echo form_input($fullname); ?>
        <?php echo form_input($email); ?>
        <?php echo form_password($password); ?>
        <?php echo form_password($confirm_password); ?>
		<?php echo form_input($bot_honey); ?>
        <button class="btn btn-large btn-primary" type="submit">Register</button>
		<div>
			Already Have An Account? <?php echo anchor('site/auth/login','Login'); ?>
		</div>
      <?php echo form_close(); ?>
	  <?php } ?>
	</section>            
	<!-- Login ends -->
</div>