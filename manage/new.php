<?php
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}

$pgtitle="Blog";
$pgurl="new.php";
$retuurl="news.php";
$iscart=1;
include("newin.php");
?>