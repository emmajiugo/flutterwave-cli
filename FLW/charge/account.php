<?php
session_start();
require('library.php');

//set keys
$secret_key = "FLWPUBK-8a4df6681c15550f6aaf2fc4a5c6d428-X";
$public_key = "FLWSECK-fd1cb6456e26d2f12e7da43d0e07e0c1-X";
$encryption_key = "fd1cb6456e265d0806d619d1";
$baseurl = "https://ravesandboxapi.flutterwave.com";
$page_status = '';
$country = 'NG';//charge this for multi-currency
// get back for direct debit
$url = $baseurl.'/v3/banks/'.$country;
$banks = getCURL($url, $secret_key);
// print_r($banks);

// initiate transaction
if (isset($_POST['initiate'])){
    $email = $_POST['email'];
    $amount = $_POST['amount'];
    $bank = $_POST['bank'];
    $accountno = $_POST['accountno'];

    //card payment
    $data = array(
        
        'account_bank' => $bank,
        'account_number' => $accountno,
        'payment_type' => 'account',
        'currency' => 'NGN',
        'amount' => $amount,
        'redirect_url' => 'https://github.com/emmajiugo',
        'email' => $email,
        'tx_ref' => time(),
    );
        
    //setup for charge
    if($country == "UK"){
        $url = $baseurl."/v3/charges?type=debit_uk_account";
    }else {
        $url = $baseurl."v3/charges?type=debit_ng_account";
    }    
    $res = postCURL($url, $data, $secret_key);


    if ($res['status'] == 'success' && $res['message'] == 'Charge initiated') {
        $page_status = $res['meta']['authorization']['mode'];
        $_SESSION['flwref'] = $res['data']['flw_ref'];
    }
        
}

// complete transaction with sug
if (isset($_POST['enter_otp'])){
    $otp = $_POST['otp'];

    // get flwref  
    $data = array(
        'type' => 'account',
        'flw_ref' => $_SESSION['flwref'],
        'otp' => $otp
    );

    //validate account charge
    $url = $baseurl."/v3/validate-charge";
    $res = postCURL($url, $data);

    if ($res['status'] == 'success' && $res['message'] == 'Charge validated'){
        //call the verify endpoint
        $aid = (int)$res['data']['id'];
        $data = array(
            "id" => $aid
        );

        $url = $baseurl.'/v3/transactions/'.$data['id'].'/verify';
        $res = postCURL($url, $data, $secret_key);

        if ($res['status'] == 'success' && $res['data']['processor_response'] == 'Approved by Financial Institution'){
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
    if ($page_status == 'otp') {
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