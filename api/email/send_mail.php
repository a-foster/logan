<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../objects/email.php';

$recipients = isset($_GET['recipients']) ? $_GET['recipients'] : '';
$subject = $_GET['subject'];
$body = $_GET['body'];

$email = new Email($recipients, $subject, $body);

$response = $email->sendMail();

if ($response == 'success') {
    echo '{';
        echo '"Status": "Email sent successfully."';
    echo '}';
    echo '{';
        echo '"Recipients": "' . $email->getRecipients() . '"';
    echo '}';
    echo '{';
        echo '"Body": "' . $body . '"';
    echo '}';
}
else{
    echo '{';
        echo '"Status": "' . $response . '"';
    echo '}';
}
?>
