<?php
session_start();
include("db5conn.php");

if(isset($_GET['getCustomer']) && isset($_GET['q'])){
	$letters = $_GET['q'];
	$letters = preg_replace("/[^a-z0-9 ]/si","",$letters);
	$sql="select concat(c.username,' ', c.lastname) as customer, c.compid, c.phone from ccd9company c where (c.username like '%".$letters."%' or c.lastname like '%".$letters."%' or c.phone like '%".$letters."%' ) ";
	$sql=$sql." order by c.lastname, c.username";  
	$result=$mysqli->query($sql);
	while($inf=$result->fetch_array()){
		echo $inf["customer"]." (".$inf["phone"].")|".$inf["compid"]."\n";
		$n++;
	}	
	if($n==0){
		echo "No records found!|\n";
	}
}

?>
