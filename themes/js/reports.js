var IS_REPORT_GENERATOR_CHANGE = false;

function reload_content(fragment) {
	var hash = window.location.hash;

	if(
		IS_REPORT_GENERATOR_CHANGE
	) {
		var con = confirm("Data is not yet saved. Do you want to continue?");

		if(con) {
			load_page(hash);
		}

	} else {
		load_page(hash);
	}
}

function reset_changes(){
	IS_REPORT_GENERATOR_CHANGE = false;
}

function load_page(hash) {
	reset_all();
	reset_all_topbars();
	reset_changes();
	$('.module_management_topbar').removeClass('hidden');

	$('#main_wrapper_management').html("");
	$('.reports_menu').addClass('hilited');
	if(hash == "#sales" || hash == "") {	
		loadIndex();
	}else if(hash == "#invoice"){
		loadConvertInvoice();
	}else if(hash == "#invoice-Alist"){
		loadConvertInvoiceAlist();
	}else if(hash == "#receivables"){
		loadReceivables();
	}else if(hash == "#collections"){
		loadCollections();
	}
}



$(".patient_menu").live('click', function() {
	window.location.href = "patient";
});

$(".order_menu").live('click', function() {
	window.location.href = "order";
});

$(".module_management_menu").live('click', function() {
	window.location.href = "module";
});

$(".calendar_menu").live('click', function() {
	window.location.href = "calendar";
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

$(".activity_log_menu").live('click', function() {
	window.location.href = "activity_log";
});

$(".calendar_menu").live('click', function() {
	window.location.href = "calendar";
});

function loadIndex(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "reports/getIndex",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

/*
	FUNCTION FOR REPORT GENERATOR.PHP
*/
function showMainForm(module_name){
	hideAllModule();
	hideAllSubForms();

	switch(module_name){
		case 'calendar':
			$("#calendar_form").show();
			var calendar_mode = $(".mode_calendar:checked").val();
			showSubForm(calendar_mode);
			break;
		case 'patients':
			$("#patient_form").show();
			var patient_mode = $(".mode_patient:checked").val();
			showSubForm(patient_mode);
			break;
		case 'regimen':
			$("#regimen_form").show();
			var regimen_mode = $(".mode_regimen:checked").val();
			showSubForm(regimen_mode);
			break;
		case 'inventory':
			$("#inventory_form").show();
			var inventory_mode = $(".mode_inventory:checked").val();
			showSubForm(inventory_mode);
			break;
		case 'billing':
			$("#billing_form").show();
			var biling_mode = $(".mode_billing:checked").val();
			showSubForm(biling_mode);
			break;
		case 'returns':
			$("#returns_form").show();
			$("#all_return_form").show();
			break;
	}
}

function showSubForm(mode){
	hideAllSubForms();
	switch(mode){
		case 'birthdays':
			$("#birthdays_form").show();
			break;
		case 'event_daterange':
			$("#event_daterange_form").show();
			break;
		case 'per_patient':
			$("#per_patient_form").show();
			break;
		case 'all_patient':
			$("#all_patients_form").show();
			break;
		case 'all_patient_without_daterange':
			$("#all_patients_without_daterange_form").show();
			break;
		case 'all_active_patient':
			$("#all_active_patients_form").show();
			break;
		case 'all_inactive_patient':
			$("#all_inactive_patients_form").show();
			break;
		case 'per_regimen':
			$("#per_regimen_form").show();
			break;
		case 'all_regimen':
			$("#all_regimen_form").show();
			break;
		case 'per_inventory':
			$("#per_inventory_form").show();
			break;
		case 'all_inventory':
			$("#all_inventory_form").show();
			break;
		case 'per_batch':
			$("#per_batch_form").show();
			break;
		case 'all_batch':
			$("#all_batch_form").show();
			break;
		case 'claim_sold_item':
			$("#claim_sold_item_form").show();
			break;
		case 'per_collection':
			$("#per_collection_form").show();
			break;
		case 'all_collection':
			$("#all_collection_form").show();
			break;
		case 'per_sales':
			$("#per_sales_form").show();
			break;
		case 'all_sales':
			$("#all_sales_form").show();
			break;
		case 'event_per_patient':
			$("#event_per_patient_form").show();
			break;
	}
}

function hideAllModule(){
	$(".main_form").hide();
}

function hideAllSubForms(){
	$(".sub_form").hide();
}

function hasValue(variable) {
	return ($.trim(variable).length > 0 ? true : false);
}