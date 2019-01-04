<?php
namespace App\Traits;

/**
 * Rave Base functions are included here
 */
trait RaveBase
{
    public $skey = "FLWSECK-ea81e705d82161de5b7757c897d96ba4-X"; 
    public $pkey = "FLWPUBK-56e4a2c6c9a6b58364bfd07fc1993e2c-X";
    public $url = "https://ravesandboxapi.flutterwave.com";

    /**
     * Encryption starts here
     */
    public function getKey($seckey){
        $hashedkey = md5($seckey);
        $hashedkeylast12 = substr($hashedkey, -12);
    
        $seckeyadjusted = str_replace("FLWSECK-", "", $seckey);
        $seckeyadjustedfirst12 = substr($seckeyadjusted, 0, 12);
    
        $encryptionkey = $seckeyadjustedfirst12.$hashedkeylast12;
        return $encryptionkey;
    
    }
    
    public function encrypt3Des($data, $key)
    {
        $encData = openssl_encrypt($data, 'DES-EDE3', $key, OPENSSL_RAW_DATA);
        return base64_encode($encData);
    }
    // Encryption Ends

    /**
     * post CURL
     */
    public function postCURL($url, $data) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); //Post Fields
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 200);
        curl_setopt($ch, CURLOPT_TIMEOUT, 200);

        $headers = array('Content-Type: application/json');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $request = curl_exec($ch);
        $result = json_decode($request, true);

        return $result;
    }

    /**
     * get CURL
     */
    public function getCURL($data, $url) {

    }

    /**
     * Rave charge card
     */
    public function chargeCard($payload)
    {
        $key = $this->getKey($this->skey);
        $dataReq = json_encode($payload);
        $post_enc = $this->encrypt3Des($dataReq, $key);
        $postdata = array(
            'PBFPubKey' => $this->pkey,
            'client' => $post_enc,
            'alg' => '3DES-24'
        );

        //excute the charge
        $endpoint_url = $this->url."/flwv3-pug/getpaidx/api/charge";
        $res = $this->postCURL($endpoint_url, $postdata);
        return $res;
    }

    /**
     * Validate charge after OTP
     */
    public function getOTP($flwref, $OTP)
    {
        $data = array(
            "PBFPubKey" => $this->pkey,
            "transaction_reference" => $flwref,
            "otp" => $OTP
        );

        //validate the charge
        $endpoint_url = $this->url."/flwv3-pug/getpaidx/api/validatecharge";
        $res = $this->postCURL($endpoint_url, $data);
        return $res;
    }
}
