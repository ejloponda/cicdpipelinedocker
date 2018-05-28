<div class="modal-dialog"> 
	<script>
		$(function(){
			$(".btn-close-modal").on('click', function(){
				view_upload_list();
			});
			
			$("#uploader").pluploadQueue({
				// General settings
				runtimes : 'html5',
				url : base_url + 'patient_management/postUploadManager?patient_id=<?php echo $patient_id ?>',
				max_file_size : '20mb',
				//chunk_size : '1mb',
				unique_names : true,
				multipart_params: { },

				// Resize images on clientside if we can
				//resize : {width : 320, height : 240, quality : 90},

				// Specify what files to browse for
				filters : [
					{title : "Image files", extensions : "jpg,gif,png"},
					{title : "PDF files", extensions : "pdf"},
				],
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

		    var uploader = $('#uploader').pluploadQueue();
		    uploader.bind('UploadComplete', function(){
		    	// console.log("test upload all success!");
		    	upload_file_form_edit_modal();
		    });
		    uploader.bind('BeforeUpload', function(){
	        	var box = new jBox('Modal', {
				    title: 'Attention!',
				    content: "Once finished uploading you will be redirected to another form."
				});

				box.open();

				setTimeout(function(){
					box.close();
				},2000)
	        });
		});
	</script>
	<div class="modal-content">
		<div class="modal-header">
			<h3>Upload File</h3>
		</div>
		<div class="modal-body">
			<form id="file_uploader_form" name="file_uploader_form" enctype="multipart/form-data">
				<input type="hidden" name="patient_id" id="patient_id" value="<?php echo $patient_id ?>">
				<div id="uploader" style="width:400px;"></div>
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default btn-close-modal" data-dismiss="modal">Close</button>
		</div>
	</div>
</div>