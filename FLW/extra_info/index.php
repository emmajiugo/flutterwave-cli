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
} else {
    $skey = $test_skey;
    $pkey = $test_pkey;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">

    <!-- Title Page-->
    <title>Corona School Payment Platform</title>

    <!-- Icons font CSS-->
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <!-- Font special for pages-->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">

    <!-- Vendor CSS-->
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="vendor/datepicker/daterangepicker.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="css/main.css" rel="stylesheet" media="all">
</head>

<body>
    <div class="page-wrapper bg-gra-03 p-t-45 p-b-50">
        <div class="wrapper wrapper--w790">
            <div class="card card-5">
                <div class="card-heading" style="background: rgb(218, 37, 29);">
                    <h2 class="title" style="font-size: 18px">SECONDARY SCHOOL - PAYMENT FORM</h2>
                </div>
                <div class="card-body">
                    <p><b>NB:</b> The extra data that you want to pass to the payload can be passed through <b>Meta-data</b> in your payload. Check the code to see example of how extra information is passed in the payload.</p>
                    <br><br>

                    <!-- <form id="corona_form"> -->

                        <div class="form-row">
                            <div class="name">Student's Class</div>
                            <div class="value">
                                <div class="input-group">
                                    <div class="rs-select2 js-select-simple select--no-search">
                                        <select name="studentclass" id="studentclass" required>
                                            <option disabled="disabled" selected="selected">-- Choose your class --</option>
                                            <option value="Nursery 1">Nursery 1</option>
                                            <option value="Nursery 2">Nursery 2</option>
                                            <option value="Primary 1">Primary 1</option>
                                            <option value="Primary 2">Primary 2</option>
                                            <option value="Primary 3">Primary 3</option>
                                            <option value="Primary 4">Primary 4</option>
                                            <option value="Primary 5">Primary 5</option>
                                            <option value="Primary 6">Primary 6</option>
                                            <option value="Year 7">Year 7</option>
                                            <option value="Year 8">Year 8</option>
                                            <option value="Year 9">Year 9</option>
                                            <option value="Year 10">Year 10</option>
                                            <option value="Year 11">Year 11</option>
                                            <option value="Year 12">Year 12</option>
                                        </select>
                                        <div class="select-dropdown"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="name">Term</div>
                            <div class="value">
                                <div class="input-group">
                                    <div class="rs-select2 js-select-simple select--no-search">
                                        <select name="term" id="term" required>
                                            <option disabled="disabled" selected="selected">-- Choose Term --</option>
                                            <option value="FIRST TERM">FIRST TERM</option>
                                            <option value="SECOND TERM">SECOND TERM</option>
                                            <option value="THIRD TERM">THIRD TERM</option>
                                        </select>
                                        <div class="select-dropdown"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row m-b-55">
                            <div class="name">Name</div>
                            <div class="value">
                                <div class="row row-space">
                                    <div class="col-2">
                                        <div class="input-group-desc">
                                            <input class="input--style-5" type="text" name="first_name" id="first_name" required>
                                            <label class="label--desc">Student's First Name</label>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="input-group-desc">
                                            <input class="input--style-5" type="text" name="last_name" id="last_name" required>
                                            <label class="label--desc">Student's Last Name</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="name">Student ID</div>
                            <div class="value">
                                <div class="input-group">
                                    <input class="input--style-5" type="text" name="student_id" id="student_id" required>
                                </div>
                                <span id="msg"></span>
                            </div>                            
                        </div>
						
						 <div class="form-row">
                            <div class="name">Name of Payer</div>
                            <div class="value">
                                <div class="input-group">
                                    <input class="input--style-5" type="text" name="payer" id="payer" required>
                                </div>
                            </div>
                        </div>
                        
						
						<div class="form-row">
                            <div class="name">Payment Purpose</div>
                            <div class="value">
                                <div class="input-group">
                                    <input class="input--style-5" type="text" name="payment_purpose" id="payment_purpose" required>
                                </div>
                            </div>
                        </div>
						
						
						 <div class="form-row">
                            <div class="name">Amount</div>
                            <div class="value">
                                <div class="input-group">
                                    <input class="input--style-5" type="number" name="amount" id="amount" required>
                                </div>
                            </div>
                        </div>
						
                        <div class="form-row">
                            <div class="name">Email</div>
                            <div class="value">
                                <div class="input-group">
                                    <input class="input--style-5" type="email" name="email" id="email" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="name">Phone</div>
                            <div class="value">
                                <div class="input-group">
                                    <input class="input--style-5" type="text" name="phone" id="phone" required>
                                </div>
                            </div>
                        </div>  

                        <div>
                            <button class="btn btn--radius-2 btn--red pull-right" id="continue" type="button" onClick="makePayment()">Continue >></button>
                        </div>
                        <br><br>
                    <!-- </form> -->
                </div>
            </div>
        </div>
    </div>

    <!-- Jquery JS-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <!-- Vendor JS-->
    <script src="vendor/select2/select2.min.js"></script>
    <script src="vendor/datepicker/moment.min.js"></script>
    <script src="vendor/datepicker/daterangepicker.js"></script>

    <!-- Main JS-->
    <script src="js/global.js"></script>

<?php
if ($production == 'true') {
?>
    <!-- Flutterwave rave live script -->
    <script type="text/javascript" src="https://checkout.flutterwave.com/v3.js"></script>
<?php
} 

if ($production == 'false') {
?>
    <!-- Flutterwave rave test script -->
    <script type="text/javascript" src="https://checkout.flutterwave.com/v3.js"></script>
<?php
}
?>
    <!-- excel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.12.4/xlsx.core.min.js"></script>


    <!-- script goes here -->
    <script>
  function makePayment() {

    var studentClass = $('#studentclass').val();
            var firstName = $('#first_name').val();
            var lastName = $('#last_name').val();
            var studentId = $('#student_id').val();
            var term = $('#term').val();
            var payer = $('#payer').val();
            var paymentPurpose = $('#payment_purpose').val();
            var amount = $('#amount').val();
            var email = $('#email').val();
            var phone = $('#phone').val();

            if (firstName == "" || lastName == "" || studentId == "" || payer == "" || paymentPurpose == "" || amount == "" || email == "" || phone == "" || term == "" || studentClass == "") {
                alert("All fields must be filled.");
                // window.location.href = "index.php";
            } else {



    FlutterwaveCheckout({
      public_key: "<?php echo json_encode($pkey); ?>",
      tx_ref: "CRN-"+Math.random(),
      amount: amount,
      currency: "NGN",
      payment_options: "card, mobilemoneyghana, ussd",
      redirect_url: // specified redirect URL
        "https://github.com/emmajiugo",
        meta: [
                        {
                            metaname: "nameOfStudent",
                            metavalue: firstName+' '+lastName,
                        },
                        {
                            metaname: "studentClass",
                            metavalue: studentClass
                        },
                        {
                            metaname: 'studentId',
                            metavalue: studentId
                        },
                        {
                            metaname: 'term',
                            metavalue: term
                        },
                        {
                            metaname: 'paymentPurpose',
                            metavalue: paymentPurpose
                        }
                    ],
      customer: {
        email: email,
        phone_number: phone,
        name: payer,
      },
      callback: function (data) {
		console.log(data);
		if(data['status'] == 'successful'){
            txref = data['txref'];
            amount = data['amount'];
            id = data['transaction_id']
            verifyPayment(txref, amount, id);
		}else{
            alert('We are sorry, payment didn\' go through!');
		}
      },
      onclose: function() {
        // close modal
      },
      customizations: {
        title: "My store",
        description: "Payment for items in cart",
        logo: "https://assets.piedpiper.com/logo.png",
      },
    });
  }
}

function verifyPayment(txref, amount, transaction_id) {

window.location.href = "verify.php?txref="+txref+"&amount="+amount+"&id="+transaction_id;

}
</script>
</body>

</html>
<!-- end document-->