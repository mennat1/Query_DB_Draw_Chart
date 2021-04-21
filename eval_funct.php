<?php
include "connect_to_mysql_server.php";

//echo("Holaaaaaaaaaaa");
if(isset($_POST)){
	//echo("Hiiiiiiiiii\n");
	//echo(gettype($_POST)."\n");
	//echo($_POST."\n");
	//echo("file contents: ".file_get_contents('php://input')."\n");
	//$_POST = json_decode(file_get_contents('php://input'), true);
	$_POST = file_get_contents('php://input');
	//echo($_POST."\n");
	//echo(gettype($_POST)."\n");
	$_POST = json_decode($_POST, true);
	//echo(gettype($_POST)."\n");
	//echo($_POST["funct_name"]."\n");




	$funct_name = mysqli_real_escape_string($mysql_srvr,$_POST['funct_name']);
	//echo($funct_name."\n");
	$db_name = mysqli_real_escape_string($mysql_srvr,$_POST['db_name']);
   	mysqli_select_db($mysql_srvr, $db_name);

   	$table_name = mysqli_real_escape_string($mysql_srvr,$_POST['table_name']);
   	$srvr_name = mysqli_real_escape_string($mysql_srvr,$_POST['srvr_name']);
   	$param_name = mysqli_real_escape_string($mysql_srvr,$_POST['param_name']);
   	//echo("---------------\n".$db_name."\n".$table_name."\n".$srvr_name."\n".$param_name."\n");

}

$table_to_be_checked = 'SUMMARY_T';
$has_SUMMARY_T = table_exists($db_name, $table_to_be_checked, $mysql_srvr);

$table_to_be_checked = 'System_Info';
$has_System_Info = table_exists($db_name, $table_to_be_checked, $mysql_srvr);


$has_System_Name1 = FALSE;
$has_System_Name2 = FALSE;
$has_System_Name3 = FALSE;
find_column($has_System_Name1, $has_System_Name2, $has_System_Name3, $table_name, $mysql_srvr);


if($has_System_Name1){
		// echo("111111111111\n");
	$query = sprintf("SELECT `%s` FROM `%s` WHERE System_Name='%s'",$param_name, $table_name, $srvr_name);

}elseif($has_System_Name2){
		// echo("222222222222222222\n");
	$query = sprintf("SELECT `%s` FROM `%s` WHERE `System Name`='%s'",$param_name, $table_name, $srvr_name);


}elseif($has_System_Name3){
	$query = sprintf("SELECT `%s` FROM `%s` WHERE Hostname='%s'",$param_name, $table_name, $srvr_name);
		
}else{
	echo("YALAHWIIIIIII1111");
}
	
$funct_result_x_y = mysqli_query($mysql_srvr, $query);




$funct_result_arr = array();
$i = 0;

while($row = mysqli_fetch_array($funct_result_x_y)){ 
	
	$funct_result_arr[$i] = $row[0];
	$i++;
}	



if($funct_name == "sum"){
	
	if($has_System_Name1){
		//echo("66666666666\n");
		$query = sprintf("SELECT SUM(`%s`) FROM `%s` WHERE System_Name='%s'",$param_name, $table_name, $srvr_name);
		

	}elseif($has_System_Name2){
		$query = sprintf("SELECT SUM(`%s`) FROM `%s` WHERE `System Name`='%s'",$param_name, $table_name, $srvr_name);

	}elseif($has_System_Name3){
		$query = sprintf("SELECT SUM(`%s`) FROM `%s` WHERE Hostname='%s'",$param_name, $table_name, $srvr_name);
	}else{
		echo("YALAHWIIIIIII222");
	}
	$funct_result_sum = mysqli_query($mysql_srvr, $query);
	
	

	while($row = mysqli_fetch_array($funct_result_sum)){ 
		//$funct_result_arr += ["sum" => $row[0]];
		$funct_result_arr["sum"] = $row[0];
	}	
	//echo("777777777777777\n");

}else if($funct_name == "avg"){
	
	if($has_System_Name1){
		//echo("99999999999999\n");
		$query = sprintf("SELECT AVG(`%s`) FROM `%s` WHERE System_Name='%s'",$param_name, $table_name, $srvr_name);
		

	}elseif($has_System_Name2){
		// echo("33333333\n");
		$query = sprintf("SELECT AVG(`%s`) FROM `%s` WHERE `System Name`='%s'",$param_name, $table_name, $srvr_name);

	}elseif($has_System_Name3){
		$query = sprintf("SELECT AVG(`%s`) FROM `%s` WHERE Hostname='%s'",$param_name, $table_name, $srvr_name);
	}else{
		echo("YALAHWIII333333");
	}
	
	$funct_result_avg = mysqli_query($mysql_srvr, $query);
	
	
	while($row = mysqli_fetch_array($funct_result_avg)){ 
		//$funct_result_arr += ["avg" => $row[0]];
		$funct_result_arr["avg"] = $row[0];
	}	


}elseif($funct_name == "std_dev"){
	
	if($has_System_Name1){
		$query = sprintf("SELECT STDDEV(`%s`) FROM `%s` WHERE System_Name='%s'",$param_name, $table_name, $srvr_name);
		

	}elseif($has_System_Name2){
		$query = sprintf("SELECT STDDEV(`%s`) FROM `%s` WHERE `System Name`='%s'",$param_name, $table_name, $srvr_name);

	}elseif($has_System_Name3){
		$query = sprintf("SELECT STDDEV(`%s`) FROM `%s` WHERE Hostname='%s'",$param_name, $table_name, $srvr_name);
	}else{
		echo("YALAHWIII444444");
	}
		
	$funct_result_std_dev = mysqli_query($mysql_srvr, $query);
	while($row = mysqli_fetch_array($funct_result_std_dev)){ 
		$funct_result_arr["std_dev"] = $row[0];
	}	


}elseif($funct_name == "sum_of_deltas"){
	//echo("111111111111\n");
	$param_values = array();
	foreach ($funct_result_arr as $key => $value) {
		//echo($value."\n");
		$param_values[] = $value;
	}
	$deltas = array();
	for($i=0; $i < sizeof($param_values)-1; $i++){
		
		$delta = number_format($param_values[$i]) - number_format($param_values[$i+1]);
		//echo($param_values[$i]." - ".$param_values[$i+1]." = ".$delta."\n");
		$delta = strval($delta);
		$deltas[] = $delta;
		//echo($delta."\n");
	}
	$sum_of_deltas = array_sum($deltas);
	//echo($sum_of_deltas."\n");
	//unset($funct_result_arr);
	$funct_result_arr = array();
	foreach ($deltas as $key => $value) {
		//$funct_result_arr += [$key => $value];
		$funct_result_arr[$key] = $value;

	}
	//$funct_result_arr += ["sum_of_deltas" => $sum_of_deltas];
	$funct_result_arr["sum_of_deltas"] = $sum_of_deltas;



}elseif($funct_name == "avg_of_deltas"){
	//echo("111111111111\n");
	$param_values = array();
	foreach ($funct_result_arr as $key => $value) {
		//echo($value."\n");
		$param_values[] = $value;
	}
	$deltas = array();
	for($i=0; $i < sizeof($param_values)-1; $i++){
		
		$delta = number_format($param_values[$i]) - number_format($param_values[$i+1]);
		//echo($param_values[$i]." - ".$param_values[$i+1]." = ".$delta."\n");
		$delta = strval($delta);
		$deltas[] = $delta;
		//echo($delta."\n");
	}
	$avg_of_deltas = array_sum($deltas)/sizeof($deltas);
	//echo($sum_of_deltas."\n");
	//unset($funct_result_arr);
	$funct_result_arr = array();
	foreach ($deltas as $key => $value) {
		//$funct_result_arr += [$key => $value];
		$funct_result_arr[$key] = $value;

	}
	//$funct_result_arr += ["avg_of_deltas" => $avg_of_deltas];
	$funct_result_arr["avg_of_deltas"] = $avg_of_deltas;



}elseif($funct_name == "stddev_of_deltas"){
	//echo("111111111111\n");
	$param_values = array();
	foreach ($funct_result_arr as $key => $value) {
		//echo($value."\n");
		$param_values[] = $value;
	}
	$deltas = array();
	for($i=0; $i < sizeof($param_values)-1; $i++){
		
		$delta = number_format($param_values[$i]) - number_format($param_values[$i+1]);
		//echo($param_values[$i]." - ".$param_values[$i+1]." = ".$delta."\n");
		$delta = strval($delta);
		$deltas[] = $delta;
		//echo($delta."\n");
	}
	$stddev_of_deltas = std_dev($deltas);
	//echo($sum_of_deltas."\n");
	//unset($funct_result_arr);
	$funct_result_arr = array();
	foreach ($deltas as $key => $value) {
		//$funct_result_arr += [$key => $value];
		$funct_result_arr[$key] = $value;

	}
	//$funct_result_arr += ["stddev_of_deltas" => $stddev_of_deltas];
	$funct_result_arr["stddev_of_deltas"] = $stddev_of_deltas;



}else{
	echo("ERRORRRR!!!!!!!!!!!!!!");
}


mysqli_close($mysql_srvr);


function std_dev($arr) { 
    $num_of_elements = count($arr); 
      
    $variance = 0.0; 
    $average = array_sum($arr)/$num_of_elements; 
      
    foreach($arr as $i) 
    { 
        // sum of squares of differences between  
                    // all numbers and means. 
        $variance += pow(($i - $average), 2); 
    } 
      
    return (float)sqrt($variance/$num_of_elements); 
} 


$funct_result_json_str = utf8_encode(json_encode($funct_result_arr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT|JSON_FORCE_OBJECT| JSON_PRETTY_PRINT ));


//echo(gettype($funct_result_json_str)."\n");
echo($funct_result_json_str);


function table_exists($db_name, $table_name, $mysql_srvr){
	$query = sprintf("SHOW TABLES FROM `%s` LIKE '%s'", $db_name, $table_name);
	$result = mysqli_query($mysql_srvr, $query);
	$row_cnt = mysqli_num_rows($result);
	if( $row_cnt == 1 ){
		//echo($table_name." was found\n");
	    return TRUE;
	}
	else{
		//echo($table_name." was NOT found\n");
	    return FALSE;
	}
	mysqli_free_result($result);
}

function column_exists($table_name, $column_name, $mysql_srvr){
	$query = sprintf("SHOW COLUMNS FROM `%s` LIKE '%s'", $table_name, $column_name);
	$result = mysqli_query($mysql_srvr, $query);
	$row_cnt = mysqli_num_rows($result);
	if( $row_cnt == 1 ){
		//echo($table_name." was found\n");
	    return TRUE;
	}
	else{
		//echo($table_name." was NOT found\n");
	    return FALSE;
	}
	mysqli_free_result($result);
}

function find_column(&$has_System_Name1, &$has_System_Name2, &$has_System_Name3, $table_name, $mysql_srvr){
	$query1 = sprintf("SHOW COLUMNS FROM `%s` LIKE 'System_Name'", $table_name);
	$result1 = mysqli_query($mysql_srvr, $query1);
	$row_cnt1 = mysqli_num_rows($result1);
	// echo($row_cnt1."\n");

	$query2 = sprintf("SHOW COLUMNS FROM `%s` LIKE 'System Name'", $table_name);
	$result2 = mysqli_query($mysql_srvr, $query2);
	$row_cnt2 = mysqli_num_rows($result2);
	// echo($row_cnt2."\n");

	$query3 = sprintf("SHOW COLUMNS FROM `%s` LIKE 'Hostname'", $table_name);
	$result3 = mysqli_query($mysql_srvr, $query3);
	$row_cnt3 = mysqli_num_rows($result3);
	// echo($row_cnt3."\n");

	if(($row_cnt1 == 1) && ($row_cnt2 == 1) && ($row_cnt3 == 0)){
		$has_System_Name2 = TRUE;
		// echo("lalala1111");

	}elseif(($row_cnt1 == 1) && ($row_cnt2 == 0) && ($row_cnt3 == 0)){
		$has_System_Name1 = TRUE;
		// echo("lalala222");

	}elseif(($row_cnt3 == 1) && ($row_cnt1 == 0) && ($row_cnt2 == 0)){
		$has_System_Name3 = TRUE;
		// echo("lalala333");
	}else{
		echo("YALAHWIII+++++lalalala44444");
	}

}