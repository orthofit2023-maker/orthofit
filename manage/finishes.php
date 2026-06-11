<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");
$filetitle='Product Sizes';
$opt='4';
$newfile="finishe.php";
$retufile="finishes.php";
include("options.php");
?>