<input type="hidden" id="id" value="<?php echo $id['id']?>">
<section class="area patient-dashboard-section" id="all-test-sec">
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
		<div class="col-md-12 all-test-wrapper with-border">
			<div class="header-radio-holder top-wrapper">
				<h1>Patient Tests</h1>
				<form class="radio-test-type">
					<label for="labtest">
					  <input type="radio" id="labtest" name="filtertest" checked/> Lab Test
					</label>
					<label for="imgtest">
					  <input type="radio" id="imgtest" name="filtertest"/> Imaging Test
					</label>
				</form>
				<!-- <form role="search" class="searchform">
					<div class="searchboxwrapper">
						<input class="searchbox" type="text" value=""  placeholder="Search">
						<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
					</div>
				</form> -->
			</div>
			<div class="clear"></div>
			<hr class="custom-hr">
			<div class="all-test-tbl-hldr">
				<div class="table-wrapper">
				<div class="for-lab-test">
					<?php if(!empty($labtest)){?>
						<?php foreach ($laboratory_names as $key => $value) { ?>
							<?php 
			 					$files = Patient_Labtest::findAllByPatientIdCategory(array("patient_id" => $patient['id'], "category" => $value['category_value']));
			 					if(!empty($files)){
			 				?>
							<h5><?php echo $value['category_name'];?></h5>
							<table class="all-urinalysis-table stripe datatable custom-table">
								<thead>
									<th>Test No. :</th>
									<th>Date:</th>
									<th>Requesting Physician</th>
									<th>Hospital / Clinic</th>
									<th>Action</th>
								</thead>
								<tbody>
									
					 				<?php foreach ($files as $a => $b) { 
					 					$doctor = Doctors::findById(array("id" => $b['requesting_physician']));
					 				?>		
									<tr>
										<td><?php echo $b['test_patient_number'];?></td>
										<td><?php echo $b['date_of_test'];?></td>
										<td><?php echo $doctor['full_name']; ?></td>
										<td><?php echo $b['hospital'];?></td>
										<td>
											<a href="javascript: void(0);" onclick="javascript: view_labtest(<?php echo $b['id']?>, <?php echo $id['id'];?>)"><i class="fa fa-eye" aria-hidden="true"></i></a>
							            	<a href="javascript: void(0);" onclick="javascript: edit_labtest(<?php echo $b['id']?>, <?php echo $id['id'];?>)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
							            	<a href="javascript: void(0);" onclick="javascript: delete_labtest(<?php echo $b['id']?>, <?php echo $id['id'];?>)"><i class="fa fa-trash" aria-hidden="true"></i></a>
							            </td>
									</tr>
									<?php }?>
								</tbody>
							</table>
						<div class="clear"></div>
						<hr class="custom-hr">
						<div class="clear"></div>
						<?php }
						}
					}else{ ?>
						<h5 class="no-test-note">No Patient Test Result</h5>
					<?php } ?>
				</div>	

				<div class="for-img-test">
					<?php if(!empty($imaging_test)){?>
						<?php foreach ($imaging_names as $key => $value) { ?>
							<?php 
			 					$files = Patient_Labtest::findAllByPatientIdCategory(array("patient_id" => $patient['id'], "category" => $value['category_value']));
			 					if(!empty($files)){
			 				?>
							<h5><?php echo $value['category_name'];?></h5>
							<table class="all-urinalysis-table stripe datatable custom-table">
								<thead>
									<th>Test No. :</th>
									<th>Date:</th>
									<th>Requesting Physician</th>
									<th>Hospital / Clinic</th>
									<th>Action</th>
								</thead>
								<tbody>
									
					 				<?php foreach ($files as $a => $b) { 
					 					$doctor = Doctors::findById(array("id" => $b['requesting_physician']));
					 				?>		
									<tr>
										<td><?php echo $b['test_patient_number'];?></td>
										<td><?php echo $b['date_of_test'];?></td>
										<td><?php echo $doctor['full_name']; ?></td>
										<td><?php echo $b['hospital'];?></td>
										<td>
											<a href="javascript: void(0);" onclick="javascript: view_imagingtest(<?php echo $b['id']?>, <?php echo $id['id'];?>)"><i class="fa fa-eye" aria-hidden="true"></i></a>
							            	<!-- <a href="javascript: void(0);" onclick="javascript: edit_labtest(<?php echo $b['id']?>, <?php echo $id['id'];?>)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> -->
							            	<a href="javascript: void(0);" onclick="javascript: delete_labtest(<?php echo $b['id']?>, <?php echo $id['id'];?>)"><i class="fa fa-trash" aria-hidden="true"></i></a>
							            </td>
									</tr>
									<?php }?>
								</tbody>
							</table>
						<div class="clear"></div>
						<hr class="custom-hr">
						<div class="clear"></div>
						<?php }
						}
					}else{ ?>
						<h5 class="no-test-note">No Imaging Test Result</h5>
					<?php } ?>
				</div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>	
<div class="clear"></div>
</section>
<div class="clear"></div>
<script type="text/javascript">
	$('.all-urinalysis-table').dataTable({
		"bFilter": true,
		"bLengthChange": false,
		"bInfo": false,
		"oLanguage": { "sSearch": "" },
			"oLanguage": { "sSearch": '<a class="btn searchBtn" id="searchBtn"><i class="fa fa-search"></i></a>' },
	})
	
	$(".dataTables_filter input").attr("placeholder", "Search");
// 	$(document).ready(function() {
//   function setHeight() {
//     windowHeight = $(window).innerHeight();
//     $('.all-test-tbl-hldr .no-test-note').css('min-height', windowHeight);
//   };
//   setHeight();
  
//   $(window).resize(function() {
//     setHeight();
//   });
// });
$(".radio-test-type input[name='filtertest']").click(function() {
 if ($("#labtest").is(":checked")) {
   $(".for-lab-test").show();
   $(".for-img-test").hide();
 } else {
   $(".for-lab-test").hide();
   $(".for-img-test").show();
 }
});
</script>
