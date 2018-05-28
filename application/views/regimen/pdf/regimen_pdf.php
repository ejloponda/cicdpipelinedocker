<?php
	$html .= '<style>'.file_get_contents('themes/css/RPC-style.css').'</style>';
	$html .= '	
				<div>
					<div class="logo" style="text-align:center">
						<img src="' . BASE_FOLDER . 'themes/images/RPC-logo.png" width="210px;">
						<h3 style="text-align: center;">Regimen</h3>
						<hr>
					</div>	
					
		';
$start_date = new Datetime($reg['start_date']); 
$end_date = new Datetime($reg['end_date']);
	$html .='
			<div>
				<table>
					<tr>
						<td width="80%"><b style="font-size: 14px; color: #96a960;">
						<span style = "color: black;">Patient Name: </span>
						<span >'.$patient['patient_name'].'</span></b> </td>
						<td width="40%" style="font-size: 10px; color: #787878;">
						<b style="color: black;">Date Generated:</b> '.date("M d,Y").' </td>
					</tr>
				<tr>

				</table>
			';
			
	if(!empty($version)){
		$date  =new DateTime($version['start_date']);
		$start = $date->format('M d');
		
		$date1 =new DateTime($version['end_date']);
		$end   = $date1->format('M d, Y');
		
		$html .='
			<p style="font-size:11px;"><b style="color: black;">   Regimen Duration: </b> 
			'.$start." - ".$end.'</p>
		';

	}else{
		$html .='
			<p style="font-size:11px;"><b style="color: black;">   Regimen Duration: </b> 
			'.date_format($start_date, "M d")." - ".date_format($end_date, "M d, Y").'</p>
		';
	}

	if(!empty($version)){
		
		
		$html .='
			<p style="font-size:11px;"><b style="color: black;">  LMP: </b> 
			'.$version['lmp'].'
			<br>
			<b style="color: black;">  Program: </b> 
			'.$version['program'].'</p>
		';

	}else{
		$html .='
			<p style="font-size:11px;"><b style="color: black;">  LMP: </b> 
			'.$reg['lmp'].'
			<br>
			<b style="color: black;">  Program: </b> 
			'.$reg['program'].'</p>
		';
	}
	

	$html .= '
		<table style="font-size:11px; padding: 5px 5px 5px 5px; " class="table_pdfborder" >
		<thead>
			<tr>
				<th width="25%" style="background-color: #f8941d; color:white; text-align:center; border: 1px solid #f4814b;">Date</th>
				<th width="25%" style="background-color: #f8941d; color:white; text-align:center; border: 1px solid #f4814b;">Breakfast</th>
				<th width="25%" style="background-color: #f8941d; color:white; text-align:center; border: 1px solid #f4814b;">Lunch</th>
				<th width="25%" style="background-color: #f8941d; color:white; text-align:center; border: 1px solid #f4814b;">Dinner</th>
			</tr>
		</thead>
		';

	
foreach ($meds as $key => $value) {
	
		$html .='
					<tr  nobr="true" style = "page-break-inside: avoid;">

				';

		$start = new DateTime($value['start_date']);
		$end = new DateTime($value['end_date']);

		
		$html .='

			<td width="25%" style="font-size:15px; color: #78815f; page-break-inside: avoid; ">
					<tr ><td></td></tr> &nbsp; &nbsp; &nbsp; &nbsp; 
					'.date_format($start, "M d")." - ".date_format($end, "M d ").'</td>	
			<td width="25%" style="color:#454545; page-break-inside: avoid;">
				';
				

			foreach ($value['breakfast'] as $a => $b):
				
			$html .= ($b['activity'] != "" ? "<b>" . strtoupper($b['activity']) . "</b><br/>" : "<br/>");		
				foreach ($b['med_ops'] as $c => $d):
						$medicine_id 	= $d['medicine_id'];
						$med 			= Inventory::findById(array("id" => $medicine_id));
						$dosage 		= Dosage_Type::findById(array("id" => $med['dosage_type']));
						$quantity 		= Quantity_Type::findById(array("id" => $med['quantity_type']));
				$html .='
						<style>
						b{
							color:red;
						}
						</style>
						'. ($d['medicine_name'] != "  " ? $d['medicine_name'] . " <strong>(" . $d['quantity'] . "" . ($d['quantity'] == 1 ? (substr($quantity['abbreviation'], 0,-1)) : $quantity['abbreviation']) . ") </strong><br/>" : "") 
							.($d['quantity_type'] == 'Others' ? "Taken As: " . $d['quantity_val'] . "<br/>" : "") 
						.' 
				';
				endforeach;
				
			endforeach;
			//$bfreplace3 =str_replace("<div>", "<br/>", $value['bf_instructions']);
			//$bfreplace4 =str_replace("</div>", "", $bfreplace3);
			$trim = trim($value['bf_instructions']);
			$bfreplace3 =str_replace("<div>", "<span>", $trim);
			$bfreplace4 =str_replace("</div>", "</span><br>", $bfreplace3);
			$bfreplace5 =str_replace("<div> </div>", "", $bfreplace4);
				$html .='
					<br/><br/><span style="color:black;">'. $bfreplace4.'</span></td>
				';
		
		$html .= '<td width="25%" style="color:#454545;">';			
			foreach ($value['lunch'] as $a => $b):
				$html .= ($b['activity'] != "" ? "<b>" . strtoupper($b['activity']) . "</b><br/>" : "");
				foreach ($b['med_ops'] as $c => $d):
					$medicine_id 	= $d['medicine_id'];
					$med 			= Inventory::findById(array("id" => $medicine_id));
					$dosage 		= Dosage_Type::findById(array("id" => $med['dosage_type']));
					$quantity 		= Quantity_Type::findById(array("id" => $med['quantity_type']));
				$html .='
						' .($d['medicine_name'] != "" ? $d['medicine_name'] . " <strong>(" . $d['quantity'] . "" . ($d['quantity'] == 1 ? (substr($quantity['abbreviation'], 0,-1)) : $quantity['abbreviation']) . ") </strong><br/>" : "") 
							.($d['quantity_type'] == 'Others' ? "Taken As: " . $d['quantity_val'] . "<br/>" : "")
						.'
				';
				endforeach;
				
			endforeach;
			//$lreplace3 =str_replace("<div>", "<br/>", $value['l_instructions']);
			//$lreplace4 =str_replace("</div>", "", $lreplace3);
			$trim = trim($value['l_instructions']);
			$lreplace3 =str_replace("<div>", "<span>", $trim);
			$lreplace4 =str_replace("</div>", "</span><br>", $lreplace3);
			$lreplace5 =str_replace("<div> </div>", "", $lreplace4);

				$html .='
					<br/><br/><span style="color:black;">'.$lreplace4.'</span></td>
				';
		
		$html .= '<td width="25%" style="color:#454545;">';	
			foreach ($value['dinner'] as $a => $b):
				$html .= ($b['activity'] != "" ? "<b>" . strtoupper($b['activity']) . "</b><br/>" : "");
				foreach ($b['med_ops'] as $c => $d):
					$medicine_id 	= $d['medicine_id'];
					$med 			= Inventory::findById(array("id" => $medicine_id));
					$dosage 		= Dosage_Type::findById(array("id" => $med['dosage_type']));
					$quantity 		= Quantity_Type::findById(array("id" => $med['quantity_type']));
				$html .='

						'. ($d['medicine_name'] != "" ? $d['medicine_name'] . " <strong>(" . $d['quantity'] . "" . ($d['quantity'] == 1 ? (substr($quantity['abbreviation'], 0,-1)) : $quantity['abbreviation']) . ") </strong><br/>" : "") 
							.($d['quantity_type'] == 'Others' ? "Taken As: " . $d['quantity_val'] . "<br/>" : "")
						.'
				';
				endforeach;
				
			endforeach;
			//$dreplace3 =str_replace("<div>", "<br/>", $value['d_instructions']);
			//$dreplace4 =str_replace("</div>", "", $dreplace3);
			$trim = trim($value['d_instructions']);
			$dreplace3 =str_replace("<div>", "<span>", $trim);
			$dreplace4 =str_replace("</div>", "</span><br>", $dreplace3);
			$dreplace5 =str_replace("<div> </div>", "", $dreplace4);
				$html .='
					<br/><br/><span style="color:black;">'.$dreplace4.'</span></td>
				';	

		$html .='</tr>';
	}
		$html .='</table>';
			$trim1 = trim($reg['regimen_notes']);
			$regreplace1 =str_replace("<div>", "", $trim1);
			$regreplace2 =str_replace("</div>", "<br/>", $regreplace1);

			$trim2 = trim($version['regimen_notes']);
			$versionreplace1 =str_replace("<div>", "", $trim2);
			$versionreplace2 =str_replace("</div>", "<br/>", $versionreplace1);
		$html .='	
				<table>
				<tr><hr></tr>
					<tr>
						<td width="20%">Regimen Notes: </td>
		';
		if(!empty($version)){
			$html .= '<td width="60%">'.$versionreplace2.'</td>';
		}else{
			$html .= '<td width="60%">'.$regreplace2.' </td>';
		}

		$html .= '	</tr>
				</table>';
	
?>