var IS_EDIT_REGIMEN_GENERAL_FORM_CHANGE = false;
var IS_ADD_REGIMEN_GENERAL_FORM_CHANGE 	= false;

function resetStatus(){
	IS_EDIT_REGIMEN_GENERAL_FORM_CHANGE = false;
	IS_ADD_REGIMEN_GENERAL_FORM_CHANGE 	= false;
}

function hideAlert(){
	$("#alert_confirmation_wrapper").hide();
}

function reload_content(fragment) {
	var hash = window.location.hash;

	if(
		IS_EDIT_REGIMEN_GENERAL_FORM_CHANGE ||
		IS_ADD_REGIMEN_GENERAL_FORM_CHANGE
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
	unsetSession();
	hideAlert();

	$('#main_wrapper_management').html("");
	$('.module_management_topbar').removeClass('hidden');
	$('.regimen_menu_topbar').removeClass('hidden');
	$('.regimen_menu').addClass('hilited');


	if(hash == "#lists" || hash == "") {
		getRegimenList();
	}else if(hash == "#newRegimen"){
		loadNewRegimenForm();
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
	window.location.href = "order";
});

$(".firm_admin_users").live('click', function() {
	window.location.href="users";
});

$(".calendar_menu").live('click', function() {
	window.location.href = "calendar";
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
	window.location.href = "inventory";
});

$(".new_regimen_form").live('click', function() {
	window.location.hash = "newRegimen";
	reload_content("newRegimen");
});

$(".regimen_menu").live('click', function() {
	window.location.hash = "lists";
	reload_content("lists");
});

$(".regimen_list_form").live('click', function() {
	window.location.hash = "lists";
	reload_content("lists");
});

$(".regimen_general_cancel_button").live('click', function() {
	unsetSession();
	window.location.hash = "lists";
	reload_content("lists");
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

function getRegimenList(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "regimen_management/getRegimenList",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function loadNewRegimenForm(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "regimen_management/loadNewRegimenApp",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function loadMedicineFormforNewRow(form){
	var patient_id = $("#user_list").val();
	if(patient_id == 0 || patient_id == null){
		alert("Please select patient.");
	} else {
		var regimen_id = $("#regimen_id").val();
		var version_id = $("#version_id").val();
		// alert(regimen_id);
		$.post(base_url + "regimen_management/loadMedicineFormforNewRow",{patient_id:patient_id,form:form, regimen_id:regimen_id, version_id:version_id},function(o){
			$('#medicine_modal_form').html(o);
			$('#medicine_modal_form').modal("show");
		});
	}
}

function view_regimen_summary(id,version_id){
	var id = parseInt(id);
	var version_id = parseInt(version_id);
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "regimen_management/loadViewSummary", {id:id, version_id:version_id}, function(o){
		$('#main_wrapper_management').html(o);
	});
}

function print_version_regimen(id,version_id){
	window.open(base_url+'download/print_version_regimen/'+id+'/'+version_id,"_blank");
}

function view_regimen(id,version_id){
	var id = parseInt(id);
	var version_id = parseInt(version_id);
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "regimen_management/loadViewRegimen", {id:id,version_id:version_id}, function(o){
		$('#main_wrapper_management').html(o);
	});
}

function getMedicineTable(id,version_id){
	var id = parseInt(id);
	var version_id = parseInt(version_id);
	$('#medicine_wrapper_table').html(default_ajax_loader);
	if(id){
		$.post(base_url + "regimen_management/loadMedicineTable2", {id:id, version_id:version_id}, function(o){
			$('#medicine_wrapper_table').html(o);
		});
	} else {
		$.post(base_url + "regimen_management/loadMedicineTable", {}, function(o){
			$('#medicine_wrapper_table').html(o);
		});
	}
}

/*function getMedicineTable2(){
	$('#medicine_wrapper_table').html(default_ajax_loader);
	$.post(base_url + "regimen_management/loadMedicineTable3", {}, function(o){
			$('#medicine_wrapper_table').html(o);
		});
}*/

function edit_regimen(id,version_id){
	var id = parseInt(id);
	var version_id = parseInt(version_id);
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "regimen_management/loadEditRegimenApp",{id:id,version_id:version_id},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function delete_regimen(id){
	var id = parseInt(id);
	$.post(base_url + "regimen_management/deleteRegimenRecord",{id:id},function(o){
		$('#delete_regimen').html(o);
		$('#delete_regimen').modal("show");
	});
}


function edit_regimen_record(id){
	var id = parseInt(id);
	var regimen_id = parseInt($('#regimen_id').val());
	$.post(base_url + "regimen_management/loadMedicineFormforEditRow",{id:id, regimen_id:regimen_id},function(o){
		$('#medicine_modal_form').html(o);
		$('#medicine_modal_form').modal("show");
	});
}

function edit_regimen_sess(id){
	var id = parseInt(id);
	$.post(base_url + "regimen_management/loadMedicineFormforEditRowSession",{id:id},function(o){
		$('#medicine_modal_form').html(o);
		$('#medicine_modal_form').modal("show");
	});
}


function delete_regimen_sess(id){
	var id = parseInt(id);
	$.post(base_url + "regimen_management/deleteMedicineEntrySession",{id:id},function(o){
		$('#delete_regimen_entry').html(o);
		$('#delete_regimen_entry').modal("show");
	});
}

function delete_regimen_record(id){
	var id = parseInt(id);
	$.post(base_url + "regimen_management/deleteMedicineEntry",{id:id},function(o){
		$('#delete_regimen_entry').html(o);
		$('#delete_regimen_entry').modal("show");
	});
}


function deleteElementRow(element,id){
	if(id){
		$.post(base_url + 'regimen_management/deletePerRecord',{id:id}, function(){});
	}
	$('.'+element).remove();
}

var breakfastCount = 0;
var subBreakfastCount = 0;
function addBreakfastRow() {
	subBreakfastCount = 0;
	var main_index = breakfastCount++;
	var index 		= subBreakfastCount++;
	$('#loading_wrapper').html('<div style="padding: 15px 57px 0px 80px; text-align: center; font-size: 30px;">' + topright_ajax_loader + '</div>');
	$.post(base_url + 'regimen_management/createBreakfastFields',{index:index, main_index:main_index},function(o) {
	$('#loading_wrapper').html("");
	$('#add_breakfast_wrapper').before(o.html);
	},'json');
}

function createSubBreakfastFields(main_index) {
	var index = subBreakfastCount++;
	var main_index = parseInt(main_index);
	$('#sub_wrapper_loader').html('<div style="padding: 15px 57px 0px 80px; text-align: center; font-size: 30px;">' + topright_ajax_loader + '</div>');
	$.post(base_url + 'regimen_management/createSubBreakfastFields',{index:index,main_index:main_index},function(o) {
	$('#sub_bf_wrapper_loader_' + main_index ).html("");
	$('#sub_bf_wrapper_' + main_index).before(o.html);
	},'json');
}


function delSaveMedicine(element, counter, meal_type){
	var counter = parseInt(counter);
	var temp_id = parseInt($("#temp_id").val());

	
	if(counter >= 0 && meal_type != "" && temp_id >= 0){
		$.post(base_url + "regimen_management/deleteMultiMedicineEntry", {counter:counter,meal_type:meal_type,temp_id:temp_id},function(){});
	}
	// get id then delete it at database
	$('.'+element).remove();
}

var lunchCount = 0;
var subLunchCount = 0;

function addLunchRow() {
	subLunchCount = 0;
	var main_index  = lunchCount++;
	var index 		= subLunchCount++;
	$('#loading_wrapper2').html('<div style="padding: 15px 57px 0px 80px; text-align: center; font-size: 30px;">' + topright_ajax_loader + '</div>');
	$.post(base_url + 'regimen_management/createLunchFields',{index:index,main_index:main_index},function(o) {
	$('#loading_wrapper2').html("");
	$('#add_lunch_wrapper').before(o.html);
	},'json');
}

function createSubLunchFields(main_index) {
	var index = subLunchCount++;
	var main_index = parseInt(main_index);	
	$('#sub_wrapper_loader').html('<div style="padding: 15px 57px 0px 80px; text-align: center; font-size: 30px;">' + topright_ajax_loader + '</div>');
	$.post(base_url + 'regimen_management/createSubLunchFields',{index:index,main_index:main_index},function(o) {
		$('#sub_l_wrapper_loader_' + main_index ).html("");
		$('#sub_l_wrapper_' + main_index).before(o.html);
	},'json');
}


function deleteSubElementLunchRow(element){
	var element = parseInt(element);
	$('.lunch_sub_wrapper_'+element).remove();
	
}

var dinnerCount = 0;
var subDinnerCount = 0;

function addDinnerRow() {
	subDinnerCount = 0;
	var main_index = dinnerCount++;
	var index = subDinnerCount++;
	$('#loading_wrapper3').html('<div style="padding: 15px 57px 0px 80px; text-align: center; font-size: 30px;">' + topright_ajax_loader + '</div>');
	$.post(base_url + 'regimen_management/createDinnerFields',{index:index,main_index:main_index},function(o) {
	$('#loading_wrapper3').html("");
	$('#add_dinner_wrapper').before(o.html);
	},'json');
}

function createSubDinnerFields(main_index) {
	var index = subDinnerCount++;
	var main_index = parseInt(main_index);
	$('#sub_wrapper_loader').html('<div style="padding: 15px 57px 0px 80px; text-align: center; font-size: 30px;">' + topright_ajax_loader + '</div>');
	$.post(base_url + 'regimen_management/createSubDinnerFields',{index:index,main_index:main_index},function(o) {
	$('#sub_d_wrapper_loader_' + main_index ).html("");
	$('#sub_d_wrapper_' + main_index).before(o.html);
	},'json');
}

function deleteSubElementDinnerRow(element){
	var element = parseInt(element);
	$('.dinner_sub_wrapper_'+element).remove();
	
}

function unsetSession(){
	$.post(base_url + 'regimen_management/unsetSession',{},function(o) {
	});
}

function getMedicineQuantityType(main_fragments,fragments,type){
	var main_fragments 	= parseInt(main_fragments);
	var fragments 		= parseInt(fragments);
	var med 			= parseInt($("#" + type + "_medicine_list_" + main_fragments + "_" + fragments).val());	
	$("#" + type + "_quantity_val_" + fragments).val("");

	$.post(base_url + "inventory_management/getMedicineDetails", {med:med}, function(o){
		$("#" + type + "_taken_as_" + main_fragments + "_" + fragments).attr('checked', true);
		$("#" + type + "_dosage_val_" + main_fragments + "_" + fragments).val(o.output['quantity_type']);
	}, 'json');	
}

function dupicate2Breakfast(meal_type){
	myData = $('#regimen_new_row_form').serializeObject();

	$.post(base_url + "regimen_management/dupicate2Breakfast", {myData:myData,meal_type:meal_type}, function(o){
		if(o.is_successful){
			loadBreakfastAddForm();
		} else {
			alert("No Data to duplicate");
		}
	}, 'json');
}

function loadBreakfastAddForm(){
	$.post(base_url + "regimen_management/loadBreakfastAddForm", {}, function(o){
		$("#selector_tabs a[href='#breakfast_form']").tab('show');
		$("#breakfast_duplicate_wrapper").html(o);
	});
}

function duplicateB2Lunch(meal_type){
	myData = $('#regimen_new_row_form').serializeObject();

	$.post(base_url + "regimen_management/duplicateB2Lunch", {myData:myData,meal_type:meal_type}, function(o){
		if(o.is_successful){
			loadLunchAddForm();
		} else {
			alert("No Data to duplicate");
		}
	}, 'json');
}

function loadLunchAddForm(){
	$.post(base_url + "regimen_management/loadLunchAddForm", {}, function(o){
		$("#selector_tabs a[href='#lunch_form']").tab('show');
		$("#lunch_duplicate_wrapper").html(o);
	});
}

function duplicateL2Dinner(meal_type){
	myData = $('#regimen_new_row_form').serializeObject();

	$.post(base_url + "regimen_management/duplicateL2Dinner", {myData:myData,meal_type:meal_type}, function(o){
		if(o.is_successful){
			loadDinnerAddForm();
		} else {
			alert("No Data to duplicate");
		}
	}, 'json');
}

/*$("#dinner_tab").live("click", function(){
	loadDinnerAddForm();
});*/

function loadDinnerAddForm(){
	$.post(base_url + "regimen_management/loadDinnerAddForm", {}, function(o){
		$("#selector_tabs a[href='#dinner_form']").tab('show');
		$("#dinner_duplicate_wrapper").html(o);
	});
}

function duplicate(meal_type){
	var duplicate = $("#" + meal_type + "_duplicate_select").val();
	// alert(duplicate);
	if(duplicate == "to Lunch"){
		duplicateB2Lunch(meal_type);
	} else if(duplicate == "to Dinner") {
		duplicateL2Dinner(meal_type);
	} else {
		dupicate2Breakfast(meal_type);
	}
}

function duplicateDB(meal_type){
	var duplicate 	= $("#" + meal_type + "_duplicate_select").val();
	var row_id 		= $("#row_id").val();
	var regimen_id 	= $("#regimen_id").val();
	var version_id 	= $("#version_id").val();
	var temp_id 	= $("#temp_id").val();
	if(duplicate == "to Lunch"){
		var type = "lunch";
	} else if(duplicate == "to Dinner") {
		var type = "dinner";
	} else {
		var type = "bf";
	}

	$.post(base_url + "regimen_management/duplicateDBMedicine", {meal_type: meal_type, type:type, row_id:row_id, regimen_id:regimen_id, version_id:version_id}, function(o){
		if(o.is_successful){
			$("#medicine_modal_form").modal();
			getMedicineTable(regimen_id,version_id);
			edit_regimen_record(temp_id);
			alert(o.message);
		} else {
			alert(o.message)
		}
		
	}, "json");
}

function createVersion(reg_id, version_id){
	var reg_id = parseInt(reg_id);
	var version_id = parseInt(version_id);
	$.post(base_url + "regimen_management/createVersion", {reg_id:reg_id, version_id:version_id}, function(o){
		$("#main_wrapper_management").html(o);
	});
}



function unsetMealTypes(){
	$.post(base_url + "regimen_management/unsetMealTypes", {}, function(){});
}

function view_version(version_id){
	var version_id = parseInt(version_id);
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "regimen_management/loadViewVersion", {version_id:version_id}, function(o){
		$('#main_wrapper_management').html(o);
	});
}

function delete_version_regimen(version_id){
	var version_id = parseInt(version_id);
	$.post(base_url + "regimen_management/deleteRegimenVersionRecord",{version_id:version_id},function(o){
		$('#delete_regimen_version').html(o);
		$('#delete_regimen_version').modal("show");
	});
}

function duplicate_row(temp_id){
	var temp_id = parseInt(temp_id);
	var x = confirm("Duplicate Row?");
	if(x){
		$.post(base_url + "regimen_management/duplicateRow",{temp_id:temp_id},function(o){
			getMedicineTable();
		});
	}
}

function duplicate_rowDB(main_id){
	var main_id = parseInt(main_id);
	var x = confirm("Duplicate Row?");
	if(x){
		$.post(base_url + "regimen_management/duplicateRowDB",{main_id:main_id},function(o){
			if(o.is_successful){
				getMedicineTable(o.regimen_id, o.version_id);
			} else {
				alert(o.message);
			}
			
		}, "json");
	}
}

function convertInvoice(id,version_id){
	var regimen_id = parseInt(id);
	var version_id = parseInt(version_id);
	window.location.href = 'billing#invoice';
	sessionStorage.setItem("regimen_id", regimen_id);
	sessionStorage.setItem("version_id", version_id);
}

function generateSummary(){
	var regimen_id = sessionStorage.getItem("regimen_id");
	var version_id = sessionStorage.getItem("version_id");
	if(regimen_id){
		sessionStorage.removeItem("regimen_id");
		sessionStorage.removeItem("version_id");
		view_regimen_summary(regimen_id,version_id);
	}
}