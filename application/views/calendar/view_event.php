<div class="modal-content modal-calendar view-events-modal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3><?php echo ucfirst($event['title']) ?></h3>
  </div>
  <div class="modal-body">
  <div class="col-md-12">
    <b>PATIENT NAME:</b>
    <ul style="margin-top: 10px !important;">
        <?php foreach ($patient as $key => $value) { ?>
        <li style="margin-top: -10px !important;"> <?php echo $value['name'] ?></li>
        <?php } ?>
    </ul>    
     <!--  <p><?php echo $patient['patient_name'] ?></p>--><br> 
    <b>DATE & TIME:</b>
      <p>
        <?php 
         /* $start_date  = new DateTime($event['start_db']);
          $end_date    = new DateTime($event['end_db']);
          echo $start_date->format('D, M j,Y h:ia - ');
          echo $end_date->format('D, M j,Y h:ia');

          $time_update = new DateTime($event['datetime_updated']);
          $datetime_updated = $time_update->format('M j,Y h:ia');*/

          $start_date  = new DateTime($event['start_db']);
          $end_date    = new DateTime($event['end_db']);
          echo $start_date->format('D, M j,Y h:ia - ');
          echo $end_date->format('D, M j,Y h:ia');

          $time_update = new DateTime($event['datetime_updated']);
          $datetime_updated = $time_update->format('M j,Y h:ia');

          $date_updated = $time_update->format('M j,Y');
          $time_update = $time_update->format('h:ia');

          $datetime_created = new DateTime($event['datetime_created']);
          $date_created = $datetime_created->format('M j,Y');
          $time_created = $datetime_created->format('h:ia');
        ?>
      </p>
    </div>
    <div class="col-md-6 event-content">
    <b>STATUS:</b> 
       <div class="event-hldr" style="height:40px; overflow:hidden">
        <p><?php echo $event['status'] ?><b><?php echo ' - ('. $datetime_updated .')' ?></b></p><br>
      </div>
    </div>
    <div class="col-md-6 event-content">
    <b>TYPE:</b> 
      <div class="event-hldr" style="height:40px; overflow:hidden">
        <p><?php echo $type['value'] ?></p><br>
      </div>
    </div>
    <div class="col-md-12 event-content">
    <b>DETAILS:</b> 
      <div class="event-hldr">
        <p><?php echo $event['details'] ?></p><br>
      </div>
    </div>
    <div class="clear"></div>
    <div class="col-md-6 event-content">
    <b>LOCATION:</b> 
      <div class="event-hldr">
        <p><?php echo $location['value'] ?></p><br>
      </div>
    </div>
      <div class="col-md-6 event-content">
    <b>INVITEES:</b>
    <div class="event-hldr">
    <ul>
      <?php foreach ($invitees as $key => $value) { ?>

      <li> 
       <?php 
        if($value['id'] == $user_id){
          if(empty($value['status'])){
            echo "<span style='color: blue !important;'>" .$value['name']. "</span>"; 
            echo 
            '&nbsp; <a href="javascript: void(0);" id="status2" value="<?php echo $user_id;?>" class="edit_item_inv table_icon" original-title="Edit"><i class="glyphicon glyphicon-question-sign"></i></a>';
            /*echo '<button type="button" class="btn btn-primary select_btn" value="<?php echo $user_id;?>">Status</button>';*/
          }else {
            if ($value['status'] == 'Confirm') {
               echo "<span style='color: green !important;'>" .$value['name']. "</span>";
                echo 
            '&nbsp; <a href="javascript: void(0);" id="status2" value="<?php echo $user_id;?>" class="edit_item_inv table_icon" original-title="Edit"><i class="glyphicon glyphicon-question-sign"></i></a>';
            }elseif ($value['status'] == 'Decline') {
              echo "<span style='color: red !important;'>" .$value['name']. "</span>"; 
               echo 
            '&nbsp; <a href="javascript: void(0);" id="status2" value="<?php echo $user_id;?>" class="edit_item_inv table_icon" original-title="Edit"><i class="glyphicon glyphicon-question-sign"></i></a>';
            }else{
              echo "<span style='color: blue !important;'>" .$value['name']. "</span>";  
              echo '&nbsp; <a href="javascript: void(0);" id="status2" value="<?php echo $user_id;?>" class="edit_item_inv table_icon" original-title="Edit"><i class="glyphicon glyphicon-question-sign"></i></a>';
             /* echo $value['status'];*/
            }
          }
        }else {
           if(empty($value['status'])){
            echo "<span style='color: blue !important;'>" .$value['name']. "</span>"; 
            /*echo "haven't confirm yet";*/
           }else{
            echo "<span style='color: green !important;'>" .$value['name']. "</span>"; 
           /* echo $value['status'];*/
           }
        }

       ?>
         
     
      </li>
        <!-- <li><?php echo $value['name'] ?> - <?php echo ($value['status'] ? $value['status'] : ($value['id'] == $user_id ? '<button type="button" class="btn btn-primary select_btn" id="status2" value="<?php echo $user_id;?>">Status</button>' : "haven't confirm yet")) ?></li> -->
      <?php } ?>
    </ul>
      </div>
      </div>
       <!-- <div>
        <br> <br>legend <br> Blue - means No confirmation <br> Red- Decline <br>Green- Confirm</div> -->
        <div class="clear"></div>
      
      <div class="col-md-6 event-content">
        <b>CREATED:</b> 
          <div class="event-hldr">
            <p><?php echo $user['firstname'] .' '. $user['lastname'] ?><br><?php echo $date_created .' at '. $time_created?></p>
          </div>
      </div>
      <div class="col-md-6 event-content">
        <b>MODIFIED:</b> 
          <div class="event-hldr">
            <p><?php echo $date_updated .' at '. $time_update?></p>
          </div>
      </div>

      <div class="legend-wrapper">
          <p>Legend</p>
          <div><span class="legend-color blue"></span><label>No Confirmation</label></div>
          <div><span class="legend-color red"></span><label>Decline</label></div>
          <div><span class="legend-color green"></span><label>Confirm</label></div>
        </div>
       <div class="clear"></div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-primary select_btn" id="editBtn">Edit</button>
    <?php if($event_id) {?>
     <button type="button" class="btn btn-danger select_btn" id="deleteBtn">Delete</button>
     <?php } ?>
    <button type="button" class="btn btn-default" id="cancelBtn" data-dismiss="modal">Close</button>
  </div>
</div>


<script>
  $(function(){
    var event_id = <?php echo $event_id ?>;
    var user_id  = <?php echo $user_id ?>;
   
   
    $("#status2").on('click', function(e){
      //$.post(base_url+'calendar_management/invitees_status', {event_id: event_id, user_id:user_id}, function(o){
      var status_confirm = 'Confirm';
      var status_decline = 'Decline';

      var confirm = new jBox('Confirm', {
        content: '<h3>Confirmation to attend for the Event: <?php echo $event_title?>? </h3>',
        confirmButton: 'Confirm',
        cancelButton: 'Decline',
        confirm: function(){
         $.post(base_url+"calendar_management/invitees_status", {event_id:event_id, user_id:user_id, status_confirm:status_confirm}, function(){
               $("#viewEventModal").modal('hide');
          });         
        },
        cancel: function(){
          $.post(base_url+"calendar_management/invitees_status", {event_id:event_id, user_id:user_id, status_decline:status_decline}, function(){
               $("#viewEventModal").modal('hide');
          }); 
        },
        animation: {open: 'tada', close: 'pulse'}
      });
      confirm.open();
    });

    $("#editBtn").on('click', function(){
      $.post(base_url+'calendar_management/editEventForm', {event_id: event_id}, function(o){
        $("#viewEventModal").modal('hide');
        $("#createEventModal").html(o);
        $("#createEventModal").modal('show');
      });
    });

    /*Delete Event*/
    $('#deleteBtn').on('click', function(e){
      var id = event_id;
    
      var confirm = new jBox('Confirm', {
        content: '<h3>Are you sure you want to delete?</h3>',
        confirmButton: 'Yes',
        cancelButton: 'No',
        confirm: function(){
          $("#createEventModal").modal('hide');
          $.post(base_url+"calendar_management/deleteEvent", {id:id}, function(){
              $('#calendar').fullCalendar('removeEvents',id);
              $("#viewEventModal").modal('hide');
          }); 
        },
        cancel: function(){
          
        },
        animation: {open: 'tada', close: 'pulse'}
      });
      confirm.open();
    });
  });
</script>