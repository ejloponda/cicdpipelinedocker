var IS_ADD_INVENTORY_FORM_CHANGE 		= false;

function resetStatus(){
	IS_ADD_INVENTORY_FORM_CHANGE 		= false;
}


function reload_content(fragment) {
	var hash = window.location.hash;

	if(
		IS_ADD_INVENTORY_FORM_CHANGE
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
	
	resetStatus();
	reset_all();
	reset_all_topbars();
	reset_all_topbars_menu();

	$('#main_wrapper_management').html("");
	$('.module_management_topbar').removeClass('hidden');
	$('.menu_returns_inv').removeClass('hidden');
	$('.inventory_menu_topbar').addClass('hidden');
	$('.inventory_management_menu').addClass('hilited');


	if(hash == "#lists" || hash == "") {
		getInventoryList();
	}else if(hash == "#newInventory"){
		loadInventoryForm();
	}else if(hash == "#returns"){
		loadAllReturnsHistory();
	}else if(hash == "#newReturns"){
		loadReturnsForm();
	}
}

$(".patient_menu").live('click', function() {
	window.location.href="patient";
});

$(".order_menu").live('click', function() {
	window.location.href="order";
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

$(".inventory_management_menu").live('click', function() {
	window.location.hash = "lists";
	reload_content("lists");
});

$(".cancel_inventory_form").live('click', function() {
	window.location.hash = "lists";
	reload_content("lists");
});

$(".add_new_inventory_form").live('click', function() {
	window.location.hash = "newInventory";
	reload_content("newInventory");
});

$(".add_new_inventory").live('click', function() {
	window.location.hash = "newInventory";
	reload_content("newInventory");
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


function getInventoryList(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "inventory_management/getInventoryList",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function loadInventoryForm(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "inventory_management/loadInventoryForm",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function loadInventoryListTable(stock){
	$('#inventory_list_wrapper').html(default_ajax_loader);
	$.post(base_url + "inventory_management/loadInventoryListTable",{stock:stock},function(o){
		$('#inventory_list_wrapper').html(o);
	});
}

// EDIT && DELETE

$(".inventory_list_form").live('click', function() {
	var item_id = parseInt($("#item_id").val());
	edit_item_inv(item_id);
});


function edit_item_inv(item_id){
	var item_id = parseInt(item_id);
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "inventory_management/checkToEditItemInv",{item_id:item_id},function(o){
		$('.inventory_menu_topbar').removeClass('hidden');
		$('#main_wrapper_management').html(o);
	});
}


function view_item(item_id){
	var item_id = parseInt(item_id);
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "inventory_management/loadMedicineForm",{item_id:item_id},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function delete_item_inv(item_id){
	var item_id = parseInt(item_id);
	
	$.post(base_url + "inventory_management/checkToDeleteItemInv",{item_id:item_id},function(o){
		$('#delete_inventory_item').html(o);
		$('#delete_inventory_item').modal('show');
	});
}

// END EDIT && DELETE


/* STOCK ADJUSTMENT */


$(".stock_adjustment_form").live('click', function() {
	loadStockAdjustment();
});

function loadStockAdjustment(){
	var item_id = parseInt($("#item_id").val());
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "inventory_management/loadStockAdjustmentForm",{item_id:item_id},function(o){
		$('#main_wrapper_management').html(o);
	});
}

$(".returns_form").live('click', function() {
	loadReturnsHistoryPage();
});

function loadReturnsHistoryPage(){
	var item_id = parseInt($("#item_id").val());
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "inventory_management/loadReturnsHistoryPage",{item_id:item_id},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function batch_list(medicine_id){
	$("#batch_list_wrapper").html(default_ajax_loader);
	$.post(base_url + "inventory_management/batch_list", {medicine_id:medicine_id}, function(o){
		$('#batch_list_wrapper').html(o);
	})
}

function getBatchDetails(batch_id){
	var batch_id = parseInt(batch_id);
	$.post(base_url + "inventory_management/getBatchDetails", {batch_id:batch_id}, function(o){
		$("#quantity").val(o.output['quantity']);
		$("#quantity_type").val(o.output['quantity_type']);
	}, "json");
}