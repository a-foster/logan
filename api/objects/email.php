<?php
require __DIR__ . '../../../vendor/autoload.php';
include_once '../config/core_config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email{

    // Common attributes for any email type
    protected $sender;
    protected $recipients;
    protected $subject;
    protected $body;

    // constructor with $db as database connection
    public function __construct($recipients, $subject, $body){
        // get core config to use as defaults
        $conf = new CoreConfig();
        $this->core_conf = $conf->getConfig();

        // anything the user would want to change is stored on the email object
        $this->recipients = $recipients ? $recipients : $this->core_conf->email_recipients;
        $this->subject = $subject;
        $this->body = $body;

        // setup default mail object
        $this->setupMail();
    }

    function setupMail() {
        $this->mail = new PHPMailer(true);                          // Passing `true` enables exceptions

        //Server settings
        $this->mail->SMTPDebug = 2;                                 // Enable verbose debug output
        $this->mail->isSMTP();
        $this->mail->Host = $this->core_conf->logan_mail_server;
        $this->mail->SMTPAuth = true;                               // Enable SMTP authentication
        $this->mail->Username = $this->core_conf->logan_email;
        $this->mail->Password = $this->core_conf->logan_email_password;
        $this->mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $this->mail->Port = 25;                                     // TCP port to connect to

        //Content
        $this->mail->isHTML(true);                                  // Set email format to HTML
        $this->mail->Subject = $this->subject;
        $this->mail->Body    = $this->body;

        // Recipients
        $this->mail->setFrom($this->core_conf->logan_email, 'LOGAN');
        $this->addRecipients($this->core_conf->email_recipients);
    }

    function addRecipients( $recipients ) {
        $recipients_list = explode(',', $recipients);
        foreach ($recipients_list as $recipient) {
            $this->mail->addCC($recipient);
        }
    }

    function addAttachment( $attachment ) {
      $this->mail->addAttachment( $attachment );
    }

    function sendMail() {
      try {
        $this->mail->send();

        // TODO - Add some logging for this bit
        echo 'Message has been sent';
      } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $this->mail->ErrorInfo;
      }
    }
}
