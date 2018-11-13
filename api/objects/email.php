<?php
require __DIR__ . '../../../vendor/autoload.php';
include_once 'logan.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email{

    // should be able to construct email object without arguments
    public function __construct(){

        // setup default mail object
        $this->logan = new Logan();
        $this->setupMail();
    }

    function setupMail() {
        $this->mail = new PHPMailer(true);                          // Passing `true` enables exceptions

        //Server settings
        $this->mail->isSMTP();
        $this->mail->Host = $this->logan->conf->logan_email_server;
        $this->mail->SMTPAuth = true;                               // Enable SMTP authentication
        $this->mail->Username = $this->logan->conf->logan_email;
        $this->mail->Password = $this->logan->conf->logan_email_password;
        $this->mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $this->mail->Port = 25;                                     // TCP port to connect to

        //Content
        $this->mail->isHTML(true);                                  // Set email format to HTML

        // Recipients
        $this->mail->setFrom($this->logan->conf->logan_email_sender, 'LOGAN');
        $this->recipients = '';
    }

    // Add recipients to mail object
    function addRecipients( $recipients ) {
        $recipients_list = explode(',', $recipients);
        foreach ($recipients_list as $recipient) {
            $this->mail->addAddress($recipient);
        }
        $this->recipients .= $recipients . ',';
    }

    // Return recipients from email object
    function getRecipients () {
        return $this->recipients;
    }

    // supply path to file to attach
    function addAttachment( $attachment ) {
        $this->mail->addAttachment( $attachment );
    }

    function sendMail($subject, $body) {

        // use default recipients from config if none defined
        if (!isset($this->recipients)) { $this->addRecipients($this->logan->conf->default_email_recipients); }

        // email content
        $this->mail->Subject = $subject;
        $this->mail->Body    = $body;

        try {
            $this->mail->send();
            $this->logan->log->writeLog("Email", "Sent mail to: $this->recipients - Subject: $subject");
            return "success";
        } catch (Exception $e) {
            $this->logan->log->writeLog("Email Error", "Failed sending mail to: $this->recipients - Subject: $subject");
            return $this->mail->ErrorInfo;
        }
    }
}
