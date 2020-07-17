<?php 
require __DIR__.'/vendor/autoload.php'; // Uncomment this autoloader if you need it
$dotenv = new Dotenv\Dotenv(__DIR__.'/include/classes/Flutterwave');
$dotenv->load();

echo "Hello World";
 $options = array(
    'cluster' => 'eu',
    'encrypted' => true
  );
  
  $pusher = new Pusher\Pusher(
    '084259e4e356fb9a0622',
    'bfb407e2dc876b7fe5a3',
    '636291',
    $options
  );
  
  //$data['message'] = "Hello world";
  $pusher->trigger('my-channel', 'my-event', "Hello");
  
  echo '<script src="https://js.pusher.com/4.3/pusher.min.js"></script>';
    
    echo'<script>
        Pusher.logToConsole = true;
        var pusher = new Pusher("084259e4e356fb9a0622", {
        cluster: "eu",
        forceTLS: true
        });
        var channel = pusher.subscribe("my-channel");
        channel.bind("my-event", function(data) {
        console.log(data);
        }).bind("pusher:subscription_succeeded", function(data) {
          alert("hello");
      });
    </script>';
  
  
// // Retrieve the request's body
// $body = @file_get_contents("php://input");

// // retrieve the signature sent in the reques header's.
// $signature = (isset($_SERVER['HTTP_VERIF_HASH']) ? $_SERVER['HTTP_VERIF_HASH'] : '');

// /* It is a good idea to log all events received. Add code *
//  * here to log the signature and body to db or file       */

// if (!$signature) {
//     // only a post with rave signature header gets our attention
//     exit();
// }

// // Store the same signature on your server as an env variable and check against what was sent in the headers
// $local_signature = $_ENV['SECRET_HASH'];

// // confirm the event's signature
// if( $signature !== $local_signature ){
//   // silently forget this ever happened
//   exit();
// }

// http_response_code(200); // PHP 5.4 or greater
// // parse event (which is json string) as object
// // Give value to your customer but don't give any output
// // Remember that this is a call from rave's servers and 
// // Your customer is not seeing the response here at all
// $response = json_decode($body);

// if (isset($response)) {
//   $data['message'] = $response;
//   $pusher->trigger('my-channel', 'my-event', $data);
  
// }else{
//   $data['message'] = "Nothing to see here";
//   $pusher->trigger('my-channel', 'my-event', $data); 
// }
// exit();
?>