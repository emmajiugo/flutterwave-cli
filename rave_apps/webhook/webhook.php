<?php

// Retrieve the request's body
$body = @file_get_contents("php://input");

// retrieve the signature sent in the reques header's.
$signature = (isset($_SERVER['HTTP_VERIF_HASH']) ? $_SERVER['HTTP_VERIF_HASH'] : '');

/* It is a good idea to log all events received. Add code *
 * here to log the signature and body to db or file       */

if (!$signature) {
    // only a post with rave signature header gets our attention
    exit();
}

// Store the same signature on your server as an env variable and check against what was sent in the headers
// $local_signature = getenv('SECRET_HASH');
$options = parse_ini_file('.env');
$local_signature = $options['SECRET_HASH'];


// confirm the event's signature
if( $signature !== $local_signature ){
  // silently forget this ever happened
  exit();
}

http_response_code(200); // PHP 5.4 or greater
// parse event (which is json string) as object
// Give value to your customer but don't give any output
// Remember that this is a call from rave's servers and 
// Your customer is not seeing the response here at all
$response = json_decode($body);
if ($response->status == 'successful') {
    // You can log the rsponse to the database or do anything of your choice
    // but for the sake of this app, we will log to a file using the below block of code
    $res = json_encode($response, JSON_PRETTY_PRINT);
    $content = ['body' => $res, 'local_signature' => $local_signature];
    $pretty = json_decode(json_encode($content), JSON_PRETTY_PRINT);
    file_put_contents(time(), $pretty);
}
exit();

