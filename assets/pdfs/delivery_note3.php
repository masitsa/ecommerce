<?php session_start();
/*
	-----------------------------------------------------------------------------------------
	Required Libraries
	-----------------------------------------------------------------------------------------
*/
include ('fpdf16/fpdf.php');
include ('connect.php');

/*
	-----------------------------------------------------------------------------------------
	Retrieve the passed order delivery date
	-----------------------------------------------------------------------------------------
*/
$order_delivery_date = $_GET['order_delivery_date'];

/*
	-----------------------------------------------------------------------------------------
	Retrieve the order id
	-----------------------------------------------------------------------------------------
*/
$sql = "SELECT order_id FROM `order` WHERE order_delivery_date = '".$order_delivery_date."'";
$get = new Database;
$order_rs = $get->select($sql);
$num_orders = mysql_num_rows($order_rs);

if($num_orders > 0){
	for($s = 0; $s < $num_orders; $s++){

		$order_id = mysql_result($order_rs, $s, "order_id");
		$_SESSION['order_id'] = $order_id;

/*
	-----------------------------------------------------------------------------------------
	Create a class that creates the pdf
	-----------------------------------------------------------------------------------------
*/
Class PDF extends FPDF{
		
	/*
		-----------------------------------------------------------------------------------------
		Header contains the customer & order details. It appears at the top of every page
		-----------------------------------------------------------------------------------------
	*/
	function header(){
		$order_id = $_SESSION['order_id'];
		/*
			-----------------------------------------------------------------------------------------
			Retrieve the order details from the database
			-----------------------------------------------------------------------------------------
		*/

		$sql = "SELECT * FROM `order` WHERE order_id = ".$order_id;
		$get = new Database;
		$orders = $get->select($sql);

		/*
			-----------------------------------------------------------------------------------------
			Retrieve the order's order method from the database
			-----------------------------------------------------------------------------------------
		*/

		$sql = "SELECT order_methods.order_method_name FROM order_methods, `order` WHERE order_methods.order_method_id = order.order_method_id AND order.order_id = ".$order_id;
		$get = new Database;
		$order_methods = $get->select($sql);

		/*
			-----------------------------------------------------------------------------------------
			Retrieve the order's customer from the database
			-----------------------------------------------------------------------------------------
		*/

		$sql = "SELECT `users`.`first_name`, users.last_name, users.phone_no, users.email FROM `users`, `order` WHERE users.user_id = order.customer_id AND order.order_id = ".$order_id;//echo $sql;
		$get = new Database;
		$customers = $get->select($sql);
		
		//get the order details
		for($r = 0; $r < (mysql_num_rows($orders)); $r++){
			$order_id = mysql_result($orders, $r, "order_id");
			$order_date = date('jS M Y H:i a',strtotime(mysql_result($orders, $r, "order_date")));
			$order_delivery_date = mysql_result($orders, $r, "order_delivery_date");
		}
		
		//get the customer's details
		for($r = 0; $r < (mysql_num_rows($customers)); $r++){
			$customer_name = mysql_result($customers, $r, "first_name"). " ".mysql_result($customers, $r, "last_name");
			$_SESSION['customer'] = $customer_name;
			$customer_phone = mysql_result($customers, $r, "phone_no");
			$customer_address = mysql_result($customers, $r, "email");
		}
		
		//get the order method
		for($r = 0; $r < (mysql_num_rows($order_methods)); $r++){
			$order_method = mysql_result($order_methods, $r, "order_method_name");
		}
			
		/*
			-----------------------------------------------------------------------------------------
			Measurements of the page cells
			-----------------------------------------------------------------------------------------
		*/
		$pageH = 5;//height of an output cell
		$pageW = 0;//width of the output cell. Takes the entire width of the page
		$lineBreak = 20;//height between cells
		
		/*
			-----------------------------------------------------------------------------------------
			Colors of frames, background and Text
			-----------------------------------------------------------------------------------------
		*/
		$this->SetDrawColor(092, 123, 29);//color of borders
		$this->SetFillColor(0, 232, 12);//color of shading
		//$this->SetTextColor(092, 123, 29);//color of text
		$this->SetFont('Times', 'B', 12);
		
		/*
			-----------------------------------------------------------------------------------------
			Title of the document.
			-----------------------------------------------------------------------------------------
		*/
		$this->Cell($pageW, $pageH, 'DELIVERY NOTE', "B", 1, 'C');
		
		/*
			-----------------------------------------------------------------------------------------
			Page subtitle. Shows the order & customer details
			-----------------------------------------------------------------------------------------
		*/
		$pageW = 200;//width of the output cell. Change it to half the page size
		$this->Cell($pageW/2, $pageH, 'Order Details', 0, 0, 'C');
		$this->Cell($pageW/2, $pageH, 'Customer Details', 0, 1, 'C');
		$this->setFont('Times', '', 12);
		
		/*
			-----------------------------------------------------------------------------------------
			First row. Order ID and Customer Name
			-----------------------------------------------------------------------------------------
		*/
		$this->Cell($pageW/4, $pageH, 'Order ID:', 0, 0, 'L');
		$this->Cell($pageW/4, $pageH, $order_id, 0, 0, 'L');
		$this->Cell($pageW/4, $pageH, 'Customer Name:', 0, 0, 'L');
		$this->Cell($pageW/4, $pageH, $customer_name, 0, 1, 'L');
		
		/*
			-----------------------------------------------------------------------------------------
			Second row. Order Date and Customer Phone
			-----------------------------------------------------------------------------------------
		*/
		$this->Cell($pageW/4, $pageH, 'Order Date:', 0, 0, 'L');
		$this->Cell($pageW/4, $pageH, $order_date, 0, 0, 'L');
		$this->Cell($pageW/4, $pageH, 'Customer Phone:', 0, 0, 'L');
		$this->Cell($pageW/4, $pageH, $customer_phone, 0, 1, 'L');
		
		/*
			-----------------------------------------------------------------------------------------
			Third row. Order Method and Customer Address
			-----------------------------------------------------------------------------------------
		*/
		$this->Cell($pageW/4, $pageH, 'Order Method:', "B", 0, 'L');
		$this->Cell($pageW/4, $pageH, $order_method, "B", 0, 'L');
		$this->Cell($pageW/4, $pageH, 'Email: ', "B", 0, 'L');
		$this->Cell($pageW/4, $pageH, $customer_address, "B", 1, 'L');
		$this->Ln($lineBreak);
	}
	
	//page footer
	function footer(){
		
		//position 1.5cm from Bottom
		$this->SetY(-15);
		//set Text color to gray
		$this->SetTextColor(128);
		//font
		$this->SetFont('Arial', 'I', 8);
		//page number
		$this->Cell(0, 5, 'Page '.$this->PageNo().'/{nb}',0,1,"C");
	}
}

/*
	-----------------------------------------------------------------------------------------
	Retrieve the order items details from the database
	-----------------------------------------------------------------------------------------
*/
$sql = "SELECT * FROM order_item, product, `order` WHERE order_item.product_id = product.product_id AND order_item.order_id = order.order_id AND order.order_id = ".$order_id;
$get = new Database;
$order_items = $get->select($sql);

/*
	-----------------------------------------------------------------------------------------
	Retrieve the discount from the database
	-----------------------------------------------------------------------------------------
*/

$sql = "SELECT order.order_discount FROM `order` WHERE order.order_id = ".$order_id;
$get = new Database;
$orders = $get->select($sql);

/*
	-----------------------------------------------------------------------------------------
	Retrieve the order details from the database
	-----------------------------------------------------------------------------------------
*/
//get the order details
for($r = 0; $r < (mysql_num_rows($orders)); $r++){
	$order_discount = mysql_result($orders, $r, "order_discount"); 
}

/*
	-----------------------------------------------------------------------------------------
	Retrieve the discount from the database
	-----------------------------------------------------------------------------------------
*/

$sql = "SELECT coupon.coupon_name FROM coupon, `order` WHERE coupon.coupon_id = order.coupon_id AND order.order_id = ".$order_id;
$get = new Database;
$orders = $get->select($sql);

/*
	-----------------------------------------------------------------------------------------
	Retrieve the order details from the database
	-----------------------------------------------------------------------------------------
*/
//get the order details
for($r = 0; $r < (mysql_num_rows($orders)); $r++){
	$coupon_name = mysql_result($orders, $r, "coupon_name");
}

/*
	-----------------------------------------------------------------------------------------
	Begin creating the PDF in A4
	-----------------------------------------------------------------------------------------
*/
$pdf = new PDF('P','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();

/*
	-----------------------------------------------------------------------------------------
	Text, font & fill details
	-----------------------------------------------------------------------------------------
*/
$pdf->setFont('Times', '', 12);
$pdf->SetFillColor(174, 255, 187); //226, 225, 225

/*
	-----------------------------------------------------------------------------------------
	Set width and height of every cell
	-----------------------------------------------------------------------------------------
*/
$pageH = 5;//height of an output cell
$pageW = 200;//width of the output cell. Takes the entire width of the page

/*
	-----------------------------------------------------------------------------------------
	Retrieve the ordered products from the order items array passed to the page
	-----------------------------------------------------------------------------------------
*/
$pdf->Cell(0,$pageH,"PRODUCTS",'B',1,"C");
$pdf->Ln(5);

if(count($order_items) > 0){
	$count = 0;
	$count2 = 0;
	$total_amount = 0;
	$fill = FALSE;
	
	for($r = 0; $r < (mysql_num_rows($order_items)); $r++){
		$count++;
		$customization = mysql_result($order_items, $r, "order_item_customization");
		$quantity = mysql_result($order_items, $r, "order_item_quantity");
		$price = mysql_result($order_items, $r, "order_item_price");
		$product_id = mysql_result($order_items, $r, "product_id");
		$product_name = mysql_result($order_items, $r, "product_name");
		$product_code = mysql_result($order_items, $r, "product_code");
		$image = mysql_result($order_items, $r, "product_image_name");
		$mime = mysql_result($order_items, $r, "product_image_mime");
		$total = ($quantity * $price);
		$total_amount += $total;
		//$image = $base_url."img/gallery2.php?product_id=".$product_id;
		
		/*
			-----------------------------------------------------------------------------------------
			Display the ordered products
			-----------------------------------------------------------------------------------------
		*/
		$pdf->Image($_SESSION['base_url'].'products/thumbs/'.$image);
		$pdf->Cell($pageW/7,$pageH,"",'B',0,"L",$fill);
		$pdf->Cell($pageW/7,$pageH,$product_code,'B',0,"L",$fill);
		$pdf->Cell($pageW/4,$pageH,$product_name,'B',0,"L",$fill);
		$pdf->Cell($pageW/10,$pageH,$quantity,'B',0,"L",$fill);
		$pdf->Cell($pageW/7,$pageH,$price,'B',0,"L",$fill);
		$pdf->Cell($pageW/7,$pageH,$total,'B',1,"L",$fill);
		
		if(!empty($customization)){
			//add shading to every odd numbered row
			if(($count % 2) == 0){
				$fill = FALSE;
			}
			else{
				$fill = TRUE;
			}
			$pdf->MultiCell(0,$pageH,"CUSTOMIZATION: ".$customization,'B',1,"C",$fill);
			$count++;
		}
		
		//add shading to every odd numbered row
		if(($count % 2) == 0){
			$fill = FALSE;
		}
		else{
			$fill = TRUE;
		}
	}
		
	/*
		-----------------------------------------------------------------------------------------
		Display the order total amount
		-----------------------------------------------------------------------------------------
	*/
	$pdf->Cell(134.45,$pageH,"Total",'B',0,"R",$fill);
	$pdf->Cell(50,$pageH,$total_amount,'B',1,"R",$fill);
	$count++;
	if(($count % 2) == 0){
		$fill = FALSE;
	}
	else{
		$fill = TRUE;
	}
		
	/*
		-----------------------------------------------------------------------------------------
		Display the order discount
		-----------------------------------------------------------------------------------------
	*/
	$pdf->Cell(134.45,$pageH,"Discount",'B',0,"R",$fill);
	$pdf->Cell(50,$pageH,$order_discount,'B',1,"R",$fill);
	$count++;
	if(($count % 2) == 0){
		$fill = FALSE;
	}
	else{
		$fill = TRUE;
	}
		
	/*
		-----------------------------------------------------------------------------------------
		Display the order total
		-----------------------------------------------------------------------------------------
	*/
	$pdf->Cell(134.45,$pageH,"",'B',0,"R",$fill);
	$pdf->Cell(50,$pageH,($total_amount - $order_discount),'B',1,"R",$fill);
	$count++;
	if(($count % 2) == 0){
		$fill = FALSE;
	}
	else{
		$fill = TRUE;
	}
	
	/*
		-----------------------------------------------------------------------------------------
		Display the order coupon
		-----------------------------------------------------------------------------------------
	*/
	if(!empty($coupon_name)){
		$pdf->Cell(134.45,$pageH,"Coupon",'B',0,"R",$fill);
		$pdf->Cell(50,$pageH,$coupon_name,'B',1,"R",$fill);
	}
}
$pdf->Output('Delivery Note_'.$_SESSION['customer']."_".$order_id.".pdf", 'D');
$_SESSION['customer'] = NULL;
}
}

?>