<div class="modal-dialog"> 
	<script>
		$(function(){
			$(".btn-close-modal").on('click', function(){
				view_upload_list();
			});

			$(".delete_file_upload_btn").on('click', function(){
				var confirm = new jBox('Confirm', {
					title: 'Please confirm your action:',
					content: '<h3>Do you wish to delete this to File?</h3>',
					confirmButton: 'Yes',
					cancelButton: 'No',
					confirm: function(){
						var id = $(".delete_file_upload_btn").data("id");
						$.post(base_url + 'patient_management/deleteFileUpload',{id:id}, function(){
							$(".btn-close-modal").click();
						});
					},
					cancel: function(){
					},
					animation: {open: 'tada', close: 'pulse'}
				});
				confirm.open();
			});
		});
	</script>
	<div class="modal-content">
		<div class="modal-header">
			<h3><?php echo $file['title'] ?><br><small><?php echo $file['description'] ?></small></h3>
		</div>
		<div class="modal-body">			
			<div id="myfile_<?php echo $file['id'] ?>">
				<script>
					var extension = "<?php echo $file['extension'] ?>";
					if(extension == "pdf"){
						var file_id = "<?php echo $file['id'] ?>";
						var myPDF = new PDFObject({
						  url: "<?php echo BASE_FOLDER . $file['base_path'] . '/' . $file['filename'] . '.' . $file['extension']; ?>",
						  id: "myPDF",
						  width: "550px",
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
			Time Created: <strong><?php echo Tool::getHumanTimeDifference($file['date_created']) ?></strong><br>
			Time Updated: <strong><?php echo Tool::getHumanTimeDifference($file['date_updated']) ?></strong>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-info" onclick="edit_uploaded_file(<?php echo $file['id'] ?>)">Edit</button>
			<button type="button" class="btn btn-danger delete_file_upload_btn" data-id="<?php echo $file['id'] ?>">Delete</button>
			<button type="button" class="btn btn-default btn-close-modal" data-dismiss="modal">Close</button>
		</div>
	</div>
</div>