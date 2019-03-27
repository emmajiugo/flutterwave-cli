<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <title>Webhook Test</title>
    </head>
    <body>
  <div class="container">
      <div class="col-md-6 col-md-offset-4">
        <form>
          <div class="row">
            <div class="col-md-8">
              <label for="">Email address</label>
              <input type="text" name="email" id="email" class="form-control border-input" placeholder="Enter email address" style="margin-bottom: 30px;">
            </div>
          </div>

          <button id="test" class="btn btn-primary">Pay Now</button>
          <div class="clearfix"></div>
      </div>
      </form>

  </div>
  </div>
  <script src="https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
<script>
    const API_publicKey = "FLWPUBK-xxxxxxxxxxxxxxxxxxx-X";
    test.addEventListener("click", function(e) {
        e.preventDefault();
        var email = document.getElementById('email').value;
        payWithRave(email)
    })


function payWithRave(email) {
    var x = getpaidSetup({
        PBFPubKey: API_publicKey,
        customer_email: email,
        amount: 2000,
        currency: "NGN",
        txref: "rave-123456",
        meta: [{
            metaname: "flightID",
            metavalue: "AP1234"
        }],
        onclose: function() {},
        callback: function(response) {
            var txref = response.tx.txRef; // collect flwRef returned and pass to a 					server page to complete status check.
            console.log("This is the response returned after a charge", response);
            if (
                response.tx.chargeResponseCode == "00" ||
                response.tx.chargeResponseCode == "0"
            ) {
                // redirect to a success page

                window.location.href = "webhook.php";
            } else {
                // redirect to a failure page.
                alert("Transaction is unsuccessful");
            }

            x.close(); // use this to close the modal immediately after payment.
        }
    });
}
</script>
        
    </body>

</html>