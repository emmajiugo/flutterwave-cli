<?php

require_once('config/db.php');
require_once('lib/pdo_db.php');
require_once('model/customer.php');
require_once('model/transaction.php');

if(!empty($_GET['txid'])) {

    $txid = $_GET['txid'];

    //call the verify endpoint

    $data = [
        'txref' => $txid,
        'SECKEY' => 'FLWSECK-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-X'
    ];

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/v2/verify",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            "content-type: application/json",
            "cache-control: no-cache"
        ],
    ));
    
    $response = curl_exec($curl);
    $err = curl_error($curl);

    if($err){
        // there was an error contacting the rave API
        die('Curl returned error: ' . $err);
    }
    $transaction = json_decode($response);
     print_r($transaction);

    if('success' == $transaction->status && '00' == $transaction->data->chargecode) {
    // echo "successful";
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <title>Thank You</title>
    </head>
    <body>
        <div class="container col-md-4">
            <h2>Thank you for ordering cloth from us</h2>
            <hr>
            <p> Your Transaction Reference is <?php echo $txid; ?></p>
            <p>Check your email for the reciept</p>
            <button class="btn btn-success"><a herf="index.php" class="col-md-2">Go Back </a></button>
        </div>
    </body>
</html>

<?php


    } else {
        header('Location: failed.php');
    }
}

$id = $transaction->data->customerid;
$email = $transaction->data->custemail;
$name = $transaction->data->custname;
$amount = $transaction->data->amountsettledforthistransaction;
$phone_number = $transaction->data->custphone;
$address = $transaction->data->meta;

$address = json_decode(json_encode($address), true);

// Customer Data
$customerData = array(
    'id' => $id,
    'name' => $name,
    'phone_number' => $phone_number,
    'email' => $email,
    'address' =>$address[0]['metavalue']
);

// Instantiate Customer
$customer = new Customer();

// Add Customer To DB
$customer->addCustomer($customerData);



$Txid = $transaction->data->txid;
$amount = $transaction->data->amount;
$currency = $transaction->data->currency;
$status = $transaction->status;

// Transactions Data

$transactionData = array(

    'id' => $Txid,
    'customer_id' => $id,
    'amount' => $amount,
    'currency' => $currency,
    'status' => $status

);

// Instantiate Transaction
$transaction = new Transaction();
// Add Transaction To DB
$transaction->addTransaction($transactionData);

?>
