<?php

if(!empty($_GET['txid'])) {
    $txid = $_GET['txid'];
    //call the verify endpoint
    $data = [
        'id' => $_GET['id']
    ];
    $token = 'Bearer '.$seckey;
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.flutterwave.com/v3/transactions/'.$data['id'].'/verify',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "content-type: application/json",
            "cache-control: no-cache",
            'authorization:'.$token
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