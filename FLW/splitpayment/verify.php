<?php

if(!empty($_GET['txid'])) {
    $txid = $_GET['txid'];
    //call the verify endpoint
    $data = [
        'txref' => $txid,
        'SECKEY' => 'FLWSECK-xxxxxxxxxxxxxxxxxxxxxxxxxxxx-X'
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

    echo "Your payment of {}successful";
    }
}
?>