<?php
include('db_connect.php');
include('auth.php');
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

    <style>
        @font-face {
            font-family: 'font94060';
            src: url(css/EmbassyBT.ttf);
        }

        @import url('https://www.fontify.me/wf/88337ea1d85c75ed2e7c6e9fd3d15fff');
    </style>
</head>

<body>
    <div class="limiter">
        <div class="container-login100">
            <div class="container">
                <div class="text-center" style="margin:auto">
                    <img src="logo.png" class="img-fluid" style="margin-bottom:30px">
                </div>
                <style>
                    .selectYourName .select2-container--default .select2-selection--single .select2-selection__arrow {
                        height: 46px;
                    }

                    .selectYourName .select2-container--default .select2-selection--single .select2-selection__rendered {
                        line-height: 48px;
                    }

                    .selectYourName .select2-container .select2-selection--single {
                        height: 48px;
                    }

                    .selectYourName .select2-container .select2-selection--single:focus {
                        outline: none
                    }

                    .selectYourName .select2-container--default .select2-selection--single {
                        background-color: #fff7f7;
                        border: 1px solid #ffced0;
                    }
                </style>
                <div class="selectYourName" id="selectYourName" style="display: none">
                    <p style="margin-bottom:0">Generate Your Email Signature</p>
                    <p style="line-height:12px;"><small style="font-size:60%;color:#bd0007;">(Please read the
                            instructions before you proceed)</small></p>
                    <div class="form-group">
                        <select class="form-control" name="empss" id="employee">
                            <option style="text-align: center" value="">-- Select Your Name --</option>
                            <?php
                        $sql = "SELECT * FROM employees";
                        $resultset = mysqli_query($conn, $sql) or die("database error:" . mysqli_error($conn));
                        while ($rows = mysqli_fetch_assoc($resultset)) {
                            ?>
                            <option value="<?php echo $rows["emp_id"]; ?>">
                                <?php echo $rows["emp_name"]; ?>
                            </option>
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
                                    <!-- <tr>
                              <td>Emp Id</td>
                              <td id="emp_id"></td>
                            </tr> -->
                                    <tr>
                                        <td>Emp Name</td>
                                        <td><span id="emp_name"></span> (<span id="emp_id"></span>)</td>
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
                                        <td>Noida Office</td>
                                        <td><span id="hq_office"></span> | Landline: <span id="hq_phone"></span></td>
                                    </tr>
                                    <!--  <tr>
                               <td>Delhi Office Phone</td>
                               <td id="hq_phone"></td>
                             </tr> -->
                                    <tr>
                                        <td>Employee Photo</td>
                                        <td class="empimaged" id="emp_image"></td>
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
                                        <td id="website"></td>
                                    </tr>
                                    <!--   <tr>
                                 <td>Mumbai Office</td>
                                 <td id="mumbai_office"></td>
                               </tr> -->
                                    <tr id="mOffice">
                                        <td>Mumbai Office</td>
                                        <td><span id="mumbai_office"></span> | Landline: <span id="mumbai_phone"></span>
                                        </td>

                                    </tr>

                                    <tr id="oOffice">
                                        <td>Overseas Office</td>
                                        <td><span id="overseas_office"></span> | Landline: <span
                                                id="overseas_phone"></span>
                                        </td>

                                    </tr>
                                    <!--  <tr>
                               <td>Overseas Phone</td>
                               <td id="overseas_phone"></td>
                             </tr> -->
                                    <tr>
                                        <td>Location</td>
                                        <td class="imaged" id="emp_location"></td>
                                    </tr>
                                    <tr>
                                        <td>Employee Signature</td>
                                        <!--<td class="signrd" id="emp_sign"> </td>-->
                                        <td>
                                            <canvas id='textCanvas' height=45
                                                style="font-family:embassy;vertical-align: middle;"></canvas>

                                        </td>
                                    </tr>

                                </tbody>
                            </table>

                        </div>
                    </div>
                    <div class="row">
                        <input type="hidden" id="selectedVal" name="checkedvalues"><br>
                        <div class="form-group aa">
                            <input type="checkbox" class="checks" id="checkValues" name="doffice" disabled value="delhi"
                                checked> Noida Office<br>
                        </div>
                        <div class="form-group aa checker">
                            <input type="checkbox" class="checks checkmumbai" id="checkValues" name="moffice"
                                value="mumbai" id="location"> Mumbai Office<br>
                        </div>
                        <div class="form-group aa checker overcheckbox">
                            <input type="checkbox" class="checks checkoverseas" id="checkValues" name="office"
                                value="other" id="location">Overseas Office<br>
                        </div>

                    </div>
                    <div class="row"></div>
                    <div class="row">
                        <button type="button" id="sub" name="submit" class="btn-generate btn btn-success">Generate Sign
                        </button>
                    </div>

                    <!-- signature code -->

                  <div id="signature" style="display: none;">
                        <div class="row" id="copyDiv" style="padding-left: calc(50% - 300px);">
                            <table cellspacing="0" cellpadding="0" border="0" align="left" width="600"
                                style="border:0px;margin:30px auto;">
                                <style type="text/css">
                                    table,
                                    tbody,
                                    tr,
                                    td,
                                    tr td,
                                    tr th {
                                        border-spacing: 0 !important;
                                    }
                                </style>
                                <link
                                    href="https://fonts.googleapis.com/css?family=Josefin+Sans:100,300,400,400i,600,700&display=swap"
                                    rel="stylesheet">
                                <tbody>
                                    <tr>
                                        <td>
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td class="imgsimage"
                                                            style="font-size: 10pt;font-family:Arial;width:124px;border:none;text-align:center;border-right:1px;padding-top: 43px;"
                                                            vAlign=top rowSpan=2 id="emps_image">
                                                            <!-- <img border=0 src="#" id="emps_image" style="max-width:100%;margin-top:10px;"> -->
                                                        </td>
                                                        <td style="width: 10px;margin: auto;text-align: center;">
                                                            <img border=0
                                                                src="https://www.dookinternational.com/dookinternational.com/mail/line.png"
                                                                style="max-width:100%;">
                                                        </td>
                                                    <td>
                                                      <p style="margin:0;font-size:22px;font-weight:700;color:#111111;font-family:Arial, sans-serif;text-align:left!important;">
                                                            <span style="margin:0;font-weight:700;font-family:Arial, sans-serif;"
                                                                id="emps_name"></span>
                                                        </p>
                                                            <p style="margin:0;font-weight:600;font-family:Arial, sans-serif; text-align: left;font-size: 13.5px;line-height: 14px;display:none"
                                                                id="emps_id"></p>
                                                         <p style="margin:0;font-size:13px;font-weight:700;color:#4a4a4a;font-family:'Arial', sans-serif;text-align:left!important;">
                                                             <span style="margin:0;font-weight:700;font-family:'Arial', sans-serif;" id="designations"></span>
                                                            </p>
                                                             <p style="margin:2px 0 4px 0;width:40px;border-bottom:2px solid #e2001a;font-size:0;line-height:0;text-align:left!important;">&nbsp;</p>                       
                                                            <p style="font-size:12px;font-weight:700;color:#111111;vertical-align:middle;font-family:'Josefin Sans', sans-serif;text-align:left!important;display: flex;margin: 0;align-items: center;border-bottom: 1px solid #c9c9d1;padding: 5px 0px 5px 0px;">
                                                                <span>
                                                                <img src="https://www.dookinternational.com/signature_icon/mobile.png" width="15" height="15" style="display:block;">
                                                            </span>
                                                                <span style="margin:0;font-weight:700;font-family:'Josefin Sans', sans-serif;" id="emps_mobile"></span>
                                                            </p>
                                                            <p style="margin:0;margin-top:2px;font-family:arial;font-size: 12px; text-align: left;line-height:14px;display: flex;font-weight: 700;color: black;align-items: center;border-bottom: 1px solid #c9c9d1;padding: 5px 0px 5px 0px;">
                                                                <span style="margin-right: 4px;"><img src="https://www.dookinternational.com/signature_icon/pin.png" width="15" height="15" style="display:block;"></span>
                                                               <span id="hqs_office"></span> 
                                                            </p>
                                                            <p style="font-size:12px;font-weight:700;color:#111111;vertical-align:middle;font-family:'Josefin Sans', sans-serif;text-align:left!important;display: flex;margin: 0;align-items: center;">
                                                            <span style="margin-right:4px;">
                                                                <img src="https://www.dookinternational.com/signature_icon/telephone-icon.png" width="15" height="15" style="display:block;">
                                                            </span>
                                                            <span style="margin:0;font-weight:700;font-family:'Josefin Sans', sans-serif;border-bottom: 1px solid #c9c9d1;padding: 5px 0px 5px 0px;" id="hqs_phone"></span>
                                                            </p>
                                                            <p style="font-size:12px;font-weight:700;color:#111111;vertical-align:middle;font-family:'Josefin Sans', sans-serif;text-align:left!important;display: flex;margin: 0;align-items: center;border-bottom: 1px solid #c9c9d1;padding: 5px 0px 5px 0px;">
                                                            <span style="margin-right:4px">
                                                                <img src="https://www.dookinternational.com/signature_icon/mail.png" width="15" height="15" style="display:block;">
                                                            </span>
                                                                <span style="margin:0;font-weight:700;font-family:'Josefin Sans', sans-serif;" id="emailsA"></span>
                                                            </p>
                                                           <p style="font-size:12px;font-weight:700;color:#111111;vertical-align:middle;font-family:'Josefin Sans', sans-serif;text-align:left!important;display: flex;margin: 0;align-items: center;border-bottom: 1px solid #c9c9d1;padding: 5px 0px 5px 0px;">
                                                           <span style="margin-right:4px">
                                                                <img src="https://www.dookinternational.com/signature_icon/globe.png" width="15" height="15" style="display:block;">
                                                            </span>
                                                                <span style="margin:0;font-weight:700;font-family:'Josefin Sans', sans-serif;" id="websitesA"></span>
                                                            </p>
                                                        </td>
                                                        <td style="width: 10px;margin: auto;text-align: center;">
                                                            <img border=0 src="https://www.dookinternational.com/dookinternational.com/mail/line.png" style="max-width:100%;">
                                                        </td>
                                                        <td style="width:94px;vertical-align:top">
                                                           <table>
                                                                <tbody style="position:relative;">
                                                                    <tr style="vertical-align:top;border-bottom: 1px solid #bfbfbb;">
                                                                        <td style="padding-top: 38px;padding-bottom: 7px;">
                                                                            <img border="0" src="https://www.dookinternational.com/dookinternational.com/mail/Logo.png" style="max-width:100%;vertical-align: top;/*! margin-bottom:55px */">
                                                                        </td>
                                                                    </tr>
                                                                    <tr style="vertical-align:bottom;position: absolute;top: 115px;right: 10px;">
                                                                        
                                                                    <td style="padding: 34px 13px 0px 20px;">
                                                                            <img border="0" src="https://www.dookinternational.com/dookinternational.com/mail/iata.png" style="max-width:100%;vertical-align: bottom;">
                                                                        </td></tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr id="csi_countryList">
                                        <td style="border-top:4px solid #e20911;padding-top:12px;">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td
                                                            style="background:#e20911;border-radius:30px;padding:5px 20px;font-size:9.5px;font-family:arial;color:#fff;line-height:15px;font-weight:600;">
                                                            UZBEKISTAN | KAZAKHSTAN | KYRGYZSTAN | AZERBAIJAN | ARMENIA
                                                            | GEORGIA |
                                                            RUSSIA | SERBIA | UKRAINE BELARUS | BULGARIA | TAJIKISTAN |
                                                            TURKMENISTAN
                                                            | FINLAND | LATVIA | ESTONIA | LITHUANIA | CROATIA | ROMANIA
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
                            <button type="button" id="copyText" name="copytext" class="btn-generate btn btn-success"
                                onclick="selectElementContents( document.getElementById('copyDiv') );">Copy
                                Signature
                            </button>
                        </div>

                    </div>
                    <!-- Signature start here -->


                    <!-- Signature start here -->

                </div>
                <div class="mt-5"
                    style="display:block;float:left;clear:both;width:100%;margin-top: 3rem;border: 1px dashed;padding: 20px;border-color: #980006;">
                    <p>Select your name from the drop-down list.</p>
                    <p>Wait for the page to load completely with your image and related information.</p>
                    <p>A button labelled as “GENERATE SIGNATURE” will appear below the details along with the following
                        check boxes:
                    <ul style="padding-left: 20px;font-size: 90%;">
                        <li>Noida Office – visible for ALL</li>
                        <li>Mumbai Office – visible for ALL</li>
                        <li>Overseas Office – Visible, if you are an employee from one of our overseas locations (e.g.
                            ‘Biplab’ or ‘Alex’)</li>
                    </ul>
                    </p>
                    <p>Select either ‘Mumbai’ or ‘Overseas’ or both as per your choice/requirement.</p>
                    <p>Click on the ‘COPY SIGNATURE’ button. Your signature details will be copied to the Clipboard.</p>
                    <p>Access your Dook Travels Mailbox powered by Gmail. Go to: SETTINGS (Gear icon on top-right) &gt;
                        GENERAL TAB &gt; SIGNATURE BOX.</p>
                    <p>Paste your signature (CTRL + V). Scroll Down and save the details.</p>
                    <p>Your new HTML signature should now be ready to use!</p>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function () {

        jQuery("#display").click(function (e) {
            e.preventDefault();
            jQuery('#myInput').hide();
            jQuery('#iframes').show();
        });
    });

    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script>
        jQuery(document).ready(function () {
        $checks = $(":checkbox");
        $checks.on('change', function () {
            var string = $checks.filter(":checked").map(function (i, v) {
                return this.value;
            }).get().join("");
            $('#selectedVal').val(string);
        });
    });

    // hide showsignature div

    $("#sub").on("click", function () {
        $("#signature").css("display", "block")
    });

    // Copy text

    // function myFunction() {
    //   var copyText = document.getElementById("copyDiv");
    //   copyText.select();

    //   document.execCommand("copy");
    //   alert("Copied the text: " + copyText.value);
    // }

    </script>

    <script>
        function selectElementContents(el) {
        var body = document.body, range, sel;
        if (document.createRange && window.getSelection) {
            range = document.createRange();
            sel = window.getSelection();
            sel.removeAllRanges();
            try {
                range.selectNodeContents(el);
                sel.addRange(range);
            } catch (e) {
                range.selectNode(el);
                sel.addRange(range);
            }
        } else if (body.createTextRange) {
            range = body.createTextRange();
            range.moveToElementText(el);
            range.select();
        }
        document.execCommand("Copy");
    }


    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.js"></script>
    <script type="text/javascript">
        //$('#employee').select2();
    </script>
    <style type="text/css">
        .form-group.aa {
            display: inline-flex;
            margin-right: 25px;
        }

        input.checks {
            margin-right: 5px;
        }

        td.imgsimage>img {
            width: 45px;
        }

        td.empimaged>img {
            width: 45px;
        }
    </style>


</body>

</html>