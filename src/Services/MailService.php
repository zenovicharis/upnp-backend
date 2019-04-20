<?php
/**
 * Created by PhpStorm.
 * User: haris
 * Date: 30.4.18
 * Time: 01:13
 */

namespace Upnp\Services;
use PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    /** @var PHPmailer $mail**/
    private $mail;
    private $password;
    private $reciever;
    public function __construct($credentials){
        $this->mail = $credentials['address'];
        $this->password = $credentials['password'];
        $this->reciever = $credentials['reciever'];
    }

    public function sendMail($clientMail, $clientName, $subject, $content){
        //var_dump($this->reciever, $this->mail, $this->password);die();
        $this->mail = new PHPMailer();
        $this->mail->SMTPDebug = 5;
        $this->mail->isSMTP();
        $this->mail->SMTPAuth = true;
        $this->mail->SMTPSecure = 'tls';
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->Username = $this->mail;
        $this->mail->Password = $this->password;
        $this->mail->Port = 587;
        $this->mail->setFrom($clientMail, $clientName);
        $this->mail->addReplyTo($clientMail, $clientName);
        $this->mail->CharSet = 'UTF-8';
        $this->mail->isHTML();                                  // Set email format to HTML
        $mailContent = '<p>'.htmlentities($content).'</p><br/><p> This mail has been sent from upnp.rs contact form</p>';
        $this->mail->Subject = $subject;
        $this->mail->Body    = $mailContent;
        $this->mail->AltBody = htmlentities($content);
        $this->mail->addAddress($this->reciever, "Haris Zenovic");     // Add a recipient
        $isSent = $this->mail->Send();
        if(!$isSent) {
            return false;
        }
        return true;
    }
}