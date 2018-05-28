<?php

$html .= '
		<div style="text-align: center;">
			<div class="logo">
				<img src="' . BASE_FOLDER . 'themes/images/rpc-logo.png" width="328px;" height="58px;">
			</div><br/>
			<h3>Prescribed RPC Supplements</h3>
		</div>
		<table border="0" cellpadding="4" cellspacing="4" align="center" style="border-top: 1px solid black;border-right: 1px solid black;border-left: 1px solid black;border-bottom: 1px solid black;">
			<tr nobr="true">
				<td>Patient Name: '. $patient['patient_name'] .'</td>
				<td>Date: '. date("Y-m-d") .'</td>
			</tr>
			<tr nobr="true">
				<td>Service: Medication</td>
				<td>Patient Code: '. $patient_code .'</td>
			</tr>
			
		</table>
		<br>
		<br>
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
	$html .= '
			<tr>
				<td>'.$rpc_ctr.'</td>
				<td>'.$value['medicine_name'] . ' (' . $value['dosage'] . ')' .'</td>
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
		</table><br>
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
				<td style="text-align: center;">
					For check payment, kindly make it payable to:
				</td>
				<td></td>
				<td style="text-align: center;">
					SUBTOTAL
				</td>
				<td style="text-align: center;">'.number_format($rpc_total_price, 2, '.', ',').'</td>
			</tr>

			<tr>
				<td></td>
				<td  style="text-align: center;"><b>ROYAL PREVENTIVE CLINIC, INC.</b></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>

			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		</table>
';

$a_total_price = 0;
if($a_meds){

$html .= '
		<table border="1" align="center" width="100%">
			<tr>
				<th width="10%">Item #</th>
				<th width="50%">A-List Supplement Description</th>
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

$a_ctr = 1;
foreach ($a_meds as $key => $value) {
	$html .= '
			<tr>
				<td>'.$a_ctr.'</td>
				<td>'.$value['medicine_name'] . ' (' . $value['dosage'] . ')' .'</td>
				<td>'.number_format($value['price'], 2, '.', ',').'</td>
				<td>'.$value['quantity'].'</td>
				<td>'.number_format($value['total_price'], 2, '.', ',').'</td>
			</tr>';

	$a_total_price += $value['total_price'];
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
		</table><br>
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
				<td style="text-align: center;">
					For check payment, kindly make it payable to:
				</td>
				<td></td>
				<td style="text-align: center;">
					SUBTOTAL
				</td>
				<td style="text-align: center;">'.number_format($a_total_price, 2, '.', ',').'</td>
			</tr>

			<tr>
				<td></td>
				<td  style="text-align: center;"><b>A-LIST BIOPHARMA AND NUTRACEUTICALS CORPORATION</b></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>

			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		</table>
';

}

$total = $a_total_price + $rpc_total_price;

$html .= '
		<table border="0" width="100%">
			<tr>
				<th width="45%"></th>
				<th width="45%"></th>
				<th width="10%"></th>
			</tr>
			<tr>
				<td>Confirmed / Verified by:</td>
				<td style="text-align:right;"><b>Total Sales</b></td>
				<td style="text-align:right;">' . number_format($total, 2, '.', ',')  . '</td>
			</tr>
			<tr>
				<td>________________________________________________</td>
				<td style="text-align:right;">Refer to actual invoice on discounts applied.</td>
				<td style="text-align:right;"></td>
			</tr>
			<tr>
				<td></td>
				<td style="text-align:right;"><b>TOTAL AMOUNT DUE</b></td>
				<td style="text-align:right;border-bottom: 1px solid black">'.number_format($total, 2, '.', ',').'</td>
			</tr>
		</table>
';