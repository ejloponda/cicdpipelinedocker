var IS_ADD_ACCOUNTING_BILLING_CHANGE = false;
var IS_ADD_RECEIPT_BILLING_CHANGE = false;
var IS_EDIT_ACCOUNTING_BILLING_CHANGE = false;

function reload_content(fragment) {
	var hash = window.location.hash;

	if(IS_EDIT_ACCOUNTING_BILLING_CHANGE){
		alert("You have deleted information from database. Please press Save to continue.");
	} else {
		if(
			IS_ADD_ACCOUNTING_BILLING_CHANGE ||
			IS_ADD_RECEIPT_BILLING_CHANGE
		) {
			var con = confirm("Data is not yet saved. Do you want to continue?");

			if(con) {
				load_page(hash);
			}

		} else {
			load_page(hash);
		}
	}
	
}

function reset_changes(){
	IS_ADD_ACCOUNTING_BILLING_CHANGE = false;
	IS_ADD_RECEIPT_BILLING_CHANGE = false;
	IS_EDIT_ACCOUNTING_BILLING_CHANGE = false;
}

function load_page(hash) {
	reset_all();
	reset_all_topbars();
	reset_changes();
	$('.module_management_topbar').removeClass('hidden');
	$('.account_billing_menu').removeClass('hidden');

	$('#main_wrapper_management').html("");
	$('.billing_menu').addClass('hilited');
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

$(".billing_menu").live('click', function() {
	window.location.hash = "sales";
	reload_content("sales");
});

$(".order_menu").live('click', function() {
	window.location.href = "order";
});

$(".calendar_menu").live('click', function() {
	window.location.href = "calendar";
});

$(".sales_report_list").live('click', function() {
	window.location.hash = "sales";
	reload_content("sales");
});

$(".accounts_receivable_list").live('click', function() {
	window.location.hash = "receivables";
	reload_content("receivables");
});

$(".collections_list").live('click', function() {
	window.location.hash = "collections";
	reload_content("collections");
});

$(".rpc_form_invoice").live('click', function() {
	window.location.hash = "invoice";
	reload_content("invoice");
});

$(".alist_form_invoice").live('click', function() {
	window.location.hash = "invoice-Alist";
	reload_content("invoice-Alist");
});

$(".activity_log_menu").live('click', function() {
	window.location.href = "activity_log";
});


$(".patient_menu").live('click', function() {
	
	if(IS_EDIT_ACCOUNTING_BILLING_CHANGE){
		alert("You have deleted information from database. Please press Save to continue.");
	} else {
		window.location.href = "patient";
	}

});

$(".module_management_menu").live('click', function() {
	
	if(IS_EDIT_ACCOUNTING_BILLING_CHANGE){
		alert("You have deleted information from database. Please press Save to continue.");
	} else {
		window.location.href = "module";
	}
});

$(".firm_admin_users").live('click', function() {
	
	if(IS_EDIT_ACCOUNTING_BILLING_CHANGE){
		alert("You have deleted information from database. Please press Save to continue.");
	} else {
		window.location.href = "users";
	}
});

$(".inventory_management_menu").live('click', function() {
	
	if(IS_EDIT_ACCOUNTING_BILLING_CHANGE){
		alert("You have deleted information from database. Please press Save to continue.");
	} else {
		window.location.href = "inventory";
	}
});

$(".firm_admin_permissions").live('click', function() {
	
	if(IS_EDIT_ACCOUNTING_BILLING_CHANGE){
		alert("You have deleted information from database. Please press Save to continue.");
	} else {
		window.location.href = "users#permissions";
	}
});

$(".firm_admin_add_role").live('click', function() {
	
	if(IS_EDIT_ACCOUNTING_BILLING_CHANGE){
		alert("You have deleted information from database. Please press Save to continue.");
	} else {
		loadUserRoleForm();
	}
});

$(".regimen_menu").live('click', function() {
	
	if(IS_EDIT_ACCOUNTING_BILLING_CHANGE){
		alert("You have deleted information from database. Please press Save to continue.");
	} else {
		window.location.href = "regimen";
	}
});

$(".firm_admin_roles").live('click', function() {
	
	if(IS_EDIT_ACCOUNTING_BILLING_CHANGE){
		alert("You have deleted information from database. Please press Save to continue.");
	} else {
		window.location.href = "permissions";
	}
});

$(".reports_menu").live('click', function() {
	
	if(IS_EDIT_ACCOUNTING_BILLING_CHANGE){
		alert("You have deleted information from database. Please press Save to continue.");
	} else {
		window.location.href = "reports";
	}
});

function loadIndex(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "account_billing/getIndex",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function loadPreviousForm(){
	var content = window.location.hash;
	content = content.replace(/#/g,'');
	reload_content(content);
}

function loadReceivables(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "account_billing/getAccountReceivables",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function loadCollections(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "account_billing/getCollections",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function loadConvertInvoice(){
	regimen_id = sessionStorage.getItem("regimen_id");
	version_id = sessionStorage.getItem("version_id");
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "account_billing/getConvertToInvoice", {regimen_id:regimen_id, version_id:version_id}, function(o){
		$('#main_wrapper_management').html(o);
	});
}

function loadConvertInvoiceAlist(){
	regimen_id = sessionStorage.getItem("regimen_id");
	version_id = sessionStorage.getItem("version_id");
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "account_billing/getConvertToInvoiceALIST", {regimen_id:regimen_id, version_id:version_id}, function(o){
		$('#main_wrapper_management').html(o);
	});
}

function loadConvertInvoiceAlist2(regimen_id,version_id){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "account_billing/getConvertToInvoiceALIST", {regimen_id:regimen_id, version_id:version_id}, function(o){
		$('#main_wrapper_management').html(o);
	});
}

function loadInvoiceORForm(invoice_id){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "account_billing/getORAddForm", {invoice_id:invoice_id}, function(o){
		$('#main_wrapper_management').html(o);
	});
}


/* START */

function hasValue(variable) {
	return ($.trim(variable).length > 0 ? true : false);
}

function checkRadio(variable){
	return variable === "undefined" ? false : true;
}

function addCommas(nStr)  // Add Comma to Price (Numbers)
{
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}


var otherCounter 	= 0;
var otherCounter2 	= 0;
var totalOCharges 	= 0;
function addNewOtherCharges() {
	// Get the values
	var description 		= $('#o_description').val();
	var quantity 			= parseInt($('#o_quantity').val());
	var cost 				= parseFloat($('#o_cost').val());

	// check if there's value using the hasValue function which can be found in accounting.js
	if(hasValue(description) && hasValue(quantity) && hasValue(cost) ) {
		if(!isNaN(cost) && !isNaN(quantity)){
			var html="";
			otherCounter++;
			otherCounter2++;
			var total = cost * quantity;
			var total_charges = parseFloat($("#total_o_charges").val());
			if(total_charges > 0){
				totalOCharges = total_charges;
			}
			totalOCharges = totalOCharges + total;

			var description_input 	= "additional["+otherCounter+"][description]";
			var quantity_input		= "additional["+otherCounter+"][quantity]";
			var cost_item_input 	= "additional["+otherCounter+"][cost_per_item]";
			var cost_input 			= "additional["+otherCounter+"][cost]";
			var cost_input_id		= "other_charges_cost_" + otherCounter;
			var cost_item_input_id	= "other_charges_per_cost_" + otherCounter;
			var delete_button		= BASE_IMAGE_PATH +"doc_delete.png";
			$('#other_charges_list').show();

			html += '<tr id="other_charge_row_'+otherCounter+'">';
			html += '<td><input type="hidden" name="'+ description_input +'" value="'+description+'">'+description+'</td>';
			html += '<td><input type="hidden" name="'+ quantity_input +'" value="'+quantity+'">'+quantity+'</td>';
			html += '<td><input type="hidden" name="'+ cost_item_input +'" id="' + cost_item_input_id + '" value="'+cost+'">&#8369; '+addCommas(cost.toFixed(2))+'</td>';
			html += '<td><input type="hidden" name="'+ cost_input +'" id="' + cost_input_id + '" value="'+total+'">&#8369; '+addCommas(total.toFixed(2))+'</td>';
			html += '<td style="width: 10%;"><ul class="actions"><li><img src="' + delete_button + '" onclick="javascript:removeOChargesRow('+otherCounter+')" title="Delete"></li></ul></td>';
			html += '</tr>';

			$('#other_charges_list').append(html);
			$("#total_o_charges").val(totalOCharges.toFixed(2));
			$(".other_charges_gross").html("&#8369; " + addCommas(totalOCharges.toFixed(2)));
			$('.other_charges_main').val("");
			$('#o_description').focus();
			addToDropDownCM(description,cost_input_id);
			calculateOverallTotal();
		} else {
			alert("Warning: Quantity / Cost must be limited to number formatting.")
		}
	} else {
		alert("Warning : Please fill-up the required fields before adding other charges.")
	}
}

function addToDropDownCM(description, cost_input_id){
	if(otherCounter2 <= 1){
		$("#apply").append("<optgroup label='Other Charges' class='other_charges_select'><option data-type='Other Charges' value='All Other Charges'>All Other Charges</option><option data-type='Other Charges' value='"+description+"' data-id='"+cost_input_id+"'>"+description+"</option></optgroup>");
	} else {
		$("#apply .other_charges_select").append("<option value='"+description+"' data-id='"+cost_input_id+"'>"+description+"</option>");
	}	
}

function removeToDropDownCM(cost_input_id){
	if(otherCounter2 < 1){
		$("#apply option[data-id='"+cost_input_id+"']").remove();
		$("#apply").children().remove("optgroup[label='Other Charges']");
	} else {
		$("#apply option[data-id='"+cost_input_id+"']").remove();
	}
}

function removeOChargesRow(id) {
	var q = confirm("Delete Other Charges Row?");
	if(q){
		var x_cost = parseFloat($('#other_charges_cost_'+id).val());
		totalOCharges = totalOCharges - x_cost;

		$('#other_charge_row_'+id).remove();
		otherCounter2--;
		removeToDropDownCM("other_charges_cost_"+id);
		$("#total_o_charges").val(totalOCharges.toFixed(2));
		$(".other_charges").html("&#8369; " + addCommas(totalOCharges.toFixed(2)));
		$(".other_charges_gross").html("&#8369; " + addCommas(totalOCharges.toFixed(2)));
		if(otherCounter2 <= 0){
			$('#other_charges_list').hide();
		}
		calculateOverallTotal();
	}
}

function deleteOtherCharges(id) {
	var q = confirm("Delete Other Charges Row?");
	if(q){
		var total_charges = parseFloat($("#total_o_charges").val());
		if(total_charges > 0){
			totalOCharges = total_charges;
		}
		var x_cost = parseFloat($('#other_charges_cost_data_'+id).val());
		totalOCharges = totalOCharges - x_cost;
		$('#old_other_charges_row_'+id).remove();
		otherCounter2--;
		removeToDropDownCM("other_charges_cost_data_"+id);
		$("#total_o_charges").val(totalOCharges.toFixed(2));
		$(".other_charges").html("&#8369; " + addCommas(totalOCharges.toFixed(2)));
		$(".other_charges_gross").html("&#8369; " + addCommas(totalOCharges.toFixed(2)));
		if(otherCounter2 <= 0){
			$('#other_charges_list').hide();
		}
		$.post(base_url+"account_billing/delete_other_charges", {id:id}, function(){
			IS_EDIT_ACCOUNTING_BILLING_CHANGE = true;
		});
		calculateOverallTotal();
	}
}


var CMCounter 	= 0;
var CMCounter2 	= 0;
var totalCM 	= 0;
function addNewCM() {
	// Get the values
	var apply_to 			= $('#apply2').val();
	var modifier_type		= $('input[name=modifier_type]:checked', '#add_invoice_form').val();
	var modify_due_to		= $('#modify_due_to').val();
	var cost_type 			= $('#cost_type').val();
	var cost_modifier		= parseFloat($('#cost_modifier').val());
	var modifier 			= "";
	var a 					= 0;
	var price 				= 0;
	var regimen_cost_input 	= "";
	var log = $("#apply2").find('option:selected').data('type');
	var log2 = $("#apply2").find('option:selected').data('id');
	
	if(log == "RPC"){
		if(apply_to == "All Medicines"){
			// regimen_cost_input = parseFloat($("#regimen_cost_input").val());
			regimen_cost_input = parseFloat($("#rpc_meds_total_input").val());
		} else {
			var key = $("#apply2").find('option:selected').data('key');
			regimen_cost_input = parseFloat($("#rpc_per_med_total_price_"+key).val());
		}
	} else {
		if(apply_to == "All Other Charges"){
			// regimen_cost_input = parseFloat($("#summary_total_o_charges").val());
			regimen_cost_input = parseFloat($("#total_o_charges").val());
		} else {

			var regimen_cost_input =  parseFloat($("#apply2").find('option:selected').data('price'));
			//regimen_cost_input = parseFloat($("#"+data_id).val());
		}
	}
	
	
	var operatorPercentage = {
			    '+': function(price, b) { return price+b },
			    '-': function(price, b) { return price-b },
			};
	var operatorCash = {
			    '+': function(price, regimen_cost_input) { return price+cost_modifier },
			    '-': function(price, regimen_cost_input) { return price-cost_modifier },
			};
	
	// check if there's value using the hasValue and checkRadio for radio button function which can be found in accounting.js
	if(hasValue(apply_to) && checkRadio(modifier_type) && hasValue(modify_due_to) && hasValue(cost_type) && hasValue(cost_modifier) ) {
		if(!isNaN(cost_modifier)){
			CMCounter++;
			CMCounter2++;

			if(modifier_type == "Mark up"){
				modifier = "+";
			} else {
				modifier = "-";
			}
			
			if(cost_type == "php"){
				price = operatorCash[modifier](price, regimen_cost_input);
			} else {
				a = cost_modifier / 100;
				var b = regimen_cost_input * a;
				price = operatorPercentage[modifier](price, b);
			}
			var CM = parseFloat($("#total_cm").val());
			totalCM = CM;
			totalCM = parseFloat(totalCM + price);

			var html="";

			var applies_to		 	= "cm_additional["+CMCounter+"][applies_to]";
			var modifier_type_input	= "cm_additional["+CMCounter+"][modifier_type]";
			var modify_due_to_input = "cm_additional["+CMCounter+"][modify_due_to]";
			var cost_type_input 	= "cm_additional["+CMCounter+"][cost_type]";
			var cost_modifier_input	= "cm_additional["+CMCounter+"][cost_modifier]";
			var total_cost_input	= "cm_additional["+CMCounter+"][total_cost]";
			var is_medicine			= "cm_additional["+CMCounter+"][is_medicine]";
			var cm_cost_type		= "cm_cost_type_" + CMCounter;
			var cm_cost_modifier	= "cm_cost_modifier_" + CMCounter;
			var cost_input_id		= "cm_cost_" + CMCounter;
			var modifier_type_id	= "cm_modifier_" + CMCounter;
			
			var delete_button		= BASE_IMAGE_PATH +"doc_delete.png";
			$('#cm_list').show();
			html += '<tr id="cm_row_'+CMCounter+'">';
			html += '<td><input type="hidden" name="'+ applies_to +'" value="'+apply_to+'">'+apply_to+'</td>';
			html += '<td><input type="hidden" name="'+ modifier_type_input +'" id="'+modifier_type_id+'" value="'+modifier_type+'">'+modifier_type+'</td>';
			html += '<td><input type="hidden" name="'+ modify_due_to_input +'" value="'+modify_due_to+'">'+modify_due_to+'</td>';
			html += '<td><input type="hidden" name="'+ cost_type_input +'" id="'+cm_cost_type+'" value="'+cost_type+'"><input type="hidden" name="'+ cost_modifier_input +'" id="'+cm_cost_modifier+'" value="'+cost_modifier+'">'+cost_modifier+' '+cost_type+'</td>';
			html += '<td><input type="hidden" name="'+ total_cost_input +'" id="' + cost_input_id + '" value="'+price+'">&#8369; '+addCommas(price.toFixed(2))+'</td>';
			html += '<td style="width: 10%;"><ul class="actions"><li><img src="' + delete_button + '" onclick="javascript:removeCMRow('+CMCounter+')" title="Delete"></li></ul></td>';
			html += '<td class = "is_medicine"><input type="hidden" name="'+ is_medicine +'" value="'+log2+'"></td>';
			html += '</tr>';

			$('#cm_list').append(html);
			$("#total_cm").val(totalCM.toFixed(2));
			$(".total_cm").html("&#8369; " + addCommas(totalCM.toFixed(2)));
			$('#modify_due_to').focus();
			$(".modifier_inputs").val("");
			calculateOverallTotal();
		} else {
			alert("Warning: Cost must be limited to number formatting.")
		}
	} else {
		alert("Warning : Please fill-up the required fields before adding cost modifiers")
	}
}

function removeCMRow(id) {
	
	var q = confirm("Delete Cost Modifier Row?");
	if(q){
		var x_cost = parseFloat($('#cm_cost_'+id).val());
		var modifier = $('#cm_modifier_'+id).val();

		totalCM = totalCM - x_cost;
		
		$('#cm_row_'+id).remove();
		CMCounter2--;
		
		$("#total_cm").val(totalCM.toFixed(2));
		$(".total_cm").html("&#8369; " + addCommas(totalCM.toFixed(2)));
		if(CMCounter2 <= 0){
			$('#cm_list').hide();
		}
		calculateOverallTotal();
	}
	
}

function deleteCMRow(id) {
	
	var q = confirm("Delete Cost Modifier Row?");
	if(q){
		var CM = parseFloat($("#total_cm").val());
		totalCM = CM;
		var x_cost = parseFloat($('#old_cm_cost_'+id).val());
		totalCM = totalCM - x_cost;
		
		// alert(totalCM);
		$('#old_cm_row_'+id).remove();
		CMCounter2--;
		
		$("#total_cm").val(totalCM.toFixed(2));
		$(".total_cm").html("&#8369; " + addCommas(totalCM.toFixed(2)));
		if(CMCounter2 <= 0){
			$('#cm_list').hide();
		}
		$.post(base_url+"account_billing/delete_cost_modifiers", {id:id}, function(){
			IS_EDIT_ACCOUNTING_BILLING_CHANGE = true;
		});
		calculateOverallTotal();
	}
	
}


/* ALIST */

var alist_otherCounter 		= 0;
var alist_otherCounter2 	= 0;
var alist_totalOCharges 	= 0;
function addNewOtherChargesALIST() {
	// Get the values
	var description 		= $('#alist_o_description').val();
	var quantity 			= parseInt($('#alist_o_quantity').val());
	var cost 				= parseFloat($('#alist_o_cost').val());

	// check if there's value using the hasValue function which can be found in accounting.js
	if(hasValue(description) && hasValue(quantity) && hasValue(cost) ) {
		if(!isNaN(cost) && !isNaN(quantity)){
			var html="";
			alist_otherCounter++;
			alist_otherCounter2++;
			var total = cost * quantity;
			alist_totalOCharges = alist_totalOCharges + total;

			var description_input 	= "alist_additional["+alist_otherCounter+"][description]";
			var quantity_input		= "alist_additional["+alist_otherCounter+"][quantity]";
			var cost_item_input 	= "alist_additional["+alist_otherCounter+"][cost_per_item]";
			var cost_input 			= "alist_additional["+alist_otherCounter+"][cost]";
			var cost_input_id		= "alist_other_charges_cost_" + alist_otherCounter;
			var cost_item_input_id	= "alist_other_charges_per_cost_" + alist_otherCounter;
			var delete_button		= BASE_IMAGE_PATH +"doc_delete.png";
			$('#alist_other_charges_list').show();

			html += '<tr id="other_charge_row_'+alist_otherCounter+'">';
			html += '<td><input type="hidden" name="'+ description_input +'" value="'+description+'">'+description+'</td>';
			html += '<td><input type="hidden" name="'+ quantity_input +'" value="'+quantity+'">'+quantity+'</td>';
			html += '<td><input type="hidden" name="'+ cost_item_input +'" id="' + cost_item_input_id + '" value="'+cost+'">&#8369; '+addCommas(cost.toFixed(2))+'</td>';
			html += '<td><input type="hidden" name="'+ cost_input +'" id="' + cost_input_id + '" value="'+total+'">&#8369; '+addCommas(total.toFixed(2))+'</td>';
			html += '<td style="width: 10%;"><ul class="actions"><li><img src="' + delete_button + '" onclick="javascript:removeOChargesRowALIST('+alist_otherCounter+')" title="Delete"></li></ul></td>';
			html += '</tr>';

			$('#alist_other_charges_list').append(html);
			
			$("#alist_total_o_charges").val(alist_totalOCharges.toFixed(2));
			$(".alist_other_charges").html("&#8369; " + addCommas(alist_totalOCharges.toFixed(2)));

			$('.alist_other_charges_main').val("");
			$('#alist_o_description').focus();
			addToDropDownCMALIST(description,cost_input_id);
			calculateOverallTotalALIST();
		} else {
			alert("Warning: Quantity / Cost must be limited to number formatting.")
		}
	} else {
		alert("Warning : Please fill-up the required fields before adding other charges.")
	}
}

function addToDropDownCMALIST(description, cost_input_id){
	if(alist_otherCounter2 <= 1){
		$("#alist_apply").append("<optgroup label='Other Charges' class='other_charges_select'><option data-type='Other Charges' value='All Other Charges'>All Other Charges</option><option data-type='Other Charges' value='"+description+"' data-id='"+cost_input_id+"'>"+description+"</option></optgroup>");
	} else {
		$("#alist_apply .other_charges_select").append("<option value='"+description+"' data-id='"+cost_input_id+"'>"+description+"</option>");
	}	
}

function removeToDropDownCMALIST(cost_input_id){
	if(alist_otherCounter2 < 1){
		$("#alist_apply option[data-id='"+cost_input_id+"']").remove();
		$("#alist_apply").children().remove("optgroup[label='Other Charges']");
	} else {
		$("#alist_apply option[data-id='"+cost_input_id+"']").remove();
	}
}

function removeOChargesRowALIST(id) {
	var q = confirm("Delete Other Charges Row?");
	if(q){
		var x_cost = parseFloat($('#alist_other_charges_cost_'+id).val());
		alist_totalOCharges = alist_totalOCharges - x_cost;

		$('#other_charge_row_'+id).remove();
		alist_otherCounter2--;
		removeToDropDownCMALIST("other_charges_cost_"+id);
		$("#alist_total_o_charges").val(alist_totalOCharges.toFixed(2));
		$(".alist_other_charges").html("&#8369; " + addCommas(alist_totalOCharges.toFixed(2)));
		if(alist_otherCounter2 <= 0){
			$('#alist_other_charges_list').hide();
		}
		calculateOverallTotalALIST();
	}
}


var alist_CMCounter 	= 0;
var alist_CMCounter2 	= 0;
var alist_totalCM 	= 0;
function addNewCMALIST() {
	// Get the values
	var apply_to 			= $('#alist_apply').val();
	var modifier_type		= $('input[name=alist_modifier_type]:checked', '#add_invoice_form').val();
	var modify_due_to		= $('#alist_modify_due_to').val();
	var cost_type 			= $('#alist_cost_type').val();
	var cost_modifier		= parseFloat($('#alist_cost_modifier').val());
	var modifier 			= "";
	var a 					= 0;
	var price 				= 0;
	var regimen_cost_input 	= "";
	var log = $("#alist_apply").find('option:selected').data('type');

	if(log == "A-List"){
		if(apply_to == "All Medicines"){
			regimen_cost_input = parseFloat($("#alist_total_input").val());
		} else {
			var key = $("#alist_apply").find('option:selected').data('key');
			regimen_cost_input = parseFloat($("#rpc_per_med_total_price_"+key).val());
		}
	} else {
		if(apply_to == "All Other Charges"){
			regimen_cost_input = parseFloat($("#alist_summary_total_o_charges").val());
		} else {
			var data_id = $("#alist_apply").find('option:selected').data('id');
			regimen_cost_input = parseFloat($("#"+data_id).val());
		}
	}
	
	
	var operatorPercentage = {
			    '+': function(price, b) { return price+b },
			    '-': function(price, b) { return price-b },
			};
	var operatorCash = {
			    '+': function(price, regimen_cost_input) { return price+cost_modifier },
			    '-': function(price, regimen_cost_input) { return price-cost_modifier },
			};
	
	// check if there's value using the hasValue and checkRadio for radio button function which can be found in accounting.js
	if(hasValue(apply_to) && checkRadio(modifier_type) && hasValue(modify_due_to) && hasValue(cost_type) && hasValue(cost_modifier) ) {
		if(!isNaN(cost_modifier)){
			alist_CMCounter++;
			alist_CMCounter2++;

			if(modifier_type == "Mark up"){
				modifier = "+";
			} else {
				modifier = "-";
			}
			
			if(cost_type == "php"){
				price = operatorCash[modifier](price, regimen_cost_input);
			} else {
				a = cost_modifier / 100;
				var b = regimen_cost_input * a;
				price = operatorPercentage[modifier](price, b);
			}

			alist_totalCM = parseFloat(alist_totalCM + price);

			var html="";

			var applies_to		 	= "alist_cm_additional["+alist_CMCounter+"][applies_to]";
			var modifier_type_input	= "alist_cm_additional["+alist_CMCounter+"][modifier_type]";
			var modify_due_to_input = "alist_cm_additional["+alist_CMCounter+"][modify_due_to]";
			var cost_type_input 	= "alist_cm_additional["+alist_CMCounter+"][cost_type]";
			var cost_modifier_input	= "alist_cm_additional["+alist_CMCounter+"][cost_modifier]";
			var total_cost_input	= "alist_cm_additional["+alist_CMCounter+"][total_cost]";
			var cm_cost_type		= "alist_cm_cost_type_" + alist_CMCounter;
			var cm_cost_modifier	= "alist_cm_cost_modifier_" + alist_CMCounter;
			var cost_input_id		= "alist_cm_cost_" + alist_CMCounter;
			var modifier_type_id	= "alist_cm_modifier_" + alist_CMCounter;
			
			var delete_button		= BASE_IMAGE_PATH +"doc_delete.png";
			$('#alist_cm_list').show();
			html += '<tr id="alist_cm_row_'+alist_CMCounter+'">';
			html += '<td><input type="hidden" name="'+ applies_to +'" value="'+apply_to+'">'+apply_to+'</td>';
			html += '<td><input type="hidden" name="'+ modifier_type_input +'" id="'+modifier_type_id+'" value="'+modifier_type+'">'+modifier_type+'</td>';
			html += '<td><input type="hidden" name="'+ modify_due_to_input +'" value="'+modify_due_to+'">'+modify_due_to+'</td>';
			html += '<td><input type="hidden" name="'+ cost_type_input +'" id="'+cm_cost_type+'" value="'+cost_type+'"><input type="hidden" name="'+ cost_modifier_input +'" id="'+cm_cost_modifier+'" value="'+cost_modifier+'">'+cost_modifier+' '+cost_type+'</td>';
			html += '<td><input type="hidden" name="'+ total_cost_input +'" id="' + cost_input_id + '" value="'+price+'">&#8369; '+addCommas(price.toFixed(2))+'</td>';
			html += '<td style="width: 10%;"><ul class="actions"><li><img src="' + delete_button + '" onclick="javascript:removeCMRowALIST('+alist_CMCounter+')" title="Delete"></li></ul></td>';
			html += '</tr>';

			$('#alist_cm_list').append(html);
			$("#alist_total_cm").val(alist_totalCM.toFixed(2));
			$(".alist_total_cm").html("&#8369; " + addCommas(alist_totalCM.toFixed(2)));
			$('#alist_modify_due_to').focus();
			$(".alist_modifier_inputs").val("");
			calculateOverallTotalALIST();
		} else {
			alert("Warning: Cost must be limited to number formatting.")
		}
	} else {
		alert("Warning : Please fill-up the required fields before adding cost modifiers")
	}
}

function removeCMRowALIST(id) {
	
	var q = confirm("Delete Cost Modifier Row?");
	if(q){
		var x_cost = parseFloat($('#alist_cm_cost_'+id).val());
		var modifier = $('#alist_cm_modifier_'+id).val();

		alist_totalCM = alist_totalCM - x_cost;
		
		$('#alist_cm_row_'+id).remove();
		alist_CMCounter2--;
		
		$("#alist_total_cm").val(alist_totalCM.toFixed(2));
		$(".alist_total_cm").html("&#8369; " + addCommas(alist_totalCM.toFixed(2)));
		if(alist_CMCounter2 <= 0){
			$('#alist_cm_list').hide();
		}
		calculateOverallTotalALIST();
	}
	
}


/* CONVERT INVOICE FUNCTIONS */

function calculateOCVAT(){
	var other_charges  	= parseFloat($("#total_o_charges").val());
	var vat = (other_charges / 1.12) * 0.12;
	$(".other_charges_vat").html("&#8369; " + addCommas(vat.toFixed(2)));
	$("#total_o_charges_vat").val(vat.toFixed(2));
	var total = other_charges / 1.12;
	$(".summary_total_other_charges").html("&#8369; " + addCommas(total.toFixed(2)));
	$("#summary_total_o_charges").val(total.toFixed(2));
}

function calculateOverallTotal(){
	calculateOCVAT();

	var regimen_cost   	= parseFloat($("#rpc_meds_total_input").val()); // with vat
	var other_charges  	= parseFloat($("#total_o_charges").val()); // with vat
	/*var regimen_cost   	= parseFloat($("#regimen_cost_input").val()); // no vat
	var other_charges  	= parseFloat($("#summary_total_o_charges").val()); // no vat*/
	var cost_modifiers 	= parseFloat($("#total_cm").val());
	var total 			= (regimen_cost + other_charges + cost_modifiers); // total invoice amount
	
	//  vat 
	var regimen_cost_vat = parseFloat($("#rpc_meds_vat_input").val());
	var other_charges_vat = parseFloat($("#total_o_charges_vat").val());
	// var vat 			= (regimen_cost_vat + other_charges_vat);

	/*CALCULATE VAT */
	var vat 			= (total / 1.12) * 0.12;

	// end of vat
	var t_invoice		= total - vat;
	
	$("#regimen_cost").html("&#8369; " + addCommas(regimen_cost.toFixed(2)));
	$("#total_regimen_cost").val(regimen_cost.toFixed(2));

	$(".other_charges").html("&#8369; " + addCommas(other_charges.toFixed(2)));
	$("#total_other_charges").val(other_charges.toFixed(2));

	$("#cost_modifiers").html("&#8369; " + addCommas(cost_modifiers.toFixed(2)));
	$("#total_cost_modifier").val(cost_modifiers.toFixed(2));

	$("#invoice_amount").html("&#8369; " + addCommas(t_invoice.toFixed(2)));
	$("#net_sales").val(t_invoice.toFixed(2));

	$("#invoice_amount_vat").html("&#8369; " + addCommas(vat.toFixed(2)));
	$("#net_sales_vat").val(vat.toFixed(2));

	$("#total_invoice_amount").html("&#8369; " + addCommas(total.toFixed(2)));
	$("#total_net_sales_vat").val(total.toFixed(2));
}

function calculateOCVATALIST(){
	var other_charges  	= parseFloat($("#alist_total_o_charges").val());
	var vat = other_charges - (other_charges / 1.12);
	$(".alist_other_charges_vat").html("&#8369; " + addCommas(vat.toFixed(2)));
	$("#alist_total_o_charges_vat").val(vat.toFixed(2));
	var total = other_charges / 1.12;
	$(".alist_summary_total_other_charges").html("&#8369; " + addCommas(total.toFixed(2)));
	$("#alist_summary_total_o_charges").val(total.toFixed(2));
}

function calculateOverallTotalALIST(){
	calculateOCVATALIST();

	var regimen_cost   	= parseFloat($("#alist_meds_total_input").val());
	// var regimen_cost   	= parseFloat($("#alist_total_input").val());
	var other_charges  	= parseFloat($("#alist_total_o_charges").val());
	// var other_charges  	= parseFloat($("#alist_summary_total_o_charges").val());
	var cost_modifiers 	= parseFloat($("#alist_total_cm").val());
	var total 			= (regimen_cost + other_charges + cost_modifiers);

	/*var regimen_cost_vat  = parseFloat($("#alist_meds_vat_input").val());
	var other_charges_vat = parseFloat($("#alist_total_o_charges_vat").val());
	var vat 			  = regimen_cost_vat + other_charges_vat;*/

	var vat 			= (total / 1.12) * 0.12;

	var t_invoice		= total - vat;
	
	$("#alist_cost").html("&#8369; " + addCommas(regimen_cost.toFixed(2)));
	$("#total_alist_cost").val(regimen_cost.toFixed(2));

	$(".alist_other_charges_span").html("&#8369; " + addCommas(other_charges.toFixed(2)));
	$("#total_alist_other_charges").val(other_charges.toFixed(2));

	$("#alist_cost_modifiers").html("&#8369; " + addCommas(cost_modifiers.toFixed(2)));
	$("#total_alist_cost_modifier").val(cost_modifiers.toFixed(2));

	$("#alist_invoice_amount").html("&#8369; " + addCommas(total.toFixed(2)));
	$("#alist_net_sales").val(total.toFixed(2));

	$("#alist_invoice_amount_vat").html("&#8369; " + addCommas(vat.toFixed(2)));
	$("#alist_net_sales_vat").val(vat.toFixed(2));

	$("#alist_total_invoice_amount").html("&#8369; " + addCommas(t_invoice.toFixed(2)));
	$("#alist_total_net_sales_vat").val(t_invoice.toFixed(2));
}

function calcualateAlistMedicine(element_id){
	var quantity = $("#alist_quantity_" + element_id).val();
	var price 	 = parseFloat($("#alist_price_" + element_id).val());
	var total    = $("#alist_total_price_" + element_id);
	var total_input    = $("#alist_total_price_input_" + element_id);
	var result 	 = price * quantity;
	// alert(result);
	total.html(addCommas(result.toFixed(2)));
	total_input.val(result);
	// calculateTotalAlistMedicine();
}


/* END CONVERT INVOICE FUNCTION*/

// CONVERT INVOICE VIEW REGIMEN POP up

function loadRegimenPopUP(regimen_id,version_id){
	$.post(base_url + "account_billing/loadRegimenPopUP", {regimen_id:regimen_id, version_id:version_id}, function(o){
		$('#view_regimen_modal_wrapper').html(o);
		$('#view_regimen_modal_wrapper').modal();
	});
}

// END CONVERT INVOICE VIEW REGIMEN POP up


function viewInvoice(invoice_id){
	var invoice_id = parseInt(invoice_id);
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "account_billing/getInvoice", {invoice_id:invoice_id}, function(o){
		$('#main_wrapper_management').html(o);
	});
}

function viewCollection(or_id){
	var or_id = parseInt(or_id);
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "account_billing/getCollectionData", {or_id:or_id}, function(o){
		$('#main_wrapper_management').html(o);
	});
}

function voidInvoice(invoice_id){
	$.post(base_url + "returns_management/LoadVoidForm", {invoice_id:invoice_id}, function(o){
		$('#void_form_wrapper').html(o);
		$('#void_form_wrapper').modal();
	});
}