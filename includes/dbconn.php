<?php

$dbServername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "coffeeshop";

$conn = mysqli_connect($dbServername , $dbUsername , $dbPassword , $dbName);

$conn->set_charset("utf8");

if (mysqli_connect_error()) {
    die("Database connection failed: " . mysqli_connect_error());
}
//echo "Connected successfully";
