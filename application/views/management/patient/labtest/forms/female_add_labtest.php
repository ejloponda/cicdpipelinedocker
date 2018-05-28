<script type="text/javascript">
	$(function() {
        $('#category-select').change(function(){
            $('.category-input-wrapper').hide();
            $('#' + $(this).val()).show();
        });

        $('#date_of_test').datepicker({
			format: 'yyyy-mm-dd'
	    });

	    var id = <?php echo $id['id']; ?>

		$("#add_new_labtest_form").ajaxForm({
	        success: function(o) {
	      		if(o.is_successful) {
	      			IS_ADD_USER_PATIENT_FORM_CHANGE = false;
	      			default_success_confirmation({message : o.message, alert_type: "alert-success"});
	      			$.unblockUI();
	      			view_all_test(id);
	      		} else {
	      			default_success_confirmation({message : o.message, alert_type: "alert-error"});
	      		}
	      		
				$('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);
	        },
	         beforeSubmit : function(evt){
	         	//$.blockUI();
	         },
	        
	        dataType : "json"
	    });

	    $(".add_submit_btn").on('click', function(event) {
			$('#add_new_labtest_form').submit();
		});
	    $(".cancel-btn").on('click', function(){
			window.location.hash = "patient";
			reload_content("patient");
		});
    });
    $(function() {
	   $(".radio-test-type input[name='type_of_test']").click(function() {
	     if ($("#labtest").is(":checked")) {
	       $(".for-lab-test").show();
	       $(".for-img-test").hide();
	       $('#category-select').empty().append('<option selected value="urinalysis">Urinalysis</option>',
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
						    
	     } else {
	       $(".for-lab-test").hide();
	       $(".for-img-test").show();
	       $('#category-select').empty().append('<option selected="selected" value="xray">Xray</option>',
	       	'<option value="ultrasound">Ultrasound</option>',);
	     }
	   });
	 });

</script>

<section class="area patient-dashboard-section">
	<form action="<?php echo url('patient_management/add_new_labtest') ?>" method="post" id="add_new_labtest_form"  style="width:100%;">	
		<input type="hidden" id="id" name="patient_id" value="<?php echo $id['id']?>">
		<div class="col-md-12 dashboard-patient-wrapper no-padding">
			<!-- PATIENT INFO -->
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
			<!-- INPUT TEST INFO -->
			<div class="col-md-12 add-test-input-wrapper with-border">
				<div class="col-md-12 top-form-holder no-padding">
					<h1>Add Test Female</h1>
					<div class="col-md-3 select-input-wrapper type-test">
						<label>type of test</label>
							<div class="radio-test-type">
								<label for="labtest">
								  <input type="radio" id="labtest" name="type_of_test" value="1" checked/> Lab Test
								</label>
								<label for="imgtest">
								  <input type="radio" id="imgtest" name="type_of_test" value="0" /> Imaging Test
								</label>
							</div>
					</div>
					<div class="col-md-3 select-input-wrapper">
						<label>Specification</label>
						<p>For <?php echo $patient['gender'];?></p>
					</div>
					<div class="clear"></div>
					<hr class="custom-hr">
					<div class="clear"></div>
					<div class="col-md-3 select-input-wrapper">
						<label>category</label>
						<select id="category-select" name="category">
						    <?php foreach($laboratory_test as $key=>$value): ?>
								<option value="<?php echo $value['category_value'] ?>"><?php echo $value['category_name'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-md-3 select-input-wrapper">
						<label>Hospital / Clinic</label>
						<select name="hospital">
						    <option  disabled selected>Choose one</option>
						    <option value="Makati Med">Makati Med</option>
						    <option value="St. Lukes Medical">St. Lukes Medical</option>
						    <option value="PGH">PGH</option>
						    <option value="GENSENS, Inc">GENSENS, Inc</option>
						</select>
					</div>
					<div class="col-md-3 select-input-wrapper">
						<label>Date of test</label>
						<input type="text" name="date_of_test" id="date_of_test" placeholder="Date of Test"> 
					</div>
					<div class="clear"></div>
					<div class="col-md-3 select-input-wrapper">
						<label>REQUESTING PHYSICIAN</label>
						<select name="requesting_physician">
							<option  disabled selected>Choose one</option>
							<?php foreach($doctors as $key=>$value): ?>
								<option value="<?php echo $value['id'] ?>"><?php echo $value['full_name'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-md-3 select-input-wrapper">
						<label>PATIENT ID NO. IN TEST</label>
						<input type="text" name="test_patient_number" placeholder="Patient ID Number">
					</div>
					<div class="col-md-3 select-input-wrapper">
						<label>CASE NO.</label>
						<input type="text" name="case_number" placeholder="Case Number">
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<!-- INPUT TEST DESCRIPTION AREA -->
			<div class="col-md-12 add-test-input-wrapper with-border">
				<div class="input-wrapper-add">
					<div class="col-md-12 descript-input-hldr no-padding for-lab-test">
						<h5>TEST DESCRIPTION</h5>
						<!-- URINALYSIS INPUT AREA -->
						<div id="urinalysis" class="col-md-12 urinalysis-input-hldr category-input-wrapper no-padding">
							<h6>GROSS</h6>
							<div class="col-md-2 select-input-wrapper">
								<label>Color</label>
								<input type="text" name="color">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>TRANSPARENCY</label>
								<input type="text" name="transparency">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>SPECIFIC GRAVITY</label>
								<input type="text" name="specific_gravity">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label style="text-transform: initial;">pH</label>
								<input type="text" name="pH">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>PROTEIN</label>
								<input type="text" name="protein">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>SUGAR</label>
								<input type="text" name="sugar">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>BILIRUBIN</label>
								<input type="text" name="bilirubin">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>UROBILINOGEN</label>
								<input type="text" name="urobilinogen">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>KETONE</label>
								<input type="text" name="ketone">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>NITRITE</label>
								<input type="text" name="nitrite">
							</div>
							<div class="clear"></div>
							<hr class="custom-hr">
							<h6>MICROSCOPIC</h6>
							<div class="col-md-2 select-input-wrapper">
								<label>RBC</label>
								<input type="text" name="microscopic_rbc">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>PUS CELL</label>
								<input type="text" name="pus_cell">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>EPITHELIAL CELLS</label>
								<input type="text" name="epithelial_cell">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>MUCUS THREADS</label>
								<input type="text" name="mucus_threads">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>BACTERIA</label>
								<input type="text" name="bacteria">
							</div>

							<div class="col-md-2 select-input-wrapper">
								<label>AMORPHOUS URATES</label>
								<input type="text" name="amorphous_urates">
							</div>
						</div>
						<!-- URINE CHEMISTRY INPUT AREA -->
						<div id="urine_chemistry" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper" style="display:none">
							<div class="col-md-2 select-input-wrapper">
								<label>MICROALBUMIN</label>
								<input type="text" name="microalbumin">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="microalbumin_unit">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="microalbumin_rf">
							</div>
						</div>
						<!-- COAGULATION FACTOR INPUT AREA -->
						<div id="coagulation_factor" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper" style="display:none">
							<div class="col-md-2 select-input-wrapper">
								<label>FIBRINOGEN</label>
								<input type="text" name="fibrinogen">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="fibrinogen_unit">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="fibrinogen_rf">
							</div>
							<div class="clear"></div>
							<div class="col-md-2 select-input-wrapper">
								<label>BLEEDING TIME</label>
								<input type="text" name="bleeding_time">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="bleeding_time_rf">
							</div>
							<div class="clear"></div>
							<div class="col-md-2 select-input-wrapper">
								<label>CLOTTING TIME</label>
								<input type="text" name="clotting_time">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="clotting_time_rf">
							</div>
						</div>
						<!-- COAGULATION INPUT AREA -->
						<div id="coagulation" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper two-col-wrapper" style="display:none">
							<h5 class="no-test-note">No Test for Female</h5>
							<!-- <div class="col-md-6 border-right">
								<div class="col-md-4 select-input-wrapper">
									<label>PROTHROMBIN TIME (PT)</label>
									<input type="text" name="">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>CONTROL</label>
									<input type="text" name="">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>INR<span style="text-transform:Initial">(Please refer to comments below)</span></label>
									<input type="text" name="">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>RESULT</label>
									<input type="text" name="">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>ACTIVATED PARTIAL</label>
									<input type="text" name="">
								</div>
								<div class="col-md-12 comments-wrapper">
									<h5>Comments:</h5>
									<ul>
										<li>0.80 - 1.20 : Normal</li>
										<li>2.00 - 3.00 : Routine Therapy</li>
										<li>2.50 - 3.50 : Recurrent MI or mechanical valve prostetic</li>
									</ul>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>PERCENTAGE ACTIVITY </label>
									<input type="text" name="">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="">
								</div>
							</div>
							<div class="col-md-6">
								<div class="col-md-4 select-input-wrapper">
									<label>ACTIVATED PARTIAL</label>
									<input type="text" name="">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>THROMBOPLASTIN TIME (APTT)</label>
									<input type="text" name="">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>CONTROL</label>
									<input type="text" name="">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="">
								</div>
							</div> -->
						</div>
						<!-- HEMATOLOGY INPUT AREA -->
						<div id="hematology" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper" style="display:none">
							<div class="col-md-12 no-padding">
								<div class="col-md-4 border-right">
									<div class="col-md-4 select-input-wrapper">
										<label>RED BLOOD CELL</label>
										<input type="text" name="rbc">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>UNIT</label>
										<input type="text" name="rbc_unit">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>REFERENCE VALUE</label>
										<input type="text" name="rbc_rf">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>HEMOGLOBIN</label>
										<input type="text" name="hemoglobin">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>UNIT</label>
										<input type="text" name="hemoglobin_unit">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>REFERENCE VALUE</label>
										<input type="text" name="hemoglobin_rf">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>HEMATOCRIT</label>
										<input type="text" name="hematocrit">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>UNIT</label>
										<input type="text" name="hematocrit_unit">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>REFERENCE VALUE</label>
										<input type="text" name="hematocrit_rf">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>MCV</label>
										<input type="text" name="mcv">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>UNIT</label>
										<input type="text" name="mcv_unit">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>REFERENCE VALUE</label>
										<input type="text" name="mcv_rf">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>MCH</label>
										<input type="text" name="mch">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>UNIT</label>
										<input type="text" name="mch_unit">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>REFERENCE VALUE</label>
										<input type="text" name="mch_rf">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>MCHC</label>
										<input type="text" name="mchc">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>UNIT</label>
										<input type="text" name="mchc_unit">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>REFERENCE VALUE</label>
										<input type="text" name="mchc_rf">
									</div>
								</div>
								<div class="col-md-4 border-right">
									<div class="col-md-4 select-input-wrapper">
										<label>WHITE BLOOD CELL</label>
										<input type="text" name="wbc">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>UNIT</label>
										<input type="text" name="wbc_unit">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>REFERENCE VALUE</label>
										<input type="text" name="wbc_rf">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>GRANULOCYTES </label>
										<input type="text" name="granulocytes">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>UNIT</label>
										<input type="text" name="granulocytes_unit">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>REFERENCE VALUE</label>
										<input type="text" name="granulocytes_rf">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>LYMPHOCYTES</label>
										<input type="text" name="lymphocytes">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>UNIT</label>
										<input type="text" name="lymphocytes_unit">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>REFERENCE VALUE</label>
										<input type="text" name="lymphocytes_rf">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>MONOCYTES</label>
										<input type="text" name="monocytes">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>UNIT</label>
										<input type="text" name="monocytes_unit">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>REFERENCE VALUE</label>
										<input type="text" name="monocytes_rf">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>EOSINOPHIL</label>
										<input type="text" name="eosinophil">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>UNIT</label>
										<input type="text" name="eosinophil_unit">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>REFERENCE VALUE</label>
										<input type="text" name="eosinophil_rf">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>BASOPHILS</label>
										<input type="text" name="basophils">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>UNIT</label>
										<input type="text" name="basophils_unit">
									</div>
									<div class="col-md-4 select-input-wrapper">
										<label>REFERENCE VALUE</label>
										<input type="text" name="basophils_rf">
									</div>
								</div>
								<div class="col-md-4">
									<div class="col-md-6 select-input-wrapper">
										<label>PLATELET COUNT</label>
										<input type="text" name="platelet_count">
									</div>
									<div class="col-md-6 select-input-wrapper">
										<label>UNIT</label>
										<input type="text" name="platelet_count_unit">
									</div>
									<div class="col-md-6 select-input-wrapper">
										<label>ESR ( < 50 Y/O )</label>
										<input type="text" name="esr_lessthan_50">
									</div>
									<div class="col-md-6 select-input-wrapper">
										<label>UNIT</label>
										<input type="text" name="esr_lessthan_50_unit">
									</div>
									<div class="col-md-6 select-input-wrapper">
										<label>ESR ( > 50 Y/O )</label>
										<input type="text" name="esr_greaterthan_50">
									</div>
									<div class="col-md-6 select-input-wrapper">
										<label>UNIT</label>
										<input type="text" name="esr_greaterthan_50_unit">
									</div>
								</div>
							</div>
						</div>
						<!-- BIOCHEMISTRY INPUT AREA -->
						<div id="biochemistry" class="col-md-12 bio-input-hldr no-padding category-input-wrapper" style="display:none">
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>GLUCOSE (FASTING)</label>
									<input type="text" name="bio_fasting_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="bio_fasting_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="bio_fasting_si_rf">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>GLUCOSE (FASTING)</label>
									<input type="text" name="bio_fasting_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="bio_fasting_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="bio_fasting_cu_rf">
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>GLUCOSE (RANDOM)</label>
									<input type="text" name="bio_random_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="bio_random_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="bio_random_si_rf">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>GLUCOSE (RANDOM)</label>
									<input type="text" name="bio_random_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="bio_random_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="bio_random_cu_rf">
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper">
									<label>BLOOD UREA NITROGEN</label>
									<input type="text" name="blood_urea_nitrogen_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="blood_urea_nitrogen_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="blood_urea_nitrogen_si_rf">
									<ul class="sub-note ">
										<li>Female < 50yrs : 2.6 - 6.7mmol/L</li>
										<li>Female > 50yrs : 3.5 - 7.2mmol/L</li>
									</ul>
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>BLOOD UREA NITROGEN</label>
									<input type="text" name="blood_urea_nitrogen_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="blood_urea_nitrogen_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="blood_urea_nitrogen_cu_rf">
									<ul class="sub-note ">
										<li>Female < 50yrs : 7.28 - 18.78 mg/dL</li>
										<li>Female > 50yrs : 9.80 - 20.16 mg/dL</li>
									</ul>
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>CREATININE</label>
									<input type="text" name="creatinine_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="creatinine_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="creatinine_si_rf">
									<ul class="sub-note">
										<li>Female : 40 - 88  µmol/L</li>
									</ul>
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>CREATININE</label>
									<input type="text" name="creatinine_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="creatinine_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="creatinine_cu_rf">
									<ul class="sub-note">
										<li>Female : 0.5  - 1.0 mg/dL</li>
									</ul>
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>SGOT</label>
									<input type="text" name="sgot_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="sgot_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="sgot_si_rf">
									<ul class="sub-note">
										<li>Female : 0 - 31 U/L</li>
									</ul>
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>SGOT</label>
									<input type="text" name="sgot_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="sgot_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="sgot_cu_rf">
									<ul class="sub-note">
										<li>Female : 0 - 31 U/L</li>
									</ul>
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>SGPT</label>
									<input type="text" name="sgpt_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="sgpt_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="sgpt_si_rf">
									<ul class="sub-note">
										<li>Female : 0 - 34 U/L</li>
									</ul>
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>SGPT</label>
									<input type="text" name="sgpt_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="sgpt_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="sgpt_cu_rf">
									<ul class="sub-note">
										<li>Female : 0 - 34 U/L</li>
									</ul>
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>ALK. PHOSPHATASE</label>
									<input type="text" name="alk_phosphatase_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="alk_phosphatase_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="alk_phosphatase_si_rf">
									<ul class="sub-note">
										<li>Female : 35 - 104 U/L</li>
									</ul>
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>ALK. PHOSPHATASE</label>
									<input type="text" name="alk_phosphatase_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="alk_phosphatase_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="alk_phosphatase_cu_rf">
									<ul class="sub-note">
										<li>Female : 35-104 U/L</li>
									</ul>
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>GGT (Enzymatic)</label>
									<input type="text" name="ggt_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="ggt_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="ggt_si_rf">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>GGT (Enzymatic)</label>
									<input type="text" name="ggt_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="ggt_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="ggt_cu_rf">
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>TOTAL BILIRUBIN</label>
									<input type="text" name="total_bilirubin_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="total_bilirubin_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="total_bilirubin_si_rf">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>TOTAL BILIRUBIN</label>
									<input type="text" name="total_bilirubin_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="total_bilirubin_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="total_bilirubin_cu_rf">
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>DIRECT BILIRUBIN</label>
									<input type="text" name="direct_bilirubin_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="direct_bilirubin_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="direct_bilirubin_si_rf">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>DIRECT BILIRUBIN</label>
									<input type="text" name="direct_bilirubin_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="direct_bilirubin_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="direct_bilirubin_cu_rf">
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>INDIRECT BILIRUBIN</label>
									<input type="text" name="indirect_bilirubin_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="indirect_bilirubin_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="indirect_bilirubin_si_rf">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>INDIRECT BILIRUBIN</label>
									<input type="text" name="indirect_bilirubin_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="indirect_bilirubin_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="indirect_bilirubin_cu_rf">
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>TOTAL PROTEIN </label>
									<input type="text" name="total_protein_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="total_protein_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="total_protein_si_rf">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>TOTAL PROTEIN </label>
									<input type="text" name="total_protein_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="total_protein_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="total_protein_cu_rf">
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>ALBUMIN</label>
									<input type="text" name="albumin_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="albumin_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="albumin_si_rf">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>ALBUMIN</label>
									<input type="text" name="albumin_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="albumin_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="albumin_cu_rf">
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>GLOBULIN</label>
									<input type="text" name="globulin_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="globulin_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="globulin_si_rf">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>GLOBULIN</label>
									<input type="text" name="globulin_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="globulin_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="globulin_cu_rf">
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>A/G RATIO</label>
									<input type="text" name="ag_ratio_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="ag_ratio_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="ag_ratio_si_rf">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>A/G RATIO</label>
									<input type="text" name="ag_ratio_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="ag_ratio_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="ag_ratio_cu_rf">
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>LACTOSE DEHYDROGENASE</label>
									<input type="text" name="lactose_dehydrogenase_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="lactose_dehydrogenase_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="lactose_dehydrogenase_si_rf">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>LACTOSE DEHYDROGENASE</label>
									<input type="text" name="lactose_dehydrogenase_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="lactose_dehydrogenase_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="lactose_dehydrogenase_cu_rf">
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>INORGANIC PHOSPHATE</label>
									<input type="text" name="inorganic_phosphate_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="inorganic_phosphate_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="inorganic_phosphate_si_rf">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>INORGANIC PHOSPHATE</label>
									<input type="text" name="inorganic_phosphate_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="inorganic_phosphate_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="inorganic_phosphate_cu_rf">
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>BICARBONATE  (ENZYMATIC)</label>
									<input type="text" name="bicarbonate_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="bicarbonate_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="bicarbonate_si_rf">
									<ul class="sub-note">
										<li>Female : 22 - 30 mmol/L</li>
									</ul>
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>BICARBONATE  (ENZYMATIC)</label>
									<input type="text" name="bicarbonate_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="bicarbonate_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="bicarbonate_cu_rf">
									<ul class="sub-note">
										<li>Female : 22 - 30 meq/L</li>
									</ul>
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>AMYLASE</label>
									<input type="text" name="amylase_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="amylase_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="amylase_si_rf">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>AMYLASE</label>
									<input type="text" name="amylase_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="amylase_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="amylase_cu_rf">
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>LIPASE</label>
									<input type="text" name="lipase_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="lipase_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="lipase_si_rf">
									<ul class="sub-note">
										<li>Newborn : < 60 U/L</li>
										<li>Adult : 10 - 140 U/L</li>
										<li>Elderly : (> 60 y/o): 18 -180 U/L </li>
									</ul>
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>LIPASE</label>
									<input type="text" name="lipase_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="lipase_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="lipase_cu_rf">
									<ul class="sub-note">
										<li>Newborn : < 60 U/L</li>
										<li>Adult : 10 - 140 U/L</li>
										<li>Elderly : (> 60 y/o): 18 -180 U/L </li>
									</ul>
								</div>
							</div>
							<div class="clear"></div>
							<hr class="custom-hr">
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>CK TOTAL</label>
									<input type="text" name="ck_total_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="ck_total_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="ck_total_si_rf">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>CK TOTAL</label>
									<input type="text" name="ck_total_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="ck_total_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="ck_total_cu_rf">
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>CPK MM</label>
									<input type="text" name="cpk_mm_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="cpk_mm_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="cpk_mm_si_rf">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>CPK MM</label>
									<input type="text" name="cpk_mm_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="cpk_mm_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="cpk_mm_cu_rf">
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>CK - MB</label>
									<input type="text" name="ck_mb_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="ck_mb_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="ck_mb_si_rf">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>CK - MB</label>
									<input type="text" name="ck_mb_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="ck_mb_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="ck_mb_cu_rf">
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>FRUCTOSAMINE</label>
									<input type="text" name="fructosamine_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="fructosamine_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="fructosamine_si_rf">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>FRUCTOSAMINE</label>
									<input type="text" name="fructosamine_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="fructosamine_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="fructosamine_cu_rf">
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>HBA1C</label>
									<input type="text" name="hba1c_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="hba1c_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="hba1c_si_rf">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>HBA1C</label>
									<input type="text" name="hba1c_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="hba1c_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="hba1c_cu_rf">
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>LIPOPROTEIN (a)</label>
									<input type="text" name="lipoprotein_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="lipoprotein_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="lipoprotein_si_rf">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>LIPOPROTEIN (a)</label>
									<input type="text" name="lipoprotein_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="lipoprotein_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="lipoprotein_cu_rf">
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>CHOLESTEROL</label>
									<input type="text" name="cholesterol_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="cholesterol_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="cholesterol_si_rf">
									<ul class="sub-note ">
										<li>Desirable : ≤5.2 mmol/L</li>
										<li>Borderline High Risk : 5.2 - 6.2 mmol/L</li>
										<li>High Risk : >6.2 mmol/L</li>
									</ul>
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>CHOLESTEROL</label>
									<input type="text" name="cholesterol_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="cholesterol_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="cholesterol_cu_rf">
									<ul class="sub-note ">
										<li>Desirable : ≤200 mg/dL</li>
										<li>Borderline High Risk : 200 - 240 mg/dL</li>
										<li>High Risk : > 240 mg/dL</li>
									</ul>
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>TRIGLYCERIDES</label>
									<input type="text" name="triglycerides_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="triglycerides_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="triglycerides_si_rf">
									<ul class="sub-note">
										<li>Normal : < 1.695 mmol/L</li>
										<li>Low Risk : 1.695 - 2.26 mmol/L</li>
										<li>High Risk : 2.26 - 5.65 mmol/L</li>
										<li>Extremely High : >5.65 mmol/L</li>
									</ul>
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>TRIGLYCERIDES</label>
									<input type="text" name="triglycerides_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="triglycerides_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="triglycerides_cu_rf">
									<ul class="sub-note">
										<li>Normal : < 150 mg/dL</li>
										<li>Low Risk : 150 - 200 mg/dL</li>
										<li>High Risk : 200 - 500 mg/dL</li>
										<li>Extremely High : > 500 mg/dL</li>
									</ul>
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>HDL </label>
									<input type="text" name="hdl_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="hdl_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="hdl_si_rf">
									<ul class="sub-note">
										<li>Female : 0.77 - 2.19 mmol/L</li>
									</ul>
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>HDL</label>
									<input type="text" name="hdl_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="hdl_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="hdl_cu_rf">
									<ul class="sub-note">
										<li>Male : 30 - 85 mg/dL</li>
									</ul>
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>LDL</label>
									<input type="text" name="ldl_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="ldl_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="ldl_si_rf">
									<ul class="sub-note ">
										<li>Desirable : < 3.36 mmol/L</li>
										<li>Borderline High Risk : 3.36 - 4.11 mmol/L</li>
										<li>High Risk : >4.14 mmol/L</li>
									</ul>
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>LDL</label>
									<input type="text" name="ldl_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="ldl_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="ldl_cu_rf">
									<ul class="sub-note ">
										<li>Desirable : < 130 mg/dL</li>
										<li>Borderline High Risk : 130 - 159 mg/dL</li>
										<li>High Risk : >160 mg/dL</li>
									</ul>
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>VLDL</label>
									<input type="text" name="vldl_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="vldl_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="vldl_si_rf">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>VLDL</label>
									<input type="text" name="vldl_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="vldl_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="vldl_cu_rf">
								</div>
							</div>
							<div class="clear"></div>
							<hr class="custom-hr">
							<div class="col-md-12 no-padding">
								<h6>RATIO</h6>
								<div class="col-md-12 no-padding">
									<div class="col-md-2 select-input-wrapper no-padding">
										<label>Total Chole:HDL</label>
										<input type="text" name="total_chole_hdl_si">
									</div>
									<div class="col-md-2 select-input-wrapper">
										<label>si unit</label>
										<input type="text" name="total_chole_hdl_si_unit">
									</div>
									<div class="col-md-2 select-input-wrapper border-right">
										<label>reference value</label>
										<input type="text" name="total_chole_hdl_si_rf">
									</div>
									<div class="col-md-2 select-input-wrapper">
										<label>Total Chole:HDL</label>
										<input type="text" name="total_chole_hdl_cu">
									</div>
									<div class="col-md-2 select-input-wrapper">
										<label>Conventional unit</label>
										<input type="text" name="total_chole_hdl_cu_unit">
									</div>
									<div class="col-md-2 select-input-wrapper">
										<label>reference value</label>
										<input type="text" name="total_chole_hdl_cu_rf">
										<ul class="sub-note">
											<li>Preferably : < 5.0</li>
											<li>Ideal : < 3.5</li>
										</ul>
									</div>
								</div>
								<div class="col-md-12 no-padding">
									<div class="col-md-2 select-input-wrapper no-padding">
										<label>HDL:LDL</label>
										<input type="text" name="hdl_ldl_si">
									</div>
									<div class="col-md-2 select-input-wrapper">
										<label>si unit</label>
										<input type="text" name="hdl_ldl_si_unit">
									</div>
									<div class="col-md-2 select-input-wrapper border-right">
										<label>reference value</label>
										<input type="text" name="hdl_ldl_si_rf">
									</div>
									<div class="col-md-2 select-input-wrapper">
										<label>HDL:LDL</label>
										<input type="text" name="hdl_ldl_cu">
									</div>
									<div class="col-md-2 select-input-wrapper">
										<label>Conventional unit</label>
										<input type="text" name="hdl_ldl_cu_unit">
									</div>
									<div class="col-md-2 select-input-wrapper">
										<label>reference value</label>
										<input type="text" name="hdl_ldl_cu_rf">
										<ul class="sub-note">
											<li>Preferably : >0.3</li>
											<li>Ideal : >0.4</li>
										</ul>
									</div>
								</div>
								<div class="col-md-12 no-padding">
									<div class="col-md-2 select-input-wrapper no-padding">
										<label>Triglycerides:HDL</label>
										<input type="text" name="triglycerides_hdl_si">
									</div>
									<div class="col-md-2 select-input-wrapper">
										<label>si unit</label>
										<input type="text" name="triglycerides_hdl_si_unit">
									</div>
									<div class="col-md-2 select-input-wrapper border-right">
										<label>reference value</label>
										<input type="text" name="triglycerides_hdl_si_rf">
									</div>
									<div class="col-md-2 select-input-wrapper">
										<label>Triglycerides:HDL</label>
										<input type="text" name="triglycerides_hdl_cu">
									</div>
									<div class="col-md-2 select-input-wrapper">
										<label>Conventional unit</label>
										<input type="text" name="triglycerides_hdl_cu_unit">
									</div>
									<div class="col-md-2 select-input-wrapper">
										<label>reference value</label>
										<input type="text" name="triglycerides_hdl_cu_rf">
										<ul class="sub-note">
											<li>Preferably : < 4</li>
											<li>Ideal : < 2</li>
										</ul>
									</div>
								</div>
							</div>
							<div class="clear"></div>
							<hr class="custom-hr">
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>SODIUM</label>
									<input type="text" name="sodium_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="sodium_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="sodium_si_rf">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>SODIUM</label>
									<input type="text" name="sodium_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="sodium_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="sodium_cu_rf">
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>POTASSIUM</label>
									<input type="text" name="potassium_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="potassium_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="potassium_si_rf">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>POTASSIUM</label>
									<input type="text" name="potassium_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="potassium_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="potassium_cu_rf">
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>CALCIUM</label>
									<input type="text" name="calcium_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="calcium_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="calcium_si_rf">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>CALCIUM</label>
									<input type="text" name="calcium_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="calcium_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="calcium_cu_rf">
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>CHLORIDE</label>
									<input type="text" name="chloride_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="chloride_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="chloride_si_rf">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>CHLORIDE</label>
									<input type="text" name="chloride_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="chloride_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="chloride_cu_rf">
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>MAGNESIUM</label>
									<input type="text" name="magnesium_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="magnesium_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="magnesium_si_rd">
									<ul class="sub-note">
										<li>Female : 0.65 - 1.05 mmol/L</li>
									</ul>
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>MAGNESIUM</label>
									<input type="text" name="magnesium_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="magnesium_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="magnesium_cu_rf">
									<ul class="sub-note">
										<li>Female : 1.58 - 2.55 mg/dL</li>
									</ul>
								</div>
							</div>
						</div>
						<!-- ORAL GLUCOSE CHALLENGE INPUT AREA -->
						<div id="oral_glucose_challenge" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper" style="display:none">
							<div class="col-md-2 select-input-wrapper">
								<label>GLUCOSE (2nd hour)</label>
								<input type="text" name="glucose_si">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>SI UNIT</label>
								<input type="text" name="glucose_si_unit">
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>REFERENCE VALUE</label>
								<input type="text" name="glucose_si_rf">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>GLUCOSE (2nd hour)</label>
								<input type="text" name="glucose_cu">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>COnventional UNIT</label>
								<input type="text" name="glucose_cu_unit">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="glucose_cu_rf">
							</div>
							<p><b><i>Note: 75g Glucose load.</i></b></p>
						</div>
						<!-- ORAL GLUCOSE TOLERANCE INPUT AREA -->
						<div id="oral_glucose_tolerance" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper" style="display:none">
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper">
									<label>GLUCOSE (FASTING)</label>
									<input type="text" name="fasting_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si UNIT</label>
									<input type="text" name="fasting_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>REFERENCE VALUE</label>
									<input type="text" name="fasting_si_rf">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>GLUCOSE (FASTING)</label>
									<input type="text" name="fasting_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>conventional UNIT</label>
									<input type="text" name="fasting_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="fasting_cu_rf">
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper">
									<label>GLUCOSE (1ST HOUR)</label>
									<input type="text" name="1st_hour_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si UNIT</label>
									<input type="text" name="1st_hour_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>REFERENCE VALUE</label>
									<input type="text" name="1st_hour_si_rf">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>GLUCOSE (1ST HOUR)</label>
									<input type="text" name="1st_hour_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>conventional UNIT</label>
									<input type="text" name="1st_hour_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="1st_hour_cu_rf">
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper">
									<label>GLUCOSE (2ND HOUR)</label>
									<input type="text" name="2nd_hour_si">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>SI UNIT</label>
									<input type="text" name="2nd_hour_si_unit">
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>REFERENCE VALUE</label>
									<input type="text" name="2nd_hour_si_rf">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>GLUCOSE (2ND HOUR)</label>
									<input type="text" name="2nd_hour_cu">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>conventional UNIT</label>
									<input type="text" name="2nd_hour_cu_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="2nd_hour_cu_rf">
								</div>
							</div>
						</div>
						<!-- THYROID INPUT AREA -->
						<div id="thyroid" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper" style="display:none">
							<div class="col-md-6 border-right">
								<div class="col-md-4 select-input-wrapper">
									<label>FT3 (CLIA)</label>
									<input type="text" name="ft3">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="ft3_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="ft3_rf">
								</div>
								<div class="clear"></div>
								<div class="col-md-4 select-input-wrapper">
									<label>FT4 (CLIA)</label>
									<input type="text" name="ft4">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="ft4_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="ft4_rf">
								</div>
								<div class="clear"></div>
								<div class="col-md-4 select-input-wrapper">
									<label>TSH (CLIA)</label>
									<input type="text" name="tsh">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="tsh_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="tsh_rf">
								</div>
								<div class="clear"></div>
							</div>
							<div class="col-md-6">
								<div class="col-md-4 select-input-wrapper">
									<label>T3 REVERSE (CLIA)</label>
									<input type="text" name="t3_reverse">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="t3_reverse_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="t3_reverse_rf">
								</div>
								<div class="clear"></div>
								<div class="col-md-4 select-input-wrapper">
									<label>THYROGLOBULIN ANTIBODY (CLIA)</label>
									<input type="text" name="thyroglobulin_antibody">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="thyroglobulin_antibody_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="thyroglobulin_antibody_rf">
								</div>
								<div class="clear"></div>
								<div class="col-md-4 select-input-wrapper">
									<label>THYROID PEROXIDASE ANTIBODY (CLIA)</label>
									<input type="text" name="thyroid_peroxidase_antibody">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="thyroid_peroxidase_antibody_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="thyroid_peroxidase_antibody_rf">
								</div>
								<div class="clear"></div>
							</div>
						</div>
						<!-- HORMONES INPUT AREA -->
						<div id="hormones" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper" style="display:none">
							<div class="col-md-6 border-right">
								<div class="col-md-4 select-input-wrapper">
									<label>FSH (CLIA)</label>
									<input type="text" name="fsh">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="fsh_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="fsh_rf">
									<ul class="sub-note">
										<li>Ovulation Peak : 6.3 - 24</li>
										<li>Follicular Phase
											<ul>
												<li>1st Half : 3.9 -12.0 </li>
												<li>2nd Half : 2.9 - 9.0</li>
											</ul>
										</li>
										<li>Luteal Phase : 1.5 -7.0</li>
										<li>Menopause : 17 - 95</li>
									</ul>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>LH (CLIA)</label>
									<input type="text" name="lh">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="lh_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="lh_rf">
									<ul class="sub-note">
										<li>Ovulation Peak : 9.6 - 80</li>
										<li>Follicular Phase
											<ul>
												<li>1st Half : 1.5 - 8.0 </li>
												<li>2nd Half : 2.0 - 8.0</li>
											</ul>
										</li>
										<li>Luteal Phase : 0.2 - 6.5</li>
										<li>Menopause : 8.0 - 33.0</li>
									</ul>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>PROGESTERONE (CLIA)</label>
									<input type="text" name="progesterone">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="progesterone_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="progesterone_rf">
									<ul class="sub-note">
										<li>Follicular Phase : < 0.25 - 54 </li>
										<li>Luteal Phase : 0.2 - 6.5</li>
										<li>Ovulation Peak : < 0.25 - 6.22 </li>
										<li>Menopause : 8.0 - 33.0</li>
									</ul>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>ESTRADIOL (CLIA)</label>
									<input type="text" name="estradiol">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="estradiol_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="estradiol_rf">
									<ul class="sub-note">
										<li>Follicular Phase : < 18 -147</li>
										<li>Pre-Ovulatroy : 93 - 575 </li>
										<li>Luteal : 43 -214</li>
										<li>Menopause : < 58</li>
									</ul>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>TOTAL TESTOSTERONE (CLIA)</label>
									<input type="text" name="total_testosterone">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="total_testosterone_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="total_testosterone_rf">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>DIHYDROTESTOSTERONE (DHT) - RIA</label>
									<input type="text" name="dht">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="dht_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="dht_rf">
									<ul class="sub-note">
										<li>Premenopausal : .024- 0.368</li>
										<li>Postmenopausal : 0.010 - 0.181</li>
									</ul>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>CORTISOL (CLIA)</label>
									<input type="text" name="cortisol">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="cortisol_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="cortisol_rf">
									<ul class="sub-note">
										<li>8:00 A.M SPECIMEN : 5 - 23</li>
									</ul>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>SEROTONIN (ELISA)</label>
									<input type="text" name="serotonin">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="serotonin_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="serotonin_rf">
								</div>
							</div>
							<div class="col-md-6">
								<div class="col-md-4 select-input-wrapper">
									<label>PREGNENOLONE (ELISA)</label>
									<input type="text" name="pregnenolone">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="pregnenolone_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="pregnenolone_rf">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>INSULIN ASSAY (CLIA) (FASTING)</label>
									<input type="text" name="insulin_assay_fasting">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="insulin_assay_fasting_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="insulin_assay_fasting_rf">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>INSULIN ASSAY (CLIA) (POST PRANDIAL)</label>
									<input type="text" name="post_prandial">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="post_prandial_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="post_prandial_rf">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>Dehydroepiandrosterone Sulfate (DHEA-SO4) - (CLIA)</label>
									<input type="text" name="dhea_so4">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="dhea_so4_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="dhea_so4_rf">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>INSULIN GROWTH FACTOR-1 (IGF-1) - (CLIA)</label>
									<input type="text" name="igf_1">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="igf_1_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="igf_1_rf">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>INSULIN GROWTH FACTOR-BP3 (IGF-BP3) - (CLIA)</label>
									<input type="text" name="igf_bp3">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="igf_bp3_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="igf_bp3_rf">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>OSTEOCALCIN (ELISA)</label>
									<input type="text" name="osteocalcin">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="osteocalcin_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="osteocalcin_rf">
									<ul class="sub-note">
										<li>Premenopausal Phase <br> > 20 YRS OLD : 11.0 - 43.0 </li>
										<li>Postmenopausal Phase : 15.0 - 46.0</li>
										<li>Osteoporosis Patients : 13.0 - 48.0</li>
									</ul>
								</div>
							</div>
						</div>
						<!-- HOMEOSTASIS ASSESSMENT INDEX INPUT AREA -->
						<div id="homeostasis_ass_index" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper" style="display:none">
							<div class="col-md-4 border-right">
								<div class="col-md-12 select-input-wrapper">
								<label>Beta Cell Function (% β :)</label>
								<input type="text" name="beta_cell_function">
								</div>
							</div>
							<div class="col-md-4 border-right">
								<div class="col-md-12 select-input-wrapper">
									<label>Insulin Sensitivity (% S :)</label>
									<input type="text" name="insulin_sensitivity">
								</div>
							</div>
							<div class="col-md-4">
								<div class="col-md-12 select-input-wrapper">
									<label>Insulin Resistance (IR :)</label>
									<input type="text" name="insulin_resistance">
								</div>
							</div>
							<div class="clear"></div>
							<div class="col-md-12 note-test">
								<p>
									<b><i>Note</i></b>: The measures correspond well, but are not necessarily equivalent, to non-steady state estimates of beta cell function and insulin sensitivity derived from stimulatory models such as the hyperinsulinaemic clamp the hyperglycaemic clamp, the intravenous glucose tolerance test ( acute insulin response, minimal model) &   the oral glucose tolerance test (0-30 delta I/G).
								</p>
							</div>
						</div>
						<!-- SEROLOGY / IMMUNOLOGY INPUT AREA -->
						<div id="serology" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper" style="display:none">
							<div class="col-md-6 border-right">
								<div class="col-md-4 select-input-wrapper">
									<label>RHEUMATOID FACTOR </label>
									<input type="text" name="rheumatoid_factor">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="rheumatoid_factor_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="rheumatoid_factor_rf">
								</div>
								<div class="clear"></div>
								<div class="col-md-4 select-input-wrapper">
									<label>C-REACTIVE PROTEIN (HCRP)</label>
									<input type="text" name="c-reactive_protein">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="c-reactive_protein_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="c-reactive_protein_rf">
								</div>
								<div class="clear"></div>
								<div class="col-md-4 select-input-wrapper">
									<label>FERRITIN</label>
									<input type="text" name="ferritin">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="ferritin-unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="ferritin_rf">
								</div>
							</div>
							<div class="col-md-6">
								<div class="col-md-4 select-input-wrapper">
									<label>ERYTHROPOIETIN</label>
									<input type="text" name="erythropoietin">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</span></label>
									<input type="text" name="erythropoietin_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="erythropoietin_rf">
								</div>
								<div class="clear"></div>
								<div class="col-md-4 select-input-wrapper">
									<label>Serum Immunoglobulin E <span style="text-transform:initial">(IgE)</span></label>
									<input type="text" name="serum_immunoglobulin">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="serum_immunoglobulin_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="serum_immunoglobulin_rf">
								</div>
							</div>
							<div class="clear"></div>
							<hr class="custom-hr">
							<div class="col-md-12">
								<div class="col-md-2 select-input-wrapper">
									<label>CMV <span class="text-initial">(IgM)</span></label>
									<input type="text" name="cmv">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>patient</label>
									<input type="text" name="patient">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>CUT-OFF VALUE</label>
									<input type="text" name="cut-off">
								</div>
							</div>
							<div class="clear"></div>
							<hr class="custom-hr">
							<div class="col-md-12">
								<div class="col-md-2 select-input-wrapper">
									<label>TP-HA</label>
									<input type="text" name="tp-ha">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>unit</label>
									<input type="text" name="tp-ha_unit">
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="tp-ha_rf">
								</div>
							</div>
						</div>
						<!-- TUMOR MARKERS INPUT AREA -->
						<div id="tumor_markers" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper" style="display:none">
							<div class="col-md-6 border-right">
								<div class="col-md-4 select-input-wrapper">
									<label>CEA  (CLIA)</label>
									<input type="text" name="cea">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="cea_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="cea_rf">
									<ul class="sub-note">
										<li>Non-Smoker : < 2.5 </li>
										<li>Smoker : < 5 </li>
									</ul>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>CA 125   (CLIA)</label>
									<input type="text" name="ca_125">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="ca_125_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="ca_125_rf">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>CA 19-9   (CLIA)</label>
									<input type="text" name="ca_19_9">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="ca_19_9_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="ca_19_9_rf">
								</div>
							</div>
							<div class="col-md-6">
								<div class="col-md-4 select-input-wrapper">
									<label>CA 72-4  (CLIA)</label>
									<input type="text" name="ca_72_4">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="ca_72_4_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="ca_72_4_rf">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>BETA-HCG  (CLIA)</label>
									<input type="text" name="beta_hcg">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="beta_hcg_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="beta_hcg_rf">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>CYFRA 21-1</label>
									<input type="text" name="cyfra_21_1">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="cyfra_21_1_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="cyfra_21_1_rf">
								</div>
							</div>
						</div>
						<!-- SPECIAL CHEMISTRY INPUT AREA -->
						<div id="special_chem" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper" style="display:none">
							<div class="col-md-6 border-right">
								<div class="col-md-4 select-input-wrapper">
									<label>HOMOCYSTEINE</label>
									<input type="text" name="homocysteine">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>unit</label>
									<input type="text" name="homocysteine_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="homocysteine_rf">
								</div>
							</div>
							<div class="col-md-6">
								<div class="col-md-4 select-input-wrapper">
									<label style="text-transform:initial">NT-proBNP</label>
									<input type="text" name="NT-proBNPNT-proBNP">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>unit</label>
									<input type="text" name="NT-proBNP_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="NT-proBNP_rf">
								</div>
							</div>
						</div>
						<!-- VITAMINS AND NUTRIENTS INPUT AREA -->
						<div id="vita_nutri" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper" style="display:none">
							<div class="col-md-2 select-input-wrapper">
								<label>VITAMIN D 25 OH</label>
								<input type="text" name="vitamin_d_25">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>unit</label>
								<input type="text" name="vitamin_d_25_unit">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="vitamin_d_25_rf">
							</div>
							<div class="clear"></div>
							<div class="col-md-2 select-input-wrapper">
								<label>VITAMIN B12</label>
								<input type="text" name="vitamin_b12">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>unit</label>
								<input type="text" name="vitamin_b12_unit">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="vitamin_b12_rf">
							</div>
							<div class="clear"></div>
							<div class="col-md-2 select-input-wrapper">
								<label>FOLATE</label>
								<input type="text" name="folate">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>unit</label>
								<input type="text" name="folate_unit">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="folate_rf">
							</div>
						</div>
						<!-- VIRAL HEPATITIS TEST INPUT AREA -->
						<div id="viral_hepa" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper" style="display:none">
							<div class="col-md-6 border-right">
								<div class="col-md-4 select-input-wrapper">
									<label class="text-initial">HBs Ag</label>
									<input type="text" name="hbs_ag">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>patient</label>
									<input type="text" name="hbs_ag_patient">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>CUT-OFF VALUE</label>
									<input type="text" name="hbs_ag_cutoff">
								</div>
								<div class="clear"></div>
								<div class="col-md-4 select-input-wrapper">
									<label class="text-initial">ANTI-HBs</label>
									<input type="text" name="anti_hbs">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>patient</label>
									<input type="text" name="anti_hbs_patient">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>CUT-OFF VALUE</label>
									<input type="text" name="anti_hbs_cutoff">
								</div>
								<div class="clear"></div>
								<div class="col-md-4 select-input-wrapper">
									<label class="text-initial">ANTI- HBc IgM</label>
									<input type="text" name="anti_hbc_lgm">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>patient</label>
									<input type="text" name="anti_hbc_lgm_patient">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>CUT-OFF VALUE</label>
									<input type="text" name="anti_hbc_lgm_cutoff">
								</div>
								<div class="clear"></div>
								<div class="col-md-4 select-input-wrapper">
									<label class="text-initial">ANTI- HBc IgG</label>
									<input type="text" name="anti_hbc_lgg">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>patient</label>
									<input type="text" name="anti_hbc_lgg_patient">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>CUT-OFF VALUE</label>
									<input type="text" name="anti_hbc_lgg_cutoff">
								</div>
								<div class="clear"></div>
								<div class="col-md-4 select-input-wrapper">
									<label class="text-initial">Hbe Ag</label>
									<input type="text" name="hbe_ag">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>patient</label>
									<input type="text" name="hbe_ag_patient">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>CUT-OFF VALUE</label>
									<input type="text" name="hbe_ag_cutoff">
								</div>
							</div>
							<div class="col-md-6">
								<div class="col-md-4 select-input-wrapper">
									<label class="text-initial">ANTI- Hbe</label>
									<input type="text" name="anti_hbe">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>patient</label>
									<input type="text" name="anti_hbe_patient">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>CUT-OFF VALUE</label>
									<input type="text" name="anti_hbe_cutoff">
								</div>
								<div class="clear"></div>
							
								<div class="col-md-4 select-input-wrapper">
									<label class="text-initial">ANTI- HCV</label>
									<input type="text" name="anti_hcv">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>patient</label>
									<input type="text" name="anti_hcv_patient">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>CUT-OFF VALUE</label>
									<input type="text" name="anti_hcv_cutoff">
								</div>
								<div class="clear"></div>
								<div class="col-md-4 select-input-wrapper">
									<label class="text-initial">ANTI- HAV IgM</label>
									<input type="text" name="anti_hav_lgm">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>patient</label>
									<input type="text" name="anti_hav_lgm_patient">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>CUT-OFF VALUE</label>
									<input type="text" name="anti_hav_lgm_cutoff">
								</div>
								<div class="clear"></div>
								<div class="col-md-4 select-input-wrapper">
									<label class="text-initial">ANTI- HAV IgG</label>
									<input type="text" name="anti_hav_lgg">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>patient</label>
									<input type="text" name="anti_hav_lgg_patient">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>CUT-OFF VALUE</label>
									<input type="text" name="anti_hav_lgg_cutoff">
								</div>
							</div>
						</div>
						<!-- HIV INPUT AREA -->
						<div id="hiv" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper" style="display:none">
							<div class="col-md-2 select-input-wrapper">
								<label class="text-initial">HIV</label>
								<input type="text" name="hiv">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>patient</label>
								<input type="text" name="hiv_patient">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>CUT-OFF VALUE</label>
								<input type="text" name="hiv_cut_off">
							</div>
						</div>
						<!-- eGFR TEST INPUT AREA -->
						<div id="eGFR" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper" style="display:none">
							<div class="col-md-12">
								<div class="col-md-4 select-input-wrapper">
									<label>Estimated GLOMERULAR  FILTRATION RATE <span class="text-initial">(eGFR)</span></label>
									<input type="text" name="egfr">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>unit</label>
									<input type="text" name="egfr_unit">
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="egfr_rf">
									<p class="notes-sm">Please refer to the table below</p>
								</div>
							</div>
							<div class="col-md-12 note-test">
								<p><b>Note:</b> Please consider clinical correlation to potential inaccuracies due to the non-steady state of serum  creatinine, co-morbidities that cause malnutrition and the use of medications that interfere with the measurement of serum creatinine. </p>
								<table>
									<thead>
										<tr>
											<th>Age (Years)</th>
											<th>Mean eGFR*</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>20 - 29</td>
											<td>116 mL/min/1.73 m2</td>
										</tr>
										<tr>
											<td>30-39 </td>
											<td>107 mL/min/1.73 m2</td>
										</tr>
										<tr>
											<td>40-49 </td>
											<td>99 mL/min/1.73 m2</td>
										</tr>
										<tr>
											<td>50-59</td>
											<td>93 mL/min/1.73 m2</td>
										</tr>
										<tr>
											<td>60-69 </td>
											<td>85 mL/min/1.73 m2</td>
										</tr>
										<tr>
											<td>70 +</td>
											<td>75 mL/min/1.73 m2</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<!-- NUTRIENT ELEMENTS INPUT AREA -->
						<div id="nutri_elements" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper" style="display:none">
							<div class="col-md-2 select-input-wrapper">
								<label>MAGNESIUM, RBC (ICPS)</label>
								<input type="text" name="magnesium_rbc">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>unit</label>
								<input type="text" name="magnesium_rbc_unit">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="magnesium_rbc_rf">
							</div>
							<div class="clear"></div>
							<div class="col-md-2 select-input-wrapper">
								<label>MERCURY, RBC (ADAS)</label>
								<input type="text" name="mercury_rbc">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>unit</label>
								<input type="text" name="mercury_rbc_unit">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="mercury_rbc_rf">
							</div>
							<div class="clear"></div>
							<div class="col-md-2 select-input-wrapper">
								<label>LEAD, RBC (GFAAS)</label>
								<input type="text" name="lead_rbc">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>unit</label>
								<input type="text" name="lead_rbc_unit">
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="lead_rbc_rf">
							</div>
						</div>
						<div class="clear"></div>
					</div>
					<!-- FOR IMAGE UPLOAD -->
					<div class="col-md-12 img-input-hldr no-padding for-img-test">
						<div class="col-md-6 ">
							<div class="upload-img-hldr">
					            <div class="form-group file-input-group">
								    <input type="file" name="img[]" class="file">
								    <div class="input-group col-xs-12">
								      <span class="input-group-addon"><i class="glyphicon glyphicon-picture"></i></span>
								      <input type="text" class="form-control file-name input-lg" disabled placeholder="Select Image">
								      <span class="input-group-btn">
								        <button class="browse btn btn-primary input-lg" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
								      </span>
								      <input type="text" name="" class="form-control input-lg" placeholder="Description">

								      <!-- <span class="input-group-btn">
								      <input type="button" class="removebtn col-xs-4" value="Delete" id="removebtn"' + (counter) + '"/>
								      <button class="browse btn btn-primary input-lg" type="button"><i class="glyphicon glyphicon-trash"></i> Browse</button>
								      </span> -->
								    </div>
								</div>
						    </div>
						    <a href="#" class="add-file-input def-btn view-records-btn">Add +</a>
					    </div>
					    <div class="col-md-6 no-padding">
					    	Interpretation
					    	<textarea class="input-textarea" name="interpretation"></textarea>
					    </div>
					    
					    <div class="clear"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
		<div class="save-cancel-btn-wrapper" style="display: block;">
			<button type="button" class="def-btn view-records-btn cancel-btn">Cancel</button>
			<button type="button" class="def-btn view-records-btn add_submit_btn">Save</button>
		</div>
	</section>
</form>
<script type="text/javascript">
$(document).on('click', '.browse', function(){
  var file = $(this).parent().parent().parent().find('.file');
  file.trigger('click');
});
$(document).on('change', '.file', function(){
  $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
});

    var counter = 0;
    $('.add-file-input').click(function () {
        var elems =  
        '<div class="form-group file-input-group">'+
		    '<input type="file" name="img[]" class="file">'+
		    '<div class="input-group col-xs-12">'+
		      '<span class="input-group-addon"><i class="glyphicon glyphicon-picture"></i></span>'+
		      '<input type="text" class="form-control file-name input-lg" disabled placeholder="Select Image">'+
		      '<span class="input-group-btn">'+
		        '<button class="browse btn btn-primary input-lg" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>'+
		      '</span>'+
		      '<input type="text" name="" class="form-control input-lg" placeholder="Description">'+
		    '</div>'+
		    '<button class="removebtn glyphicon glyphicon-trash" id="removebtn"' + (counter) + '"/>Delete</button>' +
		  '</div>'
        $('.upload-img-hldr').append(elems);
        counter++;
        return false;
    });

    $('.removebtn').live('click',function () {
        $(this).parent().remove();   
    });
 
</script>