<?php include('themes/templates/header.php'); ?>
<script>
$(function() {
	$('#dp1').datetimepicker({
		// format: 'yyyy-MM-dd',
		format: 'MM-dd-yyyy',
		pickTime: false
	});
	$('#dp1').on('change.dp', function(e){
		$('#dp1').datetimepicker('hide');
	});
});
</script>

<input type="text" class="span2" value="02-16-2012" id="dp1">


<div id="main_wrapper_management">TEst</div>
<?php include('themes/templates/footer/user-footer.php'); ?>