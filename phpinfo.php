<?php
include("manage/db5conn.php");

$adminid = "orders@orthofit.in";  
$adminuser = "Orthofit Healthcare";

$fromid=$adminid;
$fromuser=$adminuser;

$to="samir.sudrik@gmail.com";
$subject="mailhostbox subject";
$emailtext=" mailhostbox email";


$SmtpServer="smtp.orthofit.in"; //"us2.smtp.mailhostbox.com"; //smtp.orthofit.in
$SmtpPort="587";
$SmtpUser="orders@orthofit.in";
$SmtpPass="wq$QdVS0";


	$mail = new PHPMailer();
	$mail->isSMTP();
	$mail->Host = $SmtpServer;
	$mail->SMTPAuth = true;
	$mail->Username = $SmtpUser;
	$mail->Password = $SmtpPass;
	$mail->setFrom($SmtpUser);

	$mail->From = $fromid;
	$mail->FromName = $fromuser;
	$mail->AddAddress($to);

	$mail->IsHTML(true); 
	$mail->Subject = $subject;
	$mail->Body    = $emailtext;

	if(!$mail->Send()) {
	   echo 'Message could not be sent.';
	   echo 'Mailer Error: ' . $mail->ErrorInfo;
	   exit();
	} 


exit();



phpinfo();
exit();

$db = Database::getInstance();
$mysqli = $db->getConnection();


echo "num_rows - before 0";
checkhraccess(1);

function checkhraccess($id){
	
	$db = Database::getInstance();
	$mysqli = $db->getConnection();

	$result = $mysqli->query("SELECT accessid FROM files2admin where fileid in (1,2) and accesstype in (1,2) and loginid='$id'");
	if(mysqli_num_rows($result)>0){
		echo "<br><br>num_rows - after ".mysqli_num_rows($result);
	}else{
		echo "<br><br> failed";
	}
}



class Database {
	private $_connection;
	private static $_instance; //The single instance
	var $_host = "localhost";
	var $_username = "cmsolymp_dbuser";
	var $_password = "4K1ywcHOhhzO9";
	var $_database = "cmsolymp_cms";
	/*
	Get an instance of the Database
	@return Instance
	*/
	public static function getInstance() {
		if(!self::$_instance) { // If no instance then make one
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	// Constructor
	private function __construct() {
		$this->_connection = new mysqli($this->_host, $this->_username, 
			$this->_password, $this->_database);
	
		// Error handling
		if(mysqli_connect_error()) {
			trigger_error("Failed to conencto to MySQL: " . mysqli_connect_error(),
				 E_USER_ERROR);
		}
	}
	// Magic method clone is empty to prevent duplication of connection
	private function __clone() { }
	// Get mysqli connection
	public function getConnection() {
		return $this->_connection;
	}
}


?>