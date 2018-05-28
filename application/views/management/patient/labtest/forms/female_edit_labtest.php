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
	<form action="<?php echo url('patient_management/add_new_labtest') ?>" method="post" id="add_new_labtest_form"  style="width:100%;">	
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
					<h1>Edit Test</h1>
					<div class="col-md-3 select-input-wrapper type-test">
						<label>type of test</label>
							<form class="radio-test-type">
							<label for="labtest">
							  <input type="radio" id="labtest" name="type_of_test" value="1" <?php echo ($labtest['type_of_test'] == "1" ? "checked" : "") ?>/> Lab Test
							</label>
							<label for="imgtest">
							  <input type="radio" id="imgtest" name="type_of_test" value="0" <?php echo ($labtest['type_of_test'] == "0" ? "checked" : "") ?>/> Imaging Test
							</label>
						</form>
					</div>
					<div class="col-md-3 select-input-wrapper">
						<label>category</label>
						<input type="hidden" name="category" id="category" value="<?php echo $laboratory_test_name['category'];?>"> 
						<input type="text" id="category" value="<?php echo $laboratory_test_name['category_name'];?>" disabled> 
					</div>
					<div class="col-md-3 select-input-wrapper">
						<label>Hospital / Clinic</label>
						<select name="hospital">
						    <option   selected>Choose one</option>
						    <option value="Makati Med" <?php echo ($labtest['hospital'] == "Makati Med" ? "selected" : "") ?>>Makati Med</option>
						    <option value="St. Lukes Medical" <?php echo ($labtest['hospital'] == "St. Lukes Medical" ? "selected" : "") ?>>St. Lukes Medical</option>
						    <option value="PGH" <?php echo ($labtest['hospital'] == "PGH" ? "selected" : "") ?>>PGH</option>
						    <option value="GENSENS, Inc" <?php echo ($labtest['hospital'] == "GENSENS, Inc" ? "selected" : "") ?>>GENSENS, Inc</option>
						</select>
					</div>
					<div class="col-md-3 select-input-wrapper">
						<label>Date of test</label>
						<input type="text" name="date_of_test" id="date_of_test" placeholder="Date of Test" value="<?php echo $labtest['date_of_test'];?>"> 
					</div>
					<div class="clear"></div>
					<div class="col-md-3 select-input-wrapper">
						<label>REQUESTING PHYSICIAN</label>
							<select name="requesting_physician">
								<option   selected>Choose one</option>
								<?php foreach($doctors as $key=>$value): ?>
									<option value="<?php echo $value['id'] ?>" <?php echo ($labtest['requesting_physician'] == $value['id'] ? "selected" : "") ?>><?php echo $value['full_name'] ?></option>
								<?php endforeach; ?>
							</select>
					</div>
					<div class="col-md-3 select-input-wrapper">
						<label>PATIENT ID NO. IN TEST</label>
						<input type="text" name="test_patient_number" placeholder="Patient ID Number" value="<?php echo $labtest['test_patient_number'];?>">
					</div>
					<div class="col-md-3 select-input-wrapper">
						<label>CASE NO.</label>
						<input type="text" name="case_number" placeholder="Case Number" value="<?php echo $labtest['case_number'];?>">
					</div>
				</div>
				<div class="clear"></div>
				<hr class="custom-hr">
				<div class="col-md-12 descript-input-hldr no-padding">
				<h5>DESCRIPTION</h5>
				<!-- URINALYSIS -->
				<?php if($category == 'urinalysis'){ ?>
					<div id="urinal-wrapper" class="col-md-12 urinalysis-input-hldr category-input-wrapper no-padding">
						<h6>GROSS</h6>
						<div class="col-md-3 select-input-wrapper">
							<label>Color</label>
							<input type="text" name="color" value="<?php echo $data_labtest['color'];?>" >
						</div>
						<div class="col-md-3 select-input-wrapper">
							<label>TRANSPARENCY</label>
							<input type="text" name="transparency" value="<?php echo $data_labtest['transparency'];?>" >
						</div>
						<div class="col-md-3 select-input-wrapper">
							<label>SPECIFIC GRAVITY</label>
							<input type="text" name="specific_gravity" value="<?php echo $data_labtest['specific_gravity'];?>" >
						</div>
						<div class="col-md-3 select-input-wrapper">
							<label style="text-transform: initial;">pH</label>
							<input type="text" name="pH" value="<?php echo $data_labtest['pH'];?>" >
						</div>
						<div class="col-md-3 select-input-wrapper">
							<label>PROTEIN</label>
							<input type="text" name="protein" value="<?php echo $data_labtest['protein'];?>" >
						</div>
						<div class="col-md-3 select-input-wrapper">
							<label>SUGAR</label>
							<input type="text" name="sugar" value="<?php echo $data_labtest['sugar'];?>" >
						</div>
						<div class="col-md-3 select-input-wrapper">
							<label>BILIRUBIN</label>
							<input type="text" name="bilirubin" value="<?php echo $data_labtest['bilirubin'];?>" >
						</div>
						<div class="col-md-3 select-input-wrapper">
							<label>UROBILINOGEN</label>
							<input type="text" name="urobilinogen" value="<?php echo $data_labtest['urobilinogen'];?>" >
						</div>
						<div class="col-md-3 select-input-wrapper">
							<label>KETONE</label>
							<input type="text" name="ketone" value="<?php echo $data_labtest['ketone'];?>" >
						</div>
						<div class="col-md-3 select-input-wrapper">
							<label>NITRITE</label>
							<input type="text" name="nitrite" value="<?php echo $data_labtest['nitrite'];?>" >
						</div>
						<div class="clear"></div>
						<h6>MICROSCOPIC</h6>
						<div class="col-md-3 select-input-wrapper">
							<label>RBC</label>
							<input type="text" name="rbc" value="<?php echo $data_labtest['microscopic_rbc'];?>" >
						</div>
						<div class="col-md-3 select-input-wrapper">
							<label>PUS CELL</label>
							<input type="text" name="pus_cell" value="<?php echo $data_labtest['pus_cell'];?>" >
						</div>
						<div class="col-md-3 select-input-wrapper">
							<label>EPITHELIAL CELLS</label>
							<input type="text" name="epithelial_cell" value="<?php echo $data_labtest['epithelial_cell'];?>" >
						</div>
						<div class="col-md-3 select-input-wrapper">
							<label>MUCUS THREADS</label>
							<input type="text" name="mucus_threads" value="<?php echo $data_labtest['mucus_threads'];?>" >
						</div>
						<div class="col-md-3 select-input-wrapper">
							<label>BACTERIA</label>
							<input type="text" name="bacteria" value="<?php echo $data_labtest['bacteria'];?>" >
						</div>

						<div class="col-md-3 select-input-wrapper">
							<label>AMORPHOUS URATES / PHOSPHATES</label>
							<input type="text" name="amorphous_urates" value="<?php echo $data_labtest['amorphous_urates'];?>" >
						</div>
					</div>
				<?php } ?>

				<!-- URINE CHEMISTRY -->
				<?php if($category == 'urine_chemistry'){ ?>
					<div id="urine_chemistry" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper">
						<div class="col-md-2 select-input-wrapper">
							<label>MICROALBUMIN</label>
							<input type="text" name="microalbumin" value="<?php echo $data_labtest['microalbumin']; ?>" >
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>UNIT</label>
							<input type="text" name="microalbumin_unit" value="<?php echo $data_labtest['microalbumin_unit']; ?>" >
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>REFERENCE VALUE</label>
							<input type="text" name="microalbumin_rf" value="<?php echo $data_labtest['microalbumin_rf']; ?>" >
						</div>
					</div>
				<?php } ?>

				<!-- COAGULATION FACTOR -->
				<?php if($category == 'coagulation_factor'){ ?>
					<div id="coagulation_factor" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper">
						<div class="col-md-2 select-input-wrapper">
							<label>FIBRINOGEN</label>
							<input type="text" name="fibrinogen" value="<?php echo $data_labtest['fibrinogen']; ?>" >
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>UNIT</label>
							<input type="text" name="fibrinogen_unit" value="<?php echo $data_labtest['fibrinogen_unit']; ?>" >
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>REFERENCE VALUE</label>
							<input type="text" name="fibrinogen_rf" value="<?php echo $data_labtest['fibrinogen_unit']; ?>" >
						</div>
						<div class="clear"></div>
						<div class="col-md-2 select-input-wrapper">
							<label>BLEEDING TIME</label>
							<input type="text" name="bleeding_time" value="<?php echo $data_labtest['bleeding_time']; ?>" >
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>REFERENCE VALUE</label>
							<input type="text" name="bleeding_time_rf" value="<?php echo $data_labtest['bleeding_time_rf']; ?>" >
						</div>
						<div class="clear"></div>
						<div class="col-md-2 select-input-wrapper">
							<label>CLOTTING TIME</label>
							<input type="text" name="clotting_time" value="<?php echo $data_labtest['clotting_time']; ?>" >
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>REFERENCE VALUE</label>
							<input type="text" name="clotting_time_rf" value="<?php echo $data_labtest['clotting_time_rf']; ?>" >
						</div>
					</div>
				<?php } ?>

				<!-- COAGULATION -->
				<?php if($category == 'coagulation'){ ?>
					<h5 class="no-test-note">No Test for Female</h5>
					<!-- <div id="coagulation" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper two-col-wrapper">
						<div class="col-md-6 border-right">
							<div class="col-md-4 select-input-wrapper">
								<label>PROTHROMBIN TIME (PT)</label>
								<input type="text" name="prothrombin_time" value="<?php echo $data_labtest['prothrombin_time']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="prothrombin_time_unit" value="<?php echo $data_labtest['prothrombin_time_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="prothrombin_time_rf" value="<?php echo $data_labtest['prothrombin_time_rf']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>CONTROL</label>
								<input type="text" name="control" value="<?php echo $data_labtest['control']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="control_unit" value="<?php echo $data_labtest['control_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="control_rf" value="<?php echo $data_labtest['control_rf']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>INR<span style="text-transform:Initial">(Please refer to comments below)</span></label>
								<input type="text" name="inr" value="<?php echo $data_labtest['inr']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="inr_unit" value="<?php echo $data_labtest['inr_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="inr_rf" value="<?php echo $data_labtest['inr_rf']; ?>" >
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
								<input type="text" name="percentage_activity" value="<?php echo $data_labtest['percentage_activity']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="percentage_activity_unit" value="<?php echo $data_labtest['percentage_activity_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="percentage_activity_rf" value="<?php echo $data_labtest['percentage_activity_rf']; ?>" >
							</div>
						</div>
						<div class="col-md-6">
							<div class="col-md-4 select-input-wrapper">
								<label>ACTIVATED PARTIAL</label>
								<input type="text" name="activated_partial" value="<?php echo $data_labtest['activated_partial']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="activated_partial_unit" value="<?php echo $data_labtest['activated_partial_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="activated_partial_rf" value="<?php echo $data_labtest['activated_partial_rf']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>THROMBOPLASTIN TIME (APTT)</label>
								<input type="text" name="thromboplastin_time" value="<?php echo $data_labtest['thromboplastin_time']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="thromboplastin_time_unit" value="<?php echo $data_labtest['thromboplastin_time_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="thromboplastin_time_rf" value="<?php echo $data_labtest['thromboplastin_time_rf']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>CONTROL</label>
								<input type="text" name="aptt_control" value="<?php echo $data_labtest['aptt_control']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="aptt_control_unit" value="<?php echo $data_labtest['aptt_control_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="aptt_control_rf" value="<?php echo $data_labtest['aptt_control_rf']; ?>" >
							</div>
						</div>
					</div> -->
				<?php } ?>

				<!-- HEMATOLOGY -->
				<?php if($category == 'hematology'){ ?>
					<div id="hematology" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper">
						<!-- <div class="col-md-6 border-right padding-bottom">
							<div class="col-md-4 select-input-wrapper">
								<label>BLOOD TYPING W/ RH</label>
								<input type="text" name="blood_typing_with_rh" value="<?php echo $data_labtest['blood_typing_with_rh']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>BLOOD TYPE</label>
								<input type="text" name="blood_type" value="<?php echo $data_labtest['blood_type']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>RH TYPING</label>
								<input type="text" name="rh_typing" value="<?php echo $data_labtest['rh_typing']; ?>" >
							</div>
						</div>
						<div class="col-md-6 padding-bottom">
							<div class="col-md-4 select-input-wrapper">
								<label>RETICULOCYTE COUNT</label>
								<input type="text" name="reticulocyte_count" value="<?php echo $data_labtest['reticulocyte_count']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="reticulocyte_count_unit" value="<?php echo $data_labtest['reticulocyte_count_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="reticulocyte_count_rf" value="<?php echo $data_labtest['reticulocyte_count_rf']; ?>" >
							</div>
						</div> -->
						<div class="col-md-12 no-padding border-top">
							<div class="col-md-4 border-right">
								<div class="col-md-4 select-input-wrapper">
									<label>RED BLOOD CELL</label>
									<input type="text" name="rbc" value="<?php echo $data_labtest['rbc']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="rbc_unit" value="<?php echo $data_labtest['rbc_unit']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="rbc_rf" value="<?php echo $data_labtest['rbc_rf']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>HEMOGLOBIN</label>
									<input type="text" name="hemoglobin" value="<?php echo $data_labtest['hemoglobin']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="hemoglobin_unit" value="<?php echo $data_labtest['hemoglobin_unit']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="hemoglobin_rf" value="<?php echo $data_labtest['hemoglobin_rf']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>HEMATOCRIT</label>
									<input type="text" name="hematocrit" value="<?php echo $data_labtest['hematocrit']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="hematocrit_unit" value="<?php echo $data_labtest['hematocrit_unit']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="hematocrit_rf" value="<?php echo $data_labtest['hematocrit_rf']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>MCV</label>
									<input type="text" name="mcv" value="<?php echo $data_labtest['mcv']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="mcv_unit" value="<?php echo $data_labtest['mcv_unit']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="mcv_rf" value="<?php echo $data_labtest['mcv_rf']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>MCH</label>
									<input type="text" name="mch" value="<?php echo $data_labtest['mch']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="mch_unit" value="<?php echo $data_labtest['mch_unit']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="mch_rf" value="<?php echo $data_labtest['mch_rf']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>MCHC</label>
									<input type="text" name="mchc" value="<?php echo $data_labtest['mchc']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="mchc_unit" value="<?php echo $data_labtest['mchc_unit']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="mchc_rf" value="<?php echo $data_labtest['mchc_rf']; ?>" >
								</div>
							</div>
							<div class="col-md-4 border-right">
								<div class="col-md-4 select-input-wrapper">
									<label>WHITE BLOOD CELL</label>
									<input type="text" name="wbc" value="<?php echo $data_labtest['wbc']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="wbc_unit" value="<?php echo $data_labtest['wbc_unit']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="wbc_rf" value="<?php echo $data_labtest['wbc_rf']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>GRANULOCYTES </label>
									<input type="text" name="granulocytes" value="<?php echo $data_labtest['granulocytes']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="granulocytes_unit" value="<?php echo $data_labtest['granulocytes_unit']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="granulocytes_rf" value="<?php echo $data_labtest['granulocytes_rf']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>LYMPHOCYTES</label>
									<input type="text" name="lymphocytes" value="<?php echo $data_labtest['lymphocytes']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="lymphocytes_unit" value="<?php echo $data_labtest['lymphocytes_unit']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="lymphocytes_rf" value="<?php echo $data_labtest['lymphocytes_rf']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>MONOCYTES</label>
									<input type="text" name="monocytes" value="<?php echo $data_labtest['monocytes']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="monocytes_unit" value="<?php echo $data_labtest['monocytes_unit']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="monocytes_rf" value="<?php echo $data_labtest['monocytes_rf']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>EOSINOPHIL</label>
									<input type="text" name="eosinophil" value="<?php echo $data_labtest['eosinophil']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="eosinophil_unit" value="<?php echo $data_labtest['eosinophil_unit']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="eosinophil_rf" value="<?php echo $data_labtest['eosinophil_rf']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>BASOPHILS</label>
									<input type="text" name="basophils" value="<?php echo $data_labtest['basophils']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="basophils_unit" value="<?php echo $data_labtest['basophils_unit']; ?>" >
								</div>
								<div class="col-md-4 select-input-wrapper">
									<label>REFERENCE VALUE</label>
									<input type="text" name="basophils_rf" value="<?php echo $data_labtest['basophils_rf']; ?>" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="col-md-6 select-input-wrapper">
									<label>PLATELET COUNT</label>
									<input type="text" name="platelet_count" value="<?php echo $data_labtest['platelet_count']; ?>" >
								</div>
								<div class="col-md-6 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="platelet_count_unit" value="<?php echo $data_labtest['platelet_count_unit']; ?>" >
								</div>
								<div class="col-md-6 select-input-wrapper">
									<label>ESR ( < 50 Y/O )</label>
									<input type="text" name="esr_lessthan_50" value="<?php echo $data_labtest['esr_lessthan_50']; ?>" >
								</div>
								<div class="col-md-6 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="esr_lessthan_50_unit" value="<?php echo $data_labtest['esr_lessthan_50_unit']; ?>" >
								</div>
								<div class="col-md-6 select-input-wrapper">
									<label>ESR ( > 50 Y/O )</label>
									<input type="text" name="esr_greaterthan_50" value="<?php echo $data_labtest['esr_greaterthan_50']; ?>" >
								</div>
								<div class="col-md-6 select-input-wrapper">
									<label>UNIT</label>
									<input type="text" name="esr_greaterthan_50_unit" value="<?php echo $data_labtest['esr_greaterthan_50_unit']; ?>" >
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
								<input type="text" name="bio_fasting_si" value="<?php echo $data_labtest['bio_fasting_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="bio_fasting_si_unit" value="<?php echo $data_labtest['bio_fasting_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="bio_fasting_si_rf" value="<?php echo $data_labtest['bio_fasting_si_rf']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>GLUCOSE (FASTING)</label>
								<input type="text" name="bio_fasting_cu" value="<?php echo $data_labtest['bio_fasting_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="bio_fasting_cu_unit" value="<?php echo $data_labtest['bio_fasting_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="bio_fasting_cu_rf" value="<?php echo $data_labtest['bio_fasting_cu_rf']; ?>" >
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>GLUCOSE (RANDOM)</label>
								<input type="text" name="bio_random_si" value="<?php echo $data_labtest['bio_random_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="bio_random_si_unit" value="<?php echo $data_labtest['bio_random_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="bio_random_si_rf" value="<?php echo $data_labtest['bio_random_si_rf']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>GLUCOSE (RANDOM)</label>
								<input type="text" name="bio_random_cu" value="<?php echo $data_labtest['bio_random_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="bio_random_cu_unit" value="<?php echo $data_labtest['bio_random_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="bio_random_cu_rf" value="<?php echo $data_labtest['bio_random_cu_rf']; ?>" >
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper">
								<label>BLOOD UREA NITROGEN</label>
								<input type="text" name="blood_urea_nitrogen_si" value="<?php echo $data_labtest['blood_urea_nitrogen_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="blood_urea_nitrogen_si_unit" value="<?php echo $data_labtest['blood_urea_nitrogen_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="blood_urea_nitrogen_si_rf" value="<?php echo $data_labtest['blood_urea_nitrogen_si_rf']; ?>" >
								<ul class="sub-note ">
									<li>Male < 50yrs : 3.2 - 7.3 mmol/L</li>
									<li>Male > 50yrs : 3.0 - 9.2 mmol/L</li>
								</ul>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>BLOOD UREA NITROGEN</label>
								<input type="text" name="blood_urea_nitrogen_cu" value="<?php echo $data_labtest['blood_urea_nitrogen_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="blood_urea_nitrogen_cu_unit" value="<?php echo $data_labtest['blood_urea_nitrogen_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="blood_urea_nitrogen_cu_rf" value="<?php echo $data_labtest['blood_urea_nitrogen_cu_rf']; ?>" >
								<ul class="sub-note ">
									<li>Male < 50yrs : 9.0 - 20.5 mg/dL</li>
									<li>Male > 50yrs : 8.4 - 25.8 mg/dL</li>
								</ul>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>CREATININE</label>
								<input type="text" name="creatinine_si" value="<?php echo $data_labtest['creatinine_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="creatinine_si_unit" value="<?php echo $data_labtest['creatinine_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="creatinine_si_rf" value="<?php echo $data_labtest['creatinine_si_rf']; ?>" >
								<ul class="sub-note">
									<li>Male : 50  - 106 µmol/L</li>
								</ul>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>CREATININE</label>
								<input type="text" name="creatinine_cu" value="<?php echo $data_labtest['creatinine_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="creatinine_cu_unit" value="<?php echo $data_labtest['creatinine_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="creatinine_cu_rf" value="<?php echo $data_labtest['creatinine_si_rf']; ?>" >
								<ul class="sub-note">
									<li>Male : 0.6 - 1.20 mg/dL</li>
								</ul>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>SGOT</label>
								<input type="text" name="sgot_si" value="<?php echo $data_labtest['sgot_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="sgot_si_unit" value="<?php echo $data_labtest['sgot_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="sgot_si_rf" value="<?php echo $data_labtest['sgot_si_rf']; ?>" >
								<ul class="sub-note">
									<li>Male : 0 - 35 U/L</li>
								</ul>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>SGOT</label>
								<input type="text" name="sgot_cu" value="<?php echo $data_labtest['sgot_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="sgot_cu_unit" value="<?php echo $data_labtest['sgot_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="sgot_cu_rf" value="<?php echo $data_labtest['sgot_cu_rf']; ?>" >
								<ul class="sub-note">
									<li>Male : 0 - 35 U/L</li>
								</ul>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>SGPT</label>
								<input type="text" name="sgpt_si" value="<?php echo $data_labtest['sgpt_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="sgpt_si_unit" value="<?php echo $data_labtest['sgpt_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="sgpt_si_rf" value="<?php echo $data_labtest['sgpt_si_rf']; ?>" >
								<ul class="sub-note">
									<li>Male : 0 - 45 U/L</li>
								</ul>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>SGPT</label>
								<input type="text" name="sgpt_cu" value="<?php echo $data_labtest['sgpt_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="sgpt_cu_unit" value="<?php echo $data_labtest['sgpt_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="sgpt_cu_rf" value="<?php echo $data_labtest['sgpt_cu_rf']; ?>" >
								<ul class="sub-note">
									<li>Male : 0 - 45 U/L</li>
								</ul>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>ALK. PHOSPHATASE</label>
								<input type="text" name="alk_phosphatase_si" value="<?php echo $data_labtest['alk_phosphatase_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="alk_phosphatase_si_unit" value="<?php echo $data_labtest['alk_phosphatase_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="alk_phosphatase_si_rf" value="<?php echo $data_labtest['alk_phosphatase_si_rf']; ?>" >
								<ul class="sub-note">
									<li>Male : 35 - 104 U/L</li>
								</ul>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>ALK. PHOSPHATASE</label>
								<input type="text" name="alk_phosphatase_cu" value="<?php echo $data_labtest['alk_phosphatase_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="alk_phosphatase_cu_unit" value="<?php echo $data_labtest['alk_phosphatase_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="alk_phosphatase_cu_rf" value="<?php echo $data_labtest['alk_phosphatase_cu_rf']; ?>" >
								<ul class="sub-note">
									<li>Male : 35-104 U/L</li>
								</ul>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>GGT (Enzymatic)</label>
								<input type="text" name="ggt_si" value="<?php echo $data_labtest['ggt_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="ggt_si_unit" value="<?php echo $data_labtest['ggt_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="ggt_si_rf" value="<?php echo $data_labtest['ggt_si_rf']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>GGT (Enzymatic)</label>
								<input type="text" name="ggt_cu" value="<?php echo $data_labtest['ggt_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="ggt_cu_unit" value="<?php echo $data_labtest['ggt_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="ggt_cu_rf" value="<?php echo $data_labtest['ggt_cu_rf']; ?>" >
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>TOTAL BILIRUBIN</label>
								<input type="text" name="total_bilirubin_si" value="<?php echo $data_labtest['total_bilirubin_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="total_bilirubin_si_unit" value="<?php echo $data_labtest['total_bilirubin_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="total_bilirubin_si_rf" value="<?php echo $data_labtest['total_bilirubin_si_rf']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>TOTAL BILIRUBIN</label>
								<input type="text" name="total_bilirubin_cu" value="<?php echo $data_labtest['total_bilirubin_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="total_bilirubin_cu_unit" value="<?php echo $data_labtest['total_bilirubin_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="total_bilirubin_cu_rf" value="<?php echo $data_labtest['total_bilirubin_cu_rf']; ?>" >
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>DIRECT BILIRUBIN</label>
								<input type="text" name="direct_bilirubin_si" value="<?php echo $data_labtest['direct_bilirubin_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="direct_bilirubin_si_unit" value="<?php echo $data_labtest['direct_bilirubin_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="direct_bilirubin_si_rf" value="<?php echo $data_labtest['direct_bilirubin_si_rf']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>DIRECT BILIRUBIN</label>
								<input type="text" name="direct_bilirubin_cu" value="<?php echo $data_labtest['direct_bilirubin_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="direct_bilirubin_cu_unit" value="<?php echo $data_labtest['direct_bilirubin_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="direct_bilirubin_cu_rf" value="<?php echo $data_labtest['direct_bilirubin_cu_rf']; ?>" >
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>INDIRECT BILIRUBIN</label>
								<input type="text" name="indirect_bilirubin_si" value="<?php echo $data_labtest['indirect_bilirubin_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="indirect_bilirubin_si_unit" value="<?php echo $data_labtest['indirect_bilirubin_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="indirect_bilirubin_si_rf" value="<?php echo $data_labtest['indirect_bilirubin_si_rf']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>INDIRECT BILIRUBIN</label>
								<input type="text" name="indirect_bilirubin_cu" value="<?php echo $data_labtest['indirect_bilirubin_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="indirect_bilirubin_cu_unit" value="<?php echo $data_labtest['indirect_bilirubin_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="indirect_bilirubin_cu_rf" value="<?php echo $data_labtest['indirect_bilirubin_cu_rf']; ?>" >
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>TOTAL PROTEIN </label>
								<input type="text" name="total_protein_si" value="<?php echo $data_labtest['total_protein_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="total_protein_si_unit" value="<?php echo $data_labtest['total_protein_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="total_protein_si_rf" value="<?php echo $data_labtest['total_protein_si_rf']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>TOTAL PROTEIN </label>
								<input type="text" name="total_protein_cu" value="<?php echo $data_labtest['total_protein_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="total_protein_cu_unit" value="<?php echo $data_labtest['total_protein_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="total_protein_cu_rf" value="<?php echo $data_labtest['total_protein_cu_rf']; ?>" >
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>ALBUMIN</label>
								<input type="text" name="albumin_si" value="<?php echo $data_labtest['albumin_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="albumin_si_unit" value="<?php echo $data_labtest['albumin_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="albumin_si_rf" value="<?php echo $data_labtest['albumin_si_rf']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>ALBUMIN</label>
								<input type="text" name="albumin_cu" value="<?php echo $data_labtest['albumin_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="albumin_cu_unit" value="<?php echo $data_labtest['albumin_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="albumin_cu_rf" value="<?php echo $data_labtest['albumin_cu_rf']; ?>" >
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>GLOBULIN</label>
								<input type="text" name="globulin_si" value="<?php echo $data_labtest['globulin_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="globulin_si_unit" value="<?php echo $data_labtest['globulin_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="globulin_si_rf" value="<?php echo $data_labtest['globulin_si_rf']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>GLOBULIN</label>
								<input type="text" name="globulin_cu" value="<?php echo $data_labtest['globulin_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="globulin_cu_unit" value="<?php echo $data_labtest['globulin_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="globulin_cu_rf" value="<?php echo $data_labtest['globulin_cu_rf']; ?>" >
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>A/G RATIO</label>
								<input type="text" name="ag_ratio_si" value="<?php echo $data_labtest['ag_ratio_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="ag_ratio_si_unit" value="<?php echo $data_labtest['ag_ratio_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="ag_ratio_si_rf" value="<?php echo $data_labtest['ag_ratio_si_rf']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>A/G RATIO</label>
								<input type="text" name="ag_ratio_cu" value="<?php echo $data_labtest['ag_ratio_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="ag_ratio_cu_unit" value="<?php echo $data_labtest['ag_ratio_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="ag_ratio_cu_rf" value="<?php echo $data_labtest['ag_ratio_cu_rf']; ?>" >
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>LACTOSE DEHYDROGENASE</label>
								<input type="text" name="lactose_dehydrogenase_si" value="<?php echo $data_labtest['lactose_dehydrogenase_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="lactose_dehydrogenase_si_unit" value="<?php echo $data_labtest['lactose_dehydrogenase_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="lactose_dehydrogenase_si_rf" value="<?php echo $data_labtest['lactose_dehydrogenase_si_rf']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>LACTOSE DEHYDROGENASE</label>
								<input type="text" name="lactose_dehydrogenase_cu" value="<?php echo $data_labtest['lactose_dehydrogenase_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="lactose_dehydrogenase_cu_unit" value="<?php echo $data_labtest['lactose_dehydrogenase_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="lactose_dehydrogenase_cu_rf" value="<?php echo $data_labtest['lactose_dehydrogenase_cu_rf']; ?>" >
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>INORGANIC PHOSPHATE</label>
								<input type="text" name="inorganic_phosphate_si" value="<?php echo $data_labtest['inorganic_phosphate_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="inorganic_phosphate_si_unit" value="<?php echo $data_labtest['inorganic_phosphate_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="inorganic_phosphate_si_rf" value="<?php echo $data_labtest['inorganic_phosphate_si_rf']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>INORGANIC PHOSPHATE</label>
								<input type="text" name="inorganic_phosphate_cu" value="<?php echo $data_labtest['inorganic_phosphate_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="inorganic_phosphate_cu_unit" value="<?php echo $data_labtest['inorganic_phosphate_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="inorganic_phosphate_cu_rf" value="<?php echo $data_labtest['inorganic_phosphate_cu_rf']; ?>" >
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>BICARBONATE  (ENZYMATIC)</label>
								<input type="text" name="bicarbonate_si" value="<?php echo $data_labtest['bicarbonate_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="bicarbonate_si_unit" value="<?php echo $data_labtest['bicarbonate_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="bicarbonate_si_rf" value="<?php echo $data_labtest['bicarbonate_si_rf']; ?>" >
								<ul class="sub-note">
									<li>Male : 23 - 29 mmol/L</li>
								</ul>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>BICARBONATE  (ENZYMATIC)</label>
								<input type="text" name="bicarbonate_cu" value="<?php echo $data_labtest['bicarbonate_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="bicarbonate_cu_unit" value="<?php echo $data_labtest['bicarbonate_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="bicarbonate_cu_rf" value="<?php echo $data_labtest['bicarbonate_cu_rf']; ?>" >
								<ul class="sub-note">
									<li>Male : 23 - 29 meq/L</li>
								</ul>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>AMYLASE</label>
								<input type="text" name="amylase_si" value="<?php echo $data_labtest['amylase_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="amylase_si_unit" value="<?php echo $data_labtest['amylase_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="amylase_si_rf" value="<?php echo $data_labtest['amylase_si_rf']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>AMYLASE</label>
								<input type="text" name="amylase_cu" value="<?php echo $data_labtest['amylase_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="amylase_cu_unit" value="<?php echo $data_labtest['amylase_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="amylase_cu_rf" value="<?php echo $data_labtest['amylase_cu_rf']; ?>" >
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>LIPASE</label>
								<input type="text" name="lipase_si" value="<?php echo $data_labtest['lipase_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="lipase_si_unit" value="<?php echo $data_labtest['lipase_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="lipase_si_rf" value="<?php echo $data_labtest['lipase_si_rf']; ?>" >
								<ul class="sub-note">
									<li>Newborn : < 60 U/L</li>
									<li>Adult : 10 - 140 U/L</li>
									<li>Elderly : (> 60 y/o): 18 -180 U/L </li>
								</ul>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>LIPASE</label>
								<input type="text" name="lipase_cu" value="<?php echo $data_labtest['lipase_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="lipase_cu_unit" value="<?php echo $data_labtest['lipase_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="lipase_cu_rf" value="<?php echo $data_labtest['lipase_cu_rf']; ?>" >
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
								<input type="text" name="ck_total_si" value="<?php echo $data_labtest['ck_total_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="ck_total_si_unit" value="<?php echo $data_labtest['ck_total_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="ck_total_si_rf" value="<?php echo $data_labtest['ck_total_si_rf']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>CK TOTAL</label>
								<input type="text" name="ck_total_cu" value="<?php echo $data_labtest['ck_total_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="ck_total_cu_unit" value="<?php echo $data_labtest['ck_total_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="ck_total_cu_rf" value="<?php echo $data_labtest['ck_total_cu_rf']; ?>" >
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>CPK MM</label>
								<input type="text" name="cpk_mm_si" value="<?php echo $data_labtest['cpk_mm_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="cpk_mm_si_unit" value="<?php echo $data_labtest['cpk_mm_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="cpk_mm_si_rf" value="<?php echo $data_labtest['cpk_mm_si_rf']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>CPK MM</label>
								<input type="text" name="cpk_mm_cu" value="<?php echo $data_labtest['cpk_mm_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="cpk_mm_cu_unit" value="<?php echo $data_labtest['cpk_mm_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="cpk_mm_cu_rf" value="<?php echo $data_labtest['cpk_mm_cu_rf']; ?>" >
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>CK - MB</label>
								<input type="text" name="ck_mb_si" value="<?php echo $data_labtest['ck_mb_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="ck_mb_si_unit" value="<?php echo $data_labtest['ck_mb_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="ck_mb_si_rf" value="<?php echo $data_labtest['ck_mb_si_rf']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>CK - MB</label>
								<input type="text" name="ck_mb_cu" value="<?php echo $data_labtest['ck_mb_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="ck_mb_cu_unit" value="<?php echo $data_labtest['ck_mb_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="ck_mb_cu_rf" value="<?php echo $data_labtest['ck_mb_cu_rf']; ?>" >
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>FRUCTOSAMINE</label>
								<input type="text" name="fructosamine_si" value="<?php echo $data_labtest['fructosamine_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="fructosamine_si_unit" value="<?php echo $data_labtest['fructosamine_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="fructosamine_si_rf" value="<?php echo $data_labtest['fructosamine_si_rf']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>FRUCTOSAMINE</label>
								<input type="text" name="fructosamine_cu" value="<?php echo $data_labtest['fructosamine_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="fructosamine_cu_unit" value="<?php echo $data_labtest['fructosamine_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="fructosamine_cu_rf" value="<?php echo $data_labtest['fructosamine_si_rf']; ?>" >
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>HBA1C</label>
								<input type="text" name="hba1c_si" value="<?php echo $data_labtest['hba1c_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="hba1c_si_unit" value="<?php echo $data_labtest['hba1c_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="hba1c_si_rf" value="<?php echo $data_labtest['hba1c_si_rf']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>HBA1C</label>
								<input type="text" name="hba1c_cu" value="<?php echo $data_labtest['hba1c_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="hba1c_cu_unit" value="<?php echo $data_labtest['hba1c_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="hba1c_cu_rf" value="<?php echo $data_labtest['hba1c_cu_rf']; ?>" >
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>LIPOPROTEIN (a)</label>
								<input type="text" name="lipoprotein_si" value="<?php echo $data_labtest['lipoprotein_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="lipoprotein_si_unit" value="<?php echo $data_labtest['lipoprotein_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="lipoprotein_si_rf" value="<?php echo $data_labtest['lipoprotein_si_rf']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>LIPOPROTEIN (a)</label>
								<input type="text" name="lipoprotein_cu" value="<?php echo $data_labtest['lipoprotein_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="lipoprotein_cu_unit" value="<?php echo $data_labtest['lipoprotein_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="lipoprotein_cu_rf" value="<?php echo $data_labtest['lipoprotein_cu_rf']; ?>" >
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>CHOLESTEROL</label>
								<input type="text" name="cholesterol_si" value="<?php echo $data_labtest['cholesterol_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="cholesterol_si_unit" value="<?php echo $data_labtest['cholesterol_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="cholesterol_si_rf" value="<?php echo $data_labtest['cholesterol_si_rf']; ?>" >
								<ul class="sub-note ">
									<li>Desirable : ≤5.2 mmol/L</li>
									<li>Borderline High Risk : 5.2 - 6.2 mmol/L</li>
									<li>High Risk : >6.2 mmol/L</li>
								</ul>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>CHOLESTEROL</label>
								<input type="text" name="cholesterol_cu" value="<?php echo $data_labtest['cholesterol_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="cholesterol_cu_unit" value="<?php echo $data_labtest['cholesterol_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="cholesterol_cu_rf" value="<?php echo $data_labtest['cholesterol_cu_rf']; ?>" >
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
								<input type="text" name="triglycerides_si" value="<?php echo $data_labtest['triglycerides_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="triglycerides_si_unit" value="<?php echo $data_labtest['triglycerides_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="triglycerides_si_rf" value="<?php echo $data_labtest['triglycerides_si_rf']; ?>" >
								<ul class="sub-note">
									<li>Normal : < 1.695 mmol/L</li>
									<li>Low Risk : 1.695 - 2.26 mmol/L</li>
									<li>High Risk : 2.26 - 5.65 mmol/L</li>
									<li>Extremely High : >5.65 mmol/L</li>
								</ul>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>TRIGLYCERIDES</label>
								<input type="text" name="triglycerides_cu" value="<?php echo $data_labtest['triglycerides_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="triglycerides_cu_unit" value="<?php echo $data_labtest['triglycerides_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="triglycerides_cu_rf" value="<?php echo $data_labtest['triglycerides_cu_rf']; ?>" >
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
								<input type="text" name="hdl_si" value="<?php echo $data_labtest['hdl_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="hdl_si_unit" value="<?php echo $data_labtest['hdl_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="hdl_si_rf" value="<?php echo $data_labtest['hdl_si_rf']; ?>" >
								<ul class="sub-note">
									<li>Male : 0.77 - 1.81 mmol/L</li>
								</ul>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>HDL</label>
								<input type="text" name="hdl_cu" value="<?php echo $data_labtest['hdl_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="hdl_cu_unit" value="<?php echo $data_labtest['hdl_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="hdl_cu_rf" value="<?php echo $data_labtest['hdl_cu_rf']; ?>" >
								<ul class="sub-note">
									<li>Male : 30 - 70 mg/dL</li>
								</ul>
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>LDL</label>
								<input type="text" name="ldl_si" value="<?php echo $data_labtest['ldl_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="ldl_si_unit" value="<?php echo $data_labtest['ldl_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="ldl_si_rf" value="<?php echo $data_labtest['ldl_si_rf']; ?>" >
								<ul class="sub-note ">
									<li>Desirable : < 3.36 mmol/L</li>
									<li>Borderline High Risk : 3.36 - 4.11 mmol/L</li>
									<li>High Risk : >4.14 mmol/L</li>
								</ul>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>LDL</label>
								<input type="text" name="ldl_cu" value="<?php echo $data_labtest['ldl_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="ldl_cu_unit" value="<?php echo $data_labtest['ldl_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="ldl_cu_rf" value="<?php echo $data_labtest['ldl_cu_rf']; ?>" >
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
								<input type="text" name="vldl_si" value="<?php echo $data_labtest['vldl_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="vldl_si_unit" value="<?php echo $data_labtest['vldl_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="vldl_si_rf" value="<?php echo $data_labtest['vldl_si_rf']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>VLDL</label>
								<input type="text" name="vldl_cu" value="<?php echo $data_labtest['vldl_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="vldl_cu_unit" value="<?php echo $data_labtest['vldl_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="vldl_cu_rf" value="<?php echo $data_labtest['vldl_cu_rf']; ?>" >
							</div>
						</div>
						<div class="clear"></div>
						<hr class="custom-hr">
						<div class="col-md-12 no-padding">
							<h6>RATIO</h6>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>Total Chole:HDL</label>
									<input type="text" name="total_chole_hdl_si" value="<?php echo $data_labtest['total_chole_hdl_si']; ?>" >
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="total_chole_hdl_si_unit" value="<?php echo $data_labtest['total_chole_hdl_si_unit']; ?>" >
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="total_chole_hdl_si_rf" value="<?php echo $data_labtest['total_chole_hdl_si_rf']; ?>" >
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Total Chole:HDL</label>
									<input type="text" name="total_chole_hdl_cu" value="<?php echo $data_labtest['total_chole_hdl_cu']; ?>" >
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="total_chole_hdl_cu_unit" value="<?php echo $data_labtest['total_chole_hdl_cu_unit']; ?>" >
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="total_chole_hdl_cu_rf" value="<?php echo $data_labtest['total_chole_hdl_cu_rf']; ?>" >
									<ul class="sub-note">
										<li>Preferably : < 5.0</li>
										<li>Ideal : < 3.5</li>
									</ul>
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>HDL:LDL</label>
									<input type="text" name="hdl_ldl_si" value="<?php echo $data_labtest['hdl_ldl_si']; ?>" >
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="hdl_ldl_si_unit" value="<?php echo $data_labtest['hdl_ldl_si_unit']; ?>" >
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="hdl_ldl_si_rf" value="<?php echo $data_labtest['hdl_ldl_si_rf']; ?>" >
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>HDL:LDL</label>
									<input type="text" name="hdl_ldl_cu" value="<?php echo $data_labtest['hdl_ldl_cu']; ?>" >
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="hdl_ldl_cu_unit" value="<?php echo $data_labtest['hdl_ldl_cu_unit']; ?>" >
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="hdl_ldl_cu_rf" value="<?php echo $data_labtest['hdl_ldl_cu_rf']; ?>" >
									<ul class="sub-note">
										<li>Preferably : >0.3</li>
										<li>Ideal : >0.4</li>
									</ul>
								</div>
							</div>
							<div class="col-md-12 no-padding">
								<div class="col-md-2 select-input-wrapper no-padding">
									<label>Triglycerides:HDL</label>
									<input type="text" name="triglycerides_hdl_si" value="<?php echo $data_labtest['triglycerides_hdl_si']; ?>" >
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>si unit</label>
									<input type="text" name="triglycerides_hdl_si_unit" value="<?php echo $data_labtest['triglycerides_hdl_si_unit']; ?>" >
								</div>
								<div class="col-md-2 select-input-wrapper border-right">
									<label>reference value</label>
									<input type="text" name="triglycerides_hdl_si_rf" value="<?php echo $data_labtest['triglycerides_hdl_si_rf']; ?>" >
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Triglycerides:HDL</label>
									<input type="text" name="triglycerides_hdl_cu" value="<?php echo $data_labtest['triglycerides_hdl_cu']; ?>" >
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>Conventional unit</label>
									<input type="text" name="triglycerides_hdl_cu_unit" value="<?php echo $data_labtest['triglycerides_hdl_cu_unit']; ?>" >
								</div>
								<div class="col-md-2 select-input-wrapper">
									<label>reference value</label>
									<input type="text" name="triglycerides_hdl_cu_rf" value="<?php echo $data_labtest['triglycerides_hdl_cu_rf']; ?>" >
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
								<input type="text" name="sodium_si" value="<?php echo $data_labtest['sodium_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="sodium_si_unit" value="<?php echo $data_labtest['sodium_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="sodium_si_rf" value="<?php echo $data_labtest['sodium_si_rf']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>SODIUM</label>
								<input type="text" name="sodium_cu" value="<?php echo $data_labtest['sodium_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="sodium_cu_unit" value="<?php echo $data_labtest['sodium_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="sodium_cu_rf" value="<?php echo $data_labtest['sodium_cu_rf']; ?>" >
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>POTASSIUM</label>
								<input type="text" name="potassium_si" value="<?php echo $data_labtest['potassium_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="potassium_si_unit" value="<?php echo $data_labtest['potassium_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="potassium_si_rf" value="<?php echo $data_labtest['potassium_si_rf']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>POTASSIUM</label>
								<input type="text" name="potassium_cu" value="<?php echo $data_labtest['potassium_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="potassium_cu_unit" value="<?php echo $data_labtest['potassium_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="potassium_cu_rf" value="<?php echo $data_labtest['potassium_cu_rf']; ?>" >
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>CALCIUM</label>
								<input type="text" name="calcium_si" value="<?php echo $data_labtest['calcium_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="calcium_si_unit" value="<?php echo $data_labtest['calcium_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="calcium_si_rf" value="<?php echo $data_labtest['calcium_si_rf']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>CALCIUM</label>
								<input type="text" name="calcium_cu" value="<?php echo $data_labtest['calcium_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="calcium_cu_unit" value="<?php echo $data_labtest['calcium_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="calcium_cu_rf" value="<?php echo $data_labtest['calcium_cu_rf']; ?>" >
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>CHLORIDE</label>
								<input type="text" name="chloride_si" value="<?php echo $data_labtest['chloride_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="chloride_si_unit" value="<?php echo $data_labtest['chloride_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="chloride_si_rf" value="<?php echo $data_labtest['chloride_si_rf']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>CHLORIDE</label>
								<input type="text" name="chloride_cu" value="<?php echo $data_labtest['chloride_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="chloride_cu_unit" value="<?php echo $data_labtest['chloride_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="chloride_cu_rf" value="<?php echo $data_labtest['chloride_cu_rf']; ?>" >
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper no-padding">
								<label>MAGNESIUM</label>
								<input type="text" name="magnesium_si" value="<?php echo $data_labtest['magnesium_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si unit</label>
								<input type="text" name="magnesium_si_unit" value="<?php echo $data_labtest['magnesium_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>reference value</label>
								<input type="text" name="magnesium_si_rf" value="<?php echo $data_labtest['magnesium_si_rf']; ?>" >
								<ul class="sub-note">
									<li>Male : 0.73 - 1.06 mmol/L</li>
								</ul>
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>MAGNESIUM</label>
								<input type="text" name="magnesium_cu" value="<?php echo $data_labtest['magnesium_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>Conventional unit</label>
								<input type="text" name="magnesium_cu_unit" value="<?php echo $data_labtest['magnesium_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="magnesium_cu_rf" value="<?php echo $data_labtest['magnesium_cu_rf']; ?>" >
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
							<input type="text" name="glucose_si" value="<?php echo $data_labtest['glucose_si']; ?>" >
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>SI UNIT</label>
							<input type="text" name="glucose_si_unit" value="<?php echo $data_labtest['glucose_si_unit']; ?>" >
						</div>
						<div class="col-md-2 select-input-wrapper border-right">
							<label>REFERENCE VALUE</label>
							<input type="text" name="glucose_si_rf" value="<?php echo $data_labtest['glucose_si_rf']; ?>" >
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>GLUCOSE (2nd hour)</label>
							<input type="text" name="glucose_cu" value="<?php echo $data_labtest['glucose_cu']; ?>" >
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>COnventional UNIT</label>
							<input type="text" name="glucose_cu_unit" value="<?php echo $data_labtest['glucose_cu_unit']; ?>" >
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>REFERENCE VALUE</label>
							<input type="text" name="glucose_cu_rf" value="<?php echo $data_labtest['glucose_cu_rf']; ?>" >
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
								<input type="text" name="fasting_si" value="<?php echo $data_labtest['fasting_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>SI UNIT</label>
								<input type="text" name="fasting_si_unit" value="<?php echo $data_labtest['fasting_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>REFERENCE VALUE</label>
								<input type="text" name="fasting_si_rf" value="<?php echo $data_labtest['fasting_si_rf']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>GLUCOSE (FASTING)</label>
								<input type="text" name="fasting_cu" value="<?php echo $data_labtest['fasting_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>conventional UNIT</label>
								<input type="text" name="fasting_cu_unit" value="<?php echo $data_labtest['fasting_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="fasting_cu_rf" value="<?php echo $data_labtest['fasting_si_rf']; ?>" >
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper">
								<label>GLUCOSE (1ST HOUR)</label>
								<input type="text" name="1st_hour_si" value="<?php echo $data_labtest['1st_hour_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>si UNIT</label>
								<input type="text" name="1st_hour_si_unit" value="<?php echo $data_labtest['1st_hour_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>REFERENCE VALUE</label>
								<input type="text" name="1st_hour_si_rf" value="<?php echo $data_labtest['1st_hour_si_rf']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>GLUCOSE (1ST HOUR)</label>
								<input type="text" name="1st_hour_cu" value="<?php echo $data_labtest['1st_hour_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>conventional UNIT</label>
								<input type="text" name="1st_hour_cu_unit" value="<?php echo $data_labtest['1st_hour_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="1st_hour_cu_rf" value="<?php echo $data_labtest['1st_hour_cu_rf']; ?>" >
							</div>
						</div>
						<div class="col-md-12 no-padding">
							<div class="col-md-2 select-input-wrapper">
								<label>GLUCOSE (2ND HOUR)</label>
								<input type="text" name="2nd_hour_si" value="<?php echo $data_labtest['2nd_hour_si']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>SI UNIT</label>
								<input type="text" name="2nd_hour_si_unit" value="<?php echo $data_labtest['2nd_hour_si_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper border-right">
								<label>REFERENCE VALUE</label>
								<input type="text" name="2nd_hour_si_rf" value="<?php echo $data_labtest['2nd_hour_si_rf']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>GLUCOSE (2ND HOUR)</label>
								<input type="text" name="2nd_hour_cu" value="<?php echo $data_labtest['2nd_hour_cu']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>conventional UNIT</label>
								<input type="text" name="2nd_hour_cu_unit" value="<?php echo $data_labtest['2nd_hour_cu_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="2nd_hour_cu_rf" value="<?php echo $data_labtest['2nd_hour_cu_rf']; ?>" >
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
								<input type="text" name="ft3" value="<?php echo $data_labtest['ft3']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="ft3_unit" value="<?php echo $data_labtest['ft3_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="ft3_rf" value="<?php echo $data_labtest['ft3_rf']; ?>" >
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>FT4 (CLIA)</label>
								<input type="text" name="ft4" value="<?php echo $data_labtest['ft4']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="ft4_unit" value="<?php echo $data_labtest['ft4_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="ft4_rf" value="<?php echo $data_labtest['ft4_rf']; ?>" >
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>TSH (CLIA)</label>
								<input type="text" name="tsh" value="<?php echo $data_labtest['tsh']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="tsh_unit" value="<?php echo $data_labtest['tsh_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="tsh_rf" value="<?php echo $data_labtest['tsh_rf']; ?>" >
							</div>
							<div class="clear"></div>
						</div>
						<div class="col-md-6">
							<div class="col-md-4 select-input-wrapper">
								<label>T3 REVERSE (CLIA)</label>
								<input type="text" name="t3_reverse" value="<?php echo $data_labtest['t3_reverse']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="t3_reverse_unit" value="<?php echo $data_labtest['t3_reverse_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="t3_reverse_rf" value="<?php echo $data_labtest['t3_reverse_rf']; ?>" >
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>THYROGLOBULIN ANTIBODY (CLIA)</label>
								<input type="text" name="thyroglobulin_antibody" value="<?php echo $data_labtest['thyroglobulin_antibody']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="thyroglobulin_antibody_unit" value="<?php echo $data_labtest['thyroglobulin_antibody_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="thyroglobulin_antibody_rf" value="<?php echo $data_labtest['thyroglobulin_antibody_rf']; ?>" >
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>THYROID PEROXIDASE ANTIBODY (CLIA)</label>
								<input type="text" name="thyroid_peroxidase_antibody" value="<?php echo $data_labtest['thyroid_peroxidase_antibody']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="thyroid_peroxidase_antibody_unit" value="<?php echo $data_labtest['thyroid_peroxidase_antibody_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="thyroid_peroxidase_antibody_rf" value="<?php echo $data_labtest['thyroid_peroxidase_antibody_rf']; ?>" >
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
								<label>FSH (CLIA)</label>
								<input type="text" name="fsh" value="<?php echo $data_labtest['fsh']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="fsh_unit" value="<?php echo $data_labtest['fsh_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="fsh_rf" value="<?php echo $data_labtest['fsh_rf']; ?>" >
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
								<input type="text" name="lh" value="<?php echo $data_labtest['lh']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="lh_unit" value="<?php echo $data_labtest['lh_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="lh_rf" value="<?php echo $data_labtest['lh_rf']; ?>" >
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
								<input type="text" name="progesterone" value="<?php echo $data_labtest['progesterone']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="progesterone_unit" value="<?php echo $data_labtest['progesterone_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="progesterone_rf" value="<?php echo $data_labtest['progesterone_rf']; ?>" >
								<ul class="sub-note">
									<li>Follicular Phase : < 0.25 - 54 </li>
									<li>Luteal Phase : 0.2 - 6.5</li>
									<li>Ovulation Peak : < 0.25 - 6.22 </li>
									<li>Menopause : 8.0 - 33.0</li>
								</ul>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>ESTRADIOL (CLIA)</label>
								<input type="text" name="estradiol" value="<?php echo $data_labtest['estradiol']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="estradiol_unit" value="<?php echo $data_labtest['estradiol_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="estradiol_rf" value="<?php echo $data_labtest['estradiol_rf']; ?>" >
								<ul class="sub-note">
									<li>Follicular Phase : < 18 -147</li>
									<li>Pre-Ovulatroy : 93 - 575 </li>
									<li>Luteal : 43 -214</li>
									<li>Menopause : < 58</li>
								</ul>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>TOTAL TESTOSTERONE (CLIA)</label>
								<input type="text" name="total_testosterone" value="<?php echo $data_labtest['total_testosterone']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="total_testosterone_unit" value="<?php echo $data_labtest['total_testosterone_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="total_testosterone_rf" value="<?php echo $data_labtest['total_testosterone_rf']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>DIHYDROTESTOSTERONE (DHT) - RIA</label>
								<input type="text" name="dht" value="<?php echo $data_labtest['dht']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="dht_unit" value="<?php echo $data_labtest['dht_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="dht_rf" value="<?php echo $data_labtest['dht_rf']; ?>" >
								<ul class="sub-note">
									<li>Premenopausal : .024- 0.368</li>
									<li>Postmenopausal : 0.010 - 0.181</li>
								</ul>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>CORTISOL (CLIA)</label>
								<input type="text" name="cortisol" value="<?php echo $data_labtest['cortisol']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="cortisol_unit" value="<?php echo $data_labtest['cortisol_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="cortisol_rf" value="<?php echo $data_labtest['cortisol_rf']; ?>" >
								<ul class="sub-note">
									<li>8:00 A.M SPECIMEN : 5 - 23</li>
								</ul>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>SEROTONIN (ELISA)</label>
								<input type="text" name="serotonin" value="<?php echo $data_labtest['serotonin']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="serotonin_unit" value="<?php echo $data_labtest['serotonin_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="serotonin_rf" value="<?php echo $data_labtest['serotonin_rf']; ?>" >
							</div>
						</div>
						<div class="col-md-6">
							<div class="col-md-4 select-input-wrapper">
								<label>PREGNENOLONE (ELISA)</label>
								<input type="text" name="pregnenolone" value="<?php echo $data_labtest['pregnenolone']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="pregnenolone_unit" value="<?php echo $data_labtest['pregnenolone_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="pregnenolone_rf" value="<?php echo $data_labtest['pregnenolone_rf']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>INSULIN ASSAY (CLIA) (FASTING)</label>
								<input type="text" name="insulin_assay_fasting" value="<?php echo $data_labtest['insulin_assay_fasting']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="insulin_assay_fasting_unit" value="<?php echo $data_labtest['insulin_assay_fasting_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="insulin_assay_fasting_rf" value="<?php echo $data_labtest['insulin_assay_fasting_rf']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>INSULIN ASSAY (CLIA) (POST PRANDIAL)</label>
								<input type="text" name="post_prandial" value="<?php echo $data_labtest['post_prandia']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="post_prandial_unit" value="<?php echo $data_labtest['post_prandial_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="post_prandial_rf" value="<?php echo $data_labtest['post_prandial_rf']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>Dehydroepiandrosterone Sulfate (DHEA-SO4) - (CLIA)</label>
								<input type="text" name="dhea_so4" value="<?php echo $data_labtest['dhea_so4']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="dhea_so4_unit" value="<?php echo $data_labtest['dhea_so4_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="dhea_so4_rf" value="<?php echo $data_labtest['dhea_so4_rf']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>INSULIN GROWTH FACTOR-1 (IGF-1) - (CLIA)</label>
								<input type="text" name="igf_1" value="<?php echo $data_labtest['igf_1']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="igf_1_unit" value="<?php echo $data_labtest['igf_1_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="igf_1_rf" value="<?php echo $data_labtest['igf_1_rf']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>INSULIN GROWTH FACTOR-BP3 (IGF-BP3) - (CLIA)</label>
								<input type="text" name="igf_bp3" value="<?php echo $data_labtest['igf_bp3']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="igf_bp3_unit" value="<?php echo $data_labtest['igf_bp3_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="igf_bp3_rf" value="<?php echo $data_labtest['igf_bp3_rf']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>OSTEOCALCIN (ELISA)</label>
								<input type="text" name="osteocalcin" value="<?php echo $data_labtest['osteocalcin']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="osteocalcin_unit" value="<?php echo $data_labtest['osteocalcin_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="osteocalcin_rf" value="<?php echo $data_labtest['osteocalcin_rf']; ?>" >
								<ul class="sub-note">
									<li>Premenopausal Phase <br> > 20 YRS OLD : 11.0 - 43.0 </li>
									<li>Postmenopausal Phase : 15.0 - 46.0</li>
									<li>Osteoporosis Patients : 13.0 - 48.0</li>
								</ul>
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
							<input type="text" name="beta_cell_function" value="<?php echo $data_labtest['beta_cell_function']; ?>" >
							</div>
						</div>
						<div class="col-md-4 border-right">
							<div class="col-md-12 select-input-wrapper">
								<label>Insulin Sensitivity (% S :)</label>
								<input type="text" name="insulin_sensitivity" value="<?php echo $data_labtest['insulin_sensitivity']; ?>" >
							</div>
						</div>
						<div class="col-md-4">
							<div class="col-md-12 select-input-wrapper">
								<label>Insulin Resistance (IR :)</label>
								<input type="text" name="insulin_resistance" value="<?php echo $data_labtest['insulin_resistance']; ?>" >
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
								<input type="text" name="rheumatoid_factor" value="<?php echo $data_labtest['rheumatoid_factor']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="rheumatoid_factor_unit" value="<?php echo $data_labtest['rheumatoid_factor_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="rheumatoid_factor_rf" value="<?php echo $data_labtest['rheumatoid_factor_rf']; ?>" >
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>C-REACTIVE PROTEIN (HCRP)</label>
								<input type="text" name="c-reactive_protein" value="<?php echo $data_labtest['c_reactive_protein']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="c-reactive_protein_unit" value="<?php echo $data_labtest['c_reactive_protein_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="c-reactive_protein_rf" value="<?php echo $data_labtest['c_reactive_protein_rf']; ?>" >
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>FERRITIN</label>
								<input type="text" name="ferritin" value="<?php echo $data_labtest['ferritin']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="ferritin_unit" value="<?php echo $data_labtest['ferritin_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="ferritin_rf" value="<?php echo $data_labtest['ferritin_rf']; ?>" >
							</div>
						</div>
						<div class="col-md-6">
							<div class="col-md-4 select-input-wrapper">
								<label>ERYTHROPOIETIN</label>
								<input type="text" name="erythropoietin" value="<?php echo $data_labtest['erythropoietin']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</span></label>
								<input type="text" name="erythropoietin_unit" value="<?php echo $data_labtest['erythropoietin_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="erythropoietin_rf" value="<?php echo $data_labtest['erythropoietin_rf']; ?>" >
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label>Serum Immunoglobulin E <span style="text-transform:initial">(IgE)</span></label>
								<input type="text" name="serum_immunoglobulin" value="<?php echo $data_labtest['serum_immunoglobulin']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="serum_immunoglobulin_unit" value="<?php echo $data_labtest['serum_immunoglobulin_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="serum_immunoglobulin_rf" value="<?php echo $data_labtest['serum_immunoglobulin_rf']; ?>" >
							</div>
						</div>
						<div class="clear"></div>
						<hr class="custom-hr">
						<div class="col-md-12">
							<div class="col-md-2 select-input-wrapper">
								<label>CMV <span class="text-initial">(IgM)</span></label>
								<input type="text" name="cmv" value="<?php echo $data_labtest['cmv']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>patient</label>
								<input type="text" name="patient" value="<?php echo $data_labtest['patient']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>CUT-OFF VALUE</label>
								<input type="text" name="cut-off" value="<?php echo $data_labtest['cut_off']; ?>" >
							</div>
						</div>
						<div class="clear"></div>
						<hr class="custom-hr">
						<div class="col-md-12">
							<div class="col-md-2 select-input-wrapper">
								<label>TP-HA</label>
								<input type="text" name="tp-ha" value="<?php echo $data_labtest['tp_ha']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>unit</label>
								<input type="text" name="tp-ha_unit" value="<?php echo $data_labtest['tp_ha_unit']; ?>" >
							</div>
							<div class="col-md-2 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="tp-ha_rf" value="<?php echo $data_labtest['tp_ha_rf']; ?>" >
							</div>
						</div>
					</div>
				<?php } ?>

				<!-- TUMOR MARKERS -->
				<?php if($category == 'tumor_markers'){ ?>
					<div id="tumor_markers" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper">
						<div class="col-md-6 border-right">
							<div class="col-md-4 select-input-wrapper">
								<label>CEA  (CLIA)</label>
								<input type="text" name="cea" value="<?php echo $data_labtest['cea']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="cea_unit" value="<?php echo $data_labtest['cea_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="cea_rf" value="<?php echo $data_labtest['cea_rf']; ?>" >
								<ul class="sub-note">
									<li>Non-Smoker : < 2.5 </li>
									<li>Smoker : < 5 </li>
								</ul>
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>CA 125   (CLIA)</label>
								<input type="text" name="ca_125" value="<?php echo $data_labtest['ca_125']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="ca_125_unit" value="<?php echo $data_labtest['ca_125_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="ca_125_rf" value="<?php echo $data_labtest['ca_125_rf']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>CA 19-9   (CLIA)</label>
								<input type="text" name="ca_19_9" value="<?php echo $data_labtest['ca_19_9']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="ca_19_9_unit" value="<?php echo $data_labtest['ca_19_9_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="ca_19_9_rf" value="<?php echo $data_labtest['ca_19_9_rf']; ?>" >
							</div>
						</div>
						<div class="col-md-6">
							<div class="col-md-4 select-input-wrapper">
								<label>CA 72-4  (CLIA)</label>
								<input type="text" name="ca_72_4" value="<?php echo $data_labtest['ca_72_4']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="ca_72_4_unit" value="<?php echo $data_labtest['ca_72_4_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="ca_72_4_rf" value="<?php echo $data_labtest['ca_72_4_rf']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>BETA-HCG  (CLIA)</label>
								<input type="text" name="beta_hcg" value="<?php echo $data_labtest['beta_hcg']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="beta_hcg_unit" value="<?php echo $data_labtest['beta_hcg_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="beta_hcg_rf" value="<?php echo $data_labtest['beta_hcg_rf']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>CYFRA 21-1</label>
								<input type="text" name="cyfra_21_1" value="<?php echo $data_labtest['cyfra_21_1']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>UNIT</label>
								<input type="text" name="cyfra_21_1_unit" value="<?php echo $data_labtest['cyfra_21_1_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>REFERENCE VALUE</label>
								<input type="text" name="cyfra_21_1_rf" value="<?php echo $data_labtest['cyfra_21_1_rf']; ?>" >
							</div>
						</div>
					</div>
				<?php } ?>

				<!-- special_chem -->
				<?php if($category == 'special_chem'){ ?>
					<div id="special_chem" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper">
						<div class="col-md-6 border-right">
							<div class="col-md-4 select-input-wrapper">
								<label>HOMOCYSTEINE</label>
								<input type="text" name="homocysteine" value="<?php echo $data_labtest['homocysteine']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>unit</label>
								<input type="text" name="homocysteine_unit" value="<?php echo $data_labtest['homocysteine_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="homocysteine_rf" value="<?php echo $data_labtest['homocysteine_rf']; ?>" >
							</div>
						</div>
						<div class="col-md-6">
							<div class="col-md-4 select-input-wrapper">
								<label style="text-transform:initial">NT-proBNP</label>
								<input type="text" name="NT-proBNP" value="<?php echo $data_labtest['NT_proBNP']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>unit</label>
								<input type="text" name="NT-proBNP_unit" value="<?php echo $data_labtest['NT_proBNP_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="NT-proBNP_rf" value="<?php echo $data_labtest['NT_proBNP_rf']; ?>" >
							</div>
						</div>
					</div>
				<?php } ?>

				<!-- special_chem -->
				<?php if($category == 'vita_nutri'){ ?>
					<div id="vita_nutri" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper">
						<div class="col-md-2 select-input-wrapper">
							<label>VITAMIN D 25 OH</label>
							<input type="text" name="vitamin_d_25" value="<?php echo $data_labtest['vitamin_d_25']; ?>" >
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>unit</label>
							<input type="text" name="vitamin_d_25_unit" value="<?php echo $data_labtest['vitamin_d_25_unit']; ?>" >
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>reference value</label>
							<input type="text" name="vitamin_d_25_rf" value="<?php echo $data_labtest['vitamin_d_25_rf']; ?>" >
						</div>
						<div class="clear"></div>
						<div class="col-md-2 select-input-wrapper">
							<label>VITAMIN B12</label>
							<input type="text" name="vitamin_b12" value="<?php echo $data_labtest['vitamin_b12']; ?>" >
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>unit</label>
							<input type="text" name="vitamin_b12_unit" value="<?php echo $data_labtest['vitamin_b12_unit']; ?>" >
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>reference value</label>
							<input type="text" name="vitamin_b12_rf" value="<?php echo $data_labtest['vitamin_b12_rf']; ?>" >
						</div>
						<div class="clear"></div>
						<div class="col-md-2 select-input-wrapper">
							<label>FOLATE</label>
							<input type="text" name="folate" value="<?php echo $data_labtest['folate']; ?>" >
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>unit</label>
							<input type="text" name="folate_unit" value="<?php echo $data_labtest['folate_unit']; ?>" >
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>reference value</label>
							<input type="text" name="folate_rf" value="<?php echo $data_labtest['folate_rf']; ?>" >
						</div>
					</div>
				<?php } ?>

				<!-- viral_hepa -->
				<?php if($category == 'viral_hepa'){ ?>
					<div id="viral_hepa" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper">
						<div class="col-md-6 border-right">
							<div class="col-md-4 select-input-wrapper">
								<label class="text-initial">HBs Ag</label>
								<input type="text" name="hbs_ag" value="<?php echo $data_labtest['hbs_ag']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>patient</label>
								<input type="text" name="hbs_ag_patient" value="<?php echo $data_labtest['hbs_ag_patient']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>CUT-OFF VALUE</label>
								<input type="text" name="hbs_ag_cutoff" value="<?php echo $data_labtest['hbs_ag_cutoff']; ?>" >
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label class="text-initial">ANTI-HBs</label>
								<input type="text" name="anti_hbs" value="<?php echo $data_labtest['anti_hbs']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>patient</label>
								<input type="text" name="anti_hbs_patient" value="<?php echo $data_labtest['anti_hbs_patient']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>CUT-OFF VALUE</label>
								<input type="text" name="anti_hbs_cutoff" value="<?php echo $data_labtest['anti_hbs_cutoff']; ?>" >
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label class="text-initial">ANTI- HBc IgM</label>
								<input type="text" name="anti_hbc_lgm" value="<?php echo $data_labtest['anti_hbc_lgm']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>patient</label>
								<input type="text" name="anti_hbc_lgm_patient" value="<?php echo $data_labtest['anti_hbc_lgm_patient']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>CUT-OFF VALUE</label>
								<input type="text" name="anti_hbc_lgm_cutoff" value="<?php echo $data_labtest['anti_hbc_lgm_patient']; ?>" >
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label class="text-initial">ANTI- HBc IgG</label>
								<input type="text" name="anti_hbc_lgg" value="<?php echo $data_labtest['anti_hbc_lgg']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>patient</label>
								<input type="text" name="anti_hbc_lgg_patient" value="<?php echo $data_labtest['anti_hbc_lgg_patient']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>CUT-OFF VALUE</label>
								<input type="text" name="anti_hbc_lgg_cutoff" value="<?php echo $data_labtest['anti_hbc_lgg_cutoff']; ?>" >
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label class="text-initial">Hbe Ag</label>
								<input type="text" name="hbe_ag" value="<?php echo $data_labtest['hbe_ag']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>patient</label>
								<input type="text" name="hbe_ag_patient" value="<?php echo $data_labtest['hbe_ag_patient']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>CUT-OFF VALUE</label>
								<input type="text" name="hbe_ag_cutoff" value="<?php echo $data_labtest['hbe_ag_cutoff']; ?>" >
							</div>
						</div>
						<div class="col-md-6">
							<div class="col-md-4 select-input-wrapper">
								<label class="text-initial">ANTI- Hbe</label>
								<input type="text" name="anti_hbe" value="<?php echo $data_labtest['anti_hbe']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>patient</label>
								<input type="text" name="anti_hbe_patient" value="<?php echo $data_labtest['anti_hbe_patient']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>CUT-OFF VALUE</label>
								<input type="text" name="anti_hbe_cutoff" value="<?php echo $data_labtest['anti_hbe_cutoff']; ?>" >
							</div>
							<div class="clear"></div>
						
							<div class="col-md-4 select-input-wrapper">
								<label class="text-initial">ANTI- HCV</label>
								<input type="text" name="anti_hcv" value="<?php echo $data_labtest['anti_hcv']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>patient</label>
								<input type="text" name="anti_hcv_patient" value="<?php echo $data_labtest['anti_hcv_patient']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>CUT-OFF VALUE</label>
								<input type="text" name="anti_hcv_cutoff" value="<?php echo $data_labtest['anti_hcv_cutoff']; ?>" >
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label class="text-initial">ANTI- HAV IgM</label>
								<input type="text" name="anti_hav_lgm" value="<?php echo $data_labtest['anti_hav_lgm']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>patient</label>
								<input type="text" name="anti_hav_lgm_patient" value="<?php echo $data_labtest['anti_hav_lgm_patient']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>CUT-OFF VALUE</label>
								<input type="text" name="anti_hav_lgm_cutoff" value="<?php echo $data_labtest['anti_hav_lgm_cutoff']; ?>" >
							</div>
							<div class="clear"></div>
							<div class="col-md-4 select-input-wrapper">
								<label class="text-initial">ANTI- HAV IgG</label>
								<input type="text" name="anti_hav_lgg" value="<?php echo $data_labtest['anti_hav_lgg']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>patient</label>
								<input type="text" name="anti_hav_lgg_patient" value="<?php echo $data_labtest['anti_hav_lgg_patient']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>CUT-OFF VALUE</label>
								<input type="text" name="anti_hav_lgg_cutoff" value="<?php echo $data_labtest['anti_hav_lgg_cutoff']; ?>" >
							</div>
						</div>
					</div>
				<?php } ?>

				<!-- hiv -->
				<?php if($category == 'hiv'){ ?>
					<div id="hiv" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper">
						<div class="col-md-2 select-input-wrapper">
							<label class="text-initial">HIV</label>
							<input type="text" name="hiv" value="<?php echo $data_labtest['hiv']; ?>" >
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>patient</label>
							<input type="text" name="hiv_patient" value="<?php echo $data_labtest['hiv_patient']; ?>" >
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>CUT-OFF VALUE</label>
							<input type="text" name="hiv_cut_off" value="<?php echo $data_labtest['hiv_cut_off']; ?>" >
						</div>
					</div>
				<?php } ?>

				<!-- eGFR -->
				<?php if($category == 'eGFR'){ ?>
					<div id="eGFR" class="col-md-12 hematology-input-hldr no-padding category-input-wrapper">
						<div class="col-md-12">
							<div class="col-md-4 select-input-wrapper">
								<label>Estimated GLOMERULAR  FILTRATION RATE <span class="text-initial">(eGFR)</span></label>
								<input type="text" name="egfr" value="<?php echo $data_labtest['egfr']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>unit</label>
								<input type="text" name="egfr_unit" value="<?php echo $data_labtest['egfr_unit']; ?>" >
							</div>
							<div class="col-md-4 select-input-wrapper">
								<label>reference value</label>
								<input type="text" name="egfr_rf" value="<?php echo $data_labtest['egfr_rf']; ?>" >
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
							<input type="text" name="magnesium_rbc" value="<?php echo $data_labtest['magnesium_rbc']; ?>" >
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>unit</label>
							<input type="text" name="magnesium_rbc_unit" value="<?php echo $data_labtest['magnesium_rbc_unit']; ?>" >
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>reference value</label>
							<input type="text" name="magnesium_rbc_rf" value="<?php echo $data_labtest['magnesium_rbc_rf']; ?>" >
						</div>
						<div class="clear"></div>
						<div class="col-md-2 select-input-wrapper">
							<label>MERCURY, RBC (ADAS)</label>
							<input type="text" name="mercury_rbc" value="<?php echo $data_labtest['mercury_rbc']; ?>" >
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>unit</label>
							<input type="text" name="mercury_rbc_unit" value="<?php echo $data_labtest['mercury_rbc_unit']; ?>" >
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>reference value</label>
							<input type="text" name="mercury_rbc_rf" value="<?php echo $data_labtest['mercury_rbc_rf']; ?>" >
						</div>
						<div class="clear"></div>
						<div class="col-md-2 select-input-wrapper">
							<label>LEAD, RBC (GFAAS)</label>
							<input type="text" name="lead_rbc" value="<?php echo $data_labtest['lead_rbc']; ?>" >
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>unit</label>
							<input type="text" name="lead_rbc_unit" value="<?php echo $data_labtest['lead_rbc_unit']; ?>" >
						</div>
						<div class="col-md-2 select-input-wrapper">
							<label>reference value</label>
							<input type="text" name="lead_rbc_rf" value="<?php echo $data_labtest['lead_rbc_rf']; ?>" >
						</div>
					</div>
					<div class="clear"></div>
				<?php } ?>
				
					<div class="save-cancel-btn-wrapper" style="display: block;">
						<button class="def-btn view-records-btn cancel-btn">Cancel</button>
						<button type="button"class="def-btn view-records-btn add_submit_btn" >Save</button>
					</div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</section>
</form>
