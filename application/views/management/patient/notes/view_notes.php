<div class="modal-dialog"> 
	<!--  style="width:60%; word-wrap:normal;" -->
	<script>
		$(function(){

			$(".btn-close-modal").on('click', function(){
				view_patient("<?php echo $patient['id']?>");
			})
			var patient_id = $("#patient_id").val();
			//alert(patient_id);
			loadNotes(patient_id);
			editNotes(patient_id);
			//loadCreditsHistory(patient_id);
			
		});


		function addNotes(){
			
			var notes = $("#notes").val();
			var patient_id = $("#patient_id").val();

			if( hasValue(notes)){
				$.post(base_url + "patient_management/savePatientNotes", {patient_id:patient_id, notes:notes}, function(o){
					loadNotes(patient_id);
					editNotes(patient_id);
					$("#notes").val("");
					$("#result_wrapper").html(o);
				});
			} 
		}

	function loadNotes(patient_id){
			$.post(base_url + "patient_management/loadNotes", {patient_id:patient_id}, function(o){
					$("#view_notes_form_wrapper").html(o);
				});
		}

	function editNotes(patient_id){
			$.post(base_url + "patient_management/editNotes", {patient_id:patient_id}, function(o){
					$("#edit_notes_form_wrapper").html(o);
				});
		}

	</script>
	<div class="modal-content">
		<div class="modal-header">
			<h3>Notes</h3>
		</div>
		<div class="modal-body">
			<div role="tabpanel">

			 	 <!-- Nav tabs -->
				<ul class="nav nav-tabs" role="tablist">
					
					<li role="presentation" class="active"><a href="#view_notes" aria-controls="view_notes" role="tab" data-toggle="tab">View Notes</a></li>
					<li role="presentation" ><a href="#add_notes" aria-controls="add_notes" role="tab" data-toggle="tab">Add Notes</a></li>
					<!-- <li role="presentation" ><a href="#edit_notes" aria-controls="edit_notes" role="tab" data-toggle="tab">Edit Notes</a></li> -->
				</ul>

				<!-- Tab panes -->
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane " id="add_notes">
						<br/>
						<input type="hidden" id="patient_id" value="<?php echo $patient['id'] ?>">
						<center>
							<div id="result_wrapper"></div>
								<textarea placeholder="Add Notes" id="notes" class="form-control add-note-textarea"></textarea>
							
							<button type="button" onclick="addNotes()" class="btn btn-success btn-xs">Add Note</button>	
						</center>
					</div>
					<div role="tabpanel" class="tab-pane active" id="view_notes">
						<br/>
						<div id="view_notes_form_wrapper"></div>
					</div>

					<!-- <div role="tabpanel" class="tab-pane" id="edit_notes">
						<br/>
						<div id="edit_notes_form_wrapper"></div>
					</div> -->
				</div>

			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default btn-close-modal" data-dismiss="modal">Close</button>
		</div>
	</div>
</div>