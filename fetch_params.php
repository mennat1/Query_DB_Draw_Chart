<?php
include "connect_to_mysql_server.php";


if(isset($_POST)){
	$_POST = json_decode(file_get_contents('php://input'), true);
	$db_name = mysqli_real_escape_string($mysql_srvr,$_POST['db_name']);
   //echo($db_name."\n");
   	mysqli_select_db($mysql_srvr, $db_name);
   	$table_name = mysqli_real_escape_string($mysql_srvr,$_POST['table_name']);
   //echo($table_name."\n");

}


$params_arr = array();
$query = sprintf("SHOW COLUMNS FROM `%s` FROM `%s` WHERE Type='int(11)' OR Type='float' OR Type='double'", $table_name, $db_name);
$params_info = mysqli_query($mysql_srvr, $query);

while($row = mysqli_fetch_assoc($params_info)){ 
	//echo($row['Field']+"\n");
	$params_arr[] = utf8_encode($row['Field']);
	
	
}	

//echo("3333333333333\n");
mysqli_close($mysql_srvr);
$params_json_str = utf8_encode(json_encode($params_arr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT|JSON_FORCE_OBJECT| JSON_PRETTY_PRINT ));

echo $params_json_str;