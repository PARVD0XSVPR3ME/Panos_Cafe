<?php 
session_start();

if (!isset($_SESSION['e_uname'])) {
  header("Location: ../index.php");
  exit();
}
$_SESSION['location'] = array 
(
   'lat' => $_GET['lat'],
   'long' => $_GET['lng']            
);

?>

