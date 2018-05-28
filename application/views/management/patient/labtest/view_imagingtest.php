<input type="hidden" id="id" name="patient_id" value="<?php echo $patient_id?>">
<input type="hidden" name="labtest_id" value="<?php echo $labtest['id']?>">
<section class="area patient-dashboard-section">
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
				<h1 class="h1-rpc-header">Imaging Test: <?php echo $labtest['category']?></h1>
				<div class="rpc-image-test-image">
					<img src="<?php echo BASE_FOLDER . $data_labtest['base_path'] . '/' . $data_labtest['filename'] . '.' . $data_labtest['extension']?>">
				</div>

				<b><h5 class="h5-rpc-header">Description</h5></b>
				<p><?php echo $data_labtest['description'];?></p>
				<b><h5 class="h5-rpc-header">Interpretation</h5></b>
				<p><?php echo $data_labtest['interpretation'];?></p>

		</div>


	</div>
<div class="clear"></div>	
</section>

