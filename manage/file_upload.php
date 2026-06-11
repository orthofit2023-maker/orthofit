<?php
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");
$dir = $imgpath;
$n=0;
if (is_dir($dir)){
	if ($dh = opendir($dir)){
		while (($file = readdir($dh)) !== false){
			if($file!='.' && $file!='..'){
			  
			  $n++;
			}
		}
		closedir($dh);
	}
}

if(!empty($_FILES)){
	$fileName = $_FILES['file']['name'];
	//$dstfile = $imgpath.$fileName;
	$newName=trim($_POST['prodname']).'-'.trim($_POST['prodcol']).'-'.trim($_POST['prodfit']).'-'.trim($_POST['prodid']).'-0'. ($n+1).'.jpg';
	$dstfile = $imgpath.$newName;
	if(move_uploaded_file($_FILES['file']['tmp_name'],$imgpath.$fileName)){
		$image = imagecreatefromstring(file_get_contents($imgpath.$fileName));
		imagejpeg($image, $dstfile);
		imagedestroy($image);
		if($fileName!=$newName){
			unlink($imgpath.$fileName);
		}
		//echo $fileName.'<br>';
		
		

		//$photofile = imagecreatefromjpeg($dstfile);
		//imagejpeg($photofile,$rawfile1);

	}
}
?>