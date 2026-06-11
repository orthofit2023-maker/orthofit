<?php 
session_start();
ini_set('max_execution_time', 300);
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");
/*
$sql="select prodcode, produrl from ccd9products where prodcode in ('PS-TP0029-D-1','PS-TP0029-3','PS-TP0027-A-3','PS-JK0029-D-1','PS-JK0029-C-1','PS-TP0036-F-1') order by prodid";
$result = $mysqli->query($sql);
while($row = $result->fetch_array()){
	for($n=1;$n<20;$n++){	
		for($m=0;$m<4;$m++){
			$file =$imgpath.dbval($row['prodcode']).strtolower(chr($n+64)).$m.'.jpg';
			if(file_exists($file)){
				unlink($file);
				echo str_replace("/home/payals/public_html/",$stackurl,$file).'<BR>';
			}
			$file =$imgpath.dbval($row['prodcode']).strtolower(chr($n+64)).$m.'.webp';
			if(file_exists($file)){
				unlink($file);
				echo str_replace("/home/payals/public_html/",$stackurl,$file).'<BR>';
			}
			$delfile =$imgpath.dbval($row['produrl']).'-'.strtolower(chr($n+64)).$m.'.webp';
			if(file_exists($delfile)){
				unlink($delfile);
				echo str_replace("/home/payals/public_html/",$stackurl,$delfile).'<BR>';
			}
			$delfile =$imgpath.dbval($row['produrl']).'-'.strtolower(chr($n+64)).$m.'.jpg';
			if(file_exists($delfile)){
				unlink($delfile);
				echo str_replace("/home/payals/public_html/",$stackurl,$delfile).'<BR>';
			}
		}
	}
}

exit();


*/
if($_POST['prodcode']!='' && $_POST['n']!=''){
	$prodcode=inpval($_POST['prodcode']);
	$n=intval($_POST['n']);
	$sql="select prodcode, produrl from ccd9products where prodcode='$prodcode' order by prodid";
	$result = $mysqli->query($sql);
	if($row = $result->fetch_array()){
			
		$file =$imgpath.dbval($row['prodcode']).'.jpg';
		if(file_exists($file)){
			unlink($file);
			echo str_replace("/home/payals/public_html/",$stackurl,$file).'<BR>';
		}
		if($_POST['n']=='ALL'){
		for($n=1;$n<20;$n++){
			$file =$imgpath.dbval($row['prodcode']).strtolower(chr($n+64)).'.jpg';
			if(file_exists($file)){
				unlink($file);
				echo str_replace("/home/payals/public_html/",$stackurl,$file).'<BR>';
			}
			for($m=0;$m<4;$m++){
				$file =$imgpath.dbval($row['prodcode']).strtolower(chr($n+64)).$m.'.jpg';
				if(file_exists($file)){
					unlink($file);
					echo str_replace("/home/payals/public_html/",$stackurl,$file).'<BR>';
				}
				$file =$imgpath.dbval($row['prodcode']).strtolower(chr($n+64)).$m.'.webp';
				if(file_exists($file)){
					unlink($file);
					echo str_replace("/home/payals/public_html/",$stackurl,$file).'<BR>';
				}
				$delfile =$imgpath.dbval($row['produrl']).'-'.strtolower(chr($n+64)).$m.'.webp';
				if(file_exists($delfile)){
					unlink($delfile);
					echo str_replace("/home/payals/public_html/",$stackurl,$delfile).'<BR>';
				}
				$delfile =$imgpath.dbval($row['produrl']).'-'.strtolower(chr($n+64)).$m.'.jpg';
				if(file_exists($delfile)){
					unlink($delfile);
					echo str_replace("/home/payals/public_html/",$stackurl,$delfile).'<BR>';
				}
			}
		}}else{
			$file =$imgpath.dbval($row['prodcode']).strtolower(chr($n+64)).'.jpg';
			if(file_exists($file)){
				unlink($file);
				echo str_replace("/home/payals/public_html/",$stackurl,$file).'<BR>';
			}
			for($m=0;$m<4;$m++){
				$file =$imgpath.dbval($row['prodcode']).strtolower(chr($n+64)).$m.'.jpg';
				if(file_exists($file)){
					unlink($file);
					echo str_replace("/home/payals/public_html/",$stackurl,$file).'<BR>';
				}
				$file =$imgpath.dbval($row['prodcode']).strtolower(chr($n+64)).$m.'.webp';
				if(file_exists($file)){
					unlink($file);
					echo str_replace("/home/payals/public_html/",$stackurl,$file).'<BR>';
				}
				$delfile =$imgpath.dbval($row['produrl']).'-'.strtolower(chr($n+64)).$m.'.webp';
				if(file_exists($delfile)){
					unlink($delfile);
					echo str_replace("/home/payals/public_html/",$stackurl,$delfile).'<BR>';
				}
				$delfile =$imgpath.dbval($row['produrl']).'-'.strtolower(chr($n+64)).$m.'.jpg';
				if(file_exists($delfile)){
					unlink($delfile);
					echo str_replace("/home/payals/public_html/",$stackurl,$delfile).'<BR>';
				}
			}

		}
	}

	$msg= "Photo deleted successfully!";

	//echo $msg;
	exit();
	header("Location:photodel.php?msg=$msg&p=$p");

}

if($_GET['msg']!="")$msg=$_GET['msg'];
$p=$_GET['p'];
include("top.php");?>
<div class="header">
	<h1 class="page-title">Delete Photo</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Delete Photo</li>
	</ul>

</div>
<div class="main-content">
<?php echo ($msg!="") ? '<div class="err">'.$msg."</div>" : "";?>
<div class="row">
  <div class="col-md-6">
    <form name="frm" method="post" action='photodel.php'>
    <div id="myTabContent" class="tab-content">
		<div class="form-group col-md-6 left">
        <label>Product Code</label>
        <input type="text" name="prodcode" class="form-control">
        </div>
		<div class="form-group col-md-6">
        <label>Photo No</label>
        <input type="text" name="n" class="form-control">
        </div>
    </div>

    <div class="form-group">
      <button type="submit" class="btn btn-primary" name="btnsubmit">Delete</button>
    </div>
    </form>
  </div>
</div>
<?php include("bot.php");?>