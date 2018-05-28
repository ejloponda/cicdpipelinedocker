function reset_all_topbars(){
	// remove
	$('.user-topbar').removeClass('hidden');
	$('.user-level-topbar').removeClass('hidden');
	$('.user-add-patient').removeClass('hidden');
	$('.user-sidebar').removeClass('hidden');
	$('.user-welcome').removeClass('hidden');
	$('.patient-view-topbar').removeClass('hidden');
	$('.module_management_topbar').removeClass('hidden');
	$('.module_menu_topbar').removeClass('hidden');
	$('.inventory_menu_topbar').removeClass('hidden');
	$('.account_billing_menu').removeClass('hidden');
	$('.account_billing_add_invoice_menu').removeClass('hidden');
	$('.menu_returns_inv').removeClass('hidden');
	

	// add
	$('.user-welcome').addClass('hidden');
	$('.user-topbar').addClass('hidden');
	$('.user-level-topbar').addClass('hidden');
	$('.user-add-patient').addClass('hidden');
	$('.user-sidebar').addClass('hidden');
	$('.patient-view-topbar').addClass('hidden');
	$('.module_management_topbar').addClass('hidden');
	$('.module_menu_topbar').addClass('hidden');
	$('.inventory_menu_topbar').addClass('hidden');
	$('.account_billing_menu').addClass('hidden');
	$('.account_billing_add_invoice_menu').addClass('hidden');
	$('.menu_returns_inv').addClass('hidden');
	// alert("in");
}

function reset_all(){
	$('.patient_menu').removeClass('hilited');
	$('.regimen_menu').removeClass('hilited');
	$('.inventory_management_menu').removeClass('hilited');
	$('.activity_menu').removeClass('hilited');
	$('.billing_menu').removeClass('hilited');
	$('.module_management_menu').removeClass('hilited');
	$('.firm_admin_users').removeClass('hilited');
	$('.firm_admin_roles').removeClass('hilited');
	$('.firm_admin_permissions').removeClass('hilited');
	$('.reports_menu').removeClass('hilited');
	$('.order_menu').removeClass('hilited');
}

function reset_all_topbars_menu(){
	// Module Management
	$('.patient_info_settings_form').removeClass('sub-hilited');
	$('.medical_history_settings_form').removeClass('sub-hilited');
	$('.regimen_history_settings_form').removeClass('sub-hilited');
	$('.account_billing_settings_form').removeClass('sub-hilited');
	$('.inventory_settings_form').removeClass('sub-hilited');
	$('.returns_settings_form').removeClass('sub-hilited');
	$('.calendar_settings_form').removeClass('sub-hilited');
	
	
	// Inventory Management
	$('.inventory_list_form').removeClass('sub-hilited');
	$('.stock_adjustment_form').removeClass('sub-hilited');
	$('.add_new_inventory_form').removeClass('sub-hilited');
	$('.returns_form').removeClass('sub-hilited');
	$('.inventory_settings_form').removeClass('sub-hilited');
	$('.returns_history_form').removeClass('sub-hilited');


	// 	Accounts and Billing
	$('.sales_report_list').removeClass('sub-hilited');
	$('.accounts_receivable_list').removeClass('sub-hilited');
	$('.collections_list').removeClass('sub-hilited');

	// Invoice
	$('.alist_form_invoice').removeClass('sub-hilited');
	$('.rpc_form_invoice').removeClass('sub-hilited');
}
