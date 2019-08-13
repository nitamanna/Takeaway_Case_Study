<!DOCTYPE HTML>
<html>
<head>

<?php

include("config.php");

$con = mysqli_connect($host, $user, $pass,'DEMOPROJECT');

$sql = "SELECT Last_Refresh_Dttm ,Exchange_Rate FROM BitcoinExchangeRate;";
           $result = mysqli_query($con, $sql);
           $json_array = array();
           while($row = mysqli_fetch_assoc($result))
           {
                $json_array[] = $row;
           }
          // echo json_encode($json_array);
?>
</head>
</html>
