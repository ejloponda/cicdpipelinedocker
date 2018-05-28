<style type="text/css">
.activity_style{color:red;font-size: 14px;}
.instructions_style{color:black;}
tbody{
	vertical-align: baseline !important;
	}
</style>
<table class="table-regimen datatable" style="width: 96%;">
	<th onclick="javascript: loadMedicineFormforNewRow('date_form');">Date</th>
	<th onclick="javascript: loadMedicineFormforNewRow('breakfast_form');">Breakfast</th>
	<th onclick="javascript: loadMedicineFormforNewRow('lunch_form');">Lunch</th>
	<th onclick="javascript: loadMedicineFormforNewRow('dinner_form');">Dinner</th>
	<th ></th>
	<?php foreach ($meds as $key => $value) { ?>
	<tr>
		<?php $start = new DateTime($value['start_date']); $end = new DateTime($value['end_date']) ?>
		<td style="width: 15%; vertical-align:middle;"><?php echo date_format($start, 'M d') . " - " . date_format($end, 'M d') ?></td>
		<td style="width: 20%;">
			<?php foreach ($value['params']['breakfast'] as $a => $b): ?>
			<?php echo ($b['activity'] != "" ? "<b class='activity_style'>" . strtoupper($b['activity']) . "</b><br/>" : "") ?>
				<?php foreach ($b['med_ops'] as $c => $d): ?>
					<?php 
						$medicine_id 	= $d['medicine_id'];
						$med 			= Inventory::findById(array("id" => $medicine_id));
						$dosage 		= Dosage_Type::findById(array("id" => $med['dosage_type']));
					  	$quantity 		= Quantity_Type::findById(array("id" => $med['quantity_type']));
					 ?>
					
					<?php echo ($d['medicine_name'] != "" ? $d['medicine_name'] . " <b>(" . $d['quantity'] . " " . ($d['quantity'] == 1 ? (substr($quantity['abbreviation'], 0,-1)) : $quantity['abbreviation']) . ")</b><br/>" : "") ?>
					<?php echo ($d['quantity_type'] == 'Others' ? "Taken As: " . $d['quantity_val'] . "<br/>" : ""); ?>
				<?php endforeach ?>
			<br/>
			<?php endforeach ?>
			<br/><span class="instructions_style"><?php echo $value['bf_instructions'] ?></span>
		</td>
		<td style="width: 20%;">
			<?php foreach ($value['params']['lunch'] as $a => $b): ?>
				<?php echo ($b['activity'] != "" ? "<b class='activity_style'>" . strtoupper($b['activity']) . "</b><br/>" : "") ?>
					<?php foreach ($b['med_ops'] as $c => $d): ?>
						<?php 
							$medicine_id 	= $d['medicine_id'];
							$med 			= Inventory::findById(array("id" => $medicine_id));
							$dosage 		= Dosage_Type::findById(array("id" => $med['dosage_type']));
						  	$quantity 		= Quantity_Type::findById(array("id" => $med['quantity_type']));
						 ?>
						
						<?php echo ($d['medicine_name'] != "" ? $d['medicine_name'] . " <b>(" . $d['quantity'] . " " . ($d['quantity'] == 1 ? (substr($quantity['abbreviation'], 0,-1)) : $quantity['abbreviation']) . ")</b><br/>" : "") ?>
						<?php echo ($d['quantity_type'] == 'Others' ? "Taken As: " . $d['quantity_val'] . "<br/>" : ""); ?>
					<?php endforeach ?>
				<br/>
			<?php endforeach ?>
			<br/><span class="instructions_style"><?php echo $value['l_instructions'] ?></span>
		</td>
		<td style="width: 20%;">
			<?php foreach ($value['params']['dinner'] as $a => $b): ?>
				<?php echo ($b['activity'] != "" ? "<b class='activity_style'>" . strtoupper($b['activity']) . "</b><br/>" : "") ?>
				<?php foreach ($b['med_ops'] as $c => $d): ?>
						<?php 
							$medicine_id 	= $d['medicine_id'];
							$med 			= Inventory::findById(array("id" => $medicine_id));
							$dosage 		= Dosage_Type::findById(array("id" => $med['dosage_type']));
						  	$quantity 		= Quantity_Type::findById(array("id" => $med['quantity_type']));
						 ?>
						
						<?php echo ($d['medicine_name'] != "" ? $d['medicine_name'] . " <b>(" . $d['quantity'] . " " . ($d['quantity'] == 1 ? (substr($quantity['abbreviation'], 0,-1)) : $quantity['abbreviation']) . ")</b><br/>" : "") ?>
						<?php echo ($d['quantity_type'] == 'Others' ? "Taken As: " . $d['quantity_val'] . "<br/>" : ""); ?>
					<?php endforeach ?>
				<br/>
			<?php endforeach ?>
			<br/><span class="instructions_style"><?php echo $value['d_instructions'] ?></span>
		</td>
		<td style="width: 10%;">
			<a href="javascript: void(0);" onClick="javascript: edit_regimen_sess(<?php echo $key ?>);" class="table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
			<span style="margin: 0 5px 0 5px; text-align: center;"><img src="<?php echo BASE_FOLDER ?>themes/images/line.png"></span>
			<a href="javascript:void(0);" onclick="javascript: delete_regimen_sess(<?php echo $key ?>);" class="table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
			<span style="margin: 0 5px 0 5px; text-align: center;"><img src="<?php echo BASE_FOLDER ?>themes/images/line.png"></span>
			<a href="javascript:void(0);" onclick="javascript: duplicate_row(<?php echo $key ?>);" class="table_icon" original-title="Duplicate Row"><i class="glyphicon glyphicon-repeat"></i></a>
		</td>
	</tr>
	<?php } ?>
	
</table>