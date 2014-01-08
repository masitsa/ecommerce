<?php

/*
	-----------------------------------------------------------------------------------------
	Retrieve top footer advert
	-----------------------------------------------------------------------------------------
*/

$table = "advert"; 
$where = "ad_position_id = 3";
$items = "advert_poster";
$order = "advert_poster";
$poster = $this->order_model->select_entries_where($table, $where, $items, $order);

if(count($poster) > 0){
	foreach($poster as $pos){
		$advert_poster2 = base_url().'assets/adverts/images/'.$pos->advert_poster;
	}
}
else{
	$advert_poster2 = '';
}

/*
	-----------------------------------------------------------------------------------------
	Retrieve bottom right footer advert
	-----------------------------------------------------------------------------------------
*/

$table = "advert"; 
$where = "ad_position_id = 4";
$items = "advert_poster";
$order = "advert_poster";
$poster = $this->order_model->select_entries_where($table, $where, $items, $order);

if(count($poster) > 0){
	foreach($poster as $pos){
		$advert_poster3 = base_url().'assets/adverts/images/'.$pos->advert_poster;
	}
}
else{
	$advert_poster3 = '';
}
?>
</div>

    </div>
</div>
<!-- end: Container -->

<div id="bonus-line">
    <div class="container">
        <div class="row-fluid">
            <div class="span4 bonus1">
                <p class="social-icons">
                    <span><a href="#" rel="tooltip" data-placement="top" data-original-title="Google+"><i class="icon-google-plus"></i></a></span>
                    <span><a href="#" rel="tooltip" data-placement="top" data-original-title="Facebook"><i class="icon-facebook"></i></a></span>
                    <span><a href="#" rel="tooltip" data-placement="top" data-original-title="Twitter"><i class="icon-twitter"></i></a></span>
                </p>
            </div>
            <div class="span4 bonus2"><!-- NEW! BONUS GIFT CARDS --> <a href="<?php echo $advert_poster2; ?>" class="image" rel="prettyPhoto[product]"><img src="<?php echo $advert_poster2; ?>" alt="" /></a></div>
            <div class="span4 bonus3">Tel.: +1 (123) 12-12-123</div>
        </div>
    </div>
</div>

<!-- start: Footer -->
<footer id="footer">
    <div class="container">
        <div class="row-fluid">
            <div class="span3 clearfix">
                <h3 class="widget-title"><span class="text-info">Our</span> Offers</h3>
                <div class="widget-inner">
                    <ul class="unstyled">
                        <li><a href="#">New products</a></li>
                        <li><a href="#">Top sellers</a></li>
                        <li><a href="#">Specials</a></li>
                        <li><a href="#">Manufacturers</a></li>
                        <li><a href="#">Suppliers</a></li>
                        <li><a href="#">Customer Service</a></li>
                    </ul>
                </div>
            </div>
            <div class="span3 clearfix">
                <h3 class="widget-title"><span class="text-info">Shipping</span> Info</h3>
                <div class="widget-inner">
                    <ul class="unstyled">
                        <li><a href="#">New products</a></li>
                        <li><a href="#">Top sellers</a></li>
                        <li><a href="#">Specials</a></li>
                        <li><a href="#">Manufacturers</a></li>
                        <li><a href="#">Suppliers</a></li>
                        <li><a href="#">Customer Service</a></li>
                    </ul>
                </div>
            </div>

            <div class="span3 clearfix">
                <h3 class="widget-title"><span class="text-info">Latest</span> Tweets</h3>
                <!-- start: Twitter Widget -->
                <div class="widget-inner">
                    <div class="twitter"></div>
                </div>
                <!-- end: Twitter Widget -->
            </div>

            <div class="span3 clearfix">
               <a href="<?php echo $advert_poster3; ?>" class="image" rel="prettyPhoto[product]"><img src="<?php echo $advert_poster3; ?>" alt="" /></a>
            </div>
        </div>
    </div>
</footer>
<!-- end: Footer -->

<!-- start: Footer menu -->
<section id="footer-menu">
    <div class="container">
        <div class="row-fluid">
            <div class="span6">
                <ul class="privacy inline">
                    <li><a href="#">Conditions of Use</a></li>
                    <li><a href="#">Privacy Notice</a></li>
                    <li><a href="#">Contact Us</a></li>
                </ul>
                <p class="copyright">&copy; Copyright 2012. Powered by <a href="http://dxthemes.com/">DX Themes</a>.</p>
            </div>
            <div class="span6 payment">
                <img src="<?php echo base_url()."img/cards/visa_straight.png";?>" alt=""/>
                <img src="<?php echo base_url()."img/cards/paypal.png";?>" alt=""/>
                <img src="<?php echo base_url()."img/cards/discover.png";?>" alt=""/>
                <img src="<?php echo base_url()."img/cards/aex.png";?>" alt=""/>
                <img src="<?php echo base_url()."img/cards/maestro.png";?>" alt=""/>
                <img src="<?php echo base_url()."img/cards/mastercard.png";?>" alt=""/>
            </div>
        </div>
    </div>
</section>
<!-- end: Footer menu -->

<!-- Javascript placed at the end of the document so the pages load faster -->
<script type="text/javascript" src="<?php echo base_url()."js/cart/jquery.min.js";?>"></script>
<script type="text/javascript" src="<?php echo base_url()."js/cart/bootstrap.min.js";?>"></script>
<script type="text/javascript" src="<?php echo base_url()."js/cart/jasny-bootstrap.min.js";?>"></script>
<script type="text/javascript" src="<?php echo base_url()."js/cart/jquery-migrate-1.1.1.js";?>"></script>
<script type="text/javascript" src="<?php echo base_url()."js/cart/jquery.easing.1.3.js";?>"></script>
<script type="text/javascript" src="<?php echo base_url()."js/cart/superfish.js";?>"></script>
<script type="text/javascript" src="<?php echo base_url()."js/cart/hoverIntent.js";?>"></script>
<script type="text/javascript" src="<?php echo base_url()."js/cart/jquery.flexslider.js";?>"></script>
<script type="text/javascript" src="<?php echo base_url()."js/cart/jflickrfeed.min.js";?>"></script>
<script type="text/javascript" src="<?php echo base_url()."js/cart/jquery.prettyPhoto.js";?>"></script>
<script type="text/javascript" src="<?php echo base_url()."js/cart/jquery.elastislide.js";?>"></script>
<script type="text/javascript" src="<?php echo base_url()."js/cart/jquery.tweet.js";?>"></script>
<script type="text/javascript" src="<?php echo base_url()."js/cart/smoothscroll.js";?>"></script>
<script type="text/javascript" src="<?php echo base_url()."js/cart/jquery.ui.totop.js";?>"></script>
<script type="text/javascript" src="<?php echo base_url()."js/cart/ajax-mail.js";?>"></script>
<script type="text/javascript" src="<?php echo base_url()."js/cart/main.js";?>"></script>
<script type="text/javascript" src="<?php echo base_url()."js/cart/cart.js";?>"></script>
<script type="text/javascript" src="<?php echo base_url()."js/script.js";?>" id="loader" ></script>
<script type="text/javascript" src="<?php echo base_url()."js/modernizr.custom.js";?>"></script>
<script type="text/javascript" src="<?php echo base_url()."js/jquery.dlmenu.js";?>"></script>
<script type="text/javascript" src="<?php echo base_url()."js/jquery.catslider.js";?>"></script>
<script type="text/javascript" src="<?php echo base_url()."js/jquerypp.custom.js";?>"></script>
<script type="text/javascript" src="<?php echo base_url()."js/jquery.elastislide.js";?>"></script>
<script type="text/javascript" src="<?php echo base_url()."js/sequence.jquery-min.js";?>"></script>
<script type="text/javascript" src="<?php echo base_url()."js/sequencejs-options.js";?>"></script>
<script type="text/javascript" src="<?php echo base_url()."js/jquery.countdown.js";?>"></script>
<script>
	$(function() {
			$( '#dl-menu' ).dlmenu();
			$( '#mi-slider' ).catslider();
			$( '#carousel' ).elastislide();
			
			var note = $('#note'),
			ts = new Date(2014, 0, 8),
			newYear = true;
		
			if((new Date()) > ts){
				// The new year is here! Count towards something else.
				// Notice the *1000 at the end - time must be in milliseconds
				ts = (new Date()).getTime() + 10*24*60*60*1000;
				newYear = false;
			}
				
			$('#countdown').countdown({
				timestamp	: ts,
				callback	: function(days, hours, minutes, seconds){
					
					var message = "";
					
					message += days + " day" + ( days==1 ? '':'s' ) + ", ";
					message += hours + " hour" + ( hours==1 ? '':'s' ) + ", ";
					message += minutes + " minute" + ( minutes==1 ? '':'s' ) + " and ";
					message += seconds + " second" + ( seconds==1 ? '':'s' ) + " <br />";
					
					if(newYear){
						message += "left until the new year!";
					}
					else {
						message += "left to 10 days from now!";
					}
					
					note.html(message);
				}
			});
		}
	);
	
	/*Currency dropdown*/
	function DropDown(el) {
		this.dd = el;
		this.placeholder = this.dd.children('span');
		this.opts = this.dd.find('ul.dropdown > li');
		this.val = '';
		this.index = -1;
		this.initEvents();
	}
	DropDown.prototype = {
		initEvents : function() {
			var obj = this;

			obj.dd.on('click', function(event){
				$(this).toggleClass('active');
				return false;
			});

			obj.opts.on('click',function(){
				var opt = $(this);
				obj.val = opt.text();
				obj.index = opt.index();
				obj.placeholder.text(obj.val);
			});
		},
		getValue : function() {
			return this.val;
		},
		getIndex : function() {
			return this.index;
		}
	}

	$(function() {

		var dd = new DropDown( $('#dd') );

		$(document).click(function() {
			// all dropdowns
			$('.wrapper-dropdown-3').removeClass('active');
		});

	});
</script>

</body>
</html>
