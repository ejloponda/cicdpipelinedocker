var IS_ADD_CATEGORY_FORM_CHANGE 		= false;
var IS_ADD_TYPE_FORM_CHANGE 			= false;
var IS_ADD_DOSAGE_TYPE_CHANGE 			= false;
var IS_ADD_QUANTITY_TYPE_CHANGE 		= false;
var IS_ADD_DOCTORS_FORM_CHANGE			= false;
var IS_ADD_REASONS_FORM_CHANGE			= false;
var IS_ADD_OTHER_CHARGE_FORM_CHANGE		= false;

function resetStatus(){
	IS_ADD_CATEGORY_FORM_CHANGE 		= false;
	IS_ADD_TYPE_FORM_CHANGE 			= false;
	IS_ADD_DOSAGE_TYPE_CHANGE 			= false;
	IS_ADD_QUANTITY_TYPE_CHANGE 		= false;
	IS_ADD_DOCTORS_FORM_CHANGE 			= false;
	IS_ADD_REASONS_FORM_CHANGE			= false;
	IS_ADD_OTHER_CHARGE_FORM_CHANGE		= false;
}


function reload_content(fragment) {
	var hash = window.location.hash;

	if(
		IS_ADD_CATEGORY_FORM_CHANGE ||
		IS_ADD_TYPE_FORM_CHANGE ||
		IS_ADD_DOSAGE_TYPE_CHANGE ||
		IS_ADD_QUANTITY_TYPE_CHANGE ||
		IS_ADD_DOCTORS_FORM_CHANGE ||
		IS_ADD_REASONS_FORM_CHANGE ||
		IS_ADD_OTHER_CHARGE_FORM_CHANGE
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

	$('#main_wrapper_management').html("");
	$('.module_management_topbar').removeClass('hidden');
	$('.module_menu_topbar').removeClass('hidden');
	$('.module_management_menu').addClass('hilited');
	if(hash == "#disease_category" || hash == "") {
		getDiseaseCategoryList();
	}else if(hash == "#disease_type"){
		getDiseaseTypeList();
	}else if(hash == "#addNewDiseaseCategory"){
		loadAddNewCategoryForm();
	}else if(hash == "#addNewDiseaseType"){
		loadAddNewTypeForm();
	}else if(hash == "#dosage_list"){
		getDosageList();
	}else if(hash == "#location_list"){
		getLocationList();
	}else if(hash == "#type_list"){
		getTypeList();
	}else if(hash == "#addNewLocationType"){
		loadAddNewLocationTypeForm();
	}else if(hash == "#addNewType"){
		loadAddNewCalendarTypeForm();
	}else if(hash == "#addNewDosageType"){
		loadAddNewDosageTypeForm();
	}else if(hash == "#quantity_list"){
		getQuantityList();
	}else if(hash == "#addNewQuantityType"){
		loadAddNewQuantityTypeForm();
	}else if(hash == "#doctors_list"){
		loadDoctorsForm();
	}else if(hash == "#addNewDoctor"){
		loadAddDoctorsForm();
	}else if(hash == "#reasons_list"){
		loadReasonsForm();
	}else if(hash == "#addNewReasons"){
		loadAddReasonsForm();
	}else if(hash == "#charges_list"){
		getOtherChargesList();
	}else if(hash == "#cost_modifier"){
		getCostModifierList();
	}else if(hash == "#addOtherCharges"){
		loadOtherChargesForm();
	}else if(hash == "#addCostModifier"){
		loadCostModifierForm();
	}else if(hash == "#files_category"){
		getFileCategoryList();
	}else if(hash == "#add_new_filecategory"){
		loadAddNewFileCategory();
	}
}

$(".patient_menu").live('click', function() {
	window.location.href="patient";
});

$(".order_menu").live('click', function() {
	window.location.href = "order";
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

$("#dosage_list_form").live('click', function() {
	window.location.hash = "dosage_list";
	reload_content("dosage_list");
});

$(".dosage_type_list").live('click', function() {
	window.location.hash = "dosage_list";
	reload_content("dosage_list");
});

$(".quantity_type_list").live('click', function() {
	window.location.hash = "quantity_list";
	reload_content("quantity_list");
});

$(".add_dosage_type").live('click', function() {
	window.location.hash = "addNewDosageType";
	reload_content("addNewDosageType");
});

$(".add_location_type").live('click', function() {
	window.location.hash = "addNewLocationType";
	reload_content("addNewLocationType");
});

$(".add_type").live('click', function() {
	window.location.hash = "addNewType";
	reload_content("addNewType");
});

$(".add_quantity_type").live('click', function() {
	window.location.hash = "addNewQuantityType";
	reload_content("addNewQuantityType");
});

$("#quantity_list_form").live('click', function() {
	window.location.hash = "quantity_list";
	reload_content("quantity_list");
});

$(".module_management_menu").live('click', function() {
	window.location.hash = "disease_category";
	reload_content("disease_category");
});

$(".add_disease_category").live('click', function() {
	window.location.hash = "addNewDiseaseCategory";
	reload_content("addNewDiseaseCategory");
});

$("#disease_type").live('click', function() {
	window.location.hash = "disease_type";
	reload_content("disease_type");
});

$(".add_disease_type").live('click', function() {
	window.location.hash = "addNewDiseaseType";
	reload_content("addNewDiseaseType");
});

$("#disease_category").live('click', function() {
	window.location.hash = "disease_category";
	reload_content("disease_category");
});

$("#doctors_list").live('click', function() {
	window.location.hash = "doctors_list";
	reload_content("doctors_list");
});

$(".get_doctors_form").live('click', function() {
	window.location.hash = "addNewDoctor";
	reload_content("addNewDoctor");
});

$("#reasons_list_form").live('click', function() {
	window.location.hash = "reasons_list";
	reload_content("reasons_list");
});

$(".reasons_list_form").live('click', function() {
	window.location.hash = "reasons_list";
	reload_content("reasons_list");
});

$(".add_reason").live('click', function() {
	window.location.hash = "addNewReasons";
	reload_content("addNewReasons");
});

$(".add_other_charges").live('click', function() {
	window.location.hash = "addOtherCharges";
	reload_content("addOtherCharges");
});

$(".add_cost_modifier").live('click', function() {
	window.location.hash = "addCostModifier";
	reload_content("addCostModifier");
});

$(".other_charges").live('click', function() {
	window.location.hash = "charges_list";
	reload_content("charges_list");
});

$(".cost_modifier").live('click', function() {
	window.location.hash = "cost_modifier";
	reload_content("cost_modifier");
});

$("#files_category_list").live('click', function() {
	window.location.hash = "files_category";
	reload_content("files_category");
});

$(".get_filecategory_form").live('click', function() {
	window.location.hash = "add_new_filecategory";
	reload_content("add_new_filecategory");
});

$(".calendar_location").live('click', function() {
	window.location.hash = "location_list";
	reload_content("location_list");
});

$(".calendar_type").live('click', function() {
	window.location.hash = "type_list";
	reload_content("type_list");
});


/* SIDE NAV */
$(".inventory_management_menu").live('click', function() {
	window.location.href = "inventory";
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

$(".calendar_menu").live('click', function() {
	window.location.href = "calendar";
});

function loadMedicalSettings(){
	window.location.hash = "disease_category";
	reload_content("disease_category");
}

function loadInventorySettings(){
	window.location.hash = "dosage_list";
	reload_content("dosage_list");
}

function loadCalendarSettings(){
	window.location.hash = "location_list";
	reload_content("location_list");
}

function getLocationList(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "module_management/location_list",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function getTypeList(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "module_management/type_list",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function getDosageList(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "module_management/dosage_list",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function getFileCategoryList(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "module_management/files_list",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function loadAddNewFileCategory(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "module_management/loadAddNewFileCategory",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function getQuantityList(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "module_management/quantity_list",{},function(o){
		$('#main_wrapper_management').html(o);
	});
} 

function loadAddNewLocationTypeForm(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "module_management/loadAddNewLocationTypeForm",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function loadAddNewCalendarTypeForm(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "module_management/loadAddNewCalendarTypeForm",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function loadAddNewDosageTypeForm(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "module_management/loadAddNewDosageTypeForm",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function loadAddNewQuantityTypeForm(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "module_management/loadAddNewQuantityTypeForm",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

//CALENDAR
function edit_location_type(id){
	var id = parseInt(id);
	$.post(base_url + "module_management/loadModalEditLocationForm",{id:id},function(o){
		$('#edit_location_form_wrapper').html(o);
		$('#edit_location_form_wrapper').modal();
	});
}

//DISEASE

function getDiseaseCategoryList(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "module_management/disease_category",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function getDiseaseTypeList(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "module_management/disease_type",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function loadAddNewCategoryForm(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "module_management/loadAddNewDiseaseCategoryForm",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function loadAddNewTypeForm(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "module_management/loadAddNewDiseaseTypeForm",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}


function edit_disease_category(id){
	var id = parseInt(id);
	$.post(base_url + "module_management/loadModalEditDiseaseCategoryForm",{id:id},function(o){
		$('#edit_disease_category_form_wrapper').html(o);
		$('#edit_disease_category_form_wrapper').modal();
	});
}

function edit_disease_type(id){
	var id = parseInt(id);
	$.post(base_url + "module_management/loadModalEditDiseaseTypeForm",{id:id},function(o){
		$('#edit_disease_type_form_wrapper').html(o);
		$('#edit_disease_type_form_wrapper').modal();
	});
}

function edit_dosage_type(id){
	var id = parseInt(id);
	$.post(base_url + "module_management/loadModalEditDosageForm",{id:id},function(o){
		$('#edit_dosage_form_wrapper').html(o);
		$('#edit_dosage_form_wrapper').modal();
	});
}

function edit_quantity_type(id){
	var id = parseInt(id);
	$.post(base_url + "module_management/loadModalEditQuantityForm",{id:id},function(o){
		$('#edit_quantity_form_wrapper').html(o);
		$('#edit_quantity_form_wrapper').modal();
	});
}

function delete_disease_category(id){
	var id = parseInt(id);
	$.post(base_url + "module_management/loadModalDeleteDiseaseCategoryForm",{id:id},function(o){
		$('#delete_disease_category_form_wrapper').html(o);
		$('#delete_disease_category_form_wrapper').modal();
	});
}
function delete_calendar_type(id){
	var id = parseInt(id);
	$.post(base_url + "module_management/loadModalDeleteCalendarForm",{id:id},function(o){
		$('#delete_calendar_form_wrapper').html(o);
		$('#delete_calendar_form_wrapper').modal();
	});
}

function delete_disease_type(id){
	var id = parseInt(id);
	$.post(base_url + "module_management/loadModalDeleteDiseaseTypeForm",{id:id},function(o){
		$('#delete_disease_type_form_wrapper').html(o);
		$('#delete_disease_type_form_wrapper').modal();
	});
}

function delete_dosage_type(id){
	var id = parseInt(id);
	$.post(base_url + "module_management/loadModalDosageForm",{id:id},function(o){
		$('#delete_dosage_form_wrapper').html(o);
		$('#delete_dosage_form_wrapper').modal();
	});
}

function delete_quantity_type(id){
	var id = parseInt(id);
	$.post(base_url + "module_management/loadModalQuantityForm",{id:id},function(o){
		$('#delete_quantity_form_wrapper').html(o);
		$('#delete_quantity_form_wrapper').modal();
	});
}

/* DOCTORS LIST / PATIENTS SETTINGS */

function loadDoctorsForm(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "module_management/doctors_list",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function loadAddDoctorsForm(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "module_management/loadDoctorsForm",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function edit_doctor(id){
	var id = parseInt(id);
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "module_management/loadEditDoctorsForm",{id:id},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function delete_doctor(id){
	var id = parseInt(id);
	$.post(base_url + "module_management/loadDeleteDoctorsForm",{id:id},function(o){
		$('#delete_doctors_form_wrapper').html(o);
		$('#delete_doctors_form_wrapper').modal();
	});
}

/* REASONS SETTINGS */

function loadReasonsForm(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "module_management/reasons_list",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function loadAddReasonsForm(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "module_management/loadReasonsForm",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function edit_reason(id){
	var id = parseInt(id);
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "module_management/loadEditReasonsForm",{id:id},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function delete_reason(id){
	var id = parseInt(id);
	$.post(base_url + "module_management/loadDeleteReasonsForm",{id:id},function(o){
		$('#delete_reasons_form_wrapper').html(o);
		$('#delete_reasons_form_wrapper').modal();
	});
}

/* ACCOUNT AND BILLING */


function loadAccountBillingSettings(){
	window.location.hash = "charges_list";
	reload_content("charges_list");
}

function getOtherChargesList(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "module_management/other_charges_list",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function getCostModifierList(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "module_management/cost_modifier_list",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function loadOtherChargesForm(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "module_management/loadOtherChargesForm",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function loadCostModifierForm(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "module_management/loadCostModifierForm",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function edit_oc(id){
	var id = parseInt(id);
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "module_management/loadEditOtherChargesForm",{id:id},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function edit_cost_modifier(id){
	var id = parseInt(id);
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "module_management/loadEditCostModifierForm",{id:id},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function delete_oc(id){
	var id = parseInt(id);
	$.post(base_url + "module_management/loadDeleteOtherChargesForm",{id:id},function(o){
		$('#delete_doctors_form_wrapper').html(o);
		$('#delete_doctors_form_wrapper').modal();
	});
}

function delete_cost_modifier(id){
	var id = parseInt(id);
	$.post(base_url + "module_management/loadDeleteCostModifierForm",{id:id},function(o){
		$('#delete_cost_modifier_form_wrapper').html(o);
		$('#delete_cost_modifier_form_wrapper').modal();
	});
}

function edit_filecategory(id){
	var id = parseInt(id);
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "module_management/loadEditFileCategory",{id:id},function(o){
		$('#main_wrapper_management').html(o);
	});
}