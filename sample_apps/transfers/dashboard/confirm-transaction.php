<?php
	include('../include/config.php');//connection

    if (isset($_GET['txref'])) {
    	//check if the user cancelled the transaction
    	if (isset($_GET['cancelled']) == true) {

    		echo "Transaction cancelled! <br>";
    		echo "<a href='index.php'>GoTo Home</a>";

    	} else {
    		//transacton not cancelled
	        $ref = $_GET['txref'];
	        //$amount = $_SESSION['amount']; //Correct Amount from Server
	        //$currency = $_SESSION['currency']; //Correct Currency from Server

	        $query = array(
	            "SECKEY" => "FLWSECK-ea81e705d82161de5b7757c897d96ba4-X",//remember to pull key from table and not paste it here
	            "txref" => $ref,
	            "include_payment_entity" => "1"
	        );

	        $data_string = json_encode($query);
	                
	        $ch = curl_init('https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/xrequery');                                                                      
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

	        // echo $_SESSION['userid'];
	        // echo "<pre>";
	        // print_r($resp);

	      	$paymentStatus = $resp['data']['status'];
	        $chargeResponsecode = $resp['data']['chargecode'];
	        $chargeAmount = $resp['data']['amount'];
	        $chargeCurrency = $resp['data']['currency'];

	        if (($chargeResponsecode == "00" || $chargeResponsecode == "0")) {// && ($chargeAmount == $amount)  && ($chargeCurrency == $currency)) {
	          	// transaction was successful...
	  			// please check other things like whether you already gave value for this ref
	          	// if the email matches the customer who owns the product etc
	          	//Give Value and return to Success page
	        	$userid = $_SESSION['userid'];
	        	$status = $transaction->updateWalletAmount($userid, $chargeAmount);

	        	if ($status == 1) {
	        		echo "Yeeaah! Transaction was successful <br>";
	            	echo "<a href='index.php'>GoTo Home</a>";
	        	} else {
	        		echo "There was a problem with your transaction! Try again or contact us! <br>";
	            	echo "<a href='index.php'>GoTo Home</a>";
	        	}
	        } else {
	            //Dont Give Value and return to Failure page
	            echo "There was a problem with your transaction! Try again or contact us!";
	            echo "<a href='index.php'>GoTo Home</a>";
	        }
	    }
    } else {
      die('No reference supplied');
    }

?>