<?php require("inc/header.php");

//create a group
if (isset($_POST['group'])) {
    $groupname = $_POST['gname'];
    $groupdesc = $_POST['gdesc'];
    $userid = $_SESSION['userid'];

    //pass to function
    $transaction->createGroup($groupname, $groupdesc, $userid);
}
?>
        

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Bulk Transfer</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
            <!-- Modal -->
            <div id="myModal" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Create Transfer Group</h4>
                  </div>
                  <div class="modal-body">
                    <p>Create a transfer group.</p>
                    <form action="bulk-transfer.php" method="POST">
                        <div class="form-group">
                            <label>Group Name:</label><br>
                            <input type="text" class="form-control" name="gname" placeholder="Enter group name">
                        </div>
                        <div class="form-group">
                            <label>Group Description:</label><br>
                            <textarea class="form-control" name="gdesc" placeholder="Enter group description"></textarea>
                        </div>
                    
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" name="group" class="btn btn-primary" value="Create">
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
                            <i class="fa fa-bar-chart-o fa-fw"></i> Add Groups
                            <button class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-target="#myModal">Add Group</button>
                            <br><br>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <?php
                            $groupsCount = $transaction->getGroups($_SESSION['userid'])->rowCount();
                            if ($groupsCount > 0) {
                                $groups = $transaction->getGroups($_SESSION['userid'])->fetchall();

                                foreach ($groups as $group) {
                                    echo "<div style='background-color:#f8f8f8; width:100%; padding:20px; margin-bottom:10px'>";
                                    echo "<h3>".$group['groupname']."</h3>";
                                    echo "<p>".$group['groupdesc']."</p>";
                                    echo "<a href='bulk-transfer-view.php?group=".$group['id']."' class='btn btn-sm btn-info pull-right'>View</a><br>";
                                    echo "</div>";
                                }
                            } else {
                                echo "<p>No group created.</p>";
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

   <?php require("inc/footer.php")?>
