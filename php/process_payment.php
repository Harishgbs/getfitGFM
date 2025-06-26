<?php
require 'vendor/autoload.php'; // Make sure to include the Stripe PHP library

\Stripe\Stripe::setApiKey('YOUR_SECRET_KEY'); // Replace with your Stripe secret key

// Get the JSON data from the request
$data = json_decode(file_get_contents("php://input"), true);

$paymentMethodId = $data['paymentMethodId'];
$sessionType = $data['sessionType'];

// Set the amount based on the session type
$amount = ($sessionType === 'weekly') ? 5000 : 18000; // Amount in cents

try {
    // Create a PaymentIntent
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $amount,
        'currency' => 'usd',
        'payment_method' => $paymentMethodId,
        'confirmation_method' => 'manual',
        'confirm' => true,
    ]);

    // If the payment is successful
    if ($paymentIntent->status === 'succeeded') {
        // Here you can save the booking details to your database
        //