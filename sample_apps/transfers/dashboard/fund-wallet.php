<?php
include('../include/config.php');//connection

if(isset($_POST['fund'])) {

	//get amount
	$amount = $_POST['amount'];

	//pass the amount to fundWallet function
	$api->fundWallet($amount);
	
}?>