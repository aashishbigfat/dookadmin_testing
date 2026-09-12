<?php

include('db_connect.php');
include('auth.php');
	//print_r($_REQUEST['imgBase64']);
	
	$imgBase64 = $_REQUEST['imgBase64'];
	$empName=$_REQUEST['empName'];
	$emp_id=$_REQUEST['emp_id'];
	$empName = preg_replace('/\s+/', '_', $empName);
	$emp_id=$emp_id;
	
	$sqls = "SELECT * FROM employees WHERE emp_id='".$emp_id."'";
	$resultset = mysqli_query($conn, $sqls) or die("database error:". mysqli_error($conn));
	$data = array();
	while( $rows = mysqli_fetch_assoc($resultset) ) {
        if($rows['emp_signature']){
			echo 'found';
		}else{
			 $imgBase64 = str_replace('data:image/png;base64,', '', $imgBase64);
			  $imgBase64 = str_replace(' ', '+', $imgBase64);
			  $data = base64_decode($imgBase64);
			  echo $image_path='images/emp_sign/'.$emp_id.'.png';
			   
			  $imgwrite= $file_write=file_put_contents($image_path, $data);
			  if($imgwrite){
			  	
				$sqls = "SELECT * FROM employees WHERE emp_id='".$emp_id."'";
				$resultset = mysqli_query($conn, $sqls) or die("database error:". mysqli_error($conn));
				$data = array();
				while( $rows = mysqli_fetch_assoc($resultset) ) {
					if($rows['emp_signature']){
						unlink($rows['emp_signature']);
					}
				}
				
				//unlink('images/emp_sign/Ajay_Shukla568.png');
				$sqls = "UPDATE employees SET `emp_signature` = '".$image_path."' WHERE emp_id='".$emp_id."'";
				$resultset = mysqli_query($conn, $sqls) or die("database error:". mysqli_error($conn));
			}
		}
	}
 
	
	
	
	

?> 