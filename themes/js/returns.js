var IS_ADD_RETURNS = false;

function reload_content(fragment) {
	var hash = window.location.hash;

	if(
		IS_ADD_RETURNS
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
	IS_ADD_RETURNS = false;
}

function load_page(hash) {
	reset_all();
	reset_all_topbars();
	reset_changes();
	$('.module_management_topbar').removeClass('hidden');
	$('.menu_returns_inv').removeClass('hidden');
	$('.inventory_form').removeClass('sub-hilited');

	$('#main_wrapper_management').html("");
	$('.inventory_management_menu').addClass('hilited');
	if(hash == "#returns" || hash == "") {	
		loadIndex();
	}
}

$(".patient_menu").live('click', function() {
	window.location.href="patient";
});

$(".order_menu").live('click', function() {
	window.location.href = "order";
});

$(".calendar_menu").live('click', function() {
	window.location.href = "calendar";
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

$(".module_management_menu").live('click', function() {
	window.location.href = "module";
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

function loadIndex(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "returns_management/getIndex",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function viewReturns(returns_id){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "returns_management/loadReturnsView",{returns_id:returns_id},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function loadAddReturnsForm(invoice_id){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "returns_management/loadAddReturnsForm",{invoice_id:invoice_id},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function AcceptDeclineReturns(returns_id){
	$.post(base_url + "returns_management/loadAcceptanceForm", {returns_id:returns_id}, function(o){
		$('#void_form_wrapper').html(o);
		$('#void_form_wrapper').modal();
	});
}


function loadModalChoose(){
	$.post(base_url + "returns_management/LoadReturnsModalForm", {}, function(o){
		$('#void_form_wrapper').html(o);
		$('#void_form_wrapper').modal();
	});
}

function loadPreviousForm(){
	var content = window.location.hash;
	content = content.replace(/#/g,'');
	reload_content(content);
}