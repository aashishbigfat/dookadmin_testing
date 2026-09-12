<?php
include_once("db_connect.php");

//$id = isset($_GET['id']) ? $_GET['id'] : '';
//print_r($_REQUEST['empid']) ; exit;
if(isset($_GET['empid'])) {
$sqls = "SELECT * FROM employees WHERE emp_id='".$_GET['empid']."'";
//echo $sqls ;
$resultset = mysqli_query($conn, $sqls) or die("database error:". mysqli_error($conn));
$data = array();
while( $rows = mysqli_fetch_assoc($resultset) ) {
//print_r($rows) ;
  $data=$rows;
  }
echo json_encode($data);
} 

?>
