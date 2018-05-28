<ul class="drag-drop">
	<?php if(!empty($laboratory_test)){ 
		foreach ($laboratory_test as $key => $value) { 
			if($value['category'] == 'urinalysis'){
				$files = Patient_Labtest_Urinalysis::findByLabtestId(array("id" => $value['id']));
			}elseif ($value['category'] == 'urine_chemistry') {
				$files = Patient_Labtest_Urine_Chemistry::findByLabtestId(array("id" => $value['id']));
			}elseif ($value['category'] == 'coagulation_factor') {
				$files = Patient_Labtest_Coagulation_Factor::findByLabtestId(array("id" => $value['id']));
			}elseif ($value['category'] == 'coagulation') {
				$files = Patient_Labtest_Coagulation::findByLabtestId(array("id" => $value['id']));
			}elseif ($value['category'] == 'hematology') {
				$files = Patient_Labtest_Hematology::findByLabtestId(array("id" => $value['id']));
			}elseif ($value['category'] == 'biochemistry') {
				$files = Patient_Labtest_Biochemistry::findByLabtestId(array("id" => $value['id']));
				$files2 = Patient_Labtest_Biochemistry_2::findByLabtestId(array("id" => $value['id']));
			}elseif ($value['category'] == 'oral_glucose_challenge') {
				$files = Patient_Labtest_Oral_Glucose_Challenge::findByLabtestId(array("id" => $value['id']));
			}elseif ($value['category'] == 'oral_glucose_tolerance') {
				$files = Patient_Labtest_Oral_Glucose_Tolerance::findByLabtestId(array("id" => $value['id']));
			}elseif ($value['category'] == 'thyroid') {
				$files = Patient_Labtest_Thyroid::findByLabtestId(array("id" => $value['id']));
			}elseif ($value['category'] == 'hormones') {
				$files = Patient_Labtest_Hormones_Test::findByLabtestId(array("id" => $value['id']));
			}elseif ($value['category'] == 'homeostasis_ass_index') {
				$files = Patient_Labtest_Homeostasis_Assessment_Index::findByLabtestId(array("id" => $value['id']));
			}elseif ($value['category'] == 'serology') {
				$files = Patient_Labtest_Serology_Immunology::findByLabtestId(array("id" => $value['id']));
			}elseif ($value['category'] == 'tumor_markers') {
				$files = Patient_Labtest_Tumor_Markers::findByLabtestId(array("id" => $value['id']));
			}elseif ($value['category'] == 'special_chem') {
				$files = Patient_Labtest_Special_Chemistry::findByLabtestId(array("id" => $value['id']));
			}elseif ($value['category'] == 'vita_nutri') {
				$files = Patient_Labtest_Vitamins_Nutrition::findByLabtestId(array("id" => $value['id']));
			}elseif ($value['category'] == 'viral_hepa') {
				$files = Patient_Labtest_Viral_Hepatitis::findByLabtestId(array("id" => $value['id']));
			}elseif ($value['category'] == 'hiv') {
				$files = Patient_Labtest_Hiv::findByLabtestId(array("id" => $value['id']));
			}elseif ($value['category'] == 'eGFR') {
				$files = Patient_Labtest_Egfr::findByLabtestId(array("id" => $value['id']));
			}elseif ($value['category'] == 'nutri_elements') {
				$files = Patient_Labtest_Nutrients_Elements::findByLabtestId(array("id" => $value['id']));
			}
	?>			
	<li>
	<div class="tbl-recent-test-wrapper">
		<div class="sub-tbl-header">
			<?php $laboratory_test_name = Patient_Laboratory_Test::findByName(array("category_value" => $value['category'])); ?>
			<h6><?php echo $laboratory_test_name['category_name'];?></h6>
			<a href="javascript: void(0);" onclick="javascript: view_labtest(<?php echo $value['id']?>, <?php echo $id;?>)">view test details</a>
		</div>
		<p class="test-date"><?php  $date = new Datetime($value['date_of_test']); $date_generated = date_format($date, "M d, Y"); echo $date_generated;?></p>
		<table style="width:100%">
			<thead>
				<th>DESCRIPTION</th>
				<th>RESULT</th>
			</thead>
			<tbody>
				<?php if($value['category'] == 'urinalysis'){ ?>
					<tr>
						<td>Color</td>
						<td><?php echo $files['color'];?></td>
					</tr>
					<tr>
						<td>Transparency</td>
						<td><?php echo $files['transparency'];?></td>
					</tr>
					<tr>
						<td>Specific Gravity</td>
						<td><?php echo $files['specific_gravity'];?></td>
					</tr>
					<tr>
						<td>pH</td>
						<td><?php echo $files['pH'];?></td>
					</tr>
					<tr>
						<td>Protein</td>
						<td><?php echo $files['protein'];?></td>
					</tr>
					<tr>
						<td>Sugar</td>
						<td><?php echo $files['sugar'];?></td>
					</tr>
					<tr>
						<td>Bilirubin</td>
						<td><?php echo $files['bilirubin'];?></td>
					</tr>
					<tr>
						<td>Ketone</td>
						<td><?php echo $files['ketone'];?></td>
					</tr>
					<tr>
						<td>Nitrite</td>
						<td><?php echo $files['nitrite'];?></td>
					</tr>

					<tr>
						<td>RBC</td>
						<td><?php echo $files['microscopic_rbc'];?></td>
					</tr>
					<tr>
						<td>Pus Cell</td>
						<td><?php echo $files['pus_cell'];?></td>
					</tr>
					<tr>
						<td>Epithelial Cells</td>
						<td><?php echo $files['epithelial_cell'];?></td>
					</tr>
					<tr>
						<td>Mucus Thread</td>
						<td><?php echo $files['mucus_threads'];?></td>
					</tr>
					<tr>
						<td>Bacteria</td>
						<td><?php echo $files['bacteria'];?></td>
					</tr>
					<tr>
						<td>AMORPHOUS URATES / PHOSPHATES</td>
						<td><?php echo $files['amorphous_urates'];?></td>
					</tr>
					<tr>
						<td>hyaline</td>
						<td><?php echo $files['hyaline'];?></td>
					</tr>
					<tr>
						<td>fine granular</td>
						<td><?php echo $files['fine_granular'];?></td>
					</tr>
					<tr>
						<td>coarse granular</td>
						<td><?php echo $files['coarse_granular'];?></td>
					</tr>
					<tr>
						<td>rbc cast</td>
						<td><?php echo $files['rbc_cast'];?></td>
					</tr>
					<tr>
						<td>wbc cast</td>
						<td><?php echo $files['wbc_cast'];?></td>
					</tr>
					<tr>
						<td>calcium oxalates</td>
						<td><?php echo $files['calcium_oxalates'];?></td>
					</tr>
					<tr>
						<td>uric acid</td>
						<td><?php echo $files['uric_acid'];?></td>
					</tr>
					<tr>
						<td>hippuric acid</td>
						<td><?php echo $files['hippuric_acid'];?></td>
					</tr>

				<?php } elseif ($value['category'] == 'urine_chemistry') {?>
					<tr>
						<td>microalbumin</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['microalbumin_rf'] ."". $files['microalbumin_unit'] ;?></td>
						</span>
						<?php echo $files['microalbumin'];?></td>
					</tr>
				<?php } elseif ($value['category'] == 'coagulation_factor') {?>
					<tr>
						<td>fibrinogen</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext"><?php echo $files." ". $files['fibrinogen_rf'] ." ". $files['fibrinogen_unit']  ;?></span>
						<?php echo $files['fibrinogen'];?>
						</td>
					</tr>
					<tr>
						<td>bleeding time</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['bleeding_time'] ;?>
						</span>
						<?php echo $files['bleeding_time'] ;?></td>
					</tr>
					<tr>
						<td>clotting time</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['clotting_time'] ;?>
						</span>
						<?php echo $files['clotting_time'] ;?></td>
					</tr>
				<?php } elseif ($value['category'] == 'coagulation') {?>
					<tr>
						<td>prothrombin time</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['prothrombin_time_rf'] ."". $files['prothrombin_time_unit'] ;?>
						</span>
						<?php echo $files['prothrombin_time'] ;?></td>
					</tr>
					<tr>
						<td>control</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['control_rf'] ."". $files['control_unit'] ;?>
						</span>
						<?php echo $files['control'] ;?></td>
					</tr>
					<tr>
						<td>inr</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['inr_rf'] ."". $files['inr_unit'] ;?>
						</span>
						<?php echo $files['inr'];?></td>
					</tr>
					<tr>
						<td>percentage activity</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['percentage_activity_rf'] ."". $files['percentage_activity_unit'] ;?>	
						</span>
						<?php echo $files['percentage_activity'] ;?></td>
					</tr>
					<tr>
						<td>activated partial</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['activated_partial_rf'] ."". $files['activated_partial_unit'] ;?>
						</span>
						<?php echo $files['activated_partial'] ;?></td>
					</tr>
					<tr>
						<td>thromboplastin time</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['thromboplastin_time_rf'] ."". $files['thromboplastin_time_unit'] ;?>
						</span>
						<?php echo $files['thromboplastin_time'] ;?></td>
					</tr>
					<tr>
						<td>aptt control</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['aptt_control_rf'] ."". $files['aptt_control_unit'] ;?>
						</span>
						<?php echo $files['aptt_control'];?></td>
					</tr>
				<?php } elseif ($value['category'] == 'hematology') {?>
					<tr>
						<td>blood typing with rh</td>
						<td><?php echo $files['blood_typing_with_rh'] ;?></td>
					</tr>
					<tr>
						<td>blood_type</td>
						<td><?php echo $files['blood_type'] ;?></td>
					</tr>
					<tr>
						<td>rh_typing</td>
						<td><?php echo $files['rh_typing'] ;?></td>
					</tr>
					<tr>
						<td>reticulocyte count</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['reticulocyte_count_rf'] ."". $files['reticulocyte_count_unit'] ;?>	
						</span>
						<?php echo $files['reticulocyte_count'] ;?></td>
					</tr>
					<tr>
						<td>rbc</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['rbc_rf'] ."". $files['rbc_unit'] ;?>
						</span>
						<?php echo $files['rbc'] ;?></td>
					</tr>
					<tr>
						<td>hemoglobin</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['hemoglobin_rf'] ."". $files['hemoglobin_unit'] ;?>
						</span>
						<?php echo $files['hemoglobin'] ;?></td>
					</tr>
					<tr>
						<td>hematocrit</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['hematocrit_rf'] ."". $files['hematocrit_unit'] ;?>
						</span>
						<?php echo $files['hematocrit'];?></td>
					</tr>
					<tr>
						<td>mcv</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['mcv_rf'] ."". $files['mcv_unit'] ;?>
						</span>
						<?php echo $files['mcv'] ;?></td>
					</tr>
					<tr>
						<td>mch</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['mch_rf'] ."". $files['mch_unit'] ;?>
						</span>
						<?php echo $files['mch'] ;?></td>
					</tr>
					<tr>
						<td>mchc</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['mchc_rf'] ."". $files['mchc_unit'] ;?>
						</span>
						<?php echo $files['mchc'];?></td>
					</tr>
					<tr>
						<td>White Blood Cell</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['wbc_rf'] ."". $files['wbc_unit'] ;?>
						</span>
						<?php echo $files['wbc'];?></td>
					</tr>
					<tr>
						<td>granulocytes</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['granulocytes_rf'] ."". $files['granulocytes_unit'] ;?>
						</span>
						<?php echo $files['granulocytes'];?></td>
					</tr>
					<tr>
						<td>lymphocytes</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['lymphocytes_rf'] ."". $files['lymphocytes_unit'] ;?>
						</span>
						<?php echo $files['lymphocytes'];?></td>
					</tr>
					<tr>
						<td>monocytes</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['monocytes_rf'] ."". $files['monocytes_unit'] ;?>
						</span>
						<?php echo $files['monocytes'] ;?></td>
					</tr>
					<tr>
						<td>eosinophil</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['eosinophil_rf'] ."". $files['eosinophil_unit'] ;?>
						</span>
						<?php echo $files['eosinophil'] ;?></td>
					</tr>
					<tr>
						<td>basophils</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['basophils_rf'] ."". $files['basophils_unit'] ;?>
						</span>
						<?php echo $files['basophils'];?></td>
					</tr>
					<tr>
						<td>platelet count</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['platelet_count_unit'] ;?>
						</span>
						<?php echo $files['platelet_count'];?></td>
					</tr>
					<tr>
						<td>ESR < 50</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['esr_lessthan_50_unit'] ;?>
						</span>
						<?php echo $files['esr_lessthan_50'];?></td>
					</tr>
					<tr>
						<td>ESR > 50</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['esr_greaterthan_50_unit'] ;?>
						</span>
						<?php echo $files['esr_greaterthan_50'] ;?></td>
					</tr>

				<?php } elseif ($value['category'] == 'biochemistry') { ?>
					<tr>
						<td>Glucose (Fasting)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo  $files['bio_fasting_si_rf'] ."". $files['bio_fasting_si_unit'] ;?>
						</span>
						<?php echo $files['bio_fasting_si'] ;?></td>
					</tr>
					<tr>
						<td>Glucose (Random)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['bio_fasting_si_rf'] ."". $files['bio_fasting_si_unit'] ;?>
						</span>
						<?php echo $files['bio_random_si'] ;?></td>
					</tr>
					<tr>
						<td>BLOOD UREA NITROGEN</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['blood_urea_nitrogen_si_rf'] ."". $files['blood_urea_nitrogen_si_unit'] ;?>
						</span>
						<?php echo $files['blood_urea_nitrogen_si'];?></td>
					</tr>
					<tr>
						<td>CREATININE</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['creatinine_si_rf'] ."". $files['creatinine_si_unit'] ;?>
						</span>
						<?php echo $files['creatinine_si'];?></td>
					</tr>
					<tr>
						<td>SGOT</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['sgot_si_rf'] ."". $files['sgot_si_unit'] ;?>
						</span>
						<?php echo $files['sgot_si'];?></td>
					</tr>
					<tr>
						<td>SGPT</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['sgpt_si_rf'] ."". $files['sgpt_si_unit'] ;?>
						</span>
						<?php echo $files['sgpt_si'];?></td>
					</tr>
					<tr>
						<td>ALK. PHOSPHATASE</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['alk_phosphatase_si_rf'] ."". $files['alk_phosphatase_si_unit'] ;?>
						</span>
						<?php echo $files['alk_phosphatase_si'] ;?></td>
					</tr>
					<tr>
						<td>GGT (ENZYMATIC)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['ggt_si_rf'] ."". $files['ggt_si_unit'] ;?>
						</span>
						<?php echo $files['ggt_si'] ;?></td>
					</tr>
					<tr>
						<td>TOTAL BILIRUBIN</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['total_bilirubin_si_rf'] ."". $files['total_bilirubin_si_unit'] ;?>
						</span>
						<?php echo $files['total_bilirubin_si'];?></td>
					</tr>
					<tr>
						<td>DIRECT BILIRUBIN</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['direct_bilirubin_si_rf'] ."". $files['direct_bilirubin_si_unit'] ;?>
						</span>
						<?php echo $files['direct_bilirubin_si'];?></td>
					</tr>
					<tr>
						<td>INDIRECT BILIRUBIN</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['indirect_bilirubin_si_rf'] ."". $files['indirect_bilirubin_si_unit'] ;?>
						</span>
						<?php echo $files['indirect_bilirubin_si'];?></td>
					</tr>
					<tr>
						<td>TOTAL PROTEIN</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['total_protein_si_rf'] ."". $files['total_protein_si_unit'] ;?>
						</span>
						<?php echo $files['total_protein_si'];?></td>
					</tr>
					<tr>
						<td>ALBUMIN</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['albumin_si_rf'] ."". $files['albumin_si_unit'] ;?>
						</span>
						<?php echo $files['albumin_si'];?></td>
					</tr>
					<tr>
						<td>GLOBULIN</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['globulin_si_rf'] ."". $files['globulin_si_unit'] ;?>
						</span>
						<?php echo $files['globulin_si'];?></td>
					</tr>
					<tr>
						<td>A/G RATIO</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['ag_ratio_si_rf'] ."". $files['ag_ratio_si_unit'] ;?>
						</span>
						<?php echo $files['ag_ratio_si'];?></td>
					</tr>
					<tr>
						<td>LACTOSE DEHYDROGENASE</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo  $files['lactose_dehydrogenase_si_rf'] ."". $files['lactose_dehydrogenase_si_unit'] ;?>
						</span>
						<?php echo $files['lactose_dehydrogenase_si'] ;?></td>
					</tr>
					<tr>
						<td>INORGANIC PHOSPHATE</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['inorganic_phosphate_si_rf'] ."". $files['inorganic_phosphate_si_unit'] ;?>
						</span>
						<?php echo $files['inorganic_phosphate_si'];?></td>
					</tr>
					<tr>
						<td>BICARBONATE (ENZYMATIC)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['bicarbonate_si_rf'] ."". $files['bicarbonate_si_unit'] ;?>
						</span>
						<?php echo $files['bicarbonate_si'];?></td>
					</tr>
					<tr>
						<td>AMYLASE</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['amylase_si_rf'] ."". $files['amylase_si_unit'] ;?>
						</span>
						<?php echo $files['amylase_si'];?></td>
					</tr>
					<tr>
						<td>LIPASE</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['lipase_si_rf'] ."". $files['lipase_si_unit'] ;?>
						</span>
						<?php echo $files['lipase_si'] ;?></td>
					</tr>
					<tr>
						<td>CK TOTAL</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files2['ck_total_si_rf'] ."". $files2['ck_total_si_unit'] ;?>
						</span>
						<?php echo $files2['ck_total_si'];?></td>
					</tr>
					<tr>
						<td>CPK MM</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files2['cpk_mm_si_rf'] ."". $files2['cpk_mm_si_unit'] ;?>
						</span>
						<?php echo $files2['cpk_mm_si'] ;?></td>
					</tr>
					<tr>
						<td>CK - MB</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files2['ck_mb_si_rf'] ."". $files2['ck_mb_si_unit'] ;?>
						</span>
						<?php echo $files2['ck_mb_si'] ;?></td>
					</tr>
					<tr>
						<td>FRUCTOSAMINE</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files2['fructosamine_si_rf'] ."". $files2['fructosamine_si_unit'] ;?>
						</span>
						<?php echo $files2['fructosamine_si'];?></td>
					</tr>
					<tr>
						<td>HBA1C</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files2['hba1c_si_rf'] ."". $files2['hba1c_si_unit'] ;?>
						</span>
						<?php echo $files2['hba1c_si'] ;?></td>
					</tr>
					<tr>
						<td>LIPOPROTEIN (A)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files2['lipoprotein_si_rf'] ."". $files2['lipoprotein_si_unit'] ;?>
						</span>
						<?php echo $files2['lipoprotein_si'] ;?></td>
					</tr>
					<tr>
						<td>CHOLESTEROL</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files2['cholesterol_si_rf'] ."". $files2['cholesterol_si_unit'] ;?>
						</span>
						<?php echo $files2['cholesterol_si'] ;?></td>
					</tr><tr>
						<td>TRIGLYCERIDES</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files2['triglycerides_si_rf'] ."". $files2['triglycerides_si_unit'] ;?>
						</span>
						<?php echo $files2['triglycerides_si'];?></td>
					</tr>
					<tr>
						<td>HDL</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files2['hdl_si_rf'] ."". $files2['hdl_si_unit'] ;?>
						</span>
						<?php echo $files2['hdl_si'];?></td>
					</tr>
					<tr>
						<td>LDL</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files2['ldl_si_rf'] ."". $files2['ldl_si_unit'] ;?>
						</span>
						<?php echo $files2['ldl_si'];?></td>
					</tr>
					<tr>
						<td>VLDL</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files2['vldl_si_rf'] ."". $files2['vldl_si_unit'] ;?>
						</span>
						<?php echo $files2['vldl_si'];?></td>
					</tr>
					<tr>
						<td>TOTAL CHOLE:HDL</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files2['total_chole_hdl_si_rf'] ."". $files2['total_chole_hdl_si_unit'] ;?>
						</span>
						<?php echo $files2['total_chole_hdl_si'] ;?></td>
					</tr>
					<tr>
						<td>HDL:LDL</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo  $files['hdl_ldl_si_rf'] ."". $files2['hdl_ldl_si_unit'] ;?>
						</span>
						<?php echo $files2['hdl_ldl_si'] ;?></td>
					</tr>
					<tr>
						<td>TRIGLYCERIDES:HDL</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo  $files2['triglycerides_hdl_si_rf'] ."". $files2['triglycerides_hdl_si_unit'] ;?>
						</span>
						<?php echo $files2['triglycerides_hdl_si'];?></td>
					</tr>
					<tr>
						<td>SODIUM</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo  $files2['sodium_si_rf'] ."". $files2['sodium_si_unit'] ;?>
						</span>
						<?php echo $files2['sodium_si'];?></td>
					</tr>
					<tr>
						<td>POTASSIUM</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo  $files2['potassium_si_rf'] ."". $files2['potassium_si_unit'] ;?>
						</span>
						<?php echo $files2['potassium_si'];?></td>
					</tr>
					<tr>
						<td>CALCIUM</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files2['calcium_si_rf'] ."". $files2['calcium_si_unit'] ;?>
						</span>
						<?php echo $files2['calcium_si'];?></td>
					</tr>
					<tr>
						<td>CHLORIDE</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo  $files2['chloride_si_rf'] ."". $files2['chloride_si_unit'] ;?>
						</span>
						<?php echo $files2['chloride_si'] ;?></td>
					</tr>
					<tr>
						<td>MAGNESIUM</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo  $files2['magnesium_si_rf'] ."". $files2['magnesium_si_unit'] ;?>
						</span>
						<?php echo $files2['magnesium_si'] ;?></td>
					</tr>										
				<?php } elseif ($value['category'] == 'oral_glucose_challenge') { ?>
					<tr>
						<td>GLUCOSE (2ND HOUR)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['glucose_si_rf'] ."". $files['glucose_si_unit'] ;?>
						</span>
						<?php echo $files['glucose_si'] ;?></td>
					</tr>	
				<?php } elseif ($value['category'] == 'oral_glucose_tolerance') {?>
					<tr>
						<td>GLUCOSE (FASTING)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['fasting_si_rf'] ."". $files['fasting_si_unit'] ;?>
						</span>
						<?php echo $files['fasting_si'];?></td>
					</tr>
					<tr>
						<td>GLUCOSE (1ST HOUR)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['1st_hour_si_rf'] ."". $files['1st_hour_si_unit'] ;?>
						</span>
						<?php echo $files['1st_hour_si'];?></td>
					</tr>
					<tr>
						<td>GLUCOSE (2ND HOUR)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['2nd_hour_si_rf'] ."". $files['2nd_hour_si_unit'] ;?>
						</span>
						<?php echo $files['2nd_hour_si'] ;?></td>
					</tr>
				<?php } elseif ($value['category'] == 'thyroid') {?>
					<tr>
						<td>FT3 (CLIA)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['ft3_rf'] ."". $files['ft3_unit'] ;?>
						</span>
						<?php echo $files['ft3'] ;?></td>
					</tr>
					<tr>
						<td>FT4 (CLIA)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['ft4_rf'] ."". $files['ft4_unit'] ;?>
						</span>
						<?php echo $files['ft4'];?></td>
					</tr>
					<tr>
						<td>TSH (CLIA)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['tsh_rf'] ."". $files['tsh_unit'] ;?>
						</span>
						<?php echo $files['tsh'] ;?></td>
					</tr>
					<tr>
						<td>T3 REVERSE (CLIA)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['t3_reverse_rf'] ."". $files['t3_reverse_unit'] ;?>
						</span>
						<?php echo $files['t3_reverse'] ;?></td>
					</tr>
					<tr>
						<td>THYROGLOBULIN ANTIBODY (CLIA)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['thyroglobulin_antibody_rf'] ."". $files['thyroglobulin_antibody_unit'] ;?>
						</span>
						<?php echo $files['thyroglobulin_antibody'] ;?></td>
					</tr>
					<tr>
						<td>THYROID PEROXIDASE ANTIBODY (CLIA)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['thyroid_peroxidase_antibody_rf'] ."". $files['thyroid_peroxidase_antibody_unit'] ;?>
						</span>
						<?php echo $files['thyroid_peroxidase_antibody'] ;?></td>
					</tr>
				<?php } elseif ($value['category'] == 'hormones') { ?>
					<tr>
						<td>FSH (CLIA)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['fsh_rf'] ."". $files['fsh_unit'] ;?>
						</span>
						<?php echo $files['fsh'];?></td>
					</tr>
					<tr>
						<td>LH (CLIA)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo  $files['lh_rf'] ."". $files['lh_unit'] ;?>
						</span>
						<?php echo $files['lh'] ;?></td>
					</tr>
					<tr>
						<td>PROGESTERONE (CLIA)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo  $files['progesterone_rf'] ."". $files['progesterone_unit'] ;?>
						</span>
						<?php echo $files['progesterone'];?></td>
					</tr>
					<tr>
						<td>ESTRADIOL (CLIA)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['estradiol_rf'] ."". $files['estradiol_unit'] ;?>
						</span>
						<?php echo $files['estradiol'] ;?></td>
					</tr>
					<tr>
						<td>TESTOSTERONE (CLIA)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['testosterone_rf'] ."". $files['testosterone_unit'] ;?>
						</span>
						<?php echo $files['testosterone'] ;?></td>
					</tr>
					<tr>
						<td>TOTAL TESTOSTERONE (CLIA)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['total_testosterone_rf'] ."". $files['total_testosterone_unit'] ;?>
						</span>
						<?php echo $files['total_testosterone'];?></td>
					</tr>
					<tr>
						<td>FREE TESTOSTERONE (CLIA)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['free_testosterone_rf'] ."". $files['free_testosterone_unit'] ;?>
						</span>
						<?php echo $files['free_testosterone'];?></td>
					</tr>
					<tr>
						<td>SEX HORMONE BINDING GLOBULIN (SHBG) (CLIA)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['shbg_rf'] ."". $files['shbg_unit'] ;?>
						</span>
						<?php echo $files['shbg'] ;?></td>
					</tr>
					<tr>
						<td>CORTISOL (CLIA)td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['cortisol_rf'] ."". $files['cortisol_unit'] ;?>
						</span>
						<?php echo $files['cortisol'];?></td>
					</tr>
					<tr>
						<td>ALDOSTERONE (CLIA)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['aldosterone_rf'] ."". $files['aldosterone_unit'] ;?>
						</span>
						<?php echo $files['aldosterone'] ;?></td>
					</tr>
					<tr>
						<td>DIHYDROTESTOSTERONE (DHT) - RIA</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['dht_rf'] ."". $files['dht_unit'] ;?>
						</span>
						<?php echo $files['dht'];?></td>
					</tr>
					<tr>
						<td>SEROTONIN (ELISA)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['serotonin_rf'] ."". $files['serotonin_unit'] ;?>
						</span>
						<?php echo $files['serotonin'];?></td>
					</tr>
					<tr>
						<td>PREGNENOLONE (ELISA)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext"> 
							<?php echo $files['pregnenolone_rf'] ."". $files['pregnenolone_unit'] ;?>
						</span>
						<?php echo $files['pregnenolone'];?></td>
					</tr>
					<tr>
						<td>C-PEPTIDE (CLIA)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['c_peptide_rf'] ."". $files['c_peptide_unit'] ;?>
						</span>
						<?php echo $files['c_peptide'];?></td>
					</tr>
					<tr>
						<td>INSULIN ASSAY (CLIA) (FASTING)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['insulin_assay_fasting_rf'] ."". $files['insulin_assay_fasting_unit'] ;?>
						</span>
						<?php echo $files['insulin_assay_fasting'] ;?></td>
					</tr>
					<tr>
						<td>INSULIN ASSAY (CLIA) (POST PRANDIAL)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['post_prandial_rf'] ."". $files['post_prandial_unit'] ;?>
						</span>
						<?php echo $files['post_prandial'] ;?></td>
					</tr>
					<tr>
						<td>DEHYDROEPIANDROSTERONE SULFATE (DHEA-SO4) - (CLIA)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['dhea_so4_rf'] ."". $files['dhea_so4_unit'] ;?>
						</span>
						<?php echo $files['dhea_so4'] ;?></td>
					</tr>
					<tr>
						<td>INSULIN GROWTH FACTOR-1 (IGF-1) - (CLIA)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['igf_1_rf'] ."". $files['igf_1_unit'] ;?></td>
						</span>
						<?php echo $files['igf_1'];?></td>
					</tr>
					<tr>
						<td>INSULIN GROWTH FACTOR-BP3 (IGF-BP3) - (CLIA)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['igf_bp3_rf'] ."". $files['igf_bp3_unit'] ;?>
						</span>
						<?php echo $files['igf_bp3'] ;?></td>
					</tr>
					<tr>
						<td>OSTEOCALCIN (ELISA)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['osteocalcin_rf'] ."". $files['osteocalcin_unit'] ;?>
						</span>
						<?php echo $files['osteocalcin'];?></td>
					</tr>
				<?php } elseif ($value['category'] == 'homeostasis_ass_index') {?>
					<tr>
						<td>BETA CELL FUNCTION (% Β :)</td>
						<td><?php echo $files['beta_cell_function'] ;?></td>
					</tr>
					<tr>
						<td>INSULIN SENSITIVITY (% S :)</td>
						<td><?php echo $files['insulin_sensitivity'] ;?></td>
					</tr>
					<tr>
						<td>INSULIN RESISTANCE (IR :)</td>
						<td><?php echo $files['insulin_resistance'] ;?></td>
					</tr>
				<?php } elseif ($value['category'] == 'serology') { ?>
					<tr>
						<td>RHEUMATOID FACTOR</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo  $files['rheumatoid_factor_rf'] ."". $files['rheumatoid_factor_unit'] ;?>
						</span>
						<?php echo $files['rheumatoid_factor'];?></td>
					</tr>
					<tr>
						<td>C-REACTIVE PROTEIN (HCRP)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
						<?php echo $files['c_reactive_protein_rf'] ."". $files['c_reactive_protein_unit'] ;?></span>
						<?php echo $files['c_reactive_protein'];?>
							
						</td>
					</tr>
					<tr>
						<td>FERRITIN</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo  $files['ferritin_rf'] ."". $files['ferritin_unit'] ;?>
						</span>
						<?php echo $files['ferritin'] ;?></td>
					</tr>
					<tr>
						<td>ERYTHROPOIETIN</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['erythropoietin_rf'] ."". $files['erythropoietin_unit'] ;?>
						</span>
						<?php echo $files['erythropoietin'];?></td>
					</tr>
					<tr>
						<td>SERUM IMMUNOGLOBULIN E (IgE)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
						<?php echo $files['serum_immunoglobulin_rf'] ."". $files['serum_immunoglobulin_unit'] ;?>
						</span>
						<?php echo $files['serum_immunoglobulin'] ;?></td>
					</tr>
					<tr>
						<td>CMV (IgM)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['patient'] ." ". $files['cut_off'] ;?>
						</span>
						<?php echo $files['cmv'];?></td>
					</tr>
					<tr>
						<td>TP-HA</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['tp_ha_rf'] ."". $files['tp_ha_unit'] ;?>
						</span>
						<?php echo $files['tp_ha'];?></td>
					</tr>
				<?php } elseif ($value['category'] == 'tumor_markers') { ?>
					<tr>
						<td>BETA-HCG (CLIA)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['beta_hcg_rf'] ."". $files['beta_hcg_unit'] ;?>
						</span>
						<?php echo $files['beta_hcg'];?></td>
					</tr>
					<tr>
						<td>CEA (CLIA)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['cea_rf'] ."". $files['cea_unit'] ;?>
						</span>
						<?php echo $files['cea'];?></td>
					</tr>
					<tr>
						<td>AFP (CLIA)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['afp_rf'] ."". $files['afp_unit'] ;?>
						</span>
						<?php echo $files['afp'];?></td>
					</tr>
					<tr>
						<td>CA 19-9 (CLIA)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['ca_19_9_rf'] ."". $files['ca_19_9_unit'] ;?>
						</span>
						<?php echo $files['ca_19_9'];?></td>
					</tr>
					<tr>
						<td>CA 15-3 (CLIA)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['ca_15_3_rf'] ."". $files['ca_15_3_unit'] ;?>
						</span>
						<?php echo $files['ca_15_3'];?></td>
					</tr>
					<tr>
						<td>CA 72-4 (CLIA)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['ca_72_4_rf'] ."". $files['ca_72_4_unit'] ;?>
						</span>
						<?php echo $files['ca_72_4'];?></td>
					</tr>
					<tr>
						<td>CA 125 (CLIA)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['ca_125_rf'] ."". $files['ca_125_unit'] ;?>
						</span>
						<?php echo $files['ca_125'];?></td>
					</tr>
					<tr>
						<td>CYFRA 21-1 </td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['cyfra_21_1_rf'] ."". $files['cyfra_21_1_unit'] ;?>
						</span>
						<?php echo $files['cyfra_21_1'];?></td>
					</tr>
					<tr>
						<td>CYFRA 21-1 (CLIA)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['yfra_21_1_clia_rf'] ."". $files['yfra_21_1_clia_unit'] ;?>
						</span>
						<?php echo $files['yfra_21_1_clia'];?></td>
					</tr>
					<tr>
						<td>TOTAL PSA (CLIA)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['total_psa_rf'] ."". $files['total_psa_unit'] ;?>
						</span>
						<?php echo $files['total_psa'];?></td>
					</tr>
					<tr>
						<td>FREE PSA (CLIA)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['free_psa_rf'] ."". $files['free_psa_unit'] ;?>
						</span>
						<?php echo $files['free_psa'] ;?></td>
					</tr>
					<tr>
						<td>FREE: TOTAL PSA RATIO</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['total_psa_ratio_rf'] ."". $files['total_psa_ratio_unit'] ;?>
						</span>
						<?php echo $files['total_psa_ratio'];?></td>
					</tr>

				<?php } elseif ($value['category'] == 'special_chem') { ?>
					<tr>
						<td>HOMOCYSTEINE</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['homocysteine_rf'] ."". $files['homocysteine_unit'] ;?>
						</span>
						<?php echo $files['homocysteine'];?></td>
					</tr>
					<tr>
						<td>NT-proBNP</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['NT_proBNP_rf'] ."". $files['NT_proBNP_unit'] ;?>
						</span>
						<?php echo $files['NT_proBNP'] ;?></td>
					</tr>
				<?php } elseif ($value['category'] == 'vita_nutri') { ?>
					<tr>
						<td>VITAMIN D 25 OH</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['vitamin_d_25_rf'] ."". $files['vitamin_d_25_unit'] ;?>
						</span>
						<?php echo $files['vitamin_d_25'] ;?></td>
					</tr>
					<tr>
						<td>VITAMIN B12</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['vitamin_b12_rf'] ."". $files['vitamin_b12_unit'] ;?>
						</span>
						<?php echo $files['vitamin_b12'];?></td>
					</tr>
					<tr>
						<td>FOLATE</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['folate_rf'] ."". $files['folate_unit'] ;?>
						</span>
						<?php echo $files['folate'];?></td>
					</tr>
				<?php } elseif ($value['category'] == 'viral_hepa') { ?>
					<tr>
						<td>HBs Ag</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo  $files['hbs_ag_patient'] ." ". $files['hbs_ag_cutoff'] ;?>
						</span>
						<?php echo $files['hbs_ag'];?></td>
					</tr>
					<tr>
						<td>ANTI-HBs</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['anti_hbs_patient'] ." ". $files['anti_hbs_cutoff'] ;?>
						</span>
						<?php echo $files['anti_hbs'];?></td>
					</tr>
					<tr>
						<td>ANTI- HBc IgM</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['anti_hbc_lgm_patient'] ." ". $files['anti_hbc_lgm_cutoff'] ;?>
						</span>
						<?php echo $files['anti_hbc_lgm'];?></td>
					</tr>
					<tr>
						<td>ANTI- HBc IgG</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['anti_hbc_lgg_patient'] ." ". $files['anti_hbc_lgg_cutoff'] ;?>
						</span>
						<?php echo $files['anti_hbc_lgg'];?></td>
					</tr>
					<tr>
						<td>Hbe Ag</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['hbe_ag_patient'] ." ". $files['hbe_ag_cutoff'] ;?>
						</span>
						<?php echo $files['hbe_ag'];?></td>
					</tr>
					<tr>
						<td>ANTI- Hbe</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['anti_hbe_patient'] ." ". $files['anti_hbe_cutoff'] ;?>
						</span>
						<?php echo $files['anti_hbe'];?></td>
					</tr>
					<tr>
						<td>ANTI- HCV</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['anti_hcv_patient'] ." ". $files['anti_hcv_cutoff'] ;?>
						</span>
						<?php echo $files['anti_hcv'] ;?></td>
					</tr>
					<tr>
						<td>ANTI- HAV IgM</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo  $files['anti_hav_lgm_patient'] ." ". $files['anti_hav_lgm_cutoff'] ;?>
						</span>
						<?php echo $files['anti_hav_lgm'];?></td>
					</tr>
					<tr>
						<td>ANTI- HAV IgG</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo  $files['anti_hav_lgg_patient'] ." ". $files['anti_hav_lgg_cutoff'] ;?>
						</span>
						<?php echo $files['anti_hav_lgg'];?></td>
					</tr>
				<?php } elseif ($value['category'] == 'hiv') { ?> 
					<tr>
						<td>HIV</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['hiv_patient'] ." ". $files['hiv_cutoff'] ;?>
						</span>

						<?php echo $files['hiv'];?></td>
					</tr>
				<?php } elseif ($value['category'] == 'eGFR') { ?>
					<tr>
						<td>ESTIMATED GLOMERULAR FILTRATION RATE (eGFR)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['egfr_rf'] ."". $files['egfr_unit'] ;?>
						</span>
						<?php echo $files['egfr'];?></td>
					</tr>
				<?php } elseif ($value['category'] == 'nutri_elements') { ?>
					<tr>
						<td>MAGNESIUM, RBC (ICPS)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['magnesium_rbc_rf'] ."". $files['magnesium_rbc_unit'] ;?>
						</span>
						<?php echo $files['magnesium_rbc'];?></td>
					</tr>
					<tr>
						<td>MERCURY, RBC (ADAS)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['mercury_rbc_rf'] ."". $files['mercury_rbc_unit'] ;?>
						</span>
						<?php echo $files['mercury_rbc'];?></td>
					</tr>
					<tr>
						<td>LEAD, RBC (GFAAS)</td>
						<td class="tooltip-rpc">
						<span class="tooltiptext">
							<?php echo $files['lead_rbc_rf'] ."". $files['lead_rbc_unit'] ;?>
						</span>
						<?php echo $files['lead_rbc'];?></td>
					</tr>
				<?php }?>
			</tbody>
		</table>	
		<button class="see-more-btn">See more </button>
		<button class="see-more-btn  see-less-btn">See less</button>
	</div>
	</li>
	<?php }}else {?>
		<h5 >No Laboratory Test</h5> 
	<?php } ?>
</ul>

<script>
	$('.see-more-btn').click(function(){
        $(this).parent().toggleClass('expanded');
    });
    $( ".drag-drop" ).disableSelection();
    $( ".drag-drop" ).sortable({
      placeholder: "ui-state-highlight"
    });
</script>