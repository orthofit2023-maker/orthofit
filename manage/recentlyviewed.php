<?php 
ini_set('display_errors', 1);
ini_set('max_execution_time', 3000);
include("db5conn.php"); 

$resdb=$mysqli->query("SELECT c.compid, c.username, c.email, c.countryid FROM `ccd9prodviewed` v join ccd9company c on v.compid=c.compid where v.compid>0 and TIMESTAMPDIFF(MINUTE, datemodified, now())<1200 and v.compid!=2 group by v.compid limit 0,1"); //   
$olduserid = 0;
while($resord=$resdb->fetch_array()){
	
	

    if($olduserid!=$resord['compid']){
        if($olduserid>0){
            if($n<6){
                list($caturl,$catid)=getcaturl($prodid); 
                $cartpg=$cartpg.callmore($catid,$countryid,$n,$stackurl,$userid);

            }

            $cartpg=$cartpg.'<tr/></tbody></table>';
            echo $cartpg;

            $emailtext=getpagedata('140');
            $subject=getpagetitle('140');
            
            $emailtext=str_replace("##customername##",$username,$emailtext);
            $emailtext=str_replace("##shoppingcart##",$cartpg,$emailtext);
            

            //$to=$email; 
            $to="samir@swarom.com";
            $technicalemail="samir.sudrik@gmail.com";

            sendsmtpmail($to,$subject,$emailtext,$technicalemail);
            echo '<br>-----------------'.$email.'-----------<br>';
        }

        $username=dbval($resord['username']);
        $email = $resord['email'];
        $countryid = $resord['countryid'];
        $userid = $resord['compid'];
        //echo $userid;

        $cartpg='
        <table border="0" cellpadding="5" cellspacing="0" style="width:600px;border:thin" width="600">
            <tbody><tr>
                ';
    }
        

                $result = $mysqli->query("select p.prodid, p.prodcode, p.prodname, p.produrl, ".($countryid==99 ? "p.prodprice" : "p.usdprice")." as price  from ccd9prodviewed c join ccd9products p on p.prodid=c.prodid where c.compid='$userid' group by c.prodid order by c.datemodified desc limit 0,6"); //

                echo "select p.prodid, p.prodcode, p.prodname, p.produrl, ".($countryid==99 ? "p.prodprice" : "p.usdprice")." as price  from ccd9prodviewed c join ccd9products p on p.prodid=c.prodid left join ccd9cart s on p.prodid=s.prodid and s.compid=c.compid and s.status=1 where c.compid='$userid' and isnull(s.status) group by c.prodid order by c.datemodified desc limit 0,6";
                  
                $cartrows = mysqli_num_rows($result);
                $n=0; $tot=0; $ordhasgc=0; 
                while($rescart=$result->fetch_array()){ $n++;
                    $prodid=$rescart['prodid'];
                    $showcur=($countryid==99 ? 'Rs. ': 'US $ ');
                    $prodimg=$stackurl.getprodimg($rescart['produrl'],'a','3');

                    $cartpg=$cartpg.'<td style="width:180px;" width="180" align="center" valign="top"><a href="'.getprodurl($rescart['produrl']).'" target="_blank"><img width="180" style="width:180px;" src="'.$prodimg.'"></a>';
                    $cartpg=$cartpg.'<br/><br/>'.dbval($rescart['prodname']).'<br/>'.$showcur.dbval($rescart['price']).'<br/></td>'; 
                    if($n%3==0) $cartpg=$cartpg.'<tr/><tr>';
                }


                



        $olduserid = $resord['compid'];
		
	
}
if($n<6){
    list($caturl,$catid)=getcaturl($prodid); 
    $cartpg = $cartpg.callmore($catid,$countryid,$n,$stackurl,$userid);

}

$cartpg=$cartpg.'<tr/></tbody></table>';
echo $cartpg;
$emailtext=getpagedata('140');
$subject=getpagetitle('140');

$emailtext=str_replace("##customername##",$username,$emailtext);
$emailtext=str_replace("##shoppingcart##",$cartpg,$emailtext);


//$to=$email; 
$to="samir@swarom.com";
$technicalemail="samir.sudrik@gmail.com";

sendsmtpmail($to,$subject,$emailtext,$technicalemail);
echo '<br>-----------------'.$email.'-----------<br>';
echo '<br>-----------------'.$username.'-----------<br>';


function callmore($catid,$countryid,$n,$stackurl,$userid){
    $x=6-$n; $cartpg='';
    $db = Database::getInstance();
	$mysqli = $db->getConnection(); 
	$result = $mysqli->query("select p.prodid, p.prodcode, p.prodname, p.produrl, ".($countryid==99 ? "p.prodprice" : "p.usdprice")." as price  from ccd9products p join ccd9prod2cat c on p.prodid=c.prodid left join ccd9cart s on p.prodid=s.prodid and s.compid='$userid' and s.status=1 where c.catid='$catid' and isnull(s.status) group by c.prodid order by c.prodid desc limit 0,$x"); //
    while($rescart=$result->fetch_array()){ 
        if($rescart['price']>0){ $n++;
            $prodid=$rescart['prodid'];
            $showcur=($countryid==99 ? 'Rs. ': 'US $ ');
            $prodimg=$stackurl.getprodimg($rescart['produrl'],'a','3');

            $cartpg=$cartpg.'<td style="width:180px;" width="180" align="center" valign="top"><a href="'.getprodurl($rescart['produrl']).'" target="_blank"><img width="180" style="width:180px;" src="'.$prodimg.'"></a>';
            $cartpg=$cartpg.'<br/><br/>'.dbval($rescart['prodname']).'<br/>'.$showcur.dbval($rescart['price']).'<br/></td>'; 
        }
        if($n%3==0) $cartpg=$cartpg.'<tr/><tr>';
    }

    return $cartpg;
}
?>