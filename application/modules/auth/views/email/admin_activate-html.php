<!DOCTYPE html>
<html>
	<head><title>Hi Admin!</title></head>
	<body>
		<div style="max-width: 800px; margin: 0; padding: 10px 0;">
			<table width="80%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="5%"></td>
					<td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
					Hi Admin,<br/><br/>
					This is to inform you that <?php echo $fullname; ?> has registered on the system.<br />
					Please <?php echo anchor('auth/admin_email_activate/'.$user_key,' click here ');?> to activate their account.<br />
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