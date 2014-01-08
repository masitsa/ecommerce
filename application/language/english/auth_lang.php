<?php
// First set the instance of your CI installation
$this->ci =& get_instance();
/*
* Now load any necessary models and controllers
* These can be accessed using $this->ci->model/controller
*/
$this->ci->load->model('auth/auth_model');

/*
*--------------------------------------------------------------
*			Errors
*--------------------------------------------------------------*/
$lang['auth_login_label_username'] = 'Username';
$lang['auth_login_label_email'] = 'Email Address';
$lang['auth_login_label_both'] = 'Username or Email Address';
$lang['auth_incorrect_credentials'] = 'Incorrect Credentials';
$lang['auth_incorrect_email_or_username'] = 'Login or email doesn\'t exist';
$lang['auth_email_in_use'] = 'This email address already exists in our records. Incase you\'ve forgotten the password, click '.anchor("auth/forgot_password","here").' to reset it';
$lang['auth_username_in_use'] = 'This username already exists. Please choose another username.';
$lang['auth_current_email'] = 'This is your current email';
$lang['auth_bot_detected'] = 'Something went wrong. Please try again later';
$lang['auth_email_not_found'] = 'Sorry, we can not find that email address in our records.';
$lang['admin_user_cannot_be_found'] = 'Sorry, we cannot find that user.';
$lang['admin_user_cannot_be_edited'] = 'Sorry, you cannot edit this user.';
$lang['admin_email_in_use'] = 'This email address already exists. Please choose another address';
$lang['admin_username_in_use'] = 'This username already exists. Please choose another username.';

/*
*--------------------------------------------------------------
*			Notifications
*-------------------------------------------------------------*/
$lang['auth_message_logged_out'] = 'You have been successfully logged out.';
$lang['auth_message_registration_disabled'] = 'Registration is disabled.';
$lang['auth_message_registration_completed_1'] = 'You have successfully registered. Check your email for the activiation link. '.anchor("auth/login","Click here to login");
$lang['auth_message_registration_completed_1_frontend'] = 'You have successfully registered. Check your email for the activiation link. '.anchor("site/auth/login","Click here to login");
$lang['auth_message_registration_completed_2'] = 'You have successfully registered.';
$lang['auth_message_registration_completed_3'] = 'You have successfully registered. Check your email address for further instructions.';
$lang['auth_message_activation_email_sent'] = 'A new activation email has been sent. Follow the instructions in the email to activate your account.';
$lang['auth_message_activation_completed'] = 'Your account has been successfully activated. Click '.anchor("auth/login"," here").' to login';
$lang['auth_message_admin_activation_completed'] = 'The account has been successfully activated. Click '.anchor("auth/login"," here").' to login';
$lang['auth_message_activation_failed'] = 'Your activation key is incorrect or expired. Please check your email again and follow the instructions.';
$lang['auth_message_activation_disallowed'] = 'Account activation failed. Please '.mailto($this->ci->auth_model->get_auth_setting_value('webmaster_email'),'contact the administrator').' for assistance';
$lang['auth_message_password_changed'] = 'Your password has been successfully changed.';
$lang['auth_message_new_password_sent'] = 'An email with instructions for creating a new password has been sent to you.';
$lang['auth_message_new_password_activated'] = 'You have successfully reset your password';
$lang['auth_message_new_password_failed'] = 'Your password reset key is incorrect or expired. Click '.anchor('auth/forgot_password','here').' to resend password reset link';
$lang['auth_message_new_email_sent'] = 'A confirmation email has been sent to %s. Follow the instructions in the email to complete this change of email address.';
$lang['auth_message_new_email_activated'] = 'You have successfully changed your email';
$lang['auth_message_new_email_failed'] = 'Your activation key is incorrect or expired. Please check your email again and follow the instructions.';
$lang['auth_message_banned'] = 'You are banned.';
$lang['auth_message_unregistered'] = 'Your account has been deleted...';
$lang['auth_message_account_not_activated_admin_activate'] = 'Account is not activated. Incase this is not expected, '.mailto($this->ci->auth_model->get_auth_setting_value('webmaster_email'),'contact the administrator');
$lang['auth_message_account_not_activated_self_activate'] = 'Account is not activated. Incase you haven\'t received the activation code, click '.anchor('auth/resend_activate','here').' to resend activation code';
$lang['auth_message_level_not_activated'] = 'Your account level is not activated. Incase this is not expected, '.mailto($this->ci->auth_model->get_auth_setting_value('webmaster_email'),'contact the administrator');
$lang['auth_message_group_not_activated'] = 'Your account group is not activated. Incase this is not expected, '.mailto($this->ci->auth_model->get_auth_setting_value('webmaster_email'),'contact the administrator');
$lang['auth_login_first'] = 'Please Login First In Order To View This Page';

/*
*-------------------------------------------------------------
*			Email subjects
*-------------------------------------------------------------*/
$lang['auth_subject_welcome'] = 'Welcome to %s!';
$lang['auth_subject_activate'] = 'Welcome to %s!';
$lang['auth_subject_forgot_password'] = 'Forgot your password on %s?';
$lang['auth_subject_reset_password'] = 'Your new password on %s';
$lang['auth_subject_change_email'] = 'Your new email address on %s';
$lang['event_subject_accept_bid'] = 'Your bid was accepted';

/* End of file auth_lang.php */
/* Location: ./application/language/english/auth_lang.php */