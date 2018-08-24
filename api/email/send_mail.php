<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// instantiate product object
include_once '../objects/product.php';

$recipients = $_GET['recipients'];
$subject = $_GET['subject'];
$body = $_GET['body'];

$product = new Product($db);

$email = new Email($recipients, $subject, $body);

if($email->sendMail()){
    echo '{';
        echo '"message": "Email sent successfully."';
    echo '}';
}

else{
    echo '{';
        echo '"message": "Unable."';
    echo '}';
}
?>
