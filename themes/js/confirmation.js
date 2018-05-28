function default_success_confirmation(options) {

	var alert_type  = options['alert_type'] || "";
	var message		= options['message'] || "No Message";
	$('#alert_confirmation_wrapper').fadeIn(500);
	var content = '<div class="alert '+alert_type+'"><button type="button" class="close" data-dismiss="alert">&times;</button><b>'+message+'</b></div>';
	$('#alert_confirmation_wrapper').html(content);
}