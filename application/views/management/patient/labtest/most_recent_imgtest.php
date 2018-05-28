<ul class="drag-drop">
	<?php if(!empty($imaging_test)){ 
		foreach ($imaging_test as $key => $value) { 
		$file = Patient_Labtest_Xray::findByLabtestId(array("id" => $value['id']));
	?>				
	<li>
		<div class="tbl-recent-test-wrapper">
			<div class="sub-tbl-header">
				<h6><?php echo $value['category'];?></h6>
				<a href="javascript: void(0);" onclick="javascript: view_imagingtest(<?php echo $value['id']?>, <?php echo $id;?>)">view details</a>
			</div>
			<p class="test-date"> <?php  $date = new Datetime($value['date_of_test']); $date_generated = date_format($date, "M d, Y"); echo $date_generated;?></p> 
			<div class="img-wrapper-test">
				<img src="<?php echo BASE_FOLDER . $file['base_path'] . '/' . $file['filename'] . '.' . $file['extension']?>">
			</div>
		</div>
	</li>
	<?php }}else {?>
		<h5 >No Imaging Test</h5> 
	<?php } ?>
</ul>