<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Returns_Management extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Manila');
    }

    public function index()
    {
        if (!$this->isUserLoggedIn()) {
            redirect('authenticate');
        } else {
            Engine::appStyle('RPC-style.css');
            Engine::appStyle('forms-style.css');
            Engine::appStyle('main.css');
            Engine::appStyle('bootstrap.min.css');

            Engine::appScript('returns.js');
            Engine::appScript('nf.js');
            Engine::appScript('topbars.js');
            Engine::appScript('confirmation.js');
            Engine::appScript('profile.js');
            Engine::appScript('blockUI.js');

            /* NOTIFICATIONS */
            Jquery::pusher();
            Jquery::gritter();
            Jquery::pnotify();
            Engine::appScript('notification.js');
            /* END */

            Jquery::select2();
            Jquery::datatable();
            Jquery::form();
            Jquery::inline_validation();
            Jquery::tipsy();

            Bootstrap::datepicker();
            Bootstrap::modal();

            $data['page_title'] = 'Returns Management';
            $data['session'] = $session = $this->session->all_userdata();
            $data['user'] = $user = User::findById(array('id' => $this->encrypt->decode($session['user_id'])));
            $user_type_id = User_Type::findByName(array('user_type' => $user['account_type']));
            $data['roles'] = RPC_User_Access_Permission::findPermissionByUserType(array('user_type_id' => $user_type_id['id']));

            /* PATIENT MANAGEMENT */
            $data['pm_fmh'] = RPC_User_Access_Permission::findPermissionByUserTypeID(array('user_type_id' => $user_type_id['id'], 'user_module_id' => 1));
            $data['pm_pi'] = RPC_User_Access_Permission::findPermissionByUserTypeID(array('user_type_id' => $user_type_id['id'], 'user_module_id' => 2));
            $data['pm_pmh'] = RPC_User_Access_Permission::findPermissionByUserTypeID(array('user_type_id' => $user_type_id['id'], 'user_module_id' => 3));

            $data['om_order'] = RPC_User_Access_Permission::findPermissionByUserTypeID(array('user_type_id' => $user_type_id['id'], 'user_module_id' => 29));

            /* MODULE MANAGEMENT */
            $data['mm_dc'] = RPC_User_Access_Permission::findPermissionByUserTypeID(array('user_type_id' => $user_type_id['id'], 'user_module_id' => 6));
            $data['mm_dt'] = RPC_User_Access_Permission::findPermissionByUserTypeID(array('user_type_id' => $user_type_id['id'], 'user_module_id' => 7));

            /* MANAGE USERS */
            $data['mu_default'] = RPC_User_Access_Permission::findPermissionByUserTypeID(array('user_type_id' => $user_type_id['id'], 'user_module_id' => 4));
            $data['mu_roles'] = RPC_User_Access_Permission::findPermissionByUserTypeID(array('user_type_id' => $user_type_id['id'], 'user_module_id' => 5));
            $data['mu_ms'] = RPC_User_Access_Permission::findPermissionByUserTypeID(array('user_type_id' => $user_type_id['id'], 'user_module_id' => 12));

            /* INVENTORY MANAGEMENT */
            $data['invent'] = RPC_User_Access_Permission::findPermissionByUserTypeID(array('user_type_id' => $user_type_id['id'], 'user_module_id' => 13));
            $data['returns'] = RPC_User_Access_Permission::findPermissionByUserTypeID(array('user_type_id' => $user_type_id['id'], 'user_module_id' => 14));
            $data['im_sa'] = RPC_User_Access_Permission::findPermissionByUserTypeID(array('user_type_id' => $user_type_id['id'], 'user_module_id' => 21));

            /* REGIMEN CREATOR */
            $data['rc_reg'] = RPC_User_Access_Permission::findPermissionByUserTypeID(array('user_type_id' => $user_type_id['id'], 'user_module_id' => 15));

            /*ACCOUNT AND BILLING*/
            $data['accounting'] = RPC_User_Access_Permission::findPermissionByUserTypeID(array('user_type_id' => $user_type_id['id'], 'user_module_id' => 22));

            /*REPORTS GENERATOR*/
            $data['reps'] = RPC_User_Access_Permission::findPermissionByUserTypeID(array('user_type_id' => $user_type_id['id'], 'user_module_id' => 27));

            $data['username'] = $session['firstname'];
            $this->load->view('returns/index', $data);
        }
    }

    public function getIndex()
    {
        Engine::XmlHttpRequestOnly();
        $session = $this->session->all_userdata();
        $user = User::findById(array('id' => $this->encrypt->decode($session['user_id'])));
        $user_type_id = User_Type::findByName(array('user_type' => $user['account_type']));

        $data['rets'] = $returns = RPC_User_Access_Permission::findPermissionByUserTypeID(array('user_type_id' => $user_type_id['id'], 'user_module_id' => 23));
        if ($returns['can_view'] || $returns['can_add'] || $returns['can_update'] || $returns['can_delete']) {
            $this->load->view('returns/returns', $data);
        } else {
            $this->load->view('404');
        }
    }

    public function LoadVoidForm()
    {
        Engine::XmlHttpRequestOnly();
        $post = $this->input->post();
        $invoice_id = $post['invoice_id'];
        $invoice = Invoice::findById(array('id' => $invoice_id));
        if ($invoice) {
            $data['invoice_id'] = $invoice_id;
            $data['invoice'] = $invoice;
            $this->load->view('returns/modals/void_form', $data);
        } else {
            $this->load->view('404');
        }
    }

    public function voidInvoice()
    {
        Engine::XmlHttpRequestOnly();
        $post = $this->input->post();

        $session = $this->session->all_userdata();
        $user_id = $this->encrypt->decode($session['user_id']);

        $invoice_id = $post['invoice_id'];
        $invoice = Invoice::findById(array('id' => $invoice_id));
        if ($invoice) {
            $record = array(
                'status' => 'Void',
                'remarks' => $post['remarks'],
                'last_update_by' => $user_id,
                );

            Invoice::save($record, $invoice_id);

            //$meds = Invoice_Med::findAllByInvoiceId(array("invoice_id" => $invoice['id']));
            $meds = Reserved_Meds::findAllByOrderId(array('id' => $invoice['order_id']));
            $credit = 0;
            foreach ($meds as $key => $value) {
                unset($inv);
                unset($stock);

                $record = array(
                    'taken' => '2',
                );
                Reserved_Meds::save($record, $value['id']);

                $first_batch = Inventory_Batch::findLatestBatch(array('id' => $value['med_id']));
                if (empty($first_batch)) {
                    $first_batch = Inventory_Batch::findLatestEqualBatch(array('id' => $value['med_id']));
                }

                $batch_qty = (int) $first_batch['quantity'];
                $available = (int) $first_batch['total_quantity'] - (int) $first_batch['quantity'];

                $qty = (int) $batch_qty + (int) $value['quantity'];

                $rec = array(
                        'total_quantity' => (int) $qty,
                        'quantity' => (int) $qty,
                    );
                Inventory_Batch::save($rec, $first_batch['id']);

                $stock = array(
                        'item_id' => $value['med_id'],
                        'patient_id' => $order['patient_id'],
                        'quantity' => $qty,
                        'reason' => 'Returned Item in Batch No.'.''.$first_batch['batch_no'],
                        'created_by' => $user_id,
                        'created_at' => date('Y-m-d H:i:s'),
                        );
                Stock::save($stock);
                $credit = $credit + $value['total_price'];
            }

            $sum = Inventory_Batch::computeTotalQuantity(array('id' => $value['med_id']));
            $main_inv = array(
                        'total_quantity' => $sum,
                    );
            Inventory::save($main_inv, $value['med_id']);

            /*$returns = array(
                    "invoice_id" 		=> $invoice_id,
                    "patient_id" 		=> $invoice['patient_id'],
                    "credit"			=> $credit,
                    "reason_of_return" 	=> "Void Invoice",
                    "date_return" 		=> date("Y-m-d"),
                    "status"			=> "Accepted",
                    "date_created"		=> date("Y-m-d H:i:s"),
                    "last_update_by"	=> $user_id
                );
            $returns_id = Returns::save($returns);

            foreach ($meds as $key => $value) {
                $returns_meds = array(
                        "returns_id"  => $returns_id,
                        "medicine_id" => $value['med_id'],
                        "quantity"	  => $value['quantity']
                    );

                Returns_Meds::save($returns_meds);
            }*/

            $user = User::findById(array('id' => $user_id));
            $act_tracker = array(
                'module' => 'rpc_orders',
                'user_id' => $user_id,
                'entity_id' => '0',
                'message_log' => $user['lastname'].','.$user['firstname'].' '."void Invoice {$invoice['invoice_internal']}",
                'date_created' => date('Y-m-d H:i:s'),
            );

            Activity_Tracker::save($act_tracker);

            /*	$new_credit = array(
                    "credit" => $credit
                    );

                Patients::save($new_credit, $invoice['patient_id']);*/

            $json['is_successful'] = true;
            $json['invoice_id'] = $invoice_id;
        } else {
            $json['is_successful'] = false;
        }

        echo json_encode($json);
    }

    public function void_updateInvoice()
    {
        Engine::XmlHttpRequestOnly();
        $post = $this->input->post();

        $session = $this->session->all_userdata();
        $user_id = $this->encrypt->decode($session['user_id']);

        $invoice_id = $post['invoice_id'];
        $invoice = Invoice::findById(array('id' => $invoice_id));
        $order = Orders::findById(array('id' => $invoice['order_id']));

        if ($post['invoice_id']) {
            $record = array(
                'status' => 'Pending',
                'created_by' => $user_id,
                );
            Orders::save($record, $order['id']);

            $invoice = array(
                'status' => 'Void',
                'remarks' => $post['remarks'],
                'last_update_by' => $user_id,
                );
            Invoice::save($invoice, $invoice_id);

            /*$record = array(
                "taken" => "2",
            );
            Reserved_Meds::save($record, $order['id']);*/

            $all_reserved = Reserved_Meds::findAllByOrderId(array('id' => $order['id']));

            foreach ($all_reserved as $key => $value) {
                $record = array(
                    'taken' => '2',
                );
                Reserved_Meds::save($record, $value['id']);
            }
        }
    }

    public function loadReturnsTables()
    {
        Engine::XmlHttpRequestOnly();

        $get = $this->input->get();
        $session = $this->session->all_userdata();

        $user_type_id = User_Type::findByName(array('user_type' => $session['account_type']));
        $im_list = RPC_User_Access_Permission::findPermissionByUserTypeID(array('user_type_id' => $user_type_id['id'], 'user_module_id' => 14));

        if ($get) {
            $sorting = (int) $get['iSortCol_0'];
            $query = $get['sSearch'];
            // $order_type	= strtoupper($get['sSortDir_0']);
            $order_type = strtoupper('desc');

            $display_start = (int) $get['iDisplayStart'];

            // header fields
            $rows = array(
                0 => 'date_return',
                1 => 'patient_name',
                2 => 'invoice_num',
                3 => 'status',
            );

            $fields = array(
                'a.id',
                'a.invoice_id',
                'a.patient_id',
                'b.invoice_num',
                'c.patient_name',
                'a.date_return',
                'a.status',
            );

            $order_by = $rows[$sorting]." {$order_type}";
            $limit = $display_start.', 50';

            $params = array(
                'search' => $query,
                'fields' => $fields,
                'order' => $order_by,
                'limit' => $limit,
             );

            $returns = Returns::generateReturnsDatatable($params);
            $total_records = Returns::countReturnsDatatable($params);

            $output = array(
                'sEcho' => $get['sEcho'],
                'iTotalRecords' => $total_records,
                'iTotalDisplayRecords' => $total_records,
                'aaData' => array(),
            );

            foreach ($returns as $key => $value):

                if ($value['status'] == 'Pending') {
                    $color = '<span style="color: blue">';
                } elseif ($value['status'] == 'Accepted') {
                    $color = '<span style="color: green;">';
                } else {
                    $color = '<span style="color: red;">';
                }

            $link = '<a href="javascript: void(0);" onClick="javascript: viewReturns('.$value['id'].')">'.$value['invoice_num'].'</a>';

            $row = array(
                    '0' => $color.$link.'</span>',
                    '1' => $color.$value['patient_name'].'</span>',
                    '2' => $color.$value['date_return'].'</span>',
                    '3' => $color.$value['status'].'</span>',
                );

            $output['aaData'][] = $row;

            endforeach;
        } else {
            $output = array(
                'sEcho' => $get['sEcho'],
                'iTotalRecords' => 0,
                'iTotalDisplayRecords' => 0,
                'aaData' => array(),
            );
        }

        echo json_encode($output);
    }

    public function loadReturnsView()
    {
        Engine::XmlHttpRequestOnly();
        $post = $this->input->post();
        $returns_id = $post['returns_id'];

        $returns = Returns::findById(array('id' => $returns_id));

        if ($returns) {
            $data['returns'] = $returns;
            $data['invoice'] = $invoice = Invoice::findById(array('id' => $returns['invoice_id']));
            //debug_array($invoice['invoice_num']);
            $meds = Returns_Meds::findAllByReturnsId(array('returns_id' => $returns_id));

            //Check if invoice is All Medicine
            $all_med = Invoice_Cost_Modifier::findByInvoiceIdandModifyDueTo(array('invoice_id' => $invoice['id']));

            if ($all_med['cost_type'] == 'php') {
                $all_med = '';
            }

            foreach ($meds as $key => $value) {
                $a = Inventory::findById(array('id' => $value['medicine_id']));
                $dosage = Dosage_Type::findById(array('id' => $a['dosage_type']));
                $quantity_type = Quantity_Type::findbyId(array('id' => $a['quantity_type']));

                $cost_modifier = Invoice_Cost_Modifier::findByInvoiceId(array('invoice_id' => $invoice['id'], 'applies_to' => $a['medicine_name']));
                $invoice_med = Invoice_Med::findByInvoiceIdandMedicineId(array('id' => $invoice['id'], 'medicine_id' => $value['medicine_id']));

                $rpc_meds[] = array(
                    'medicine_id' => $value['medicine_id'],
                    'medicine_name' => $a['medicine_name'],
                    'dosage' => $a['dosage'],
                    'dosage_type' => $dosage['abbreviation'],
                    'price' => $invoice_med['price'],
                    'quantity' => $value['quantity'],
                    'quantity_type' => $quantity_type['abbreviation'],
                    'cost_modifier' => !empty($all_med) ? '20' : $cost_modifier['cost_modifier'],
                    'cost_type' => !empty($all_med) ? '%' : $cost_modifier['cost_type'],
                    'modify_due_to' => !empty($all_med) ? 'Senior Discount' : $cost_modifier['modify_due_to'],
                );
            }

            $data['medicines'] = $rpc_meds;

            $data['photo'] = $patient_photo = Patient_Photos::findbyId(array('patient_id' => $returns['patient_id']));
            $data['patient'] = $patient = Patients::findById(array('id' => $returns['patient_id']));
            $session = $this->session->all_userdata();
            $user = User::findById(array('id' => $this->encrypt->decode($session['user_id'])));
            $user_type_id = User_Type::findByName(array('user_type' => $user['account_type']));
            $data['rets'] = $returns = RPC_User_Access_Permission::findPermissionByUserTypeID(array('user_type_id' => $user_type_id['id'], 'user_module_id' => 23));
            $this->load->view('returns/view_returns', $data);
        } else {
            $this->load->view('404');
        }
    }

    public function loadAcceptanceForm()
    {
        Engine::XmlHttpRequestOnly();
        $post = $this->input->post();
        $returns_id = $post['returns_id'];
        $returns = Returns::findById(array('id' => $returns_id));

        if ($returns) {
            $data['returns_id'] = $returns_id;
            $this->load->view('returns/modals/acceptance', $data);
        } else {
            $this->load->view('404');
        }
    }

    public function saveAcceptDeclineReturns()
    {
        Engine::XmlHttpRequestOnly();
        $post = $this->input->post();

        $session = $this->session->all_userdata();
        $user_id = $this->encrypt->decode($session['user_id']);

        $returns_id = $post['returns_id'];

        $returns = Returns::findById(array('id' => $returns_id));
        $invoice = Invoice::findById(array('id' => $returns['invoice_id']));
        $invoice_receipt = Invoice_Receipts::findByInvoiceId(array('invoice_id' => $returns['invoice_id']));
        if ($returns) {
            if ($post['type'] == 'Accepted') {
                $return = Returns_Meds::findAllByReturnsId(array('returns_id' => $returns_id));

                foreach ($return as $key => $value) {
                    $i = 0;
                    $first_batch = Inventory_Batch::findEarlirestMedicine(array('id' => $value['medicine_id']));
                    if (empty($first_batch)) {
                        $first_batch = Inventory_Batch::findEarlirestMedicineZero(array('id' => $value['medicine_id']));
                        $rec2 = array(
                                        'total_quantity' => $first_batch[$i]['total_quantity'] + $value['quantity'],
                                    );
                        Inventory_Batch::save($rec2, $first_batch[$i]['id']);
                    }
                    $new_qty = $value['quantity'];
                    while ($i < count($first_batch)) {
                        $batch_qty = (int) $first_batch[$i]['quantity'];
                        $available = (int) $first_batch[$i]['total_quantity'] - (int) $first_batch[$i]['quantity'];

                        if ($new_qty < 0) {
                            $new_qty = str_replace('-', '', $new_qty);
                        }

                        $new_qty = $available - $new_qty;

                        $quantity = $new_qty >= 0 ? ($available - $new_qty) + $batch_qty : $batch_qty + $available;

                        $rec = array(
                                        'quantity' => $quantity,
                                    );
                        Inventory_Batch::save($rec, $first_batch[$i]['id']);

                        $stock = array(
                                        'item_id' => $value['medicine_id'],
                                        'patient_id' => $returns['patient_id'],
                                        'quantity' => $value['quantity'],
                                        'reason' => 'Returned Item in Batch No.'.''.$first_batch[$i]['batch_no'],
                                        'created_by' => $user_id,
                                        'created_at' => date('Y-m-d H:i:s'),
                                        );
                        Stock::save($stock);

                        ++$i;
                        if ($new_qty >= 0) {
                            break;
                        }
                    }	// end while

                    $sum = Inventory_Batch::computeTotalQuantity(array('id' => $value['medicine_id']));
                    $main_inv = array(
                                'total_quantity' => $sum,
                            );
                    Inventory::save($main_inv, $value['medicine_id']);
                }

                $patient = Patients::findById(array('id' => $returns['patient_id']));
                $pat = array(
                    'credit' => $patient['credit'] + $returns['credit'],
                    'updated_at' => date('Y-m-d H:i:s'),
                    'last_update_by' => $user_id,
                    );
                Patients::save($pat, $returns['patient_id']);

                $history = array(
                    'patient_id' => $patient['id'],
                    'credit' => $returns['credit'],
                    'remarks' => 'Return Meds from '.$invoice_receipt['or_number'],
                    'type' => 'Add',
                    'date_created' => date('Y-m-d H:i:s'),
                    'created_by' => $user_id,
                    );
                Patients_Credit_History::save($history);

                $record = array(
                    'status' => $post['type'],
                    'remarks' => $post['remarks'],
                );

                Returns::save($record, $returns_id);

                $user = User::findById(array('id' => $user_id));
                $act_tracker = array(
                'module' => 'rpc_orders',
                'user_id' => $user_id,
                'entity_id' => '0',
                'message_log' => $user['lastname'].','.$user['firstname'].' '."has returned Invoice No.{$invoice['invoice_internal']}",
                'date_created' => date('Y-m-d H:i:s'),
            );
                Activity_Tracker::save($act_tracker);
            } else {
                $record = array(
                    'status' => $post['type'],
                    'remarks' => $post['remarks'],
                );

                Returns::save($record, $returns_id);
            }

            $json['is_successful'] = true;
            $json['returns_id'] = $returns_id;
        } else {
            $json['is_successful'] = false;
        }
        echo json_encode($json);
    }

    public function loadAddReturnsForm()
    {
        Engine::XmlHttpRequestOnly();
        $post = $this->input->post();
        $invoice_id = $post['invoice_id'];
        $invoice = Invoice::findById(array('id' => $invoice_id));

        if ($invoice) {
            $data['invoice_id'] = $invoice_id;
            $data['invoice'] = $invoice;
            $meds = Invoice_Med::findAllByInvoiceId(array('invoice_id' => $invoice['id']));

            $medicine_name = Inventory::findById(array('id' => $meds['medicine_id']));

            //Check if invoice is All Medicine
            $all_med = Invoice_Cost_Modifier::findByInvoiceIdandModifyDueTo(array('invoice_id' => $invoice['id']));

            if ($all_med['cost_type'] == 'php') {
                $all_med = '';
            }

            /*foreach ($meds as $key => $value) {
                $array[] = $value['invoice_id'];
                $array1[] = $value['medicine_id'];
            }
            $data['meds_summary'] = $meds_summary = array_unique($array);
            $data['meds_summary'] = $meds_summary1 = array_unique($array1);*/

            /*foreach ($meds_summary as $a => $b) {
                foreach ($meds_summary1 as $c => $d) {*/
            foreach ($meds as $key => $value) {
                $all_ids[] = $value['medicine_id'];
            }

            $med_ids = array_keys(array_flip($all_ids));

            foreach ($med_ids as $key => $value) {
                $medicine_quantity = 0;
                foreach ($meds as $k => $val) {
                    if ($value == $val['medicine_id']) {
                        $price = $val['price'];
                        $medicine_quantity += $val['quantity'];
                        if ($val['id']) {
                            $id = $val['id'];
                        }
                    }
                }

                $medic[] = array(
                                'id' => $id,
                                'medicine_id' => $value,
                                'quantity' => $medicine_quantity,
                                'price' => $price,
                                'total_price' => number_format($price * $medicine_quantity, 2, '.', ''),
                            );
                unset($id);
            }
            foreach ($medic as $key => $value) {
                $meds = Invoice_Med::findTotalQuantity(array('invoice_id' => $invoice_id, 'medicine_id' => $value['medicine_id']));
                $inv = Inventory::findById(array('id' => $value['medicine_id']));
                $qty_type = Quantity_Type::findById(array('id' => $inv['quantity_type']));
                $dosage = Dosage_Type::findById(array('id' => $inv['dosage_type']));

                $cost_modifier = Invoice_Cost_Modifier::findByInvoiceId(array('invoice_id' => $invoice_id, 'applies_to' => $inv['medicine_name']));

                $cm = $cost_modifier['cost_modifier'];
                $ct = $cost_modifier['cost_type'];
                if ($cost_modifier['cost_type'] == 'php') {
                    $ct = '';
                    $cm = '';
                }

                $rpc_meds[] = array(
                        'id' => $value['id'],
                        'medicine_id' => $value['medicine_id'],
                        'batch_id' => $value['batch_id'],
                        'medicine_name' => $inv['medicine_name'],
                        'dosage' => $inv['dosage'],
                        'dosage_type' => $dosage['abbreviation'],
                        'price' => $value['price'],
                        'quantity' => $value['quantity'],
                        'quantity_type' => $qty_type['abbreviation'],
                        'total_price' => $value['total_price'],
                        'cost_modifier' => !empty($all_med) ? '20' : $cm,
                        'cost_type' => !empty($all_med) ? '%' : $ct,
                        'modify_due_to' => !empty($all_med) ? 'Senior Discount' : $cost_modifier['modify_due_to'],
                    );
            }
            //}
            /*foreach ($meds as $key => $value) {
                $a = Inventory::findById(array("id" => $value['medicine_id']));
                $dosage = Dosage_Type::findById(array("id" => $a['dosage_type']));
                $quantity_type = Quantity_Type::findbyId(array("id" => $a['quantity_type']));
                $rpc_meds[] = array(
                    "id"			=> $value['id'],
                    "medicine_id" 	=> $value['medicine_id'],
                    "medicine_name"	=> $a['medicine_name'],
                    "dosage" 		=> $a['dosage'],
                    "dosage_type"	=> $dosage['abbreviation'],
                    "price"			=> $value['price'],
                    "quantity"		=> $value['quantity'],
                    "total_price"	=> $value['total_price'],
                    "quantity_type"	=> $quantity_type['abbreviation']
                );
            }*/
            $data['medicines'] = $rpc_meds;
            $data['photo'] = $patient_photo = Patient_Photos::findbyId(array('patient_id' => $invoice['patient_id']));
            $data['patient'] = $patient = Patients::findById(array('id' => $invoice['patient_id']));

            $this->load->view('returns/forms/add', $data);
        } else {
            $this->load->view('404');
        }
    }

    public function LoadReturnsModalForm()
    {
        Engine::XmlHttpRequestOnly();
        // $invoice = Invoice::findAllPaid();
        $limit = 10;

        // check if the post page is null, return 0
        // check if page - 1 is not -1 or lower else return 0
        $offset = $this->input->post('page') ? ($this->input->post('page') - 1 < 0 ? 0 : $this->input->post('page') - 1) : 0;
        $invoice = Invoice::findReturn('id, invoice_num', $limit, $offset, $this->input->post('q'));
        $data['invoice'] = $invoice;

        // if post request and return type is json, the app will return the json
        // encoded version back to the select2
        if ($this->input->post('return_type') == 'json') {
            // remaps the values that will be compatible with select2 default data format
            $data['invoice'] = array_map(function ($row) {
                return [
                    'id' => $row['id'],
                    'text' => $row['invoice_num'],
                ];
            }, $data['invoice']);

            $data['total'] = count(Invoice::findReturn('id', null, 0, $this->input->post('q')));
            echo json_encode($data);

            return;
        }

        // this will return the modals choose when initializing the popup
        $this->load->view('returns/modals/choose', $data);
    }

    public function checkReturn()
    {
        Engine::XmlHttpRequestOnly();
        $post = $this->input->post();

        $invoice = Invoice::findById(array('id' => $post['invoice_list']));
        $return = Returns::findByInvoiceId(array('invoice_id' => $invoice['id']));

        if (!empty($return)) {
            echo '<center><b style = color: red;> Oops! Invoice Already exist </b></center>';
        }
    }

    public function submitModalForm()
    {
        Engine::XmlHttpRequestOnly();
        $post = $this->input->post();

        $invoice_id = $post['invoice_id'];

        $invoice = Invoice::findById(array('id' => $invoice_id));

        if ($invoice) {
            $json['is_successful'] = true;
            $json['invoice_id'] = $invoice_id;
        } else {
            $json['is_successful'] = false;
        }
        echo json_encode($json);
    }

    public function saveReturns()
    {
        Engine::XmlHttpRequestOnly();
        $post = $this->input->post();
        //debug_array($post);
        $session = $this->session->all_userdata();
        $user_id = $this->encrypt->decode($session['user_id']);

        if ($post) {
            $credit = 0;
            foreach ($post['medicine'] as $key => $value) {
                //if($value['modify_due_to'] == 'Senior Discount'){
                if ($value['cost_type'] == '%') {
                    $total_amount = $value['quantity'] * $value['price'];
                    $convert_percent = $value['cost_modifier'] / 100;
                    $discount = $total_amount * $convert_percent;
                    $all_credit = $total_amount - $discount;
                    $credit = $credit + $all_credit;
                } elseif ($value['cost_type'] == 'php') {
                    $total_amount = $value['quantity'] * $value['price'];
                    $all_credit = $total_amount - $value['cost_modifier'];
                    $credit = $credit + $all_credit;
                } else {
                    $total_amount = $value['quantity'] * $value['price'];
                    $credit = $credit + $total_amount;
                }
                /*}else{
                    $total_amount = $value['quantity'] * $value['price'];
                    $credit = $credit + $total_amount;
                }*/

                /*if($value['cost_type'] == "%"){
                    $total_amount = $value['quantity'] * $value['price'];
                    $convert_percent = $value['cost_modifier'] / 100;
                    $discount = $total_amount * $convert_percent;
                    $all_credit = $total_amount - $discount;
                    $credit = $credit + $all_credit;

                }else if($value['cost_type'] == "php"){
                    $total_amount = $value['quantity'] * $value['price'];
                    $all_credit = $total_amount - $value['cost_modifier'];
                    $credit = $credit + $all_credit;

                }else{*/
                    //$total_amount = $value['quantity'] * $value['price'];
                    //$credit = $credit + $total_amount;

                //}
            }
            $total_credit = $credit - $post['discounted_amt'];
            $returns = array(
                    'invoice_id' => $post['invoice_id'],
                    'patient_id' => $post['patient_id'],
                    'credit' => $value['modify_due_to'] == 'Senior Discount' ? $credit : $total_credit, //$total_credit,
                    'reason_of_return' => $post['reasons'],
                    'date_return' => $post['date_return'],
                    'return_slip_no' => $post['return_slip'],
                    'discounted_amt' => $post['discounted_amt'],
                    'status' => 'Pending',
                    'date_created' => date('Y-m-d H:i:s'),
                    'last_update_by' => $user_id,
                );

            $returns_id = Returns::save($returns);

            foreach ($post['medicine'] as $key => $value) {
                $returns_meds = array(
                        'returns_id' => $returns_id,
                        'medicine_id' => $value['id'],
                        'quantity' => $value['quantity'],
                    );
                Returns_Meds::save($returns_meds);
            }
            $json['is_successful'] = true;
            $json['returns_id'] = $returns_id;
            $json['message'] = 'Successfully Added A Return Invoice, Please wait for it to get accepted.';
            $user = User::findById(array('id' => $user_id));
            $invoice = Invoice::findById(array('id' => $post['invoice_id']));
            $json['notif_title'] = 'Update';
            $json['notif_type'] = 'info';
            $json['notif_message'] = $user['lastname'].', '.$user['firstname']." has Added {$invoice['invoice_internal']} in Return Invoice";
        } else {
            $json['is_successful'] = false;
            $json['message'] = 'Failed Adding A Return Invoice, Please try again later.';
        }
        echo json_encode($json);
    }

    public function computeCostModifier()
    {
        Engine::XmlHttpRequestOnly();
        $post = $this->input->post();

        $total_amount = $post['return_quantity'] * $post['price'];

        if ($post['cost_type'] == '%') {
            $convert_percent = $post['cost_modifier'] / 100;

            $discount = $total_amount * $convert_percent;
            $all_credit = $total_amount - $discount;
        } else {
            $all_credit = $total_amount - $post['cost_modifier'];
        }

        $json['all_credit'] = number_format($all_credit, 2, '.', '');
        echo json_encode($json);
    }
}
