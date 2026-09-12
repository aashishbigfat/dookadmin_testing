jQuery(document).ready(function () {
    setTimeout(function () {
        $("#selectYourName").css("display", "block");
        $("#employee").select2();
    }, 100);
    // code to get all records from table via select box
    jQuery("#employee").change(function () {
        var id = jQuery(this).find(":selected").val();
        if (id) {
            var dataString = "empid=" + id;
            console.log(dataString);
            $.ajax({
                //create an ajax request to display.php

                url: "emp_details.php",
                data: dataString,
                dataType: "json", //expect html to be returned
                cache: false,
                success: function (employeeData) {
                    if (employeeData) {
                        $("#myInput").css("display", "block");
                        $("#signature").css("display", "none");
                        $("#serviceFrameSend").attr("src", "detail.php");
                        $("#heading").show();
                        jQuery("#emp_id").text(employeeData.emp_id);
                        jQuery("#emp_name").text(employeeData.emp_name);
                        jQuery("#designation").text(employeeData.designation);
                        jQuery("#emp_mobile").text(employeeData.emp_mobile);
                        jQuery("#hq_office").text(employeeData.hq_office);
                        var phone_ext =
                            employeeData.hq_phone +
                            (employeeData.extension_no
                                ? "- Ext. " + employeeData.extension_no
                                : "");
                        jQuery("#hq_phone").text(phone_ext);
                        jQuery("#mumbai_office").text(
                            employeeData.mumbai_office
                        );
                        jQuery("#mumbai_phone").text(employeeData.mumbai_phone);
                        jQuery("#email").text(employeeData.email);
                        jQuery("#website").text(employeeData.website);
                        jQuery("#overseas_office").text(
                            employeeData.overseas_office
                        );
                        jQuery("#overseas_phone").text(
                            employeeData.overseas_phone
                        );
                        jQuery("#emp_location").text(employeeData.emp_location);
                        //jQuery("#emp_image").attr('src',employeeData.emp_image);
                        //jQuery("#emp_sign").attr('src',employeeData.emp_signature);
                        jQuery("#emp_image").html(
                            "<img src='https://adm.dookinternational.com/signature/images/" +
                                employeeData.emp_image_old +
                                "'>"
                        );
                        jQuery("#emp_sign").html(
                            "<img src='https://adm.dookinternational.com/signature/images/emp_sign/" +
                                employeeData.emp_signature_old +
                                "'>"
                        );
                        // jQuery("#emp_image").html("<img src='https://landing.dookinternational.com/signature/images/"+ employeeData.emp_image + "'>");
                        // jQuery("#emp_sign").html("<img src='https://landing.dookinternational.com/signature/images/"+ employeeData.emp_signature + "'>");

                        //setTimeout(function(){ getImagesign(); }, 1000);
                        $("#records").show();

                        setTimeout(function () {
                            getImagesign(employeeData.emp_id);
                        }, 1000);

                        if (employeeData == "1") {
                            $("#csi_countryList").css("display", "none");
                        }
                        if (employeeData.emp_location == "India") {
                            //alert(employeeData.emp_location);
                            $(".overcheckbox").hide();
                            $("#oOffice").hide();
                        } else if (employeeData.emp_location != "India") {
                            //alert(employeeData.emp_location);
                            $(".overcheckbox").show();
                            $("#oOffice").show();
                        } else {
                        }
                    }
                },
            });
        } else {
            $("#myInput").css("display", "none");
        }
    });

    function getImagesign(emp_id) {
        var tCtx = document.getElementById("textCanvas").getContext("2d");
        //imageElem = document.getElementById('image');

        var font = '400 35px "font94060", "Adobe Blank"';
        var empName = document.getElementById("emp_name").innerHTML;
        tCtx.font = font;
        console.log(tCtx);

        tCtx.canvas.width = tCtx.measureText(empName).width;
        tCtx.font = font;
        tCtx.fillText(empName, 0, 35);
        //imageElem.src = tCtx.canvas.toDataURL();
        //console.log(imageElem.src);
        uploadImage(tCtx.canvas.toDataURL(), empName, emp_id);
    }

    function uploadImage(dataURL, empName, emp_id) {
        //alert(dataURL);
        $.ajax({
            type: "POST",
            url: "upload_img.php",
            data: {
                imgBase64: dataURL,
                empName: empName,
                emp_id: emp_id,
            },
        }).done(function (o) {
            console.log(o);
            // If you want the file to be visible in the browser
            // - please modify the callback in javascript. All you
            // need is to return the url to the file, you just saved
            // and than put the image in your browser.
        });
    }

    $("#sub").click(function () {
        var ids = $("#employee").val();
        var checkvals = $("#selectedVal").val();
        var dataString = "empsid=" + ids + "&checkval=" + checkvals;
        console.log(dataString);
        $.ajax({
            url: "checked.php",
            data: dataString,
            dataType: "json", //expect html to be returned
            cache: false,
            success: function (employeesData) {
                if (employeesData) {
                    // $("#myInput").css('display','block');
                    // $("#serviceFrameSend").attr("src", "detail.php");
                    $("#heading").show();
                    jQuery("#emps_id").text(employeesData.emp_id);
                    jQuery("#emps_name").text(employeesData.emp_name);
                    jQuery("#designations").text(employeesData.designation);
                    jQuery("#emps_mobile").text(employeesData.emp_mobile);
                    jQuery("#hqs_office").text(employeesData.hq_office);
                    //jQuery("#hqs_phone").text(employeesData.hq_phone);
                    var phone_ext =
                        employeesData.hq_phone +
                        (employeesData.extension_no
                            ? "- Ext. " + employeesData.extension_no
                            : "");
                    jQuery("#hqs_phone").text(phone_ext);
                    jQuery("#mumbais_office").text(employeesData.mumbai_office);
                    jQuery("#mumbais_phone").text(employeesData.mumbai_phone);
                    //jQuery("#emails").text(employeesData.email);
                    //jQuery("#websites").text(employeesData.website);
                    jQuery("#overseass_office").text(
                        employeesData.overseas_office
                    );
                    jQuery("#overseass_phone").text(
                        employeesData.overseas_phone
                    );
                    jQuery("#emps_location").text(employeesData.emp_location);
                    // jQuery("#emps_image").attr('src',employeesData.emp_image);
                    // jQuery("#emps_sign").attr('src',employeesData.emp_signature);
                    jQuery("#emps_image").html(
                        "<img style='max-width:100%;margin-top:10px;width:100%;' src='https://adm.dookinternational.com/signature/images/" +
                            employeesData.emp_image_old +
                            "'>"
                    );
                    jQuery("#emps_sign").html(
                        "<img src='https://adm.dookinternational.com/signature/images/emp_sign/" +
                            employeesData.emp_signature_old +
                            "'>"
                    );
                    //jQuery("#emails").html("<a style="font-size:11px;" href='mailto:" + employeesData.email + "'>" + employeesData.email + "</a>");
                    jQuery("#emailsA").html(employeesData.email);
                    jQuery("#emailsA").attr(
                        "href",
                        "mailto:" + employeesData.email
                    );
                    //jQuery("#websites").html("<a href='" + employeesData.website + "'>" + employeesData.website + "</a>");
                    jQuery("#websitesA").html(employeesData.website);
                    jQuery("#websitesA").attr(
                        "href",
                        "https://" + employeesData.website
                    );
                    $("#records").show();
                }
            },
        });

        if (checkvals == "delhi") {
            $(".mumbai").hide();
            $(".overseas").hide();
        } else if (checkvals == "delhimumbai") {
            $(".mumbai").show();
            $(".overseas").hide();
        } else if (checkvals == "delhimumbaiother") {
            $(".mumbai").show();
            $(".overseas").show();
        } else if (checkvals == "mumbaiother") {
            $(".mumbai").show();
            $(".overseas").show();
        } else if (checkvals == "delhiother") {
            $(".mumbai").hide();
            $(".overseas").show();
        } else {
            $(".mumbai").hide();
            $(".overseas").hide();
        }
    });
});
