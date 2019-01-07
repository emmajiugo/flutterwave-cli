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
                            <i class="fa fa-table fa-fw"></i> Charge Account
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <form action="charge-account.php" method="POST">
                                <div class="form-group col-md-8">
                                    <input type="submit" name="verify" value="Charge Account" class="btn btn-success pull-right">
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