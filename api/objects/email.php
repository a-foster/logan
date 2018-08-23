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
    }

    function sendMail() {
      $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
      try {
          //Server settings
          $mail->SMTPDebug = 2;                                 // Enable verbose debug output
          $mail->isSMTP();
          $mail->Host = $this->core_conf->logan_mail_server;
          $mail->SMTPAuth = true;                               // Enable SMTP authentication
          $mail->Username = $this->core_conf->logan_email;
          $mail->Password = $this->core_conf->logan_email_password;
          $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
          $mail->Port = 25;                                     // TCP port to connect to

          //Recipients
          $mail->setFrom($this->core_conf->logan_email, 'LOGAN');
          $this->addRecipients( $mail, $this->recipients);

          //Attachments
          //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
          //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

          //Content
          $mail->isHTML(true);                                  // Set email format to HTML
          $mail->Subject = $this->subject;
          $mail->Body    = $this->body;

          $mail->send();

          // TODO - Add some logging for this bit
          echo 'Message has been sent';
      } catch (Exception $e) {
          echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
      }

    }

    function addRecipients( $mail, $recipients ) {
        $recipients_list = explode(',', $recipients);
        foreach ($recipients_list as $recipient) {
            $mail->addCC($recipient);
        }
    }
}
