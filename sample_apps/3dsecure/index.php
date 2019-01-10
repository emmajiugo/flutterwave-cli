<?php
session_start();
require('library.php');

//set keys
$secret_key = "FLWSECK-ea81e705d82161de5b7757c897d96ba4-X";
$public_key = "FLWPUBK-56e4a2c6c9a6b58364bfd07fc1993e2c-X";
$baseurl = "https://ravesandboxapi.flutterwave.com";
$page_status = '';

// initiate transaction
if (isset($_POST['initiate'])){
    $email = $_POST['email'];
    $amount = $_POST['amount'];
    $card = $_POST['card'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $cvv = $_POST['cvv'];

    //card payment
    $data = array(
        'PBFPubKey' => $public_key,
        'cardno' => $card,
        'currency' => 'NGN',
        'country' => 'NG',
        'cvv' => $cvv,
        // 'pin' => '3310',
        // 'suggested_auth' => 'PIN',
        'amount' => '10',
        'expiryyear' => $year,
        'expirymonth' => $month,
        'redirect_url' => 'https://github.com/emmajiugo',
        'email' => $email,
        'txRef' => time(),
    );
        
    $SecKey = $secret_key;

    $key = getKey($SecKey); 
    $dataReq = json_encode($data);
    $post_enc = encrypt3Des( $dataReq, $key );
    $postdata = array(
        'PBFPubKey' => $public_key,
        'client' => $post_enc,
        'alg' => '3DES-24'
    );

    //setup for charg
    $url = $baseurl."/flwv3-pug/getpaidx/api/charge";
    $res = postCURL($url, $postdata);

    // echo "<pre>";
    // print_r($res);

    if ($res['status'] == 'success' && $res['message'] == 'AUTH_SUGGESTION') {
        $page_status = $res['data']['suggested_auth'];
        $_SESSION['auth'] = $page_status;
        $_SESSION['payload'] = $data;
    } else if ($res['status'] == 'success' && $res['message'] == 'V-COMP') {
        $url = $res['data']['authurl'];
        echo "<script>window.location.href = '$url'</script>";
        
    }
        
}

// complete transaction with suggested auth
if (isset($_POST['enter_pin'])){
    $pin = $_POST['pin'];

    //push into payload array
    $data = $_SESSION['payload'];
    $data['pin'] = $pin;
    $data['suggested_auth'] = $_SESSION['auth'];    
        
    $SecKey = $secret_key;

    $key = getKey($SecKey); 
    $dataReq = json_encode($data);
    $post_enc = encrypt3Des( $dataReq, $key );
    $postdata = array(
        'PBFPubKey' => $public_key,
        'client' => $post_enc,
        'alg' => '3DES-24'
    );

    //setup for charg
    $url = $baseurl."/flwv3-pug/getpaidx/api/charge";
    $res = postCURL($url, $postdata);

    // echo "<pre>";
    print_r($res);

    if ($res['status'] == 'success') {
        $page_status = 'OTP';
        $_SESSION['flwref'] = $res['data']['flwRef'];
    }
        
}

// complete transaction with suggested auth
if (isset($_POST['enter_otp'])){
    $otp = $_POST['otp'];

    // get flwref  
    $data = array(
        'PBFPubKey' => $public_key,
        'transaction_reference' => $_SESSION['flwref'],
        'otp' => $otp
    );

    //setup for charg
    $url = $baseurl."/flwv3-pug/getpaidx/api/validatecharge";
    $res = postCURL($url, $data);

    // echo "<pre>";
    // print_r($res);

    if ($res['status'] == 'success' && $res['data']['data']['responsecode'] == 00){
        //call the verify endpoint
        $txref = $res['data']['tx']['txRef'];
        $data = array(
            "txref" => $txref,
            "SECKEY" => $secret_key
        );

        $url = $baseurl.'/flwv3-pug/getpaidx/api/v2/verify';
        $res = postCURL($url, $data);

        if ($res['status'] == 'success' && $res['data']['chargecode'] == 00){
            $msg = $res['data']['status'];
            echo '<script>console.log('.json_encode($res).');</script>';
        }
    }
        
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Charge Sample Apps</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1>3DSecure Card Transaction</h1>
            </div>
            <div class="offset-md-4 col-md-4 text-center">
                <p style="text-align:left">
                    <b>Test with the below test card:</b><br>
                    4242424242424242 <br>
                    cvv 812 <br>
                    Expiry: 01/19 <br>
                </p>

                <p style="text-align: left; color: green">Note that the response will be appended to your provided redirect_url. You can use the $_GET to retrieve the response and verify the transaction before given any value.</p>
            </div>
            <div class="offset-md-4 col-md-4">
<?php
    // set transaction status
    if (isset($msg)){
        echo '<div class="alert alert-success" role="alert">
            Transaction status: <strong>'.$msg.'</strong>. You can check for full response in your console.
        </div>';
    }

    // Ask for pin
    if($page_status == 'PIN'){

?>

                <form action="index.php" method="POST">
                    <div class="form-group">
                        <label for="">Enter Card Pin</label>
                        <input type="text" name="pin" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="submit" name="enter_pin" class="btn btn-success btn-block" value="Continue">
                    </div>
                </form>

<?php

    } elseif ($page_status == 'OTP') {

        ?>

        <form action="index.php" method="POST">
            <div class="form-group">
                <label for="">Enter OTP</label>
                <input type="text" name="otp" class="form-control">
            </div>
            <div class="form-group">
                <input type="submit" name="enter_otp" class="btn btn-success btn-block" value="Continue">
            </div>
        </form>

<?php

    } else {
?>
                <form action="index.php" method="POST">
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="text" name="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Amount</label>
                        <input type="number" name="amount" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Card Number</label>
                        <input type="text" name="card" class="form-control">
                    </div>
                    <div class=" row form-group">
                        <div class="col-md-4">
                            <label for="">Expiry Month</label>
                            <input type="text" name="month" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="">Expiry Year</label>
                            <input type="text" name="year" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="">CVV</label>
                            <input type="text" name="cvv" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="initiate" class="btn btn-success btn-block" value="Pay">
                    </div>
                </form>

<?php
    }
?>
            </div>
        </div>
    </div>
</body>
</html>