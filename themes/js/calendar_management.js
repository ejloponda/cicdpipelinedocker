var IS_CALENDAR_CHANGE = false;

function reload_content(fragment) {
	var hash = window.location.hash;

		load_page(hash);
}

function reset_changes(){
	IS_CALENDAR_CHANGE = false;
}

function load_page(hash) {
	reset_all();
	reset_all_topbars();
	reset_changes();

	

	$('.module_management_topbar').removeClass('hidden');
	$('.calendar_event_menu').removeClass('hidden');

	$('#main_wrapper_management').html("");
	$('.calendar_menu').addClass('hilited');
	if(hash == "#calendar" || hash == "") {	
	loadIndex();
	}
}

$(".calendar_view").live('click', function() {
	window.location.hash = "calendar";
	reload_content("calendar");
});

$(".accounts_receivable_list").live('click', function() {
	window.location.hash = "receivables";
	reload_content("receivables");
});

$(".patient_menu").live('click', function() {
	window.location.href = "patient";
});

$(".order_menu").live('click', function() {
	window.location.href = "order";
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

$(".calendar_menu").live('click', function() {
	window.location.href = "calendar";
});
function loadIndex(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "calendar_management/getIndex",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function loadCalendar(){
  $.post(base_url + "calendar_management/loadCalendar",{},function(o){
    	$('#calendar_wrapper').html(o);
  
  });
}
