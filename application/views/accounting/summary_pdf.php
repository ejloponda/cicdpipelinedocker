<?php

$html .= '
		<div style="text-align: center;">
			<div class="logo">
			';
if($pharmacy == 'Royal Preventive'){		
$html .= '			
				<img src="' . BASE_FOLDER . 'themes/images/RPC-logo.png" width="219px;" height="57px;">
				</div><br/>
				<h3>Prescribed RPC Supplements</h3>
		 ';
}else{
	$html .= '			
				<img src="' . BASE_FOLDER . 'themes/images/rpp-logo.png" width="180px;" height="66px;">
				</div><br/>
				<h3>Prescribed RPP Supplements</h3>
		 ';
}

$html .= '
		</div>
		<p> <b>Invoice No: </b> '.$invoice['invoice_num'].'</p>
		<table border="0" cellpadding="4" cellspacing="4" align="center" style="border-top: 1px solid black;border-right: 1px solid black;border-left: 1px solid black;border-bottom: 1px solid black;">
			<tr nobr="true">
				<td>Patient Name: <b>'. $patient['patient_name'] .'</b></td>
				<td>Date: '. $invoice['invoice_date'] .'</td>
			</tr>
			<tr nobr="true">
				<td>Service: Medication</td>
				<td>Patient Code: '. $patient['patient_code'] .'</td>
			</tr>
			
		</table>
		<br>
';
if($rpc_meds){
$html .= '
		<h3>Medicine</h3>
		<table border="1" align="center" width="100%">
			<tr>
				<th width="10%">Item #</th>
				<th width="50%">RPC Supplement Description</th>
				<th width="15%">Unit Price</th>
				<th width="10%">Quantity</th>
				<th width="15%">Total</th>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
';

$rpc_ctr = 1;
$rpc_total_price = 0;
foreach ($rpc_meds as $key => $value) {
	$all_ids[] = $value['medicine_id'];
}
	$med_ids = array_keys(array_flip($all_ids));
	foreach($med_ids as $key => $value){
		$medicine_quantity = 0;
		foreach ($rpc_meds as $k => $val) {
			if($value == $val['medicine_id']){
				$price 	= $val['price'];
				$medicine_quantity += $val['quantity'];
				if($val['id']){ $id 	= $val['id']; }
			}
		}
		$medic[] = array(
					"id"			=> $id,
					"medicine_id" 	=> $value,
					"quantity"		=> $medicine_quantity,
					"price"			=> $price,
					"total_price"	=> number_format($price * $medicine_quantity,2,".","")
				);
		unset($id);
	}

foreach ($medic as $key => $value) {

	$medicine 		= Inventory::findById(array("id"=>$value['medicine_id']));
	$dosage = Dosage_Type::findById(array("id" => $medicine['dosage_type']));

	$html .= '
			<tr>
				<td>'.$rpc_ctr.'</td>
				<td>'.$medicine['medicine_name'] . ' (' . $medicine['dosage'] .''.$dosage['abbreviation'].')' .'</td>
				<td>'.number_format($value['price'], 2, '.', ',').'</td>
				<td>'.$value['quantity'].'</td>
				<td>'.number_format($value['total_price'], 2, '.', ',').'</td>
			</tr>';
	$rpc_total_price += $value['total_price'];
	$rpc_ctr++;
}

$html .= '
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>';


$html .= '
		</table>
';


$html .= '
		<table border="0">
			<tr>
				<th width="10%"></th>
				<th width="50%"></th>
				<th width="15%"></th>
				<th width="10%"></th>
				<th width="15%"></th>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td style="text-align: center;">
					SUBTOTAL
				</td>
				<td style="text-align: center;">'.number_format($rpc_total_price, 2, '.', ',').'</td>
			</tr>

		</table>
';
}

$a_total_price = 0;
if($other_charges){
$html .= '
		<h3>Other Charges</h3>
		<table border="1" align="center" width="100%">
			<tr>
				<th width="10%">Item #</th>
				<th width="50%">Description</th>
				<th width="15%">Quantity</th>
				<th width="15%">Cost Per Item</th>
				<th width="10%">Total Cost</th>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
';

$a_ctr = 1;
foreach ($other_charges as $key => $value) {
	$others = Other_Charges::findById(array("id" => $value['description_id']));

	$html .= '
			<tr>
				<td>'.$a_ctr.'</td>
				<td>'.$others['r_centers'] . '</td>
				<td>'.$value['quantity'].'</td>
				<td>'.number_format($value['cost_per_item'], 2, '.', ',').'</td>
				<td>'.number_format($value['cost'], 2, '.', ',').'</td>
			</tr>';

	$others_total_price += $value['cost'];
	$a_ctr++;
}


$html .= '
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>';


$html .= '
		</table>
';

$html .= '
		<table border="0">
			<tr>
				<th width="10%"></th>
				<th width="50%"></th>
				<th width="15%"></th>
				<th width="10%"></th>
				<th width="15%"></th>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td style="text-align: center;">
					SUBTOTAL
				</td>
				<td style="text-align: center;">'.number_format($others_total_price, 2, '.', ',').'</td>
			</tr>

		</table>
';


}

if($cost_modifier){
	$html .= '
		<h3>Cost Modifiers</h3>
		<table border="1" align="center" width="100%">
			<tr>
				<th width="7%">Item #</th>
				<th width="48%">Applies to</th>
				<th width="10%">Modifier Type</th>
				<th width="15%">Modify Due To</th>
				<th width="10%">Cost</th>
				<th width="10%">Total Cost </th>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
	';

	$a_ctr = 1;
	foreach ($cost_modifier as $key => $value) {
		$html .= '
				<tr>
					<td>'.$a_ctr.'</td>
					<td>'.$value['applies_to'] . '</td>
					<td>'.$value['modifier_type'].'</td>
					<td>'.$value['modify_due_to'].' </td>
					<td>'.$value['cost_modifier'].' '.$value['cost_type'].'</td>
					<td>'.$value['total_cost'].'</td>
				</tr>';

		$cost_modifier_total_price += $value['total_cost'];
		$a_ctr++;
	}

	$html .= '
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>';


	$html .= '
			</table>
	';

$html .= '
		<table border="0">
			<tr>
				<th width="10%"></th>
				<th width="50%"></th>
				<th width="15%"></th>
				<th width="10%"></th>
				<th width="15%"></th>
			</tr>
			<tr>
				<td></td>
				<td style="text-align: center;"></td>
				<td></td>
				<td style="text-align: center;">
					SUBTOTAL
				</td>
				<td style="text-align: center;">'.number_format($cost_modifier_total_price, 2, '.', ',').'</td>
			</tr>
		</table>
';

}
$html .= '
		<table border="0">
			<tr>
				<td width="10%"></td>
				<td width="50%" style="text-align: center;">
					For check payment, kindly make it payable to:
				</td>
				<td width="15%"></td>
				<td width="10%" style="text-align: center;">
				</td>
				<td width="15%" style="text-align: center;"></td>
			</tr>

			<tr>
				<td width="10%"></td>
				<td width="50%" style="text-align: center;"><b>ROYAL PREVENTIVE CLINIC, INC.</b></td>
				<td width="15%"></td>
				<td width="10%"></td>
				<td width="15%"></td>
			</tr>
		</table>
';
$total = $others_total_price + $rpc_total_price + $cost_modifier_total_price;

$html .= '
<br>
<br>
		<table>
		<br>
			<tr>
				<td width="0.1%"  height="10"></td>
				<td width="57%" height="10" color="red">Returning of Supplements Policy: (1) We will process</td>
				<td width="20%" height="10" align="center"></td>
				<td width="10%" height="10" align="center"></td>
				<td width="13%" height="10" align="center"></td>
			</tr>
			<tr>
				<td  width="0.1%"  height="10"></td>
				<td  width="57%" height="10" color="red">any returns only if supplements are returned within </td>
				<td  width="20%" height="10" align="center"></td>
				<td  width="10%" height="10" align="center"></td>
				<td  width="13%" height="10" align="center"></td>
			</tr>
			<tr>
				<td  width="0.1%"  height="10"></td>
				<td  width="55%" height="10" color="red">15 calendar days from date of pick-up; and (2)</td>
				<td  width="20%" height="10" align="center"></td>
				<td  width="10%" height="10" align="center"></td>
				<td  width="13%" height="10" align="center"></td>
			</tr>
			<tr>
				<td  width="0.1%"  height="10"></td>
				<td  width="55%" height="10" color="red">supplements are returned with corresponding invoice.</td>
				<td  width="20%" height="10" align="center"></td>
				<td  width="10%" height="10" align="center"></td>
				<td  width="13%" height="10" align="center"></td>
			</tr>
			<tr>
				<td  width="0.1%"  height="10"></td>
				<td  width="55%" height="10" color="red">(3) For sanitary & sterility purpose,injectables, drops,</td>
				<td  width="20%" height="10" align="center"></td>
				<td  width="10%" height="10" align="center"></td>
				<td  width="13%" height="10" align="center"></td>
				
			</tr>
			<tr>
				<td  width="0.1%"  height="10"></td>
				<td  width="55%" height="10" color="red">solutions & creams are non-returnable.</td>
				<td  width="20%" height="10" align="center"></td>
				<td  width="10%" height="10" align="center"></td>
				<td  width="13%" height="10" align="center"></td>
			</tr>
		</table>	

';
if($patient['credit'] > 0){
$html .= '
		<table border="0" width="100%">
			<tr>
				<th width="45%"></th>
				<th width="45%"></th>
				<th width="10%"></th>
			</tr>
			<tr>
				<td>Confirmed / Verified by:</td>
			</tr>
			<tr>
				<td>______________________________________________</td>
				<td style="text-align:right;">Refer to actual invoice on discounts applied.</td>
				<td style="text-align:right;"></td>
			</tr>
			<tr>
				<td></td>
				<td style="text-align:right; font-size: 11px;"><b>Amount Due</b></td>
				<td style="text-align:right;border-bottom: 1px solid black; font-size:13px;">'.number_format($total, 2, '.', ',').'</td>
			</tr>
			
			<tr>
				<td>Released By:</td>
				<td style="text-align:right; font-size: 11px;"><b>Credit</b></td>
				<td style="text-align:right; font-size:13px; border-bottom: 1px solid black;">'.number_format($patient['credit'], 2, '.', ',').'</td>
			</tr>

			<tr>
				<td>______________________________________________</td>
				<td style="text-align:right; font-size: 11px;"><b>Credit Balance</b></td>
				<td style="text-align:right; font-size:13px;">_______</td>

			</tr>
			<tr>
				<br>
				<td>Received By:</td>
				<td style="text-align:right; font-size: 13px;"><b>TOTAL AMOUNT DUE</b></td>
				<td style="text-align:right; font-size:13px;">_______</td>
			</tr>
			<tr>
				<td>______________________________________________</td>
			</tr>
			<tr> <td style="text-align:center; font-weight: bold;">         Printed Name / Signature           </td></tr>
			
		</table>
';
}else{
	$html .= '
		<table border="0" width="100%">
			<tr>
				<th width="45%"></th>
				<th width="45%"></th>
				<th width="10%"></th>
			</tr>
			<tr>
				<td>Confirmed / Verified by:</td>
			</tr>
			<tr>
				<td>______________________________________________</td>
				<td style="text-align:right;">Refer to actual invoice on discounts applied.</td>
				<td style="text-align:right;"></td>
			</tr>
			<tr>
				<td></td>
				<td style="text-align:right; font-size: 13px;"><b>TOTAL AMOUNT DUE</b></td>
				<td style="text-align:right;border-bottom: 1px solid black; font-size:13px;">'.number_format($total, 2, '.', ',').'</td>
			</tr>
			
			<tr>
				<td>Released By:</td>
			</tr>

			<tr>
				<td>______________________________________________</td>
			</tr>
			<tr>
				<br>
				<td>Received By:</td>
			</tr>
			<tr>
				<td>______________________________________________</td>
			</tr>
			<tr> <td style="text-align:center; font-weight: bold;">         Printed Name / Signature           </td></tr>
			
		</table>
';
}
/*$html .= '
		<table border="0" width="100%">
			<tr>
				<th width="45%"></th>
				<th width="45%"></th>
				<th width="10%"></th>
			</tr>
			<tr>
				<td>Confirmed / Verified by:</td>
			</tr>
			<tr>
				<td>________________________________________________</td>
				<td style="text-align:right;">Refer to actual invoice on discounts applied.</td>
				<td style="text-align:right;"></td>
			</tr>
			<tr>
				<td></td>
				<td style="text-align:right; font-size: 13px;"><b>TOTAL AMOUNT DUE</b></td>
				<td style="text-align:right;border-bottom: 1px solid black; font-size:13px;">'.number_format($total, 2, '.', ',').'</td>
			</tr>
		</table>
';*/