<script>
  $(function() {
      $('.tipsy-inner').remove();
      $("#edit_contact_information_form").ajaxForm({
          success: function(o) {
              
              $('#edit_contact_information_form_wrapper').modal('hide');
              contact_information_list("<?php echo $contact['user_id']; ?>","false");
          },
          beforeSubmit: function(o) {
          }
      });
  });

  function validate_extensions() {
    var contact_type = $('#edit_contact_information_type').val();
    if(contact_type == "Fax" || contact_type == "Work") {
      $('#edit_contact_information_extension').show();
    } else {  
       $('#edit_contact_information_extension').hide();
    }
  }
</script>
<div class="modal-dialog" style="width:35%; word-wrap:normal;">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">Edit Contact</h4>
    </div>
    <div class="modal-body">
        <form id="edit_contact_information_form" name="edit_contact_information_form" method="post" action="<?php echo url('admin/save_contact_information'); ?>">
        <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
        <input type="hidden" id="edit_contact_user_id" name="user_id" value="<?php echo $contact['user_id']; ?>">
        <div id="add_contact_information_wrapper"> 
            <ul class="contact">
              <li>
                <select id="edit_contact_information_type" class="select02" name="contact_type" onchange="javascript:validate_extensions();">
                  <option <?php echo ($contact['contact_type'] == "Mobile" ? 'selected="selected"' : ''); ?> value="Mobile">Mobile</option>
                  <option <?php echo ($contact['contact_type'] == "Work" ? 'selected="selected"' : ''); ?> value="Work">Work</option>
                  <option <?php echo ($contact['contact_type'] == "Home" ? 'selected="selected"' : ''); ?> value="Home">Home</option>
                  <option <?php echo ($contact['contact_type'] == "Fax" ? 'selected="selected"' : ''); ?> value="Fax">Fax</option>
                </select>
              </li>
              <li><input type="text" id="edit_contact_information_type_value" name="contact_value" class="textbox validate[required]" value="<?php echo strtolower($contact['contact_value']); ?>"></li>
              <li>
                <?php
                  if(strtolower($contact['contact_type']) != "work" && strtolower($contact['contact_type']) != "fax") { $display = "display:none;"; }
                ?>
                <input type="text" id="edit_contact_information_extension" name="contact_extension" class="textbox validate[required]" style="<?php echo $display; ?> width: 100px;" placeholder="Area Code" value="<?php echo $contact['extension']; ?>">
              </li>
            </ul>
          </div>
        </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      <a href="javascript:void(0);" onclick="$('#edit_contact_information_form').submit();" class="btn btn-primary">Update</a>
    </div>
  </div><!-- /.modal-content -->
</div>
