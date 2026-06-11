<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_WARNING);
ini_set("log_errors", 1);
ini_set('max_execution_time', 1500);

include("db5conn.php");

$sql="SELECT  id, type, sku, name, shortdescription, description, saleprice, regularprice, categories, tags, images, replace(parent,'id: ','') as parent, groupedproducts, galleryimages, swatchessttributes, attribute1name, attribute1values, attribute1visible, attribute1global, attribute1default, attribute2name, attribute2values, attribute2visible, attribute2global, attribute2default, attribute3name, attribute3values, attribute3visible, attribute3global, attribute3default, stock, published, position FROM wc_products WHERE   parent=''";
$result = $mysqli->query($sql);
while($row = $result->fetch_array()){
		echo trim($row['name']);
}
exit();



//phpinfo();
include("db5conn.php");

$sql="SELECT  id, type, sku, name, shortdescription, description, saleprice, regularprice, categories, tags, images, replace(p.parent,'id: ','') as parent, groupedproducts, galleryimages, swatchessttributes, attribute1name, attribute1values, attribute1visible, attribute1global, attribute1default, attribute2name, attribute2values, attribute2visible, attribute2global, attribute2default, attribute3name, attribute3values, attribute3visible, attribute3global, attribute3default, stock, published, position FROM wc_products WHERE   parent=''";
$result = $mysqli->query($sql);
while($row = $result->fetch_array()){
		echo trim($row['name']);
}
exit();

/*
if(isset($_SESSION["loginid"])){
	echo 'login'.$_SESSION["loginid"];
}else{
	session_start();
	$_SESSION["loginid"]=222;
	echo $_SESSION["loginid"];
}
exit();
*/


include("db5conn.php");
$sql = "SELECT * FROM ccd9user";
$row=query_first($sql);

//printf("%s - %s - %s %s\n", $row['loginid'], $row['loginname'], $row['hq'], '<br>');

//exit();

$querycon = $mysqli->query($sql);
$num_rows = $querycon->num_rows;
if ($num_rows>0){
	while($row=$querycon->fetch_assoc()){ $i++;
			printf("%s - %s - %s %s\n", $row['loginid'], $row['loginname'], $row['hq'], '<br>');
	}
}


exit();
$host="localhost";
$user="swaromco_orthofit";
$passwd="Un5mH,{Gx,b5";
$db="swaromco_orthofit";

$con = new mysqli($host, $user, $passwd, $db);

if ($con->connect_errno) {

    printf("connection failed: %s\n", $con->connect_error());
    exit();
}


$host="127.0.0.1";
$user="33469_meridian";
$passwd="ind@12345";
$db="33469_meridian";

$con = new mysqli($host, $user, $passwd, $db);

if ($con->connect_errno) {

    printf("connection failed: %s\n", $con->connect_error());
    exit();
}

$query = "SELECT * FROM ccd9user";

if ($res = $con->query($query)) {

    printf("Select query returned %d rows.\n", $res->num_rows);

    while ($row = $res->fetch_assoc())
    {
        printf("%s - %s - %s %s\n", $row['loginid'], $row['loginname'], $row['hq'], '<br>');
    }

    $res->close();
} else {

    echo "failed to fetch data\n";
}

$con->close();


?>
