<script>
	$(function() {
		var form_data;
		$("#uploader").pluploadQueue({
			// General settings
			runtimes : 'html5',
			url : base_url + 'patient_management/upload_disease_photo?patient_id=<?php echo $patient_id ?>&header_category=<?php echo $header_category ?>',
			max_file_size : '10mb',
			//chunk_size : '1mb',
			unique_names : true,
			multipart_params: { },

			// Resize images on clientside if we can
			//resize : {width : 320, height : 240, quality : 90},

			// Specify what files to browse for
			filters : [
				{title : "Image files", extensions : "jpg,gif,png"},
				{title : "Zip files", extensions : "zip"}
			],
			init : {
				 FileUploaded: function(up, file, info) {
	                // Called when file has finished uploading
	               console.log("uploaded");
	            }
			}
		});

		$('#file_uploader_form').submit(function(e) {
	        var uploader = $('#uploader').pluploadQueue();
	        // Files in queue upload them first
	        if (uploader.files.length > 0) {
	            // When all files are uploaded submit form
	            uploader.bind('StateChanged', function() {
	                if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
	                    $('form')[0].submit();
	                }
	            });
	                
	            uploader.start();
	        } else {
	            alert('You must queue at least one file.');
	        }

	        return false;
	    });
	 });
</script>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <br/>
		</div>
		<div class="modal-body">
			<div class="alert alert-danger bs-alert-old-docs">
			 	<strong>Warning</strong> : Don’t close this window till all photos are 100% uploaded.
			</div>
			<form id="file_uploader_form" name="file_uploader_form" enctype="multipart/form-data">
				<input type="hidden" name="patient_id" id="patient_id" value="<?php echo $patient_id ?>">
				<input type="hidden" name="header_category" id="header_category" value="<?php echo $header_category ?>">
				<div id="uploader" style="width:400px;"></div>
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
	</div>
</div>