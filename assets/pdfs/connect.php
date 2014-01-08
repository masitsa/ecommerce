<?php

class Database{//class to connect to and retrieve info from the database

    private $connect;

    function  __construct() {
         //connect to database
        //$this->connect=mysql_connect("70.32.66.141", "cart", "B@doK1dogo!")
		$this->connect=mysql_connect("localhost", "root", "")
                    or die("Unable to connect to MySQL".mysql_error());

        //selecting a database
       $selected = mysql_select_db("ecommerce_db", $this->connect)
	   //$selected = mysql_select_db("cart", $this->connect)
                    or die("Could not select database".mysql_error());
    }

    function select($sql){

        $rs = mysql_query($sql)
        or die ("unable to Select ".mysql_error());

        return $rs;
    }
}
?>