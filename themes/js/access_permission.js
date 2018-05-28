var IS_ADD_ACCESS_PERMISSION_FORM_CHANGE = false;
var IS_ADD_USER_ROLES_CHANGE = false;

function reload_content(fragment) {
	var hash = window.location.hash;

	if(
		IS_ADD_ACCESS_PERMISSION_FORM_CHANGE ||
		IS_ADD_USER_ROLES_CHANGE
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
	reset_all();
	reset_all_topbars();
	$('.user-level-topbar').removeClass('hidden');

	$('#main_wrapper_management').html("");

	if(hash == "#roles" || hash == "") {
		$('.firm_admin_roles').addClass('hilited');
		loadListRoleTypes();
	}
}

$(".firm_admin_roles").live('click', function() {
	window.location.hash = "roles";
	reload_content("roles");
});

$(".order_menu").live('click', function() {
	window.location.href = "order";
});

$(".calendar_menu").live('click', function() {
	window.location.href = "calendar";
});

$(".patient_menu").live('click', function() {
	window.location.href = "patient";
});

$(".module_management_menu").live('click', function() {
	window.location.href = "module";
});

$(".firm_admin_users").live('click', function() {
	window.location.href = "users";
});

$(".inventory_management_menu").live('click', function() {
	window.location.href = "inventory";
});

$(".firm_admin_permissions").live('click', function() {
	window.location.href = "users#permissions";
});

$(".firm_admin_add_role").live('click', function() {
	loadUserRoleForm();
});

$(".regimen_menu").live('click', function() {
	window.location.href = "regimen";
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


function loadPermissions(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "access_permissions/loadAccessPermission",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function loadListRoleTypes(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "admin/user_role_list",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function edit_user_role(id){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "access_permissions/loadEditFormAccessPermission",{id:id},function(o){
		$('#main_wrapper_management').html(o);
	})
;}

function view_roles(id) {
	$.post(base_url + 'access_permissions/view_user_role_form',{id:id},function(o) {
		$('#add_user_access_form').html(o);
		$('#add_user_access_form').modal();
	});
}

function delete_user_roles(id){
	var id = parseInt(id);
	$.post(base_url + 'access_permissions/delete_user_role_form',{id:id},function(o) {
		$('#delete_user_form_wrapper').html(o);
		$('#delete_user_form_wrapper').modal();
	});
}

function loadUserRoleForm() {
	$.post(base_url + 'access_permissions/loadAddFormUserRole',{},function(o) {
		$('#add_user_access_form').html(o);
		$('#add_user_access_form').modal();
	});
}