<div class="modal-dialog" style="width:60%; word-wrap:normal;">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<b>Regimen: </b><?php echo $regimen['regimen_number'] ?> <?php echo ($version_id ? "<b>Version Name: </b>" . $version['version_name'] : "") ?>
		</div>
		<div class="modal-body">
			<div class="meal">Breakfast</div>
				<table class="table" style="width: 97%;">
						<th style="width: 30%;">Medicine Name</th>
						<th style="width: 20%;">Date Period</th>
						<th style="width: 10%;">Duration</th>
						<th style="width: 10%;">Quantity</th>

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
											echo $interval->format('%a days'); 
										?>
									</td>
									<?php $quantity = (int)  $value['quantity'] * $time_interval ?>
									<td><?php echo $quantity ?></td>
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
											echo $interval->format('%a days'); 
										?>
									</td>
									<?php $quantity = (int)  $value['quantity'] * $time_interval ?>
									<td><?php echo $quantity; ?></td>
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
											echo $interval->format('%a days'); 
										?>
									</td>
									<?php $quantity = (int)  $value['quantity'] * $time_interval ?>
									<td><?php echo $quantity; ?></td>
								</tr>
							<?php } ?>
						<?php $count++; } ?>
				</table>
		</div>
	</div>
</div>