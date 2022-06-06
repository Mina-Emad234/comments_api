<?php
use Firebase\JWT\Key;

// show error reporting
error_reporting(E_ALL);

// set your default time-zone
date_default_timezone_set('Africa/Cairo');

// variables used for jwt

// Your passphrase
$passphrase = '[KEY123KEY]';

// Your private key file with passphrase
// Can be generated with "ssh-keygen -t rsa -m pem"
$privateKeyFile = 'E:\xampp\htdocs\API_Projects\API_Comments\core\rsa_id.pub';

// Create a private key of type "resource"
$privateKey = openssl_pkey_get_private(
    file_get_contents($privateKeyFile),
    $passphrase
);

$key = "SHA256:iZWZPevNh8zLJV3wsJBMjOTcu+MoynB0E8pw3EHGeSs mina@DESKTOP-BVL9KEU";
$issued_at = time();
$expiration_time = $issued_at + (60 * 60); // valid for 1 hour
$issuer = "http://localhost/CodeOfaNinja/RestApiAuthLevel1/";

?>