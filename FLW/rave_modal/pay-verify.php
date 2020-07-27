<!-- pay-success.php -->

<?php
// include('../admin/config.php');
if (isset($_GET['txref']) && isset($_GET['íd'])){
	$ref = $_GET['txref'];


	$amount = "30"; //Correct Amount from Server
	$currency = "NGN"; //Correct Currency from Server

	$query = array(
		'id' => $_GET['id']
	);
	$url = 'https://api.flutterwave.com/v3/transactions/'.$query['id'].'/verify';
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
    echo "<pre>";
	print_r($result);
	echo "</pre>";


	// $ch = curl_init('https://api.flutterwave.com/v3/transactions/'..'/verify');
	// curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	// curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

	// $response = curl_exec($ch);

	// $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	// $header = substr($response, 0, $header_size);
	// $body = substr($response, $header_size);

	// curl_close($ch);

	// $resp = json_decode($response, true);

	// echo "<pre>";
	// print_r($resp);
	// echo "</pre>";

	// // $paymentStatus = $resp['data']['status'];
	// // $chargeResponsecode = $resp['data']['chargecode'];
	// // $chargeAmount = $resp['data']['amount'];
	// // $chargeCurrency = $resp['data']['currency'];

	// // if (($chargeResponsecode == "00" || $chargeResponsecode == "0") && ($chargeAmount == $amount) && ($chargeCurrency == $currency)) {
	// // 	echo "<script>
	// // 	alert('There are no fields to generate a report');
	// // 	window.location.href='pay-failure.php';
	// // 	</script>";
	// // }else {
	// // //Dont Give Value and return to Failure page
	// // }

	
}

?>