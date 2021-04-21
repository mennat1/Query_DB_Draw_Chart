<?php

include "connect_to_mysql_server.php";

if(isset($_POST)){
	$_POST = json_decode(file_get_contents('php://input'), true);
	$db_name = mysqli_real_escape_string($mysql_srvr,$_POST['db_name']);
   	mysqli_select_db($mysql_srvr, $db_name);
   	//echo($db_name."\n");
}



$tables_arr = array();
//echo("3333333333\n");

$tables_info= mysqli_query($mysql_srvr, "SHOW TABLES FROM $db_name");
//echo("fetched tables\n");

//$x = 0;

while($row = mysqli_fetch_array($tables_info)) { 
	
	$tables_arr[] = utf8_encode($row[0]);;

}


mysqli_close($mysql_srvr);

$tables_json_str = utf8_encode(json_encode($tables_arr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT|JSON_FORCE_OBJECT| JSON_PRETTY_PRINT ));

echo $tables_json_str;