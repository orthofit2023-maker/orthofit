<?php 
include("db5conn.php");
$sql="update ccd9products set prodstatus = '1' WHERE prodid = '1847'";
$mysqli->query($sql);
?>