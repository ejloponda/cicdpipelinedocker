<script>
  $(function() {
      $('.tipsy-inner').remove();
      $("#edit_location_form").ajaxForm({
          success: function(o) {
              $('#edit_location_form_wrapper').modal('hide');
              window.location.hash = "location_list";
              reload_content("location_list");
          },
          beforeSubmit: function(o) {
          }
      });
  });
</script>
<div class="modal-dialog" style="width:40%; word-wrap:normal;">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title"><?php echo ($location['type'] == 'location' ? "Edit Location" : "Edit Type") ?> </h4>
    </div>
    <div class="modal-body">
        <form id="edit_location_form" method="post" action="<?php echo url('module_management/add_calendar_dropdown'); ?>">
         <input type="hidden" id="id" name="id" value="<?php echo $location['id']; ?>">
         <input type="hidden" id="type" name="type" value="<?php echo $location['type']; ?>">
        <ul id="form02">
          <li><?php echo ($location['type'] == 'location' ? "Location" : "Type") ?> Name<span>*</span></li>
          <li><input type="text" id="value" name="value" class="textbox validate[required] form_new_dosage_type_list" style="margin: 0px;" value="<?php echo $location['value'] ?>"></li>
        </ul>


        <section class="clear"></section>
          <ul id="form02">
            <li>Status</li>
            <li>
              <select name="status" id="status" class="select form_new_dosage_type_list" style="width: 100px;">
                <option value="Active" <?php echo ($location['status'] == "Active" ? "selected" : "") ?>>Active</option>
                <option value="Inactive" <?php echo ($location['status'] == "Inactive" ? "selected" : "") ?>>Inactive</option>
              </select>
            </li>
          </ul>
          
        <section class="clear"></section>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button class="btn btn-primary submit_button" onclick="$('#edit_location_form').submit();">Update</button>
    </div>
  </div>
</div>
