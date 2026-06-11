<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");
$filetitle='Product Colours';
$opt='7';
$newfile="hue.php";
$retufile="hues.php";
include("options.php");
?>