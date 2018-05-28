<script>
	$(function(){
		contact_information_list(<?php echo $patient['id'] ?>,true);
		contact_person_list(<?php echo $patient['id'] ?>,true);
	});
</script>
<section class="area patient-view-wrapper-sec">
	<hgroup id="area-header">
		<input type="hidden" id="id" value="<?php echo $patient['id'] ?>">
			<ul class="page-title">
				<li>
					<?php if($patient_image){ ?>
					<img class="photoID" src="<?php echo BASE_FOLDER . $patient_image['base_path'] . $patient_image['filename'] . "." . $patient_image['extension']; ?>">
					<?php } else { ?>
					<img class="photoID" src="<?php echo BASE_FOLDER; ?>themes/images/photo.png">
					<?php } ?>
				</li>
				<li>
				<h1>Patient ID: <?php echo $patient['patient_code'] ?></h1>
					<?php echo $patient['patient_name'] ?>
				</li>
			</ul>
			
			<ul id="controls">
				<?php if($pm_pi['can_update']){ ?>
				<li><a href="javascript: void(0);" onclick="javascript: edit_patient(<?php echo $patient['id'] ?>)"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_edit.png"></a></li>
				<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
				<?php } ?>
				<li><a href="#"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_print.png"></a></li>
				<?php if($pm_pi['can_delete']){ ?>
				<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
				<li><a href="javascript: void(0);" onclick="javascript: delete_patient(<?php echo $patient['id'] ?>);"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_trash.png"></a></li>
				<?php } ?>
			</ul>
			<div class="clear"></div>
			<br/>
		</hgroup>
		<section id="left" class="patient-details-top-row left">
			<h1>Patient Details</h1>
			<div class="holder patient-holder">
			<ul id="patient-data">
				<li>Initial Appointment Date</li>
				<li><?php echo date("F d Y", strtotime($patient['appointment'])) ?></li>
			</ul>
			<section class="clear"></section>
			<ul id="patient-data">
				<li>Gender</li>
				<li><?php echo $patient['gender'] ?></li>
			</ul>
			<section class="clear"></section>
			<ul id="patient-data">
				<li>Age</li>
				<li><?php echo $patient['age'] ?></li>
			</ul>
			<section class="clear"></section>
			<ul id="patient-data">
				<li>Date of Birth</li>
				<li><?php echo $patient['birthdate'] ?></li>
			</ul>
			<section class="clear"></section>
			<ul id="patient-data">
				<li>Place of Birth</li>
				<li><?php echo $patient['placeofbirth'] ?></li>
			</ul>
			<section class="clear"></section>
			<ul id="patient-data">
				<li>Current Address</li>
				<li><?php echo $patient['address'] ?><br/> <?php echo $patient['address_2'] ?> <br/>
				<?php echo ($patient['city'] == '' ? "" : " " . $patient['city']) ?>
				<!-- <br/><?php echo $patient['state'] . ($patient['zip'] == '' ? "" : ", " . $patient['zip'])?></li> -->
				<br/><?php echo $patient['zip'] ?></li>
			</ul>
			<section class="clear"></section>
			<ul id="patient-data">
				<li>Secondary Address</li>
				<li><?php echo $patient['secondary_address'] ?><br/> <?php echo $patient['secondary_address_2'] ?> <br/>
					<?php echo ($patient['secondary_city'] == '' ? "" : " " . $patient['secondary_city']) ?><br/>
					<?php echo $patient['secondary_zip'] ?>
				</li>
			</ul>
			<section class="clear"></section>
			<ul id="patient-data">
				<li>TIN</li>
				<li><?php echo $patient['tin'] ?></li>
			</ul>
			<section class="clear"></section>
			<ul id="patient-data">
				<li>Senior Citizen ID</li>
				<li><?php echo $patient['sc_id'] ?></li>
			</ul>
			<section class="clear"></section>
			</div>
			
			
			<!-- <div id="contact_information_list_wrapper" style="width: 95%;"></div> -->
			
			
		</section>
		
		<section id="right"  class="patient-details-top-row right">
			<h1>Miscellaneous Details</h1>
			<div class="holder misc-holder">
			<ul id="patient-data">
				<li>Dominant Hand</li>
				<li><?php echo $patient['dominant_hand'] ?></li>
			</ul>
			<section class="clear"></section>
			<ul id="patient-data">
				<li>Civil Status</li>
				<li><?php echo $patient['civil_status'] ?></li>
			</ul>
			<section class="clear"></section>
			<ul id="patient-data">
				<li>Relationship</li>
				<li><?php echo wordwrap($patient['relationship'],30,"<br>\n")?></li>
			</ul>
			<section class="clear"></section>
			<ul id="patient-data">
				<li>Work Status</li>
				<li><?php echo $patient['work_status'] ?></li>
			</ul>
			<section class="clear"></section>

			<ul id="patient-data">
				<li>Credits</li>
				<li><a href="javascript: void(0);" onclick="javascript: openCredit(<?php echo $patient['id'] ?>);" title="Open Credit System" class="credit-system"><?php echo $patient['credit'] ?></a></li>
			</ul>
			<section class="clear"></section>
			<?php if($pm_pi['can_view']){ ?>
			<ul id="patient-data">
				<li>Notes</li>
				<li class="notes-data"><?php echo substr($patient_notes['notes'], 0,85)?>
				<?php if(empty($patient_notes['notes'])){ ?>
				<a href="javascript: void(0);" onclick="javascript: openViewNotes(<?php echo $patient['id'] ?>);" title="View Notes" >Add Notes</a></li>
				<?php }else{ ?>
				<a href="javascript: void(0);" onclick="javascript: openViewNotes(<?php echo $patient['id'] ?>);" title="View Notes" >See More...</a></li>
				<?php } ?>
			</ul>
			<?php } ?>
		</div>
		</section>
		
		<section class="clear"></section>
		
		<section id="left" class="patient-details-top-row bottom-wrapper">

			<h1>Contact Details</h1>
			<div class="holder">
			
			<!-- <ul id="patient-data">
				<li>Contact Information</li>
			</ul> -->
			<ul id="patient-data">
				<li>Email Address</li>
				<li><?php echo $patient['email_address'] ?></li>
			</ul>
			<section class="clear"></section>
			<?php foreach($contact_information as $key=>$value): ?>
			<ul id="patient-data">
				<li><?php echo $value['contact_type']; ?></li>
				<li><?php echo $value['extension']; ?> <?php echo $value['contact_value']; ?></li>
			</ul>
			<?php endforeach; ?>
			
			</div>	
		</section>

		<section id="right"  class="patient-details-top-row bottom-wrapper">
			<h1>Doctors</h1>
			<div class="holder">
			<ul id="patient-data">
				<li>Doctor Assigned</li>
				<?php $doc = Doctors::findbyID(array("id" => $patient['doc_assigned_id'])); ?>
				<li><?php echo ($doc ? $doc['full_name'] : "") ?></li>
			</ul>
			<section class="clear"></section>
			<ul id="patient-data">
				<li>Attending Doctor</li>
				<?php $doc2 = Doctors::findbyID(array("id" => $patient['doc_attending_id'])); ?>
				<li><?php echo ($doc2 ? $doc2['full_name'] : "") ?></li>
			</ul>
			<section class="clear"></section>
			</div>
		</section>

		<section id="left" class="patient-details-top-row bottom-wrapper">
			<h1>Representative</h1>
			<div class="holder">
			<ul id="patient-data">
				<li>Name:</li>
				<li><?php echo $patient['representative_name'] ?></li>
			</ul>
			<section class="clear"></section>
			<ul id="patient-data">
				<li>Relationship:</li>
				<li><?php echo $patient['representative_relation'] ?></li>
			</ul>	
			<section class="clear"></section>
			<ul id="patient-data">
				<li>Mobile:</li>
				<li><?php echo $patient['representative_mobile'] ?></li>
			</ul>
			<section class="clear"></section>
			<ul id="patient-data">
				<li>Email:</li>
				<li><?php echo $patient['representative_email'] ?></li>
			</ul>
			</div>
			<!-- <section class="clear"></section> -->
			<!-- <div id="contact_person_list_wrapper" style="width: 95%;"></div> -->
			
		</section>
		<section id="right" class="patient-details-top-row bottom-wrapper">
			<h1>Emergency Contact</h1>
			<div class="holder">
			<ul id="patient-data">
				<li>Name</li>
				<li><?php echo $patient['contact_name'] ?></li>
			</ul>
			<section class="clear"></section>
			<ul id="patient-data">
				<li>Relationship</li>
				<li><?php echo $patient['contact_relation'] ?></li>
			</ul>
			<section class="clear"></section>
			<ul id="patient-data">
				<li>Address</li>
				<li><?php echo $patient['contact_address'] ?></li>
			</ul>
			<section class="clear"></section>	
			
			<ul id="patient-data">
				<li>Email Address</li>
				<li><?php echo $patient['contact_email_address'] ?></li>
			</ul>
			<?php foreach($contact_person as $key=>$value): ?>
			<ul id="contact-data">
				<li><?php echo $value['contact_type']; ?></li>
				<li><?php echo $value['extension']; ?> <?php echo $value['contact_value']; ?></li>
			</ul>
			<?php endforeach; ?>
			</div>
		</section>
		<section class="clear"></section>

		<hgroup id="section-header">
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
 					$files = Patient_Files::findAllByPatientIdCategory(array("patient_id" => $patient['id'], "category_id" => 1));
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
 		</table>
		<section id="buttons">
			<!--button class="previous_button" onClick="window.location.href='view-patient.html';">Previous Page</button-->
			<button class="next_button" onclick="javascript: view_medical_history();">Next</button>
		</section>			
</section>

<script>
	$(function(){
		$(".credit-system").tipsy({gravity: 's'});
	})

	function openCredit(patient_id){
		$.post(base_url + "patient_management/openCreditSystem", {patient_id:patient_id}, function(o){
			$('#credit_system_form_wrapper').html(o);
			$('#credit_system_form_wrapper').modal();
		});
	}

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

	var height = Math.max($(".misc-holder").height(), $(".patient-holder").height());
       $(".misc-holder").height(height);
       $(".patient-holder").height(height);

</script>