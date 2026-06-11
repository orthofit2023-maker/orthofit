<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");

$arrperms = array();
$arrperms[1]="Add";
$arrperms[2]="Edit";
$arrperms[3]="Approve";
$arrperms[4]="Access";
$arrperms[5]="Download";
$arrperms[6]="Delete";
$arrperms[7]="Scanning";

if($_POST['userid']>0 && checkuserref()){
	$userid=$_POST['userid'];
		$mysqli->query("delete from ccd9files2user where userid='$userid'");
	
		for($n=1;$n<=$_POST['toti'];$n++){

			$permid=$_POST['permid'.$n];
			$fileid=$_POST['fileid'.$n];
			if($permid!="" && $fileid!=""){
				if(strstr($fileid, ",")){
					$arrfileid = explode(",", $fileid);
				}else{
					$arrfileid[0] = $fileid;
				}

				if(is_array($permid)){
					$arrpermid = $permid;
				}else{
					$arrpermid[0] = $permid;
				}
				for($x=1;$x<=count($arrfileid);$x++){
					for($y=0;$y<=count($arrpermid);$y++){
						if($arrfileid[$x]!="" && $arrpermid[$y]!=""){
							$sqlin="insert into ccd9files2user (userid, fileid, accesstype) values ('$userid', '".$arrfileid[$x]."', '".$arrpermid[$y]."' )";
							$mysqli->query($sqlin);
						}
					}
				}
			}
		}
		$msg="Permissions updated!";
	//echo $sqlin;
	//exit();
	header("Location:adminusers.php?msg=$msg");

}else if($_GET['userid']!="" && $_GET['userid']!="NEW"){
	$userid=$_GET['userid'];
	$res=query_first("select * from ccd9user where loginid='$userid'");
	$user_email=dbval($res['email']);
	$user_status=dbval($res['status']);
	if($_GET['userid']!="NEW"){
		$mysqli->query("insert into ccd9adminlog (loginid, logtable, logtype, logip, logdescr) values ('".$_SESSION['loginid']."', '3', '4', '".$_SERVER['REMOTE_ADDR']."', '$userid')");
	}
}
if($_GET['msg']!=""){
	$msg=$_GET['msg'];
}
include("top.php");?>
<div class="header">
	<h1 class="page-title">Admin Users</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Admin Users</li>
	</ul>
</div>
<div class="main-content">
<?php echo ($msg!="") ? '<div class="err">'.$msg."</div>" : "";?>
<form name="frm" method="post" action='adminusers.php'>
<div class="row">
  <div class="col-md-4">
        <div class="form-group">
          <label>Users</label>
			<select name="userid" class="form-control" onchange="document.location.href='adminusers.php?userid='+this.value">
			<option value="">Select User</option>
			<?php 
			$result = $mysqli->query("select * from ccd9user order by name") or die(mysql_error());
			while($row=$result->fetch_array()){?>
			<option value="<?php echo $row['loginid']?>" <?php echo ($row['loginid']==$userid) ? "selected" : "";?>><?php echo ucwords($row['name'])?></option>
			<?php }?>
          </select>
        </div>

		<div class="btn-toolbar list-toolbar">
		  <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
		</div>
	</div>
	<div class="col-md-8" style="height:400px;overflow:auto;">
		<?php if($userid!=""){?>
		<div class="form-group">
			<table class="table">
              <thead>
                <tr>
                  <th>Module</th>
                  <th colspan="5">Permissions</th>
                </tr>
              </thead>
              <tbody>
			  <?php 
				$i=0; $oldtitle=""; $fileids="0"; $oldmenu="";
				$sql="select fileid, filetitle, fileopt, menutitle from ccd9accessfiles where fileopt!='' order by menutitle, sortby";
				$result = $mysqli->query($sql) or die(mysql_error());
				while($row=$result->fetch_array()){
					if($oldtitle!=$row['filetitle'] && $oldtitle!=""){
					$i++;
					?>
					<INPUT TYPE="hidden" NAME="fileid<?php echo $i?>" value="<?php echo $fileids;?>">
					<tr> 
					  <td nowrap><?php echo $oldmenu;?></td>
					  <td nowrap><?php echo $oldtitle;?></td>
					  <?php //echo $permids;
						$arrids=explode(",",$permids);
						for($n=0;$n<count($arrids);$n++){
							$checked="";
							if(checkuseraccess($userid, $oldfileid, $arrids[$n])){
								$checked=" checked";
								//echo $userid;
							}
							echo '<td nowrap><input type="checkbox" name="permid'.$i.'[]" value="'.$arrids[$n].'" '.$checked.'>&nbsp;';
							echo $arrperms[$arrids[$n]];
							echo "</td>";
						}
						for($p=$n;$p<4;$p++){
								echo "<td>&nbsp;</td>";
							}
						?>
					</tr>
				<?php 
						$oldtitle=""; $oldmenu="";
						$fileids="0";
					}
					$oldtitle=$row['filetitle']; $oldmenu=$row['menutitle'];
					$permids=$row['fileopt'];
					$fileids=$fileids.",".$row['fileid'];
					$oldfileid = $row['fileid'];
				}
					$i++;
				?>
					<INPUT TYPE="hidden" NAME="fileid<?php echo $i?>" value="<?php echo $fileids;?>">
					<tr> 
					  <td nowrap><?php echo $oldmenu;?></td>
					  <td nowrap><?php echo $oldtitle;?></td>
					  <?php 
						$arrids=explode(",",$permids);
						for($n=0;$n<count($arrids);$n++){
							$checked="";
							if(checkuseraccess($userid, $oldfileid, $arrids[$n])){
								$checked=" checked";
							}
							echo '<td nowrap><input type="checkbox" name="permid'.$i.'[]" value="'.$arrids[$n].'" '.$checked.'>&nbsp;';
							echo $arrperms[$arrids[$n]];
							echo "</td>";
						}
						for($p=$n;$p<4;$p++){
								echo "<td>&nbsp;</td>";
							}
						?>
					  </td>
					</tr>
				<INPUT TYPE="hidden" NAME="toti" value="<?php echo $i;?>">
				</tbody>
			</table>
		</div>



		<?php }?>
    </div>
  </div>

    
</form>
</div>

<?php include("bot.php");?>