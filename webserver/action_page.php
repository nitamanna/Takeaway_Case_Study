<?php
/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */

include("config.php");
$link = mysqli_connect($host, $user, $pass,'DEMOPROJECT');
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
 
// Escape user inputs for security
$max_threshold = mysqli_real_escape_string($link, $_REQUEST['maxthreshold']);
$min_threshold = mysqli_real_escape_string($link, $_REQUEST['minthreshold']);
 
if (empty($max_threshold)){
   $max_threshold = 0;}
if (empty($min_threshold)){
   $min_threshold = 0;}


// Attempt insert query execution

if (($max_threshold != 0) and ($min_threshold != 0))
{
$sql = "INSERT INTO THRESHOLD (Record_No,Max_Threshold,Min_Threshold) VALUES (1,$max_threshold,$min_threshold)   ON DUPLICATE KEY UPDATE Max_Threshold=VALUES(Max_Threshold) , Min_Threshold=VALUES(Min_Threshold)";
} elseif ((isset($max_threshold)) and ($min_threshold == 0)){
$sql = "INSERT INTO THRESHOLD (Record_No,Max_Threshold,Min_Threshold) VALUES (1,$max_threshold,$min_threshold)   ON DUPLICATE KEY UPDATE Max_Threshold=VALUES(Max_Threshold) , Min_Threshold=VALUES(Min_Threshold)";
} elseif (($max_threshold == 0) and (isset($min_threshold))){
$sql = "INSERT INTO THRESHOLD (Record_No,Max_Threshold,Min_Threshold) VALUES (1,$max_threshold,$min_threshold)   ON DUPLICATE KEY UPDATE Max_Threshold=VALUES(Max_Threshold) , Min_Threshold=VALUES(Min_Threshold)";
}



if(mysqli_query($link, $sql)){
    echo "Threshold updated";
    echo "<meta http-equiv='refresh' content='2;url=index.php'>";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}
 
// Close connection
mysqli_close($link);
?>
