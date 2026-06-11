<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");
$filetitle='Shipping Time';
$opt='9';
$newfile="shippingtime.php";
$retufile="shippingtimes.php";
include("option.php");
?>