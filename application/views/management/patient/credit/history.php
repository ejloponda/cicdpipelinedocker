<!-- <center>
<div style="overflow-y: auto; height: 200px;">
	<ul>
	<?php foreach ($history as $key => $value) { ?>
		<?php $user = User::findById(array("id" => $value['created_by'])) ?>
		<li>
			Remarks: <b><?php echo $value['remarks'] ?></b>
			<br/>Credit: <b><?php echo $value['credit'] ?></b>
			<br/>Type: <b><?php echo ucfirst($value['type']) ?></b>
			<br/>By: <b><?php echo $user['firstname'] . " " . $user['lastname'] ?></b>
			<br/>Time: <b><?php echo Tool::humanTiming(strtotime($value['date_created'])) ?> ago</b>
			<br/>---<br/>
		</li>
	<?php } ?>
	</ul>
</div>
</center> -->

<script>
	 $(function() {
        $('#credit_history_list_dt').dataTable( {
	        "bJQueryUI": true,
	        "sPaginationType": "full_numbers",
	        "bPaginate": true,
	        "bLengthChange": false,
	        "bFilter": false,
	        "bInfo": false,     
	        "bScrollCollapse": false,
			"iDisplayLength": 5,
        });

       $('#credit_history_list_dt_length').hide();
       $('#credit_history_list_dt_filter').hide();
       $('.edit_user_current_contact').tipsy({gravity: 's'});
       $('.delete_user_current_contact').tipsy({gravity: 's'});


	});
</script>
<table id="credit_history_list_dt" class="table datatable" style="width:100%">
    <thead>
	<tr>
		<th style="width: 80%; text-align: center;"><b>Remarks</b></th>
		<th style="width: 5%; text-align: center;"><b>Credit</b></th>
		<th style="width: 5%; text-align: center;"><b>Type</b></th>
		<th style="width: 5%; text-align: center;"><b>Added By</b></th>
		<th style="width: 5%; text-align: center;"><b>Date Added</b></th>
	</tr>
    </thead>
    <tbody>
    <?php foreach($history as $key=>$value): ?>
    	<?php $user = User::findById(array("id" => $value['created_by'])) ?>
    	<?php $date1 =new DateTime($value['date_created']);
		$date2  = $date1->format('M d, Y');?>
    	<tr>
    		
    		<td class="table_td_format"><?php echo $value['remarks'] ?></td>
	    	<td class="table_td_format"><?php echo $value['credit'] ?></td>
			<td class="table_td_format"><?php echo ucfirst($value['type']) ?></td>
			<td class="table_td_format"><?php echo $user['firstname'] . " " . $user['lastname'] ?></td>

			<td class="table_td_format"><?php echo $date2 ?></td>

		</tr>
    <?php endforeach; ?>
    </tbody>	
</table>
