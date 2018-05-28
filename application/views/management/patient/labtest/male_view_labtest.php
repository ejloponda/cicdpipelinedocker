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
	      			view_dashboard(id);
	      		} else {
	      			default_success_confirmation({message : o.message, alert_type: "alert-error"});
	      		}
	      		
				$('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);
	        },
	         beforeSubmit : function(evt){
	         	$.blockUI();
	         },
	        
	        dataType : "json"
	    });

	     $(".add_submit_btn").on('click', function(event) {
			$('#add_new_labtest_form').submit();
		});
    });
</script>

<section class="area patient-dashboard-section">	
	<input type="hidden" id="id" name="patient_id" value="<?php echo $patient_id?>">
	<input type="hidden" name="labtest_id" value="<?php echo $labtest['id']?>">
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
		<div class="col-md-12 add-test-input-wrapper with-border">
			<div class="col-md-12 top-form-holder no-padding">
				<h1>View Test</h1>
				<div class="col-md-3 select-input-wrapper type-test">
					<label>type of test</label>
						<form class="radio-test-type">
						<label for="labtest">
						  <input type="radio" id="labtest" name="type_of_test" value="1" <?php echo ($labtest['type_of_test'] == "1" ? "checked" : "") ?> disabled/> Lab Test
						</label>
						<label for="imgtest">
						  <input type="radio" id="imgtest" name="type_of_test" value="0" <?php echo ($labtest['type_of_test'] == "0" ? "checked" : "") ?> disabled/> Imaging Test
						</label>
					</form>
				</div>
				<div class="col-md-3 select-input-wrapper">
					<label>category</label>
					<input type="text" name="category" id="category" value="<?php echo $labtest['category'] ?>" disabled>
				</div>
				<div class="col-md-3 select-input-wrapper">
					<label>Hospital / Clinic</label>
					<input type="text" name="hospital" id="hospital" value="<?php echo $labtest['hospital'] ?>" disabled>
				</div>
				<div class="col-md-3 select-input-wrapper">
					<label>Date of test</label>
					<input type="text" name="date_of_test" id="date_of_test" placeholder="Date of Test" value="<?php echo $labtest['date_of_test'];?>" disabled> 
				</div>
				<div class="clear"></div>
				<div class="col-md-3 select-input-wrapper">
					<label>REQUESTING PHYSICIAN</label>
						<input type="text" name="requesting_physician"  value="<?php echo $doctors['full_name'];?>" disabled>
				</div>
				<div class="col-md-3 select-input-wrapper">
					<label>PATIENT ID NO. IN TEST</label>
					<input type="text" name="test_patient_number" placeholder="Patient ID Number" value="<?php echo $labtest['test_patient_number'];?>" disabled>
				</div>
				<div class="col-md-3 select-input-wrapper">
					<label>CASE NO.</label>
					<input type="text" name="case_number" placeholder="Case Number" value="<?php echo $labtest['case_number'];?>" disabled>
				</div>
			</div>
			<div class="clear"></div>
			<hr class="custom-hr">

			<div class="col-md-12 descript-input-hldr no-padding">
				<h5>DESCRIPTION</h5>
				<!-- URINALYSIS -->
				<?php if($category == 'urinalysis'){ ?>
					<div id="urinalysis" class="col-md-12 urinalysis-input-hldr category-input-wrapper no-padding">
						<h6>GROSS</h6>
						<div class="col-md-2 select-input-wrapper">
							<label>Color</label>
							<input type="text" name="color" value="<?php echo $data_labtest['color']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>TRANSPARENCY</label>
							<input type="text" name="transparency" value="<?php echo $data_labtest['transparency']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>SPECIFIC GRAVITY</label>
							<input type="text" name="specific_gravity" value="<?php echo $data_labtest['specific_gravity']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label style="text-transform: initial;">pH</label>
							<input type="text" name="pH" value="<?php echo $data_labtest['pH']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>PROTEIN</label>
							<input type="text" name="protein" value="<?php echo $data_labtest['protein']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>SUGAR</label>
							<input type="text" name="sugar" value="<?php echo $data_labtest['sugar']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>BILIRUBIN</label>
							<input type="text" name="bilirubin" value="<?php echo $data_labtest['bilirubin']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>UROBILINOGEN</label>
							<input type="text" name="urobilinogen" value="<?php echo $data_labtest['urobilinogen']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>KETONE</label>
							<input type="text" name="ketone" value="<?php echo $data_labtest['ketone']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>NITRITE</label>
							<input type="text" name="nitrite" value="<?php echo $data_labtest['nitrite']; ?>" disabled>
						</div>
						<div class="clear"></div>
						<hr class="custom-hr">
						<h6>MICROSCOPIC</h6>
						<div class="col-md-2 select-input-wrapper">
							<label>RBC</label>
							<input type="text" name="microscopic_rbc" value="<?php echo $data_labtest['microscopic_rbc']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>PUS CELL</label>
							<input type="text" name="pus_cell" value="<?php echo $data_labtest['pus_cell']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>EPITHELIAL CELLS</label>
							<input type="text" name="epithelial_cell" value="<?php echo $data_labtest['epithelial_cell']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>MUCUS THREADS</label>
							<input type="text" name="mucus_threads" value="<?php echo $data_labtest['mucus_threads']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>BACTERIA</label>
							<input type="text" name="bacteria" value="<?php echo $data_labtest['bacteria']; ?>" disabled>
						</div>

						<div class="col-md-2 select-input-wrapper">
							<label>AMORPHOUS URATES</label>
							<input type="text" name="amorphous_urates" value="<?php echo $data_labtest['amorphous_urates']; ?>" disabled>
						</div>
						<div class="clear"></div>
						<hr class="custom-hr">
						<h6>CAST</h6>
						<div class="col-md-2 select-input-wrapper">
							<label>HYALINE</label>
							<input type="text" name="hyaline" value="<?php echo $data_labtest['hyaline']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>FINE GRANULAR</label>
							<input type="text" name="fine_granular" value="<?php echo $data_labtest['fine_granular']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>COARSE GRANULAR</label>
							<input type="text" name="coarse_granular" value="<?php echo $data_labtest['coarse_granular']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>RBC CAST</label>
							<input type="text" name="rbc_cast" value="<?php echo $data_labtest['rbc_cast']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>WBC CAST</label>
							<input type="text" name="wbc_cast" value="<?php echo $data_labtest['wbc_cast']; ?>" disabled>
						</div>
						<div class="clear"></div>
						<hr class="custom-hr">
						<h6>CAST</h6>
						<div class="col-md-2 select-input-wrapper">
							<label>CALCIUM OXALATES</label>
							<input type="text" name="calcium_oxalates" value="<?php echo $data_labtest['calcium_oxalates']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>URIC ACID</label>
							<input type="text" name="uric_acid" value="<?php echo $data_labtest['uric_acid']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>HIPPURIC ACID</label>
							<input type="text" name="hippuric_acid" value="<?php echo $data_labtest['hippuric_acid']; ?>" disabled>
						</div>
					</div>
				<?php } ?>

				<!-- URINE CHEMISTRY -->
				<?php if($category == 'urine_chemistry'){ ?>
					<div id="urine_chemistry" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper">
						<div class="col-md-2 select-input-wrapper">
							<label>MICROALBUMIN</label>
							<input type="text" name="microalbumin" value="<?php echo $data_labtest['microalbumin']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>UNIT</label>
							<input type="text" name="microalbumin_unit" value="<?php echo $data_labtest['microalbumin_unit']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>REFERENCE VALUE</label>
							<input type="text" name="microalbumin_rf" value="<?php echo $data_labtest['microalbumin_rf']; ?>" disabled>
						</div>
					</div>
				<?php } ?>

				<!-- COAGULATION FACTOR -->
				<?php if($category == 'coagulation_factor'){ ?>
					<div id="coagulation_factor" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper">
						<div class="col-md-2 select-input-wrapper">
							<label>FIBRINOGEN</label>
							<input type="text" name="fibrinogen" value="<?php echo $data_labtest['fibrinogen']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>UNIT</label>
							<input type="text" name="fibrinogen_unit" value="<?php echo $data_labtest['fibrinogen_unit']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>REFERENCE VALUE</label>
							<input type="text" name="fibrinogen_rf" value="<?php echo $data_labtest['fibrinogen_unit']; ?>" disabled>
						</div>
						<div class="clear"></div>
						<div class="col-md-2 select-input-wrapper">
							<label>BLEEDING TIME</label>
							<input type="text" name="bleeding_time" value="<?php echo $data_labtest['bleeding_time']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>REFERENCE VALUE</label>
							<input type="text" name="bleeding_time_rf" value="<?php echo $data_labtest['bleeding_time_rf']; ?>" disabled>
						</div>
						<div class="clear"></div>
						<div class="col-md-2 select-input-wrapper">
							<label>CLOTTING TIME</label>
							<input type="text" name="clotting_time" value="<?php echo $data_labtest['clotting_time']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>REFERENCE VALUE</label>
							<input type="text" name="clotting_time_rf" value="<?php echo $data_labtest['clotting_time_rf']; ?>" disabled>
						</div>
					</div>
				<?php } ?>

				<!-- COAGULATION -->
				<?php if($category == 'coagulation'){ ?>
					<div id="coagulation" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper two-col-wrapper">
						<div class="col-md-6 border-right">
							<div class="col-md-4 select-input-wrapper">
								<label>PROTHROMBIN TIME (PT)</label>
								<input type="text" name="prothrombin_time" value="<?php echo $data_labtest['prothrombin_time']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="prothrombin_time_unit" value="<?php echo $data_labtest['prothrombin_time_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="prothrombin_time_rf" value="<?php echo $data_labtest['prothrombin_time_rf']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>CONTROL</label>
								<input type="text" name="control" value="<?php echo $data_labtest['control']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="control_unit" value="<?php echo $data_labtest['control_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="control_rf" value="<?php echo $data_labtest['control_rf']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>INR<span style="text-transform:Initial">(Please refer to comments below)</span></label>
								<input type="text" name="inr" value="<?php echo $data_labtest['inr']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="inr_unit" value="<?php echo $data_labtest['inr_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="inr_rf" value="<?php echo $data_labtest['inr_rf']; ?>" disabled>
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
								<input type="text" name="percentage_activity" value="<?php echo $data_labtest['percentage_activity']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="percentage_activity_unit" value="<?php echo $data_labtest['percentage_activity_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="percentage_activity_rf" value="<?php echo $data_labtest['percentage_activity_rf']; ?>" disabled>
							</div>
						</div>
						<div class="col-md-6">
							<div class="col-md-4 select-input-wrapper">
								<label>ACTIVATED PARTIAL</label>
								<input type="text" name="activated_partial" value="<?php echo $data_labtest['activated_partial']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="activated_partial_unit" value="<?php echo $data_labtest['activated_partial_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="activated_partial_rf" value="<?php echo $data_labtest['activated_partial_rf']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>THROMBOPLASTIN TIME (APTT)</label>
								<input type="text" name="thromboplastin_time" value="<?php echo $data_labtest['thromboplastin_time']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="thromboplastin_time_unit" value="<?php echo $data_labtest['thromboplastin_time_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="thromboplastin_time_rf" value="<?php echo $data_labtest['thromboplastin_time_rf']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>CONTROL</label>
								<input type="text" name="aptt_control" value="<?php echo $data_labtest['aptt_control']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="aptt_control_unit" value="<?php echo $data_labtest['aptt_control_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="aptt_control_rf" value="<?php echo $data_labtest['aptt_control_rf']; ?>" disabled>
							</div>
						</div>
					</div>
				<?php } ?>

				<!-- HEMATOLOGY -->
				<?php if($category == 'hematology'){ ?>
					<div id="hematology" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper">
						<div class="col-md-6 border-right padding-bottom">
							<div class="col-md-4 select-input-wrapper">
								<label>BLOOD TYPING W/ RH</label>
								<input type="text" name="blood_typing_with_rh" value="<?php echo $data_labtest['blood_typing_with_rh']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>BLOOD TYPE</label>
								<input type="text" name="blood_type" value="<?php echo $data_labtest['blood_type']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>RH TYPING</label>
								<input type="text" name="rh_typing" value="<?php echo $data_labtest['rh_typing']; ?>" disabled>
							</div>
						</div>
						<div class="col-md-6 padding-bottom">
							<div class="col-md-4 select-input-wrapper">
								<label>RETICULOCYTE COUNT</label>
								<input type="text" name="reticulocyte_count" value="<?php echo $data_labtest['reticulocyte_count']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="reticulocyte_count_unit" value="<?php echo $data_labtest['reticulocyte_count_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="reticulocyte_count_rf" value="<?php echo $data_labtest['reticulocyte_count_rf']; ?>" disabled>
							</div>
						</div>
						<div class="col-md-12 no-padding border-top">
							<div class="col-md-4 border-right">
								<div class="col-md-4 select-input-wrapper">
									<label>RED BLOOD CELL</label>
									<input type="text" name="rbc" value="<?php echo $data_labtest['rbc']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="rbc_unit" value="<?php echo $data_labtest['rbc_unit']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="rbc_rf" value="<?php echo $data_labtest['rbc_rf']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>HEMOGLOBIN</label>
									<input type="text" name="hemoglobin" value="<?php echo $data_labtest['hemoglobin']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="hemoglobin_unit" value="<?php echo $data_labtest['hemoglobin_unit']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="hemoglobin_rf" value="<?php echo $data_labtest['hemoglobin_rf']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>HEMATOCRIT</label>
									<input type="text" name="hematocrit" value="<?php echo $data_labtest['hematocrit']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="hematocrit_unit" value="<?php echo $data_labtest['hematocrit_unit']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="hematocrit_rf" value="<?php echo $data_labtest['hematocrit_rf']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>MCV</label>
									<input type="text" name="mcv" value="<?php echo $data_labtest['mcv']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="mcv_unit" value="<?php echo $data_labtest['mcv_unit']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="mcv_rf" value="<?php echo $data_labtest['mcv_rf']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>MCH</label>
									<input type="text" name="mch" value="<?php echo $data_labtest['mch']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="mch_unit" value="<?php echo $data_labtest['mch_unit']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="mch_rf" value="<?php echo $data_labtest['mch_rf']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>MCHC</label>
									<input type="text" name="mchc" value="<?php echo $data_labtest['mchc']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="mchc_unit" value="<?php echo $data_labtest['mchc_unit']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="mchc_rf" value="<?php echo $data_labtest['mchc_rf']; ?>" disabled>
								</div>
							</div>
							<div class="col-md-4 border-right">
								<div class="col-md-4 select-input-wrapper">
									<label>WHITE BLOOD CELL</label>
									<input type="text" name="wbc" value="<?php echo $data_labtest['wbc']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="wbc_unit" value="<?php echo $data_labtest['wbc_unit']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="wbc_rf" value="<?php echo $data_labtest['wbc_rf']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>GRANULOCYTES </label>
									<input type="text" name="granulocytes" value="<?php echo $data_labtest['granulocytes']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="granulocytes_unit" value="<?php echo $data_labtest['granulocytes_unit']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="granulocytes_rf" value="<?php echo $data_labtest['granulocytes_rf']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>LYMPHOCYTES</label>
									<input type="text" name="lymphocytes" value="<?php echo $data_labtest['lymphocytes']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="lymphocytes_unit" value="<?php echo $data_labtest['lymphocytes_unit']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="lymphocytes_rf" value="<?php echo $data_labtest['lymphocytes_rf']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>MONOCYTES</label>
									<input type="text" name="monocytes" value="<?php echo $data_labtest['monocytes']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="monocytes_unit" value="<?php echo $data_labtest['monocytes_unit']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="monocytes_rf" value="<?php echo $data_labtest['monocytes_rf']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>EOSINOPHIL</label>
									<input type="text" name="eosinophil" value="<?php echo $data_labtest['eosinophil']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="eosinophil_unit" value="<?php echo $data_labtest['eosinophil_unit']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="eosinophil_rf" value="<?php echo $data_labtest['eosinophil_rf']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>BASOPHILS</label>
									<input type="text" name="basophils" value="<?php echo $data_labtest['basophils']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="basophils_unit" value="<?php echo $data_labtest['basophils_unit']; ?>" disabled>
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="basophils_rf" value="<?php echo $data_labtest['basophils_rf']; ?>" disabled>
								</div>
							</div>
							<div class="col-md-4">
								<div class="col-md-6 select-input-wrapper">
									<label>PLATELET COUNT</label>
									<input type="text" name="platelet_count" value="<?php echo $data_labtest['platelet_count']; ?>" disabled>
								</div>
								<div class="col-md-6 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="platelet_count_unit" value="<?php echo $data_labtest['platelet_count_unit']; ?>" disabled>
								</div>
								<div class="col-md-6 select-input-wrapper">
									<label>ESR ( < 50 Y/O )</label>
									<input type="text" name="esr_lessthan_50" value="<?php echo $data_labtest['esr_lessthan_50']; ?>" disabled>
								</div>
								<div class="col-md-6 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="esr_lessthan_50_unit" value="<?php echo $data_labtest['esr_lessthan_50_unit']; ?>" disabled>
								</div>
								<div class="col-md-6 select-input-wrapper">
									<label>ESR ( > 50 Y/O )</label>
									<input type="text" name="esr_greaterthan_50" value="<?php echo $data_labtest['esr_greaterthan_50']; ?>" disabled>
								</div>
								<div class="col-md-6 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="esr_greaterthan_50_unit" value="<?php echo $data_labtest['esr_greaterthan_50_unit']; ?>" disabled>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				

				<!-- BIOCHEMISTRY -->
				<?php if($category == 'biochemistry'){ ?>
					<div id="biochemistry" class="col-md-12 bio-input-hldr no-padding category-input-wrapper">
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>GLUCOSE (FASTING)</label>
								<input type="text" name="bio_fasting_si" value="<?php echo $data_labtest['bio_fasting_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="bio_fasting_si_unit" value="<?php echo $data_labtest['bio_fasting_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="bio_fasting_si_rf" value="<?php echo $data_labtest['bio_fasting_si_rf']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>GLUCOSE (FASTING)</label>
								<input type="text" name="bio_fasting_cu" value="<?php echo $data_labtest['bio_fasting_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="bio_fasting_cu_unit" value="<?php echo $data_labtest['bio_fasting_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="bio_fasting_cu_rf" value="<?php echo $data_labtest['bio_fasting_cu_rf']; ?>" disabled>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>GLUCOSE (RANDOM)</label>
								<input type="text" name="bio_random_si" value="<?php echo $data_labtest['bio_random_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="bio_random_si_unit" value="<?php echo $data_labtest['bio_random_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="bio_random_si_rf" value="<?php echo $data_labtest['bio_random_si_rf']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>GLUCOSE (RANDOM)</label>
								<input type="text" name="bio_random_cu" value="<?php echo $data_labtest['bio_random_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="bio_random_cu_unit" value="<?php echo $data_labtest['bio_random_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="bio_random_cu_rf" value="<?php echo $data_labtest['bio_random_cu_rf']; ?>" disabled>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper">
								<label>BLOOD UREA NITROGEN</label>
								<input type="text" name="blood_urea_nitrogen_si" value="<?php echo $data_labtest['blood_urea_nitrogen_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="blood_urea_nitrogen_si_unit" value="<?php echo $data_labtest['blood_urea_nitrogen_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="blood_urea_nitrogen_si_rf" value="<?php echo $data_labtest['blood_urea_nitrogen_si_rf']; ?>" disabled>
								<ul class="sub-note ">
									<li>Male < 50yrs : 3.2 - 7.3 mmol/L</li>
									<li>Male > 50yrs : 3.0 - 9.2 mmol/L</li>
								</ul>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>BLOOD UREA NITROGEN</label>
								<input type="text" name="blood_urea_nitrogen_cu" value="<?php echo $data_labtest['blood_urea_nitrogen_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="blood_urea_nitrogen_cu_unit" value="<?php echo $data_labtest['blood_urea_nitrogen_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="blood_urea_nitrogen_cu_rf" value="<?php echo $data_labtest['blood_urea_nitrogen_cu_rf']; ?>" disabled>
								<ul class="sub-note ">
									<li>Male < 50yrs : 9.0 - 20.5 mg/dL</li>
									<li>Male > 50yrs : 8.4 - 25.8 mg/dL</li>
								</ul>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>CREATININE</label>
								<input type="text" name="creatinine_si" value="<?php echo $data_labtest['creatinine_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="creatinine_si_unit" value="<?php echo $data_labtest['creatinine_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="creatinine_si_rf" value="<?php echo $data_labtest['creatinine_si_rf']; ?>" disabled>
								<ul class="sub-note">
									<li>Male : 50  - 106 µmol/L</li>
								</ul>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>CREATININE</label>
								<input type="text" name="creatinine_cu" value="<?php echo $data_labtest['creatinine_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="creatinine_cu_unit" value="<?php echo $data_labtest['creatinine_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="creatinine_cu_rf" value="<?php echo $data_labtest['creatinine_si_rf']; ?>" disabled>
								<ul class="sub-note">
									<li>Male : 0.6 - 1.20 mg/dL</li>
								</ul>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>SGOT</label>
								<input type="text" name="sgot_si" value="<?php echo $data_labtest['sgot_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="sgot_si_unit" value="<?php echo $data_labtest['sgot_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="sgot_si_rf" value="<?php echo $data_labtest['sgot_si_rf']; ?>" disabled>
								<ul class="sub-note">
									<li>Male : 0 - 35 U/L</li>
								</ul>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>SGOT</label>
								<input type="text" name="sgot_cu" value="<?php echo $data_labtest['sgot_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="sgot_cu_unit" value="<?php echo $data_labtest['sgot_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="sgot_cu_rf" value="<?php echo $data_labtest['sgot_cu_rf']; ?>" disabled>
								<ul class="sub-note">
									<li>Male : 0 - 35 U/L</li>
								</ul>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>SGPT</label>
								<input type="text" name="sgpt_si" value="<?php echo $data_labtest['sgpt_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="sgpt_si_unit" value="<?php echo $data_labtest['sgpt_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="sgpt_si_rf" value="<?php echo $data_labtest['sgpt_si_rf']; ?>" disabled>
								<ul class="sub-note">
									<li>Male : 0 - 45 U/L</li>
								</ul>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>SGPT</label>
								<input type="text" name="sgpt_cu" value="<?php echo $data_labtest['sgpt_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="sgpt_cu_unit" value="<?php echo $data_labtest['sgpt_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="sgpt_cu_rf" value="<?php echo $data_labtest['sgpt_cu_rf']; ?>" disabled>
								<ul class="sub-note">
									<li>Male : 0 - 45 U/L</li>
								</ul>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>ALK. PHOSPHATASE</label>
								<input type="text" name="alk_phosphatase_si" value="<?php echo $data_labtest['alk_phosphatase_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="alk_phosphatase_si_unit" value="<?php echo $data_labtest['alk_phosphatase_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="alk_phosphatase_si_rf" value="<?php echo $data_labtest['alk_phosphatase_si_rf']; ?>" disabled>
								<ul class="sub-note">
									<li>Male : 35 - 104 U/L</li>
								</ul>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>ALK. PHOSPHATASE</label>
								<input type="text" name="alk_phosphatase_cu" value="<?php echo $data_labtest['alk_phosphatase_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="alk_phosphatase_cu_unit" value="<?php echo $data_labtest['alk_phosphatase_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="alk_phosphatase_cu_rf" value="<?php echo $data_labtest['alk_phosphatase_cu_rf']; ?>" disabled>
								<ul class="sub-note">
									<li>Male : 35-104 U/L</li>
								</ul>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>GGT (Enzymatic)</label>
								<input type="text" name="ggt_si" value="<?php echo $data_labtest['ggt_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="ggt_si_unit" value="<?php echo $data_labtest['ggt_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="ggt_si_rf" value="<?php echo $data_labtest['ggt_si_rf']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>GGT (Enzymatic)</label>
								<input type="text" name="ggt_cu" value="<?php echo $data_labtest['ggt_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="ggt_cu_unit" value="<?php echo $data_labtest['ggt_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="ggt_cu_rf" value="<?php echo $data_labtest['ggt_cu_rf']; ?>" disabled>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>TOTAL BILIRUBIN</label>
								<input type="text" name="total_bilirubin_si" value="<?php echo $data_labtest['total_bilirubin_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="total_bilirubin_si_unit" value="<?php echo $data_labtest['total_bilirubin_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="total_bilirubin_si_rf" value="<?php echo $data_labtest['total_bilirubin_si_rf']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>TOTAL BILIRUBIN</label>
								<input type="text" name="total_bilirubin_cu" value="<?php echo $data_labtest['total_bilirubin_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="total_bilirubin_cu_unit" value="<?php echo $data_labtest['total_bilirubin_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="total_bilirubin_cu_rf" value="<?php echo $data_labtest['total_bilirubin_cu_rf']; ?>" disabled>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>DIRECT BILIRUBIN</label>
								<input type="text" name="direct_bilirubin_si" value="<?php echo $data_labtest['direct_bilirubin_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="direct_bilirubin_si_unit" value="<?php echo $data_labtest['direct_bilirubin_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="direct_bilirubin_si_rf" value="<?php echo $data_labtest['direct_bilirubin_si_rf']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>DIRECT BILIRUBIN</label>
								<input type="text" name="direct_bilirubin_cu" value="<?php echo $data_labtest['direct_bilirubin_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="direct_bilirubin_cu_unit" value="<?php echo $data_labtest['direct_bilirubin_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="direct_bilirubin_cu_rf" value="<?php echo $data_labtest['direct_bilirubin_cu_rf']; ?>" disabled>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>INDIRECT BILIRUBIN</label>
								<input type="text" name="indirect_bilirubin_si" value="<?php echo $data_labtest['indirect_bilirubin_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="indirect_bilirubin_si_unit" value="<?php echo $data_labtest['indirect_bilirubin_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="indirect_bilirubin_si_rf" value="<?php echo $data_labtest['indirect_bilirubin_si_rf']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>INDIRECT BILIRUBIN</label>
								<input type="text" name="indirect_bilirubin_cu" value="<?php echo $data_labtest['indirect_bilirubin_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="indirect_bilirubin_cu_unit" value="<?php echo $data_labtest['indirect_bilirubin_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="indirect_bilirubin_cu_rf" value="<?php echo $data_labtest['indirect_bilirubin_cu_rf']; ?>" disabled>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>TOTAL PROTEIN </label>
								<input type="text" name="total_protein_si" value="<?php echo $data_labtest['total_protein_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="total_protein_si_unit" value="<?php echo $data_labtest['total_protein_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="total_protein_si_rf" value="<?php echo $data_labtest['total_protein_si_rf']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>TOTAL PROTEIN </label>
								<input type="text" name="total_protein_cu" value="<?php echo $data_labtest['total_protein_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="total_protein_cu_unit" value="<?php echo $data_labtest['total_protein_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="total_protein_cu_rf" value="<?php echo $data_labtest['total_protein_cu_rf']; ?>" disabled>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>ALBUMIN</label>
								<input type="text" name="albumin_si" value="<?php echo $data_labtest['albumin_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="albumin_si_unit" value="<?php echo $data_labtest['albumin_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="albumin_si_rf" value="<?php echo $data_labtest['albumin_si_rf']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>ALBUMIN</label>
								<input type="text" name="albumin_cu" value="<?php echo $data_labtest['albumin_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="albumin_cu_unit" value="<?php echo $data_labtest['albumin_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="albumin_cu_rf" value="<?php echo $data_labtest['albumin_cu_rf']; ?>" disabled>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>GLOBULIN</label>
								<input type="text" name="globulin_si" value="<?php echo $data_labtest['globulin_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="globulin_si_unit" value="<?php echo $data_labtest['globulin_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="globulin_si_rf" value="<?php echo $data_labtest['globulin_si_rf']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>GLOBULIN</label>
								<input type="text" name="globulin_cu" value="<?php echo $data_labtest['globulin_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="globulin_cu_unit" value="<?php echo $data_labtest['globulin_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="globulin_cu_rf" value="<?php echo $data_labtest['globulin_cu_rf']; ?>" disabled>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>A/G RATIO</label>
								<input type="text" name="ag_ratio_si" value="<?php echo $data_labtest['ag_ratio_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="ag_ratio_si_unit" value="<?php echo $data_labtest['ag_ratio_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="ag_ratio_si_rf" value="<?php echo $data_labtest['ag_ratio_si_rf']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>A/G RATIO</label>
								<input type="text" name="ag_ratio_cu" value="<?php echo $data_labtest['ag_ratio_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="ag_ratio_cu_unit" value="<?php echo $data_labtest['ag_ratio_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="ag_ratio_cu_rf" value="<?php echo $data_labtest['ag_ratio_cu_rf']; ?>" disabled>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>LACTOSE DEHYDROGENASE</label>
								<input type="text" name="lactose_dehydrogenase_si" value="<?php echo $data_labtest['lactose_dehydrogenase_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="lactose_dehydrogenase_si_unit" value="<?php echo $data_labtest['lactose_dehydrogenase_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="lactose_dehydrogenase_si_rf" value="<?php echo $data_labtest['lactose_dehydrogenase_si_rf']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>LACTOSE DEHYDROGENASE</label>
								<input type="text" name="lactose_dehydrogenase_cu" value="<?php echo $data_labtest['lactose_dehydrogenase_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="lactose_dehydrogenase_cu_unit" value="<?php echo $data_labtest['lactose_dehydrogenase_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="lactose_dehydrogenase_cu_rf" value="<?php echo $data_labtest['lactose_dehydrogenase_cu_rf']; ?>" disabled>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>INORGANIC PHOSPHATE</label>
								<input type="text" name="inorganic_phosphate_si" value="<?php echo $data_labtest['inorganic_phosphate_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="inorganic_phosphate_si_unit" value="<?php echo $data_labtest['inorganic_phosphate_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="inorganic_phosphate_si_rf" value="<?php echo $data_labtest['inorganic_phosphate_si_rf']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>INORGANIC PHOSPHATE</label>
								<input type="text" name="inorganic_phosphate_cu" value="<?php echo $data_labtest['inorganic_phosphate_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="inorganic_phosphate_cu_unit" value="<?php echo $data_labtest['inorganic_phosphate_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="inorganic_phosphate_cu_rf" value="<?php echo $data_labtest['inorganic_phosphate_cu_rf']; ?>" disabled>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>BICARBONATE  (ENZYMATIC)</label>
								<input type="text" name="bicarbonate_si" value="<?php echo $data_labtest['bicarbonate_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="bicarbonate_si_unit" value="<?php echo $data_labtest['bicarbonate_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="bicarbonate_si_rf" value="<?php echo $data_labtest['bicarbonate_si_rf']; ?>" disabled>
								<ul class="sub-note">
									<li>Male : 23 - 29 mmol/L</li>
								</ul>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>BICARBONATE  (ENZYMATIC)</label>
								<input type="text" name="bicarbonate_cu" value="<?php echo $data_labtest['bicarbonate_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="bicarbonate_cu_unit" value="<?php echo $data_labtest['bicarbonate_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="bicarbonate_cu_rf" value="<?php echo $data_labtest['bicarbonate_cu_rf']; ?>" disabled>
								<ul class="sub-note">
									<li>Male : 23 - 29 meq/L</li>
								</ul>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>AMYLASE</label>
								<input type="text" name="amylase_si" value="<?php echo $data_labtest['amylase_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="amylase_si_unit" value="<?php echo $data_labtest['amylase_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="amylase_si_rf" value="<?php echo $data_labtest['amylase_si_rf']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>AMYLASE</label>
								<input type="text" name="amylase_cu" value="<?php echo $data_labtest['amylase_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="amylase_cu_unit" value="<?php echo $data_labtest['amylase_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="amylase_cu_rf" value="<?php echo $data_labtest['amylase_cu_rf']; ?>" disabled>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>LIPASE</label>
								<input type="text" name="lipase_si" value="<?php echo $data_labtest['lipase_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="lipase_si_unit" value="<?php echo $data_labtest['lipase_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="lipase_si_rf" value="<?php echo $data_labtest['lipase_si_rf']; ?>" disabled>
								<ul class="sub-note">
									<li>Newborn : < 60 U/L</li>
									<li>Adult : 10 - 140 U/L</li>
									<li>Elderly : (> 60 y/o): 18 -180 U/L </li>
								</ul>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>LIPASE</label>
								<input type="text" name="lipase_cu" value="<?php echo $data_labtest['lipase_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="lipase_cu_unit" value="<?php echo $data_labtest['lipase_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="lipase_cu_rf" value="<?php echo $data_labtest['lipase_cu_rf']; ?>" disabled>
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
								<input type="text" name="ck_total_si" value="<?php echo $data_labtest['ck_total_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="ck_total_si_unit" value="<?php echo $data_labtest['ck_total_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="ck_total_si_rf" value="<?php echo $data_labtest['ck_total_si_rf']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>CK TOTAL</label>
								<input type="text" name="ck_total_cu" value="<?php echo $data_labtest['ck_total_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="ck_total_cu_unit" value="<?php echo $data_labtest['ck_total_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="ck_total_cu_rf" value="<?php echo $data_labtest['ck_total_cu_rf']; ?>" disabled>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>CPK MM</label>
								<input type="text" name="cpk_mm_si" value="<?php echo $data_labtest['cpk_mm_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="cpk_mm_si_unit" value="<?php echo $data_labtest['cpk_mm_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="cpk_mm_si_rf" value="<?php echo $data_labtest['cpk_mm_si_rf']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>CPK MM</label>
								<input type="text" name="cpk_mm_cu" value="<?php echo $data_labtest['cpk_mm_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="cpk_mm_cu_unit" value="<?php echo $data_labtest['cpk_mm_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="cpk_mm_cu_rf" value="<?php echo $data_labtest['cpk_mm_cu_rf']; ?>" disabled>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>CK - MB</label>
								<input type="text" name="ck_mb_si" value="<?php echo $data_labtest['ck_mb_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="ck_mb_si_unit" value="<?php echo $data_labtest['ck_mb_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="ck_mb_si_rf" value="<?php echo $data_labtest['ck_mb_si_rf']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>CK - MB</label>
								<input type="text" name="ck_mb_cu" value="<?php echo $data_labtest['ck_mb_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="ck_mb_cu_unit" value="<?php echo $data_labtest['ck_mb_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="ck_mb_cu_rf" value="<?php echo $data_labtest['ck_mb_cu_rf']; ?>" disabled>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>FRUCTOSAMINE</label>
								<input type="text" name="fructosamine_si" value="<?php echo $data_labtest['fructosamine_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="fructosamine_si_unit" value="<?php echo $data_labtest['fructosamine_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="fructosamine_si_rf" value="<?php echo $data_labtest['fructosamine_si_rf']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>FRUCTOSAMINE</label>
								<input type="text" name="fructosamine_cu" value="<?php echo $data_labtest['fructosamine_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="fructosamine_cu_unit" value="<?php echo $data_labtest['fructosamine_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="fructosamine_cu_rf" value="<?php echo $data_labtest['fructosamine_si_rf']; ?>" disabled>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>HBA1C</label>
								<input type="text" name="hba1c_si" value="<?php echo $data_labtest['hba1c_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="hba1c_si_unit" value="<?php echo $data_labtest['hba1c_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="hba1c_si_rf" value="<?php echo $data_labtest['hba1c_si_rf']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>HBA1C</label>
								<input type="text" name="hba1c_cu" value="<?php echo $data_labtest['hba1c_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="hba1c_cu_unit" value="<?php echo $data_labtest['hba1c_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="hba1c_cu_rf" value="<?php echo $data_labtest['hba1c_cu_rf']; ?>" disabled>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>LIPOPROTEIN (a)</label>
								<input type="text" name="lipoprotein_si" value="<?php echo $data_labtest['lipoprotein_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="lipoprotein_si_unit" value="<?php echo $data_labtest['lipoprotein_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="lipoprotein_si_rf" value="<?php echo $data_labtest['lipoprotein_si_rf']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>LIPOPROTEIN (a)</label>
								<input type="text" name="lipoprotein_cu" value="<?php echo $data_labtest['lipoprotein_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="lipoprotein_cu_unit" value="<?php echo $data_labtest['lipoprotein_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="lipoprotein_cu_rf" value="<?php echo $data_labtest['lipoprotein_cu_rf']; ?>" disabled>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>CHOLESTEROL</label>
								<input type="text" name="cholesterol_si" value="<?php echo $data_labtest['cholesterol_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="cholesterol_si_unit" value="<?php echo $data_labtest['cholesterol_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="cholesterol_si_rf" value="<?php echo $data_labtest['cholesterol_si_rf']; ?>" disabled>
								<ul class="sub-note ">
									<li>Desirable : ≤5.2 mmol/L</li>
									<li>Borderline High Risk : 5.2 - 6.2 mmol/L</li>
									<li>High Risk : >6.2 mmol/L</li>
								</ul>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>CHOLESTEROL</label>
								<input type="text" name="cholesterol_cu" value="<?php echo $data_labtest['cholesterol_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="cholesterol_cu_unit" value="<?php echo $data_labtest['cholesterol_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="cholesterol_cu_rf" value="<?php echo $data_labtest['cholesterol_cu_rf']; ?>" disabled>
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
								<input type="text" name="triglycerides_si" value="<?php echo $data_labtest['triglycerides_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="triglycerides_si_unit" value="<?php echo $data_labtest['triglycerides_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="triglycerides_si_rf" value="<?php echo $data_labtest['triglycerides_si_rf']; ?>" disabled>
								<ul class="sub-note">
									<li>Normal : < 1.695 mmol/L</li>
									<li>Low Risk : 1.695 - 2.26 mmol/L</li>
									<li>High Risk : 2.26 - 5.65 mmol/L</li>
									<li>Extremely High : >5.65 mmol/L</li>
								</ul>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>TRIGLYCERIDES</label>
								<input type="text" name="triglycerides_cu" value="<?php echo $data_labtest['triglycerides_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="triglycerides_cu_unit" value="<?php echo $data_labtest['triglycerides_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="triglycerides_cu_rf" value="<?php echo $data_labtest['triglycerides_cu_rf']; ?>" disabled>
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
								<input type="text" name="hdl_si" value="<?php echo $data_labtest['hdl_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="hdl_si_unit" value="<?php echo $data_labtest['hdl_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="hdl_si_rf" value="<?php echo $data_labtest['hdl_si_rf']; ?>" disabled>
								<ul class="sub-note">
									<li>Male : 0.77 - 1.81 mmol/L</li>
								</ul>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>HDL</label>
								<input type="text" name="hdl_cu" value="<?php echo $data_labtest['hdl_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="hdl_cu_unit" value="<?php echo $data_labtest['hdl_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="hdl_cu_rf" value="<?php echo $data_labtest['hdl_cu_rf']; ?>" disabled>
								<ul class="sub-note">
									<li>Male : 30 - 70 mg/dL</li>
								</ul>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>LDL</label>
								<input type="text" name="ldl_si" value="<?php echo $data_labtest['ldl_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="ldl_si_unit" value="<?php echo $data_labtest['ldl_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="ldl_si_rf" value="<?php echo $data_labtest['ldl_si_rf']; ?>" disabled>
								<ul class="sub-note ">
									<li>Desirable : < 3.36 mmol/L</li>
									<li>Borderline High Risk : 3.36 - 4.11 mmol/L</li>
									<li>High Risk : >4.14 mmol/L</li>
								</ul>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>LDL</label>
								<input type="text" name="ldl_cu" value="<?php echo $data_labtest['ldl_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="ldl_cu_unit" value="<?php echo $data_labtest['ldl_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="ldl_cu_rf" value="<?php echo $data_labtest['ldl_cu_rf']; ?>" disabled>
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
								<input type="text" name="vldl_si" value="<?php echo $data_labtest['vldl_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="vldl_si_unit" value="<?php echo $data_labtest['vldl_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="vldl_si_rf" value="<?php echo $data_labtest['vldl_si_rf']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>VLDL</label>
								<input type="text" name="vldl_cu" value="<?php echo $data_labtest['vldl_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="vldl_cu_unit" value="<?php echo $data_labtest['vldl_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="vldl_cu_rf" value="<?php echo $data_labtest['vldl_cu_rf']; ?>" disabled>
							</div>
						</div>
						<div class="clear"></div>
						<hr class="custom-hr">
						<div class="col-md-12 no-padding">
							<h6>RATIO</h6>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>Total Chole:HDL</label>
									<input type="text" name="total_chole_hdl_si" value="<?php echo $data_labtest['total_chole_hdl_si']; ?>" disabled>
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="total_chole_hdl_si_unit" value="<?php echo $data_labtest['total_chole_hdl_si_unit']; ?>" disabled>
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="total_chole_hdl_si_rf" value="<?php echo $data_labtest['total_chole_hdl_si_rf']; ?>" disabled>
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Total Chole:HDL</label>
									<input type="text" name="total_chole_hdl_cu" value="<?php echo $data_labtest['total_chole_hdl_cu']; ?>" disabled>
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="total_chole_hdl_cu_unit" value="<?php echo $data_labtest['total_chole_hdl_cu_unit']; ?>" disabled>
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="total_chole_hdl_cu_rf" value="<?php echo $data_labtest['total_chole_hdl_cu_rf']; ?>" disabled>
									<ul class="sub-note">
										<li>Preferably : < 5.0</li>
										<li>Ideal : < 3.5</li>
									</ul>
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>HDL:LDL</label>
									<input type="text" name="hdl_ldl_si" value="<?php echo $data_labtest['hdl_ldl_si']; ?>" disabled>
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="hdl_ldl_si_unit" value="<?php echo $data_labtest['hdl_ldl_si_unit']; ?>" disabled>
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="hdl_ldl_si_rf" value="<?php echo $data_labtest['hdl_ldl_si_rf']; ?>" disabled>
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>HDL:LDL</label>
									<input type="text" name="hdl_ldl_cu" value="<?php echo $data_labtest['hdl_ldl_cu']; ?>" disabled>
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="hdl_ldl_cu_unit" value="<?php echo $data_labtest['hdl_ldl_cu_unit']; ?>" disabled>
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="hdl_ldl_cu_rf" value="<?php echo $data_labtest['hdl_ldl_cu_rf']; ?>" disabled>
									<ul class="sub-note">
										<li>Preferably : >0.3</li>
										<li>Ideal : >0.4</li>
									</ul>
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>Triglycerides:HDL</label>
									<input type="text" name="triglycerides_hdl_si" value="<?php echo $data_labtest['triglycerides_hdl_si']; ?>" disabled>
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="triglycerides_hdl_si_unit" value="<?php echo $data_labtest['triglycerides_hdl_si_unit']; ?>" disabled>
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="triglycerides_hdl_si_rf" value="<?php echo $data_labtest['triglycerides_hdl_si_rf']; ?>" disabled>
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Triglycerides:HDL</label>
									<input type="text" name="triglycerides_hdl_cu" value="<?php echo $data_labtest['triglycerides_hdl_cu']; ?>" disabled>
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="triglycerides_hdl_cu_unit" value="<?php echo $data_labtest['triglycerides_hdl_cu_unit']; ?>" disabled>
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="triglycerides_hdl_cu_rf" value="<?php echo $data_labtest['triglycerides_hdl_cu_rf']; ?>" disabled>
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
								<input type="text" name="sodium_si" value="<?php echo $data_labtest['sodium_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="sodium_si_unit" value="<?php echo $data_labtest['sodium_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="sodium_si_rf" value="<?php echo $data_labtest['sodium_si_rf']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>SODIUM</label>
								<input type="text" name="sodium_cu" value="<?php echo $data_labtest['sodium_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="sodium_cu_unit" value="<?php echo $data_labtest['sodium_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="sodium_cu_rf" value="<?php echo $data_labtest['sodium_cu_rf']; ?>" disabled>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>POTASSIUM</label>
								<input type="text" name="potassium_si" value="<?php echo $data_labtest['potassium_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="potassium_si_unit" value="<?php echo $data_labtest['potassium_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="potassium_si_rf" value="<?php echo $data_labtest['potassium_si_rf']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>POTASSIUM</label>
								<input type="text" name="potassium_cu" value="<?php echo $data_labtest['potassium_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="potassium_cu_unit" value="<?php echo $data_labtest['potassium_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="potassium_cu_rf" value="<?php echo $data_labtest['potassium_cu_rf']; ?>" disabled>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>CALCIUM</label>
								<input type="text" name="calcium_si" value="<?php echo $data_labtest['calcium_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="calcium_si_unit" value="<?php echo $data_labtest['calcium_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="calcium_si_rf" value="<?php echo $data_labtest['calcium_si_rf']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>CALCIUM</label>
								<input type="text" name="calcium_cu" value="<?php echo $data_labtest['calcium_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="calcium_cu_unit" value="<?php echo $data_labtest['calcium_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="calcium_cu_rf" value="<?php echo $data_labtest['calcium_cu_rf']; ?>" disabled>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>CHLORIDE</label>
								<input type="text" name="chloride_si" value="<?php echo $data_labtest['chloride_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="chloride_si_unit" value="<?php echo $data_labtest['chloride_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="chloride_si_rf" value="<?php echo $data_labtest['chloride_si_rf']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>CHLORIDE</label>
								<input type="text" name="chloride_cu" value="<?php echo $data_labtest['chloride_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="chloride_cu_unit" value="<?php echo $data_labtest['chloride_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="chloride_cu_rf" value="<?php echo $data_labtest['chloride_cu_rf']; ?>" disabled>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>MAGNESIUM</label>
								<input type="text" name="magnesium_si" value="<?php echo $data_labtest['magnesium_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="magnesium_si_unit" value="<?php echo $data_labtest['magnesium_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="magnesium_si_rf" value="<?php echo $data_labtest['magnesium_si_rf']; ?>" disabled>
								<ul class="sub-note">
									<li>Male : 0.73 - 1.06 mmol/L</li>
								</ul>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>MAGNESIUM</label>
								<input type="text" name="magnesium_cu" value="<?php echo $data_labtest['magnesium_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="magnesium_cu_unit" value="<?php echo $data_labtest['magnesium_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="magnesium_cu_rf" value="<?php echo $data_labtest['magnesium_cu_rf']; ?>" disabled>
								<ul class="sub-note">
									<li>Male : 1.8 - 2.6 mg/dL</li>
								</ul>
							</div>
						</div>
					</div>
				<?php } ?>

				<!-- ORAL GLUCOSE CHALLENGE -->
				<?php if($category == 'oral_glucose_challenge'){ ?>
					<div id="oral_glucose_challenge" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper">
						<div class="col-md-2 select-input-wrapper">
							<label>GLUCOSE (2nd hour)</label>
							<input type="text" name="glucose_si" value="<?php echo $data_labtest['glucose_si']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>SI UNIT</label>
							<input type="text" name="glucose_si_unit" value="<?php echo $data_labtest['glucose_si_unit']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper border-right">
							<label>REFERENCE VALUE</label>
							<input type="text" name="glucose_si_rf" value="<?php echo $data_labtest['glucose_si_rf']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>GLUCOSE (2nd hour)</label>
							<input type="text" name="glucose_cu" value="<?php echo $data_labtest['glucose_cu']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>COnventional UNIT</label>
							<input type="text" name="glucose_cu_unit" value="<?php echo $data_labtest['glucose_cu_unit']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>REFERENCE VALUE</label>
							<input type="text" name="glucose_cu_rf" value="<?php echo $data_labtest['glucose_cu_rf']; ?>" disabled>
						</div>
						<p><b><i>Note: 75g Glucose load.</i></b></p>
					</div>
				<?php } ?>

				<!-- ORAL GLUCOSE TOLERANCE -->
				<?php if($category == 'oral_glucose_tolerance'){ ?>
					<div id="oral_glucose_tolerance" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper">
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper">
								<label>GLUCOSE (FASTING)</label>
								<input type="text" name="fasting_si" value="<?php echo $data_labtest['fasting_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>SI UNIT</label>
								<input type="text" name="fasting_si_unit" value="<?php echo $data_labtest['fasting_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>REFERENCE VALUE</label>
								<input type="text" name="fasting_si_rf" value="<?php echo $data_labtest['fasting_si_rf']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>GLUCOSE (FASTING)</label>
								<input type="text" name="fasting_cu" value="<?php echo $data_labtest['fasting_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>conventional UNIT</label>
								<input type="text" name="fasting_cu_unit" value="<?php echo $data_labtest['fasting_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="fasting_cu_rf" value="<?php echo $data_labtest['fasting_si_rf']; ?>" disabled>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper">
								<label>GLUCOSE (1ST HOUR)</label>
								<input type="text" name="1st_hour_si" value="<?php echo $data_labtest['1st_hour_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si UNIT</label>
								<input type="text" name="1st_hour_si_unit" value="<?php echo $data_labtest['1st_hour_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>REFERENCE VALUE</label>
								<input type="text" name="1st_hour_si_rf" value="<?php echo $data_labtest['1st_hour_si_rf']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>GLUCOSE (1ST HOUR)</label>
								<input type="text" name="1st_hour_cu" value="<?php echo $data_labtest['1st_hour_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>conventional UNIT</label>
								<input type="text" name="1st_hour_cu_unit" value="<?php echo $data_labtest['1st_hour_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="1st_hour_cu_rf" value="<?php echo $data_labtest['1st_hour_cu_rf']; ?>" disabled>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper">
								<label>GLUCOSE (2ND HOUR)</label>
								<input type="text" name="2nd_hour_si" value="<?php echo $data_labtest['2nd_hour_si']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>SI UNIT</label>
								<input type="text" name="2nd_hour_si_unit" value="<?php echo $data_labtest['2nd_hour_si_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>REFERENCE VALUE</label>
								<input type="text" name="2nd_hour_si_rf" value="<?php echo $data_labtest['2nd_hour_si_rf']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>GLUCOSE (2ND HOUR)</label>
								<input type="text" name="2nd_hour_cu" value="<?php echo $data_labtest['2nd_hour_cu']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>conventional UNIT</label>
								<input type="text" name="2nd_hour_cu_unit" value="<?php echo $data_labtest['2nd_hour_cu_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="2nd_hour_cu_rf" value="<?php echo $data_labtest['2nd_hour_cu_rf']; ?>" disabled>
							</div>
						</div>
					</div>
				<?php } ?>

				<!-- THYROID -->
				<?php if($category == 'thyroid'){ ?>
					<div id="thyroid" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper">
						<div class="col-md-6 border-right">
							<div class="col-md-4 select-input-wrapper">
								<label>FT3 (CLIA)</label>
								<input type="text" name="ft3" value="<?php echo $data_labtest['ft3']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="ft3_unit" value="<?php echo $data_labtest['ft3_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="ft3_rf" value="<?php echo $data_labtest['ft3_rf']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>FT4 (CLIA)</label>
								<input type="text" name="ft4" value="<?php echo $data_labtest['ft4']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="ft4_unit" value="<?php echo $data_labtest['ft4_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="ft4_rf" value="<?php echo $data_labtest['ft4_rf']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>TSH (CLIA)</label>
								<input type="text" name="tsh" value="<?php echo $data_labtest['tsh']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="tsh_unit" value="<?php echo $data_labtest['tsh_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="tsh_rf" value="<?php echo $data_labtest['tsh_rf']; ?>" disabled>
							</div>
							<div class="clear"></div>
						</div>
						<div class="col-md-6">
							<div class="col-md-4 select-input-wrapper">
								<label>T3 REVERSE (CLIA)</label>
								<input type="text" name="t3_reverse" value="<?php echo $data_labtest['t3_reverse']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="t3_reverse_unit" value="<?php echo $data_labtest['t3_reverse_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="t3_reverse_rf" value="<?php echo $data_labtest['t3_reverse_rf']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>THYROGLOBULIN ANTIBODY (CLIA)</label>
								<input type="text" name="thyroglobulin_antibody" value="<?php echo $data_labtest['thyroglobulin_antibody']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="thyroglobulin_antibody_unit" value="<?php echo $data_labtest['thyroglobulin_antibody_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="thyroglobulin_antibody_rf" value="<?php echo $data_labtest['thyroglobulin_antibody_rf']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>THYROID PEROXIDASE ANTIBODY (CLIA)</label>
								<input type="text" name="thyroid_peroxidase_antibody" value="<?php echo $data_labtest['thyroid_peroxidase_antibody']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="thyroid_peroxidase_antibody_unit" value="<?php echo $data_labtest['thyroid_peroxidase_antibody_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="thyroid_peroxidase_antibody_rf" value="<?php echo $data_labtest['thyroid_peroxidase_antibody_rf']; ?>" disabled>
							</div>
							<div class="clear"></div>
						</div>
					</div>
				<?php } ?>

				<!-- HORMONES -->
				<?php if($category == 'hormones'){ ?>
					<div id="hormones" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper">
						<div class="col-md-6 border-right">
							<div class="col-md-4 select-input-wrapper">
								<label>FSH  (CLIA)</label>
								<input type="text" name="fsh" value="<?php echo $data_labtest['fsh']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>unit</label>
								<input type="text" name="fsh_unit" value="<?php echo $data_labtest['fsh_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="fsh_rf" value="<?php echo $data_labtest['fsh_rf']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>LH   (CLIA)</label>
								<input type="text" name="lh" value="<?php echo $data_labtest['lh']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>unit</label>
								<input type="text" name="lh_unit" value="<?php echo $data_labtest['lh_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="lh_rf" value="<?php echo $data_labtest['lh_rf']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>PROGESTERONE   (CLIA)</label>
								<input type="text" name="progesterone" value="<?php echo $data_labtest['progesterone']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>unit</label>
								<input type="text" name="progesterone_unit" value="<?php echo $data_labtest['progesterone_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="progesterone_rf" value="<?php echo $data_labtest['progesterone_rf']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>ESTRADIOL  (CLIA)</label>
								<input type="text" name="estradiol" value="<?php echo $data_labtest['estradiol']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>unit</label>
								<input type="text" name="estradiol_unit" value="<?php echo $data_labtest['estradiol_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="estradiol_rf" value="<?php echo $data_labtest['estradiol_rf']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>TESTOSTERONE  (CLIA)</label>
								<input type="text" name="testosterone" value="<?php echo $data_labtest['testosterone']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>unit</label>
								<input type="text" name="testosterone_unit" value="<?php echo $data_labtest['testosterone_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="testosterone_rf" value="<?php echo $data_labtest['testosterone_rf']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>FREE TESTOSTERONE (CLIA)</label>
								<input type="text" name="free_testosterone" value="<?php echo $data_labtest['free_testosterone']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>unit</label>
								<input type="text" name="free_testosterone_unit" value="<?php echo $data_labtest['free_testosterone_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="free_testosterone_rf" value="<?php echo $data_labtest['free_testosterone_rf']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>Sex Hormone Binding Globulin (SHBG) (CLIA)</label>
								<input type="text" name="shbg" value="<?php echo $data_labtest['shbg']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>unit</label>
								<input type="text" name="shbg_unit" value="<?php echo $data_labtest['shbg_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="shbg_rf" value="<?php echo $data_labtest['shbg_rf']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>CORTISOL   (CLIA)</label>
								<input type="text" name="cortisol" value="<?php echo $data_labtest['cortisol']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>unit</label>
								<input type="text" name="cortisol_unit" value="<?php echo $data_labtest['cortisol_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="cortisol_rf" value="<?php echo $data_labtest['cortisol_rf']; ?>" disabled>
								<ul class="sub-note">
									<li>8-10 AM SPECIMEN : 5 - 23</li>
									<li>4-7 PM SPECIMEN : 2.5 - 17.2</li>
								</ul>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>ALDOSTERONE  (CLIA)</label>
								<input type="text" name="aldosterone" value="<?php echo $data_labtest['aldosterone']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>unit</label>
								<input type="text" name="aldosterone_unit" value="<?php echo $data_labtest['aldosterone_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="aldosterone_rf" value="<?php echo $data_labtest['aldosterone_rf']; ?>" disabled>
								<ul class="sub-note">
									<li>UPRIGHT : 4.0 - 31.0</li>
									<li>RECUMBENT : 1.0 - 16.0</li>
								</ul>
							</div>
						</div>
						<div class="col-md-6">
							<div class="col-md-4 select-input-wrapper">
								<label>DIHYDROTESTOSTERONE (DHT) - RIA</label>
								<input type="text" name="dht" value="<?php echo $data_labtest['dht']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>unit</label>
								<input type="text" name="dht_unit" value="<?php echo $data_labtest['dht_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="dht_rf" value="<?php echo $data_labtest['dht_rf']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>SEROTONIN (ELISA)</label>
								<input type="text" name="serotonin" value="<?php echo $data_labtest['serotonin']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>unit</label>
								<input type="text" name="serotonin_unit" value="<?php echo $data_labtest['serotonin_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="serotonin_rf" value="<?php echo $data_labtest['serotonin_rf']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>PREGNENOLONE (ELISA)</label>
								<input type="text" name="pregnenolone" value="<?php echo $data_labtest['pregnenolone']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>unit</label>
								<input type="text" name="pregnenolone_unit" value="<?php echo $data_labtest['pregnenolone_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="pregnenolone_rf" value="<?php echo $data_labtest['pregnenolone_rf']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>C-PEPTIDE (CLIA)</label>
								<input type="text" name="c_peptide" value="<?php echo $data_labtest['c_peptide']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>unit</label>
								<input type="text" name="c_peptide_unit" value="<?php echo $data_labtest['c_peptide_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="c_peptide_rf" value="<?php echo $data_labtest['c_peptide_rf']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>INSULIN ASSAY (CLIA) (FASTING)</label>
								<input type="text" name="insulin_assay_fasting" value="<?php echo $data_labtest['insulin_assay_fasting']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>unit</label>
								<input type="text" name="insulin_assay_fasting_unit" value="<?php echo $data_labtest['insulin_assay_fasting_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="insulin_assay_fasting_rf" value="<?php echo $data_labtest['insulin_assay_fasting_rf']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>INSULIN ASSAY (CLIA) (POST PRANDIAL)</label>
								<input type="text" name="post_prandial" value="<?php echo $data_labtest['post_prandial']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>unit</label>
								<input type="text" name="post_prandial_unit" value="<?php echo $data_labtest['post_prandial_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="post_prandial_rf" value="<?php echo $data_labtest['post_prandial_rf']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>Dehydroepiandrosterone Sulfate (DHEA-SO4) - (CLIA)</label>
								<input type="text" name="dhea_so4" value="<?php echo $data_labtest['dhea_so4']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>unit</label>
								<input type="text" name="dhea_so4_unit" value="<?php echo $data_labtest['dhea_so4_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="dhea_so4_rf" value="<?php echo $data_labtest['dhea_so4_rf']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>INSULIN GROWTH FACTOR-1 (IGF-1) - (CLIA)</label>
								<input type="text" name="igf_1" value="<?php echo $data_labtest['igf_1']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>unit</label>
								<input type="text" name="igf_1_unit" value="<?php echo $data_labtest['igf_1_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="igf_1_rf" value="<?php echo $data_labtest['igf_1_rf']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>INSULIN GROWTH FACTOR-BP3 (IGF-BP3) - (CLIA)</label>
								<input type="text" name="igf_bp3" value="<?php echo $data_labtest['igf_bp3']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>unit</label>
								<input type="text" name="igf_bp3_unit" value="<?php echo $data_labtest['igf_bp3_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="igf_bp3_rf" value="<?php echo $data_labtest['igf_bp3_rf']; ?>" disabled>
							</div>
						</div>
					</div>
				<?php } ?>

				<!-- homeostasis_ass_index -->
				<?php if($category == 'homeostasis_ass_index'){ ?>
					<div id="homeostasis_ass_index" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper">
						<div class="col-md-4 border-right">
							<div class="col-md-12 select-input-wrapper">
							<label>Beta Cell Function (% β :)</label>
							<input type="text" name="beta_cell_function" value="<?php echo $data_labtest['beta_cell_function']; ?>" disabled>
							</div>
						</div>
						<div class="col-md-4 border-right">
							<div class="col-md-12 select-input-wrapper">
								<label>Insulin Sensitivity (% S :)</label>
								<input type="text" name="insulin_sensitivity" value="<?php echo $data_labtest['insulin_sensitivity']; ?>" disabled>
							</div>
						</div>
						<div class="col-md-4">
							<div class="col-md-12 select-input-wrapper">
								<label>Insulin Resistance (IR :)</label>
								<input type="text" name="insulin_resistance" value="<?php echo $data_labtest['insulin_resistance']; ?>" disabled>
							</div>
						</div>
						<div class="clear"></div>
						<div class="col-md-12 note-test">
							<p>
								<b><i>Note</i></b>: The measures correspond well, but are not necessarily equivalent, to non-steady state estimates of beta cell function and insulin sensitivity derived from stimulatory models such as the hyperinsulinaemic clamp the hyperglycaemic clamp, the intravenous glucose tolerance test ( acute insulin response, minimal model) &   the oral glucose tolerance test (0-30 delta I/G).
							</p>
						</div>
					</div>
				<?php } ?>

				<!-- serology -->
				<?php if($category == 'serology'){ ?>
					<div id="serology" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper">
						<div class="col-md-6 border-right">
							<div class="col-md-4 select-input-wrapper">
								<label>RHEUMATOID FACTOR </label>
								<input type="text" name="rheumatoid_factor" value="<?php echo $data_labtest['rheumatoid_factor']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="rheumatoid_factor_unit" value="<?php echo $data_labtest['rheumatoid_factor_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="rheumatoid_factor_rf" value="<?php echo $data_labtest['rheumatoid_factor_rf']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>C-REACTIVE PROTEIN (HCRP)</label>
								<input type="text" name="c-reactive_protein" value="<?php echo $data_labtest['c_reactive_protein']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="c-reactive_protein_unit" value="<?php echo $data_labtest['c_reactive_protein_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="c-reactive_protein_rf" value="<?php echo $data_labtest['c_reactive_protein_rf']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>FERRITIN</label>
								<input type="text" name="ferritin" value="<?php echo $data_labtest['ferritin']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="ferritin_unit" value="<?php echo $data_labtest['ferritin_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="ferritin_rf" value="<?php echo $data_labtest['ferritin_rf']; ?>" disabled>
							</div>
						</div>
						<div class="col-md-6">
							<div class="col-md-4 select-input-wrapper">
								<label>ERYTHROPOIETIN</label>
								<input type="text" name="erythropoietin" value="<?php echo $data_labtest['erythropoietin']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</span></label>
								<input type="text" name="erythropoietin_unit" value="<?php echo $data_labtest['erythropoietin_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="erythropoietin_rf" value="<?php echo $data_labtest['erythropoietin_rf']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>Serum Immunoglobulin E <span style="text-transform:initial">(IgE)</span></label>
								<input type="text" name="serum_immunoglobulin" value="<?php echo $data_labtest['serum_immunoglobulin']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="serum_immunoglobulin_unit" value="<?php echo $data_labtest['serum_immunoglobulin_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="serum_immunoglobulin_rf" value="<?php echo $data_labtest['serum_immunoglobulin_rf']; ?>" disabled>
							</div>
						</div>
						<div class="clear"></div>
						<hr class="custom-hr">
						<div class="col-md-12">
							<div class="col-md-2 select-input-wrapper">
								<label>CMV <span class="text-initial">(IgM)</span></label>
								<input type="text" name="cmv" value="<?php echo $data_labtest['cmv']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>patient</label>
								<input type="text" name="patient" value="<?php echo $data_labtest['patient']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>CUT-OFF VALUE</label>
								<input type="text" name="cut-off" value="<?php echo $data_labtest['cut_off']; ?>" disabled>
							</div>
						</div>
						<div class="clear"></div>
						<hr class="custom-hr">
						<div class="col-md-12">
							<div class="col-md-2 select-input-wrapper">
								<label>TP-HA</label>
								<input type="text" name="tp-ha" value="<?php echo $data_labtest['tp_ha']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>unit</label>
								<input type="text" name="tp-ha_unit" value="<?php echo $data_labtest['tp_ha_unit']; ?>" disabled>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="tp-ha_rf" value="<?php echo $data_labtest['tp_ha_rf']; ?>" disabled>
							</div>
						</div>
					</div>
				<?php } ?>

				<!-- TUMOR MARKERS -->
				<?php if($category == 'tumor_markers'){ ?>
					<div id="tumor_markers" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper">
						<div class="col-md-6 border-right">
							<div class="col-md-4 select-input-wrapper">
								<label>BETA-HCG  (CLIA)</label>
								<input type="text" name="beta_hcg" value="<?php echo $data_labtest['beta_hcg']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="beta_hcg_unit" value="<?php echo $data_labtest['beta_hcg_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="beta_hcg_rf" value="<?php echo $data_labtest['beta_hcg_rf']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>CEA  (CLIA)</label>
								<input type="text" name="cea" value="<?php echo $data_labtest['cea']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="cea_unit" value="<?php echo $data_labtest['cea_unit']; ?>" disabled>	
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="cea_rf" value="<?php echo $data_labtest['cea_rf']; ?>" disabled>
								<ul class="sub-note">
									<li>Non-Smoker : < 2.5 </li>
									<li>Smoker : < 5 </li>
								</ul>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>AFP  (CLIA)</label>
								<input type="text" name="afp" value="<?php echo $data_labtest['afp']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="afp_unit" value="<?php echo $data_labtest['afp_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="afp_rf" value="<?php echo $data_labtest['afp_rf']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>CA 19-9   (CLIA)</label>
								<input type="text" name="ca_19_9" value="<?php echo $data_labtest['ca_19_9']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="ca_19_9_unit" value="<?php echo $data_labtest['ca_19_9_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="ca_19_9_rf" value="<?php echo $data_labtest['ca_19_9_rf']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>CA 15-3  (CLIA)</label>
								<input type="text" name="ca_15_3" value="<?php echo $data_labtest['ca_15_3']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="ca_15_3_unit" value="<?php echo $data_labtest['ca_15_3_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="ca_15_3_rf" value="<?php echo $data_labtest['ca_15_3_rf']; ?>" disabled>
							</div>
							<div class="clear"></div>
						</div>
						<div class="col-md-6">
							<div class="col-md-4 select-input-wrapper">
								<label>CA 72-4  (CLIA)</label>
								<input type="text" name="ca_72_4" value="<?php echo $data_labtest['ca_72_4']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="ca_72_4_unit" value="<?php echo $data_labtest['ca_72_4_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="ca_72_4_rf" value="<?php echo $data_labtest['ca_72_4_rf']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>CYFRA 21-1  (CLIA)</label>
								<input type="text" name="cyfra_21_1_clia" value="<?php echo $data_labtest['cyfra_21_1_clia']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="cyfra_21_1_clia_unit" value="<?php echo $data_labtest['cyfra_21_1_clia_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="cyfra_21_1_clia_rf" value="<?php echo $data_labtest['cyfra_21_1_clia_rf']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>TOTAL PSA  (CLIA)</label>
								<input type="text" name="total_psa" value="<?php echo $data_labtest['total_psa']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="total_psa_unit" value="<?php echo $data_labtest['total_psa_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="total_psa_rf" value="<?php echo $data_labtest['total_psa_rf']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>FREE PSA  (CLIA)</label>
								<input type="text" name="free_psa" value="<?php echo $data_labtest['free_psa']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="free_psa_unit" value="<?php echo $data_labtest['free_psa_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="free_psa_rf" value="<?php echo $data_labtest['free_psa_rf']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>Free: Total PSA Ratio</label>
								<input type="text" name="total_psa_ratio" value="<?php echo $data_labtest['total_psa_ratio']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="total_psa_ratio_unit" placeholder="%" value="<?php echo $data_labtest['total_psa_ratio_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="total_psa_ratio_rf" value="<?php echo $data_labtest['total_psa_ratio_rf']; ?>" disabled>
								<p class="notes-sm">Please refer to the table below</p>
							</div>
						</div>
						<div class="col-md-12 note-test">
							<p>Based on Free: Total PSA ratio; the percent probability of finding Prostate Cancer on a needle biopsy by age in years:</p>
							<table>
								<thead>
									<tr>
										<th>Free: Total PSA Ratio</th>
										<th>50 - 59 years</th>
										<th>60 - 69 years</th>
										<th>> 70 years</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>< 0.10 </td>
										<td>49.2%</td>
										<td>57.5%</td>
										<td>64.5%</td>
									</tr>
									<tr>
										<td>0.11 - 0.18</td>
										<td>26.9%</td>
										<td>33.9%</td>
										<td>40.8%</td>
									</tr>
									<tr>
										<td>0.19 - 0.25</td>
										<td>18.3%</td>
										<td>23.9%</td>
										<td>29.7%</td>
									</tr>
									<tr>
										<td>> 0.25</td>
										<td>9.1%</td>
										<td>12.2%</td>
										<td>15.8%</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="clear"></div>
					</div>
				<?php } ?>

				<!-- special_chem -->
				<?php if($category == 'special_chem'){ ?>
					<div id="special_chem" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper">
						<div class="col-md-6 border-right">
							<div class="col-md-4 select-input-wrapper">
								<label>HOMOCYSTEINE</label>
								<input type="text" name="homocysteine" value="<?php echo $data_labtest['homocysteine']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>unit</label>
								<input type="text" name="homocysteine_unit" value="<?php echo $data_labtest['homocysteine_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="homocysteine_rf" value="<?php echo $data_labtest['homocysteine_rf']; ?>" disabled>
							</div>
						</div>
						<div class="col-md-6">
							<div class="col-md-4 select-input-wrapper">
								<label style="text-transform:initial">NT-proBNP</label>
								<input type="text" name="NT-proBNP" value="<?php echo $data_labtest['NT_proBNP']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>unit</label>
								<input type="text" name="NT-proBNP_unit" value="<?php echo $data_labtest['NT_proBNP_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="NT-proBNP_rf" value="<?php echo $data_labtest['NT_proBNP_rf']; ?>" disabled>
							</div>
						</div>
					</div>
				<?php } ?>

				<!-- special_chem -->
				<?php if($category == 'vita_nutri'){ ?>
					<div id="vita_nutri" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper">
						<div class="col-md-2 select-input-wrapper">
							<label>VITAMIN D 25 OH</label>
							<input type="text" name="vitamin_d_25" value="<?php echo $data_labtest['vitamin_d_25']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>unit</label>
							<input type="text" name="vitamin_d_25_unit" value="<?php echo $data_labtest['vitamin_d_25_unit']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>reference value</label>
							<input type="text" name="vitamin_d_25_rf" value="<?php echo $data_labtest['vitamin_d_25_rf']; ?>" disabled>
						</div>
						<div class="clear"></div>
						<div class="col-md-2 select-input-wrapper">
							<label>VITAMIN B12</label>
							<input type="text" name="vitamin_b12" value="<?php echo $data_labtest['vitamin_b12']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>unit</label>
							<input type="text" name="vitamin_b12_unit" value="<?php echo $data_labtest['vitamin_b12_unit']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>reference value</label>
							<input type="text" name="vitamin_b12_rf" value="<?php echo $data_labtest['vitamin_b12_rf']; ?>" disabled>
						</div>
						<div class="clear"></div>
						<div class="col-md-2 select-input-wrapper">
							<label>FOLATE</label>
							<input type="text" name="folate" value="<?php echo $data_labtest['folate']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>unit</label>
							<input type="text" name="folate_unit" value="<?php echo $data_labtest['folate_unit']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>reference value</label>
							<input type="text" name="folate_rf" value="<?php echo $data_labtest['folate_rf']; ?>" disabled>
						</div>
					</div>
				<?php } ?>

				<!-- viral_hepa -->
				<?php if($category == 'viral_hepa'){ ?>
					<div id="viral_hepa" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper">
						<div class="col-md-6 border-right">
							<div class="col-md-4 select-input-wrapper">
								<label class="text-initial">HBs Ag</label>
								<input type="text" name="hbs_ag" value="<?php echo $data_labtest['hbs_ag']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>patient</label>
								<input type="text" name="hbs_ag_patient" value="<?php echo $data_labtest['hbs_ag_patient']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>CUT-OFF VALUE</label>
								<input type="text" name="hbs_ag_cutoff" value="<?php echo $data_labtest['hbs_ag_cutoff']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label class="text-initial">ANTI-HBs</label>
								<input type="text" name="anti_hbs" value="<?php echo $data_labtest['anti_hbs']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>patient</label>
								<input type="text" name="anti_hbs_patient" value="<?php echo $data_labtest['anti_hbs_patient']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>CUT-OFF VALUE</label>
								<input type="text" name="anti_hbs_cutoff" value="<?php echo $data_labtest['anti_hbs_cutoff']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label class="text-initial">ANTI- HBc IgM</label>
								<input type="text" name="anti_hbc_lgm" value="<?php echo $data_labtest['anti_hbc_lgm']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>patient</label>
								<input type="text" name="anti_hbc_lgm_patient" value="<?php echo $data_labtest['anti_hbc_lgm_patient']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>CUT-OFF VALUE</label>
								<input type="text" name="anti_hbc_lgm_cutoff" value="<?php echo $data_labtest['anti_hbc_lgm_patient']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label class="text-initial">ANTI- HBc IgG</label>
								<input type="text" name="anti_hbc_lgg" value="<?php echo $data_labtest['anti_hbc_lgg']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>patient</label>
								<input type="text" name="anti_hbc_lgg_patient" value="<?php echo $data_labtest['anti_hbc_lgg_patient']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>CUT-OFF VALUE</label>
								<input type="text" name="anti_hbc_lgg_cutoff" value="<?php echo $data_labtest['anti_hbc_lgg_cutoff']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label class="text-initial">Hbe Ag</label>
								<input type="text" name="hbe_ag" value="<?php echo $data_labtest['hbe_ag']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>patient</label>
								<input type="text" name="hbe_ag_patient" value="<?php echo $data_labtest['hbe_ag_patient']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>CUT-OFF VALUE</label>
								<input type="text" name="hbe_ag_cutoff" value="<?php echo $data_labtest['hbe_ag_cutoff']; ?>" disabled>
							</div>
						</div>
						<div class="col-md-6">
							<div class="col-md-4 select-input-wrapper">
								<label class="text-initial">ANTI- Hbe</label>
								<input type="text" name="anti_hbe" value="<?php echo $data_labtest['anti_hbe']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>patient</label>
								<input type="text" name="anti_hbe_patient" value="<?php echo $data_labtest['anti_hbe_patient']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>CUT-OFF VALUE</label>
								<input type="text" name="anti_hbe_cutoff" value="<?php echo $data_labtest['anti_hbe_cutoff']; ?>" disabled>
							</div>
							<div class="clear"></div>
						
							<div class="col-md-4 select-input-wrapper">
								<label class="text-initial">ANTI- HCV</label>
								<input type="text" name="anti_hcv" value="<?php echo $data_labtest['anti_hcv']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>patient</label>
								<input type="text" name="anti_hcv_patient" value="<?php echo $data_labtest['anti_hcv_patient']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>CUT-OFF VALUE</label>
								<input type="text" name="anti_hcv_cutoff" value="<?php echo $data_labtest['anti_hcv_cutoff']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label class="text-initial">ANTI- HAV IgM</label>
								<input type="text" name="anti_hav_lgm" value="<?php echo $data_labtest['anti_hav_lgm']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>patient</label>
								<input type="text" name="anti_hav_lgm_patient" value="<?php echo $data_labtest['anti_hav_lgm_patient']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>CUT-OFF VALUE</label>
								<input type="text" name="anti_hav_lgm_cutoff" value="<?php echo $data_labtest['anti_hav_lgm_cutoff']; ?>" disabled>
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label class="text-initial">ANTI- HAV IgG</label>
								<input type="text" name="anti_hav_lgg" value="<?php echo $data_labtest['anti_hav_lgg']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>patient</label>
								<input type="text" name="anti_hav_lgg_patient" value="<?php echo $data_labtest['anti_hav_lgg_patient']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>CUT-OFF VALUE</label>
								<input type="text" name="anti_hav_lgg_cutoff" value="<?php echo $data_labtest['anti_hav_lgg_cutoff']; ?>" disabled>
							</div>
						</div>
					</div>
				<?php } ?>

				<!-- hiv -->
				<?php if($category == 'hiv'){ ?>
					<div id="hiv" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper">
						<div class="col-md-2 select-input-wrapper">
							<label class="text-initial">HIV</label>
							<input type="text" name="hiv" value="<?php echo $data_labtest['hiv']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>patient</label>
							<input type="text" name="hiv_patient" value="<?php echo $data_labtest['hiv_patient']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>CUT-OFF VALUE</label>
							<input type="text" name="hiv_cut_off" value="<?php echo $data_labtest['hiv_cut_off']; ?>" disabled>
						</div>
					</div>
				<?php } ?>

				<!-- eGFR -->
				<?php if($category == 'eGFR'){ ?>
					<div id="eGFR" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper">
						<div class="col-md-12">
							<div class="col-md-4 select-input-wrapper">
								<label>Estimated GLOMERULAR  FILTRATION RATE <span class="text-initial">(eGFR)</span></label>
								<input type="text" name="egfr" value="<?php echo $data_labtest['egfr']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>unit</label>
								<input type="text" name="egfr_unit" value="<?php echo $data_labtest['egfr_unit']; ?>" disabled>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="egfr_rf" value="<?php echo $data_labtest['egfr_rf']; ?>" disabled>
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
				<?php } ?>

				<!-- nutri_elements -->
				<?php if($category == 'nutri_elements'){ ?>
					<div id="nutri_elements" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper">
						<div class="col-md-2 select-input-wrapper">
							<label>MAGNESIUM, RBC (ICPS)</label>
							<input type="text" name="magnesium_rbc" value="<?php echo $data_labtest['magnesium_rbc']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>unit</label>
							<input type="text" name="magnesium_rbc_unit" value="<?php echo $data_labtest['magnesium_rbc_unit']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>reference value</label>
							<input type="text" name="magnesium_rbc_rf" value="<?php echo $data_labtest['magnesium_rbc_rf']; ?>" disabled>
						</div>
						<div class="clear"></div>
						<div class="col-md-2 select-input-wrapper">
							<label>MERCURY, RBC (ADAS)</label>
							<input type="text" name="mercury_rbc" value="<?php echo $data_labtest['mercury_rbc']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>unit</label>
							<input type="text" name="mercury_rbc_unit" value="<?php echo $data_labtest['mercury_rbc_unit']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>reference value</label>
							<input type="text" name="mercury_rbc_rf" value="<?php echo $data_labtest['mercury_rbc_rf']; ?>" disabled>
						</div>
						<div class="clear"></div>
						<div class="col-md-2 select-input-wrapper">
							<label>LEAD, RBC (GFAAS)</label>
							<input type="text" name="lead_rbc" value="<?php echo $data_labtest['lead_rbc']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>unit</label>
							<input type="text" name="lead_rbc_unit" value="<?php echo $data_labtest['lead_rbc_unit']; ?>" disabled>
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>reference value</label>
							<input type="text" name="lead_rbc_rf" value="<?php echo $data_labtest['lead_rbc_rf']; ?>" disabled>
						</div>
					</div>
					<div class="clear"></div>
				<?php } ?>
				<div class="clear"></div>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</section>
