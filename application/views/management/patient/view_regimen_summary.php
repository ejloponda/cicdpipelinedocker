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

		<ul id="controls">
			<li><a href="javascript: void(0);" onClick="javascript: view_regimen_summary(<?php echo $reg['id'] ?>)"><img class="icon" src="<?php echo BASE_FOLDER?>themes/images/icon_refresh.png"></a></li>
			<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER?>themes/images/dot.png" ></li>
			<li><a href="javascript: void(0);"><img class="icon" src="<?php echo BASE_FOLDER?>themes/images/icon_discontinue.png"></a></li>
			<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER?>themes/images/dot.png" ></li>
			<li><a href="javascript: void(0);"><img class="icon" src="<?php echo BASE_FOLDER?>themes/images/icon_print.png"></a></li>
			<?php #if($rc_reg['can_update']) { ?>
			<!-- <li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER?>themes/images/dot.png" ></li> -->
			<!-- <li><a href="javascript: void(0);" onClick="javascript: edit_regimen(<?php echo $reg['id'] ?>)"><img class="icon" src="<?php echo BASE_FOLDER?>themes/images/icon_edit.png"></a></li> -->
			<?php #} ?>
			<?php #if($rc_reg['can_delete']) { ?>
			<!-- <li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER?>themes/images/dot.png" ></li> -->
			<!-- <li><a href="javascript: void(0);" onClick="javascript: delete_regimen(<?php echo $reg['id'] ?>)"><img class="icon" src="<?php echo BASE_FOLDER?>themes/images/icon_trash.png"></a></li> -->
			<?php #} ?>
		</ul>
		<div class="clear"></div>
	</hgroup>
	<input type="hidden" id="id" value="<?php echo $patient['id'] ?>">
	<input type="hidden" id="header_category" name="header_category" value="Personal">
	<hgroup id="section-header">
			<h1>Regimen Summary</h1>
			<div class="clear"></div>
	</hgroup>	
	<ul style="padding-right: 36px;">
		<li><b>Date Generated:</b> <?php $date = new Datetime($reg['date_generated']); $date_generated = date_format($date, "M d, Y"); echo $date_generated; ?></li>
	</ul>
	<div class="clear"></div>

	<br>

	<div class="meal">Breakfast</div>
	<table class="table" style="width: 97%;">
			<th style="width: 30%;">Medicine Name</th>
			<th style="width: 20%;">Date Period</th>
			<th style="width: 10%;">Duration Period</th>
			<th style="width: 10%;">Quantity</th>
			<th style="width: 10%;">Availability</th>

			<?php $count = 0; ?>	
			<?php foreach ($bf as $key => $value) { ?>
				<?php $med = Inventory::findById(array("id" => $value['medicine_id'])); 
				if ($med) { ?>
					<tr>
						<td <?php echo ($med['stock'] == "A-List" ? 'class="blue"' : '') ?>><?php echo $value['medicine_name'] ?> (<?php echo $med['dosage'] ?> mg)</td>
						<td>
							<?php 
							$start = new Datetime($value['start_date']);
							$end = new Datetime($value['end_date']);
							echo date_format($start, "M d") . " - " . date_format($end, "M d");
							?>
						</td>
						<td>
							<?php 
								$interval = $start->diff($end);
								echo $interval->format('%a days'); 
							?>
						</td>
						<td><?php echo $value['quantity']; ?> tablets</td>
						<td style="text-align:center;"><input type="checkbox" name="bf_availability_<?php echo $count; ?>" checked></td>
					</tr>
				<?php } ?>
			<?php $count++; } ?>
			
	</table>

	<div class="meal">Lunch</div>
	<table class="table" style="width: 97%;">
			<th style="width: 30%;">Medicine Name</th>
			<th style="width: 20%;">Date Period</th>
			<th style="width: 10%;">Duration Period</th>
			<th style="width: 10%;">Quantity</th>
			<th style="width: 10%;">Availability</th>
			
			<?php $count = 0; ?>	
			<?php foreach ($lunch as $key => $value) { ?>
				<?php $med = Inventory::findById(array("id" => $value['medicine_id'])); 
				if ($med) { ?>
					<tr>
						<td <?php echo ($med['stock'] == "A-List" ? 'class="blue"' : '') ?>><?php echo $value['medicine_name'] ?> (<?php echo $med['dosage'] ?> mg)</td>
						<td>
							<?php 
							$start = new Datetime($value['start_date']);
							$end = new Datetime($value['end_date']);
							echo date_format($start, "M d") . " - " . date_format($end, "M d");
							?>
						</td>
						<td>
							<?php 
								$interval = $start->diff($end);
								echo $interval->format('%a days'); 
							?>
						</td>
						<td><?php echo $value['quantity']; ?> tablets</td>
						<td style="text-align:center;"><input type="checkbox" name="bf_availability_<?php echo $count; ?>" checked></td>
					</tr>
				<?php } ?>
			<?php $count++; } ?>
	</table>

	<div class="meal">Dinner</div>
	<table class="table" style="width: 97%;">
			<th style="width: 30%;">Medicine Name</th>
			<th style="width: 20%;">Date Period</th>
			<th style="width: 10%;">Duration Period</th>
			<th style="width: 10%;">Quantity</th>
			<th style="width: 10%;">Availability</th>
			
			<?php $count = 0; ?>	
			<?php foreach ($dinner as $key => $value) { ?>
				<?php $med = Inventory::findById(array("id" => $value['medicine_id'])); 
				if ($med) { ?>
					<tr>
						<td <?php echo ($med['stock'] == "A-List" ? 'class="blue"' : '') ?>><?php echo $value['medicine_name'] ?> (<?php echo $med['dosage'] ?> mg)</td>
						<td>
							<?php 
							$start = new Datetime($value['start_date']);
							$end = new Datetime($value['end_date']);
							echo date_format($start, "M d") . " - " . date_format($end, "M d");
							?>
						</td>
						<td>
							<?php 
								$interval = $start->diff($end);
								echo $interval->format('%a days'); 
							?>
						</td>
						<td><?php echo $value['quantity']; ?> tablets</td>
						<td style="text-align:center;"><input type="checkbox" name="bf_availability_<?php echo $count; ?>" checked></td>
					</tr>
				<?php } ?>
			<?php $count++; } ?>
	</table>

	<div class="meal">Summary</div>
	<table class="table" >
			<th>Medicine Name</th>
			<th>From Stock</th>
			<th>Total Quantity</th>
			
			<?php foreach ($regimen_summary as $key => $value) { ?>
				<?php $med = Inventory::findById(array("id" => $value));
				$medicine  = Regimen_Med_List::SumByRegimenIdMedId(array("regimen_id" => $reg['id'], "medicine_id" => $value));
					if($med){ ?>
						<tr <?php echo ($med['stock'] == "A-List" ? 'class="blue"' : '') ?>>
							<td><?php echo $med['medicine_name'] ?> (<?php echo $med['dosage'] ?> mg)</td>
							<td><?php echo $med['stock'] ?></td>
							<td><?php echo $medicine['Total']; ?> tablets</td>
						</tr>
					<?php } ?>
			<?php } ?>
			<?php foreach ($meds as $key => $value) { ?>
				<?php $med = Inventory::findById(array("id" => $value['l_med_id'])); 
					if($med){ ?>
						<tr <?php echo ($med['stock'] == "A-List" ? 'class="blue"' : '') ?>>
							<td><?php echo $value['l_med_name'] ?> (<?php echo $med['dosage'] ?> mg)</td>
							<td><?php echo $med['stock'] ?></td>
							<td><?php echo $value['l_quantity']; ?> tablets</td>
						</tr>
					<?php } ?>
			<?php } ?>
			<?php foreach ($meds as $key => $value) { ?>
				<?php $med = Inventory::findById(array("id" => $value['d_med_id'])); 
					if($med){ ?>
						<tr <?php echo ($med['stock'] == "A-List" ? 'class="blue"' : '') ?>>
							<td><?php echo $value['d_med_name'] ?> (<?php echo $med['dosage'] ?> mg)</td>
							<td><?php echo $med['stock'] ?></td>
							<td><?php echo $value['d_quantity']; ?> tablets</td>
						</tr>
					<?php } ?>
			<?php } ?>
	</table>


	<br>
	<section id="buttons">
		<button class="form_button-green" onClick="javascript: void(0);">Print Summary</button>
		<button class="form_button" onclick="javascript: view_regimen_record(<?php echo $reg['id'] ?>);">Cancel</button>
		<?php #if($rc_reg['can_update']) { ?>
		<!-- <button class="form_button" onClick="javascript: edit_regimen(<?php echo $reg['id'] ?>)">Edit Summary</button> -->
		<?php #} ?>
		<?php #if($rc_reg['can_delete']) { ?>
		<!-- <button class="form_button" onClick="javascript: delete_regimen(<?php echo $reg['id'] ?>)">Delete Summary</button> -->
		<?php #} ?>
	</section>						
		<div class="clear"></div>
		<!-- <section id="buttons">
			<button class="previous_button" onClick="javascript: view_personal_medical_history();">Previous</button>
			<img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png">
			<button class="next_button" onClick="#">Next</button>
		</section>	 -->
</section>