<script>
	$(function() {
		$('.delete_case_photo').tipsy({gravity: 's'});
		imagePreview();
	});
</script>

<div class="table-wrapper">
	<table class="table table-bordered">
		
		<th style="text-align: center;">Filename</th>
		<th style="text-align: center;">Size</th>
		<th style="text-align: center;">Date Uploaded</th>
		<?php if ($viewing_page == "false") { ?><th></th> <?php } ?>
		<?php if($files) { ?>
			<?php foreach($files as $key=>$value): ?>
			<?php 
				$image_path = BASE_FOLDER . "files/photos/tmp/"
			?>
				<tr>
					<td width="30%">
						<a href="<?php echo $image_path . $value['filename'] . "." . $value['extension']; ?>" class="preview"><?php echo $value['upload_filename']; ?></a>
					</td>
					<td width="30%"><?php echo Tool::formatSizeUnits($value['size']); ?></td>
					<td width="30%"><?php echo date("M d, Y h:i:s a",strtotime($value['date_created'])); ?></td>
					<?php if ($viewing_page == "false") { ?><td><a href="javascript:void(0);" class="delete_case_photo" original-title="Delete" onclick="javascript:delete_photo(<?php echo $value['id'] ?>);"><i class="glyphicon glyphicon-trash"></i></a></td><?php } ?>
				</tr>
			<?php endforeach;?>

			<?php 
				$file = Patient_Files::findAllByPatientIdCategory(array("patient_id" => $patient_id, "category_id" => 2));
			?>
 				<?php foreach ($file as $a => $b) { ?>		
 				<?php 
					$path = BASE_FOLDER . $b['base_path'] ."/".$b['filename'] . ".".$b['extension']
				?>
	 				<tr>
	 					<td width="30%"><a href="<?php echo $path; ?>" class="preview"><?php echo $b['upload_filename']; ?></a></td>
	 					<td width="30%"><?php echo Tool::formatSizeUnits($b['size']); ?></td>
	 					<td width="30%"><?php echo date("M d, Y h:i:s a",strtotime($b['date_created'])); ?></td>
	 				</tr>
 				<?php } ?>

		<?php } else { ?>
		<tr>
			<td colspan="5"><b>No uploaded files</b></td>
		</tr>
		<?php } ?>
	</table>
</div>

<style>
	img{border:none;}
</style>
