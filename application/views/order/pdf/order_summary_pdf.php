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
		<table border="0" cellpadding="4" cellspacing="4" align="center" style="border-top: 1px solid black;border-right: 1px solid black;border-left: 1px solid black;border-bottom: 1px solid black;">
			<tr nobr="true">
				<td>Patient Name: '. $patient['patient_name'] .'</td>
				<td>Date: '. date("Y-m-d") .'</td>
			</tr>
			<tr nobr="true">
				<td>Service: Medication</td>
				<td>Patient Code: '. $patient['patient_code'] .'</td>
			</tr>
			
		</table>
		<br>
';
if($medicine){


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
foreach ($medicine as $key => $value) {
					$all_ids[] = $value['med_id'];
				}

				$med_ids = array_keys(array_flip($all_ids));
				foreach($med_ids as $key => $value){
					$medicine_quantity = 0;
					foreach ($medicine as $k => $val) {
						if($value == $val['med_id']){
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
	$html .= '
			<tr>
				<td>'.$rpc_ctr.'</td>
				<td>'.$medicine['medicine_name'] . ' (' . $medicine['dosage'] .''.$medicine['abbreviation'].')' .'</td>
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
				<td></td>
				<td></td>
				<td style="text-align: center;">
					SUBTOTAL
				</td>
				<td style="text-align: center;">'.number_format($rpc_total_price, 2, '.', ',').'</td>
			</tr>

			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		</table>
';
}

$a_total_price = 0;
if($others){

$html .= '
		<h3>Other Charges</h3>
		<table border="1" align="center" width="100%">
			<tr>
				<th width="10%">Item #</th>
				<th width="50%">Description</th>
				<th width="15%">Quantity</th>
				<th width="10%">Price</th>
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
foreach ($others as $key => $value) {
	$html .= '
			<tr>
				<td>'.$a_ctr.'</td>
				<td>'.$value['r_centers'] . '</td>
				<td>'.$value['quantity'].'</td>
				<td>'.number_format($value['cost'], 2, '.', ',').'</td>
				<td>'.number_format($value['total_cost'], 2, '.', ',').'</td>
			</tr>';

	$others_total_price += $value['total_cost'];
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
				</td>
				<td></td>
				<td style="text-align: center;">
					SUBTOTAL
				</td>
				<td style="text-align: center;">'.number_format($others_total_price, 2, '.', ',').'</td>
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

$total = $others_total_price + $rpc_total_price;

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