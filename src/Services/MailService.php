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
        $this->username = $credentials['address'];
        $this->password = $credentials['password'];
        $this->reciever = $credentials['reciever'];

    }
    
    public function sendMail($clientMail, $clientName, $subject, $content){
        // var_dump($clientMail, $clientName, $subject, $content);die();
        $this->mail = new PHPMailer();
        // $this->mail->SMTPDebug = 5;
        $this->mail->isSMTP();
        $this->mail->SMTPAuth = true;
        $this->mail->SMTPSecure = 'ssl';
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->Username = $this->username;
        $this->mail->Password = $this->password;
        $this->mail->Port = 465;
        $this->mail->setFrom('no-reply@upnp.rs', $clientName);
        $this->mail->addReplyTo($clientMail, $clientName);
        $this->mail->CharSet = 'UTF-8';
        $this->mail->isHTML();                                  // Set email format to HTML
        $mailContent = $content;
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

    public function generateContent ($name, $email, $phone, $company, $content) {
        return "
            <div>
                <p><strong>Ime</strong>: ".(empty($name) ? 'Nije Popunjeno' : filter_var($name, FILTER_SANITIZE_STRING))."</p>
                <p><strong>E-Mail</strong>: ".(empty($email) ? 'Nije Popunjeno' : filter_var($email, FILTER_SANITIZE_STRING))."</p>
                <p><strong>Broj Telefona</strong>: ".(empty($phone) ? 'Nije Popunjeno' : filter_var($phone, FILTER_SANITIZE_STRING))."</p>
                <p><strong>Ime Kompanije</strong>: ".(empty($company) ? 'Nije Popunjeno' : filter_var($company, FILTER_SANITIZE_STRING))."</p>
                <hr>	
                <p>
                ".filter_var($content, FILTER_SANITIZE_STRING)."
                </p>
                <hr>
                <small>Mail poslat sa forme sajta https://upnp.rs</small>
            </div>";
    }
}