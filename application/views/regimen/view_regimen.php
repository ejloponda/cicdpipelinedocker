<style>
.activity_style{color:red;font-size: 14px;}
.instructions_style{color:black;}
</style>
<script>
	$(function(){
		if($('.tipsy-inner')) {
			$('.tipsy-inner').remove();
		}

	    reset_all();
		reset_all_topbars_menu();
		$('.regimen_menu').addClass('hilited');
		$('.regimen_list_form').addClass('sub-hilited');

		$(".medicine_status").tipsy({gravity: 's'});
	})
</script>
<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-regimen.png"></li>
			<li><h1>Regimen</h1></li>
		</ul>
		
		<div class="clear"></div>
	</hgroup>
	
		<ul class="regimen-ID" >
			<li><img class="photoID" src="<?php echo ($photo ?  BASE_FOLDER . $photo['base_path'] . $photo['filename'] . '.' . $photo['extension'] : BASE_FOLDER . 'themes/images/photo.png') ?>"></li>
			<li class="patient">
				<b style="font-size: 20px;"><span><?php echo $patient['patient_name'] ?></span></b>
			<br>Patient ID: <b><?php echo $patient['patient_code'] ?></b> Regimen Number: <b><?php echo $reg['regimen_number'] ?></b></li>
		</ul>
		
		<ul id="filter-search" style="padding-right: 36px;">
			<li><b>Date Generated:</b> <?php $date = new Datetime($dgenerated); $date_generated = date_format($date, "M d, Y"); echo $date_generated; ?></li>
		</ul>
		<div class="clear"></div>

		<h5><b>Regimen Duration:</b> <span style="width: 100%;"><?php $start_date = new Datetime($sdate); $end_date = new Datetime($edate); echo date_format($start_date, "M d") . " - " . date_format($end_date, "M d, Y") ; ?></span></h3>
		<!-- <div class="clear"></div> -->
		<ul>
			<li style="display:inline-block;"><b> LMP: </b></li>
			<li style="display:inline-block;"><?php echo $_lmp?></li>
		</ul>
		<ul>
			<li style="display:inline-block;"><b>Program: </b></li>
			<li style="display:inline-block;"><?php echo $_program?></li>
		</ul>
		<style type="text/css">
			tbody{
				vertical-align: baseline !important;
			}
		</style>
		<table class="table-regimen datatable" style="width: 96%;">
		<th>Date</th>
		<th style='background: #f8941d;'>Breakfast</th>
		<th style='background: #f8941d;'>Lunch</th>
		<th style='background: #f8941d;'>Dinner</th>
		<?php foreach ($meds as $key => $value) { ?>
		<tr>
			<?php $start = new DateTime($value['start_date']); $end = new DateTime($value['end_date']) ?>
			<td style="width: 15%; vertical-align:middle;"><?php echo date_format($start, 'M d') . " - " . date_format($end, 'M d') ?></td>
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
						
						<?php echo ($d['medicine_name'] != "" ? $d['medicine_name'] . "  <b>(" . $d['quantity'] . " " . ($d['quantity'] == 1 ? (substr($quantity['abbreviation'], 0,-1)) : $quantity['abbreviation']) . ")</b><br/>" : "") ?>
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
							
							<?php echo ($d['medicine_name'] != "" ? $d['medicine_name'] . " <b>(" . $d['quantity'] . " " . ($d['quantity'] == 1 ? (substr($quantity['abbreviation'], 0,-1)) : $quantity['abbreviation']) . ")</b><br/>" : "") ?>
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
							
							<?php echo ($d['medicine_name'] != "" ? $d['medicine_name'] . " <b>(" . $d['quantity'] . " " . ($d['quantity'] == 1 ? (substr($quantity['abbreviation'], 0,-1)) : $quantity['abbreviation']) . ")</b><br/>" : "") ?>
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
			<li>Regimen Notes</li>
			<li style="width: 540px"><?php echo $_regimen_notes ?></li>
		</ul>
		<div class="clear"></div><br/>
		<ul id="notes" style="margin: -14px 0 20px 0;">
			<li>Preferences</li>
			<li><?php echo $_preferences ?></li>
		</ul>
		<div class="clear"></div><br/>
		<ul id="notes" style="margin: -14px 0 20px 0;">
			<li>Status</li>
			<li><?php echo $_status ?></li>
		</ul>
		<br/>

		<?php if(!empty($versions)){ ?>
		<div class="line02"></div>
		<div class="clear"></div>
		<h5><b>Version History</b></h5>
		<table class="datatable table" style="width: 96%;">
			<thead>
				<th style="width: 10%;background: #f8941d;">Version Name</th>
				<th style="width: 20%;background: #f8941d;">Version Remarks</th>
				<th style="width: 5%;background: #f8941d;"></th>
			</thead>
			<tbody>
				<?php foreach ($versions as $key => $value) { ?>

				<?php 
					$pl = array(
					"regimen_id" => $reg['id'],
					"version_id" => $value['id']
					);
					$medicine_count = Regimen_Med_List::countByRegAndVersionID($pl);
				?>
				<tr >
					<td><a href="javascript: void(0);" class="medicine_status" <?php echo ($medicine_count > 0 ? "Title='Incomplete Medicine'" : "Title='Complete Medicine'") ?> onclick="javascript: view_version(<?php echo $value['id'] ?>);"><?php echo $value['version_name'] ?></a></td>
					<td><span <?php echo ($medicine_count > 0 ? "style='background:yellow;'" : "") ?>><?php echo $value['version_remarks'] ?></span></td>
					<td>
						
						<a href="javascript: void(0);" onclick="javascript: edit_regimen(<?php echo $reg['id'] ?>, <?php echo $value['id'] ?>)" class="edit_regimen table_icon" original-title="Edit">
						<i class="glyphicon glyphicon-edit"></i></a>
						<span style="margin: 0 5px 0 5px; text-align: center;">
							<img src="<?php echo BASE_FOLDER ?>themes/images/line.png"></span>
							
							<a href="javascript: void(0);" onclick="javascript: delete_version_regimen(<?php echo $value['id'] ?>)" class="delete_regimen table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a></td>
				</tr>	
				<?php } ?>

				<tr >
					<td><a href="javascript: void(0);" onclick="javascript: view_regimen(<?php echo $reg['id'] ?>,<?php echo "0" ?>);">Original Regimen</a></td>
					<td><span></span></td>
					<td>
						
						<a href="javascript: void(0);" onclick="javascript: edit_regimen(<?php echo $reg['id'] ?>, <?php echo "0" ?>)" class="edit_regimen table_icon" original-title="Edit">
						<i class="glyphicon glyphicon-edit"></i></a>
						<span style="margin: 0 5px 0 5px; text-align: center;">
							
							
							</td>
				</tr>	
				
			</tbody>
		</table>
		<?php } ?>
		<div class="clear"></div>

	<!-- Adding Print PDF Button -->
	<!-- <section id="buttons" style="padding-right:16px; padding-bottom: 5px;">	
		<button class="form_button-green" onClick="javascript: print_pdf(<?php echo $reg['id'] ?>, <?php echo $version_id; ?>);">Print to PDF</button>
	</section> -->
	<!--  -->

	<section id="buttons" style="padding-right:16px;">
		<?php if($versioning['can_add']) { ?>
		<button class="form_button-green" onClick="javascript: createVersion(<?php echo $reg['id'] ?>, <?php echo $version_id; ?>)">Create new Version</button>
		<?php } ?>
		<button class="form_button-green" onClick="javascript: print_pdf(<?php echo $reg['id'] ?>, <?php echo $version_id; ?>);">Print to PDF</button>
		<!-- <button class="form_button-green" onClick="javascript: print_word(<?php echo $reg['id'] ?>, <?php echo $version_id; ?>);">Print to Word</button>
		<button class="form_button-green" onClick="javascript: view_regimen_summary(<?php echo $reg['id'] ?>, <?php echo $version_id; ?>);">Generate Summary</button> -->
		<?php if($rc_reg['can_update']) { ?> 
		<button class="form_button" onClick="javascript: edit_regimen(<?php echo $reg['id'] ?>, <?php echo $version_id ?>)">Edit</button>
		<?php } ?>
		<?php if($rc_reg['can_delete']) { ?>
		<button class="form_button" onClick="javascript: delete_regimen(<?php echo $reg['id'] ?>)">Delete</button>
		<?php } ?>
		<!-- <button class="form_button" onClick="$('#regimen_general_form').submit();">Save & Continue</button> -->
		<button class="form_button regimen_general_cancel_button">Cancel</button>
	</section>						
</section>

<!-- Adding Javascript for print pdf -->
<script>
function print_pdf(regimen_id, version_id){
			window.open(base_url+'download/print_pdf/'+regimen_id+'/'+version_id,"_blank");
		}
function print_word(regimen_id, version_id){
			window.open(base_url+'download/print_word/'+regimen_id+'/'+version_id,"_blank");
		}
</script>
<?php unset($_SESSION['view_regimen_meds']); ?>