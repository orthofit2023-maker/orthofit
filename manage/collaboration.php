<?php
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}

$pgtitle="Collaborations";
$pgurl="collaboration.php";
$retuurl="collaborations.php";
$iscart=2;
include("newin.php");
?>