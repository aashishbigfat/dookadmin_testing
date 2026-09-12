jQuery(document).ready(function(){
// code to get all records from table via select box
jQuery("#employee").change(function() {
var id = jQuery(this).find(":selected").val();

var dataString = 'empid='+ id;
console.log(dataString);
$.ajax({    //create an ajax request to display.php
        
        url: 'emp_details.php',             
        data: dataString,
        dataType: "json",   //expect html to be returned                
        cache: false,
        success: function(employeeData){                    
            if(employeeData) {
				$("#myInput").css('display','block');
				  $("#serviceFrameSend").attr("src", "detail.php");
				$("#heading").show();
				jQuery("#emp_id").text(employeeData.emp_id); 
				jQuery("#emp_name").text(employeeData.emp_name);
				jQuery("#designation").text(employeeData.designation);
				jQuery("#emp_mobile").text(employeeData.emp_mobile);
				jQuery("#hq_office").text(employeeData.hq_office);
				var phone_ext = employeeData.hq_phone + employeeData.extension_no ? '- Ext. '+employeeData.extension_no : '';
					jQuery("#hq_phone").text(phone_ext);
				jQuery("#mumbai_office").text(employeeData.mumbai_office);
				jQuery("#mumbai_phone").text(employeeData.mumbai_phone);
				jQuery("#email").text(employeeData.email);
				jQuery("#website").text(employeeData.website);
				jQuery("#overseas_office").text(employeeData.overseas_office);
				jQuery("#overseas_phone").text(employeeData.overseas_phone);
				jQuery("#emp_location").text(employeeData.emp_location);
				jQuery("#emp_image").attr('src',employeeData.emp_image_old);
				jQuery("#emp_sign").attr('src',employeeData.emp_signature_old);
				//jQuery("#location").attr('value',employeeData.emp_location);
				$("#records").show();
			}


        }
    });

});

$("#sub").click(function () {
  var ids  = $("#employee").val(); 
  var checkvals  = $("#selectedVal").val();
  var dataString = 'empsid='+ ids + '&checkval='+ checkvals;
  console.log(dataString);
  $.ajax({    //create an ajax request to display.php
        
        url: 'checked.php',             
        data: dataString,
        dataType: "json",   //expect html to be returned                
        cache: false,
        success: function(employeesData){                    
            if(employeesData) {
				// $("#myInput").css('display','block');
				// $("#serviceFrameSend").attr("src", "detail.php");
				$("#heading").show();
				jQuery("#emps_id").text(employeesData.emp_id); 
				jQuery("#emps_name").text(employeesData.emp_name);
				jQuery("#designation").text(employeesData.designation);
				jQuery("#emps_mobile").text(employeesData.emp_mobile);
				jQuery("#hqs_office").text(employeesData.hq_office);
				var phone_ext = employeeData.hq_phone + employeeData.extension_no ? '- Ext. '+employeeData.extension_no : '';
					jQuery("#hqs_phone").text(phone_ext);
				jQuery("#mumbais_office").text(employeesData.mumbai_office);
				jQuery("#mumbais_phone").text(employeesData.mumbai_phone);
				jQuery("#emails").text(employeesData.email);
				jQuery("#websites").text(employeesData.website);
				jQuery("#overseass_office").text(employeesData.overseas_office);
				jQuery("#overseass_phone").text(employeesData.overseas_phone);
				jQuery("#emps_location").text(employeesData.emp_location);
				jQuery("#emps_image").attr('src',employeesData.emp_image_old);
				jQuery("#emps_sign").attr('src',employeesData.emp_signature_old);
				$("#records").show();
			}			
        }
    });

if(checkvals=='delhi'){
	$('.mumbai').hide();
	$('.overseas').hide();
}
else if(checkvals=='delhimumbai'){
	$('.mumbai').show();
	$('.overseas').hide();
}
else if(checkvals=='delhimumbaiother'){
	$('.mumbai').show();
	$('.overseas').show();
}
else if(checkvals=='mumbaiother'){
	$('.mumbai').show();
	$('.overseas').show();
}
else if(checkvals=='delhiother'){
	$('.mumbai').hide();
	$('.overseas').show();
}
else{
	$('.mumbai').hide();
	$('.overseas').hide();
}


});
});
