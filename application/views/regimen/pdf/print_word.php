<?php

ob_start();

$section = $this->word->createSection(array('orientation'=>'portrait'));

$fontStyle = array('bold'=>true, 'align'=>'center');
// Add image elements
$section->addImage( FCPATH. 'themes/images/rpc-logo.png', array('width'=>328, 'height'=>58, 'align'=>'center'));
$section->addTextBreak(1);

	$start_date = new Datetime($reg['start_date']); 
	$end_date = new Datetime($reg['end_date']);

	$this->word->addFontStyle('rStyle', array('bold'=>true,'size'=>12));
	$this->word->addParagraphStyle('pStyle', array('align'=>'center', 'spaceAfter'=>100));

	$section->addText('Regimen', 'rStyle', 'pStyle');
	$section->addText('                                                                                                                                                     Date Generated:' ." ". date_format($start_date, "M d, Y"), array('size'=>8));
	$section->addText('Patient Name:'." ". $patient['patient_name'], array('size'=>10));
	$section->addText('Regimen Duration:' ." ". date_format($start_date, "M d").'-'.date_format($end_date, "M d, Y"), array('size'=>8));

	// Define table style arrays
$styleTable = array('borderSize'=>3, 'borderColor'=>'#f4814b', 'cellMargin'=>50);
$styleFirstRow = array('borderBottomColor'=>'#f4814b', 'bgColor'=>'#f8941d');

$styleCell = array('valign'=>'center');
$styleCellBTLR = array('valign'=>'center', 'textDirection'=>PHPWord_Style_Cell::TEXT_DIR_BTLR);
$boldStyle = array('bold'=>true);

// Add table style
$this->word->addTableStyle('myOwnTableStyle', $styleTable, $styleFirstRow);
$table = $section->addTable('myOwnTableStyle');

// Add row
$table->addRow(300);
		
// Add cells
$table->addCell(5000, $styleCell)->addText('Date', $fontStyle, 'pStyle');
$table->addCell(9500, $styleCell)->addText('Breakfast', $fontStyle, 'pStyle');
$table->addCell(9500, $styleCell)->addText('Lunch', $fontStyle, 'pStyle');
$table->addCell(9500, $styleCell)->addText('Dinner', $fontStyle, 'pStyle');

// Add more rows / cells
foreach ($meds as $key => $value) {
	$start = new DateTime($value['start_date']);
	$end = new DateTime($value['end_date']);	
	$table->addRow();
	$table->addCell(9000,$styleCell)->addText(date_format($start, "M d").' - '.date_format($end, "M d"), $fontStyle, 'pStyle');
	if(empty($value['breakfast'])){
			$table->addCell(9500)->addText("");	
	}
	foreach ($value['breakfast'] as $a => $b){	
			foreach ($b['med_ops'] as $c => $d){
					$medicine_id 	= $d['medicine_id'];
					$med 			= Inventory::findById(array("id" => $medicine_id));
					$dosage 		= Dosage_Type::findById(array("id" => $med['dosage_type']));
					$quantity 		= Quantity_Type::findById(array("id" => $med['quantity_type']));

			//$table->addCell(9500)->addText("hege");	
			
			$table->addCell(9500)->addText(strtoupper($b['activity']) ."                               ". $d['medicine_name'] . " " . $med['dosage'] . " " . $dosage['abbreviation'] ."(" . $d['quantity'] . "" . $quantity['abbreviation'] . ")                     ". "Taken As:" .$d['quantity_val'] ."            ".  $value['bf_instructions']);
					
		}
	}
	if(empty($value['lunch'])){
			$table->addCell(9500)->addText(" ");	
	}
	foreach ($value['lunch'] as $a => $b){
			foreach ($b['med_ops'] as $c => $d){
				$medicine_id 	= $d['medicine_id'];
				$med 			= Inventory::findById(array("id" => $medicine_id));
				$dosage 		= Dosage_Type::findById(array("id" => $med['dosage_type']));
				$quantity 		= Quantity_Type::findById(array("id" => $med['quantity_type']));
		
		$table->addCell(9500)->addText(strtoupper($b['activity']) ."                              ". $d['medicine_name'] . " " . $med['dosage'] . " " . $dosage['abbreviation'] ."(" . $d['quantity'] . "" . $quantity['abbreviation'] . ")                     ". $value['l_instructions']);	
		}
	}	

	if(empty($value['dinner'])){
			$table->addCell(9500)->addText(" ");	
	}
	foreach ($value['dinner'] as $a => $b){
			foreach ($b['med_ops'] as $c => $d){
				$medicine_id 	= $d['medicine_id'];
				$med 			= Inventory::findById(array("id" => $medicine_id));
				$dosage 		= Dosage_Type::findById(array("id" => $med['dosage_type']));
				$quantity 		= Quantity_Type::findById(array("id" => $med['quantity_type']));
		
		$table->addCell(9500)->addText(strtoupper($b['activity']) ."                              ". $d['medicine_name'] . " " . $med['dosage'] . " " . $dosage['abbreviation'] ."(" . $d['quantity'] . "" . $quantity['abbreviation'] . ")                     ". $value['d_instructions']);

	}
	}				

}
$date_today = date("Ymd");

$section->addTextBreak(1);
	$section->addText('Regimen Notes:' ." ". $reg['regimen_notes'], array('size'=>10));	

$filename="{$reg['regimen_number']}-{$version['version_name']}-{$date_today}-{$patient['patient_code']}.docx"; //save our document as this file name

header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document'); //mime type
header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
header('Cache-Control: max-age=0'); //no cache
 
$objWriter = PHPWord_IOFactory::createWriter($this->word, 'Word2007');
$objWriter->save('php://output');

