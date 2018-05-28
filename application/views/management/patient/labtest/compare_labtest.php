<section class="area patient-dashboard-section">
	<input type="hidden" id="id" value="<?php echo $id['id']?>">
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
		<div class="col-md-12 compare-test-wrapper with-border">
			<div class="header-top-wrapper">
				<div class="header-radio-holder">
					<h1>Compare Tests</h1>
						<div class="radio-test-type">
						<label for="labtest">
						  <input type="radio" id="labtest" name="type_of_test" value="1" checked/> Lab Test
						</label>
						<label for="imgtest">
						  <input type="radio" id="imgtest" name="type_of_test" value="0" /> Imaging Test
						</label>
					</div>
				</div>
				<a href="javascript: void(0);" onclick="javascript: view_all_test(<?php echo $id['id'] ?>)" class="def-btn view-records compare-test-btn">View All Tests</a>
				<a href="javascript: void(0);" onclick="javascript: add_labtest(<?php echo $id['id'] ?>)" class="def-btn">Add Test</a>
				<div class="select-test-wrapper">
					<label>Category</label>
					<select id="category-select" name="category" onchange="javascript: most_recent_test();">
						<option disabled selected >---</option>
					    <option value="urinalysis">Urinalysis</option>
					    <option value="urine_chemistry">Urine Chemistry</option>
					    <option value="coagulation_factor">Coagulation Factor</option>
					    <option value="coagulation">Coagulation</option>						    
					    <option value="hematology">Hematology</option>
					    <option value="biochemistry">Biochemistry</option>
					    <option value="oral_glucose_challenge">Oral Glucose Challenge Test</option>
					    <option value="oral_glucose_tolerance">Oral Glucose Tolerance Test</option>
					    <option value="thyroid">Thyroid Test</option>
					    <option value="hormones">Hormones Test</option>
					    <option value="homeostasis_ass_index">Homeostasis Assessment Index</option>
					    <option value="serology">Serology/Immunology</option>
					    <option value="tumor_markers">Tumor Markers</option>
					    <option value="special_chem">Special Chemistry</option>
					    <option value="vita_nutri">Vitamins and Nutrition</option>
					    <option value="viral_hepa">Viral Hepatitis Test</option>
					    <option value="hiv">HIV Test</option>
					    <option value="eGFR">Estimated Glomerular Filtration Rate (eGFR)</option>
					    <option value="nutri_elements">Nutrients Elements</option>
					</select>
				</div>
			</div>
			<div class="col-md-12 for-lab-test no-padding">
				<div class="col-md-12 text-display">
					<p><i>Choose a category to start comparing tests.</i></p>
				</div>
				
			</div>
			<div class="col-md-12 for-img-test no-padding compare-category-test-wrapper">
				
			</div>
		</div>
	</div>
	<div class="clear"></div>
</section>
<script type="text/javascript">
	most_recent_test();
	function most_recent_test(){
		var patient_id = parseInt($("#id").val());
		var filtered_by = $("#category-select").val();

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
	/*$('#category-select').change(function(){
            $('.compare-category-test-wrapper').hide();
            $('.text-display').hide();
            $('#' + $(this).val()).show();
        });*/
	 $(".radio-test-type input[name='type_of_test']").click(function() {
	     if ($("#labtest").is(":checked")) {
	       $('#category-select').empty().append(
	       	'<option disabled selected >---</option>',
	       	'<option selected value="urinalysis">Urinalysis</option>',
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
	       $('#category-select').empty().append('<option disabled selected >---</option>',
	       	'<option value="xray">Xray</option>',
	       	'<option value="ultrasound">Ultrasound</option>',);
	        $(".for-lab-test").hide();
	        $(".for-img-test").show();
	       most_recent_test();
	     }
	   });
	 $('.see-more-btn').click(function(){
        $(this).parent().toggleClass('expanded');
    });
    $( ".drag-drop" ).disableSelection();
    $( ".drag-drop" ).sortable({
      placeholder: "ui-state-highlight"
    });
</script>