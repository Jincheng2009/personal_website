<?php
  //1. create a database connection
  $dbhost = "jinchengwucom.ipagemysql.com";
  $dbuser = "general";
  $dbpass="123456";
  $dbname="gene_data";
  $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
  //test the connection
  if(mysqli_connect_errno()) {
  	die("Database connection failed: " .
  		mysqli_connect_error() . 
  		" (" . mysqli_connect_errno() . ")"
  		);
  }

?>