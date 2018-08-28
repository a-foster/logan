<?php
require __DIR__ . '../../../vendor/autoload.php';
include_once '../config/core_config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email{

    // should be able to construct email object without arguments
    public function __construct(){

        // setup default mail object
        $this->conf = new CoreConfig();
        $this->setupMail();
    }

    function setupMail() {
        $this->mail = new PHPMailer(true);                          // Passing `true` enables exceptions

        //Server settings
        $this->mail->isSMTP();
        $this->mail->Host = $this->conf->logan_email_server;
        $this->mail->SMTPAuth = true;                               // Enable SMTP authentication
        $this->mail->Username = $this->conf->logan_email;
        $this->mail->Password = $this->conf->logan_email_password;
        $this->mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $this->mail->Port = 25;                                     // TCP port to connect to

        //Content
        $this->mail->isHTML(true);                                  // Set email format to HTML

        // Recipients
        $this->mail->setFrom($this->conf->logan_email, 'LOGAN');
    }

    // Add recipients to mail object
    function addRecipients( $recipients ) {
        $recipients_list = explode(',', $recipients);
        foreach ($recipients_list as $recipient) {
            $this->mail->addCC($recipient);
        }
    }

    // Return recipients from email object
    // TODO - this isn't right because recipients will be on $this->mail
    function getRecipients () {
        return $this->recipients;
    }

    // supply path to file to attach
    function addAttachment( $attachment ) {
        $this->mail->addAttachment( $attachment );
    }

    function sendMail($subject, $body) {

        // use default recipients from config if none defined
        if (!isset($this->recipients)) { $this->addRecipients($this->conf->default_email_recipients); }

        // email content
        $this->mail->Subject = $subject;
        $this->mail->Body    = $body;

        try {
            $this->mail->send();

            // TODO - Add some logging for this bit
            return "success";
        } catch (Exception $e) {
            return $this->mail->ErrorInfo;
        }
    }
}
