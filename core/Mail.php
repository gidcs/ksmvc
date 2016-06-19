<?php

date_default_timezone_set('Etc/UTC');

class Mail{
	static private $_smtp;
	
	static public function set($smtp_array=[]){
		self::$_smtp = $smtp_array;
	}
	
	static public function send($receiver='', $subject='', $body=''){
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->Host = self::$_smtp['host'];
		$mail->Port = self::$_smtp['port'];
		$mail->SMTPSecure = self::$_smtp['secure'];
		$mail->SMTPAuth = self::$_smtp['auth'];
		$mail->Username = self::$_smtp['username'];
		$mail->Password = self::$_smtp['password'];
		$mail->setFrom(self::$_smtp['username'], self::$_smtp['name']);
		$mail->addAddress($receiver);
		$mail->Subject = $subject;
		$mail->isHTML(true);
		$mail->Body = $body;
		
		if (!$mail->send()) {
			echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
			echo "Message sent!";
		}
		
	}
	
}

