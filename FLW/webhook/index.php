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
  <script src="https://checkout.flutterwave.com/v3.js"></script>

  <script>
      test.addEventListener("click", function(e) {
        e.preventDefault();
        var email = document.getElementById('email').value;
        makePayment(email)
    })
  function makePayment(email) {
    FlutterwaveCheckout({
      public_key: "FLWSECK_TEST-SANDBOXDEMOKEY-X",
      tx_ref: "hooli-tx-1920bbtyt",
      amount: 600,
      currency: "NGN",
      payment_options: "card",
      customer: {
        email: email,
        phonenumber: "08102909304",
        name: "yemi desola",
      },
      callback: function (data) { // specified callback function
        if(data['status'] == 'successful'){
            window.location.href = "webhook.php";
        }else{
            alert("Transaction is unsuccessful");
        }
      },
      customizations: {
        title: "My store",
        description: "Payment for items in cart",
        logo: "https://www.designevo.com/images/home/3d-green-letter-o.png",
      },
    });
  }
</script>        
    </body>

</html>