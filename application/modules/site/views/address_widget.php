<?php $details = $this->site_model->get_contact_details(); ?>
<address>
	<ul class="icons">
		<li><i class="icon-map-marker"></i><?php echo $details['street_address']; ?></li>
		<li><i class="icon-phone"></i><?php echo $details['phone_number']; ?></li>
		<li><i class="icon-print"></i><?php echo $details['fax_number']; ?></li>
		<li><i class="icon-envelope"></i><a href="mailto:<?php echo $details['contact_email']; ?>"><?php echo $details['contact_email']; ?></a></li>
	</ul>
</address>