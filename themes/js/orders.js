var IS_ADD_ORDER_FORM = false;

function reload_content(fragment) {
	var hash = window.location.hash;

	if(
		IS_ADD_ORDER_FORM
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
	IS_ADD_ORDER_FORM = false;
}

function load_page(hash) {
	$('.module_management_topbar').removeClass('hidden');
	$('#main_wrapper_management').html("");

	if(hash != "#view" && hash != "#edit" && hash != "#invoice" && hash != "#delete"){
		removeSession();
	}

	if(hash == "#list" || hash == "") {	
		loadIndex();
	}else if(hash == "#create"){
		loadCreate();
	}else if(hash == "#view"){
		loadView();
	}else if(hash == "#edit"){
		edit_order();
	}else if(hash == "#invoice"){
		create_invoice();
	}else if(hash == "#delete"){
		delete_order();
	}
}

function removeSession(){
	sessionStorage.removeItem("order_id");
	sessionStorage.removeItem("s_order_id");
	sessionStorage.removeItem("i_order_id");
	// alert(sessionStorage.getItem("order_id"));
}


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
	// loadUserRoleForm();
});

$(".regimen_menu").live('click', function() {
	window.location.href = "regimen";
});

$(".firm_admin_roles").live('click', function() {
	window.location.href = "permissions";
});

$(".calendar_menu").live('click', function() {
	window.location.href = "calendar";
});

$(".billing_menu").live('click', function() {
	window.location.href = "billing";
});

$(".create_new_order").live('click', function() {
	window.location.hash = "create";
	reload_content("create");
});

$(".order_menu").live('click', function(){
	window.location.hash = "list";
	reload_content("list");
});

$(".reports_menu").live('click', function() {
	window.location.href = "reports";
});

$(".activity_log_menu").live('click', function() {
	window.location.href = "activity_log";
});

function loadIndex(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "orders_management/showIndex",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function loadCreate(){
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "orders_management/create",{},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function loadView(id){
	window.location.hash = "view";
	var order_id = 0;
	if(id){
		order_id = parseInt(id);
		sessionStorage.setItem("order_id", order_id);
	} else {
		order_id = sessionStorage.getItem("order_id");
	}
	// alert(order_id);
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "orders_management/view",{order_id:order_id},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function edit_order(id){
	window.location.hash = "edit";
	var s_order_id = 0;
	if(id){
		s_order_id = parseInt(id);
		sessionStorage.setItem("s_order_id", s_order_id);
	} else {
		s_order_id = sessionStorage.getItem("s_order_id");
	}	
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "orders_management/update",{s_order_id:s_order_id},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function delete_order(id){
	window.location.hash = "delete";
	var s_order_id = 0;
	if(id){
		s_order_id = parseInt(id);
		sessionStorage.setItem("s_order_id", s_order_id);
	} else {
		s_order_id = sessionStorage.getItem("s_order_id");
	}	
	$.post(base_url + "orders_management/delete",{s_order_id:s_order_id},function(o){
		$('#delete_order_record').html(o);
		$('#delete_order_record').modal("show");
	});
}


function create_invoice(id){
	window.location.hash = "invoice";
	var i_order_id = 0;
	if(id){
		i_order_id = parseInt(id);
		sessionStorage.setItem("i_order_id", i_order_id);
	} else {
		i_order_id = sessionStorage.getItem("i_order_id");
	}	
	$('#main_wrapper_management').html(default_ajax_loader);
	$.post(base_url + "account_billing/getConvertToInvoice",{i_order_id:i_order_id},function(o){
		$('#main_wrapper_management').html(o);
	});
}

function hasValue(variable) {
	return ($.trim(variable).length > 0 ? true : false);
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

function checkRadio(variable){
	return variable === "undefined" ? false : true;
}

//  ORDER FORM
var med_ctr = 0;
var med_field_ctr = 0;

function addMedicine(){
	$("#medicine_list_wrapper_loader").show();
	var medicine_id = $("#medicine_list").val();
	$.post(base_url+"orders_management/orderCreateFields", {medicine_id:medicine_id,med_ctr:med_ctr}, function(o){
		$("#medicine_list_wrapper_loader").hide();
		if(o.errors){
			alert(o.errors);
		} else {
			med_ctr++;
			med_field_ctr++;
			if(med_field_ctr > 0){ $("#medicine_list_wrapper").show() }
			$("#medicine_list_wrapper").append(o.html);
			$("#medicine_list").val("").trigger('change');
		}
	}, "json");
}

function removeMedicine(div_id,id){

	var confirm = new jBox('Confirm', {
	content: '<h3>Are you sure you want to remove this other charges?</h3>',
	confirmButton: 'Yes',
	cancelButton: 'No',
	confirm: function(){
		$.post(base_url + "orders_management/deleteOtherCharges", {id:id}, function(o){	
			$("."+div_id).remove();
			getTotalPriceMedicine();		
		}, "json");
	},
	cancel: function(){
		
	},
	animation: {open: 'tada', close: 'pulse'}
	});
	confirm.open();
}

function removeMedicineDB(div_id,id){
	var confirm = new jBox('Confirm', {
	content: '<h3>Are you sure you want to remove this medicine?</h3>',
	confirmButton: 'Yes',
	cancelButton: 'No',
	confirm: function(){
		$.post(base_url + "orders_management/deleteOrderMedicine", {id:id}, function(o){
			$("."+div_id).remove();
			getTotalPriceMedicine();		
		}, "json");
	},
	cancel: function(){
		
	},
	animation: {open: 'tada', close: 'pulse'}
	});
	confirm.open();
}

function removeOChargesRow(count){
	var x = confirm("Are you sure you want to remove this?");
	if(x){
		$("#other_charge_row_"+count).remove();
		updateTotalCharges();
	}
}

var otherCounter 	= 0;
var otherCounter2 	= 0;
function addOtherCharges() {
	// Get the values
	var description 		= $('#o_description').val();
	var description_id 		= $('#o_description option:selected').data("id");
	var quantity 			= parseInt($('#o_quantity').val());
	var cost 				= parseFloat($('#o_cost').val());

	if(description == 0){
		alert('Please select other charges');
	}else{
	// check if there's value using the hasValue function which can be found in accounting.js
		if(hasValue(description) && hasValue(quantity) && hasValue(cost) ) {
			if(!isNaN(cost) && !isNaN(quantity)){
				var html="";
				otherCounter++;
				otherCounter2++;
				var total = cost * quantity;
				

				var description_input 		= "additional["+otherCounter+"][description]";
				var description_id_input 	= "additional["+otherCounter+"][description_id]";
				var quantity_input			= "additional["+otherCounter+"][quantity]";
				var cost_item_input 		= "additional["+otherCounter+"][cost_per_item]";
				var cost_input 				= "additional["+otherCounter+"][cost]";
				var cost_input_id			= "other_charges_cost_" + otherCounter;
				var cost_input_class		= "other_charges_cost_class";
				var cost_item_input_id		= "other_charges_per_cost_" + otherCounter;
				var delete_button			= BASE_IMAGE_PATH +"doc_delete.png";
				$('#other_charges_list').show();

				/*html += '<tr id="other_charge_row_'+otherCounter+'">';
				html += '<td><input type="hidden" name="'+ description_input +'" value="'+description+'"><input type="hidden" name="'+ description_id_input +'" value="'+description_id+'">'+description+'</td>';
				html += '<td><input type="hidden" name="'+ quantity_input +'" value="'+quantity+'">'+quantity+'</td>';
				html += '<td><input type="hidden" name="'+ cost_item_input +'" id="' + cost_item_input_id + '" value="'+cost+'">&#8369; '+addCommas(cost.toFixed(2))+'</td>';
				html += '<td><input type="hidden" name="'+ cost_input +'" id="' + cost_input_id + '" value="'+total+'">&#8369; '+addCommas(total.toFixed(2))+'</td>';
				html += '<td style="width: 10%;"><ul class="actions"><li><img src="' + delete_button + '" onclick="javascript:removeOChargesRow('+otherCounter+')" title="Delete"></li></ul></td>';
				html += '</tr>';*/

				html += '<div class="minibox" id="other_charge_row_'+otherCounter+'">';
				html += '	<span id="button-box" onclick="javascript:removeOChargesRow('+otherCounter+')">X</span>';
				html += '	<span id="med01">';
				html += '		<span class="title"><input type="hidden" name="'+ description_input +'" value="'+description+'"><input type="hidden" name="'+ description_id_input +'" value="'+description_id+'">'+description+'</span>';
				html += '		<br><br>&nbsp;&nbsp;&nbsp;';
				html += '		<span>Quantity: <input type="hidden" name="'+ quantity_input +'" value="'+quantity+'">'+quantity+' / <input type="hidden" name="'+ cost_item_input +'" id="' + cost_item_input_id + '" value="'+cost+'">P '+addCommas(cost.toFixed(2));
				html += '		<span class="price">Php:&nbsp;';
				html += '			<span class="pricecolor"><input type="hidden" class="'+ cost_input_class +'" name="'+ cost_input +'" id="' + cost_input_id + '" value="'+total+'"> '+addCommas(total.toFixed(2))+'</span>';
				html += '		</span>';
				html += '	</span>';
				html += '</div>';

				$('#other_charges_list').append(html);
				$('.other_charges_main').val("");
				$('#o_description').focus();
				updateTotalCharges();
			} else {
				alert("Warning: Quantity / Cost must be limited to number formatting.")
			}
		} else {
			alert("Warning : Please fill-up the required fields before adding other charges.")
		}
	}
}

function wordwrap( str, width, brk, cut ) {
     brk = brk || '\n';
     width = width || 75;
     cut = cut || false;

     if (!str) { return str; }

     var regex = '.{1,' +width+ '}(\\s|$)' + (cut ? '|.{' +width+ '}|.+$' : '|\\S+?(\\s|$)');

     return str.match( RegExp(regex, 'g') ).join( brk );
}

function getPatientDetails(patient_id){
	var patient_id = parseInt(patient_id);
	$.post(base_url + "regimen_management/getPatientDetails", {patient_id:patient_id}, function(o){
		
		$("#patient_code").html(o.output['patient_code']);
		$('#credit').html(o.output['credit']);
		$('#age').html(o.output['age']);
		if(o.doctors['id'] == null || o.doctors['id'] == NaN){
			$('#attending_doctor ').select2('val',"-Select-");
		}else{
			$('#attending_doctor').prop('selectedIndex', 0);
			$('#attending_doctor').select2('data', o.doctors);	
		}

		if(o.output['base_path']){
			// var src = BASE_IMAGE_PATH + o.output['base_path'] + o.output['filename'] + "." + o.output['extension'];
			var src = BASE_IMAGE_PATH + o.output['filename'] + "." + o.output['extension'];
		} else {
			var src = BASE_IMAGE_PATH+"photo.png";
		}
		
		$(".photoID").attr("src", src);
	}, "json");	

}

function getTotalPriceMedicine(){
	var total_price = 0;
	if($(".medicine_total_price_class").length > 0){
		$(".medicine_total_price_class").each(function(){
			total_price += parseFloat($(this).val());
		});
	}
	
	// alert(addCommas(total_price.toFixed(2)));
	$("#total_medicine_price").html(addCommas(total_price.toFixed(2)));

	if(total_price == 0){
		// $("#regimen_gross_wrapper").hide();
	} else {
		$("#regimen_gross_wrapper").show();
	}
}

function updateTotalCharges(){
	var totalOCharges 	= 0;

	if($(".other_charges_cost_class").length > 0){
		$(".other_charges_cost_class").each(function(){
			totalOCharges += parseFloat($(this).val());
		});
	}
	$("#total_other_charges_price").html(addCommas(totalOCharges.toFixed(2)));
}

/*
	THIS IS FOR INVOICE
*/

var CMCounter 	= 0;
var CMCounter2 	= 0;
var totalCM 	= 0;
function addNewCM() {
	// Get the values
	var apply_to 			= $('#apply').val();
	var modifier_type		= $('input[name=modifier_type]:checked', '#add_invoice_form').val();
	var modify_due_to		= $('#modify_due_to').val();
	var cost_type 			= $('#cost_type').val();
	var cost_modifier		= parseFloat($('#cost_modifier').val());
	var modifier 			= "";
	var a 					= 0;
	var price 				= 0;
	var regimen_cost_input 	= parseFloat($("#apply").find('option:selected').data('price'));
	var log 				= $("#apply").find('option:selected').data('type');

	/*if(log == 1){
		var medicine = 1;
	}else{
		var medicine = 0;
	}*/
	
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
			html += '<td class = "is_medicine"><input type="hidden" name="'+ is_medicine +'" value="'+log+'"></td>';
			html += '</tr>';

			$('#cm_list').append(html);
			
			setCMValues(totalCM);

			$('#modify_due_to').focus();
			$(".modifier_inputs").val("");
		} else {
			alert("Warning: Cost must be limited to number formatting.")
		}
	} else {
		alert("Warning : Please fill-up the required fields before adding cost modifiers")
	}
}

function setCMValues(totalCM){
	$("#total_cm").val(totalCM.toFixed(2));
	$(".total_cm").html(addCommas(totalCM.toFixed(2)));
	$("#cost_modifiers").html(addCommas(totalCM.toFixed(2)));
	$("#total_cost_modifier").val(totalCM.toFixed(2));
	calculateTotalInvoice();
}

function removeCMRow(id) {
	
	var q = confirm("Delete Cost Modifier Row?");
	if(q){
		var x_cost = parseFloat($('#cm_cost_'+id).val());
		var modifier = $('#cm_modifier_'+id).val();

		totalCM = totalCM - x_cost;
		
		$('#cm_row_'+id).remove();
		CMCounter2--;
		
		setCMValues(totalCM);

		if(CMCounter2 <= 0){
			$('#cm_list').hide();
		}
	}
	calculateTotalInvoice();
	
}

function calculateTotalInvoice(){
	
	var regimen 		= parseFloat($("#total_regimen_cost").val());
	var other_charges 	= parseFloat($("#total_other_charges").val());
	var modifiers 		= parseFloat($("#total_cost_modifier").val());
	var total 			= (regimen + other_charges + modifiers);
	var vat 			= (total / 1.12) * 0.12;
	var t_invoice		= total - vat;

	$("#total_net_sales_vat").val(total.toFixed(2));
	$("#net_sales_vat").val(vat.toFixed(2));
	$("#net_sales").val(t_invoice.toFixed(2));

	// HTML
	$("#total_invoice_amount").html(addCommas(total.toFixed(2)));
	$("#invoice_amount_vat").html(addCommas(vat.toFixed(2)));
	$("#invoice_amount").html(addCommas(t_invoice.toFixed(2)));
}