<?php require("inc/header.php");
require __DIR__.'/../vendor/autoload.php'; // Uncomment this autoloader if you need it
$options = array(
    'cluster' => 'eu',
    'useTLS' => true
  );
  $pusher = new Pusher\Pusher(
    '084259e4e356fb9a0622',
    'bfb407e2dc876b7fe5a3',
    '636291',
    $options
  );

//make a single transfer
if (isset($_POST['stransfer'])) {
    //get values
    $bankcode = $_POST['bank'];
    $accountno = $_POST['acctno'];
    $amount = $_POST['amount'];
    $narration = $_POST['comment'];

    /*
    ** check if money in the wallet is greater than
    ** the amount that wants to be transfered
    */
    $walletamount = $transaction->getWalletAmount($_SESSION['userid']);

   // if ($walletamount >= $amount) {
        //pass to single transfer function
        //echo "<script>alert('Hello');</script>";
        $trx = $api->singleTransfer($bankcode, $accountno, $amount, $narration);

       // check transaction status
        if ($trx['status'] === 'error') {
            $data['message'] = $trx["data"];
            $pusher->trigger('my-channel', 'my-event', $data);
        } else{
            $data['message'] = $trx["data"];
            $pusher->trigger('my-channel', 'my-event', $data);
        }
    // } else {
    //     //insufficient amount
    //     echo "<script>alert('Insufficient Wallet Balance. Please fund your wallet.');</script>";
    //     //echo "Insufficient Wallet Balance. Please fund your wallet.";
    // }
}

?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Single Transfer</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
            <div class="row">                
                <div class="col-lg-10">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-table fa-fw"></i> Single Transfer
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <p>Send money from your wallet to any bank account.</p>
                            <form action="single-transfer.php" method="POST">
                                <div class="form-group col-md-8">
                                    <label>Bank Name:</label><br>
                                    <select class="form-control" name="bank">
                                        <option value="">--select bank --</option>
                                        <?php 
                                            $banks = $api->getBanks();
                                            foreach ($banks as $bank) {
                                                echo '<option value="'.$bank['code'].'">'.$bank['name'].'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-8">
                                    <label>Account Number:</label><br>
                                    <input type="text" name="acctno" class="form-control" placeholder="0020176171">
                                </div>
                                <div class="form-group col-md-8">
                                    <label>Amount to send:</label><br>
                                    <input type="text" name="amount" class="form-control" placeholder="3000">
                                </div>
                                <div class="form-group col-md-8">
                                    <label>Narration:</label><br>
                                    <input type="text" name="comment" class="form-control" placeholder="Eg: Transfer for the goods delivered">
                                </div>
                                <div class="form-group col-md-8">
                                    <input type="submit" name="stransfer" value="Transfer" class="btn btn-success pull-right">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

<?php require("inc/footer.php")?>