<!-- pay-success.php -->

<?php
// include('../admin/config.php');
if (isset($_GET['txref'])){
	$ref = $_GET['txref'];


	$amount = "30"; //Correct Amount from Server
	$currency = "NGN"; //Correct Currency from Server

	$query = array(
		"SECKEY" => "FLWSECK-xxxx-X",
		"txref" => $ref
	);

	$data_string = json_encode($query);
	$ch = curl_init('https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/v2/verify');
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

	$response = curl_exec($ch);

	$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	$header = substr($response, 0, $header_size);
	$body = substr($response, $header_size);

	curl_close($ch);

	$resp = json_decode($response, true);

	echo "<pre>";
	print_r($resp);
	echo "</pre>";

	// $paymentStatus = $resp['data']['status'];
	// $chargeResponsecode = $resp['data']['chargecode'];
	// $chargeAmount = $resp['data']['amount'];
	// $chargeCurrency = $resp['data']['currency'];

	// if (($chargeResponsecode == "00" || $chargeResponsecode == "0") && ($chargeAmount == $amount) && ($chargeCurrency == $currency)) {
	// 	echo "<script>
	// 	alert('There are no fields to generate a report');
	// 	window.location.href='pay-failure.php';
	// 	</script>";
	// }else {
	// //Dont Give Value and return to Failure page
	// }
}

?>