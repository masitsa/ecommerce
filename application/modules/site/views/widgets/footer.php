	<!-- start: Footer -->
	<footer id="footer">
	    <div class="container">
	        <div class="row">
	            <div class="span4">
	                <h3 class="widget-title">Address</h3>
	                <div class="widget-inner">
	                    <?php $this->load->view('address_widget'); ?>
	                    <p><a href="<?php echo base_url().'site/contact';?>" class="btn btn-primary">Get in Touch</a></p>
	                </div>
	                <h3 class="widget-title">Follow Us</h3>
	                <div class="widget-inner">
	                    <ul class="social clearfix">
	                        <li><a href="#" data-original-title="Facebook" data-placement="top" rel="tooltip">
	                            <i class="icon-facebook-sign"></i>
	                        </a></li>
	                        <li><a href="#" data-original-title="Twitter" data-placement="top" rel="tooltip">
	                            <i class="icon-twitter-sign"></i>
	                        </a></li>
	                        <li><a href="#" data-original-title="Linkedin" data-placement="top" rel="tooltip">
	                            <i class="icon-linkedin-sign"></i>
	                        </a></li>
	                        <li><a href="#" data-original-title="Pinterest" data-placement="top" rel="tooltip">
	                            <i class="icon-pinterest-sign"></i>
	                        </a></li>
	                        <li><a href="#" data-original-title="Google+" data-placement="top" rel="tooltip">
	                            <i class="icon-google-plus-sign"></i>
	                        </a></li>
	                    </ul>
	                </div>
	            </div>
	            <div class="span4">
	                <h3 class="widget-title">Connect On Facebook</h3>
	                <div class="widget-inner">
					<div id="fb-root"></div>
					<script>(function(d, s, id) {
					  var js, fjs = d.getElementsByTagName(s)[0];
					  if (d.getElementById(id)) return;
					  js = d.createElement(s); js.id = id;
					  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=239471786112742";
					  fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));
					</script>
	                    <p>Like Facebook</p>
	                    <fb:like href="https://www.facebook.com/facebook" send="true" width="100%" show_faces="true" font="segoe ui"></fb:like>
	                </div>
	            </div>
	            <div class="span4">
	                <div class="widget-inner">
						<a class="twitter-timeline" href="https://twitter.com/twitter" data-widget-id="314360968081702912" data-chrome="nofooter noborders transparent" height="300">Tweets by @twitter</a>
						<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
	                </div>
	            </div>
	        </div>
	    </div>
	</footer>
	<!-- end: Footer -->

	<!-- start: Footer menu-->
	<section id="footer-menu">
	    <div class="container">
	        <div class="row">
	            <div class="span4">
	                <p class="copyright">&copy; Copyright 2013 &middot; Your Company</p>
	            </div>
	        </div>
	    </div>
	</section>
	<!-- end: Footer menu-->