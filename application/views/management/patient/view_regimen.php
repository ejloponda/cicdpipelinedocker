<script>
	 $(function() {
        $('#version_history_dt').dataTable( {
	        "bJQueryUI": true,
	        "sPaginationType": "full_numbers",
	        "bPaginate": false,
	        "bLengthChange": false,
	        "bFilter": true,
	        "bInfo": false,     
	        "bScrollCollapse": false
        });

       $('#version_history_dt_length').hide();
       $('#version_history_dt_filter').hide();
      });
	 $('.regimen-tbl').dataTable({
			"bJQueryUI": true,
		"bFilter": true,
		"bLengthChange": false,
		"bPaginate": false,
		"bInfo": false,
		"oLanguage": { "sSearch": "" },
			"oLanguage": { "sSearch": '<a class="btn searchBtn" id="searchBtn"><i class="fa fa-search"></i></a>' },
	})
	 $('.own-medication-tbl').dataTable({
			"bJQueryUI": true,
		"bFilter": true,
		"bLengthChange": false,
		"bPaginate": false,
		"bInfo": false,
		"oLanguage": { "sSearch": "" },
			"oLanguage": { "sSearch": '<a class="btn searchBtn" id="searchBtn"><i class="fa fa-search"></i></a>' },
	})
	 $(".dataTables_filter input").attr("placeholder", "Search for supplement or ingredient");

	 function addData() {
      $('#add_med').on('click', function (e) {
      	$('.save-cancel-btn-wrapper').show();
    	 e.preventDefault();
        $('.own-medication-tbl').append('<tr><td><input type="text"/></td><td><input type="text"/></td><td><input type="text"/></td><td><input type="text"/></td><td><input type="text"/></td><td><input type="text"/></td></tr>');
    });
    function del() {
        $('.own-medication-tbl tr').hide($('tr').index()-1);
    };
}
addData();
</script>

<section class="area patient-dashboard-section view-regimen">

	<input type="hidden" id="id" value="<?php echo $patient_id ?>">
	<div class="col-md-12 dashboard-patient-wrapper no-padding">
		<div class="col-md-12 patient-info-wrapper no-padding add-test-info">
				<ul>
					<li>
						<label>Patient Name:</label>
						<p><?php echo $patient['patient_name'];?></p>
					</li>
					<li class="nickname">
						<label>Nickname:</label>
						<p><?php echo $patient['firstname'];?></p>
					</li>
					<li class="col-third">
						<label>Patient ID:</label> <span><?php echo $patient['patient_code'];?></span>
						<br>
						<label>Gender:</label> <span><?php echo $patient['gender'];?></span>
					</li>
					<li class="col-third">
						<label>Date of Birth: </label><span><?php echo $patient['birthdate'];?></span>
						<br>
						<label>Age:</label><span><?php echo $patient['age'];?></span>
					</li>
				</ul>
			</div>
		<div class="col-md-12  with-border">
			<div class="header-top-wrapper regimen-top">
			<h1>Regimen</h1>
			<a href="" class="def-btn view-records-btn create-regi">Create New Regimen</a>
		</div>
			<h5 class="date-gen"><b>DATE GENERATED:</b><span> <?php $date = new Datetime($dgenerated); $date_generated = date_format($date, "M d, Y"); echo $date_generated; ?></span></h5>
			
			<!-- <div class="clear"></div> -->
			<div class="table-wrapper">
			<table class="table-regimen regimen-tbl datatable" style="width: 100%;">
			<thead>
		        <th class="th-head" style="width:15%">regimen number</th>
		        <th class="th-head" style="width:5%">lmp</th>
		        <th class="th-head" style="width:5%">program</th>
		        <th class="th-head" style="width:10%">duration</th>
		        <th class="th-head" style="width:20%">Breakfast</th>
		        <th class="th-head" style="width:20%">lunch</th>
		        <th class="th-head" style="width:20%">dinner</th>
		    </thead>
			<?php foreach ($meds as $key => $value) { ?>
			<tr>
				<td style="width:15%">REG-00892</td>
				<td style="width:5%">N/A</td>
				<td style="width:5%">N/A</td>
				<?php $start = new DateTime($value['start_date']); $end = new DateTime($value['end_date']) ?>
				<td style="width:10%"><?php echo date_format($start, 'M d') . " - " . date_format($end, 'M d') ?></td>
				<td style="width:20%">
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
				<td style="width:20%">
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
				<td style="width:20%">
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
		</div>

			<div class="col-md-12 regimen-notes-wrapper no-padding">
				<div class="col-md-6">
					<h5>Regimen Notes</h5>
					<div class="notes-hldr"><?php echo $_regimen_notes ?></div>
				</div>
				<div class="col-md-3">
					<h5>Preferences</h5>
					<div class="notes-hldr"><?php echo $_preferences ?></div>
				</div>
				<div class="col-md-3">
					<h5>Status</h5>
					<div class="notes-hldr"><?php echo $_status ?></div>
				</div>
			</div>
			<div class="clear"></div>
			
			<div class="table-wrapper regimen-history">
			<h5><b>Version History</b></h5>
				<table class="datatable table">
					<thead>
						<th style="width: 50%">Version Name</th>
						<th style="width: 50%">Version Remarks</th>
					</thead>
					<tbody>
						<?php foreach ($versions as $key => $value) { ?>
						<tr >
							<td><a href="javascript: void(0);" onclick="javascript: view_version(<?php echo $value['id'] ?>);"><?php echo $value['version_name'] ?></a></td>
							<td><?php echo $value['version_remarks'] ?></td>
						</tr>	
						<?php } ?>
						<tr >
							<td><a href="javascript: void(0);" onclick="javascript: view_regimen_record(<?php echo $patient['id'] ?>,<?php echo "0" ?>);">Original Regimen</a></td>
							<td><span></span></td>
						</tr>
					</tbody>
				</table>
			</div>
			
			<div class="clear"></div>
			
			<!-- <hgroup id="section-header">
			<h1>Uploaded Files</h1>
			</hgroup>
			<table class="datatable table category_list_dt">
	 			<thead>
	 				<th style="width: 20%; text-align: center;">File Title</th>
	 				<th style="width: 20%; text-align: center;">File Description</th>
	 				<th style="width: 20%; text-align: center;">File Uploaded / File Updated</th>
	 				<th style="width: 10%; text-align: center;"></th>
	 			</thead>
	 			<tbody>
	 				<?php 
	 					$files = Patient_Files::findAllByPatientIdCategory(array("patient_id" => $patient['id'], "category_id" => 3));
	 				?>
	 				<?php foreach ($files as $a => $b) { ?>		
		 				<tr>
		 					<td class="first"><?php echo $b['title'] ?></td>
		 					<td><?php echo $b['description'] ?></td>
		 					<td><?php echo Tool::getHumanTimeDifference($b['date_created']) ?> / <?php echo Tool::getHumanTimeDifference($b['date_updated']) ?></td>
		 					<td><a href="javascript: void(0);" onclick="view_uploaded_file(<?php echo $b['id'] ?>)"><span title="View File" class="glyphicon glyphicon-eye-open view_file"></span></a></td>
		 				</tr>
	 				<?php } ?>
	 			</tbody>
	 		</table> -->
	 		<div class="clear"></div>
				
			<div class="clear"></div>
		<!-- <section id="buttons">
			<button class="previous_button" onClick="javascript: view_personal_medical_history();">Previous</button>
			<img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png">
			<button class="next_button" onClick="#">Next</button>
		</section>	 -->
	</div>
	<div class="col-md-12 own-medication-wrapper with-border">
			<div class="header-top-wrapper">
				<h1>Own Medication</h1>
				<a href="" class="def-btn view-records-btn">View Records</a>
				<button class="def-btn" id="add_med">Add New Medication</button>
			</div>
			<div class="table-wrapper">
				<table class="own-medication-tbl">
					<thead>
						<th>SUPPLEMENT</th>
						<th>MANUFACTURER</th>
						<th>DOSAGE</th>
						<th>DURATION</th>
						<th>SCHEDULE OF INTAKE</th>
						<th style="width:30%">INGREDIENTS</th>
					</thead>
					<tbody>
						<tr>
							<td>Ultimate Carb Control</td>
							<td>N/A</td>
							<td>2 Capsules</td>
							<td>FEB 23, 2017 to MAR 23, 2017</td>
							<td>UPON WAKING UP, Before breakfast</td>
							<td>White Kidney Bean, Gelatin Capsule, Rice Powder,
							Vegetable Magnesium Stearate, Silica</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="save-cancel-btn-wrapper">
				<button class="def-btn view-records-btn cancel-btn">Cancel</button>
				<button  class="def-btn view-records-btn">Save</button>
			</div>
		</div>
	</div>
	<div class="clear"></div>
	<section id="buttons" class="cancel-hldr">
		<!-- <button class="form_button-green" onClick="javascript: view_regimen_summary(<?php echo $reg['id'] ?>);">Generate Summary</button> -->
		<!-- <button class="form_button" onClick="$('#regimen_general_form').submit();">Save & Continue</button> -->
		<button class="form_button def-btn cancel-btn" onclick="javascript: view_regimen();">Cancel</button>
	</section>
	<div class="clear"></div>
</section>
<div class="clear"></div>
<script>
	$(function() {
        $('.category_list_dt').dataTable( {
	        "bJQueryUI": true,
	        "sPaginationType": "full_numbers",
	        "bPaginate": false,
	        "bLengthChange": false,
	        "bFilter": true,
	        "bInfo": false,     
	        "bScrollCollapse": false
        });

      	$(".view_file").tipsy({gravity: 's'});
      });

</script>