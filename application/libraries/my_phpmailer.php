<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class My_PHPMailer {
	public function My_PHPMailer()
	{
		require_once ('PHPMailer/class.phpmailer.php' );
	}

	function send_email_simple($email, $subject, $body) {
		$mail = new PHPMailer();
		$mail->CharSet = "UTF-8";
		$mail->AddAddress($email);
		$mail->IsMail();
		$mail->From     = 'info@computersneaker.com';
		$mail->FromName = 'Computer Sneaker';
		$mail->IsHTML(true);
		$mail->Subject  =  $subject;
		$mail->Body     =  $body;
		return $mail->Send();
	}
	
	public function send_email($email, $subject, $body) {
		$mail = new PHPMailer();
		$mail->CharSet = "UTF-8";
		$mail->IsSMTP(); // we are going to use SMTP
		$mail->SMTPAuth   = true; // enabled SMTP authentication
		$mail->SMTPSecure = "ssl";  // prefix for secure protocol to connect to the server
		$mail->Host       = "smtp.gmail.com";      // setting GMail as our SMTP server
		$mail->Port       = 465;                   // SMTP port to connect to GMail
		
		$mail->Username   = "emailrestart@gmail.com";  // GMAIL username
		$mail->Password   = "Goog4444";            // GMAIL password
		
		$mail->SetFrom('info@yourdomain.com', 'Firstname Lastname');  //Who is sending the email
		$mail->AddReplyTo("response@yourdomain.com","Firstname Lastname");  //email address that receives the response
		$mail->Subject    = $subject;
		$mail->Body      = $body;
		$mail->AltBody    = "Plain text message";
		$mail->AddAddress($email, "John Doe");
		
		//$mail->AddAttachment("images/phpmailer.gif");      // some attached files
		//$mail->AddAttachment("images/phpmailer_mini.gif"); // as many as you want
		return $mail->Send();
	}
}
	