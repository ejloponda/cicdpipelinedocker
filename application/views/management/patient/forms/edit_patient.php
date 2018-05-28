<script> 
$(function() {
	$('.tipsy').hide();
	$('.user-add-patient').removeClass("hidden");
	reset_sub_hilight();
	$(".patient_info_form").addClass("sub-hilited");
	$('.user-level-topbar').addClass("hidden");

	$('#tin').mask('999-999-999-999');
	$('#appointment_dtp_edit').datepicker({
		format: 'yyyy-mm-dd'
    });

    $('#birthdate_dp_edit').datepicker({
		format: 'yyyy-mm-dd'
    });

    $("#birthdate_dp_edit").live('blur', function(){
    	var bday = $("#birthdate_dp_edit").val();
    	bday = new Date(bday);
		var today = new Date();
		var age = Math.floor((today-bday) / (365.25 * 24 * 60 * 60 * 1000));
		$('#age_edit').val(age);
    });

	$("#edit_new_patient_form").validationEngine({scroll:false});
	$("#edit_new_patient_form").ajaxForm({
        success: function(o) {
      		if(o.is_successful) {
      			IS_EDIT_USER_PATIENT_FORM_CHANGE = false;
      			//send_notif(o.notif_message,o.notif_title,o.notif_type);
      			view_patient(o.patient_id);
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

    $(".edit_patient_form").live('change', function(e) {
		if($(this).val()) {
			IS_EDIT_USER_PATIENT_FORM_CHANGE = true;
		}
	});

	$(".edit_submit_btn").on('click', function(event) {
		$('#edit_new_patient_form').submit();
	});


	$("#age_edit").live('change', function(e) {
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

	contact_information_list(<?php echo $patient['id'] ?>,false);
	contact_person_list(<?php echo $patient['id'] ?>,false);

});

function getPatientCode(){
		var fname = $("#firstname").val();
		var lname = $("#lastname").val();
		var x = fname;
		var y = lname;
		
		$.post(base_url + 'patient_management/generateCode',{x:x, y:y},function(o) {
			$("#patient_code_view").html(o);
			$("#patient_code").val(o);
		});
	}
</script>
<!-- ( ͡° ͜ʖ ͡°) -->
<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-user.png"></li>
			<li><h1>Edit Patient Information</h1></li>
		</ul>
		
		<ul id="controls">
			<li><a href="javascript: void(0);" onclick="$('#edit_new_patient_form').submit();"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_save.png"></a></li>
			<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
			<li><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_cancel.png" onclick="javascript: view_patient2();"></li>
		</ul>
		<div class="clear"></div>
	</hgroup>

	<form action="<?php echo url('patient_management/add_new_patient') ?>" method="post" enctype="multipart/form-data" id="edit_new_patient_form" style="width:100%;">
		<section id="left" style="border-right: dashed 1px #d3d3d3;">
			<input type="hidden" name="id" id="id" value="<?php echo $patient['id'] ?>">
			<h1>Basic Information</h1>
			<section class="clear"></section>
			<ul id="form">
				<li>Initial Appointment Date</li>
				<li><input type="text" id="appointment_dtp_edit" name="appointment_edit" class="textbox edit_patient_form" value="<?php echo $patient['appointment'] ?>"></li>
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
								<option value="<?php echo $value['id'] ?>" <?php echo ($patient['doc_assigned_id'] == $value['id'] ? "selected" : "") ?>><?php echo $value['full_name'] ?></option>
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
								<option value="<?php echo $value['id'] ?>" <?php echo ($patient['doc_attending_id'] == $value['id'] ? "selected" : "") ?>><?php echo $value['full_name'] ?></option>
							<?php endforeach; ?>
						</select>
					</li>
				</ul>
			
			<section class="clear"></section>

			<ul id="form02">
				<li>Patient Code</li>
				<li><input type="hidden" id="patient_code" name="patient_code_edit" class="textbox edit_patient_form"  value="<?php echo $patient['patient_code'] ?>" readonly="readonly">
					<span id="patient_code_view"><?php echo $patient['patient_code'] ?></span>
				</li>

			</ul>
			<section class="clear"></section>
		
			<ul id="form02">
				<li>Last Name<span>*</span></li>
				<?php if($pm_pi['can_update']){ ?>
				<li><input type="text" id="lastname" name="lastname_edit" class="textbox validate[required] edit_patient_form" value="<?php echo $patient['lastname'] ?>" ></li>
				<?php }else{ ?>
				<li><input type="text" id="lastname" name="lastname_edit" class="textbox validate[required] edit_patient_form" onchange="javascript: getPatientCode();" value="<?php echo $patient['lastname'] ?>" readonly="readonly" ></li>
				<?php } ?>
			<section class="clear"></section>

			<ul id="form02">
				<li>Middle Name</li>
				<li><input type="text" name="middlename_edit" id="middlename_edit" class="textbox edit_patient_form" value="<?php echo $patient['middlename']; ?>"></li>
			</ul>

			<section class="clear"></section>

			<ul id="form02">
				<li>First Name<span>*</span></li>
				<li><input type="text" name="firstname_edit" id="firstname" class="textbox validate[required] edit_patient_form" onchange="javascript: getPatientCode();" value="<?php echo $patient['firstname']; ?>"></li>
			</ul>
			<section class="clear"></section>
		
			<ul id="form">
				<li>Gender</li>
				<li>
					<input type="radio" name="gender_edit" value="Male" class="edit_patient_form" <?php echo ($patient['gender'] == "Male" ? "checked" : "") ?>><label for="r1"><span></span>Male</label>
					<input type="radio" name="gender_edit" value="Female" class="edit_patient_form" <?php echo ($patient['gender'] == "Female" ? "checked" : "") ?>> <label for="r2"><span></span>Female</label>
				</li>
			</ul>
			<section class="clear">	</section>
		
			<ul id="form">
				<li>Date of Birth<span>*</span></li>
				<li><input type="text" id="birthdate_dp_edit" name="birthdate_edit" class="textbox validate[required] edit_patient_form" value="<?php echo date( "Y-m-d", strtotime( $patient['birthdate'] ) ); ?>"></li>
			</ul>
			<section class="clear"></section>
		
			<ul id="form">
				<li>Age</li>
				<li><input type="text" name="age_edit" id="age_edit" value="<?php echo $patient['age'] ?>" class="textbox edit_patient_form" style="width: 127px;"></li>
			</ul>
			<section class="clear"></section>
		
			<ul id="form02">
				<li>Place of Birth</li>
				<li>
					<input type="text" name="placeofbirth_edit" value="<?php echo $patient['placeofbirth'] ?>" class="textbox edit_patient_form">
				</li>
			</ul>
			<section class="clear"></section>
			<ul id="form02">
				<li>Current Address</li>
				<li>
					<input type="text" id="address_edit" name="address_edit" value="<?php echo $patient['address'] ?>" class="textbox edit_patient_form">
				</li>
			</ul>
			<section class="clear"></section>
			<ul id="form02">
				<li></li>
				<li>
					<input type="text" name="address_edit_2" class="textbox add_patient_form" value="<?php echo $patient['address_2'] ?>">
				</li>
			</ul>
			<section class="clear"></section>
			<ul id="form-address">
				<li></li>
				<li style="width: 100px !important; margin-right: 5px;" ><input type="text" name="city_edit" id="city_edit" class="textbox edit_patient_form" placeholder="City" value="<?php echo $patient['city'] ?>" style="width: 100px !important;"></li>
				<li><input type="text" name="zip_edit" id="zip_edit" class="textbox edit_patient_form" value="<?php echo $patient['zip'] ?>" placeholder="Zip" style="width: 100px !important;"></li>
			</ul>
			<section class="clear"></section>

			<ul id="form02">
				<li>Secondary Address</li>
				<li>
					<input type="text" id="secondary_address_edit" name="secondary_address_edit" value="<?php echo $patient['secondary_address'] ?>" class="textbox edit_patient_form">
				</li>
			</ul>
			<section class="clear"></section>
			<ul id="form02">
				<li></li>
				<li>
					<input type="text" name="secondary_address_edit_2" class="textbox add_patient_form" value="<?php echo $patient['secondary_address_2'] ?>">
				</li>
			</ul>
			<section class="clear"></section>
			<ul id="form-address">
				<li></li>
				<li style="width: 100px !important; margin-right: 5px;" ><input type="text" name="secondary_city_edit" id="secondary_city_edit" class="textbox edit_patient_form" placeholder="City" value="<?php echo $patient['secondary_city'] ?>" style="width: 100px !important;"></li>
				<li><input type="text" name="secondary_zip_edit" id="secondary_zip_edit" class="textbox edit_patient_form" value="<?php echo $patient['secondary_zip'] ?>" placeholder="Zip" style="width: 100px !important;"></li>
			</ul>
			<section class="clear"></section>


				<ul id="form02">
					<li>TIN</li>
					<li><input type="text" name="tin" id="tin" class="textbox edit_patient_form" style="width:120px;" value="<?php echo $patient['tin'] ?>"></li>
				</ul>
					
			<section class="clear"></section>

			<ul id="form02">
						<li>Senior Citizen ID</li>
						<li><input type="text" name="sc_ID_edit" id="sc_ID_edit" class="textbox edit_patient_form" style="width:120px;" value="<?php echo $patient['sc_id'] ?>"></li>
					</ul>
					
			<section class="clear"></section>
		
			
					
		</section>	
		<section id="right">
			<h1>Photo</h1>
			<section class="clear"></section>
			<ul id="form">
				<li>Upload Patient Photo</li>
				<li>
					<input type="file" id="patient_image" name="patient_image_edit" class="textbox02"><br>
					<div>*File should not be larger than 25 MB</div>
				</li><section class="clear"></section>
				<span style="position:relative; left: 200px;">
					<?php if($patient_image){ ?>
						<input type="hidden" value="<?php echo $patient_image['id'] ?>" name="patient_image_id">
						<img id="patient_display_picture" style="width:120px;" src="<?php echo BASE_FOLDER.$patient_image['base_path'] . $patient_image['filename'] . "." . $patient_image['extension']; ?>">
					<?php } else { ?>
						<img id="patient_display_picture" src="<?php echo BASE_FOLDER; ?>themes/images/photo.png">
					<?php } ?>
				</span>
				</li><section class="clear"></section>
			</ul>
			<section class="clear"></section>
			
			<h1>Representative</h1>
			<section class="clear"></section>
			<ul id="form02">
				<li>Name:</li>
				<li><input type="text" name="representative_name_edit" class="textbox add_patient_form" value="<?php echo $patient['representative_name'] ?>"></li>
			</ul>
			<section class="clear"></section>
			<ul id="form02">
				<li>Relationship:</li>
				<li><input type="text" name="representative_relation_edit" class="textbox add_patient_form" value="<?php echo $patient['representative_relation'] ?>"></li>
			</ul>
			<section class="clear"></section>
			<ul id="form02">
				<li>Mobile No.:</li>
				<li><input type="text" name="representative_mobile_edit" class="textbox add_patient_form" value="<?php echo $patient['representative_mobile'] ?>"></li>
			</ul>	
			<section class="clear"></section>
			<ul id="form02">
				<li>Email:</li>
				<li><input type="text" name="representative_email_edit" class="textbox add_patient_form" value="<?php echo $patient['representative_email'] ?>"></li>
			</ul>	
			<section class="clear"></section>
			
		</section>
		<div class="line03"></div>
		
		<section id="left" style="border-right: dashed 1px #d3d3d3;">	
			<h1>Contact Details</h1>
			<section class="clear"></section>
			<ul id="form">
				<li>Email Address</li>
				<li><input type="text" name="email_edit" value="<?php echo $patient['email_address'] ?>" class="textbox validate[custom[email]] edit_patient_form" ></li>
			</ul>
			<section class="clear"></section>
			<div id="contact_information_list_wrapper" style="width: 95%;"></div>
			<div id="add_contact_information_wrapper" class="contact-select">
				<ul class="contact">
					<li>
						<select id="contact_information_type" name="contact_type" class="select02" onchange="javascript:filter_contact_extension();">
							<option value="Mobile">Mobile</option>
							<option value="Work">Work</option>
							<option value="Home">Home</option>
							<option value="Fax">Fax</option>
						</select>
					</li>
					<li><input type="text" id="contact_information_extension" name="contact_extension" style="width: 60px;display:none;" placeholder="Area Code"></li>
					<li><input type="text" name="contact_value" id="contact_information_value" class="textbox" style="width: 140px;"></li>
					<li><button type="button" id="add_contact_information_button">+Add Number</button></li>
				</ul>
			</div>
			<section class="clear"></section>
		</section>
		<section id="right">
			<h1>Miscellaneous Details</h1>
			<section class="clear"></section>
			
			<ul id="form">
				<li>Dominant Hand Used</li>
				<li>
					<select name="hand_edit" id="hand_edit" class="select edit_patient_form" style="width: 220px;">
						<option value="Left" <?php echo ($patient['dominant_hand'] == "Left" ? "selected" : "") ?>>Left</option>
						<option value="Right" <?php echo ($patient['dominant_hand'] == "Right" ? "selected" : "") ?>>Right</option>
					</select>
				</li>
			</ul>
			<section class="clear"></section>
		
			<ul id="form">
				<li>Work Status</li>
				<li>
					<select name="work_edit" id="work_edit" class="select edit_patient_form" style="width: 220px;">
						<option value="Full Time" <?php echo ($patient['work_status'] == "Full Time" ? "selected" : "") ?>>Full Time</option>
						<option value="Part Time" <?php echo ($patient['work_status'] == "Part Time" ? "selected" : "") ?>>Part Time</option>
						<option value="Student" <?php echo ($patient['work_status'] == "Student" ? "selected" : "") ?>>Student</option>
						<option value="Self Employed" <?php echo ($patient['work_status'] == "Self Employed" ? "selected" : "") ?>>Self Employed</option>
						<option value="Retired" <?php echo ($patient['work_status'] == "Retired" ? "selected" : "") ?>>Retired</option>
					</select>
				</li>
			</ul>
			<section class="clear"></section>

			<ul id="form">
				<li>Civil Status</li>
				<li>
					<select name="status_edit" id="status_edit" class="select edit_patient_form" style="width: 220px;">
						<option value="Single" <?php echo ($patient['civil_status'] == "Single" ? "selected" : "") ?>>Single</option>
						<option value="Married" <?php echo ($patient['civil_status'] == "Married" ? "selected" : "") ?>>Married</option>
						<option value="Widowed" <?php echo ($patient['civil_status'] == "Widowed" ? "selected" : "") ?>>Widowed</option>
						<option value="Seperated" <?php echo ($patient['civil_status'] == "Seperated" ? "selected" : "") ?>>Seperated</option>
					</select>
				</li>
			</ul>
			<section class="clear"></section>

			<ul id="form">
				<li>Relationship</li>
				<li>
					<textarea type="text" name="relationship_edit" class="textbox add_patient_form"  style="width: 220px !important; height: 100px !important; resize: none;"  value="" ><?php echo $patient['relationship'] ?> </textarea>
				</li>
			</ul>
			<section class="clear"></section>

		</section>
		<div class="line03"></div>
		<section id="left" style="border-right: dashed 1px #d3d3d3;">
			<h1>Emergency Contact</h1><br/>
			<p class="emergency"><img src="<?php echo BASE_FOLDER; ?>themes/images/emergency.png"><span>In Case of Emergency, who do we contact?</span></p>
			<section class="clear"></section>
		
			<ul id="form02">
				<li>Contact Name</li>
				<li><input type="text" name="contact_name_edit" id="contact_name_edit" value="<?php echo $patient['contact_name'] ?>" class="textbox edit_patient_form"></li>
			</ul>
			<section class="clear"></section>
			<ul id="form02">
				<li>Relationship</li>
				<li><input type="text" name="contact_relation_edit" id="contact_relation_edit" value="<?php echo $patient['contact_relation'] ?>" class="textbox edit_patient_form"></li>
			</ul>
			<section class="clear"></section>
			<ul id="form02">
				<li>Address</li>
				<li><input type="text" name="contact_address_edit" id="contact_address_edit" value="<?php echo $patient['contact_address'] ?>" class="textbox edit_patient_form"></li>
			</ul>
			<section class="clear"></section>
			<ul id="form">
				<li>Email Address</li>
				<li><input type="text" name="contact_email_edit" id="contact_email" value="<?php echo $patient['contact_email_address'] ?>" class="textbox validate[custom[email]] edit_patient_form"></li>
			</ul>
			<section class="clear"></section>
			<ul id="form02">
				<li>Contact Information</li>
			</ul>
			<section class="clear"></section>

			<div id="contact_person_list_wrapper" style="width: 95%;"></div>
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
					<li><input type="text" id="contact_person_extension" name="contact_person_extension" style="width: 60px;display:none;" placeholder="Area Code"></li>
					<li><input type="text" name="contact_person_value" id="contact_person_value" class="textbox" style="width: 140px;"></li>
					<li><button type="button" id="add_contact_person_button">+Add Number</button></li>
				</ul>
			</div>
			<section class="clear"></section>
			
		</section>
		
	</form>
	<div class="line02"></div>
	<section class="clear"></section>
		<section id="buttons">
			<button class="form_button edit_submit_btn">Save & Continue</button>
			<button class="form_button patient_menu">Cancel</button>
		</section>			
	</section>
	<section class="clear"></section>
</section>
