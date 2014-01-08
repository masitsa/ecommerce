<!DOCTYPE html>
<html>
	<head><title>Hi <?php echo $fullname; ?>!</title></head>
	<body>
		<div style="max-width: 800px; margin: 0; padding: 10px 0;">
			<table width="80%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="5%"></td>
					<td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
					Hi <?php echo $fullname; ?>,<br/><br/>
					Thank you for registering with us. Below is the account activation code:
					Please <?php echo anchor("auth/email_activate/".$user_key, "click here");?> to activate your account.<br />
					Link doesn't work? Copy and paste this link into your browser address bar:<br/>
					<a href="<?php echo base_url().'auth/email_activate/'.$user_key; ?>" ><?php echo base_url().'auth/email_activate/'.$user_key; ?></a>
					<br />
					<br />
					Thank you for using the system.
					<br />
					<br />
					The IT Team.
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>