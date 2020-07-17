    
const API_publicKey = "FLWPUBK-XXXXXXXXXXXXXXXXXXXXXXX-X";

function payWithRave() {
    var email = $("#email").val();
    var fullname = $('#full_name').val();
    var num = $("#number").val();
    var address = $("#home_address").val();

    var x = getpaidSetup({
        PBFPubKey: API_publicKey,
        customer_email: email,
        amount: 10,
        customer_phone: num,
        currency: "NGN",
        customer_firstname: fullname,
        txref: "FL"+ Math.random(),
        meta: [{
            metaname: "address",
            metavalue: address
        }],
        onclose: function() {},
        callback: function(response) {
            var txref = response.tx.txRef; // collect txRef returned and pass to a 					server page to complete status check.
            console.log("This is the response returned after a charge", response);
            if (
                response.tx.chargeResponseCode == "00" ||
                response.tx.chargeResponseCode == "0"
            ) {
                // redirect to a success page
                window.location.href = "success.php?txid=" + txref;
            } else {
                // redirect to a failure page.
                window.location.href = "failed.php";
            } 

            x.close(); // use this to close the modal immediately after payment.
        }
    });
};


document.querySelector('#payment-form button').classList =
  'btn btn-primary btn-block col-md-3';
  var style = {
    base: {
      color: '#32325d',
      fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
      fontSmoothing: 'antialiased',
      fontSize: '16px',
      '::placeholder': {
        color: '#aab7c4'
      }
    },
    invalid: {
      color: '#fa755a',
      iconColor: '#fa755a'
    }
};