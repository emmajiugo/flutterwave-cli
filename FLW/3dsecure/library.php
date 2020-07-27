<?php

// Encryption starts
function getKey($seckey){
    $hashedkey = md5($seckey);
    $hashedkeylast12 = substr($hashedkey, -12);
  
    $seckeyadjusted = str_replace("FLWSECK-", "", $seckey);
    $seckeyadjustedfirst12 = substr($seckeyadjusted, 0, 12);
  
    $encryptionkey = $seckeyadjustedfirst12.$hashedkeylast12;
    return $encryptionkey;
  
}
  
function encrypt3Des($data, $key)
{
    $encData = openssl_encrypt($data, 'DES-EDE3', $key, OPENSSL_RAW_DATA);
    return base64_encode($encData);
}
// Encryption Ends

//post CURL
function postCURL($url, $data, $seckey) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); //Post Fields
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 200);
    curl_setopt($ch, CURLOPT_TIMEOUT, 200);
    $token = 'Bearer '.$seckey;
    $headers = array('Content-Type: application/json','Authorization:'.$token);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $request = curl_exec($ch);
    $result = json_decode($request, true);

    return $result;
}

//get CURL
function getCURL($url, $seckey) {
    $curl = curl_init();
    $token = 'Bearer '.$seckey;
    $header = array('Content-Type: application/json','Authorization:'.$token);
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 180,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_HTTPHEADER => $header,
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $decodedResponse = json_decode($response, true);
        $result = $decodedResponse;
    }
    //print_r($result);
    return $result;
}

