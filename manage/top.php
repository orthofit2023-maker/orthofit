<?php
session_start();
if($filename!="login.php" && $filename!="changepass.php" && $filename!="index.php" && $filename!="forgotpass.php" && $filename!="orderchart.php" && $filename!="promomailer.php" && $filename!="customer.php" && $filename!="prodphoto.php"){
	$sqlchk = "select u.accessid from ccd9accessfiles a join ccd9files2user u on a.fileid=u.fileid where u.userid='".$_SESSION['loginid']."' and a.filename='$filename'";
	$result = $mysqli->query($sqlchk);
	$row = $result->fetch_array();
	if($row['accessid']>0){
	}else{
		header("location:index.php?msg=Invalid Access");
	}

	$sql="select logid from ccd9adminsession where loginid='".$_SESSION['loginid']."' and adminsession='".session_id()."'";
	$result = $mysqli->query($sql);
	$row = $result->fetch_array();
	if($row['logid']>0){
		$mysqli->query("update ccd9adminsession set logstatus=now() where loginid='".$_SESSION["loginid"]."' and adminsession='".session_id()."'");
	}else{
		header("location:login.php?action=logout");
		exit();
	}
} 
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php echo $adminuser?></title>
<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link href='//fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="lib/bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="lib/font-awesome/css/font-awesome.css">
<link rel="stylesheet" type="text/css" href="stylesheets/theme.css">
<link rel="stylesheet" type="text/css" href="stylesheets/premium.css">
<script type="text/javascript" src="lib/jquery-1.7.2.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="stylesheets/chosen.css">
<script type="text/javascript">
$(function() {
$( ".datepicker" ).datepicker({ dateFormat: 'dd/mm/yy' });
$( ".timepicker").timepicker({
    interval: 15,
    minTime: '7:00am',
    maxTime: '11:59pm',
    startTime: '7:00',
    dynamic: false,
    dropdown: true,
    scrollbar: true
});
});
</script>

<style>
.ui-timepicker-standard a {font-size:14px; padding:2px;}
</style>
<script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="/ckfinder/ckfinder.js"></script>
</head>
<body class="theme-blue">
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



<!--[if lt IE 7 ]> <body class="ie ie6"> <![endif]-->
<!--[if IE 7 ]> <body class="ie ie7 "> <![endif]-->
<!--[if IE 8 ]> <body class="ie ie8 "> <![endif]-->
<!--[if IE 9 ]> <body class="ie ie9 "> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> 

<!--<![endif]-->

    <div class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="" href="index.php"><span class="navbar-brand" style="color:#ffffff"> <?php echo $adminuser;?> </span></a></div>

        <div class="navbar-collapse collapse" style="height: 1px;">
          <ul id="main-menu" class="nav navbar-nav navbar-right">
            <li class="dropdown hidden-xs">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <span class="glyphicon glyphicon-user padding-right-small" style="position:relative;top: 3px;"></span> <?php echo $_SESSION["loginname"];?><i class="fa fa-caret-down"></i>
                </a>

              <ul class="dropdown-menu">
				<li><a tabindex="-1"><?php echo $_SESSION["logindesg"];?></a></li>
				<li class="divider"></li>
                <li><a tabindex="-1" href="changepass.php">Change Password</a></li>
                <li class="divider"></li>
                <li><a tabindex="-1" href="login.php?action=logout">Logout</a></li>
              </ul>
            </li>
          </ul>

        </div>
      </div>
    </div>
<?php //if(isset($_SESSION['loginid'])){?>
    <div class="sidebar-nav">
    <ul>
	<li><a href="index.php" class="nav-header"> Home </a></li>
    <?php
	$sql = "select menutitle from ccd9accessfiles where menutitle!='' and ismenu='1' group by menutitle order by menutitle";
	$result = $mysqli->query($sql);
	//echo num_rows($result);
	while($rowm=$result->fetch_array()){
		
	$res=query_first("select accessid from ccd9files2user where userid='".$_SESSION['loginid']."' and fileid in (select fileid from ccd9accessfiles where menutitle='".$rowm[0]."' and ismenu='1')");
	if($res[0]>0){
		$res=query_first("select accessid from ccd9files2user where userid='".$_SESSION['loginid']."' and fileid in (select fileid from ccd9accessfiles where menutitle='".$rowm[0]."' and filename='$filename') ");
	?>
    <li><a href="#" data-target=".<?php echo str_replace(" ","",strtolower($rowm[0]))?>" class="nav-header<?php if($res[0]>0){ echo ' collapsed';}?>" data-toggle="collapse"> <?php echo $rowm[0];?><i class="fa fa-collapse"></i></a> 
    <li><ul class="<?php echo str_replace(" ","",strtolower($rowm[0]))?> nav nav-list collapse<?php if($res[0]>0){ echo ' in';}?>">
		<?php 
		
		$sqlin="select fileid, filetitle, fileopt, filename, menutitle from ccd9accessfiles where menutitle='".$rowm[0]."' and ismenu='1' group by filetitle order by sortby";
		//echo $sqlin;
		$resultin = $mysqli->query($sqlin);
		while($row=$resultin->fetch_array()){
			if(checkuseraccess($_SESSION['loginid'], $row['fileid'])){  
				if($row['filename']==$filename || str_replace('s.php','.php',$row['filename'])==$filename || str_replace('ies.php','y.php',$row['filename'])==$filename){ $class=" class='active'";}else{$class="";}
				echo '<li '.$class.'><a href="'.$row['filename'].'"><span class="fa fa-caret-right"></span> '.$row['filetitle'].'</a></li>';
			}
		}?>
    </ul>
	</li>
	<?php } }?> 
    <li><a href="login.php?action=logout" class="nav-header"> Logout </a></li>
    </ul>
    </div>
<?php //}?>
<div class="content">
