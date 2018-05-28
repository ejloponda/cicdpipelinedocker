var ADD_USER_EMAIL_ADDRESS_VALID 	= true;

var IS_ADD_USER_FORM_CHANGE 	= false;
var IS_EDIT_USER_FORM_CHANGE 	= false;
var IS_ADD_USER_ROLES_CHANGE 	= false;
var IS_EDIT_USER_ROLES_CHANGE 	= false;

function reset_all_topbars(){
	$('.user-topbar').removeClass('hidden');
	$('.user-level-topbar').removeClass('hidden');
	$('.user-add-patient').removeClass('hidden');
	$('.user-topbar').addClass('hidden');
	$('.user-level-topbar').addClass('hidden');
	$('.user-add-patient').addClass('hidden');
}

function reload_content(fragment) {
	var hash = window.location.hash;

	if(
		IS_ADD_USER_FORM_CHANGE ||
		IS_EDIT_USER_FORM_CHANGE ||
		IS_ADD_USER_ROLES_CHANGE ||
		IS_EDIT_USER_ROLES_CHANGE
		
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
	IS_ADD_USER_FORM_CHANGE 	= false;
	IS_EDIT_USER_FORM_CHANGE 	= false;
	IS_ADD_USER_ROLES_CHANGE 	= false;
	IS_EDIT_USER_ROLES_CHANGE	= false;

	reset_all();
	reset_all_topbars();
	$('.user-topbar').removeClass('hidden');

	$('#main_content_wrapper').html("");

	if(hash == "#users" || hash == "") {
		$('.firm_admin_users').addClass('hilited');
		$('.user-topbar').removeClass('hidden');
		getUserList();
	}else if(hash == "#add-user"){
		$('.firm_admin_users').addClass('hilited');
		add_user();
	}else if(hash == "#roles"){
		$('.firm_admin_roles').addClass('hilited');
		getUserRoleList();
	}else if(hash == "#add-role"){
		$('.firm_admin_roles').addClass('hilited');
		add_role();
	}else if(hash == "#permissions"){
		$('.firm_admin_permissions').addClass('hilited');
		permissions();
	}else if(hash == "#module_scope"){
		$('.firm_admin_permissions').addClass('hilited');
		loadFormAddModuleScope();
	}
}


$(".firm_admin_users").live('click', function() {
	window.location.hash = "users";
	reload_content("users");
});

$(".order_menu").live('click', function() {
	window.location.href = "order";
});

$(".calendar_menu").live('click', function() {
	window.location.href = "calendar";
});

$(".firm_admin_add_user").live('click', function() {
	window.location.hash = "add-user";
	reload_content("add-user");
});

$(".firm_admin_roles").live('click',function(){
	window.location.href="permissions";
});

$(".regimen_menu").live('click', function() {
	window.location.href = "regimen";
});

$(".firm_admin_add_role").live('click', function() {
	window.location.hash = "add-role";
	reload_content("add-role");
});

$(".add_module_scope_button").live('click', function() {
	window.location.hash = "module_scope";
	reload_content("module_scope");
});


$(".firm_admin_permissions").live('click', function() {
	window.location.hash = "permissions";
	reload_content("permissions");
});

$(".patient_menu").live('click', function() {
	window.location.href="patient";
});

$(".module_management_menu").live('click', function() {
	window.location.href="module";
});

$(".inventory_management_menu").live('click', function() {
	window.location.href = "inventory";
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


function getUserList(){
	$('#main-wrapper').html(default_ajax_loader);
	$.post(base_url + "admin/user_list",{},function(o){
		$('#main-wrapper').html(o);
	});
}

function add_user(){
	$('#main-wrapper').html(default_ajax_loader);
	$.post(base_url + "admin/user_access",{},function(o){
		$('#main-wrapper').html(o);
	});
}

function add_role(){
	$('#main-wrapper').html(default_ajax_loader);
	$.post(base_url + "admin/add_role",{},function(o){
		$('#main-wrapper').html(o);
	});
}

function getUserRoleList(){
	$('#main-wrapper').html(default_ajax_loader);
	$.post(base_url + "admin/user_role_list",{},function(o){
		$('#main-wrapper').html(o);
	});
}

function permissions(){
	$('#main-wrapper').html(default_ajax_loader);
	$.post(base_url + "admin/permissions",{},function(o){
		$('#main-wrapper').html(o);
	});
}

function loadFormAddModuleScope() {
	$.post(base_url + 'admin/loadFormModuleScope',{},function(o) {
		$('#form_module_scope_modal').html(o);
		$('#form_module_scope_modal').modal();
	});
}


$("#email_address").live('blur', function() {
 	var email_address 	= $('#email_address').val();
 	var user_id 		= $('#user_id').val();

 	if(email_address && validateEmail(email_address)) {
 		$('#email_address_checker_wrapper').html("<small class='gray'>Checking</small> "+default_ajax_loader);
 		$.post(base_url + "admin/check_email_address_validity",{user_id:user_id, email_address:email_address},function(o) {
 			if(!o.is_successful) {
	 			ADD_USER_EMAIL_ADDRESS_VALID = o.is_successful;
	 			EDIT_USER_EMAIL_ADDRESS_VALID = o.is_successful;
				$('#email_address_checker_wrapper').html("<small>"+o.message+"</small>");
			} else {
				$('#email_address_checker_wrapper').html("");		
			}
		},'json');
 	} else {
 		$('#email_address_checker_wrapper').html("");
 	}

});

function getAllUserList() {

	$('#user_list_dt_wrapper').html(default_ajax_loader);
	$.post(base_url + "admin/current_case_list_dt",{},function(o) {
		$("#user_list_dt_wrapper").html(o);
	});
}

function edit_user_access(param_id) {
	var id = parseInt(param_id);
	$('#main-wrapper').html(default_ajax_loader);
	$.post(base_url + "admin/edit_user_access",{id:id},function(o) {
		$('#main-wrapper').html(o);
	});
}

function view_user_profile(param_id) {
	var id = parseInt(param_id);
	$('#main-wrapper').html(default_ajax_loader);
	$.post(base_url + "admin/viewUserProfile",{id:id},function(o) {
		$('#main-wrapper').html(o);
	});
}

function delete_user(id) {
	$.post(base_url + 'admin/delete_user_form',{id:id},function(o) {
		$('#delete_user_form_wrapper').html(o);
		$('#delete_user_form_wrapper').modal();
	});
}

function edit_user_role(param_id){
	var id = parseInt(param_id);
	$('#main-wrapper').html(default_ajax_loader);
	$.post(base_url + "admin/edit_user_role",{id:id},function(o) {
		$('#main-wrapper').html(o);
	});
}

function delete_user_roles(id){
	var id = parseInt(id);
	$.post(base_url + 'admin/delete_user_role_form',{id:id},function(o) {
		$('#delete_user_form_wrapper').html(o);
		$('#delete_user_form_wrapper').modal();
	});
}

function view_roles(id) {
	$.post(base_url + 'admin/view_user_role_form',{id:id},function(o) {
		$('#delete_user_form_wrapper').html(o);
		$('#delete_user_form_wrapper').modal();
	});
}

$("#add_contact_information_button").live('click', function() {
	add_contact_information();
});

var contact_info_counter = 0;

function add_contact_information() {
	var contact_type_id 		= "contact_type_" + contact_info_counter;
	var contact_type_select 	= '<select id="' + contact_type_id + '" name="contact_information['+contact_info_counter+'][contact_type]" class="select02 add_user_form"><option value="Mobile">Mobile</option><option value="Work">Work</option><option value="Home">Home</option><option value="Fax">Fax</option></select>';

	var contact_type_value_id 	= "contact_type_value_" + contact_info_counter;
	var contact_type_value_text = '<input type="text" id="' + contact_type_value_id + '" name="contact_information['+contact_info_counter+'][contact_type_value]" class="textbox">';

	var contact_type = $('#contact_information_type').val();
	if(contact_type == "Fax" || contact_type == "Work") {
		var extension_id 			= "contact_information_extension_" + contact_info_counter;
		var extension_text 			= ' <input type="text" id="' + extension_id + '" name="contact_information['+contact_info_counter+'][contact_extension]" class="textbox" placeholder="Area Code">';
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

function edit_module_scope(id) {
	$.post(base_url + 'admin/loadEditFormModuleScope',{id:id},function(o) {
		$('#form_module_scope_modal').html(o);
		$('#form_module_scope_modal').modal();
	});
}

function delete_module_scope(id) {
	$.post(base_url + 'admin/deleteModuleScope',{id:id},function(o) {
		$('#delete_user_form_wrapper').html(o);
		$('#delete_user_form_wrapper').modal();
	});
}