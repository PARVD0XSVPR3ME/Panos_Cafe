<?php 
session_start();

if (!isset($_SESSION['u_email'])) {
  header("Location: ../index.php");
  exit();
}
$_SESSION['address'] = array 
(
   'lat' => $_GET['lat'],
   'long' => $_GET['lng']            
);
?>

