/* USER MANAGEMENT */
var IS_ADD_USER_PATIENT_FORM_CHANGE 	= false;
var IS_EDIT_USER_PATIENT_FORM_CHANGE 	= false;
/* END OF USER MANAGEMENT */

/* THIS IS FOR MEDICAL HISTORY */
var IS_MEDICAL_HISTORY_FORM_CHANGE		= false;
/* END FOR MEDICAL HISTORY */

function reload_content(fragment) {
	var hash = window.location.hash;

	if(
		IS_ADD_USER_PATIENT_FORM_CHANGE ||
		IS_EDIT_USER_PATIENT_FORM_CHANGE	
	) {
		var con = confirm("Data is not yet saved. Do you want to continue?");

		if(con) {
			load_page(hash);
		}

	} else {
		load_page(hash);
	}
}

function load_page(hash) {
	IS_ADD_USER_PATIENT_FORM_CHANGE 	= false;
	IS_EDIT_USER_PATIENT_FORM_CHANGE 	= false;

	IS_MEDICAL_HISTORY_FORM_CHANGE 		= false;
	reset_all();
	reset_all_topbars();
	$('.user-level-topbar').removeClass('hidden');
	$('.user-sidebar').removeClass('hidden');

	$('#main_wrapper_management').html("");

	if(hash == "#patient" || hash == "") {
		$('.patient_menu').addClass('hilited');
		getPatientList();
	}else if(hash == "#new_patient"){
		$('.patient_menu').addClass('hilited');
		addNewPatient();
	}else if(hash == "#regimen"){
		$('.regimen_menu').addClass('hilited');
		getUserRoleList();
	}else if(hash == "#regimen"){
		$('.account_billing_form').addClass('hilited');
		getUserRoleList();
	}
}

$(".patient_menu").live('click', function() {
	window.location.hash = "patient";
	reload_content("patient");
});

$(".order_menu").live('click', function() {
	window.location.href = "order";
});

$(".add_patient_menu").live('click', function() {
	window.location.hash = "new_patient";
	reload_content("new_patient");
});

$(".regimen_menu").live('click', function() {
	window.location.href = "regimen";
});

$(".activity_menu").live('click', function() {
	window.location.hash = "activity";
	reload_content("activity");
});

$(".module_management_menu").live('click', function() {
	window.location.href="module";
});

$(".firm_admin_users").live('click', function() {
	window.location.href="users";
});

$(".firm_admin_roles").live('click', function() {
	window.location.href="permissions";
});

$(".firm_admin_permissions").live('click', function() {
	window.location.href="users#permissions";
});

$("#view_personal_medical_history").live('click', function() {
	view_personal_medical_history();
});

$("#view_family_medical_history").live('click', function() {
	view_medical_history();
});

$(".inventory_management_menu").live('click', function() {
	window.location.href = "inventory";
});

$(".upload_file_menu_form").live('click', function() {
	upload_file_form_modal();
});

$(".billing_menu").live('click', function() {
	window.location.href = "billing";
});

$(".reports_menu").live('click', function() {
	window.location.href = "reports";
});

$(".activity_log_menu").live('click', function() {
	window.location.href = "activity_log";
});

$(".calendar_menu").live('click', function() {
	window.location.href = "calendar";
});



function getPatientList(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "patient_management/patient",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function addNewPatient(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "patient_management/add_patient",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}


$("#add_contact_information_button").live('click', function() {
	add_contact_information();
});


// CONTACT INFORMATION

var contact_info_counter = 0;

function add_contact_information() {
	var contact_type_id 		= "contact_type_" + contact_info_counter;
	var contact_type_select 	= '<select id="' + contact_type_id + '" name="contact_information['+contact_info_counter+'][contact_type]" class="select02 add_user_form"><option value="Mobile">Mobile</option><option value="Work">Work</option><option value="Home">Home</option><option value="Fax">Fax</option></select>';

	var contact_type_value_id 	= "contact_type_value_" + contact_info_counter;
	var contact_type_value_text = '<input type="text" id="' + contact_type_value_id + '" name="contact_information['+contact_info_counter+'][contact_type_value]" class="textbox" style="width: 140px;">';

	var contact_type = $('#contact_information_type').val();
	if(contact_type == "Fax" || contact_type == "Work") {
		var extension_id 			= "contact_information_extension_" + contact_info_counter;
		var extension_text 			= ' <input type="text" id="' + extension_id + '" name="contact_information['+contact_info_counter+'][contact_extension]" class="textbox" style="width: 60px;" placeholder="Area Code">';
	} else {
		var extension_text = "";	
	}

	var delete_button_id 		= "delete_button_" + contact_info_counter;
	var delete_button 			= '<button id="' + delete_button_id + '" name="' + delete_button_id + '" type="button" onclick="javacript:delete_contact_information('+contact_info_counter+')">Delete</button>';

	var element_id	= "contact_information_"+contact_info_counter;
	var element_clear_id = "clear_contact_"+contact_info_counter;
	var element 	= "<ul style='padding-bottom: 10px;' class='contact' id='"+element_id+"'><li>" + contact_type_select + "</li><li> " + contact_type_value_text + "</li><li>" + extension_text + " </li><li> " + delete_button + "</li></ul><section class='clear' id='" + element_clear_id +"'></section>";
	contact_info_counter++;

	$("#add_contact_information_wrapper").before(element);

	$('#'+contact_type_id).val($('#contact_information_type').val());
	$('#'+contact_type_value_id).val($('#contact_information_value').val());
	$('#'+extension_id).val($('#contact_information_extension').val());

	$('#contact_information_value').val("")
	$('#contact_information_extension').val("");
}

function delete_contact_information(element) {
	$('#contact_information_'+element).remove();
	$('#clear_contact_'+element).remove();
}

function filter_contact_extension() {
 	var contact_type = $('#contact_information_type').val();
 	if(contact_type == "Fax" || contact_type == "Work") { 
 		$('#contact_information_extension').show();
 	} else {
 		$('#contact_information_extension').hide();
 	}
}

function contact_information_list(user_id,val) {
	var user_id = parseInt(user_id);
	$('#contact_information_list_wrapper').html(default_ajax_loader);
	$.post(base_url + "admin/contact_information_list",{user_id:user_id,val:val},function(o){
		$('#contact_information_list_wrapper').html(o);
	});
}

function edit_contact_information(id) {
	var user_id = parseInt($('#user_id').val());
	$.post(base_url + 'admin/edit_contact_information_form',{id:id,user_id:user_id},function(o) {
		$('#edit_contact_information_form_wrapper').html(o);
		$('#edit_contact_information_form_wrapper').modal();
		
		$('#edit_contact_information_form_wrapper').on('hidden', function () {
		  $("#edit_contact_information_form").validationEngine('hide');
		});
	});
}

function delete_contact_information_data(id) {
	var id = parseInt(id);
	var user_id = parseInt($('#user_id').val());
	$.post(base_url + 'admin/delete_contact_information_form',{id:id,user_id:user_id},function(o) {
		$('#delete_contact_information_form_wrapper').html(o);
		$('#delete_contact_information_form_wrapper').modal();
	});
}

// END OF CONTACT INFORMATION

$("#add_contact_person_button").live('click', function() {
	add_contact_person();
});

// CONTACT PERSON

var contact_person_counter = 0;

function add_contact_person() {
	var contact_type_id 		= "contact_person_type_" + contact_person_counter;
	var contact_type_select 	= '<select id="' + contact_type_id + '" name="contact_person['+contact_person_counter+'][contact_type]" class="select02 add_user_form"><option value="Mobile">Mobile</option><option value="Work">Work</option><option value="Home">Home</option><option value="Fax">Fax</option></select>';

	var contact_type_value_id 	= "contact_person_type_value_" + contact_person_counter;
	var contact_type_value_text = '<input type="text" id="' + contact_type_value_id + '" name="contact_person['+contact_person_counter+'][contact_type_value]" class="textbox" style="width: 140px;">';

	var contact_type = $('#contact_person_type').val();
	if(contact_type == "Fax" || contact_type == "Work") {
		var extension_id 			= "contact_person_extension_" + contact_person_counter;
		var extension_text 			= ' <input type="text" id="' + extension_id + '" name="contact_person['+contact_person_counter+'][contact_extension]" class="textbox" style="width: 60px;" placeholder="Area Code">';
	} else {
		var extension_text = "";	
	}

	var delete_button_id 		= "delete_cp_button_" + contact_person_counter;
	var delete_button 			= '<button id="' + delete_button_id + '" name="' + delete_button_id + '" type="button" onclick="javacript:delete_contact_person('+contact_person_counter+')">Delete</button>';

	var element_id	= "contact_person_"+contact_person_counter;
	var element_clear_id = "clear_contact_person_"+contact_person_counter;
	var element 	= "<ul style='padding-bottom: 10px;' class='contact' id='"+element_id+"'><li>" + contact_type_select + "</li><li> " + contact_type_value_text + "</li><li>" + extension_text + " </li><li> " + delete_button + "</li></ul><section class='clear' id='" + element_clear_id +"'></section>";
	contact_person_counter++;

	$("#edit_contact_person_wrapper").before(element);

	$('#'+contact_type_id).val($('#contact_person_type').val());
	$('#'+contact_type_value_id).val($('#contact_person_value').val());
	$('#'+extension_id).val($('#contact_person_extension').val());

	$('#contact_person_value').val("")
	$('#contact_person_extension').val("");
}

function delete_contact_person(element) {
	$('#contact_person_'+element).remove();
	$('#clear_contact_person_'+element).remove();
}

function filter_contact_person_extension() {
 	var contact_type = $('#contact_person_type').val();
 	if(contact_type == "Fax" || contact_type == "Work") { 
 		$('#contact_person_extension').show();
 	} else {
 		$('#contact_person_extension').hide();
 	}
}

function contact_person_list(patient_id,val) {
	var patient_id = parseInt(patient_id);
	$('#contact_person_list_wrapper').html(default_ajax_loader);
	$.post(base_url + "patient_management/contact_person_list",{patient_id:patient_id,val:val},function(o){
		$('#contact_person_list_wrapper').html(o);
	});
}

function edit_contact_person(id) {
	var patient_id = parseInt($('#patient_id').val());
	$.post(base_url + 'patient_management/edit_contact_person_form',{id:id,patient_id:patient_id},function(o) {
		$('#edit_contact_person_form_wrapper').html(o);
		$('#edit_contact_person_form_wrapper').modal();
		
		$('#edit_contact_person_form_wrapper').on('hidden', function () {
		  $("#edit_contact_information_form").validationEngine('hide');
		});
	});
}

function delete_contact_person_data(id) {
	var id = parseInt(id);
	var patient_id = parseInt($('#patient_id').val());
	$.post(base_url + 'patient_management/delete_contact_person_form',{id:id,patient_id:patient_id},function(o) {
		$('#delete_contact_person_form_wrapper').html(o);
		$('#delete_contact_person_form_wrapper').modal();
	});
}

// END OF CONTACT PERSON

function view_patient(id){
	if(id == "" || id == NaN){
		id = parseInt($("#id").val());
	} else {
		id = parseInt(id);
	}
	reset_all_topbars();
	reset_sub_hilight();
	$('.patient-view-topbar').removeClass("hidden");
	//$(".patient_info_form").addClass("sub-hilited");
	$(".patient_dashboard").addClass("sub-hilited");
	$("#main_wrapper_management").html(default_ajax_loader);
	/*$.post(base_url + "patient_management/viewPatientProfile", {id:id}, function(e){
		$("#main_wrapper_management").html(e);
	});*/
	$.post(base_url + "patient_management/viewDashboard", {id:id}, function(e){
		$("#main_wrapper_management").html(e);
	});
}

function view_patient2(){
	var id = parseInt($("#id").val());
	reset_all_topbars();
	reset_sub_hilight();
	$('.patient-view-topbar').removeClass("hidden");
	$(".patient_info_form").addClass("sub-hilited");
	$("#main_wrapper_management").html(default_ajax_loader);
	$.post(base_url + "patient_management/viewPatientProfile", {id:id}, function(e){
		$("#main_wrapper_management").html(e);
	});
}

function view_medical_history(){
	var id = parseInt($("#id").val());
	reset_all_topbars();
	$('.patient-view-topbar').removeClass("hidden");
	reset_sub_hilight();
	$(".medical_history_form").addClass("sub-hilited");
	$("#main_wrapper_management").html(default_ajax_loader);
	$.post(base_url + "patient_management/viewMedicalHistoryProfile", {id:id}, function(e){
		$("#main_wrapper_management").html(e);
	});
}

function view_personal_medical_history(){
	var id = parseInt($("#id").val());
	reset_all_topbars();
	$('.patient-view-topbar').removeClass("hidden");
	reset_sub_hilight();
	$(".medical_history_form").addClass("sub-hilited");
	$("#main_wrapper_management").html(default_ajax_loader);
	$.post(base_url + "patient_management/viewPersonalMedicalHistoryProfile", {id:id}, function(e){
		$("#main_wrapper_management").html(e);
	});
}

function edit_patient(id){
	var id = parseInt(id);
	reset_all_topbars();
	$('.user-add-patient').removeClass('hidden');
	$("#main_wrapper_management").html(default_ajax_loader);
	$.post(base_url + "patient_management/edit_patient", {id:id}, function(e){
		$("#main_wrapper_management").html(e);
	});
}

function delete_patient(id){
	var id = parseInt(id);

	$.post(base_url + "patient_management/delete_patient_form", {id:id}, function(e){
		$("#delete_patient").html(e);
		$("#delete_patient").modal();
	});
}

function reset_sub_hilight(){
	$(".patient_info_form").removeClass("sub-hilited");
	$(".medical_history_form").removeClass("sub-hilited");
	$(".regimen_history_form").removeClass("sub-hilited");
	$(".account_billing_form").removeClass("sub-hilited");
	$(".returns_history_form").removeClass("sub-hilited");
	$(".patient_files_form").removeClass("sub-hilited");
	$(".patient_dashboard").removeClass("sub-hilited");
	$(".patient_test_form").removeClass("sub-hilited");
}

function loadMedicalHistory(){
	var id = parseInt($("#id").val());

	reset_sub_hilight();
	reset_all_topbars();
	$('.user-add-patient').removeClass("hidden");
	$(".medical_history_form").addClass("sub-hilited");
	$("#main_wrapper_management").html(default_ajax_loader);
	$.post(base_url + "patient_management/medicalHistory", {id:id}, function(e){
		$("#main_wrapper_management").html(e);
	});
}

function loadMedical(patient_id){
	var id = parseInt(patient_id);

	reset_sub_hilight();
	reset_all_topbars();
	$('.user-add-patient').removeClass("hidden");
	$(".medical_history_form").addClass("sub-hilited");
	$("#main_wrapper_management").html(default_ajax_loader);
	$.post(base_url + "patient_management/medicalHistory", {id:id}, function(e){
		$("#main_wrapper_management").html(e);
	});
}

function loadPersonalMedicalHistory(){
	var id = parseInt($("#id").val());
	reset_sub_hilight();
	reset_all_topbars();
	$('.user-add-patient').removeClass("hidden");
	$(".medical_history_form").addClass("sub-hilited");
	$("#main_wrapper_management").html(default_ajax_loader);
	$.post(base_url + "patient_management/personalMedicalHistory", {id:id}, function(e){
		$("#main_wrapper_management").html(e);
	});	
}

function loadPatientInformation(){
	var id = parseInt($("#id").val());
	reset_sub_hilight();
	reset_all_topbars();
	$('.user-add-patient').removeClass("hidden");
	$(".patient_info_form").addClass("sub-hilited");
	edit_patient(id);
}

/* Family Medical History */

function resetAllMainClass(){
	$(".main-hilited").removeClass("main-hilited");
}

function addMainClass(data,id){
	loadSubWrapper(data,id);
}

function loadSubClass(data){
	// saveDisease();
	var e = $("#" + data).is(':checked');
	resetAllMainClass();
	if(e == true){
		$("." + data).addClass("main-hilited");
	} else {
		$("." + data).removeClass("main-hilited");
		$(".gray-area").html("");
	}	
}

function loadSubWrapper(data,disease_id){
	var a = $("#" + data).is(':checked');
	var disease_id = parseInt(disease_id);
	var id = parseInt($("#id").val());
	loadSubClass(data);
	if (a == true){
		// $('.gray-area').html('<div style="padding: 204px 57px 0px 80px; text-align: center; font-size: 30px;">'+ default_ajax_loader+'</div>');
		$('.gray-area').html('<div style="padding: 204px 57px 0px 80px; text-align: center; font-size: 30px;">'+ topright_ajax_loader+'</div>');
		$.post(base_url + "patient_management/loadDiseaseForm",{id:id, disease_id:disease_id},function(e){
			$(".gray-area").html(e);
		});
	} else {
		$('.gray-area').html('<div style="padding: 204px 57px 0px 80px; text-align: center; font-size: 30px;">No Medical History Found.<br/>Please Select from the List of Conditions on the Left.</div>');
	}

}

function executeOnLoad(minNumber,id){
	var minNumber = parseInt(minNumber);
	var id = parseInt(id);
	addMainClass('disease_'+minNumber, id);
}
/* PERSONAL Medical History */

function loadPersonalSub(data,disease_id){
	var a = $("#" + data).is(':checked');
	var disease_id = parseInt(disease_id);
	var id = parseInt($("#id").val());
	loadPersonalSubClass(data);
	if (a == true){
		$('.personal_mh_gray_area').html(default_ajax_loader);
		$.post(base_url + "patient_management/loadPersonalDiseaseForm",{id:id, disease_id:disease_id},function(e){
			$(".personal_mh_gray_area").html(e);
		});
	} else {
		$('.personal_mh_gray_area').html('<div style="padding: 204px 57px 0px 80px; text-align: center; font-size: 30px;">No Personal Medical History Found.<br/>Please Select from the List of Conditions on the Left.</div>');
	}

}


function addPersonalMainClass(data,id){
	loadPersonalSub(data,id);
}

function loadPersonalSubClass(data){
	// saveDisease();
	var e = $("#" + data).is(':checked');
	resetAllMainClass();
	if(e == true){
		$("." + data).addClass("main-hilited");
	} else {
		$("." + data).removeClass("main-hilited");
		$(".personal_mh_gray_area").html("");
	}	
}
/* DELETE UL FOR EDIT IN MEDICAL HISTORY */

function delete_field_edit(element) { // OTHER
	var ask = confirm("Delete? this will be deleted permanently.");
	if (ask == true){
		var medical_history_id = $("#medical_history_id_" + element).val();
		$.post(base_url + "patient_management/delete_disease", {medical_history_id:medical_history_id}, function(e){});
		$('.field_edit_'+element).remove();
		$("#medical_history_id_" + element).remove();
	}
}

function delete_personal_edit(element) { // OTHER
	var ask = confirm("Delete? this will be deleted permanently.");
	if (ask == true){
		var medical_history_id = $("#medical_history_id_" + element).val();
		$.post(base_url + "patient_management/delete_personal_disease", {medical_history_id:medical_history_id}, function(e){});
		$('.field_edit_'+element).remove();
		$("#medical_history_id_" + element).remove();
	}
}

/* END DELETE*/

function remove_main_hilight(){
	$("#personal_medical_history").removeClass("not-hilited");
	$("#family_medical_history").removeClass("not-hilited");
}

$("#family_medical_history").live('click', function(){
	loadMedicalHistory();
});

$("#personal_medical_history").live('click', function() {
	loadPersonalMedicalHistory();
});


var diseaseVariable = 0;

function addDiseaseVariable(id) {
	var index = diseaseVariable++;
	$('#loading_wrapper').html('<div style="padding: 15px 57px 0px 80px; text-align: center; font-size: 30px;">' + topright_ajax_loader + '</div>');
	$.post(base_url + 'patient_management/getDiseaseVariablesFields',{index:index,id:id},function(o) {
	$('#loading_wrapper').html("");
	$('#add_disease_wrapper').after(o.html);
	},'json');
}

function delete_disease_element(element){
	$('.'+element).remove();
}


var diseasePersonalVariable = 0;

function addDiseasePersonalVariable(id) {
	var index = diseasePersonalVariable++;
	$('#loading_wrapper').html('<div style="padding: 15px 57px 0px 80px; text-align: center; font-size: 30px;">' + topright_ajax_loader + '</div>');
	$.post(base_url + 'patient_management/getPersonalDiseaseVariablesFields',{index:index,id:id},function(o) {
	$('#loading_wrapper').html("");
	$('#add_disease_wrapper').after(o.html);
	},'json');
}

function delete_disease_element(element){
	$('.'+element).remove();
}

function upload_party_image() {
	document.getElementById('patient_image').addEventListener('change', function(e) {
		$('#display_image_wrapper').show();
    	$('#display_image_wrapper').html("Uploading "+default_ajax_loader);
    	$('#patient_image').hide();

		var file = this.files[0];
	    var xhr = new XMLHttpRequest(); 
	    xhr.file = file; // not necessary if you create scopes like this
	    xhr.addEventListener('progress', function(e) {

	        var done = e.position || e.loaded, total = e.totalSize || e.total;
	        //console.log('xhr progress: ' + (Math.floor(done/total*1000)/10) + '%');

	    }, false);
	    if ( xhr.upload ) {
	        xhr.upload.onprogress = function(e) {
	            var done = e.position || e.loaded, total = e.totalSize || e.total;
	            //console.log('xhr.upload progress: ' + done + ' / ' + total + ' = ' + (Math.floor(done/total*1000)/10) + '%');

	        };
	    }
	    xhr.onreadystatechange = function(e) {
	        if ( 4 == this.readyState ) {
	            //console.log(['xhr upload complete', e]);
	            $('#display_image_wrapper').hide();
	            // $('#patient_image').val("");
    			$('#patient_image').show();
    			$("#patient_display_picture").attr("src", xhr.responseText);
	        }
	    };

	    var fd = new FormData;
	    fd.append('photo', file);

	    xhr.open('post', base_url+'patient_management/upload_party_image', true);
	    xhr.send(fd);
	}, false);
}

function uploaded_file_list(viewing_page) {
	$('#uploaded_file_list_dt').html(default_ajax_loader);
	var patient_id = $("#id").val();
	var header_category = $("#header_category").val();
	$.post(base_url + 'patient_management/uploaded_file_list',{patient_id:patient_id,header_category:header_category,viewing_page:viewing_page},function(o) {
		$('#uploaded_file_list_dt').html(o);
	});
}
function upload_photo_form() {
	var header_category = $("#header_category").val();
	var patient_id = $("#id").val();

	$.post(base_url + 'patient_management/upload_photo_form',{header_category:header_category,patient_id:patient_id},function(o) {
		$('#upload_photo_form_wrapper').html(o);
		$('#upload_photo_form_wrapper').modal();
		
		$('#upload_photo_form_wrapper').on('hidden', function () {
		  uploaded_file_list("false");
		});
	});
}

function delete_photo(id) {
	if($('.tipsy-inner')) {
		$('.tipsy-inner').remove();
	}

	var ask = confirm("Are you sure you want to delete this image? This will be permanently deleted.");
	if (ask == true) {
		$.post(base_url + "patient_management/delete_photo",{id:id},function(o) {
			uploaded_file_list();
		});
	}
	
}

function view_regimen(){
	var id = $("#id").val();
	reset_all_topbars();
	$('.patient-view-topbar').removeClass("hidden");
	reset_sub_hilight();
	$(".regimen_history_form").addClass("sub-hilited");
	$('#main_wrapper_management').html(default_ajax_loader);
	/*$.post(base_url + "patient_management/view_regimen_list", {id:id}, function(o){
		$('#main_wrapper_management').html(o);
	});*/
	$.post(base_url + "patient_management/view_regimen_record", {id:id}, function(o){
		$('#main_wrapper_management').html(o);
	});
}

function view_regimen_record(regimen_id,version_id){
	var regimen_id = parseInt(regimen_id);
	var version_id = parseInt(version_id);
	$('.patient-view-topbar').removeClass("hidden");
	reset_sub_hilight();
	$(".regimen_history_form").addClass("sub-hilited");
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "patient_management/view_regimen_record", {id:regimen_id,version_id:version_id}, function(o){
		$('#main_wrapper_management').html(o);
	});
}

function view_version(version_id){
	var version_id = parseInt(version_id);
	$('.patient-view-topbar').removeClass("hidden");
	reset_sub_hilight();
	$(".regimen_history_form").addClass("sub-hilited");
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "patient_management/view_regimen_version", {version_id:version_id}, function(o){
		$('#main_wrapper_management').html(o);
	});
}

function view_invoice_list(){
	var id = $("#id").val();
	reset_all_topbars();
	$('.patient-view-topbar').removeClass("hidden");
	reset_sub_hilight();
	$(".account_billing_form").addClass("sub-hilited");
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "patient_management/view_invoice_list", {id:id}, function(o){
		$('#main_wrapper_management').html(o);
	});
}

function view_invoice_record(invoice_id){
	reset_all_topbars();
	$('.patient-view-topbar').removeClass("hidden");
	reset_sub_hilight();
	$(".account_billing_form").addClass("sub-hilited");
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "patient_management/view_invoice_record", {invoice_id:invoice_id}, function(o){
		$('#main_wrapper_management').html(o);
	});
}

function viewCollection(or_id){
	reset_all_topbars();
	$('.patient-view-topbar').removeClass("hidden");
	reset_sub_hilight();
	$(".account_billing_form").addClass("sub-hilited");
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "patient_management/view_collection_record", {or_id:or_id}, function(o){
		$('#main_wrapper_management').html(o);
	});
}

function view_return_list(){
	var id = $("#id").val();
	reset_all_topbars();
	$('.patient-view-topbar').removeClass("hidden");
	reset_sub_hilight();
	$(".returns_history_form").addClass("sub-hilited");
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "patient_management/view_returns_list", {id:id}, function(o){
		$('#main_wrapper_management').html(o);
	});
}

function view_upload_list(){
	var id = $("#id").val();
	reset_all_topbars();
	$('.patient-view-topbar').removeClass("hidden");
	reset_sub_hilight();
	$(".patient_files_form").addClass("sub-hilited");
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "patient_management/view_files_list", {id:id}, function(o){
		$('#main_wrapper_management').html(o);
	});
}

function view_returns_record(return_id){
	reset_all_topbars();
	$('.patient-view-topbar').removeClass("hidden");
	reset_sub_hilight();
	$(".returns_history_form").addClass("sub-hilited");
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "patient_management/view_returns_record", {return_id:return_id}, function(o){
		$('#main_wrapper_management').html(o);
	});
}

function hasValue(variable) {
	return ($.trim(variable).length > 0 ? true : false);
}

function viewReturns(returns_id){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "returns_management/loadReturnsView",{returns_id:returns_id},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function loadPreviousForm(){
	var content = window.location.hash;
	content = content.replace(/#/g,'');
	reload_content(content);
}

function upload_file_form_modal() {
	var patient_id = $("#id").val();
	$.post(base_url + 'patient_management/upload_form',{patient_id:patient_id},function(o) {
		$('#file_upload_modal_form').html(o);
		$('#file_upload_modal_form').modal();
	});
}

function upload_file_form_edit_modal() {
	$('#file_upload_modal_form').html(default_ajax_loader);
	$.post(base_url + 'patient_management/loadPageForEditFiles',{},function(o) {
		$('#file_upload_modal_form').html(o);
	});
}

function view_uploaded_file(id){
	$.post(base_url + 'patient_management/loadPageForViewFiles',{id:id},function(o) {
		$('#file_upload_modal_form').html(o);
		$('#file_upload_modal_form').modal();
	});
	/*var bam = new jBox('Modal', {
		// title: "File",
		ajax: {
			url: base_url + 'patient_management/loadPageForViewFiles/'+id,
			data: 'id='+id,
			reload: true
		}
	});

	bam.open();*/
}

function edit_uploaded_file(id) {
	$('#file_upload_modal_form').html(default_ajax_loader);
	$.post(base_url + 'patient_management/loadPageForEditFile',{id:id},function(o) {
		$('#file_upload_modal_form').html(o);
	});
}

function openViewNotes(patient_id){
	$.post(base_url + "patient_management/viewNotes", {patient_id:patient_id}, function(o){
		$('#add_notes_form_wrapper').html(o);
		$('#add_notes_form_wrapper').modal();
		});
}

function viewNotes(patient_id){
	$.post(base_url + "patient_management/loadNotes", {patient_id:patient_id}, function(o){
			$("#view_notes_form_wrapper").html(o);
		});
}

function edit_notes(id){
	$.post(base_url + "patient_management/editNotes", {id:id}, function(o){
		$("#edit_notes_form_wrapper").html(o);
		$('#edit_notes_form_wrapper').modal();
	});
}


//LABORATORY TEST 

function view_dashboard(){
	var id = parseInt($("#id").val());
	reset_all_topbars();
	$('.patient-view-topbar').removeClass("hidden");
	reset_sub_hilight();
	$(".patient_dashboard").addClass("sub-hilited");
	$("#main_wrapper_management").html(default_ajax_loader);
	$.post(base_url + "patient_management/viewDashboard", {id:id}, function(e){
		$("#main_wrapper_management").html(e);
	});
}

function add_labtest(id){
	var id = parseInt(id);
	reset_all_topbars();
	$('.patient-view-topbar').removeClass("hidden");
	reset_sub_hilight();
	$(".patient_test_form").addClass("sub-hilited");
	$("#main_wrapper_management").html(default_ajax_loader);
	$.post(base_url + "patient_management/addLabtest", {id:id}, function(e){
		$("#main_wrapper_management").html(e);
	});
}

function compare_labtest(id){
	var id = parseInt(id);
	reset_all_topbars();
	$('.patient-view-topbar').removeClass("hidden");
	reset_sub_hilight();
	$(".patient_test_form").addClass("sub-hilited");
	$("#main_wrapper_management").html(default_ajax_loader);
	$.post(base_url + "patient_management/compareLabtest", {id:id}, function(e){
		$("#main_wrapper_management").html(e);
	});
}

function view_all_test(){
	var id = $("#id").val();
	reset_all_topbars();
	$('.patient-view-topbar').removeClass("hidden");
	reset_sub_hilight();
	$(".patient_test_form").addClass("sub-hilited");
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "patient_management/viewAllLabTest", {id:id}, function(o){
		$('#main_wrapper_management').html(o);
	});
}

function edit_labtest(id, patient_id){
	var id = parseInt(id);
	var patient_id = parseInt(patient_id);
	reset_all_topbars();
	$('.patient-view-topbar').removeClass("hidden");
	reset_sub_hilight();
	$(".patient_test_form").addClass("sub-hilited");
	$("#main_wrapper_management").html(default_ajax_loader);
	$.post(base_url + "patient_management/editLabtest", {id:id, patient_id:patient_id}, function(e){
		$("#main_wrapper_management").html(e);
	});
}

function delete_labtest(id, patient_id) {
	var id = parseInt(id);
	var patient_id = parseInt(patient_id);

	var ask = confirm("Are you sure you want to delete this Laboratory test? This will be permanently deleted.");
	if (ask == true) {
		$.post(base_url + "patient_management/deleteLabtest",{id:id,patient_id:patient_id},function(o) {
			view_all_test();
		});
	}
}

function view_labtest(id, patient_id){
	var id = parseInt(id);
	var patient_id = parseInt(patient_id);
	reset_all_topbars();
	$('.patient-view-topbar').removeClass("hidden");
	reset_sub_hilight();
	$(".patient_test_form").addClass("sub-hilited");
	$("#main_wrapper_management").html(default_ajax_loader);
	$.post(base_url + "patient_management/viewLabtest", {id:id, patient_id:patient_id}, function(e){
		$("#main_wrapper_management").html(e);
	});
}

function view_imagingtest(id, patient_id){
	var id = parseInt(id);
	var patient_id = parseInt(patient_id);
	reset_all_topbars();
	$('.patient-view-topbar').removeClass("hidden");
	reset_sub_hilight();
	$(".patient_test_form").addClass("sub-hilited");
	$("#main_wrapper_management").html(default_ajax_loader);
	$.post(base_url + "patient_management/viewImagingtest", {id:id, patient_id:patient_id}, function(e){
		$("#main_wrapper_management").html(e);
	});
}

