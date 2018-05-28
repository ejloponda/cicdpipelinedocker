<script>
	 $(function() {
        $('#notes_list_dt').dataTable( {
	        "bJQueryUI": true,
	        "sPaginationType": "full_numbers",
	        "bPaginate": false,
	        "bLengthChange": false,
	        "bFilter": true,
	        "bInfo": false,     
	        "bScrollCollapse": false
        });

      	$(".view_file").tipsy({gravity: 's'});
      	$('#notes_list_dt_length').hide();
      	$('#notes_list_dt_filter').hide();
      });

	$(".add_notes_form").live('click', function() {
		add_form_modal();
	});

	function add_form_modal() {
		var patient_id = $("#id").val();
		$.post(base_url + 'patient_management/addNotes',{patient_id:patient_id},function(o) {
			$('#add_notes_form_wrapper').html(o);
			$('#add_notes_form_wrapper').modal();
		});
	}

	function edit_patient_notes(id){
		$.post(base_url + 'patient_management/editNotes',{id:id},function(o) {
			$('#edit_notes_form_wrapper').html(o);
			$('#edit_notes_form_wrapper').modal();
		});
	}

	function delete_patient_notes(id){

		var confirm = new jBox('Confirm', {
		content: '<h3>Are you sure you want to delete note?</h3>',
		confirmButton: 'Yes',
		cancelButton: 'No',
		confirm: function(){
			$.post(base_url+"patient_management/deleteNotes", {id:id}, function(){
				openViewNotes("<?php echo $patient['id'] ?>");
			});	
		},
		cancel: function(){
			$("#medicine_claim").attr('checked', false);
		},
		animation: {open: 'tada', close: 'pulse'}
		});
		confirm.open();
	}

</script>
<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li>
				<?php if($patient_image){ ?>
					<img class="photoID" src="<?php echo BASE_FOLDER . $patient_image['base_path'] . $patient_image['filename'] . "." . $patient_image['extension']; ?>">
				<?php } else { ?>
					<img class="photoID" src="<?php echo BASE_FOLDER; ?>themes/images/photo.png">
				<?php } ?>
			</li>
			<li><h1>Patient ID: <?php echo $patient['patient_code'] ?></h1>
				<?php echo $patient['patient_name'] ?></li>
		</ul>
		
		<div class="clear"></div>
	</hgroup>
	<input type="hidden" id="id" value="<?php echo $patient['id'] ?>">
	<hgroup id="section-header">
		<h1>Patient Notes</h1>
		<?php if($pm_pi['can_add']){ ?>
			<button class="button01 add_notes_form">+Add Notes</button>
		<?php } ?>
		<div class="clear"></div>
	</hgroup>
	
 		
 		<table class="datatable table" id="notes_list_dt">
 			<thead>
 				<th style="width: 40%; text-align: center;">Notes</th>
 				<th style="width: 20%; text-align: center;">Date Added</th>
 				<?php if($pm_pi['can_update'] && $pm_pi['can_delete']){ ?>
 				<th style="width: 20%; text-align: center;"> Action </th>
 				<?php } ?>
 			</thead>
 			
 			<tbody>
 				
 				<?php
					foreach ($patient_notes as $key => $value) {
			 	?>
			 	<?php $createDate = new DateTime($value['date_created']); $date_created   = $createDate->format('M d, Y');?>
	 				<tr>
	 					<td><?php echo $value['notes'] ?></td>
	 					<td><?php echo $date_created ?></td>
	 					<?php if($pm_pi['can_update'] && $pm_pi['can_delete']){ ?>
	 					<td><a href="javascript: void(0);" onclick="javascript: edit_patient_notes(<?php echo $value['id'] ?>)" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a> | <a href="javascript:void(0);" onclick="javascript: delete_patient_notes(<?php echo $value['id'] ?>)" class="delete_user table_icon" original-title="Delete" data-id="<?php echo $value['id'] ?>"><i class="glyphicon glyphicon-trash"></i></a></td>
	 					<?php } ?>
	 				</tr>
 				<?php } ?>
 			</tbody>
 		</table>
 		<hr>
			
	<div class="clear"></div>
	
</section>

