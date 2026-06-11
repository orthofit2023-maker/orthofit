<?php 
//session_start();
ini_set('max_execution_time', 3000);
include("db5conn.php");

$mysqli->query("delete from ccd9mostviewed");

$rowdb=$mysqli->query("SELECT v.countrycode FROM `ccd9prodviewed` v where v.countrycode!='' and v.countrycode!='-' group by v.countrycode order by v.countrycode"); 
while($roword=$rowdb->fetch_array()){ 

	$mysqli->query("insert into ccd9mostviewed (cnt, prodid, countrycode) SELECT count(*) as cnt, v.prodid, v.countrycode FROM `ccd9prodviewed` v where v.countrycode!='' and v.countrycode!='-' and v.countrycode='".$roword['countrycode']."'  group by v.prodid, v.countrycode order by cnt desc "); 
}
?>