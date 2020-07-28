<?php
require "vendor/autoload.php";

// load the .env
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

session_start();
require('library.php');



//set keys
$secret_key = getenv('SECRET_KEY');
$public_key = getenv('PUBLIC_KEY');
$encryption_key = getenv('ENCRYPTION_KEY');
$baseurl = "https://api.flutterwave.com";
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
        'card_number' => $card,
        'currency' => 'NGN',
        'country' => 'NG',
        'cvv' => $cvv,
        'amount' => '10',
        'expiry_year' => $year,
        'expiry_month' => $month,
        'redirect_url' => 'https://github.com/emmajiugo',
        'email' => $email,
        'tx_ref' => time(),
    );
        
    $SecKey = $secret_key;

    $key = $encryption_key;
    $dataReq = json_encode($data);
    $post_enc = encrypt3Des( $dataReq, $key );
    $postdata = array(
        'client' => $post_enc
    );

    //setup for charge
    $url = $baseurl."/v3/charges?type=card";
    $res = postCURL($url, $postdata, $secret_key);

    echo "<pre>";
    print_r($res);
    echo "</pre>";

    if ($res['status'] == 'success') {
        $page_status = $res['data']['authorization']['mode'];
        $_SESSION['auth'] = $page_status;
        $_SESSION['payload'] = $data;
    }else{
        $msg_err = $res['message'];
    }
        
}

// complete transaction with suggested auth
if (isset($_POST['enter_pin'])){
    $pin = $_POST['pin'];

    //push into payload array
    $data = $_SESSION['payload'];
    $data['authorization']['pin'] = $pin;
    $data['authorization']['mode'] = $_SESSION['auth'];    
        
    $SecKey = $secret_key;

    $key = $encryption_key; 
    $dataReq = json_encode($data);
    $post_enc = encrypt3Des( $dataReq, $key );
    $postdata = array(
        'client' => $post_enc
    );

    //setup for charge
    $url = $baseurl."/v3/charges?type=card";
    $res = postCURL($url, $postdata, $secret_key);

    if ($res['status'] == 'success') {
        $page_status = $res['meta']['authorization']['mode'];
        $id = $res['data']['id'];
        $_SESSION['flwref'] = $res['data']['flw_ref'];
    }
        
}

// complete transaction with suggested auth
if (isset($_POST['enter_otp'])){
    $otp = $_POST['otp'];

    // get flwref  
    $data = array(
        'type' => 'card',
        'flw_ref' => $_SESSION['flwref'],
        'otp' => $otp
    );

    //setup for charg
    $url = $baseurl."/v3/validate-charge";
    $res = postCURL($url, $data, $secret_key);

    if ($res['status'] == 'success'){
        //call the verify endpoint
        $prid = $res['data']['id'];
        
        $url = $baseurl.'/v3/transactions/'.$prid.'/verify';
        $res = getCURL($url,$secret_key);

        if ($res['status'] == 'success' && $res['data']['status'] == 'successful'){
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
                <h1>Card Transaction</h1>
                <p style="text-align:left">
                    <b>Test with the below test card:</b><br>
                    5399838383838381 <br>
                    cvv 470 <br>
                    Expiry: 10/22 <br>
                    Pin 3310 <br>
                    otp 12345 <br>
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

    if (isset($msg_err)){
        echo '<div class="alert alert-danger" role="alert">
            '.$msg_err.'
        </div>';
    }

    // Ask for pin
    if($page_status == 'pin'){

?>

                <form action="card.php" method="POST">
                    <div class="form-group">
                        <label for="">Enter Card Pin</label>
                        <input type="text" name="pin" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="submit" name="enter_pin" class="btn btn-success btn-block" value="Continue">
                    </div>
                </form>

<?php

    } elseif ($page_status == 'otp') {

        ?>

        <form action="card.php" method="POST">
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
                <form action="card.php" method="POST">
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