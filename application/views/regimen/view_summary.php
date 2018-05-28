<script type="text/javascript">
	$(function(){
		if($('.tipsy-inner')) {
			$('.tipsy-inner').remove();
		}
	});
</script>

<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER?>themes/images/header-regimen.png"></li>
			<li><h1>Regimen</h1></li>
		</ul>
		
		<ul id="controls">
			<li><a href="javascript: void(0);" onClick="javascript: view_regimen_summary(<?php echo $reg['id'] ?>)"><img class="icon" src="<?php echo BASE_FOLDER?>themes/images/icon_refresh.png"></a></li>
			<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER?>themes/images/dot.png" ></li>
			<li><a href="javascript: void(0);"><img class="icon" src="<?php echo BASE_FOLDER?>themes/images/icon_discontinue.png"></a></li>
			<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER?>themes/images/dot.png" ></li>
			<li><a href="javascript: void(0);" onClick="javascript: print_summary(<?php echo $reg['id'] ?>, <?php echo $version_id ?>);"><img class="icon" src="<?php echo BASE_FOLDER?>themes/images/icon_print.png"></a></li>
			<?php if($rc_reg['can_update']) { ?>
			<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER?>themes/images/dot.png" ></li>
			<li><a href="javascript: void(0);" onClick="javascript: edit_regimen(<?php echo $reg['id'] ?>, 0)"><img class="icon" src="<?php echo BASE_FOLDER?>themes/images/icon_edit.png"></a></li>
			<?php } ?>
			<?php if($rc_reg['can_delete']) { ?>
			<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER?>themes/images/dot.png" ></li>
			<li><a href="javascript: void(0);" onClick="javascript: delete_regimen(<?php echo $reg['id'] ?>)"><img class="icon" src="<?php echo BASE_FOLDER?>themes/images/icon_trash.png"></a></li>
			<?php } ?>
		</ul>
		
		<div class="clear"></div>
	</hgroup>
	

	<ul class="regimen-ID" >
		<li><img class="photoID" src="<?php echo ($photo ?  BASE_FOLDER . $photo['base_path'] . $photo['filename'] . '.' . $photo['extension'] : BASE_FOLDER . 'themes/images/photo.png') ?>"></li>
		<li class="patient"><span><?php echo $patient['patient_name'] ?></span><br>
			Patient ID <b><?php echo $patient['patient_code'] ?></b> 
			<?php if($version) { ?> 
			Version Name: <b><?php echo $version['version_name'] ?></b>
			<?php } else { ?>
			Regimen Number: <b><?php echo $reg['regimen_number'] ?></b>
			<?php } ?>
		</li>
	</ul>
	
	<ul id="filter-search" style="padding-right: 36px;">
		<li><b>Date Generated:</b> <?php $date = new Datetime($reg['date_generated']); $date_generated = date_format($date, "M d, Y"); echo $date_generated; ?></li>
	</ul>
	<ul id="filter-search" style="padding-right: 36px;">
		<li><b>Regimen Duration:</b> <?php $start_date = new Datetime($reg['start_date']); $end_date = new Datetime($reg['end_date']); echo date_format($start_date, "M d") . " - " . date_format($end_date, "M d, Y") ; ?></li>
	</ul>
	<div class="clear"></div>
	
	<br>
	
	<div class="meal">Breakfast</div>
	<table class="table" style="width: 97%;">
			<th style="width: 30%;">Medicine Name</th>
			<th style="width: 20%;">Date Period</th>
			<th style="width: 10%;">Duration</th>
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
								$time_interval = (int) $interval->format('%a');
								$time_interval = $time_interval + 1;
								echo $time_interval . " days"; 
							?>
						</td>
						<?php $quantity = (int)  $value['quantity'] * $time_interval ?>
						<td><?php echo $quantity ?></td>
						<td style="text-align:center;"><input type="checkbox" class="medicine_availability" data-id="<?php echo $value['id'] ?>" name="bf_availability_<?php echo $count; ?>" <?php echo ($value['taken'] == 1 ? "checked disabled" : "") ?>></td>
					</tr>
				<?php } ?>
			<?php $count++; } ?>
			
	</table>
	
	<div class="meal">Lunch</div>
	<table class="table" style="width: 97%;">
			<th style="width: 30%;">Medicine Name</th>
			<th style="width: 20%;">Date Period</th>
			<th style="width: 10%;">Duration</th>
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
								$time_interval = (int) $interval->format('%a');
								$time_interval = $time_interval + 1;
								echo $time_interval . " days"; 
							?>
						</td>
						<?php $quantity = (int)  $value['quantity'] * $time_interval ?>
						<td><?php echo $quantity; ?></td>
						<td style="text-align:center;"><input type="checkbox" class="medicine_availability" data-id="<?php echo $value['id'] ?>" name="bf_availability_<?php echo $count; ?>" <?php echo ($value['taken'] == 1 ? "checked disabled" : "") ?>></td>
					</tr>
				<?php } ?>
			<?php $count++; } ?>
	</table>
	
	<div class="meal">Dinner</div>
	<table class="table" style="width: 97%;">
			<th style="width: 30%;">Medicine Name</th>
			<th style="width: 20%;">Date Period</th>
			<th style="width: 10%;">Duration</th>
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
								$time_interval = (int) $interval->format('%a');
								$time_interval = $time_interval + 1;
								echo $time_interval . " days";
							?>
						</td>
						<?php $quantity = (int)  $value['quantity'] * $time_interval ?>
						<td><?php echo $quantity; ?></td>
						<td style="text-align:center;"><input type="checkbox" class="medicine_availability" data-id="<?php echo $value['id'] ?>" name="bf_availability_<?php echo $count; ?>" <?php echo ($value['taken'] == 1 ? "checked disabled" : "") ?>></td>
					</tr>
				<?php } ?>
			<?php $count++; } ?>
	</table>
	
	<div class="meal">Summary</div>
	<table class="table" >
			<th>Medicine Name</th>
			<th>From Stock</th>
			<th>Total Quantity</th>
			
			<?php foreach ($summary_meds as $key => $value) { ?>
				<?php $med = Inventory::findById(array("id" => $value['medicine_id']));
				// $medicine  = Regimen_Med_List::SumByRegimenIdMedId(array("regimen_id" => $reg['id'], "medicine_id" => $value['medicine_id']));
					if($med){ ?>
						<tr <?php echo ($med['stock'] == "A-List" ? 'class="blue"' : '') ?>>
							<td><?php echo $med['medicine_name'] ?> (<?php echo $med['dosage'] ?> mg)</td>
							<td><?php echo $med['stock'] ?></td>
							<td><?php echo $value['quantity']; ?></td>
						</tr>
					<?php } ?>
			<?php } ?>
	</table>
	
	
	<br>
	<section id="buttons" style="padding-right:16px;">
		<button type="button" class="form_button-green" onClick="javascript: print_summary(<?php echo $reg['id'] ?>, <?php echo $version_id ?>);">Print Summary</button>
		<?php if($invoicing['can_add']){ ?>
			<?php if(!$reg['converted']){ ?>
				<button type="button" class="form_button" onClick="javascript: convertInvoice(<?php echo $reg['id'] ?>, <?php echo $version_id ?>);">Convert to Invoice</button>
			<?php } ?>
		<?php } ?>
		<button type="button" class="form_button back_to_regimen">Back</button>
	</section>						

	<script>
		$(function(){
			var regimen_id = "<?php echo $reg['id'] ?>";
			var version_id = "<?php echo $version_id ?>";
			$(".back_to_regimen").on('click', function(){
				if(version_id == 0){
					view_regimen(regimen_id);
				} else {
					view_version(version_id);
				}
			});

			$(".medicine_availability").on('click', function(){

				var id = $(this).data("id");

				var x = confirm("Is it availble?");
				if(x){
					$.post(base_url+'regimen_management/update_availability',{id:id},function(){
						view_regimen_summary(regimen_id);
					});
				}
			});
		});

		function print_summary(regimen_id, version_id){
			window.open(base_url+'download/generate_summary/'+regimen_id+'/'+version_id,"_blank");
		}
	</script>
</section>