<?php
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}

$pgtitle="Product Pages";
$pgurl="prodpage.php";
$retuurl="prodpages.php";
$iscart=2;
include("newin.php");
?>