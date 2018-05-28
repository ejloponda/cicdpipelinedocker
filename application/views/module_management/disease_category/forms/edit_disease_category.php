<script>
  $(function() {
      $('.tipsy-inner').remove();
      $("#edit_disease_category_form").ajaxForm({
          success: function(o) {
              $('#edit_disease_category_form_wrapper').modal('hide');
              window.location.hash = "disease_category";
              reload_content("disease_category");
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
      <h4 class="modal-title">Edit Disease Category</h4>
    </div>
    <div class="modal-body">
        <form id="edit_disease_category_form" method="post" action="<?php echo url('module_management/add_disease_category'); ?>">
          <input type="hidden" id="id" name="id" value="<?php echo $disease['id']; ?>">
          <ul id="form">
              <li>Type</li>
              <li>
                <select name="page_category" id="page_category" class="select form_new_disease_category" style="width: 180px;">
                  <option value="Family" <?php echo ($disease['header_category'] == "Family" ? "selected" : "" ) ?>>Family Medical History</option>
                  <option value="Personal" <?php echo ($disease['header_category'] == "Personal" ? "selected" : "" ) ?>>Personal Medical History</option>
                </select>
              </li>
            </ul>
            
          <section class="clear"></section>
          
            <ul id="form02">
              <li>Name of Disease Category<span>*</span></li>
              <li><input type="text" id="disease_name" name="disease_name" class="textbox validate[required] form_new_disease_category" style="margin: 0px;" value="<?php echo $disease['disease_name'] ?>"></li>
            </ul>

          <section class="clear"></section>
            <ul id="form02">
              <li>Status</li>
              <li>
                <select name="status" id="status" class="select form_new_disease_category" style="width: 100px;">
                  <option value="Active" <?php echo ($disease['status'] == "Active" ? "selected" : "" ) ?>>Active</option>
                  <option value="Inactive" <?php echo ($disease['status'] == "Inactive" ? "selected" : "" ) ?>>Inactive</option>
                </select>
              </li>
            </ul>
            
          <section class="clear"></section>
        </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      <a href="javascript:void(0);" onclick="$('#edit_disease_category_form').submit();" class="btn btn-primary">Update</a>
    </div>
  </div><!-- /.modal-content -->
</div>