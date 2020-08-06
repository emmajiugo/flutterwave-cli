<?php
//class for login section
require_once('class.general.php');//including the general functions
require_once('Flutterwave/api/Pay.php');
require_once('Flutterwave/api/Transfer.php');


use Flutterwave\Pay;
use Flutterwave\Transfer;


class api extends general
{
	
	function __construct($con)
	{
		parent::__construct($con);
	}

	//fund wallet
	public function fundWallet($newamount)
	{
		$customer_email = "emereuwaonueze@gmail.com";//gets user email from db...but for the sake of this test....we are using my email
		$amount = $newamount;  
		$currency = "NGN";
		$txref = "rave-".time(); // ensure you generate unique references per transaction.
		$secretKey = $_ENV['SECRET_KEY'];
		$env = $_ENV['ENV'];
		$PBFPubKey = $_ENV['PUBLIC_KEY']; // we are suppose to pull from a table in our db name eg: "api-key". dont paste ur keys like this. but for the sake of this test, we will be doing the paste. 
		$redirect_url = "http://localhost/demo/simplepayroll/dashboard/confirm-transaction.php";
		$payment_plan = ""; // this is only required for recurring payments.

		$array_options = array(
			'amount'=>$amount,
			'customer'=> [ 'email' => $customer_email],
			'currency'=>$currency,
			'tx_ref'=>$txref,
			'payment_options' => 'card',
			'redirect_url' => $redirect_url,
			'payment_plan'=>$payment_plan,
			"customizations" => [
				"title" => "Pied Piper Payments",
				"description" => "Middleout isn't free. Pay the price",
				"logo" => "https://miro.medium.com/fit/c/128/128/1*Z1GByNW4KCR8HNCUjbgzdA.png"
			]
		);

		$pay = new Pay();
		$result = $pay->pay($PBFPubKey, $secretKey,$env,$array_options);

		$transaction = json_decode($result);

		if(!$transaction->data && !$transaction->data->link){
			// there was an error from the API
			print_r('API returned error: ' . $transaction->message);
		}
		
		// uncomment out this line if you want to redirect the user to the payment page
		//print_r($transaction->data->message);
		
		
		// redirect to page so User can pay
		// uncomment this line to allow the user redirect to the payment page
		header('Location: ' . $transaction->data->link);
	}

	//get supported banks
	public function getBanks()
	{
		$curl = curl_init();
		$base_url = "https://api.flutterwave.com/v3/banks";
		$token = "Bearer ".$_ENV['SECRET_KEY'];
		$header = array(
			  "Content-Type: application/json",
			  "Authorization: ".$token
		);
		$query = "/NG";//pss NG, GH, KE

		curl_setopt_array($curl, array(
		  	CURLOPT_URL => $base_url . $query,
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
		  	$banks = $decodedResponse['data'];
		}

		//print_r($banks);
		return $banks;
	}

	//get supported banks name
	public function getBankName($bankcode)
	{
		$curl = curl_init();
		$base_url = "https://api.flutterwave.com/v3/banks";
		$token = "Bearer ".$_ENV['SECRET_KEY'];
		$header = array(
			  "Content-Type: application/json",
			  "Authorization: ".$token
		);
		$query = "/NG";//pss NG, GH, KE

		curl_setopt_array($curl, array(
		  	CURLOPT_URL => $base_url . $query,
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
		  	$banks = $decodedResponse['data'];
		}

		$bankname = "BANK NOT FOUND";

		foreach ($banks as $bank) {
			if ($bank['code'] == $bankcode) {
				$bankname = $bank['name'];
			}
		}

		//print_r($banks);
		// return $banks;
		return $bankname;
	}

	public function verifyBVN($bvnno){

	}
	//verify account number
	public function verifyAccount($bankcode, $accountno)
	{
		$curl = curl_init();
		$token = "Bearer ".$_ENV['SECRET_KEY'];

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.flutterwave.com/v3/accounts/resolve",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => json_encode([
		    'account_number' => $accountno,
		    'account_bank' => $bankcode,
		    
		  ]),
		  CURLOPT_HTTPHEADER => [
		    "content-type: application/json",
			"cache-control: no-cache",
			"authorization: ".$token
		  ],
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		if($err){
		  // there was an error contacting the rave API
		  die('Curl returned error: ' . $err);
		}

		$verifyStatus = json_decode($response, true);

		return $verifyStatus;
	}

	//single transfer
	public function singleTransfer($bankcode, $accountno, $amount, $narration)
	{
		$secretKey = $_ENV['SECRET_KEY'];
		$env = $_ENV['ENV'];
		$PBFPubKey = $_ENV['PUBLIC_KEY']; // we are suppose to pull from a table in our db name eg: "api-key". dont paste ur keys like this. but for the sake of this test, we will be doing the paste. 
		

		$array = array(
			'account_bank' => $bankcode,
			'account_number' => $accountno,
			'amount' => $amount,
			'narration' => $narration,
			'currency' => "NGN",
			'reference' => "rave-".time()
		);
	
		$single = new Transfer();
		$result = $single->singleTransfer($array);
		$trx = json_decode($result, true);
		//print_r($result);
		return $trx;
	}

	//bulk transfer
	public function bulkTransfer($members)
	{
		
		foreach ($members as $member) {
			$bulk_data[] = array (
		        'bank_code' => $member['staffbank'],
		        'account_number' => $member['staffacctno'],
		        'amount' => $member['amount'],
		        'narration' => 'Something goes here',
				'currency' => 'NGN',
				'reference' => 'rave-'.time()
		    );
		}


		$data["title"] = "My staff salary";
		$data["bulk_data"] = $bulk_data;
		
		$bulk = new Transfer();
		$result = $bulk->bulkTransfer($data);
		$trx = json_decode($result, true);
		return $trx;
	}
}
?>