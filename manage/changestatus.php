<?php
session_start();
include("db5conn.php");

if($_GET['req']=='ps7896' && $_GET['pcode']!=""){
	//echo "update ccd9products set prodstatus='0' where prodcode='".trim($_GET['pcode'])."'<br>";
	$mysqli->query("update ccd9products set prodstatus='0' where prodcode='".trim($_GET['pcode'])."'");
	echo $_GET['pcode'];
}
?>