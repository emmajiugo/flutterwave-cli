<?php

if (isset($_POST['standard'])) {
	echo "Getting the standard";

	$curl = curl_init();

	$customer_email = "user@example.com";
	$amount = 10;  
	$currency = "NGN";
	$txref = "rave-29933838"; // ensure you generate unique references per transaction.
	$PBFPubKey = "FLWPUBK-xxxxx-X"; // get your public key from the dashboard.
	$redirect_url = "http://localhost/rave-test/pay-verify.php";

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


	curl_setopt_array($curl, array(
	CURLOPT_URL => "https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/v2/hosted/pay",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_CUSTOMREQUEST => "POST",
	CURLOPT_POSTFIELDS => json_encode([
		'amount'=>$amount,
		'customer_email'=>$customer_email,
		'currency'=>$currency,
		'txref'=>$txref,
		'PBFPubKey'=>$PBFPubKey,
		'redirect_url'=>$redirect_url,
		'meta'=> $array,
	]),
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

<!-- Rave Modal Quick setup with data attributes -->
<form>
    <a class="flwpug_getpaid"
       data-PBFPubKey="FLWPUBK-xxxx-X"
       data-txref="ravetxref-2522766472"
       data-amount="1"
       data-customer_email="test@test.com"
       data-currency="EUR"
       data-country="NG"
       data-custom_title="Clinibella Limited"
       data-custom_description=""
       data-redirect_url="https://www.apsp.biz/pay/Rave/RedirectUser.aspx"
       data-payment_method="both">
    </a>
    <script src="https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
</form>

<!-- Rave Inline Modal -->
<form role="form">
	<div>
		<br><br><br>
		<script type="text/javascript" src="https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
		<!-- <script type="text/javascript" src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script> -->
		<button type="button" onClick="payWithRave()"> Pay Inline Modal </button>
	</div>
</form>

<!-- Rave Standard -->
<form action="index.php" method="POST">
	<button type="submit" name="standard"> Pay Standard Modal </button>
</form>


<script>
	const API_publicKey = "FLWPUBK-xxxxx-X";
	//var email = document.getElementById('email').value;
	function payWithRave() {

		var x = getpaidSetup({
			PBFPubKey: API_publicKey,
			customer_email: 'emma@gmail.com', //email,
			amount: 30,
			currency: "NGN",
			txref: "<?=uniqid(rand(0,1000)); ?>",
			payment_plan: 1494,
			redirect_url: 'https://github.com/emmajiugo',

			onclose: function() {
			},
			callback: function(response) {
				var txref = response.tx.txRef; // collect flwRef returned and pass to a server page to complete status check.
				console.log("This is the response returned after a charge", response);
				if (
					response.tx.chargeResponseCode == "00" ||
					response.tx.chargeResponseCode == "0"
				) {
					window.location.href='pay-verify.php?txref='+txref;
				} else {
					// redirect to a failure page.
					alert('Failed');
				}

				x.close(); // use this to close the modal immediately after payment.
			}
		});
	}
</script>
