
<h3><?php echo $file['title'] ?><br><small><?php echo $file['description'] ?></small></h3>

	
<div id="myfile_<?php echo $file['id'] ?>">
	<script>
		var extension = "<?php echo $file['extension'] ?>";
		if(extension == "pdf"){
			var file_id = "<?php echo $file['id'] ?>";
			var myPDF = new PDFObject({
			  url: "<?php echo BASE_FOLDER . $file['base_path'] . '/' . $file['filename'] . '.' . $file['extension']; ?>",
			  id: "myPDF",
			  width: "700px",
			  height: "500px",
			  pdfOpenParams: {
			    navpanes: 1,
			    statusbar: 0,
			    view: "FitH",
			    pagemode: "thumbs"
			  }
			}).embed("myfile_"+file_id);
		}
	</script>
	<?php if($file['extension'] != "pdf"){ ?>
		<img src="<?php echo BASE_FOLDER . $file['base_path'] . '/' . $file['filename'] . '.' . $file['extension']; ?>" style="width: 500px; height: 300px;">
	<?php } ?>
</div>
<br>
Time Created: <?php echo Tool::getHumanTimeDifference($file['date_created']) ?>
		