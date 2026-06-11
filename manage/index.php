<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");
include("top.php");
$sql="SELECT e.* from ccd9events e where e.datedue>=DATE_ADD(CURDATE(), INTERVAL -30 DAY) and (e.loginid='$userid' or e.userid='$userid' or e.userid='0') order by e.datedue";
$result = $mysqli->query($sql);
$num_rows = mysqli_num_rows($query); 
$i=0; $events="";

?>
<div class="main-content">
<link href='lib/fullcalendar-1.6.4/fullcalendar/fullcalendar.css' rel='stylesheet' />
<link href='lib/fullcalendar-1.6.4/fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='lib/fullcalendar-1.6.4/fullcalendar/fullcalendar.min.js'></script>

<script>

	$(document).ready(function() {
	
		//var date = new Date();
		//var d = date.getDate();
		//var m = date.getMonth();
		//var y = date.getFullYear();
		
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month'
			},
			dayClick: function(date, jsEvent, view) {
				openNewForm(date.getDate()+'/'+(parseInt(date.getMonth())+1)+'/'+date.getFullYear(),date.toDateString());
			},
			editable: true,
			events: [
			<?php while($row=$result->fetch_array()){ $i++;
				list($y,$m,$d)=explode("-",$row["datedue"]);
				$events=$events."{
					title: 'Task - ".inpval($row["projtype"])." - ".inpval($row["contact"])." - ".inpval($row["projdescr"])."',
					start: new Date(".intval($y).",".(intval($m)-1).",".intval($d)."),
					allDay: true,";
					if($row["status"]==0){
						$events=$events."color: '#669900',";
					}else{
						$events=$events."textColor: 'black',color: '#FFFFCC',";
					}
				$events=$events."url: '".$row["projid"]."', ptype: '1'";
				$events=$events."}";
				if($num_rows>$i){
					$events=$events.",";
				}
				}
				if($num_rows>0){
					$events=$events.",";
				}
				echo $events;
				?>
			],
			eventClick: function(event) {
				if (event.url) {
					//window.open(event.url);
					if(event.ptype=="2"){
						//window.open("vendata.php?dataid="+event.url);
					}else{
						var date = event.start;
						openNewForm(date.getDate()+'/'+(parseInt(date.getMonth())+1)+'/'+date.getFullYear(),date.toDateString(),event.url);
					}
					return false;
				}
			}
		});
		
	});

</script>

<style>
#calendar {
	margin: 0 auto;
}
</style>
<div id='calendar'></div>
</div>
<?php 
//include("comment-box.php"); 
include("bot.php");
?>