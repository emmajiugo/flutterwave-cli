<?php require("inc/header.php");
$transaction->getWalletAmount($_SESSION['userid']);
?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Dashboard</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-money fa-4x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                <div class="huge"><?php if(isset($_SESSION["AvailableBalance"])){ echo $_SESSION["AvailableBalance"];}else{echo "Unavailable";}?></div>
                                    <div>Available Balance</div>
                                </div>
                            </div>
                        </div>
                        <a data-toggle="modal" data-target="#myModal">
                            <div class="panel-footer">
                                <span class="pull-left">Fund Rave Wallet</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-money fa-4x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php if(isset($_SESSION["LedgerBalance"])){ echo $_SESSION["LedgerBalance"];}else{echo "Unavailable";}?></div>
                                    <div>Ledger Balance</div>
                                </div>
                            </div>
                        </div>
                        <a data-toggle="modal" data-target="#myModal">
                            <div class="panel-footer">
                                <span class="pull-left">Fund Rave Wallet</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>

            </div>
            <!-- /.row -->
            <!-- modal -->
            <!-- Modal -->
            <div id="myModal" class="modal fade" role="dialog">
              <div class="modal-dialog modal-sm">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Fund Wallet</h4>
                  </div>
                  <div class="modal-body">
                    <p>Enter amount you want to fund.</p>
                    <form action="fund-wallet.php" method="POST">
                        <div class="form-group">
                            <label>Amount:</label><br>
                            <input type="text" class="form-control" name="amount" placeholder="Eg: 30000" name="amount">
                        </div>
                    
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" name="fund" class="btn btn-primary" value="Fund">
                    </form>
                  </div>
                </div>

              </div>
            </div>
            <!-- end modal -->

            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Test Cards
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <p>Use the below <b>Test Cards</b> to test the application and fund your wallet.</p>
                            <p>
                                Test Mastercard PIN authentication<br>
                                5399 8383 8383 8381<br>
                                cvv 470<br>
                                Expiry: 10/22<br>
                                Pin 3310<br>
                                otp 12345<br>
                            </p>
                            <hr>
                            <p>
                                Test Noauth Visa Card<br>
                                4751763236699647<br>
                                Expiry: 09/21<br>
                            </p>
                            <hr>
                            <p>
                                Test Noauth VisaCard<br>
                                4242 4242 4242 4242<br>
                                cvv: 812<br>
                                Expiry: 01/19<br>
                                Pin 3310<br>
                                otp 12345<br>
                            </p>
                            <hr>
                            <p>
                                Test Verve Card<br>
                                5061460410120223210<br>
                                Expiry Month 12<br>
                                Expiry Year 21<br>
                                cvv: 780<br>
                                Pin: 3310<br>
                                otp 12345<br>
                            </p>
                            <hr>
                            <p>
                                Test VisaCard (Local)<br>
                                4187427415564246<br>
                                cvv: 828<br>
                                Expiry: 09/19<br>
                                Pin 3310<br>
                                otp 12345<br>
                            </p>
                            <hr>
                            <p>
                                Test VisaCard (International)<br>
                                4556052704172643<br>
                                cvv: 899<br>
                                Expiry: 01/19<br>
                            </p>
                            <hr>
                            <p>
                                Test American Express Card (International)<br>
                                344173993556638<br>
                                cvv: 828<br>
                                Expiry: 01/22<br>
                            </p>
                            <hr>
                            <p>
                                Test card Declined<br>
                                5143010522339965<br>
                                cvv 276<br>
                                Expiry: 08/19<br>
                                Pin 3310<br>
                            </p>
                            <hr>
                            <p>
                                Test Card Fraudulent<br>
                                5590131743294314<br>
                                cvv 887<br>
                                Expiry: 11/20<br>
                                Pin 3310<br>
                                otp 12345<br>
                            </p>
                            <hr>
                            <p>
                                Test Card Insufficient Funds<br>
                                5258585922666506<br>
                                cvv 883<br>
                                Expiry: 09/19<br>
                                Pin 3310<br>
                                otp 12345<br>
                            </p>
                            <hr>
                            <p>
                                Pre-authorization Test Card<br>
                                5840406187553286<br>
                                cvv 116<br>
                                Expiry: 09/19<br>
                                Pin 1111<br>
                            </p>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <!-- /.col-lg-6-->
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Test Acounts
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <p>Use the below <b>Test Accounts</b> to test the application and fund your wallet.</p>
                            <p>
                                Access Bank<br>
                                Account number: 0690000031<br>
                                otp: 12345
                            </p>
                            <hr>
                            <p>
                                Providus Bank<br>
                                Account number: 5900102340, 5900002567<br>
                                otp: 12345<br>
                            </p>
                            <hr>
                            <p>
                                Sterling Bank<br>
                                Account number: 0061333471<br>
                                otp: 12345
                            </p>
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