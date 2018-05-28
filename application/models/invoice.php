<?php

class Invoice
{
    public static $table_name = 'rpc_invoice';

    public function findById($params)
    {
        $sql = ' 
			SELECT '.field_injector($fields).' FROM '.self::$table_name.'
			WHERE id = '.Mapper::safeSql($params['id']).'
			LIMIT 1
		';

        return Mapper::runActive($sql, false);
    }

    public function findAll()
    {
        $sql = ' 
			SELECT * FROM '.self::$table_name."
			WHERE status != 'Void'
		";

        return Mapper::runActive($sql, true);
    }

    public function findReturn($fields = '*', $limit = null, $offset = 0, $like = null)
    {
        // remap the results to flat array
        $accepted = array_map(function ($row) {
            return $row['invoice_id'];
        }, $this->db->select('invoice_id')
            ->where('status', 'Accepted')
            ->get('rpc_returns')
            ->result_array());

        $sql = $this->db
            ->select($fields, true)
            ->where('status !=', 'Void')
            ->where_not_in('id', $accepted)
            ->order_by('invoice_num');

        if (!empty($like)) {
            $sql->or_like('invoice_num', $like);
        }

        if (!is_null($limit)) {
            $sql->limit($limit, $offset);
        }

        return $sql->get(self::$table_name)
            ->result_array();

        // $sql = 'SELECT '.$fields.' FROM '.self::$table_name."
        //     WHERE status != 'Void'
        //     AND id NOT IN (SELECT invoice_id FROM rpc_returns WHERE status = 'Accepted')";

        // if (!empty($like)) {
        //     $sql .= ' AND invoice_num LIKE "%'.$like.'%" ';
        // }

        // $sql .= 'ORDER BY invoice_num';

        // if (!is_null($limit)) {
        //     $sql .= ' LIMIT '.$offset.', '.$limit;
        // }

        // return Mapper::runActive($sql, true);
    }

    public function findByOrderId2($params)
    {
        $sql = ' 
			SELECT '.field_injector($fields).' FROM '.self::$table_name.'
			WHERE order_id = '.Mapper::safeSql($params['id']).' 
		';
        //debug_array($sql);
        return Mapper::runActive($sql);
    }

    public function findByOrderId($params)
    {
        $sql = ' 
			SELECT '.field_injector($fields).' FROM '.self::$table_name.'
			WHERE order_id = '.Mapper::safeSql($params['id'])." 
			AND status = 'Void'
		";
        //debug_array($sql);
        return Mapper::runActive($sql);
    }

    public function findAllPaid()
    {
        $sql = ' 
			SELECT * FROM '.self::$table_name."
			WHERE status = 'Paid'
		";

        return Mapper::runActive($sql, true);
    }

    public function findAllWithVoid()
    {
        $sql = ' 
			SELECT * FROM '.self::$table_name.'
		';

        return Mapper::runActive($sql, true);
    }

    public function findDateRange($params)
    {
        $sql = ' 
			SELECT '.field_injector($params['fields']).' FROM '.self::$table_name.' a
			LEFT JOIN `rpc_patients` b on a.patient_id = b.id
			LEFT JOIN `rpc_orders` c on. a.order_id = c.id
			WHERE a.invoice_date BETWEEN '.Mapper::safeSql($params['from_date']).' AND '.Mapper::safeSql($params['to_date']).'
		';
        //debug_array($sql);
        // return $sql;
        return Mapper::runActive($sql, true);
    }

    public function findDateRangeAR($params)
    {
        $sql = ' 
			SELECT '.field_injector($params['fields']).' FROM '.self::$table_name.' a
			LEFT JOIN `rpc_patients` b on a.patient_id = b.id
			LEFT JOIN `rpc_orders` c on. a.order_id = c.id
			WHERE (a.invoice_date BETWEEN '.Mapper::safeSql($params['from_date']).' AND '.Mapper::safeSql($params['to_date']).") AND 
			a.status = 'New' OR a.status = 'Pending' OR a.status = 'Partial'
		";

        //return $sql;
        return Mapper::runActive($sql, true);
    }

    public function findAllByPatient($params)
    {
        $sql = ' 
			SELECT '.field_injector($fields).' FROM '.self::$table_name."
			WHERE status != 'Void' AND patient_id = ".Mapper::safeSql($params['patient_id']).' 
		';
        //debug_array($sql);
        return Mapper::runActive($sql, true);
    }

    public function findAllByReceivableStatus()
    {
        $sql = ' 
			SELECT * FROM '.self::$table_name."
			WHERE status = 'Partial' OR status = 'New' OR status = 'Pending'
		";

        return Mapper::runActive($sql, true);
    }

    public function generateNewRPCInvoiceID()
    {
        $sql = ' 
			SELECT * FROM '.self::$table_name.'
			ORDER BY id DESC
			LIMIT 1
		';
        $invoice = Mapper::runActive($sql);
        $nextId = ($invoice ? (int) $invoice['id'] + 1 : 1);

        return $invoiceNumber = 'I-'.str_pad($nextId, 5, '0', STR_PAD_LEFT);
    }

    public function generateNewRPCInvoiceNumber()
    {
        $sql = ' 
			SELECT * FROM '.self::$table_name.'
			ORDER BY id DESC
			LIMIT 1
		';
        $invoice = Mapper::runActive($sql);
        $nextId = ($invoice ? (int) $invoice['id'] + 1 : 1);

        return $invoiceNumber = str_pad($nextId, 5, '0', STR_PAD_LEFT);
    }

    public static function generateSalesReportDatatable($params)
    {
        if ($params['search']) {
            $q = $params['search'];
            if (!is_array($q)) {
                $q = $params['search'];
                $search = "

					WHERE
					(
						a.invoice_internal LIKE  '%".$q."%' OR
						a.invoice_num LIKE  '%".$q."%' OR
						c.full_name LIKE  '%".$q."%' OR
						b.patient_name LIKE  '%".$q."%' OR
						b.tin LIKE  '%".$q."%' OR
						a.invoice_date LIKE  '%".$q."%' OR
						a.due_date LIKE  '%".$q."%' OR
						a.aging LIKE  '%".$q."%' OR
						a.total_gross_sales LIKE  '%".$q."%' OR
						a.cost_modifier LIKE  '%".$q."%' OR
						a.net_sales LIKE  '%".$q."%' OR
						a.net_sales_vat LIKE  '%".$q."%' OR
						a.total_net_sales_vat LIKE  '%".$q."%' OR
						a.status LIKE  '%".$q."%' AND a.status != 'Void'
					) 
				";
            } else {
                $q = $params['search']['filter_to_search'];
                $type = $params['search']['filter_date'];

                if ($type == 'Day') {
                    $date_year = date('Y');
                    $date_month = date('m');
                    $date_today = date('Y-m-d');

                    $search = "
						WHERE
						(
							a.invoice_date = '".$date_year.'-'.$date_month.'-'.$q."' AND a.status != 'Void'
						)

					";
                } elseif ($type == 'Month') {
                    if (!is_numeric($q)) {
                        $arr_month = array(
                                    'January' => '01',
                                    'February' => '02',
                                    'March' => '03',
                                    'April' => '04',
                                    'May' => '05',
                                    'June' => '06',
                                    'July' => '07',
                                    'August' => '08',
                                    'September' => '09',
                                    'October' => 10,
                                    'November' => 11,
                                    'December' => 12,
                                );
                        foreach ($arr_month as $k => $v) {
                            $arr_month[substr($k, 0, 3)] = $v;
                        }
                        $q = $arr_month[ucfirst($q)];
                    }
                    $date_year = date('Y');
                    $search = "
						WHERE
						(
							a.invoice_date between '".$date_year.'-'.$q."-01' AND 
							'".$date_year.'-'.$q."-31' AND a.status != 'Void'
						)

					";
                } else {
                    $search = "
						WHERE
						(
							a.invoice_date between '".$q."-01-01' AND 
							'".$q."-12-31' AND a.status != 'Void'
						)

					";
                }
            }
        } else {
            $search = "WHERE a.status != 'Void'";
        }

        $order = ($params['order'] == '' ? '' : ' ORDER BY '.$params['order']);
        $limit = ($params['limit'] == '' ? '' : ' LIMIT '.$params['limit']);

        $sql = ' 
			SELECT '.field_injector($params['fields']).' FROM '.self::$table_name." a 
			LEFT JOIN rpc_patients b ON a.patient_id = b.id 
			LEFT JOIN rpc_doctors_list c ON b.doc_assigned_id = c.id
			{$search}
			{$order}
			{$limit}
		";
        // return $sql;
        return Mapper::runSql($sql, true, true);
    }

    public static function countSalesReportDatatable($params)
    {
        if ($params['search']) {
            $q = $params['search'];
            if (!is_array($q)) {
                $q = $params['search'];
                $search = "

					WHERE
					(
						a.invoice_internal LIKE  '%".$q."%' OR
						a.invoice_num LIKE  '%".$q."%' OR
						c.full_name LIKE  '%".$q."%' OR
						b.patient_name LIKE  '%".$q."%' OR
						b.tin LIKE  '%".$q."%' OR
						a.invoice_date LIKE  '%".$q."%' OR
						a.due_date LIKE  '%".$q."%' OR
						a.aging LIKE  '%".$q."%' OR
						a.total_gross_sales LIKE  '%".$q."%' OR
						a.cost_modifier LIKE  '%".$q."%' OR
						a.net_sales LIKE  '%".$q."%' OR
						a.net_sales_vat LIKE  '%".$q."%' OR
						a.total_net_sales_vat LIKE  '%".$q."%' OR
						a.status LIKE  '%".$q."%' AND a.status != 'Void'
					)
				";
            } else {
                $q = $params['search']['filter_to_search'];
                $type = $params['search']['filter_date'];

                if ($type == 'Day') {
                    $date_year = date('Y');
                    $date_month = date('m');
                    $date_today = date('Y-m-d');

                    $search = "
						WHERE
						(
							a.invoice_date = '".$date_year.'-'.$date_month.'-'.$q."' AND a.status != 'Void'
						)

					";
                } elseif ($type == 'Month') {
                    if (!is_numeric($q)) {
                        $arr_month = array(
                                    'January' => '01',
                                    'February' => '02',
                                    'March' => '03',
                                    'April' => '04',
                                    'May' => '05',
                                    'June' => '06',
                                    'July' => '07',
                                    'August' => '08',
                                    'September' => '09',
                                    'October' => 10,
                                    'November' => 11,
                                    'December' => 12,
                                );
                        foreach ($arr_month as $k => $v) {
                            $arr_month[substr($k, 0, 3)] = $v;
                        }
                        $q = $arr_month[ucfirst($q)];
                    }
                    $date_year = date('Y');
                    $search = "
						WHERE
						(
							a.invoice_date between '".$date_year.'-'.$q."-01' AND 
							'".$date_year.'-'.$q."-31' AND a.status != 'Void'
						)

					";
                } else {
                    $search = "
						WHERE
						(
							a.invoice_date between '".$q."-01-01' AND 
							'".$q."-12-31' AND a.status != 'Void'
						)

					";
                }
            }
        }

        $sql = ' 
			SELECT COUNT(a.id) as total FROM '.self::$table_name." a 
			LEFT JOIN rpc_patients b ON a.patient_id = b.id 
			LEFT JOIN rpc_doctors_list c ON b.doc_assigned_id = c.id
			{$search}
		";

        $record = Mapper::runSql($sql, true, false);

        return $record['total'];
    }

    public static function generateAccountsReceivablesDatatable($params)
    {
        if ($params['search']) {
            $q = $params['search'];
            $search = "
				(
					a.invoice_internal LIKE  '%".$q."%' OR
					a.invoice_num LIKE  '%".$q."%' OR
					c.full_name LIKE  '%".$q."%' OR
					b.patient_name LIKE  '%".$q."%' OR
					b.tin LIKE  '%".$q."%' OR
					a.invoice_date LIKE  '%".$q."%' OR
					a.due_date LIKE  '%".$q."%' OR
					a.aging LIKE  '%".$q."%' OR
					a.total_net_sales_vat LIKE  '%".$q."%' OR
					a.status LIKE  '%".$q."%' 
				)
 				AND
			";
        }

        $order = ($params['order'] == '' ? '' : ' ORDER BY '.$params['order']);
        $limit = ($params['limit'] == '' ? '' : ' LIMIT '.$params['limit']);

        $sql = ' 
			SELECT '.field_injector($params['fields']).' FROM '.self::$table_name." a 
			LEFT JOIN rpc_patients b ON a.patient_id = b.id 
			LEFT JOIN rpc_doctors_list c ON b.doc_assigned_id = c.id
			WHERE
			{$search}
			a.status !='Void'
			{$order}
			{$limit}
		";

        return Mapper::runSql($sql, true, true);
    }

    public static function countAccountsReceivablesDatatable($params)
    {
        if ($params['search']) {
            $q = $params['search'];
            $search = "
				(
					a.invoice_internal LIKE  '%".$q."%' OR
					a.invoice_num LIKE  '%".$q."%' OR
					c.full_name LIKE  '%".$q."%' OR
					b.patient_name LIKE  '%".$q."%' OR
					b.tin LIKE  '%".$q."%' OR
					a.invoice_date LIKE  '%".$q."%' OR
					a.due_date LIKE  '%".$q."%' OR
					a.aging LIKE  '%".$q."%' OR
					a.total_net_sales_vat LIKE  '%".$q."%' OR
					a.status LIKE  '%".$q."%' 
				)
 				AND
			";
        }

        $sql = ' 
			SELECT COUNT(a.id) as total FROM '.self::$table_name." a 
			LEFT JOIN rpc_patients b ON a.patient_id = b.id 
			LEFT JOIN rpc_doctors_list c ON b.doc_assigned_id = c.id
			WHERE
			{$search}
			a.status = 'New' OR a.status = 'Pending' OR a.status = 'Partial'
		";

        $record = Mapper::runSql($sql, true, false);

        return $record['total'];
    }

    public static function save($record, $id)
    {
        foreach ($record as $key => $value):
            $arr[] = " $key = ".Mapper::safeSql($value);
        endforeach;

        if ($id) {
            $sqlstart = ' UPDATE '.self::$table_name.' SET ';
            $sqlend = ' WHERE id = '.Mapper::safeSql($id);
        } else {
            $sqlstart = ' INSERT INTO '.self::$table_name.' SET ';
            $sqlend = '';
        }

        $sqlbody = implode($arr, ' , ');
        $sql = $sqlstart.$sqlbody.$sqlend;

        Mapper::runSql($sql, false);
        if ($id) {
            return $id;
        } else {
            return mysql_insert_id();
        }
    }

    public static function delete($id)
    {
        $sql = '
			DELETE FROM '.self::$table_name.'
			WHERE id = '.Mapper::safeSql($id).'
		';
        Mapper::runSql($sql, false);
    }

    public function SaveInvoice($data)
    {
        $session = $this->session->all_userdata();
        $user_id = $this->encrypt->decode($session['user_id']);

        $patient = Patients::findById(array('id' => $data['patient_id']));

        if ($data['id']) {
            $id = $data['id'];
            $start = new Datetime($data['invoice_date']);
            $end = new Datetime($data['due_date']);
            $interval = $start->diff($end);
            $time_interval = (int) $interval->format('%a');

            $invoice_id = $data['invoice_id'];
            $invoice_number = $data['invoice_number'];

            $record = array(
                'invoice_num' => $invoice_number,
                /*"invoice_internal" 		=> $invoice_number,*/
                /*"patient_id" 			=> $data['patient_id'],
                "regimen_id" 			=> $data['regimen_id'],
                "version_id" 			=> $data['version_id'],*/
                'charge_to' => $data['charged_to'],
                'relation_to_patient' => $data['relation_to_patient'],
                'invoice_date' => $data['invoice_date'],
                'due_date' => $data['due_date'],
                'aging' => $time_interval,
                'payment_terms' => $data['payment_terms'],
                'total_gross_sales' => $data['total_regimen_cost'] + $data['total_other_charges'],
                'total_regimen_cost' => $data['total_regimen_cost'],
                'total_other_charges' => $data['total_other_charges'],
                'cost_modifier' => $data['total_cost_modifier'],
                'net_sales' => $data['net_sales'],
                'net_sales_vat' => $data['net_sales_vat'],
                'total_net_sales_vat' => $data['total_net_sales_vat'],
                'date_updated' => date('Y-m-d H:i:s'),
                'status' => 'Pending',
                'last_update_by' => $user_id,
                );

            return self::save($record, $id);
        } else {
            // debug_array(number_format($data['total_regimen_cost'] + $data['total_other_charges'],2));
            $invoice_id = self::generateNewRPCInvoiceID();
            $invoice_number = self::generateNewRPCInvoiceNumber();

            $start = new Datetime($data['invoice_date']);
            $end = new Datetime($data['due_date']);
            $interval = $start->diff($end);
            $time_interval = (int) $interval->format('%a');

            $invoice_id = ($invoice_id == $data['invoice_id'] ? $data['invoice_id'] : $invoice_id);
            $invoice_number = ($invoice_number == $data['invoice_number'] ? $invoice_number : $data['invoice_number']);

            $record = array(
                'invoice_num' => $invoice_id,
                'invoice_internal' => $invoice_number,
                'patient_id' => $data['patient_id'],
                'regimen_id' => $data['regimen_id'],
                'version_id' => $data['version_id'],
                'charge_to' => $data['charged_to'],
                'relation_to_patient' => $data['relation_to_patient'],
                'invoice_date' => $data['invoice_date'],
                'due_date' => $data['due_date'],
                'aging' => $time_interval,
                'payment_terms' => $data['payment_terms'],
                'total_gross_sales' => $data['total_regimen_cost'] + $data['total_other_charges'],
                'total_regimen_cost' => $data['total_regimen_cost'],
                'total_other_charges' => $data['total_other_charges'],
                'cost_modifier' => $data['total_cost_modifier'],
                'net_sales' => $data['net_sales'],
                'net_sales_vat' => $data['net_sales_vat'],
                'total_net_sales_vat' => $data['total_net_sales_vat'],
                'remaining_balance' => $data['total_net_sales_vat'],
                'date_created' => date('Y-m-d H:i:s'),
                'date_updated' => date('Y-m-d H:i:s'),
                'status' => 'New',
                'last_update_by' => $user_id,
                );

            $regimen = array('converted' => 1);
            Regimen::save($regimen, $data['regimen_id']);

            return self::save($record);
        }
    }
}
