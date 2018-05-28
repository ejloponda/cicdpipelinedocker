var IS_ACTIVITY_LOG_CHANGE = false;

function reload_content(fragment) {
	var hash = window.location.hash;

		load_page(hash);
}

function reset_changes(){
	IS_ACTIVITY_LOG_CHANGE = false;
}

function load_page(hash) {
	reset_all();
	reset_all_topbars();
	reset_changes();
	$('.module_management_topbar').removeClass('hidden');

	$('#main_wrapper_management').html("");
	$('.activity_log_menu').addClass('hilited');
	if(hash == "#sales" || hash == "") {	
		loadIndex();
	}
}

$(".patient_menu").live('click', function() {
	window.location.href = "patient";
});

$(".order_menu").live('click', function() {
	window.location.href = "order";
});

$(".calendar_menu").live('click', function() {
	window.location.href = "calendar";
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

$(".firm_admin_roles").live('click', function() {
	window.location.href = "permissions";
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

function loadIndex(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "activity_log/getIndex",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}