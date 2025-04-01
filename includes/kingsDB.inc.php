<?php

	function db_connect() {
		//$db_host = '192.168.167.245';
		//$db_user = 'root';
		//$db_passwd = "23jkj24asf7b3";
		//$db_name = "kings_resource";
		
		$db_host = 'localhost';
		$db_user = 'kings_resource_dev';
		$db_passwd = "lYEbECwhA6Vs";
		$db_name = "kings_resource_dev";

		$db = mysql_connect("$db_host", "$db_user", "$db_passwd") or die("could not connect to the db");
		mysql_select_db("$db_name", $db) or die("can't select $db_name");
		return $db;
	}

	$db = db_connect();


?>
