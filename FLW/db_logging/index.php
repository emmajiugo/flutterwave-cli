<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <title>Sample Payment Page and Database Logging</title>
</head>
<body>
  <div class="container">
    <h2 class="my-4 text-center">Buy this Dress for [$10]</h2>
    <p class="text-center">After payment, the necessary details will be logged to the DB.</p>
    <hr>
    <img src="img/dress.jpg">
    <form action="success.php" method="post" id="payment-form">
      <div class="form-group">
        <input type="text" name="full_name" id="full_name" class="form-control col-md-6 pageElement pageElement--empty" placeholder="Full Name" required>
        <input type="text" name="phone_number" id="number" class="form-control col-md-6 pageElement pageElement--empty" placeholder="Phone Number" required>
        <input type="email" name="email" id="email" class="form-control col-md-6 pageElement pageElement--empty" placeholder="Email Address" required>
        <input type="text" name="address" id="home_address" class="form-control col-md-6 pageElement pageElement--empty" placeholder="Home Address" required>
        <!-- Used to display form errors -->
        <div id="card-errors" role="alert"></div>
      </div>

      <button type="button" onClick="payWithRave()">Place Order</button>
    </form>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
 <script type="text/javascript" src="https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
  <script src="./js/charge.js"></script>
</body>
</html>