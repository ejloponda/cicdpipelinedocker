<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li>
				<?php if($photo){ ?>
					<img class="photoID" src="<?php echo BASE_FOLDER . $photo['base_path'] . $photo['filename'] . "." . $photo['extension']; ?>">
				<?php } else { ?>
					<img class="photoID" src="<?php echo BASE_FOLDER; ?>themes/images/photo.png">
				<?php } ?>
			</li>
			<li><h1>Patient ID: <?php echo $patient['patient_code'] ?></h1>
				<?php echo $patient['patient_name'] ?></li>
		</ul>
		
		<div class="clear"></div>
	</hgroup>
	<input type="hidden" id="id" value="<?php echo $patient['id'] ?>">
	<h1>Regimen Version</h1><span style="color: black; font-size: 14px;"><b>Version Name</b>: <?php echo $version['version_name'] ?></span>
	<h5><b>Date Generated:</b> <?php $date = new Datetime($reg['date_generated']); $date_generated = date_format($date, "M d, Y"); echo $date_generated; ?></h5>
	<h5><b>Regimen Duration:</b> <span style="width: 100%;"><?php $start_date = new Datetime($version['start_date']); $end_date = new Datetime($version['end_date']); echo date_format($start_date, "M d") . " - " . date_format($end_date, "M d, Y") ; ?></span></h3>
		<!-- <div class="clear"></div> -->
		<table class="table-regimen datatable" style="width: 96%;">
		<th>Date</th>
		<th style='background: #f8941d;'>Breakfast</th>
		<th style='background: #f8941d;'>Lunch</th>
		<th style='background: #f8941d;'>Dinner</th>
		<?php foreach ($meds as $key => $value) { ?>
		<tr>
			<?php $start = new DateTime($value['start_date']); $end = new DateTime($value['end_date']) ?>
			<td style="width: 15%;"><?php echo date_format($start, 'M d') . " - " . date_format($end, 'M d') ?></td>
			<td style="width: 20%;">
				<?php foreach ($value['breakfast'] as $a => $b): ?>
				<?php echo ($b['activity'] != "" ? "<b class='activity_style'>" . strtoupper($b['activity']) . "</b><br/>" : "") ?>
					<?php foreach ($b['med_ops'] as $c => $d): ?>
						<?php 
							$medicine_id 	= $d['medicine_id'];
							$med 			= Inventory::findById(array("id" => $medicine_id));
							$dosage 		= Dosage_Type::findById(array("id" => $med['dosage_type']));
						  	$quantity 		= Quantity_Type::findById(array("id" => $med['quantity_type']));
						 ?>
						
						<?php echo ($d['medicine_name'] != "" ? $d['medicine_name'] . " " . $med['dosage'] . " " . $dosage['abbreviation'] ." <b>(" . $d['quantity'] . " " . ($d['quantity'] == 1 ? (substr($quantity['abbreviation'], 0,-1)) : $quantity['abbreviation']) . ")</b><br/>" : "") ?>
						<?php echo ($d['quantity_type'] == 'Others' ? "Taken As: " . $d['quantity_val'] . "<br/>" : ""); ?>
					<?php endforeach ?>
				<br/>
				<?php endforeach ?>
				<br/><span class="instructions_style"><?php echo $value['bf_instructions'] ?></span>
			</td>
			<td style="width: 20%;">
				<?php foreach ($value['lunch'] as $a => $b): ?>
					<?php echo ($b['activity'] != "" ? "<b class='activity_style'>" . strtoupper($b['activity']) . "</b><br/>" : "") ?>
						<?php foreach ($b['med_ops'] as $c => $d): ?>
							<?php 
								$medicine_id 	= $d['medicine_id'];
								$med 			= Inventory::findById(array("id" => $medicine_id));
								$dosage 		= Dosage_Type::findById(array("id" => $med['dosage_type']));
							  	$quantity 		= Quantity_Type::findById(array("id" => $med['quantity_type']));
							 ?>
							
							<?php echo ($d['medicine_name'] != "" ? $d['medicine_name'] . " " . $med['dosage'] . " " . $dosage['abbreviation'] ." <b>(" . $d['quantity'] . " " . ($d['quantity'] == 1 ? (substr($quantity['abbreviation'], 0,-1)) : $quantity['abbreviation']) . ")</b><br/>" : "") ?>
							<?php echo ($d['quantity_type'] == 'Others' ? "Taken As: " . $d['quantity_val'] . "<br/>" : ""); ?>
						<?php endforeach ?>
					<br/>
				<?php endforeach ?>
				<br/><span class="instructions_style"><?php echo $value['l_instructions'] ?></span>
			</td>
			<td style="width: 20%;">
				<?php foreach ($value['dinner'] as $a => $b): ?>
					<?php echo ($b['activity'] != "" ? "<b class='activity_style'>" . strtoupper($b['activity']) . "</b><br/>" : "") ?>
					<?php foreach ($b['med_ops'] as $c => $d): ?>
							<?php 
								$medicine_id 	= $d['medicine_id'];
								$med 			= Inventory::findById(array("id" => $medicine_id));
								$dosage 		= Dosage_Type::findById(array("id" => $med['dosage_type']));
							  	$quantity 		= Quantity_Type::findById(array("id" => $med['quantity_type']));
							 ?>
							
							<?php echo ($d['medicine_name'] != "" ? $d['medicine_name'] . " " . $med['dosage'] . " " . $dosage['abbreviation'] ." <b>(" . $d['quantity'] . " " . ($d['quantity'] == 1 ? (substr($quantity['abbreviation'], 0,-1)) : $quantity['abbreviation']) . ")</b><br/>" : "") ?>
							<?php echo ($d['quantity_type'] == 'Others' ? "Taken As: " . $d['quantity_val'] . "<br/>" : ""); ?>
						<?php endforeach ?>
					<br/>
				<?php endforeach ?>
				<br/><span class="instructions_style"><?php echo $value['d_instructions'] ?></span>
			</td>
		</tr>
		<?php } ?>
		
	</table>
		
		<div class="line02"></div>
		<ul id="notes">
			<li>Version Remarks</li>
			<li style="width: 540px"><?php echo $version['version_remarks'] ?></li>
		</ul>
		<div class="clear"></div>


		<ul id="notes">
			<li>Regimen Notes</li>
			<li style="width: 540px"><?php echo $version['regimen_notes'] ?></li>
		</ul>
		<div class="clear"></div><br/>
		<ul id="notes" style="margin: -14px 0 20px 0;">
			<li>Preferences</li>
			<li><?php echo $version['preferences'] ?></li>
		</ul>
		<div class="clear"></div><br/>
		<ul id="notes" style="margin: -14px 0 20px 0;">
			<li>Status</li>
			<li><?php echo $version['status'] ?></li>
		</ul>
		<br/>
		<div class="line02"></div>
		<div class="clear"></div>
		<h5><b>Version History</b></h5>
		<table class="datatable table" style="width: 96%;">
			<thead>
				<th style="width: 10%;background: #f8941d;">Version Name</th>
				<th style="width: 20%;background: #f8941d;">Version Remarks</th>
			</thead>
			<tbody>
				<?php foreach ($versions as $key => $value) { ?>
				<tr>
					<td <?php echo ($value['id'] == $version_id) ? 'style="background: #f0f0f0;"' : '' ?>><a href="javascript: void(0);" onclick="javascript: view_version(<?php echo $value['id'] ?>);"><?php echo $value['version_name'] ?></a></td>
					<td <?php echo ($value['id'] == $version_id) ? 'style="background: #f0f0f0;"' : '' ?>><?php echo $value['version_remarks'] ?></td>
				</tr>	
				<?php } ?>
				<tr >
					<td><a href="javascript: void(0);" onclick="javascript: view_regimen_record(<?php echo $patient['id'] ?>,<?php echo "0" ?>);">Original Regimen</a></td>
					<td><span></span></td>
				</tr>
				
			</tbody>
		</table>
		<div class="clear"></div>
	<section id="buttons" style="padding-right:16px;">
		<button class="form_button" onClick="javascript: view_regimen_record(<?php echo $reg_id ?>)">Back to Main</button>
	</section>						
</section>