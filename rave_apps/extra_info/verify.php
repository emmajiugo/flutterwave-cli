<?php
require "vendor/autoload.php";

// load the .env
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$live_pkey = getenv('LIVE_PUBLIC_KEY');
$live_skey = getenv('LIVE_SECRET_KEY');
$test_pkey = getenv('TEST_PUBLIC_KEY');
$test_skey = getenv('TEST_SECRET_KEY');
$live_endpoint = getenv('LIVE_ENDPOINT');
$test_endpoint = getenv('TEST_ENDPOINT');
$production = getenv('PRODUCTION');

if ($production == 'true') {
    $skey = $live_skey;
    $pkey = $live_pkey;
    $url = $live_endpoint;
} else {
    $skey = $test_skey;
    $pkey = $test_pkey;
    $url = $test_endpoint;
}


if (isset($_GET['txref'])) {
    $ref = $_GET['txref'];
    $amount = $_GET['amount'];; //Correct Amount from Server
    $currency = "NGN"; //Correct Currency from Server

    $query = array(
        "SECKEY" => $skey,
        "txref" => $ref 
    );

    $data_string = json_encode($query);
            
    $ch = curl_init($url.'/flwv3-pug/getpaidx/api/v2/verify');                                                                      
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

    if ($resp['status'] === 'success') {

        $paymentStatus = $resp['data']['status'];
        $chargeResponsecode = $resp['data']['chargecode'];
        $chargeAmount = $resp['data']['amount'];
        $chargeCurrency = $resp['data']['currency'];
        $metaData = $resp['data']['meta'];

        if (($chargeResponsecode == "00" || $chargeResponsecode == "0") && ($chargeAmount == $amount)) {
            // transaction was successful...
            $amount = $resp['data']['amount'];
            $payer_name = $resp['data']['custname'];
            $payer_phone = $resp['data']['custphone'];
            $payer_email = $resp['data']['custemail'];

            foreach ($metaData as $value) {
                if ($value['metaname'] == 'nameOfStudent') {
                    $student_name = $value['metavalue'];
                }

                if ($value['metaname'] == 'studentClass') {
                    $student_class = $value['metavalue'];
                }

                if ($value['metaname'] == 'studentId') {
                    $student_id = $value['metavalue'];
                }

                if ($value['metaname'] == 'term') {
                    $term = $value['metavalue'];
                }

                if ($value['metaname'] == 'paymentPurpose') {
                    $paymentPurpose = $value['metavalue'];
                }
            }

        } else {
            //Dont Give Value and return to Failure page
            echo "<script>window.location.href = 'index.php';</script>";
        }
    } else {
        //redirect to payment page
        echo "<script> window.location.href = 'index.php'; </script>";
    }
} else {
    //redirect to payment page
    echo "<script> window.location.href = 'index.php'; </script>";
}

?>

<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<link href='https://fonts.googleapis.com/css?family=Lato:300,400|Montserrat:700' rel='stylesheet' type='text/css'>
	<style>
        @import url(//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css);
		@import url(//cdnjs.cloudflare.com/ajax/libs/normalize/3.0.1/normalize.min.css);
		@import url(//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css);
	</style>
	<link rel="stylesheet" href="https://2-22-4-dot-lead-pages.appspot.com/static/lp918/min/default_thank_you.css">
	<script src="https://2-22-4-dot-lead-pages.appspot.com/static/lp918/min/jquery-1.9.1.min.js"></script>
	<script src="https://2-22-4-dot-lead-pages.appspot.com/static/lp918/min/html5shiv.js"></script>

    <style>
        body {
            font-family: 'Helvetica Neue', sans-serif;
        }

        h1 { 
            color: #111;            
            font-size: 35px; 
            font-weight: bold; 
            letter-spacing: -1px; 
            line-height: 1; 
            text-align: center; 
        }

        .check-color {
            color: green;
        }
    </style>
</head>
<body>
	<header id="header">
        <h1 class="thankyou-header">
            <!-- <i class="fa fa-check check-color"></i> -->
		    Corona Secondary School, Agbara.
        </h1>
	</header>

	<div class="main-content">
        <div class="row">
            <div class="col-md-12">
                <p class="main-content__body" data-lead-id="main-content-body">Please find below your payment details.</p>
                <br><br>
            </div>
        </div>

        <div class="row">
            <div class="col-md-offset-3 col-md-6">
                <table class="table table-bordered table-striped">
                    <tr>
                        <td><b>Student ID:</b></td>
                        <td><?php echo $student_id; ?></td>
                    </tr>
                    <tr>
                        <td><b>Student Name:</b></td>
                        <td><?php echo $student_name; ?></td>
                    </tr>
                    <tr>
                        <td><b>Student Class:</b></td>
                        <td><?php echo $student_class; ?></td>
                    </tr>
                    <tr>
                        <td><b>Term:</b></td>
                        <td><?php echo $term; ?></td>
                    </tr>
                    <tr>
                        <td><b>Payer's Name:</b></td>
                        <td><?php echo $payer_name; ?></td>
                    </tr>
                    <tr>
                        <td><b>Payer's Phone:</b></td>
                        <td><?php echo $payer_phone; ?></td>
                    </tr>
                    <tr>
                        <td><b>Payer Email:</b></td>
                        <td><?php echo $payer_email; ?></td>
                    </tr>
                    <tr>
                        <td><b>Payment Purpose:</b></td>
                        <td><?php echo $paymentPurpose; ?></td>
                    </tr>
                    <tr>
                        <td><b>Amount Paid:</b></td>
                        <td><?php echo $amount; ?></td>
                    </tr>
                </table>
                <br><br>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 text-center">
                <a href="index.php" class="btn btn-success"><span style="color:#ffffff">|<< Back</span></a>
            </div>
        </div>
	</div>

	<footer class="site-footerx" id="footerx">
        <br><br>
		<p class="site-footer__fineprint" id="fineprint">Copyright ©2018 | All Rights Reserved</p>
	</footer>
</body>
</html>