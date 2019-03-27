<?php
include('../include/config.php');//connection

$membersDetails = $transaction->getMembers('1')->fetchall();
$trx = $api->bulkTransfer($membersDetails);

echo "<pre>";
print_r($trx);