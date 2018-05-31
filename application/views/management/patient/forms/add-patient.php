<script>
$(function() {

	$('#tin').mask('999-999-999-999');
	$('#appointment_dtp').datepicker({
		format: 'yyyy-mm-dd'
    });

    $('#birthdate_dp').datepicker({
		format: 'yyyy-mm-dd'
    });

    $("#birthdate_dp").live('blur', function(){
    	var bday = $("#birthdate_dp").val();
    	bday = new Date(bday);
		var today = new Date();
		var age = Math.floor((today-bday) / (365.25 * 24 * 60 * 60 * 1000));
		$('#age').val(age);
    });

	$("#add_new_patient_form").validationEngine({scroll:false});
	$("#add_new_patient_form").ajaxForm({
        success: function(o) {
      		if(o.is_successful) {
      			IS_ADD_USER_PATIENT_FORM_CHANGE = false;
      			//send_notif(o.notif_message,o.notif_title,o.notif_type);
      			loadMedical(o.patient_id);
      			default_success_confirmation({message : o.message, alert_type: "alert-success"});
      			$.unblockUI();
      		} else {
      			default_success_confirmation({message : o.message, alert_type: "alert-error"});
      		}
      		/*window.location.hash = "patient";
			reload_content("patient");*/
			$('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);
        },
         beforeSubmit : function(evt){
         	$.blockUI();
         },
        
        dataType : "json"
    });

    $(".add_patient_form").live('change', function(e) {
		if($(this).val()) {
			IS_ADD_USER_PATIENT_FORM_CHANGE = true;
		}
	});

	$(".add_submit_btn").on('click', function(event) {
		$('#add_new_patient_form').submit();
	});

	$("#age").live('change', function(e) {
		var age = $(this).val();
		if($.isNumeric(age) == false) {
			alert("Age must be a number!");
			$("#age").val("");
		}

		if(age.length > 2){
			alert("Invalid Age");
			$("#age").val("");
		} 
	});

	upload_party_image();

});

function getPatientCode(){
		/*var fname = $("#firstname").val();
		var lname = $("#lastname").val();

		if(fname != '' && lname != ''){
			var x = fname.substring(0,3);
			var y = lname.substring(0,3);
			$("#patient_code_view").html(y+x+"0001");
			$("#patient_code").val(y+x+"0001");
		}
	}*/
		var fname = $("#firstname").val();
		var lname = $("#lastname").val();
		var x = fname;
		var y = lname;
		
		$.post(base_url + 'patient_management/generateCode',{x:x, y:y},function(o) {
			$("#patient_code_view").html(o);
			$("#patient_code").val($.trim(o));
		});
	}
</script>
	<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-user.png"></li>
			<li><h1>Add Patient Information</h1></li>
		</ul>
		
		<ul id="controls">
			<li><a href="javascript: void(0);" onclick="$('#add_new_patient_form').submit();"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_save.png"></a></li>
			<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
			<li class="patient_menu"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_cancel.png"></li>
		</ul>
		<div class="clear"></div>
	</hgroup>
	<form action="<?php echo url('patient_management/add_new_patient') ?>" enctype="multipart/form-data" method="post" id="add_new_patient_form"  style="width:100%;">
		<section id="left" style="border-right: dashed 1px #d3d3d3;">
			<h1>Basic Information</h1>
			<section class="clear"></section>
					<ul id="form">
						<li>Initial Appointment Date</li>
						<li>
							<input type="text" id="appointment_dtp" name="appointment" class="textbox add_patient_form">
						</li>
					</ul>

				<section class="clear"></section>
				
					<ul id="form">
						<li>Doctor Assigned</li>
						<li>
							<script>
								var opts=$('#doctor_assigned_source').html(), opts2='<option></option>'+opts;
							    $('#doctor_assigned').each(function() { var e=$(this); e.html(e.hasClass('placeholder')?opts2:opts); });
							    $('#doctor_assigned').select2({allowClear: true});
							</script>
							<select id='doctor_assigned' name='doctor_assigned' class='populate add_returns_trigger' style='width:280px;'></select>
							<select id='doctor_assigned_source' class='validate[required]' style='display:none'><option value='0'>-Select-</option>";
								<?php foreach($doctors as $key=>$value): ?>
									<option value="<?php echo $value['id'] ?>"><?php echo $value['full_name'] ?></option>
								<?php endforeach; ?>
							</select>
						</li>
					</ul>

				<section class="clear"></section>
				
					<ul id="form">
						<li>Attending Doctor</li>
						<li>
							<script>
								var opts=$('#attending_doctor_source').html(), opts2='<option></option>'+opts;
							    $('#attending_doctor').each(function() { var e=$(this); e.html(e.hasClass('placeholder')?opts2:opts); });
							    $('#attending_doctor').select2({allowClear: true});
							</script>
							<select id='attending_doctor' name='attending_doctor' class='populate add_returns_trigger' style='width:280px;'></select>
							<select id='attending_doctor_source' class='validate[required]' style='display:none'><option value='0'>-Select-</option>";
								<?php foreach($doctors as $key=>$value): ?>
									<option value="<?php echo $value['id'] ?>"><?php echo $value['full_name'] ?></option>
								<?php endforeach; ?>
							</select>
						</li>
					</ul>
				
				<section class="clear"></section>

					<ul id="form02">
						<li>Patient Code</li>	
						<li><input type="hidden" id="patient_code" name="patient_code" class="textbox add_patient_form"  value="" readonly="readonly">
							<span id="patient_code_view"></span>
						</li>
					</ul>
					
				<section class="clear"></section>
				
					<ul id="form02">
						<li>Last Name<span>*</span></li>
						<li><input type="text" id="lastname" name="lastname" class="textbox validate[required] add_patient_form" onchange="javascript: getPatientCode();"></li>
					</ul>

				<section class="clear"></section>

				<ul id="form02">
					<li>Middle Name</li>
					<li><input type="text" name="middlename" id="middlename" class="textbox add_patient_form"></li>
				</ul>

				<section class="clear"></section>
			
					<ul id="form02">
						<li>First Name<span>*</span></li>
						<li><input type="text" name="firstname" id="firstname" class="textbox validate[required] add_patient_form" onchange="javascript: getPatientCode();"></li>
					</ul>
					
				<section class="clear"></section>
				
						<ul id="form">
							<li>Gender</li>
							<li>
								<input type="radio" name="gender" value="Male" class="add_patient_form"><label for="r1"><span></span>Male</label>
								<input type="radio" name="gender" value="Female" class="add_patient_form"> <label for="r2"><span></span>Female</label>
							</li>
						</ul>
				
				<section class="clear">	</section>
				
					<ul id="form">
						<li>Date of Birth<span>*</span></li>
						<li><input type="text" id="birthdate_dp" name="birthdate" class="textbox validate[required] add_patient_form"></li>
					</ul>
				
				<section class="clear"></section>
				
					<ul id="form">
						<li>Age</li>
						<li><input type="text" name="age" id="age" class="textbox add_patient_form" style="width: 127px;"></li>
					</ul>
					
				<section class="clear"></section>
				
					<ul id="form02">
						<li>Place of Birth</li>
						<li>
							<input type="text" name="placeofbirth" class="textbox add_patient_form">
						</li>
					</ul>
				<section class="clear"></section>

					<ul id="form02">
						<li>Current Address</li>
						<li>
							<input type="text" name="address" class="textbox add_patient_form">
						</li>
					</ul>

				<section class="clear"></section>
					<ul id="form02">
						<li></li>
						<li>
							<input type="text" name="address_2" class="textbox add_patient_form">
						</li>
					</ul>
				<section class="clear"></section>
					
					<ul id="form-address">
						<li></li>
						<li><input type="text" name="city" class="textbox add_patient_form" placeholder="City"></li>
						<!-- <li>
							<select name="state" class="select add_patient_form">
								<?php foreach ($state as $key => $value) { ?>
									<option value="<?php echo $value ?>"><?php echo $key ?></option>
								<?php } ?>
							</select>
						</li> -->
						<li><input type="text" name="zip" class="textbox add_patient_form" placeholder="Zip"></li>
					</ul>
				
				<section class="clear"></section>

					<ul id="form02">
						<li>Secondary Address</li>
						<li>
							<input type="text" name="secondary_address" class="textbox add_patient_form">
						</li>
					</ul>
				<section class="clear"></section>
					<ul id="form02">
						<li></li>
						<li>
							<input type="text" name="secondary_address_2" class="textbox add_patient_form">
						</li>
					</ul>
				<section class="clear"></section>
					
					<ul id="form-address">
						<li></li>
						<li><input type="text" name="secondary_city" class="textbox add_patient_form" placeholder="City"></li>
						<!-- <li>
							<select name="state" class="select add_patient_form">
								<?php foreach ($state as $key => $value) { ?>
									<option value="<?php echo $value ?>"><?php echo $key ?></option>
								<?php } ?>
							</select>
						</li> -->
						<li><input type="text" name="secondary_zip" class="textbox add_patient_form" placeholder="Zip"></li>
					</ul>
				
				<section class="clear"></section>

					<ul id="form02">
						<li>TIN</li>
						<li><input type="text" name="tin" id="tin" class="textbox add_patient_form" style="width:120px;"></li>
					</ul>
					
				<section class="clear"></section>

					<ul id="form02">
						<li>Senior Citizen ID</li>
						<li><input type="text" name="sc_ID" id="sc_ID" class="textbox add_patient_form" style="width:120px;"></li>
					</ul>
					
				<section class="clear"></section>
					
		</section>	
		
		<section id="right">
				<h1>Photo</h1>
				<section class="clear"></section>
					<ul id="form">
						<li>Upload Patient Photo</li>
						<li>
							<input type="file" id="patient_image" name="patient_image" class="textbox02"><br>
							<div>*File should not be larger than 25 MB</div>
						</li><section class="clear"></section>
						<div id="display_image_wrapper" class="hidden"></div>
						<span class="patient-img" style="position:relative; left: 200px;"><img id="patient_display_picture" src="<?php echo BASE_FOLDER; ?>themes/images/photo.png" style="width: 120px;"></span>
						</li><section class="clear"></section>
					</ul>
				
				<section class="clear"></section>

			<h1>Representative</h1>
			<section class="clear"></section>
			<ul id="form02">
				<li>Name:</li>
				<li><input type="text" name="representative_name" class="textbox add_patient_form"></li>
			</ul>
			<section class="clear"></section>
			<ul id="form02">
				<li>Relationship:</li>
				<li><input type="text" name="representative_relation" class="textbox add_patient_form"></li>
			</ul>
			<section class="clear"></section>
			<ul id="form02">
				<li>Mobile No.:</li>
				<li><input type="text" name="representative_mobile" class="textbox add_patient_form"></li>
			</ul>	
			<section class="clear"></section>
			<ul id="form02">
				<li>Email:</li>
				<li><input type="text" name="representative_email" class="textbox add_patient_form"></li>
			</ul>	
			<section class="clear"></section>
				
					
		</section>	
		<div class="line03"></div>
		<section id="left" style="border-right: dashed 1px #d3d3d3;">
				<h1>Contact Details</h1>

				<section class="clear"></section>

					<div id="add_contact_information_wrapper">
						<ul class="contact">
							<li>
								<select id="contact_information_type" name="contact_type" class="select02" onchange="javascript:filter_contact_extension();">
									<option value="Mobile">Mobile</option>
									<option value="Work">Work</option>
									<option value="Home">Home</option>
									<option value="Fax">Fax</option>
								</select>
							</li>
							<li><input type="text" name="contact_value" id="contact_information_value" class="textbox" style="width: 140px;"></li>
							<li><input type="text" id="contact_information_extension" name="contact_extension" style="width: 60px;display:none;" placeholder="Area Code"></li>
							<li><button type="button" id="add_contact_information_button">+Add Number</button></li>
						</ul>
					</div>
				
				<section class="clear"></section>

					<ul id="form">
						<li>Email Address</li>
						<li><input type="text" name="email" class="textbox validate[custom[email]] add_patient_form" ></li>
					</ul>

				<section class="clear"></section>

				<p class="emergency"><img src="<?php echo BASE_FOLDER; ?>themes/images/emergency.png"><span>In Case of Emergency, who do we contact?</span></p>
				<section class="clear"></section>
				
					<ul id="form02">
						<li>Contact Name</li>
						<li><input type="text" name="contact_name" class="textbox add_patient_form"></li>
					</ul>
				<section class="clear"></section>
					<ul id="form02">
						<li>Relationship</li>
						<li><input type="text" name="contact_relation" class="textbox add_patient_form"></li>
					</ul>
				<section class="clear"></section>
					<ul id="form02">
						<li>Address</li>
						<li><input type="text" name="contact_address" class="textbox add_patient_form"></li>
					</ul>
					
				<section class="clear"></section>
				
					<ul id="form02">
						<li>Contact Information</li>
					</ul>
					<section class="clear"></section>
					<div id="edit_contact_person_wrapper">
						<ul class="contact">
							<li>
								<select id="contact_person_type" name="contact_person_type" class="select02" onchange="javascript:filter_contact_person_extension();">
									<option value="Mobile">Mobile</option>
									<option value="Work">Work</option>
									<option value="Home">Home</option>
									<option value="Fax">Fax</option>
								</select>
							</li>
							<li><input type="text" name="contact_person_value" id="contact_person_value" class="textbox" style="width: 140px;"></li>
							<li><input type="text" id="contact_person_extension" name="contact_person_extension" style="width: 60px;display:none;" placeholder="Area Code"></li>
							<li><button type="button" id="add_contact_person_button">+Add Number</button></li>
						</ul>
					</div>
					<section class="clear"></section>
					<ul id="form">
						<li>Email Address<span>*</span></li>
						<li><input type="text" name="contact_email" class="textbox add_patient_form"></li>
					</ul>
					
		</section>
		<section id="right">
			<h1>Miscellaneous Details</h1>
			<section class="clear"></section>
			<ul id="form">
				<li>Civil Status</li>
				<li>
					<select name="status" class="select add_patient_form" style="width: 132px;">
						<option value="Single">Single</option>
						<option value="Married">Married</option>
						<option value="Widowed">Widowed</option>
						<option value="Seperated">Seperated</option>
					</select>
				</li>
			</ul>
				
			<section class="clear"></section>
				
					<ul id="form">
						<li>Dominant Hand Used</li>
						<li>
							<select name="hand" class="select add_patient_form" style="width: 132px;">
								<option value="Left">Left</option>
								<option value="Right">Right</option>
							</select>
						</li>
					</ul>
				
				<section class="clear"></section>
				
					<ul id="form">
						<li>Work Status</li>
						<li>
							<select name="work" class="select add_patient_form" style="width: 132px;">
								<option value="Full Time">Full Time</option>
								<option value="Part Time">Part Time</option>
								<option value="Student">Student</option>
								<option value="Self Employed">Self Employed</option>
								<option value="Retired">Retired</option>
							</select>
						</li>
					</ul>
				
				<section class="clear"></section>

				<ul id="form">
						<li>Relationship</li>
						<li>
							<textarea type="text" name="relationship" class="textbox add_patient_form"  style="width: 220px !important; height: 100px !important; resize: none;"  value="<?php echo $patient['relationship'] ?>" > </textarea>
						</li>
					</ul>
				
				<section class="clear"></section>

					<!-- <ul id="form">
						<li>Notes</li>
						<li><a href="javascript: void(0);" onclick="javascript: openViewNotes(<?php echo $patient['id'] ?>);" title="View Notes" >Add Notes</a></li>
					</ul> -->
		</section>
	</form>
	<div class="line02"></div>
<section class="clear"></section>
		<section id="buttons">
			<button class="form_button add_submit_btn" >Save & Continue</button>
			<button class="form_button patient_menu">Cancel</button>
		</section>			
</section>
<section class="clear"></section>
</section>