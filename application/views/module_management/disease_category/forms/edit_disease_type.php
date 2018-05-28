<script>
  $(function() {
      $('.tipsy-inner').remove();
      $("#edit_disease_type_form").ajaxForm({
          success: function(o) {
              $('#edit_disease_type_form_wrapper').modal('hide');
              window.location.hash = "disease_type";
              reload_content("disease_type");
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
      <h4 class="modal-title">Edit Disease Type</h4>
    </div>
    <div class="modal-body">
        <form id="edit_disease_type_form" method="post" action="<?php echo url('module_management/add_disease_type'); ?>">
          <input type="hidden" id="id" name="id" value="<?php echo $disease['id']; ?>">
          <ul id="form">
              <li>Type</li>
              <li>
                <select name="disease_category_list" id="disease_category_list" class="select form_new_disease_category" style="width: 270px;">
                  <?php foreach ($disease_category as $key => $value) { ?>
                    <option value="<?php echo $value['id'] ?>" <?php echo ($disease['disease_category_name'] == $value['disease_name'] ? "selected" : "") ?>><?php echo $value['disease_name'] ?></option>
                  <?php } ?>
                </select>
              </li>
            </ul>
            
          <section class="clear"></section>
          
            <ul id="form02">
              <li>Name of Disease Category<span>*</span></li>
              <li><input type="text" id="disease_name" name="disease_name" class="textbox validate[required] form_new_disease_category" style="margin: 0px;" value="<?php echo $disease['type_name'] ?>"></li>
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
      <a href="javascript:void(0);" onclick="$('#edit_disease_type_form').submit();" class="btn btn-primary">Update</a>
    </div>
  </div><!-- /.modal-content -->
</div>