<?php
require('library.php');

//set keys
$secret_key = "FLWSECK-xxxx-X";
$public_key = "FLWPUBK-xxxx-X";
$baseurl = "https://api.flutterwave.com";

$cards = array(
    '5399xxxxxx8381' => 'flw-t0-48f5cde294a70de4b40bf2bac8bcf75f-m03k',
    '4187xxxxxx4246' => 'flw-t0-972dd21d7d1e0ba119937481ecccea86-m03k'
);

if (isset($_POST['charge'])) {
    $token = $_POST['token'];

    $url = $baseurl."/v3/tokenized-charges";
    $data = array(
        "currency" => "NGN",
        "token" => $token,
        "country" => "NG",
        "amount" => 100,
        "email" => "e@x.com",
        "first_name" => "temi",
        "last_name" => "Oyekole",
        "tx_ref" => time()
    );

    $res = postCURL($url, $data, $secret_key);
    $msg = $res['message'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tokenize Charges</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="offset-2 col-md-8">
                <h1>Tokenized Charges</h1>
                <p>Before we can be able to save or charge a saved card, we must be able to make a  successful charge on a card. Once we charge a card successfully, we can save the <code>"embedtoken"</code> returned in the response. You can generate a Charge sample app using the CLI or read more about card charges <a href="https://developer.flutterwave.com/reference#card-payments" target="_blank">here</a>.</p>

                <p>For the sake of this app, we will assume we have already save the card in our DB; in this case, in an array.</p>

                <p><b>Very Importanter:</b> The saved cards are unique to the <code>Keys</code> that was used in saving them. So first, you need to do a successful charge and save the <code>embedtoken</code> before you can charge with your keys.</p>

                <h4>My Saved Cards</h4>
                <?php
                    if (isset($msg)){
                        echo '<div class="alert alert-info" role="alert">
                        <strong>Heads up!</strong> '.$msg.'.</div>';
                    }

                    echo '<ul class="list-group col-md-6">';
                    foreach($cards as $key => $value) {
                        echo '<li class="list-group-item">
                                <form method="POST" action="index.php">
                                    '.$key.'
                                    <input type="hidden" name="token" value="'.$value.'">
                                    <input type="submit" value="Charge" class="btn btn-sm btn-success float-right" name="charge">
                                </form>
                            </li>';
                    }
                    echo '</ul>';
                ?>
            </div>
        </div>
    </div>


</body>
</html>