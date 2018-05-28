<script>
	 $(function() {
        $('#contact_person_list_dt').dataTable( {
	        "bJQueryUI": true,
	        "sPaginationType": "full_numbers",
	        "bPaginate": false,
	        "bLengthChange": false,
	        "bFilter": true,
	        "bInfo": false,     
	        "bScrollCollapse": false
        });

       $('#contact_person_list_dt_length').hide();
       $('#contact_person_list_dt_filter').hide();
       $('.edit_user_current_contact').tipsy({gravity: 's'});
       $('.delete_user_current_contact').tipsy({gravity: 's'});


	});
</script>
<input type="hidden" id="patient_id" name="patient_id" value="<?php echo $patient_id; ?>">
<table id="contact_person_list_dt" class="table datatable" style="width:400px;">
    <thead>
	<tr>
		<?php if($view_only=="false") { ?><th style="width: 30%; text-align: center;"></th><?php } ?>
		<th style="width: 40%; text-align: center;">Contact Type</th>
		<th style="width: 30%; text-align: center;">Number</th>
		<th style="width: 8%; text-align: center;">Area Code</th>
	</tr>
    </thead>
    <tbody>
    <?php foreach($contact_person as $key=>$value): ?>
    <?php //if($value['contact_type'] && $value['contact_type_value'] && $value['contact_extension']) ?>
    	<tr>
    		<?php if($view_only=="false") { ?><td>
				<a href="javascript:void(0);" class="edit_user_current_contact" original-title="Edit" onclick="javascript:edit_contact_person(<?php echo $value['id']; ?>);"><i class="glyphicon glyphicon-edit"></i></a>
				<span style="margin: 0 5px 0 5px; text-align: center;"><img src="<?php echo BASE_FOLDER ?>themes/images/line.png"></span>
				<a href="javascript:void(0);" class="delete_user_current_contact" original-title="Delete" onclick="javascript:delete_contact_person_data(<?php echo $value['id']; ?>);"><i class="glyphicon glyphicon-trash"></i></a>
			</td><?php } ?>
    		<td class="table_td_format"><?php echo $value['contact_type']; ?></td>
	    	<td class="table_td_format"><?php echo $value['contact_value']; ?></td>
			<td class="table_td_format"><?php echo $value['extension']; ?></td>
		</tr>
    <?php endforeach; ?>
    </tbody>	
</table>
