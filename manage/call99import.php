<?php 
session_start();
ini_set('max_execution_time', 10000);
include("db5conn.php");
//SELECT id, name, attribute1values, attribute2values, attribute2values, images, galleryimages FROM `wc_products` where name like '%Vasyli%';
//SELECT p.prodid, p.prodname, p.prodprice, w.regularprice FROM `ccd9products` p left join wc_products w on p.prodid=w.id or w.parent like concat('%',p.prodid) or w.parent like concat('%',p.prodcode,'%') where p.prodprice=0 group by p.prodid;
//SELECT p.prodid, p.prodname, p.prodprice FROM `ccd9products` p  where p.prodprice=0 group by p.prodid;
$mysqli->query("delete from ccd9products");

$sql="SELECT  id, type, sku, name, shortdescription, description, saleprice, regularprice, replace(categories,'>',',') as categories, tags, images, replace(parent,'id: ','') as parent, groupedproducts, galleryimages, swatchessttributes, attribute1name, attribute1values, attribute1visible, attribute1global, attribute1default, attribute2name, attribute2values, attribute2visible, attribute2global, attribute2default, attribute3name, attribute3values, attribute3visible, attribute3global, attribute3default, stock, published, position FROM wc_products WHERE   parent=''";
$result = $mysqli->query($sql);
while($row = $result->fetch_array()){
	//$sqlin="SELECT p.id,p.sku, p.name, p.description, replace(p.parent,'id: ','') as parent FROM `wc_products` p group by p.parent having count(*)>1;";

//attribute1name, attribute1values, attribute2name, attribute2values, attribute3name, attribute3values

	$prodcode=''; $prodcats=''; $type1=''; $type2=''; $type3=''; $prodcolor=''; $prodname=''; $proddesc=''; $prodsize=''; 

	if($row['attribute1name']=='Fit'){
		$type1=trim($row['attribute1values']);
	}else if($row['attribute2name']=='Fit'){
		$type1=trim($row['attribute2values']);
	}else if($row['attribute3name']=='Fit'){
		$type1=trim($row['attribute3values']);
	}

	if($row['attribute1name']=='Size'){
		$prodsize=trim($row['attribute1values']);
		$type2=trim($row['attribute1values']);
	}else if($row['attribute2name']=='Size'){
		$prodsize=trim($row['attribute2values']);
		$type2=trim($row['attribute2values']);
	}else if($row['attribute3name']=='Size'){
		$prodsize=trim($row['attribute3values']);
		$type2=trim($row['attribute3values']);
	}

	if($row['attribute1name']=='Color'){
		$prodcolor=trim($row['attribute1values']);
		$type3=trim($row['attribute1values']);
	}else if($row['attribute2name']=='Color'){
		$prodcolor=trim($row['attribute2values']);
		$type3=trim($row['attribute2values']);
	}else if($row['attribute3name']=='Color'){
		$prodcolor=trim($row['attribute3values']);
		$type3=trim($row['attribute3values']);
	}

	$prodid=intval($row['id']);
	$prodcode=trim($row['sku']);
	$prodname=trim($row['name']);
	$proddesc=inpval($row['description']);
	$prodcats=trim($row['categories']);
	$title=$prodname.'-'.$prodid;
	
	$parentid=intval($row['parent']);
	$prodstatus = intval($row['published']);
	$sortby = intval($row['position']);
	//$prodimg = trim($row['images']);
	//$prodgallery = trim($row['galleryimages']);
	$prodprice=trim($row['regularprice']);

	$produrl=str_replace('--','-',strtolower(preg_replace('/[^a-z\d]+/i', '-', $title)));

	$sqlin="select sku, regularprice from `wc_products` where parent like '%$prodid' and sku!='' order by position";
	$res=query_first($sqlin);
	$prodcode=trim($res['sku']);
	$sqlin="select regularprice from `wc_products` where parent like '%$prodid' and regularprice>0";
	$res=query_first($sqlin);
	if($res['regularprice']>0)$prodprice=trim($res['regularprice']);

	if($type1=='')$type1='NA';
	if($type2=='')$type2='NA';
	if($type3=='')$type3='NA';

	$sqlin="select prodid, prodcode from ccd9products where prodid='$prodid'";
	$res=query_first($sqlin);
	if($res['prodid']>0){}else{

		$query = "insert into ccd9products ( prodid, prodcode, prodcats, type1, type2, type3, prodcolor, prodname, proddesc, prodsize, prodprice, prodstatus, sortby, entrydate, parentid, produrl) values('$prodid', '$prodcode', '$prodcats', '$type1', '$type2', '$type3', '$prodcolor', '$prodname', '$proddesc', '$prodsize', '$prodprice', '$prodstatus', '$sortby',  CURDATE(), '$parentid', '$produrl') ";

		//echo $query.'<br>';

		$mysqli->query($query);

		if($prodid>0){
			Storearray(trim($prodcats), $prodid, 'ccd9prod2cat','2');
			storearray(trim($type1), $prodid, 'ccd9prod2type1','3');
			storearray(trim($type2), $prodid, 'ccd9prod2type2','4');
			storearray(trim($type3), $prodid, 'ccd9prod2type3','7');

		}

	}

	//exit();
}


exit();



//echo $n;
?>