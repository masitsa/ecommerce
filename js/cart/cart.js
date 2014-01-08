// JavaScript Document
/*************************************
*
*Link to the application
*
**************************************/
var link = $('#baseurl').val();
	
	
	/*************************************
	*
	*Add to cart
	*
	**************************************/
	$(document).on("submit","ul.thumbnails form",function(){
		
		//get the product id
		var id = $(this).find('input[name=product_id]').val();
		var qty = $(this).find('input[name=quantity]').val();
		
		//send the data to the php processing function
		$.post(link + "shop/cart/add_cart_item3", { product_id: id, quantity: qty, ajax: '1' },
  			function(data){
  			
  			if(data == 'true'){
    			$.get(link + "shop/cart/show_cart", function(cart){
  					$("#cart_content").html(cart);
				});
    			
    			$.get(link + "shop/cart/get_cart", function(cart){
					$("#cart_content2").html(cart);
					$("#cart_content3").html(cart);
				});

    		}else{
    			alert("Product does not exist");
    		}	
    		
 		 }); 

		return false;
	});
	
	$(".empty").live("click", function(){
    	$.get(link + "shop/cart/empty_cart", function(){
    		$.get(link + "shop/cart/show_cart", function(cart){
  				$("#cart_content").html(cart);
			});
		});
		
		return false;
    });
	
	/*************************************
	*
	*Update cart quantity
	*
	**************************************/
	$(document).on("submit","table.table form",function(){
		//get the product id
		var id = $(this).find('input[name=order_item]').val();
		var qty = $(this).find('input[name=quantity]').val();
		//qty = parseInt(qty);
		if(isNaN(qty) == false){
			//qty = 1;
		}
		//alert(link + "cart/update_cart3/"+id+"/"+qty);
		//send the data to the php processing function
		$.post(link + "shop/cart/update_cart3", { order_item: id, quantity: qty, ajax: '1' },
  			function(data){
			
  			if(data == 'true'){
    			
    			$.get(link + "shop/cart/get_cart2", function(cart){
					$("#cart_content4").html(cart);
				});
    			
    			$.get(link + "shop/cart/get_cart", function(cart){
					$("#cart_content2").html(cart);
					$("#cart_content3").html(cart);
				});
				
    			$.get(link + "shop/cart/show_cart", function(cart){
  					$("#cart_content").html(cart);
				});

    		}else{
    			alert("Product does not exist");
    		}	
    		
 		 }); 

		return false;
	});
	
	/*************************************
	*
	*Delete cart item
	*
	**************************************/
	
	$(document).on("click","button.delete",function(){
		
		var order_item_id = $(this).val();
		
		//send the data to the php processing function
		$.post(link + "shop/cart/delete_item3/"+order_item_id,
  			function(data){
			
  			if(data == 'true'){
    			
    			$.get(link + "shop/cart/get_cart2", function(cart){
					$("#cart_content4").html(cart);
				});
    			
    			$.get(link + "shop/cart/get_cart", function(cart){
					$("#cart_content2").html(cart);
					$("#cart_content3").html(cart);
				});
				
    			$.get(link + "shop/cart/show_cart", function(cart){
  					$("#cart_content").html(cart);
				});

    		}else{
    			alert("Product does not exist");
    		}	
    		
 		 }); 

		return false;
	});
	
	/*************************************
	*
	*Onmouseover functions
	*
	**************************************/
	$("ul.category form").hover(function() {
		//get the product id
		//var category_id2 = $(this).find('input[name=category_id]').val();
		
		//alert(link);
		//send the data to the php processing function
		$.post(link + "products/get_category_image/", { category_id: category_id2 },
  			function(data){
			
  				$("#cat"+category_id2).html('<li><img src="'+data+'"></li>');	
    		
 		 }); 

		return false;
	});
	
	/*************************************
	*
	*Add review
	*
	**************************************/
	
	$("div.add-review form").submit(function() {
		//get the product id
		var reviewer2 = $(this).find('input[name=reviewer]').val();
		var review2 = $(this).find('textarea[name=review]').val();
		var email2 = $(this).find('input[name=email]').val();
		var rating2 = $(this).find('select[name=rating]').val();
		var product_id2 = $(this).find('input[name=product_id]').val();
		
		//alert(link + "shop/browse/add_review/"+reviewer2+"/"+review2+"/"+email2+"/"+rating2+"/"+product_id2);
		//send the data to the php processing function
		$.post(link + "shop/browse/add_review/", { reviewer: reviewer2, review: review2, email: email2 , rating: rating2 , product_id: product_id2 , ajax: '1' },
  			function(data){
			
  			if(data == 'true'){
    			
    			$.get(link + "shop/browse/get_reviews/" + product_id2, function(cart){
					$("#reviews").html(cart);
				});

    		}else{
    			alert("Product does not exist ");
    		}	
    		
 		 }); 

		return false;
	});
	
	/*************************************
	*
	*Sort products by category
	*
	**************************************/
	
	function alert_here(category_id)
	{
		
		var box1; 
		box1 = new ajaxLoader(".box-1");
	
		/* get the category id */
		
		var page = 0;
    	$.get(link + "shop/browse/get_top/" + category_id + "/" + page, function(top){
			$("#top").fadeOut("slow");
			$("#top").html(top);
			$("#top").fadeIn("slow");
		});
		
		$.post(link + "shop/browse/remove_brand2/",
  			function(data){
			
  				if(data == 'true'){
					//
					//reorder products
    				$.get(link + "shop/browse/sort_products/" + category_id + "/" + page, function(products){
						$("#products").fadeOut("slow");
						$("#products").html(products);
						$("#products").fadeIn("slow");
					});
					
    				$.get(link + "shop/browse/sort_crumbs2/" + category_id, function(crumbs){
						$("#crumbs").fadeOut("slow");
						$("#crumbs").html(crumbs);
						$("#crumbs").fadeIn("slow");
					});
    		
					//reorder pagination
    				$.get(link + "shop/browse/new_pagination/" + category_id + "/" + page, function(pagination){
						$("#pagination").fadeOut("slow");
						$("#pagination").html(pagination);
						$("#pagination").fadeIn("slow");
					});
    		
					//reorder products
    				$.get(link + "shop/browse/products_count/" + category_id + "/" + page, function(products_count){
						$("#products_count").fadeOut("slow");
						$("#products_count").html(products_count);
						$("#products_count").fadeIn("slow");
					});
    		
					//retrieve brands
    				$.get(link + "shop/browse/select_brands/" + category_id, function(brands){
						//alert(brands);
						$("#brands_list").fadeOut("slow");
						$("#brands_list").html(brands);
						$("#brands_list").fadeIn("slow");
					});
    		
					//reorder products
    				$.get(link + "shop/browse/get_ordering/" + category_id + "/" + page, function(ordering){
						$("#ordering").fadeOut("slow");
						$("#ordering").html(ordering);
						$("#ordering").fadeIn("slow");
					});
    			
    				$("#shop_page").fadeOut("slow");
					$("#shop_page").html('<h1>Shop page <small><i class="icon-angle-right "></i> all products</small></h1>');
					$("#shop_page").fadeIn("slow");
					
					//set current brand id
					$('#current_category_id').attr('value', category_id);
    			}
		
				else{
    				alert("The category has no products ");
    			}	
    		
 		 });

		return false;
	}
	
	$(document).on("click",".sort_category",function(){
		
		var box1; 
		box1 = new ajaxLoader(".box-1");
	
		/* get the category id */
		category_id = $(this).attr('href');
		var page = 0;
		
    	$.get(link + "shop/browse/get_top/" + category_id + "/" + page, function(top){
			$("#top").fadeOut("slow");
			$("#top").html(top);
			$("#top").fadeIn("slow");
		});
		
		$.post(link + "shop/browse/remove_brand2/",
  			function(data){
			
  				if(data == 'true'){
					//
					//reorder products
    				$.get(link + "shop/browse/sort_products/" + category_id + "/" + page, function(products){
						$("#products").fadeOut("slow");
						$("#products").html(products);
						$("#products").fadeIn("slow");
					});
					
    				$.get(link + "shop/browse/sort_crumbs2/" + category_id, function(crumbs){
						$("#crumbs").fadeOut("slow");
						$("#crumbs").html(crumbs);
						$("#crumbs").fadeIn("slow");
					});
    		
					//reorder pagination
    				$.get(link + "shop/browse/new_pagination/" + category_id + "/" + page, function(pagination){
						$("#pagination").fadeOut("slow");
						$("#pagination").html(pagination);
						$("#pagination").fadeIn("slow");
					});
    		
					//reorder products
    				$.get(link + "shop/browse/products_count/" + category_id + "/" + page, function(products_count){
						$("#products_count").fadeOut("slow");
						$("#products_count").html(products_count);
						$("#products_count").fadeIn("slow");
					});
    		
					//retrieve brands
    				$.get(link + "shop/browse/select_brands/" + category_id, function(brands){
						//alert(brands);
						$("#brands_list").fadeOut("slow");
						$("#brands_list").html(brands);
						$("#brands_list").fadeIn("slow");
					});
    		
					//reorder products
    				$.get(link + "shop/browse/get_ordering/" + category_id + "/" + page, function(ordering){
						$("#ordering").fadeOut("slow");
						$("#ordering").html(ordering);
						$("#ordering").fadeIn("slow");
					});
    			
    				$("#shop_page").fadeOut("slow");
					$("#shop_page").html('<h1>Shop page <small><i class="icon-angle-right "></i> all products</small></h1>');
					$("#shop_page").fadeIn("slow");
					
					//set current brand id
					$('#current_category_id').attr('value', category_id);
    			}
		
				else{
    				alert("The category has no products ");
    			}	
    		
 		 });

		return false;
	});
	
	/*************************************
	*
	*Sort products
	*
	**************************************/
	
	$(document).on("change",".sort_order",function(){
		
		var box1; 
		box1 = new ajaxLoader(".box-1");
	
		/* get the requested sorting */
		var sorting = $('#sorting').val();
    	var category_id = $('#current_category_id').val();
		var page = $('#page').val();
		
		$.post(link + "shop/browse/order_products/"+category_id+"/"+sorting,
  			function(data){
			
  				if(data == 'true'){
					
					//reorder products
    				$.get(link + "shop/browse/sort_products/" + category_id + "/" + page, function(products){
						$("#products").fadeOut("slow");
						$("#products").html(products);
						$("#products").fadeIn("slow");
					});
    		
					//reorder pagination
    				$.get(link + "shop/browse/new_pagination/" + category_id + "/" + page, function(pagination){
						$("#pagination").fadeOut("slow");
						$("#pagination").html(pagination);
						$("#pagination").fadeIn("slow");
					});
    		
					//reorder products
    				$.get(link + "shop/browse/products_count/" + category_id + "/" + page, function(products_count){
						$("#products_count").fadeOut("slow");
						$("#products_count").html(products_count);
						$("#products_count").fadeIn("slow");
					});
					
					//set current brand id
					$('#current_category_id').attr('value', category_id);
    			}
		
				else{
    				alert("The category has no products ");
    			}	
    		
 		 });

		return false;
	});
	
	/*************************************
	*
	*Page Navigation
	*
	**************************************/
	$(document).on("click",".ajax_pagination",function(e){
		e.preventDefault();
		var box1; 
		box1 = new ajaxLoader(".box-1");
	
		/* get the requested page */
		var page1 = $(this).attr('href');
		var page = page1.substr(page1.length - 1);
		
		if(page == "#"){//if is on current page
			box1.remove();
		}
		
		else{
			if(page == "/"){
				page = 0;
			}
			var category_id = $('#current_category_id').val();
			//var page = $('#page').val();
			//$('#checkbox-1-'+current_brand_id).attr('checked', false);
			
			$.post(link + "shop/browse/order_pages/" + page,
				function(data){
				
					if(data == 'true'){
						
						//reorder products
						$.get(link + "shop/browse/sort_products/" + category_id + "/" + page, function(products){
							$("#products").fadeOut("slow");
							$("#products").html(products);
							$("#products").fadeIn("slow");
						});
				
						//reorder pagination
						$.get(link + "shop/browse/new_pagination/" + category_id + "/" + page, function(pagination){
							$("#pagination").fadeOut("slow");
							$("#pagination").html(pagination);
							$("#pagination").fadeIn("slow");
						});
				
						//reorder products
						$.get(link + "shop/browse/products_count/" + category_id + "/" + page, function(products_count){
							$("#products_count").fadeOut("slow");
							$("#products_count").html(products_count);
							$("#products_count").fadeIn("slow");
						});
						
						//set current category id
						$('#current_category_id').attr('value', category_id);
						//set current page
						$('#page').attr('value', page);
					}
			
					else{
						alert("The category has no products ");
					}	
				
			 });
		}

		return false;
	});

	function get_children(category_id){
		var link = $('#baseurl').val();
		
		$.post(link + "shop/browse/get_children/"+category_id,
				function(data){
					$("#category_children").html(data);	
			 });
	}
	
	function order_products(category_id){
		var link = $('#baseurl').val();
		var sorting = $('#sorting').val();
		//window.alert(link + "shop/browse/order_products/"+category_id+"/"+sorting);
		$.post(link + "shop/browse/order_products/"+category_id+"/"+sorting,
				function(){
					window.location.href = link + "shop/browse/open_products/" + category_id;
			 });
	}

	/*************************************
	*
	*Sort products by brand
	*
	**************************************/
	
	$(document).on("change",".regular-checkbox",function(){
		
		var box1; 
		box1 = new ajaxLoader(".box-1");
		
		var category_id = $('#current_category_id').val();
		var page = 0;
			
		$.get(link + "shop/browse/get_top/" + category_id + "/" + page, function(top){
			$("#top").fadeOut("slow");
			$("#top").html(top);
			$("#top").fadeIn("slow");
		});
		
		if($(this).is( ":checked" ) == true){
    	
			/* set any checked checkboxes to unchecked */
			/*var current_brand_id = $('#current_brand_id').val();
			if(current_brand_id > 0){
				$('#checkbox-1-'+current_brand_id).attr('checked', false);
			}*/
		
			/* get the brand id */
			brand_id = $(this).attr('value');
			alert(link + "shop/browse/filter_brand/"+ brand_id);
			$.post(link + "shop/browse/filter_brand/"+ brand_id,
				function(data){
				
					if(data == 'true'){
						//reorder products
						alert(link + "shop/browse/sort_products/" + category_id + "/" + page);
						$.get(link + "shop/browse/sort_products/" + category_id + "/" + page, function(products){
							$("#products").fadeOut("slow");
							$("#products").html(products);
							$("#products").fadeIn("slow");
						});
						
						$.get(link + "shop/browse/sort_crumbs2/" + category_id, function(crumbs){
							$("#crumbs").fadeOut("slow");
							$("#crumbs").html(crumbs);
							$("#crumbs").fadeIn("slow");
						});
				
						//reorder pagination
						$.get(link + "shop/browse/new_pagination/" + category_id + "/" + page, function(pagination){
							$("#pagination").fadeOut("slow");
							$("#pagination").html(pagination);
							$("#pagination").fadeIn("slow");
						});
				
						//reorder products
						$.get(link + "shop/browse/products_count/" + category_id + "/" + page, function(products_count){
							$("#products_count").fadeOut("slow");
							$("#products_count").html(products_count);
							$("#products_count").fadeIn("slow");
						});
				
						//reorder products
						$.get(link + "shop/browse/get_ordering/"+ category_id + "/" + page, function(ordering){
							$("#ordering").fadeOut("slow");
							$("#ordering").html(ordering);
							$("#ordering").fadeIn("slow");
						});
					
						$("#shop_page").fadeOut("slow");
						$("#shop_page").html('<h1>Shop page <small><i class="icon-angle-right "></i> all products</small></h1>');
						$("#shop_page").fadeIn("slow");
						
						//set current brand id
						$('#current_brand_id').attr('value', brand_id);
					}
			
					else{
						alert("The brand has no products ");
					}	
				
			 });
		}
		
		else{
		
			$.post(link + "shop/browse/remove_brand2/",
				function(data){
				
					if(data == 'true'){
						var category_id = $('#current_category_id').val();
						//reorder products
						$.get(link + "shop/browse/sort_products/" + category_id + "/" + page, function(products){
							$("#products").fadeOut("slow");
							$("#products").html(products);
							$("#products").fadeIn("slow");
						});
				
						//reorder pagination
						$.get(link + "shop/browse/new_pagination/" + category_id + "/" + page, function(pagination){
							$("#pagination").fadeOut("slow");
							$("#pagination").html(pagination);
							$("#pagination").fadeIn("slow");
						});
				
						//reorder products
						$.get(link + "shop/browse/products_count/" + category_id + "/" + page, function(products_count){
							$("#products_count").fadeOut("slow");
							$("#products_count").html(products_count);
							$("#products_count").fadeIn("slow");
						});
				
						//reorder products
						$.get(link + "shop/browse/get_ordering/"+ category_id + "/" + page, function(ordering){
							$("#ordering").fadeOut("slow");
							$("#ordering").html(ordering);
							$("#ordering").fadeIn("slow");
						});
					
						$("#shop_page").fadeOut("slow");
						$("#shop_page").html('<h1>Shop page <small><i class="icon-angle-right "></i> all products</small></h1>');
						$("#shop_page").fadeIn("slow");
					}
			
					else{
						alert("The category has no products ");
					}	
				
			 });
		}

		return false;
	});
	/*************************************
	*
	*View individual products
	*
	**************************************/
	$(document).on("click",".view_product",function(){
		
		var box1; 
		box1 = new ajaxLoader(".box-1");
	
		/* get the brand id */
		product_id = $(this).attr('href');
		//reorder products
    	$.get(link + "shop/browse/view_product/" + product_id, function(products){
			$("#top").html("");
			$("#products").fadeOut("slow");
			$("#products").html(products);
			$("#products").fadeIn("slow");
			$("#pagination").html("");
		});

		return false;
	});
	
	/*************************************
	*
	*Set category features
	*
	**************************************/
	$(document).on("click",".set_category_feature",function(){
		
		var box2; 
		box2 = new ajaxLoader(".box-2");
	
		/* get the category feature value id */
		var category_feature_value_id = $(this).attr('href');
		var current_product = $('#current_product').val();
		//alert(link + "shop/browse/save_category_feature_value/" + category_feature_value_id + "/" + current_product);
		$.post(link + "shop/browse/save_category_feature_value/" + category_feature_value_id + "/" + current_product,
  			function(data){
			
  				$("#selected_features").fadeOut("slow");
				$("#selected_features").html(data);
				$("#selected_features").fadeIn("slow");
    		
 		 });

		return false;
	});
	
	/*************************************
	*
	*View cart
	*
	**************************************/
	$(document).on("click",".viewcart", function(){
		
		var box1; 
		box1 = new ajaxLoader(".box-3");
		
		//alert(link + "shop/browse/save_category_feature_value/" + category_feature_value_id + "/" + current_product);
		$.post(link + "shop/browse/view_cart/",
  			function(data){
  				$("#products").fadeOut("slow");
				$("#products").html(data);
				$("#products").fadeIn("slow");
				
				$("#pagination").fadeOut("slow");
				$("#pagination").html("");
				$("#pagination").fadeIn("slow");
				
				$("#ordering").fadeOut("slow");
				$("#ordering").html("");
				$("#ordering").fadeIn("slow");
    			
    			$("#shop_page").fadeOut("slow");
				$("#shop_page").html('<h1>Shopping Cart <small><i class="icon-angle-right "></i> all items</small></h1>');
				$("#shop_page").fadeIn("slow");
				
				$("#top").html("");
				
    			$.get(link + "shop/browse/ordering/", function(products_count){
					$("#products_count").fadeOut("slow");
					$("#products_count").html(products_count);
					$("#products_count").fadeIn("slow");
				});
				
 		 });

		return false;
	});
	
	/*************************************
	*
	*Checkout
	*
	**************************************/
	$(document).on("click",".checkout", function(){
		
		var box1; 
		box1 = new ajaxLoader(".box-3");
		
		//alert(link + "shop/browse/save_category_feature_value/" + category_feature_value_id + "/" + current_product);
		$.post(link + "shop/browse/checkout/",
  			function(data){
				//alert(data);
				if(data == "true"){
				}
				
				else{
  					$("#products").fadeOut("slow");
					$("#products").html(data);
					$("#products").fadeIn("slow");
				
					$("#pagination").fadeOut("slow");
					$("#pagination").html("");
					$("#pagination").fadeIn("slow");
				
					$("#ordering").fadeOut("slow");
					$("#ordering").html("");
					$("#ordering").fadeIn("slow");
    			
    				$("#shop_page").fadeOut("slow");
					$("#shop_page").html('<h1>Checkout <small><i class="icon-angle-right "></i> login/ signup</small></h1>');
					$("#shop_page").fadeIn("slow");
					
					$("#products_count").fadeOut("slow");
					$("#products_count").html("");
					$("#products_count").fadeIn("slow");
				
					$("#top").html("");
				}
				
 		 });

		return false;
	});
	
	/*************************************
	*
	*Login
	*
	**************************************/
	$(document).on("submit", "div.login_user form",function(){
		
		var box1, box2; 
		box1 = new ajaxLoader(".box-4");
		
		//get the product id
		var mail = $(this).find('input[name=email]').val();
		var password = $(this).find('input[name=pwd]').val();
		
		//send the data to the php processing function
		$.post(link + "shop/browse/verify_login", { email: mail, pwd: password, ajax: '1' },
  			function(data){
  			
  			if(data == 'true'){
    			
				box2 = new ajaxLoader("#shop_page");
				
				$.get(link + "shop/browse/show_customer/", function(customer){
					$("#products_count").fadeOut("slow");
					$("#products_count").html('<h1 style="margin-bottom:0px;">Welcome <small><i class="icon-angle-right "></i> '+customer+'</small></h1>');
					$("#products_count").fadeIn("slow");
				});
				
				$("#collapseOne").attr("class", "accordion-body collapse");
				$("#collapseThree").attr("class", "accordion-body in collapse");
				
				box2.remove();
				box1.remove();
    		}
			
			else{
				$("#error").fadeOut("slow");
				$("#error").html(data);
				$("#error").fadeIn("slow");
				
				box1.remove();
    		}	
    		
 		 }); 

		return false;
	});
	
	/*************************************
	*
	*Save discount coupon
	*
	**************************************/
	$(document).on("click", ".discount",function(){
		
		var discount = $('#appendedInputButton1').val();
		
		//send the data to the php processing function
		$.post(link + "shop/browse/save_discount/"+discount, 
  			function(data){
  			
  			if(data == 'true'){
				
				$("#disc").html('saved');
    		}
			
			else{
				$("#disc").html('not saved');
    		}	
    		
 		 }); 
		return false;
	});
	
	/*************************************
	*
	*Save gift coupon
	*
	**************************************/
	$(document).on("click", ".gift",function(){
		
		var gift = $('#appendedInputButton2').val();
		
		//send the data to the php processing function
		$.post(link + "shop/browse/save_gift/"+gift, 
  			function(data){
  			
  			if(data == 'true'){
				
				$("#gift").html('saved');
    		}
			
			else{
				$("#gift").html(' not saved');
    		}	
    		
 		 }); 
		return false;
	});
	
	/*************************************
	*
	*Save currency
	*
	**************************************/
	$(document).on("click", ".currency",function(){
		
		var currency_id = $(this).attr('href');
		
		//send the data to the php processing function
		$.post(link + "shop/browse/save_currency/"+currency_id, 
  			function(data){
  			
  			if(data == 'true'){
				
				$.get(link + "shop/cart/show_cart", function(cart){
  					$("#cart_content").html(cart);
				});
    		}
    		
 		 }); 
		return false;
	});
	
	/*************************************
	*
	*Confirm order
	*
	**************************************/
	$(document).on("click", ".confirm_order",function(){
		
		//get the product id
		var name = $(this).find('input[name=customer_name]').val();
		var lname = $(this).find('input[name=customer_lname]').val();
		var mail = $(this).find('input[name=customer_email]').val();
		var phone = $(this).find('input[name=customer_phone]').val();
		var pwd = $(this).find('input[name=password]').val();
		var address = $(this).find('input[name=customer_address]').val();
		var post_code = $(this).find('input[name=customer_post_code]').val();
		var country = $(this).find('input[name=country_id]').val();
		var city = $(this).find('input[name=customer_city]').val();
		var account = $(this).find('input[name=customer_type]').val();
		
		//send the data to the php processing function
		$.post(link + "shop/browse/register_account", { customer_name: name, 
													customer_lname: lname, 
													customer_email: mail, 
													customer_phone: phone, 
													password: pwd, 
													customer_address: address, 
													customer_post_code: post_code, 
													country_id: country,  
													customer_type: city, 
													customer_city: account },
  			function(data){
  			
  			if(data == 'true'){
    			
				window.location.href = link + "shop/browse/payment";
    		}
			else{
				alert(data);
			}
    		
 		 }); 
		return false;
	});
		