<?php
require('db_connect.php');
session_start();

if (isset($_POST['user']) && isset($_POST['pass'])) {
    $user = $_REQUEST['user'];
    $pass = $_REQUEST['pass'];

    $query = "SELECT * FROM users WHERE email='$user'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        if ($row) {
            $storedHashedPassword = $row['password'];

            // Use password_verify to compare the hashed input password with the stored hashed password
            if (password_verify($pass, $storedHashedPassword)) {
                // Passwords match, set the session and redirect
                $_SESSION['user'] = $user;
                header("Location: signature.php");
            } else {
                $error = "Username or password is incorrect.";
            }
        } else {
            $error = "Username not found.";
        }
    } else {
        $error = "Database error.";
    }
}
?>

<!DOCTYPE html>
<html>
   <head>
      <title></title>
      <style type="text/css">
      	*{margin:0;padding: 0}
      	.limiter {width: 100%;margin: 0 auto;}
      	.container-login100 {width: 100%;min-height: 100vh;display: -webkit-box;display: -webkit-flex;display: -moz-box;display: -ms-flexbox;display: flex;
      		flex-wrap: wrap;justify-content: center;align-items: center;/*padding: 15px;*/background: #9053c7;
		    background: -webkit-linear-gradient(-135deg,#c850c0,#4158d0);
		    background: -o-linear-gradient(-135deg,#c850c0,#4158d0);
		    background: -moz-linear-gradient(-135deg,#c850c0,#4158d0);
		    background: linear-gradient(-135deg,#c850c0,#4158d0);
		}
		.wrap-login100 {width: 400px;background: #fff;border-radius: 10px;overflow: hidden;display: -webkit-box;display: -webkit-flex;display: -moz-box;display: -ms-flexbox;display: flex;flex-wrap: wrap;justify-content: space-between;/*padding: 177px 130px 33px 95px;*/}
		.login100-pic img {max-width: 100%;}
		/*.login100-pic {width: 316px;}
		.login100-form {width: 290px;}*/
		.login100-pic{text-align: center;width: 100%;}
		.wrap-login100 form{width: 100%;float: left;padding: 20px 20px 40px 20px;}
		.login100-form-title {font-family: Poppins-Bold;font-size: 24px;color: #333;line-height: 1.2;text-align: center;width: 100%;display: block;padding-bottom: 54px;}
		.wrap-input100 {position: relative;width: 100%;z-index: 1;margin-bottom: 10px;}
		.validate-input {position: relative;}
		.input100 {font-family: Poppins-Medium;font-size: 15px;line-height: 1.5;color: #666;display: block;width: 72%;background: #e6e6e6;height: 50px;border-radius: 25px;padding: 0 30px 0 68px;}
		.container-login100-form-btn {width: 100%;display: -webkit-box;display: -webkit-flex;display: -moz-box;display: -ms-flexbox;display: flex;flex-wrap: wrap;    justify-content: center;padding-top: 20px;}
		.login100-form-btn {font-family: Montserrat-Bold;font-size: 15px;line-height: 1.5;color: #fff;text-transform: uppercase;width: 100%;height: 50px;border-radius: 25px;background: #57b846;display: -webkit-box;display: -webkit-flex;display: -moz-box;display: -ms-flexbox;display: flex;justify-content: center;align-items: center;padding: 0 25px;-webkit-transition: all .4s;-o-transition: all .4s;-moz-transition: all .4s;transition: all .4s;}
		button {outline: none!important;border: none;background: 0 0;}
      </style>
   </head>
   <body>
      <div class="limiter">
         <div class="container-login100">
            <div class="wrap-login100">
               <div class="login100-pic js-tilt">
                  <img src="../signature/logo.png" alt="Brand-Logo">
               </div> 
               <form class="login100-form validate-form" method="post" id="login">
                  <span class="login100-form-title">
                  Member Login
                  </span>
                  <div class="wrap-input100 validate-input">
                     <input class="input100" type="text" id="user" name="user" placeholder="Username" >
                  </div>
                  <div class="wrap-input100 validate-input" data-validate="Password is required">
                     <input class="input100" type="password" id="pass" name="pass" placeholder="Password" >
                  </div>
                  <div class="container-login100-form-btn">
                     <button type="submit" class="login100-form-btn" name="submit">
                     Login
                     </button>
                  </div> 
                  <span style="color:red;text-align: center;"><?php echo isset($error)?$error:''; ?></span>                 
               </form>
            </div>
         </div>
      </div>
        
  
   </body>
</html>