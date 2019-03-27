<?php ob_start();
require("inc/header.php");
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
//get groupid from url
$groupid = $_GET['group'];

//create a group
if (isset($_POST['add'])) {
    //get groupid from url
    $groupid = $_GET['group'];
    $bank = $_POST['bank'];
    $acctno = $_POST['acctno'];
    $acctname = $_POST['acctname'];
    $salary = $_POST['salary'];

    //pass to function
    $transaction->addMember($groupid, $bank, $acctno, $acctname, $salary);
}

//paying the group members
if (isset($_POST['pay'])) {
    $groupid = $_POST['groupid'];

    //get wallet amount
    $walletamount = $transaction->getWalletAmount($_SESSION['userid']);

    //get total amount to be disbursed
    $totalPayout = $transaction->getTotalPayout($groupid);

    //check if user has money enough for payout
    // if ($totalPayout > $walletamount) {
    //     //insufficient amount
    //     echo "<script>alert('Insufficient Balance! \\nFund your wallet.');</script>";
    // } else {
        //pass to function
        $membersDetails = $transaction->getMembers($groupid)->fetchall();

        //pass ro function for bulk transfer
        $trx = $api->bulkTransfer($membersDetails);

        if ($trx['status'] === 'error') {
            $data['message'] = $trx["data"];
            $pusher->trigger('my-channel', 'my-event', $data);
        } else{
            $data['message'] = $trx["data"];
            $pusher->trigger('my-channel', 'my-event', $data);
        }
    //}
}
?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-6">
                    <h1 class="page-header">Bulk Transfer</h1>
                </div>
                <!-- /.col-lg-8 -->
                <div class="col-lg-4 col-md-6">
                    <br>
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-money fa-4x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $transaction->getTotalPayout($groupid); ?></div>
                                    <div>Total Group Payout</div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <form action="bulk-transfer-view.php?group=<?php echo $groupid; ?>" method="POST">
                                <input type="hidden" name="groupid" value="<?php echo $groupid; ?>">
                                <input type="submit" name="pay" value="Click to Pay" class="btn btn-sm btn-warning pull-right">
                            </form>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            
            <!-- Modal -->
            <div id="myModal" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Member</h4>
                  </div>
                  <div class="modal-body">
                    <p>Add member to the group.</p>
                    <form action="bulk-transfer-view.php?group=<?php echo $groupid; ?>" method="POST">
                        <div class="form-group">
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
                        <div class="form-group">
                            <label>Account Number:</label><br>
                            <input type="text" name="acctno" class="form-control" placeholder="0020176171">
                        </div>
                        <div class="form-group">
                            <label>Account Name:</label><br>
                            <input type="text" name="acctname" class="form-control" placeholder="John Doe">
                        </div>
                        <div class="form-group">
                            <label>Salary Amount:</label><br>
                            <input type="text" name="salary" class="form-control" placeholder="Eg: 30000">
                        </div>
                    
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" name="add" class="btn btn-primary" value="Add Member">
                    </form>
                  </div>
                </div>

              </div>
            </div>
            <!-- end modal -->

            <div class="row">
                <div class="col-lg-10">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Group Members
                            <button class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-target="#myModal">Add Member</button>
                            <br><br>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <p>NB: For the sake of this API test, we will not be deleting or editing any record. If you stumble on this from my github account, kindly add those features. It is highly recommended. We are skipping those features because this is not a production app.</p>
                            <br>
                            <?php
                            $membersCount = $transaction->getMembers($groupid)->rowCount();
                            if ($membersCount > 0) {
                                $members = $transaction->getMembers($groupid)->fetchall();
                                $sn = 0;

                                echo "<table class='table table-bordered'>";
                                echo "<tr>
                                        <th>S/N</th>
                                        <th>Staff Name</th>
                                        <th>Staff Bank</th>
                                        <th>Staff Account No.</th>
                                        <th>Staff Salary</th>
                                    </tr>";
                                foreach ($members as $member) {
                                    echo "<tr>
                                        <td>".++$sn."</td>
                                        <td>".$member['staffname']."</td>
                                        <td>".$api->getBankName($member['staffbank'])."</td>
                                        <td>".$member['staffacctno']."</td>
                                        <td>".$member['amount']."</td>
                                    </tr>";
                                }
                                echo "</table>";
                            } else {
                                echo "<p style='color: red'>No member in the group.</p>";
                            }
                            ?>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <!-- /.col-lg-6-->                
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

 <?php require("inc/footer.php");?>
