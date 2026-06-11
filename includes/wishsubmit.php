<?php
include("../manage/db5conn.php");
$prodid=inpval($_POST['prodid']);
$userid=inpval($_POST['userid']);
$sessid=inpval($_POST['sessid']);
if($prodid>0 && ($userid>0 || $sessid!='')){
$errcnt=0;	

	$row= query_first("select wishid from ccd9wishlist where (compid='$userid' or sessionid='$sessid') and prodid='$prodid'");
	if($row['wishid']>0){
		$sqlin="delete from ccd9wishlist where (compid='$userid' or sessionid='$sessid') and prodid='$prodid'";
		$mysqli->query($sqlin);
		$errmsg=0;
	}else{
		$row= query_first("select count(*) as cnt from ccd9wishlist where compid='0' and sessionid='$sessid' ");
		if($row['cnt']>5){
			$errcnt=1; $errmsg=3;
		}
		if($errcnt==0){
			$sqlin="INSERT INTO ccd9wishlist (compid, prodid, sessionid) VALUES ('$userid', '$prodid', '$sessid')";
			$mysqli->query($sqlin);
			$errmsg=1;
		}
	}
	echo $errmsg;
}else{
	$errmsg=2;
	echo $errmsg;
}
?>