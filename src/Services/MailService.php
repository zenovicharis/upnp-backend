<?php
/**
 * Created by PhpStorm.
 * User: haris
 * Date: 30.4.18
 * Time: 01:13
 */

namespace Upnp\Services;


class MailService
{
    /** @var PHPmailer $mail**/
    private $mail;
    public function __construct(){
    }

    public function sendMail($clientMail, $clientName, $subject, $content){
        $this->mail = new PHPMailer();
        $this->mail->SMTPDebug = 5;
        $this->mail->isSMTP();
        $this->mail->SMTPAuth = true;
        $this->mail->SMTPSecure = 'tls';
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->Username = 'zenovicharis@gmail.com';
        $this->mail->Password = 'Bostonseltiks';
        $this->mail->Port = 587;
        $this->mail->setFrom($clientMail, $clientName);
        $this->mail->addReplyTo($clientMail, $clientName);
        $this->mail->CharSet = 'UTF-8';
        $this->mail->isHTML();                                  // Set email format to HTML
        $mailContent = '<p style="text-align:center">'.htmlentities($content).'</p><br/><p> This mail has been sent from hcg.rs contact form</p>';
        $this->mail->Subject = $subject;
        $this->mail->Body    = $mailContent;
        $this->mail->AltBody = htmlentities($content);
        var_dump($clientMail);die();
        $this->mail->addAddress('zenovicharis@gmail.com', "Haris Zenovic");     // Add a recipient
        $isSent = $this->mail->Send();
        return $isSent;
        if(!$isSent) {
            return false;
        }
        return true;
    }
}