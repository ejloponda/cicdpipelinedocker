<style type="text/css">
.activity_style{color:red;font-size: 14px;}
.instructions_style{color:black;}
</style>
<table class="table-regimen datatable" style="width: 96%;">
	<th onclick="javascript: loadMedicineFormforNewRow('date_form');">Date</th>
	<th onclick="javascript: loadMedicineFormforNewRow('breakfast_form');">Breakfast</th>
	<th onclick="javascript: loadMedicineFormforNewRow('lunch_form');">Lunch</th>
	<th onclick="javascript: loadMedicineFormforNewRow('dinner_form');">Dinner</th>
	<th ></th>
	<?php foreach ($main_tbl as $key => $value) { ?>
	<tr>
		<?php $start = new DateTime($value['start_date']); $end = new DateTime($value['end_date']) ?>
		<td style="width: 15%;"><?php echo date_format($start, 'M d') . " - " . date_format($end, 'M d') ?></td>
		<td style="width: 20%;">
			<?php $bf = Regimen_Med_List::findByRegimenIdRowId(array("regimen_id" => $reg_id, "row_id" => $value['id'], "meal_type" => "bf"));
			// debug_array($bf);
			foreach ($bf as $a => $b): ?>
				<?php 
					$medicine_id 	= $b['medicine_id'];
					$med 			= Inventory::findById(array("id" => $medicine_id));
					$dosage 		= Dosage_Type::findById(array("id" => $med['dosage_type']));
				  	$quantity 		= Quantity_Type::findById(array("id" => $med['quantity_type']));
				 ?>
				<?php echo ($b['activity'] != "" ? "<b class='activity_style'>" . strtoupper($b['activity']) . "</b><br/>" : "") ?>
				<?php echo ($b['medicine_name'] != "" ? $b['medicine_name'] . " " . $med['dosage'] . " " . $dosage['abbreviation'] ." <b>(" . $b['quantity'] . " " . $quantity['abbreviation'] . ")</b><br/>" : "") ?>
				<?php echo ($b['quantity_type'] == 'Others' ? "Taken As: " . $b['quantity_val'] . "<br/>" : ""); ?>
			<?php endforeach ?>
			<br/><i class="instructions_style"><?php echo $value['bf_instructions'] ?></i>
		</td>
		<td style="width: 20%;">
			<?php $lunch = Regimen_Med_List::findByRegimenIdRowId(array("regimen_id" => $reg_id, "row_id" => $value['id'], "meal_type" => "lunch"));
			foreach ($lunch as $a => $b): ?>
				<?php 
					$medicine_id 	= $b['medicine_id'];
					$med 			= Inventory::findById(array("id" => $medicine_id));
					$dosage 		= Dosage_Type::findById(array("id" => $med['dosage_type']));
				  	$quantity 		= Quantity_Type::findById(array("id" => $med['quantity_type']));
				 ?>
				<?php echo ($b['activity'] != "" ? "<b class='activity_style'>" . strtoupper($b['activity']) . "</b><br/>" : "") ?>
				<?php echo ($b['medicine_name'] != "" ? $b['medicine_name'] . " " . $med['dosage'] . " " . $dosage['abbreviation'] ." <b>(" . $b['quantity'] . " " . $quantity['abbreviation'] . ")</b><br/>" : "") ?>
				<?php echo ($b['quantity_type'] == 'Others' ? "Taken As: " . $b['quantity_val'] . "<br/>" : ""); ?>
			<?php endforeach ?>
			<br/><i class="instructions_style"><?php echo $value['l_instructions'] ?></i>
		</td>
		<td style="width: 20%;">
			<?php $dinner = Regimen_Med_List::findByRegimenIdRowId(array("regimen_id" => $reg_id, "row_id" => $value['id'], "meal_type" => "dinner"));
			foreach ($dinner as $a => $b): ?>
				<?php 
					$medicine_id 	= $b['medicine_id'];
					$med 			= Inventory::findById(array("id" => $medicine_id));
					$dosage 		= Dosage_Type::findById(array("id" => $med['dosage_type']));
				  	$quantity 		= Quantity_Type::findById(array("id" => $med['quantity_type']));
				 ?>
				<?php echo ($b['activity'] != "" ? "<b class='activity_style'>" . strtoupper($b['activity']) . "</b><br/>" : "") ?>
				<?php echo ($b['medicine_name'] != "" ? $b['medicine_name'] . " " . $med['dosage'] . " " . $dosage['abbreviation'] ." <b>(" . $b['quantity'] . " " . $quantity['abbreviation'] . ")</b><br/>" : "") ?>
				<?php echo ($b['quantity_type'] == 'Others' ? "Taken As: " . $b['quantity_val'] . "<br/>" : ""); ?>
			<?php endforeach ?>
			<br/><i class="instructions_style"><?php echo $value['d_instructions'] ?></i>
		</td>
		<td style="width: 7%;">
			<a href="javascript: void(0);" onClick="javascript: edit_regimen_record(<?php echo $value['id'] ?>);" class="table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
			<span style="margin: 0 5px 0 5px; text-align: center;"><img src="<?php echo BASE_FOLDER ?>themes/images/line.png"></span>
			<a href="javascript:void(0);" onclick="javascript: delete_regimen_record(<?php echo $value['id'] ?>);" class="table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
		</td>
	</tr>
	<?php } ?>
	
</table>