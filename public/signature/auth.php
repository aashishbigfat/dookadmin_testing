<?php
session_start();
if(!isset($_SESSION["user"])){
header("Location: https://www.dookinternational.com/signature/");

//header("Location: http://52.25.193.143/signature/");

exit(); }
?>