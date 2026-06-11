<?php 
session_start();
ini_set('max_execution_time', 10000);
include("db5conn.php");

$mailbox = imap_open("{72.9.148.244}", "orders@payalsinghal.com", "oms@@ps$$789", OP_READONLY); //Note 1
if ($mailbox) {
	$num_msg = imap_num_msg($mailbox); //Note 2
	if ($num_msg > 0) {
		
		//Loop through all messages
		for ($i=$num_msg; $i>0; $i--) {
			$headers = imap_header($mailbox, $i); //Note 3
			
			//If the subject is "Contact Form Submission", read the body
			if ($headers->Subject == "Contact Form Submission") { 
				
				$body = imap_body($mailbox, $i); //Note 5
				//Run code to parse body information
			}
		}

	} else {
		echo "No messages in mailbox";
	}
	imap_close($mailbox);
} else {
	echo "Cannot open mailbox.";
}
?>