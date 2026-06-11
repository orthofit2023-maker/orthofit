<?php
include("db5conn.php");
if($_POST['usercode']!=""){
	$loginname=$_POST['usercode'];
	$result= $mysqli->query("select loginid, email from ccd9user where email='$loginname' and status='1'");
	$row = $result->fetch_array();
	if ($row['loginid']>0){
		$newpasswd=rand(5, 15).date("ism");
		$passwd = encyrptPassword($newpasswd);

		$mysqli->query("update ccd9user set passwd='$passwd' where loginid='".$row['loginid']."'") or die("Invalid login query 0");

		$msg="Please note your new password as below: <br><br>";
		$msg.="Login Details as follows:";
		$msg.="<br>Username: ".$loginname;
		$msg.="<br>Password: ".trim($newpasswd);
		$msg.="<br><br>Regards<br>$adminuser";
		$subject = "$adminuser Password Request";

		sendsmtpmail($row['email'],$subject,$msg);

		$msg = "Password sent to your registered email address.";

		$mysqli->query("update ccd9user set failedlogin='0', faileddate='' where loginid='".$row['loginid']."'") or die("Invalid login query 1");

	}else{
		$msg = "Invalid login name or your account is not active!";
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
        <p class="panel-heading no-collapse">Forgot Password?</p>
        <div class="panel-body">
            <form name="frm" method="post" onsubmit="return validate()">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" class="form-control span12"  name="usercode">
                </div>
                <input type='Submit' value="Sign In" class="btn btn-primary pull-right">
                <div class="clearfix"></div>
            </form>
        </div>
    </div>
    <p><a href="login.php">Sign In</a></p>
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
	}
}

document.frm.usercode.focus();
//-->
</SCRIPT>    
  
</body></html>
