<?php
session_start(); //starting session

$servername = "localhost";
$username = "root";
$password = "";

try {

    $con = new PDO("mysql:host=$servername;dbname=demo", $username, $password);
    // set the PDO error mode to exception
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {

	echo "Connection failed: " . $e->getMessage();

}

//import all classes
include_once 'classes/class.general.php';
include_once 'classes/class.authenticate.php';
include_once 'classes/class.api.php';
include_once 'classes/class.transaction.php';


//initializing new instance
$general = new general($con);
$authenticate = new auth($con);
$api = new api($con);
$transaction = new transaction($con);

?>