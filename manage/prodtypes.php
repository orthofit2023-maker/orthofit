<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");
$filetitle='Product Type';
$opt='3';
$newfile="prodtype.php";
$retufile="prodtypes.php";
include("options.php");
?>