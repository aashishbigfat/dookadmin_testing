<?php

include('db_connect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Mailer</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">   
	<link rel="stylesheet" type="text/css" href="css/main.css"> 
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="getData.js"></script>
	 
</head>
<body>	
	<div class="limiter">
		<div class="container-login100">
			<div class="container">
				<div class="selectYourName">
					<p>Please select your name</p>
					<div class="form-group">
						<select class="form-control" name="empss" id="employee">
							<option style="text-align: center">--Select Employees Name--</option>
							<?php
								$sql = "SELECT * FROM employees";
								$resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
								while( $rows = mysqli_fetch_assoc($resultset) ) {
							?>
									<option value="<?php echo $rows["emp_id"]; ?>"><?php echo $rows["emp_name"]; ?></option>
								<?php } ?>

						</select>
					</div>
					
				
				</div>
		
				
				<div class="iframe-view container" id="myInput" style="display: none">
		<div class="row">
			 <div class="col-md-6" style="text-align: left;">
					    	<table class="table table-bordered">
							    <thead>
							      <tr>
							        <th>Details</th>
							        <th>Values</th>
							      </tr>
							    </thead>
							    <tbody>
							      <tr>
							        <td>Emp Id</td>
							        <td id="emp_id"></td>
							      </tr>
							      <tr>
							        <td>Emp Name</td>
							        <td id="emp_name"></td>
							      </tr>
							      <tr>
							        <td>Designation</td>
							        <td id="designation"></td>
							      </tr>
							      <tr>
							        <td>Emp Mobile</td>
							        <td id="emp_mobile"></td>
							      </tr>
							      <tr>
							        <td>Email</td>
							        <td id="email"></td>
							      </tr>
							      <tr>
							        <td>Delhi Office</td>
							        <td id="hq_office"></td>
							      </tr>
							      <tr>
							        <td>Delhi Office Phone</td>
							        <td id="hq_phone"></td>
							      </tr>
							      <tr>
							        <td>Employee Photo</td>
							        <td> <img style="width: 11%;" src="#" id="emp_image"></td>
							      </tr>
							    </tbody>
							  </table>
					    </div>
					    <div class="col-md-6" style="text-align: left;">
					    	
					    	<table class="table table-bordered">
							    <thead>
							      <tr>
							        <th>Details</th>
							        <th>Values</th>
							      </tr>
							    </thead>
							    <tbody>
							    	<tr>
							        <td>Website</td>
							        <td idid="website"></td>
							      </tr>
							      <tr>
							        <td>Mumbai Office</td>
							        <td id="mumbai_office"></td>
							      </tr>
							      <tr>
							        <td>Mumbai Office Phone</td>
							        <td id="mumbai_phone"></td>
							      </tr>
							     
							      <tr>
							        <td>Overseas Office</td>
							        <td id="overseas_office"></td>
							      </tr>
							      <tr>
							        <td>Overseas Phone</td>
							        <td id="overseas_phone"></td>
							      </tr>
							      <tr>
							        <td>Employee Location</td>
							        <td id="emp_location"></td>
							      </tr>
							      <tr>
							        <td>Employee Signature</td>
							        <td> <img src="#" id="emp_sign"></td>
							      </tr>

							    </tbody>
							  </table>

					    </div>
		</div>			
			<div class="row">
	                 <input type="hidden" id="selectedVal" name="checkedvalues"><br>
					 <div class="form-group aa">
					<input type="checkbox" class="checks" id="checkValues" name="doffice" disabled value="delhi" checked> Delhi Office<br>
					</div>
					<div class="form-group aa checker">
						<input type="checkbox" class="checks checkmumbai" id="checkValues" name="moffice" value="mumbai" id="location"> Mumbai Office<br>
					</div>
					<div class="form-group aa checker">
						<input type="checkbox" class="checks checkoverseas" id="checkValues" name="office" value="other" id="location">Overseas Office<br>
					</div>
					
					 </div>
					 <div class="row"></div>
					  <div class="row">
					  	<button type="button" id="sub" name="submit" class="btn-generate btn btn-success" id="display">Generate Sign</button>
					 </div>

<!-- signature code -->

<div id="signature" style="display: none;">
<div class="row" id="copyDiv">					   
<table cellspacing="0" cellpadding="0" border="0" align="center" width="600" style="border:0px">
   	<style type="text/css">
      table, tbody, tr, td, tr td, tr th{border-spacing: 0 !important;}
    </style>
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:100,300,400,400i,600,700&display=swap" rel="stylesheet">
    <tbody>
      <tr>
        <td>
          <table>
            <tbody>
            	 
              <tr>
                <td style="font-size: 10pt;font-family:Arial;width:124px;border:none;text-align:center;border-right:1px;" vAlign=top rowSpan=2>
                  <img border=0 src="#" id="emps_image" style="max-width:100%;margin-top:10px;">
                </td>
                <td style="width: 10px;margin: auto;text-align: center;">
                  <img border=0 src="https://www.dookinternational.com/dookinternational.com/mail/line.png" style="max-width:100%;">
                </td>
                <td style="font-size: 12px;">
                  <img border=0 src="#" id="emps_sign" style="max-width:100%;">
                  <p style="margin:0;font-weight:600;font-family:'Josefin Sans', sans-serif; text-align: left;" id="emps_id">
                  	</p>
                  <p style="margin:0;font-weight:600;font-family:'Josefin Sans', sans-serif; text-align: left;"><span style="margin:0;font-weight:600;font-family:'Josefin Sans', sans-serif;" id="emps_name"></span> - <span style="margin:0;font-weight:600;font-family:'Josefin Sans', sans-serif;" id="designation"></span></p>
                
                    
                  <p style="margin:0;font-weight:600;font-family:'Josefin Sans',sans-serif; text-align: left; ">Mobile - <span style="margin:0;font-weight:600;font-family:'Josefin Sans', sans-serif;" id="emps_mobile"></span></p>

                  <p style="margin:0;margin-top:5px;font-family:arial;font-size: 10px; text-align: left;" ><b style="font-weight:600;">Delhi Office - </b><span id="hqs_office"></span> -  | Landline - <span id="hqs_phone"></span> </p>

                  <p class="mumbai" style="margin:0;margin-top:5px;font-family:arial;font-size: 10px; text-align: left;" ><b style="font-weight:600;">Mumbai Office - </b><span id="mumbais_office"></span> -  | Landline - <span id="mumbais_phone"></span> </p>
                 
                  <p  style="margin:0;font-family:arial;font-size: 11px;margin-bottom: 5px;text-align: left;">
                    Email - <a href="mailto:" style="color:#551a8b" id="emails"></a> <!-- | --> <br>
                    Visit us at :- <a href="" target="_blank" style="color:#551a8b" id="websites"></a>
                  </p>
                    <p class="overseas" style="margin:0;margin-top:5px;font-family:arial;font-size: 10px text-align: left;" ><span id="emps_location"></span> | <b style="font-weight:600;">Overseas Office - </b><span id="overseass_office"></span> | Landline - <span id="overseass_phone"></span> 
                   </p>

                </td>
                <td style="width:94px;vertical-align:top">
                  <table>
                    <tbody>
                      <tr style="vertical-align:top">
                        <td>
                          <img border=0 src="https://www.dookinternational.com/dookinternational.com/mail/dook-logo.png" style="max-width:100%;vertical-align: top;margin-bottom:55px">
                        </td>
                      </tr>
                      <tr style="vertical-align:bottom;float: right;">
                        <td>
                          <img border=0 src="https://www.dookinternational.com/dookinternational.com/mail/iata.png" style="max-width:100%;vertical-align: bottom;">
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
              
            </tbody>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table>
            <tbody>
              <tr>
                <td style="background:#e20911;border-radius:30px;padding:5px 20px;font-size:9.5px;font-family:arial;color:#fff;line-height:15px;font-weight:600;">
                  UZBEKISTAN | KAZAKHSTAN | KYRGYZSTAN | AZERBAIJAN | ARMENIA | GEORGIA | RUSSIA | SERBIA  | UKRAINE BELARUS | BULGARIA | TAJIKISTAN | TURKMENISTAN | FINLAND | LATVIA | ESTONIA | LITHUANIA | CROATIA | ROMANIA
                </td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>
    </tbody>
  </table>
  </div>
    <div class="row">
		<button type="button" id="copyText" name="copytext" class="btn-generate btn btn-success" id="display" onclick="myFunction()">Copy Signature</button>
	</div>

</div>
<!-- Signature start here -->
						
						


<!-- Signature start here -->

				</div>

			</div>
		</div>
	</div>
	<script type="text/javascript">

	jQuery(document).ready(function() {

	jQuery("#display").click(function(e)) {
    e.preventDefault();
    jQuery('#myInput').hide();
    jQuery('#iframes').show();
}
});

	</script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script>
jQuery(document).ready(function() {
    $checks = $(":checkbox");
    $checks.on('change', function() {
        var string = $checks.filter(":checked").map(function(i,v){
            return this.value;
        }).get().join("");
        $('#selectedVal').val(string);
    });
});

// hide showsignature div

$("#sub").on("click",function(){
    $("#signature").css("display","block")
});

// Copy text

function myFunction() {
  var copyText = document.getElementById("copyDiv");
  copyText.select();
   
  document.execCommand("copy");
  alert("Copied the text: " + copyText.value);
}
</script>
	<style type="text/css">
		.form-group.aa {
    display: inline-flex;
    margin-right: 25px;
}
input.checks {
    margin-right: 5px;
}

	</style>

	<script>
		function myFunction() {
			
		}
		

		function gethtml(value){
			 
		}
	</script>

</body>

</html>