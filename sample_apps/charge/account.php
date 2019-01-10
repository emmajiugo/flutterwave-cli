<?php
session_start();
require('library.php');

//set keys
$secret_key = "FLWSECK-ea81e705d82161de5b7757c897d96ba4-X";
$public_key = "FLWPUBK-56e4a2c6c9a6b58364bfd07fc1993e2c-X";
$baseurl = "https://ravesandboxapi.flutterwave.com";
$page_status = '';

// get back for direct debit
$url = $baseurl.'/flwv3-pug/getpaidx/api/flwpbf-banks.js?json=1';
$banks = getCURL($url);
// print_r($banks);

// initiate transaction
if (isset($_POST['initiate'])){
    $email = $_POST['email'];
    $amount = $_POST['amount'];
    $bank = $_POST['bank'];
    $accountno = $_POST['accountno'];

    //card payment
    $data = array(
        'PBFPubKey' => $public_key,
        'accountbank' => $bank,
        'accountnumber' => $accountno,
        'payment_type' => 'account',
        'country' => 'NG',
        'currency' => 'NGN',
        'amount' => $amount,
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

    if ($res['status'] == 'success') {
        $page_status = 'OTP';
        $_SESSION['flwref'] = $res['data']['flwRef'];
    }
        
}

// complete transaction with sug
if (isset($_POST['enter_otp'])){
    $otp = $_POST['otp'];

    // get flwref  
    $data = array(
        'PBFPubKey' => $public_key,
        'transactionreference' => $_SESSION['flwref'],
        'otp' => $otp
    );

    //validate account charge
    $url = $baseurl."/flwv3-pug/getpaidx/api/validate";
    $res = postCURL($url, $data);

    // echo "<pre>";
    // print_r($res);

    if ($res['status'] == 'success' && $res['data']['chargeResponseCode'] == 00){
        //call the verify endpoint
        $txref = $res['data']['txRef'];
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
            <div class="offset-md-4 col-md-4 text-center">
                <h1>Account Transaction</h1>
                <p style="text-align:left">
                    <b>Test with the below test account:</b><br>
                    Access Bank<br>
                    Account number: 0690000031<br>
                    otp: 12345
                </p>
            </div>
            <div class="offset-md-4 col-md-4">
<?php
    // set transaction status
    if (isset($msg)){
        echo '<div class="alert alert-success" role="alert">
            Transaction status: <strong>'.$msg.'</strong>. You can check for full response in your console.
        </div>';
    }

    // Ask for otp
    if ($page_status == 'OTP') {
?>
        <form action="account.php" method="POST">
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
                <form action="account.php" method="POST">
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="text" name="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Amount</label>
                        <input type="number" name="amount" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Select Bank</label>
                        <select name="bank" name="bank" class="form-control">
                            <option value="">-- select bank --</option>
                            <?php
                            foreach($banks as $bank){
                                echo '<option value="'.$bank['bankcode'].'">'.$bank['bankname'].'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Enter Account Number</label>
                        <input type="text" name="accountno" class="form-control">
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