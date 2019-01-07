<?php
//logging the user out
session_start(); 
session_unset();
session_destroy();
//header('location: index.php');
echo "<script>window.location.href = '../index.php';</script>";

?>