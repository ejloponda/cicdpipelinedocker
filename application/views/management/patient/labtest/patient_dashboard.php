<section class="area patient-dashboard-section">
<input type="hidden" id="id" value="<?php echo $id['id']?>">
<div class="dashboard-patient-wrapper">
	<div class="col-md-8 patient-info-wrapper no-padding">
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
			<li class="col-third date-birth-col">
				<label>Date of Birth: </label><span><?php echo $patient['birthdate'];?></span>
				<br>
				<label>Age:</label><span><?php echo $patient['age'];?></span>
			</li>
		</ul>
		<div class="col-md-12 notables-wrapper">
			<h3>Notables:</h3>
			<li >
				<button type="button" data-target="" class="in-active"><img src="<?php echo BASE_FOLDER; ?>themes/images/notables-icon.png">
				<span>menopausal</span></button>
			</li>
			<li>
				<button type="button" data-target="for-cancer"><img src="<?php echo BASE_FOLDER; ?>themes/images/notables-icon.png">
				<span>cancer history</span></button>
			</li>
			<li>
				<button type="button" data-target="for-diabetes"><img src="<?php echo BASE_FOLDER; ?>themes/images/notables-icon-diabetes.png">
				<span>diabetes</span></button>
			</li>
			<li>
				<button type="button" data-target="" class="in-active"><img src="<?php echo BASE_FOLDER; ?>themes/images/notables-icon.png">
				<span>allergies</span></button>
			</li>
			<li>
				<button type="button" data-target="for-hypertension"><img src="<?php echo BASE_FOLDER; ?>themes/images/notables-icon-heart.png">
				<span>hypertension</span></button>
			</li>
			<li>
				<button type="button" data-target="" class="in-active"><img src="<?php echo BASE_FOLDER; ?>themes/images/notables-icon.png">
				<span>surgical history</span></button>
			</li>
		</div>
	</div>
	<div class="col-md-4 instructions-wrapper no-padding">
		<h4>INSTRUCTIONS FROM PREVIOUS APPOINTMENT</h4>
		<div class="content-instruc">
			<h6>Notes</h6>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla nec hendrerit
			est. Aenean vehicula dui leo, sit amet commodo tellus egestas feugiat. Duis
			vel diam eget ipsum rhoncus accumsan. Pellentesque vehicula elit nec erat
			sollicitudin convallis. Mauris et nulla lobortis, suscipit felis nec, vehicula
			mauris. Nulla faucibus mollis diam, vel interdum sapien eleifend non.</p>
			<h6>Medications to Take</h6>
			<p>Thyroid Support - 1 capsule UPON WAKING UP</p>
		</div>
	</div>
	<div class="col-md-12 no-padding" style="float:none">
		<div id="for-cancer" class="col-md-12 notables-history-wrapper history-table-hldr with-border">
			<div class="header-top-wrapper">
				<h3>cancer history details</h3>
				<a href="" class="def-btn">Edit Details</a>
			</div>
			<div class="table-wrapper">
				<table class="history-table stripe datatable custom-table">
					<thead>
						<th>Cancer Type</th>
						<th>Stage</th>
						<th>Doctor</th>
						<th>Hospital</th>
						<th>Treatment Type</th>
						<th>Status</th>
					</thead>
					<tbody>
						<tr>
							<td>Lung Cancer</td>
							<td>1</td>
							<td>Doctor John Doe</td>
							<td>Makati Medical Center</td>
							<td>Chemotherapy</td>
							<td>Treatment On-going</td>
						</tr>
						<tr>
							<td>Lymphoma</td>
							<td>2</td>
							<td>Doctor John Doeterte</td>
							<td>St. Luke Medical Center</td>
							<td>Surgery</td>
							<td>Clear</td>
						</tr>
						<tr>
							<td>Lung Cancer</td>
							<td>1</td>
							<td>Doctor John Doe</td>
							<td>Makati Medical Center</td>
							<td>Chemotherapy</td>
							<td>Treatment On-going</td>
						</tr>
						<tr>
							<td>Lymphoma</td>
							<td>2</td>
							<td>Doctor John Doeterte</td>
							<td>St. Luke Medical Center</td>
							<td>Surgery</td>
							<td>Clear</td>
						</tr>
						<tr>
							<td>Lymphoma</td>
							<td>2</td>
							<td>Doctor John Doeterte</td>
							<td>St. Luke Medical Center</td>
							<td>Surgery</td>
							<td>Clear</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div id="for-diabetes" class="col-md-12 notables-history-wrapper history-table-hldr with-border">
			<div class="header-top-wrapper">
				<h3>diabetes details</h3>
				<a href="" class="def-btn">Edit Details</a>
			</div>
			<div class="table-wrapper">
				<table class="history-table stripe datatable custom-table">
					<thead>
						<th>Lorem Ipsum</th>
						<th>Stage</th>
						<th>Doctor</th>
						<th>Hospital</th>
						<th>Treatment Type</th>
						<th>Status</th>
					</thead>
					<tbody>
						<tr>
							<td>Lorem ipsum</td>
							<td>1</td>
							<td>Doctor John Doe</td>
							<td>Makati Medical Center</td>
							<td>Chemotherapy</td>
							<td>Treatment On-going</td>
						</tr>
						<tr>
							<td>Lorem ipsum</td>
							<td>2</td>
							<td>Doctor John Doeterte</td>
							<td>St. Luke Medical Center</td>
							<td>Surgery</td>
							<td>Clear</td>
						</tr>
						<tr>
							<td>Lorem ipsum </td>
							<td>1</td>
							<td>Doctor John Doe</td>
							<td>Makati Medical Center</td>
							<td>Chemotherapy</td>
							<td>Treatment On-going</td>
						</tr>
						<tr>
							<td>Lorem ipsum</td>
							<td>2</td>
							<td>Doctor John Doeterte</td>
							<td>St. Luke Medical Center</td>
							<td>Surgery</td>
							<td>Clear</td>
						</tr>
						<tr>
							<td>Lorem ipsum</td>
							<td>2</td>
							<td>Doctor John Doeterte</td>
							<td>St. Luke Medical Center</td>
							<td>Surgery</td>
							<td>Clear</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div id="for-hypertension" class="col-md-12 notables-history-wrapper history-table-hldr with-border">
			<div class="header-top-wrapper">
				<h3>hypertension details</h3>
				<a href="" class="def-btn">Edit Details</a>
			</div>
			<div class="table-wrapper">
				<table class="history-table stripe datatable custom-table">
					<thead>
						<th>Lorem ipsum Type</th>
						<th>Stage</th>
						<th>Doctor</th>
						<th>Hospital</th>
						<th>Treatment Type</th>
						<th>Status</th>
					</thead>
					<tbody>
						<tr>
							<td>Lorem ipsum sit amit</td>
							<td>1</td>
							<td>Doctor John Doe</td>
							<td>Makati Medical Center</td>
							<td>Chemotherapy</td>
							<td>Treatment On-going</td>
						</tr>
						<tr>
							<td>Lorem ipsum sit amit</td>
							<td>2</td>
							<td>Doctor John Doeterte</td>
							<td>St. Luke Medical Center</td>
							<td>Surgery</td>
							<td>Clear</td>
						</tr>
						<tr>
							<td>Lorem ipsum sit amit</td>
							<td>1</td>
							<td>Doctor John Doe</td>
							<td>Makati Medical Center</td>
							<td>Chemotherapy</td>
							<td>Treatment On-going</td>
						</tr>
						<tr>
							<td>Lorem ipsum sit amit</td>
							<td>2</td>
							<td>Doctor John Doeterte</td>
							<td>St. Luke Medical Center</td>
							<td>Surgery</td>
							<td>Clear</td>
						</tr>
						<tr>
							<td>Lorem ipsum sit amit</td>
							<td>2</td>
							<td>Doctor John Doeterte</td>
							<td>St. Luke Medical Center</td>
							<td>Surgery</td>
							<td>Clear</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
<!-- START MOST RECENT -->
	<div class="col-md-12 recent-test-wrapper with-border">
		<div class="header-top-wrapper">
			<div class="header-radio-holder">
				<h1>Most Recent Tests</h1>
				<form class="radio-test-type">
					<label for="labtest">
					  <input type="radio" id="labtest" name="filtertest" checked/> Lab Test
					</label>
					<label for="imgtest">
					  <input type="radio" id="imgtest" name="filtertest"/> Imaging Test
					</label>
				</form>
			</div>
			<a href="javascript: void(0);" onclick="javascript: compare_labtest(<?php echo $id['id'] ?>)" class="def-btn view-records compare-test-btn">Compare Tests</a>
			<a href="javascript: void(0);" onclick="javascript: add_labtest(<?php echo $id['id'] ?>)" class="def-btn">Add Test</a>
			<div class="select-test-wrapper">
			<label>Filter Display</label>
			<select name="filter_test" id="filter_test" onchange="javascript: most_recent_test();">
				<option>All Tests</option>
				<?php foreach($lab_test as $key=>$value): ?>
					<option value="<?php echo $value['category_value'] ?>"><?php echo $value['category_name'] ?></option>
				<?php endforeach; ?>
			</select>
			</div>
		</div>
		<div class="overflow-outer">
			<div class="overflow-inner">
				<div class="for-lab-test">
					
						<!-- <li>
						<div class="tbl-recent-test-wrapper">
							<div class="sub-tbl-header">
								<h6>VITAMIN & NUTRIENTS</h6>
								<a href="">view test details</a>
							</div>
							<p class="test-date">October 6, 2017</p>
							<table style="width:100%">
								<thead>
									<th>DESCRIPTION</th>
									<th>RESULT</th>
								</thead>
								<tbody>
									<tr>
										<td>Protein</td>
										<td>NEGATIVE</td>
									</tr>
									<tr>
										<td>Sugar</td>
										<td>NEGATIVE</td>
									</tr>
									<tr>
										<td>RBC</td>
										<td>0-2/hpf</td>
									</tr>
									<tr>
										<td>Pus Cell</td>
										<td>0-2/hpf</td>
									</tr>
								</tbody>
							</table>	
							<button class="see-more-btn">See more</button>
						</div>
						</li>
						<li>
						<div class="tbl-recent-test-wrapper">
							<div class="sub-tbl-header">
								<h6>HEMATOLOGY</h6>
								<a href="">view test details</a>
							</div>
							<p class="test-date">October 6, 2017</p>
							<table style="width:100%">
								<thead>
									<th>DESCRIPTION</th>
									<th>RESULT</th>
								</thead>
								<tbody>
									<tr>
										<td>Protein</td>
										<td>NEGATIVE</td>
									</tr>
									<tr>
										<td>Sugar</td>
										<td>NEGATIVE</td>
									</tr>
									<tr>
										<td>RBC</td>
										<td>0-2/hpf</td>
									</tr>
									<tr>
										<td>Pus Cell</td>
										<td>0-2/hpf</td>
									</tr>
								</tbody>
							</table>	
							<button class="see-more-btn">See more</button>
						</div>
						</li>
						<li>
						<div class="tbl-recent-test-wrapper">
							<div class="sub-tbl-header">
								<h6>SEROLOGY/LOREM IPSUM</h6>
								<a href="">view test details</a>
							</div>
							<p class="test-date">October 6, 2017</p>
							<table style="width:100%">
								<thead>
									<th>DESCRIPTION</th>
									<th>RESULT</th>
								</thead>
								<tbody>
									<tr>
										<td>Protein</td>
										<td>NEGATIVE</td>
									</tr>
									<tr>
										<td>Sugar</td>
										<td>NEGATIVE</td>
									</tr>
									<tr>
										<td>RBC</td>
										<td>0-2/hpf</td>
									</tr>
									<tr>
										<td>Pus Cell</td>
										<td>0-2/hpf</td>
									</tr>
								</tbody>
							</table>
							<button class="see-more-btn">See more</button>	
						</div>
						</li>
						<li>
						<div class="tbl-recent-test-wrapper">
							<div class="sub-tbl-header">
								<h6>TUMOR MARKERS</h6>
								<a href="">view test details</a>
							</div>
							<p class="test-date">October 6, 2017</p>
							<table style="width:100%">
								<thead>
									<th>DESCRIPTION</th>
									<th>RESULT</th>
								</thead>
								<tbody>
									<tr>
										<td>Protein</td>
										<td>NEGATIVE</td>
									</tr>
									<tr>
										<td>Sugar</td>
										<td>NEGATIVE</td>
									</tr>
									<tr>
										<td>RBC</td>
										<td>0-2/hpf</td>
									</tr>
									<tr>
										<td>Pus Cell</td>
										<td>0-2/hpf</td>
									</tr>
								</tbody>
							</table>
							<button class="see-more-btn">See more</button>	
						</div>
						</li>
						<li>
						<div class="tbl-recent-test-wrapper">
							<div class="sub-tbl-header">
								<h6>BIOCHEMISTRY</h6>
								<a href="">view test details</a>
							</div>
							<p class="test-date">October 6, 2017</p>
							<table style="width:100%">
								<thead>
									<th>DESCRIPTION</th>
									<th>RESULT</th>
								</thead>
								<tbody>
									<tr>
										<td>Protein</td>
										<td>NEGATIVE</td>
									</tr>
									<tr>
										<td>Sugar</td>
										<td>NEGATIVE</td>
									</tr>
									<tr>
										<td>RBC</td>
										<td>0-2/hpf</td>
									</tr>
									<tr>
										<td>Pus Cell</td>
										<td>0-2/hpf</td>
									</tr>
								</tbody>
							</table>	
							<button class="see-more-btn">See more</button>
						</div>
						</li>
						<li>
						<div class="tbl-recent-test-wrapper">
							<div class="sub-tbl-header">
								<h6>THYROID TEST</h6>
								<a href="">view test details</a>
							</div>
							<p class="test-date">October 6, 2017</p>
							<table style="width:100%">
								<thead>
									<th>DESCRIPTION</th>
									<th>RESULT</th>
								</thead>
								<tbody>
									<tr>
										<td>Protein</td>
										<td>NEGATIVE</td>
									</tr>
									<tr>
										<td>Sugar</td>
										<td>NEGATIVE</td>
									</tr>
									<tr>
										<td>RBC</td>
										<td>0-2/hpf</td>
									</tr>
									<tr>
										<td>Pus Cell</td>
										<td>0-2/hpf</td>
									</tr>
								</tbody>
							</table>	
							<button class="see-more-btn">See more</button>
						</div>
						</li>
						<li>
						<div class="tbl-recent-test-wrapper">
							<div class="sub-tbl-header">
								<h6>URINALYSIS</h6>
								<a href="">view test details</a>
							</div>
							<p class="test-date">October 6, 2017</p>
							<table style="width:100%">
								<thead>
									<th>DESCRIPTION</th>
									<th>RESULT</th>
								</thead>
								<tbody>
									<tr>
										<td>Protein</td>
										<td>NEGATIVE</td>
									</tr>
									<tr>
										<td>Sugar</td>
										<td>NEGATIVE</td>
									</tr>
									<tr>
										<td>RBC</td>
										<td>0-2/hpf</td>
									</tr>
									<tr>
										<td>Pus Cell</td>
										<td>0-2/hpf</td>
									</tr>
								</tbody>
							</table>	
							<button class="see-more-btn">See more</button>
						</div>
						</li>
						<li>
						<div class="tbl-recent-test-wrapper">
							<div class="sub-tbl-header">
								<h6>URINALYSIS</h6>
								<a href="">view test details</a>
							</div>
							<p class="test-date">October 6, 2017</p>
							<table style="width:100%">
								<thead>
									<th>DESCRIPTION</th>
									<th>RESULT</th>
								</thead>
								<tbody>
									<tr>
										<td>Protein</td>
										<td>NEGATIVE</td>
									</tr>
									<tr>
										<td>Sugar</td>
										<td>NEGATIVE</td>
									</tr>
									<tr>
										<td>RBC</td>
										<td>0-2/hpf</td>
									</tr>
									<tr>
										<td>Pus Cell</td>
										<td>0-2/hpf</td>
									</tr>
								</tbody>
							</table>
							<button class="see-more-btn">See more</button>	
						</div>
						</li>
						<li>
						<div class="tbl-recent-test-wrapper">
							<div class="sub-tbl-header">
								<h6>URINALYSIS</h6>
								<a href="">view test details</a>
							</div>
							<p class="test-date">October 6, 2017</p>
							<table style="width:100%">
								<thead>
									<th>DESCRIPTION</th>
									<th>RESULT</th>
								</thead>
								<tbody>
									<tr>
										<td>Protein</td>
										<td>NEGATIVE</td>
									</tr>
									<tr>
										<td>Sugar</td>
										<td>NEGATIVE</td>
									</tr>
									<tr>
										<td>RBC</td>
										<td>0-2/hpf</td>
									</tr>
									<tr>
										<td>Pus Cell</td>
										<td>0-2/hpf</td>
									</tr>
								</tbody>
							</table>	
							<button class="see-more-btn">See more</button>
						</div>
						</li> -->
					<!-- </ul> -->
				</div>
				<div class="for-img-test">

					
				</div>
			</div>	
		</div>
	</div>
<!-- END MOST RECENT -->
	<div class="col-md-12 current-regimen-wrapper with-border">
		<div class="header-top-wrapper">
			<h1>Current Regimen (ROYAL PREVENTIVE)</h1>
			<a href="javascript: void(0);" onClick="javascript: view_regimen(<?php echo $id['id'] ?>)" class="def-btn view-records view-regimen-btn">View All Regimen</a>
			<a href="" class="def-btn">Create New Regimen</a>
		</div>
		<div class="table-wrapper">
			<table class="regimen-tbl">
				    <thead>
				        <th class="th-head" style="width:15%">regimen number</th>
				        <th class="th-head" style="width:5%">lmp</th>
				        <th class="th-head" style="width:5%">program</th>
				        <th class="th-head" style="width:10%">duration</th>
				        <th class="th-head" style="width:20%">Breakfast</th>
				        <th class="th-head" style="width:20%">lunch</th>
				        <th class="th-head" style="width:20%">dinner</th>
				    </thead>
				    <tbody>
				    	<tr>
				    		<td>REG-00892</td>
				    		<td>N/A</td>
				    		<td>N/A</td>
				    		<td>FEB 23, 2017 to MAR 23, 2017</td>
				    		<td>Thyroid Support - 1 Capsule UPON WAKING UP</td>
				    		<td>Digestive Enzymes - 1 Capsule</td>
				    		<td>Endotrim - 2 Capsules</td>
				    	</tr>
				    </tbody>
			</table>
		</div>
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
			<a href="" class="def-btn view-records-btn">Save</a>
		</div>
		
	</div>
	<div class="col-md-12 instruction-wrapper with-border">
		<div class="header-top-wrapper">
			<h1>Instructions For Next Appointment</h1>
		</div>
		<div class="col-md-12 instructions-content-wrapper no-padding">
			<div class="col-md-6 col">
				<div class="top-div">
					<h4>FOR PATIENT</h4>
					<ul>
						<li><label>Issued by:</label><span>Dr. Rex Gloria</span></li>
						<li><label>Date Issued:</label><span>November 24, 2017</span></li>
					</ul>
				</div>
				<div class="input-wrapp">
					<h5>Notes</h5>
					<textarea>Take Ultrasound test at Gensens and come back with results for another check-up</textarea>
				</div>
				<div class="input-wrapp">
					<h5>Medications To Take</h5>
					<textarea>Thyroid Support - 1 Capsule UPON WAKING UP</textarea>
				</div>
				<div class="input-wrapp">
					<h5>Book next appointment?</h5>
					<input type="radio" name=""><label>Yes</label>
					<input type="radio" name=""><label>No</label>
				</div>
				
				<div class="clear"></div>
			</div>
			<div class="col-md-6 col">
				<div class="top-div">
					<h4>FOR NURSES</h4>
					<ul>
						<li><label>Issued by:</label><span>Dr. Rex Gloria</span></li>
						<li><label>Date Issued:</label><span>November 24, 2017</span></li>
					</ul>
				</div>
				<div class="input-wrapp">
					<h5>Notes</h5>
					<textarea class="nurse-notes">Take Ultrasound test at Gensens and come back with results for another check-up</textarea>
				</div>
				<div class="input-wrapp">
					<h5>Ask nurse to schedule this patient for appointment?</h5>
					<input type="radio" name=""><label>Yes</label>
					<input type="radio" name=""><label>No</label>
				</div>
				<div class="clear"></div>
			</div>
		</div>
		<div class="clear"></div>
		<div class="btn-holder">
			<a href="" class="def-btn submit-btn">Submit</a>
		</div>
	</div>
</div>
</section>
<script type="text/javascript">
	most_recent_test();
	function most_recent_test(){
		var patient_id = parseInt($("#id").val());
		var filtered_by = $("#filter_test").val();
		if ($("#labtest").is(":checked")) {
			var kind_of_test = "1";
		}else{
			var kind_of_test = "0";
		}

		$('.for-lab-test').html(default_ajax_loader);
		$.post(base_url + "patient_management/filterLabtest", {filtered_by:filtered_by, patient_id:patient_id, kind_of_test:kind_of_test}, function(o){
			if(kind_of_test == '1'){
				$(".for-lab-test").html(o);
			}else{
				$(".for-img-test").html(o);
			}
			
		});
	}
	$('.history-table').dataTable({
		"bFilter": false,
		"bLengthChange": false,
		"bPaginate": false,
		"bInfo": false,
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
	$('.regimen-tbl').dataTable({
			"bJQueryUI": true,
		"bFilter": true,
		"bLengthChange": false,
		"bPaginate": false,
		"bInfo": false,
		"oLanguage": { "sSearch": "" },
			"oLanguage": { "sSearch": '<a class="btn searchBtn" id="searchBtn"><i class="fa fa-search"></i></a>' },
	})
	$(".dataTables_filter input").attr("placeholder", "Search for supplement or ingredient");
	$(function(){
	$('.history-table-hldr').hide();
	$('.notables-wrapper li button').click(function(){
	    var target = "#" + $(this).data("target");
	    $(".history-table-hldr").not(target).hide();
	    $(target).show();
		});
	});
	$('.notables-wrapper li button').click(
	function(e) {
	    e.preventDefault(); // prevent the default action
	    e.stopPropagation; // stop the click from bubbling
	    $(this).closest('.notables-wrapper').find('.notable-selected').removeClass('notable-selected');
	    $(this).parent().addClass('notable-selected');
	});
	$(".tbl-recent-test-wrapper tbody tr:even").css("background-color", "#f2f3f4");
	$(".tbl-recent-test-wrapper tbody tr:odd").css("background-color", "#fff");

	$(function() {
	   $(".radio-test-type input[name='filtertest']").click(function() {
	     if ($("#labtest").is(":checked")) {
	     	$('#filter_test').empty().append('<option selected="selected">All Tests</option>',
				'<option value="urinalysis">Urinalysis</option>',
				'<option value="urine_chemistry">Urine Chemistry</option>',
			    '<option value="coagulation_factor">Coagulation Factor</option>',
			    '<option value="coagulation">Coagulation</option>',				    
			    '<option value="hematology">Hematology</option>',
			    '<option value="biochemistry">Biochemistry</option>',
			    '<option value="oral_glucose_challenge">Oral Glucose Challenge Test</option>',
			    '<option value="oral_glucose_tolerance">Oral Glucose Tolerance Test</option>',
			    '<option value="thyroid">Thyroid Test</option>',
			    '<option value="hormones">Hormones Test</option>',
			    '<option value="homeostasis_ass_index">Homeostasis Assessment Index</option>',
			    '<option value="serology">Serology/Immunology</option>',
			    '<option value="tumor_markers">Tumor Markers</option>',
			    '<option value="special_chem">Special Chemistry</option>',
			    '<option value="vita_nutri">Vitamins and Nutrition</option>',
			    '<option value="viral_hepa">Viral Hepatitis Test</option>',
			    '<option value="hiv">HIV Test</option>',
			    '<option value="eGFR">Estimated Glomerular Filtration Rate (eGFR)</option>',
			    '<option value="nutri_elements">Nutrients Elements</option>');
	       $(".for-lab-test").show();
	       $(".for-img-test").hide();
	       most_recent_test();
	     } else {
	     	$('#filter_test').empty().append('<option selected="selected">All Tests</option>',
					'<option value="xray">Xray</option>',
	       			'<option value="ultrasound">Ultrasound</option>',);
	       $(".for-lab-test").hide();
	       $(".for-img-test").show();
	       most_recent_test();
	     }
	   });
	 });
  function addData() {
      $('#add_med').on('click', function (e) {
      	$('.save-cancel-btn-wrapper').show();
    	 e.preventDefault();
        $('.own-medication-tbl').append('<tr><td><input type="text"/></td><td><input type="text"/></td><td><input type="text"/></td><td><input type="text"/></td><td><input type="text"/></td><td><input type="text"/></td></tr>');
    });
    function del() {
        $('.own-medication-tbl tr').hide($('tr').index()-1);
    };
    // $('.cancel-btn').on('click', function () {
    //     del();
    // });
	}
	addData();
	$('.see-more-btn').click(function(){
        $(this).parent().toggleClass('expanded');
    });
    $( ".drag-drop" ).disableSelection();
    $( ".drag-drop" ).sortable({
      placeholder: "ui-state-highlight"
    });
</script>