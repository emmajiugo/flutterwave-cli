<?php require("inc/header.php");?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Verify Account</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
            <div class="row">                
                <div class="col-lg-10">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-table fa-fw"></i> Verify BVN
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <p>Make sure that a user says he is who he is.</p>
                            <p>Works on a rave live account and charges an amount for bvn verification</p>
                            <form action="verify-account.php" method="POST">
                                <div class="form-group col-md-8">
                                    <label>BVN Number:</label><br>
                                    <input type="text" name="bvnno" class="form-control" placeholder="0020176171">
                                </div>
                                <?php
                                //verify the account
                                if (isset($_POST['verify'])) {
                                    $bvn = $_POST['bvnno'];

                                    //pass to verify function
                                    $verify = $api->verifyBVN($bvn);

                                    // echo "<pre>";
                                    // print_r($verify);

                                    if ($verify['status'] === 'success') {
                                        echo '
                                            <div class="form-group col-md-8">
                                                <hr>
                                                <label>Account Name: </label>
                                                '.$verify['data']['data']['accountname'].'
                                                <p style="color:blue">'.$verify['data']['data']['responsemessage'].'</p>
                                            </div>
                                        ';
                                    }
                                }
                                ?> 
                                <div class="form-group col-md-8">
                                    <input type="submit" name="verify" value="Verify BVN" class="btn btn-success pull-right">
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

  <?php require("inc/footer.php");?>