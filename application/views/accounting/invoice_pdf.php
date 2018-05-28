<?php

$html .= '
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<table border="0" cellpadding="2" cellspacing="2" align="center">
			<tr nobr="true">
				<th align="right" colspan="2">' . date("Y-m-d") . '</th>
			</tr>
			<tr nobr="true">
				<td align="center"><h4>                                            '. $patient['patient_name'] .'</h4></td>
				<td align="right">                                            '. ($invoice['payment_terms'] == "COD" ? $invoice['payment_terms'] : $invoice['payment_terms'] . " Days").'</td>
			</tr>
			<tr nobr="true">
				<td align="center">                                            '.($patient['tin'] ? $patient['tin'] : '                                            ') .'</td>
				<td>                                                                                        </td>
			</tr>
			<tr nobr="true">
				<td align="center">                                            '.($patient['address'] ? $patient['address'] : '                                            ').'</td>
				<td>                                                                                        </td>
			</tr>
			<tr nobr="true">
				<td align="center">                                                                                                                                    </td>
				<td>                                                                                                                                    </td>
			</tr>
		</table>
		<br>
		<br>
		<br>
		<table border="0" align="center" width="100%">
			<tr>
				<th height="20" width="5%"></th>
				<th height="20" width="5%"></th>
				<th height="20" width="60%"></th>
				<th height="20" width="15%"></th>
				<th height="20" width="23%"></th>
			</tr>
			<tr>
				<td height="20"></td>
				<td height="20"></td>
				<td height="20"></td>
				<td height="20"></td>
				<td height="20"></td>
			</tr>
			';

$total_price = 0;
foreach ($rpc_meds as $key => $value) {
	$total_price += $value['total_price'];
}
$html .= '
		<tr>
			<td height="20">1</td>
			<td height="20">LOT</td>
			<td height="20">Medication</td>
			<td height="20"></td>
			<td height="20">'.number_format($total_price, 2, '.', ',').'</td>
		</tr>';

	/*$html .= '
			<tr>
				<td>'.$value['quantity'].'</td>
				<td></td>
				<td>'.$value['medicine_name'] . ' (' . $value['dosage'] . ' ' . $value['dosage_type'] . ')' .'</td>
				<td>'.number_format($value['price'], 2, '.', ',').'</td>
				<td>'.number_format($value['total_price'], 2, '.', ',').'</td>
			</tr>';*/

$html .= '
			<tr>
				<td height="20"></td>
				<td height="20"></td>
				<td height="20"></td>
				<td height="20"></td>
				<td height="20"></td>
			</tr>
			<tr>
				<td height="20"></td>
				<td height="20"></td>
				<td height="20"></td>
				<td height="20"></td>
				<td height="20"></td>
			</tr>
			<tr>
				<td height="20"></td>
				<td height="20"></td>
				<td height="20"></td>
				<td height="20"></td>
				<td height="20"></td>
			</tr>
			<tr>
				<td height="20"></td>
				<td height="20"></td>
				<td height="20"></td>
				<td height="20"></td>
				<td height="20"></td>
			</tr>';


foreach ($other_charges as $key => $value) {
	$description_charges = Other_Charges::findById(array("id" => $value['description_id']));

	$html .= '
			<tr>
				<td height="20">'.$value['quantity'].'</td>
				<td height="20">LOT</td>
				<td height="20">'.$description_charges['r_centers'].'</td>
				<td height="20">'.number_format($value['cost_per_item'], 2, '.', ',').'</td>
				<td height="20">'.number_format($value['cost'], 2, '.', ',').'</td>
			</tr>';
}

$html .= '
			<tr>
				<td height="20"></td>
				<td height="20"></td>
				<td height="20"></td>
				<td height="20"></td>
				<td height="20"></td>
			</tr>
			<tr>
				<td height="20"></td>
				<td height="20"></td>
				<td height="20"></td>
				<td height="20"></td>
				<td height="20"></td>
			</tr>
			<tr>
				<td height="20"></td>
				<td height="20"></td>
				<td height="20"></td>
				<td height="20"></td>
				<td height="20"></td>
			</tr>
			<tr>
				<td height="20"></td>
				<td height="20"></td>
				<td height="20"></td>
				<td height="20"></td>
				<td height="20"></td>
			</tr>';


foreach ($cost_modifier as $key => $value) {
	$html .= '
			<tr>
				<td height="20"></td>
				<td height="20"></td>
				<td height="20">'. $value['applies_to'] . ' - ' .$value['modifier_type'] . ' ' . $value['modify_due_to'] . '</td>
				<td height="20">' . $value['cost_modifier'] . ' ' . $value['cost_type'] . '</td>
				<td height="20">'.number_format($value['total_cost'], 2, '.', ',').'</td>
			</tr>';
}

$html .= '
			<tr>
				<td height="20"></td>
				<td height="20"></td>
				<td height="20">Supplements can be returned within 15 days</td>
				<td height="20"></td>
				<td height="20"></td>
			</tr>
			<tr>
				<td  height="20"></td>
				<td  height="20"></td>
				<td  height="20">from the date of purchase upon approval.</td>
				<td  height="20">TOTAL SALES</td>
				<td  height="20">'.number_format($invoice['net_sales'], 2, '.', ',').'</td>
			</tr>
			<tr>
				<td  height="20"></td>
				<td  height="20"></td>
				<td  height="20">FOR CHECK PAYMENTS:</td>
				<td  height="20"></td>
				<td  height="20"></td>
			</tr>
			<tr>
				<td  height="20"></td>
				<td  height="20"></td>
				<td  height="20">ROYAL PREVENTIVE CLINIC, INC.</td>
				<td  height="20">LESS: Credits</td>
				<td  height="20">'.number_format($credits, 2, '.', ',').'</td>
			</tr>
			

		';


$html .= '
		</table>

		<br>
';

$html .= '
		<table border="0" align="center">
			<tr>
				<th height="20" width="10%"></th>
				<th height="20" width="10%"></th>
				<th height="20" width="50%"></th>
				<th height="20" width="15%"></th>
				<th height="20" width="23%"></th>
			</tr>

			<tr>
				<td  height="20"></td>
				<td  height="20"></td>
				<td  height="20"></td>
				<td  height="20"></td>
				<td  height="20"><br><br><br><br><h2>'.number_format($invoice['total_net_sales_vat'] - $credits, 2, '.', ',').'</h2><br><br></td>			
			</tr>

		</table>
';
$html .= '
		<table>
			<tr>
				<td width="2%"  height="15"></td>
				<td width="55%" height="15" color="red">Returning of Supplements Policy: (1) We will process</td>
				<td width="20%" height="15" align="center"></td>
				<td width="10%" height="15" align="center"></td>
				<td width="13%" height="15" align="center"></td>
			</tr>
			<tr>
				<td  width="2%"  height="15"></td>
				<td  width="55%" height="15" color="red">any returns only if supplements are returned</td>
				<td  width="20%" height="15" align="center"></td>
				<td  width="10%" height="15" align="center"></td>
				<td  width="13%" height="15" align="center"></td>
			</tr>
			<tr>
				<td  width="2%"  height="15"></td>
				<td  width="55%" height="15" color="red">within 15 calendar days from date of pick-up; and (2)</td>
				<td  width="20%" height="15" align="center"></td>
				<td  width="10%" height="15" align="center"></td>
				<td  width="13%" height="15" align="center"></td>
			</tr>
			<tr>
				<td  width="2%"  height="15"></td>
				<td  width="55%" height="15" color="red">supplements are returned with corresponding invoice.</td>
				<td  width="20%" height="15" align="center"></td>
				<td  width="10%" height="15" align="center"></td>
				<td  width="13%" height="15" align="center"></td>
			</tr>
			<tr>
				<td  width="2%"  height="15"></td>
				<td  width="55%" height="15" color="red">(3) For sanitary & sterility purpose,injectables, drops,</td>
				<td  width="20%" height="15" align="center"></td>
				<td  width="10%" height="15" align="center"></td>
				<td  width="13%" height="15" align="center"></td>
			</tr>
			<tr>
				<td  width="2%"  height="15"></td>
				<td  width="55%" height="15" color="red">solutions & creams are non-returnable.</td>
				<td  width="20%" height="15" align="center"></td>
				<td  width="10%" height="15" align="center"></td>
				<td  width="13%" height="15" align="center"></td>
			</tr>
		</table>	

';

$html .= '
	<br><br>
		<table>
			<tr>
				<td width="2%"  height="20" align="center"></td>
				<td width="55%" height="20" ><h4>For Check Payments: </h4></td>
				<td width="20%" height="20" align="center"></td>
				<td width="10%" height="20" align="center"></td>
				<td width="13%" height="20" align="center"></td>
			</tr>
			<tr>
				<td  width="2%"  height="20" align="center"></td>
				<td  width="55%" height="20" ><h4>ROYAL PREVENTIVE CLINIC, INC.</h4></td>
				<td  width="20%" height="20" align="center"></td>
				<td  width="10%" height="20" align="center"></td>
				<td  width="13%" height="20" align="center"></td>
			</tr>
			<tr>
				<td  width="2%"  height="20" align="center"></td>
				<td  width="30%" height="20" >______________________________</td>
				<td  width="20%" height="20" align="center"></td>
				<td  width="10%" height="20" align="center"></td>
				<td  width="13%" height="20" align="center"></td>
			</tr>
			<tr>
				<td  width="2%"  height="20" align="center"></td>
				<td  width="30%" height="20" align="center"><h4>Prepared By:</h4></td>
				<td  width="20%" height="20" align="center"></td>
				<td  width="10%" height="20" align="center"></td>
				<td  width="13%" height="20" align="center"></td>
			</tr>
			<tr>
				<td  width="2%"  height="20" align="center"></td>
				<td  width="30%" height="20" >______________________________</td>
				<td  width="20%" height="20" align="center"></td>
				<td  width="10%" height="20" align="center"></td>
				<td  width="13%" height="20" align="center"></td>
			</tr>
			<tr>
				<td  width="2%"  height="20" align="center"></td>
				<td  width="30%" height="20" align="center"><h4>Checked By:</h4></td>
				<td  width="20%" height="20" align="center"></td>
				<td  width="10%" height="20" align="center"></td>
				<td  width="13%" height="20" align="center"></td>
			</tr>
		</table>	

';


// $html .= '
// 		<table border="0">
// 			<tr>
// 				<td></td>
// 				<td></td>
// 				<td></td>
// 			</tr>
// 			<tr>
// 				<td>
// 					50 Bklts. (50x4) 0001 - 2500<br>
// 					BIR Authority to Print No.: OCN9AU0000670662<br>
// 					Date Issued: 10/25/13 Valid Until: 10/24/18<br>
// 					ST. GIRARD PRINTERS, INC.<br>
// 					33 Presidents Ave., BF Homes, Paranaque City<br>
// 					VAT REG. TIN 200-235-473-000<br>
// 				</td>
// 				<td style="text-align: center;">
// 					Printer\'s Accrediation No. PROVAN 002201<br>
// 					Date Assigned: February 14, 2013<br>
// 				</td>
// 				<td style="text-align: center;">
// 					Received the above in good order and condition.<br><br><br>
// 					______________________________<br>(Print name and signature)
// 				</td>
// 			</tr>
// 		</table>
// ';

/*

<tr>
	<td></td>
	<td></td>
	<td></td>
	<td><br><h3></h3><br></td>
	<td align="right"><br><h2>'.number_format($invoice['total_net_sales_vat'] - $credits, 2, '.', ',').'</h2><br></td>
</tr>

*/