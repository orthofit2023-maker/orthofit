<?php
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}

$pgtitle="Website Pages";
$pgurl="page.php";
$retuurl="pages.php";
$iscart=0;
include("newsin.php");
?>