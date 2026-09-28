<?php
$servername = "192.168.4.7";
$username = "root";
$password = "__88y|7(aK||Rdo";
$dbname = "dookweb";

$conn = mysqli_connect($servername, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());

if (mysqli_connect_errno()) {
printf("Connect failed: %s\n", mysqli_connect_error());
exit();
}
?>