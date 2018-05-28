<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "home";
$route['coming_soon'] = "home/coming_soon";
$route['404_override'] = '';
$route['forgot'] = 'login/forgotaccount';
$route['authenticate'] = 'login/module_gateway';
$route['login'] = 'login/user';
$route['logout'] = 'login/user_logout';
$route['forgotaccount'] = 'login/forgotaccount';
$route['welcome'] = 'admin/welcome';



//change the existing routes to this
$route['management/users']			 	= 'admin/index';
$route['management/patient'] 		 	= 'patient_management/index';
$route['management/module'] 		 	= 'module_management/index';
$route['management/permissions']	 	= 'access_permissions/index';
$route['management/inventory']	 		= 'inventory_management/index';
$route['management/regimen']		 	= 'regimen_management/index';
$route['management/billing']			= 'account_billing/index';
$route['management/returns']			= 'returns_management/index';
$route['management/reports']		    = 'reports/index';
$route['management/order']	 		 	= 'orders_management/index';
$route['management/activity_log'] 		= 'activity_log/index';
$route['management/calendar'] 		    = 'calendar_management/index';


$route['download/stock_history/(:any)'] = "patient_management/download_stockHistory/$1";
$route['download/generate_report_date_range/(:any)/(:any)/(:any)'] = "account_billing/download_report_generate_date_range/$1/$2/$3";
$route['download/generate_report_month/(:any)/(:any)'] = "account_billing/download_report_generate_month/$1/$2";
$route['download/generate_report_year/(:any)/(:any)'] = "account_billing/download_report_generate_year/$1/$2";
$route['download/generate_pdf/(:any)'] = "account_billing/download_pdf/$1";
//new
$route['download/generatesummary_pdf/(:any)'] = "account_billing/downloadsummary_pdf/$1";
$route['download/print_version_regimen/(:any)/(:any)'] = "regimen_management/download_version_regimen/$1/$2";
//
$route['download/generate_summary/(:any)/(:any)'] = "regimen_management/download_pdf_summary/$1/$2";

/*Adding route for print_pdf~Patty*/
$route['download/print_pdf/(:any)/(:any)'] = "regimen_management/print_pdf/$1/$2";
$route['download/print_word/(:any)/(:any)'] = "regimen_management/print_word/$1/$2";
$route['download/generate_summary/(:any)'] = "orders_management/download_pdf_summary/$1";
$route['download/generate_invoice/(:any)/(:any)'] = "account_billing/generate_invoice/$1/$2";

/* REPORT GENERATOR */
$route['download/generate_report_patient/(:any)/(:any)'] = "reports/generate_report_patient/$1/$2";
$route['download/generate_report_all_patients/(:any)/(:any)/(:any)'] = "reports/download_report_all_patients/$1/$2/$3";
$route['download/generate_report_all_regimen/(:any)/(:any)/(:any)'] = "reports/download_report_all_regimen/$1/$2/$3";
$route['download/generate_report_regimen/(:any)/(:any)'] = "reports/download_report_regimen/$1/$2";
$route['download/generate_report_inventory/(:any)/(:any)/(:any)/(:any)'] = "reports/download_report_inventory/$1/$2/$3/$4";
$route['download/generate_report_all_inventory/(:any)'] = "reports/download_report_all_inventory/$1";
$route['download/generate_report_per_batch/(:any)/(:any)'] = "reports/download_report_per_batch/$1/$2";
$route['download/generate_report_all_batch/(:any)/(:any)/(:any)/(:any)'] = "reports/download_report_all_batch/$1/$2/$3/$4";
$route['download/generate_report_collection/(:any)/(:any)'] = "reports/download_report_collection/$1/$2";
$route['download/generate_report_all_collection/(:any)/(:any)/(:any)'] = "reports/download_report_all_collections/$1/$2/$3";
$route['download/generate_report_sales/(:any)/(:any)'] = "reports/download_report_sales/$1/$2";
$route['download/generate_report_all_sales/(:any)/(:any)/(:any)'] = "reports/download_report_all_sales/$1/$2/$3";
$route['download/generate_report_all_patient_without_daterange/(:any)'] = "reports/download_report_all_patient_without_daterange/$1";
$route['download/generate_report_all_returns/(:any)/(:any)/(:any)'] = "reports/download_report_all_returns/$1/$2/$3";
$route['download/generate_report_claim_sold_report/(:any)/(:any)/(:any)'] = "reports/download_claim_sold_report/$1/$2/$3";
$route['download/generate_report_event_daterange/(:any)/(:any)/(:any)'] = "reports/download_event_daterange/$1/$2/$3";
$route['download/generate_report_event_per_patient/(:any)/(:any)'] = "reports/download_event_per_patient/$1/$2";
$route['download/generate_report_birthdays/(:any)/(:any)'] = "reports/download_birthdays/$1/$2";

$route['download/generate_report_all_active_patient/(:any)'] = "reports/download_report_all_active_patient/$1";
$route['download/generate_report_all_inactive_patient/(:any)'] = "reports/download_report_all_inactive_patient/$1";

$route['calendar/view2/(:any)/(:any)'] = "calendar_management/view2/$1/$2";
$route['calendar/view3/(:any)'] = "calendar_management/view3/$1";
$route['batching']	 = 'inventory_management/excelReaderBatchUpload';
/* End of file routes.php */
/* Location: ./application/config/routes.php */