<?php

class Mail{
  private static $_smtp;
  
  public static function boot(){
    $smtp_array = [];
    $smtp = Option::where('name','LIKE','smtp_%')->get();
    if(empty($smtp)||count($smtp)!=7){
      die("Error: SMTP's settings is not completed yet.");
    }
    foreach($smtp as $s){
      self::$_smtp[$s->name] = $s->value;
    }
  }
  
  public static function send($receiver='', $subject='', $body=''){
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = self::$_smtp['smtp_host'];
    $mail->Port = self::$_smtp['smtp_port'];
    $mail->SMTPSecure = self::$_smtp['smtp_secure'];
    $mail->SMTPAuth = self::$_smtp['smtp_auth'];
    $mail->Username = self::$_smtp['smtp_username'];
    $mail->Password = base64_decode(self::$_smtp['smtp_password']);
    $mail->setFrom(self::$_smtp['smtp_username'], self::$_smtp['smtp_name']);
    $mail->addAddress($receiver);
    $mail->Subject = $subject;
    $mail->isHTML(true);
    $mail->Body = str_replace('YOUR_NAME_FOR_MAIL', self::$_smtp['smtp_name'], $body);
    
    if (!$mail->send()) {
      return new ErrorMessage(1, "Mailer Error: " . $mail->ErrorInfo);
    } else {
      return new ErrorMessage();
    }
    
  }
  
}

