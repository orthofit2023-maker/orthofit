<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");
$filetitle='Occasion - Secondary';
$opt='11';
$newfile="occasionsecd.php";
$retufile="occasionsecds.php";
include("options.php");
?>