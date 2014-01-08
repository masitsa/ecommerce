<!DOCTYPE html>
<html>
	<head><title>Welcome to our world!</title></head>
	<body>
		<div style="max-width: 800px; margin: 0; padding: 10px 0;">
			<table width="80%" border="0" cellpadding="0" cellspacing="0" style="font: 13px/18px Arial, Helvetica, sans-serif;">
				<tr>
					<td width="5%"></td>
					<td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
					<h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;">Welcome to our world!</h2>
					Hi <?php echo $fullname; ?>,
					Thank you for joining us. Your account under user name <b><?php echo $username; ?></b> has been activated<br/>
					Please <?php echo anchor('auth/login',' click here ');?> to login.<br/>
					<br/>
					<br/>
					Thank you for using the system.
					<br/>
					<br/>
					The IT Team.
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>