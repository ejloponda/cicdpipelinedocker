<div class="modal-dialog"> 
	<script>
		$(function(){

			$(".btn-close-modal").on('click', function(){
				view_upload_list();
			});

			$("#file_uploaded_edit_form").validationEngine({scroll:false});
			$('#file_uploaded_edit_form').ajaxForm({
				success:function(o) {
					if(o.is_successful) {
						$(".btn-close-modal").click();
	          			default_success_confirmation({message : o.message, alert_type: "alert-success"});
	          		} else {
	          			default_success_confirmation({message : o.message, alert_type: "alert-error"});
	          		}
				}, beforeSubmit: function(o) {
				}, dataType : "json"
			});
		});
		function view_file_uploaded(id){
			var bam = new jBox('Modal', {
				title: "Files",
				content: $("#myfile_"+id),
			});

			bam.open();
		}
	</script>
	<div class="modal-content">
		<div class="modal-header">
			<h3><?php echo $file['title'] ?></h3>
		</div>
		<div class="modal-body">
			<form id="file_uploaded_edit_form" method="post" action="<?php echo url('patient_management/fileUpdate') ?>">
					<ul id="form">
						<li>File</li>
						<li>
							<button type="button" class="btn btn-info" onclick="view_file_uploaded(<?php echo $file['id'] ?>)">View File</button>
						</li>
					</ul>
					<section class="clear"></section>
					<ul id="form">
						<li>Category</li>
						<li>
							<input type="hidden" value="<?php echo $file['id'] ?>" name="id">
							<select name="category_id" class="select" style="width: 180px;">
								<?php foreach ($categories as $key => $value) { ?>
									<option value="<?php echo $value['id'] ?>" <?php echo ($file['category_id'] == $value['id'] ? "selected" : "") ?>><?php echo $value['category_name'] ?></option>
								<?php } ?>
							</select>
						</li>
					</ul>

					<section class="clear"></section>

					<ul id="form02">
						<li>Title <span>*</span></li>
						<li><input type="text" name="title" class="textbox validate[required]" style="margin: 0px;" value="<?php echo $file['title'] ?>"></li>
					</ul>

					<section class="clear"></section>
					<ul id="form02">
						<li>Description</li>
						<li>
							<input type="text" name="description" class="textbox" style="margin: 0px;" value="<?php echo $file['description'] ?>">
						</li>
					</ul>

					<section class="clear"></section>

					<div style="display:none;" id="myfile_<?php echo $file['id'] ?>">
						<script>
							var extension = "<?php echo $file['extension'] ?>";
							if(extension == "pdf"){
								var file_id = "<?php echo $file['id'] ?>";
								var myPDF = new PDFObject({
								  url: "<?php echo BASE_FOLDER . $file['base_path'] . '/' . $file['filename'] . '.' . $file['extension']; ?>",
								  id: "myPDF",
								  width: "800px",
								  height: "600px",
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
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" onclick="$('#file_uploaded_edit_form').submit()" class="btn btn-success">Update</button>
			<button type="button" class="btn btn-default btn-close-modal" data-dismiss="modal">Close</button>
		</div>
	</div>
</div>