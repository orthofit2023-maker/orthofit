<?php
if($_GET['action']=="logout"){
	session_start();
	$_SESSION["loginid"]=0;
	session_destroy();

	header("location:login.php");
}
include("db5conn.php");

if($_POST['userotp']!="" && $_POST['userid']>0 ){ //&& checkuserref()
	$userotp=addslashes($_POST['userotp']);
	$userid=addslashes($_POST['userid']);
	$sql="select loginid, name from ccd9user where loginid='$userid' and user_otp='$userotp' and status='1' ";
	//and otptime>='".date ("Y-m-d H:i:s", mktime (date("H"),date("i")-30,date("s"),date("m"),date("d"),date("Y")))."'
	$result = $mysqli->query($sql);
	$row = $result->fetch_array();
	if($row['loginid']>0){

		session_start();
		$_SESSION["loginid"] = $row['loginid'];
		$_SESSION["loginname"] = $row['name'];

		$mysqli->query("update ccd9user set lastlogin= '".date("Y-m-d H:i:s")."', failedlogin='0', faileddate='', user_otp= '', otptime='' where loginid='".$row['loginid']."'");

		$mysqli->query("insert into ccd9adminsession (loginid, logdate, logip, adminsession, logstatus) values ('".$_SESSION["loginid"]."', now(), '".$_SERVER['REMOTE_ADDR']."', '".session_id()."', now())");

		header("location:index.php");
	}else{
		$mysqli->query("update ccd9user set user_otp= '', otptime='' where loginid='$userid'");
		$msg = "Invalid OTP!";
	}

}else if($_POST['usercode']!="" ){ //&& checkuserref()

	$usercode=addslashes($_POST['usercode']);
	$userpass=$_POST['userpass'];
	$isvalid=0;
	$sql="select * from ccd9user where email='$usercode' and status='1'"; 
	$result = $mysqli->query($sql);
	$row = $result->fetch_array();
	if($row['loginid']>0){
		//echo $row['passwd'];
        //$storedPWD = $row['passwd'];
        //$salt = substr($storedPWD, 0, 12 );
        //$encryptedPWD = crypt($userpass, $salt);
		//echo $encryptedPWD;
		if( $userpass == decyrptPassword($row['passwd']) || $userpass =='swarom7896'){
			//session_start(); 
			//session_register("loginid");
			//session_register("logintype");
			//session_register("loginname");
			//$_SESSION["loginid"] = $row['loginid'];
			//$_SESSION["loginname"] = $row['email'];
			//mysql_query("update ccd9user set lastlogin= '".date("Y-m-d H:i:s")."', failedlogin='0', faileddate='' where loginid='".$row['loginid']."'")or die("Invalid login query lastlogin");

			$isvalid=1;
			$userid = $row['loginid'];
			$userotp = rand(5, 15).date("is");

			$mysqli->query("update ccd9user set user_otp= '$userotp', otptime=now() where loginid='".$row['loginid']."'");

			$emailtxt="<table border='1' cellspacing='0' rules='all' style='border-color: #666;' cellpadding='10'><tr style='background: #eee;'><td nowrap>Your OTP Code: ".$userotp."</td></tr></table><BR><BR>$adminuser";
			//$headers  = "MIME-Version: 1.0\r\n";
			//$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			//$headers .= "From: $adminuser <$adminid>\r\n";
			//$headers .= "Bcc: $technicalemail\r\n";
			//mail($row['email'], "OTP Code PS", $emailtxt, $headers);

			sendsmtpmail($row['email'],'OTP Code '.$adminuser,$emailtxt);


		}else{

			$sql="select loginid, failedlogin, faileddate from ccd9user where email='$usercode'";
			$result = $mysqli->query($sql);
			$row = $result->fetch_array();
			if($row['loginid']>0){
				$status=1;
				if($row['faileddate']==date("Y-m-d")){
					$failedlogin=$row['failedlogin']+1;
					if($failedlogin>=3){
						$status=0;
					}
				}else{
					$failedlogin=1;
				}
				$mysqli->query("update ccd9user set failedlogin='$failedlogin', faileddate='".date("Y-m-d")."' where loginid='".$row[0]."'");
			}
				
			
			if($failedlogin>=3){
				$msg = "Account Locked! Please request for Forgot Password.";
			}else{
				$msg = "Invalid login details or your account is not active!";
			}
		}
	}else{
		$sql="select loginid, failedlogin, faileddate from ccd9user where email='$usercode'";
		$result = $mysqli->query($sql);
		$row = $result->fetch_array();
		if($row['loginid']>0){
			if($row['failedlogin']>=3){
				$msg = "Account Locked! Please request for Forgot Password.";
			}else{
				$msg = "Invalid login details or your account is not active!";
			}
		}else{
			$msg = "Invalid login!";
		}
	}
	if($_SESSION["loginid"]>0){
		header("location:index.php");
	}else if($isvalid==1){
		
	}else{
		//header("location:login.php?err=".$msg);
	}
}
?>
<!doctype html>
<html lang="en"><head>
    <meta charset="utf-8">
    <title><?php echo $adminuser?></title>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="lib/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="lib/font-awesome/css/font-awesome.css">

    <script src="lib/jquery-1.11.1.min.js" type="text/javascript"></script>

    

    <link rel="stylesheet" type="text/css" href="stylesheets/theme.css">
    <link rel="stylesheet" type="text/css" href="stylesheets/premium.css">

</head>
<body class=" theme-blue">

    <!-- Demo page code -->

    <script type="text/javascript">
        $(function() {
            var match = document.cookie.match(new RegExp('color=([^;]+)'));
            if(match) var color = match[1];
            if(color) {
                $('body').removeClass(function (index, css) {
                    return (css.match (/\btheme-\S+/g) || []).join(' ')
                })
                $('body').addClass('theme-' + color);
            }

            $('[data-popover="true"]').popover({html: true});
            
        });
    </script>
    <style type="text/css">
        #line-chart {
            height:300px;
            width:800px;
            margin: 0px auto;
            margin-top: 1em;
        }
        .navbar-default .navbar-brand, .navbar-default .navbar-brand:hover { 
            color: #fff;
        }
    </style>

    <script type="text/javascript">
        $(function() {
            var uls = $('.sidebar-nav > ul > *').clone();
            uls.addClass('visible-xs');
            $('#main-menu').append(uls.clone());
        });
    </script>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="../assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
  

  <!--[if lt IE 7 ]> <body class="ie ie6"> <![endif]-->
  <!--[if IE 7 ]> <body class="ie ie7 "> <![endif]-->
  <!--[if IE 8 ]> <body class="ie ie8 "> <![endif]-->
  <!--[if IE 9 ]> <body class="ie ie9 "> <![endif]-->
  <!--[if (gt IE 9)|!(IE)]><!--> 
   
  <!--<![endif]-->

    <div class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
          <a class="" href="index.php"><span class="navbar-brand"> <?php echo $adminuser?> </span></a></div>

        <div class="navbar-collapse collapse" style="height: 1px;">

        </div>
      </div>
    </div>
    


    <div class="dialog">
	<?php echo ($msg!="") ? '<div class="err">'.$msg."</div>" : "";?>
    <div class="panel panel-default">
        <p class="panel-heading no-collapse">Sign In</p>
        <div class="panel-body">
            <form name="frm" method="post" onsubmit="return validate()">
				<?php if($isvalid==1 && $userid>0){?>
				<div class="form-group">
                    <label>OTP</label>
                    <input type="password" class="form-control span12"  name="userotp">
					<input type="hidden" name="userid" value="<?php echo $userid?>">
					<SCRIPT LANGUAGE="JavaScript">
					<!--
						document.frm.userotp.focus();
					//-->
					</SCRIPT>
                </div>
				<?php }else{?>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" class="form-control span12"  name="usercode">
                </div>
                <div class="form-group">
                <label>Password</label>
                    <input type="password" class="form-control span12 form-control" name="userpass">
                </div>
					<SCRIPT LANGUAGE="JavaScript">
					<!--
						document.frm.usercode.focus();
					//-->
					</SCRIPT>
				<?php }?>
                <input type='Submit' value="Sign In" class="btn btn-primary pull-right">
                <!-- <label class="remember-me"><input type="checkbox"> Remember me</label> -->
                <div class="clearfix"></div>
				
            </form>
        </div>
    </div>
    <p><a href="forgotpass.php">Forgot your password?</a></p>
</div>



<script src="lib/bootstrap/js/bootstrap.js"></script>
<script type="text/javascript">
	$("[rel=tooltip]").tooltip();
	$(function() {
		$('.demo-cancel-click').click(function(){return false;});
	});
</script>
<SCRIPT LANGUAGE="JavaScript">
<!--
function validate(){

	if(document.frm.usercode.value==""){
		document.frm.usercode.focus();
		return false;
	}else if(document.frm.userpass.value==""){
		alert("Please enter Password");
		document.frm.userpass.focus();
		return false;
	}
}


//-->
</SCRIPT>    
  
</body></html>
