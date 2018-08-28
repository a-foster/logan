<?php
require __DIR__ . '../../../vendor/autoload.php';
include_once 'logan.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email{

    // Common attributes for any email type
    protected $sender;
    protected $recipients;
    protected $subject;
    protected $body;

    // constructor with $db as database connection
    public function __construct($logan, $recipients, $subject, $body){
        // set logan object for db, config, logging
        $this->logan = $logan;

        // anything the user would want to change is stored on the email object
        $this->recipients = $recipients ? $recipients : $this->logan->conf->default_email_recipients;
        $this->subject = $subject;
        $this->body = $body;

        // setup default mail object
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
        $this->mail->Subject = $this->subject;
        $this->mail->Body    = $this->body;

        // Recipients
        $this->mail->setFrom($this->logan->conf->logan_email, 'LOGAN');
        $this->addRecipients($this->recipients);
    }

    // Add recipients to mail object
    function addRecipients( $recipients ) {
        $recipients_list = explode(',', $recipients);
        foreach ($recipients_list as $recipient) {
            $this->mail->addCC($recipient);
        }
    }

    // Return recipients from email object
    function getRecipients () {
        return $this->recipients;
    }

    function addAttachment( $attachment ) {
        $this->mail->addAttachment( $attachment );
    }

    function sendMail() {
        try {
            $this->mail->send();

            // TODO - Add some logging for this bit
            return "success";
        } catch (Exception $e) {
            return $this->mail->ErrorInfo;
        }
    }
}
