<?php 
include "./connect_to_mysql_server.php";
?>
<!DOCTYPE html>
<html>
<head>
  <title>PHP Retrieve Data from MySQL using Drop Down Menu</title>
  <script  type="application/javascript" src=./jquery.min.js></script>
  <link rel="shortcut icon" href="#">
  <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
  <meta content="utf-8" http-equiv="encoding">
  
</head>
<body>

<div>Choose Database: </div>
  <select id="database">
    <option value=0 disabled selected>-- Select Database --</option>
    <?php
      

        $db_info = mysqli_query($mysql_srvr, "SHOW DATABASES"); 


        while($data = mysqli_fetch_array($db_info)){
            echo "<option value='". $data[0] ."'>" .$data[0] ."</option>"."\n";  
        }
        mysqli_close($mysql_srvr);	
    ?>  
  </select>


<div>Choose Table: </div>
  <select id="table">
    <option value=0 disabled selected>-- Select Table --</option>
  </select>

<div>Choose Server: </div>
<select id="server">
  <option value=0 disabled selected>-- Select Server --</option>
</select>

<div>Choose Parameter: </div>
  <select id="parameter">
    <option value=0 disabled selected>-- Select Parameter --</option>
  </select>

<div>Choose Function: </div>
  <select id="function">
    <option value=0 disabled selected>-- Select Function --</option>
    <option value="sum">DISTRIBUTION & SUM</option>
    <option value="avg">DISTRIBUTION & AVG</option>
    <option value="std_dev">STD_DEV</option>
    <option value="sum_of_deltas">SUM_OF_DELTAS</option>
    <option value="avg_of_deltas">AVG_OF_DELTAS</option>
    <option value="stddev_of_deltas">STDDEV_OF_DELTAS</option>

  </select>


<div id="chartContainer" style="height: 300px; width: 100%;"></div>
<script type="application/javascript" src=./canvasjs.min.js></script>
<script type="application/javascript" src=./ajax_code.js></script>
<!-- <script type=text/javascript src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script type=text/javascript src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script> -->
<!-- 
<script type=text/javascript src=./canvasjs.min.js></script>
<script type=text/javascript src=./jquery.min.js></script>
<script type=text/javascript src=./ajax_code.js></script>
 -->
</body>
</html>
