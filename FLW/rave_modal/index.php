<?php

$secret_key = "FLWSECK-xxxx-X";
$public_key = "FLWPUBK-xxxx-X";

if (isset($_POST['standard'])) {
	echo "Getting the standard";

	$curl = curl_init();

	$customer_email = "user@example.com";
	$amount = 10;  
	$currency = "NGN";
	$txref = "rave-29933838"; // ensure you generate unique references per transaction.
	$PBFPubKey = "FLWPUBK-xxxxx-X"; // get your public key from the dashboard.
	$redirect_url = "http://localhost/FLW/rave_modal/pay-verify.php";

	$array = array(
		array(
			'metaname' => 'Something',
			'metavalue' => '4567890'
		),
		array(
			'metaname' => 'Something Else',
			'metavalue' => '4567890'
		)
	);
	$token = 'Bearer '.$secret_key;

	curl_setopt_array($curl, array(
	CURLOPT_URL => "https://api.flutterwave.com/v3/payments",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_CUSTOMREQUEST => "POST",
	CURLOPT_POSTFIELDS => json_encode([
		'amount'=>$amount,
		'customer'=>[ 'email' => 'olaobaju@gmail.com'],
		'currency'=>$currency,
		'payment_options'=> 'card,mobilemoney,ussd',
		'tx_ref'=>$txref,
		'redirect_url'=>$redirect_url,
		'meta'=> $array,
		'customizations' => [
			"title" => "Pied Piper Payments",
			"description" => "Middleout isn't free. Pay the price",
			"logo" => "https://assets.piedpiper.com/logo.png"
		]
	]),
	CURLOPT_HTTPHEADER => [
		"content-type: application/json",
		"cache-control: no-cache",
		"Authorization: ".$token
	],
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	if($err){
	// there was an error contacting the rave API
	die('Curl returned error: ' . $err);
	}

	$transaction = json_decode($response);

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

?>



<!-- Rave Inline Modal -->
<form role="form">
	<div>
		<br><br><br>
		<script type="text/javascript" src="https://checkout.flutterwave.com/v3.js"></script>
		<!-- <script type="text/javascript" src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script> -->
		<button type="button" onClick="makePayment()"> Pay Inline Modal </button>
	</div>
</form>

<!-- Rave Standard -->
<form action="index.php" method="POST">
	<button type="submit" name="standard"> Pay Standard Modal </button>
</form>

<script>
  function makePayment() {
    FlutterwaveCheckout({
      public_key: "<?php echo $public_key; ?>",
      tx_ref: "hooli-tx-1920bbtyt",
      amount: 540,
      currency: "NGN",
      payment_options: "card, mobilemoneyghana, ussd",
      redirect_url: // specified redirect URL
        "https://github.com/bajoski34",
      meta: {
        consumer_id: 23,
        consumer_mac: "92a3-912ba-1192a",
      },
      customer: {
        email: "emma@gmail.com",
        phone_number: "08102909304",
        name: "yemi desola",
      },
      callback: function (data) {
		console.log(data);
		if(data['status'] == 'successful'){
			let txref= data['txref'];
			let id = data['transaction_id'];
			window.location.href='pay-verify.php?txref='+txref+'&id='+id;
		}else{
			alert('Failed');
		}
      },
      onclose: function() {
        // close modal
      },
      customizations: {
        title: "My store",
        description: "Payment for items in cart",
        logo: "https://assets.piedpiper.com/logo.png",
      },
    });
  }
</script>





