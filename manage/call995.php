<?php

session_start();
ini_set('max_execution_time', 10000);
include("db5conn.php");

$sqllist="SELECT c.prodid, c.prodcode, concat(c.prodcode) as sku, c.prodname, c.prodprice, c.prodcats FROM `ccd9products` c";

$result = $mysqli->query($sqllist);
$num_rows = mysqli_num_rows($result);
if ($num_rows>0){ $i=0; 
		while($row=$result->fetch_array()){  $i++;
			$prodid=intval($row['prodid']);
			$prodcode=trim($row['prodcode']);
			$prodname=trim($row['prodname']);
			$prodcats=trim($row['prodcats']);
			$title=$prodname.'-'.$prodid;

			$prodprice=trim($row['regularprice']);

			$produrl=str_replace('--','-',strtolower(preg_replace('/[^a-z\d]+/i', '-', $title)));
			echo $produrl.'<br>';
			$mysqli->query("update `ccd9products` set produrl='$produrl' where prodid='$prodid'");
		}
}

exit();

//$mysqli->query("delete from ccd9stocks");

$sqllist="SELECT c.prodid, c.prodcode, concat(c.prodcode) as sku, c.prodname, c.type2, t1c.typeid as fitid, t1c.typevalue1 as typefit, t3c.typeid as colid, t3c.typename, t3c.typevalue1 from ccd9products c join ccd9prod2type1 t1 on c.prodid=t1.prodid join ccd9types t1c on t1.typeid=t1c.typeid join ccd9prod2type3 t3 on c.prodid=t3.prodid join ccd9types t3c on t3.typeid=t3c.typeid and t3c.opt=7  left join ccd9prod2cat t on t.prodid=c.prodid  where 1=1 and c.prodstatus='1' and t.catid in (32)  group by c.prodid, t3.typeid, t1.typeid order by c.prodid desc;"; //,t1c.typevalue1,t3c.typevalue1
$result = $mysqli->query($sqllist);
$num_rows = mysqli_num_rows($result);
if ($num_rows>0){ $i=0; 
		while($row=$result->fetch_array()){  $i++;
			$prodid = $row['prodid'];  
			//echo $i.'='.$row['prodname'].' ('.$row['typefit'].' - '.$row['typename'].')<br> ';
			$prodsize=$row['type2'];
			$arrsize=array();
			if(strstr($prodsize,',')){
				$arrsize=explode(',', dbval($prodsize));
			}else{
				if($prodsize!='')$arrsize[0]=dbval($prodsize);
			}
			for($n=0;$n<count($arrsize);$n++){
				if(trim($arrsize[$n])!='' && trim($arrsize[$n])!='NA'){
				$prodcode = trim($row['sku']).trim($arrsize[$n]);
				echo $prodcode.'<br>';
				if(trim($row['prodcode'])=='')$prodcode='';
				//$mysqli->query("insert into ccd9stocks (prodid, type1, type2, type3, prodsku) values ('".$row['prodid']."', '".$row['fitid']."', '".trim($arrsize[$n])."', '".$row['colid']."', '$prodcode')");
				}

			}
			

		}
			echo '<br>--------------------------end------------------------<br>';
}
?>