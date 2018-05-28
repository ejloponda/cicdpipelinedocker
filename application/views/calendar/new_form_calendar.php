 <script type="text/javascript">

    $(function(){
      $("#event_type option[value=<?php echo $event['type'] ?>]").attr('selected', 'selected');
      var color = $("#event_type").find(':selected').data('color');
      $("#color_events").val(color);
    });

    $('#submitButton').on('click', function(e){
      // We don't want this to act as a link so cancel the link action
      var patient = $('#patient_id').val();
      if(patient != 'null'){
         $("#patient_id").removeClass("validate[required]");
      }
      
      $('#event_form').submit();
      e.preventDefault();
      
    });

    $('#cancelButton').on('click', function(e){
      $('#event_form').trigger("reset"); 
    });

    $("#event_type").on('change', function(){
      color = $(this).find(':selected').data('color');
      $("#color_events").val(color);
      if(color == '#13f2df'){
        //$("#patient_id").addClass("validate[required]");
      }else{
        $("#patient_id").removeClass("validate[required]");
      }
    });

    $("#user_list").on('change', function(){
      patient_name = $(this).find(':selected').data('name');
      $("#patient_name").val(patient_name);

    });
    
    function doSubmit(o){
      $('#createEventModal #eventid').val('');
      var e_id = o.id;
      var e_eventname = o.event_name;
     
      $("#createEventModal").modal('hide');
      $('#createEventModal #eventid').val(e_id);
      $('#createEventModal #event_name').val(e_eventname);
      console.log($('#apptStartTime').val());
      console.log($('#').val());
      console.log($('#eventid').val());
      console.log($('#apptAllDay').val());
      console.log($('#patient_name').val());
      console.log($('#status').val(o.event_status));

      var color = $("#event_type").find(':selected').data('color');
      var name = $("#user_list").find(':selected').data('name');
    
      if(o.status == 'edit'){
          $("#calendar").fullCalendar( 'removeEvents', o.id );
          $('#calendar').fullCalendar('refetchEvents');
      }
      if (o.event_status == "Cancelled") {
        $(".fc-title").addClass('intro');
        $('#calendar').fullCalendar('refetchEvents');
      }
      
      $("#calendar").fullCalendar('renderEvent',
        {
            title: $('#event_name').val(),
            start:($('#apptStartTime').val()),
            end: ($('#apptEndTime').val()),
            id:($('#eventid').val()),
            allDay: ($('#apptAllDay').val() == "true"),
            color: color, // to change color
            description: $('#patient_name').val(),
            host: $('#host').val(),
            status:$('#status').val(),
        },true);
       $("#calendar").fullCalendar( 'removeEvents', event._id ); 
       $('#calendar').fullCalendar('refetchEvents');
       $('#event_form').trigger("reset");  
    }
   //Submit Event Form

    $("#event_form").validationEngine({scroll:false});
    $("#event_form").ajaxForm({
      success: function(o) {
        if(o.is_successful) {
          doSubmit(o);

          $(".select_btn").removeAttr('disabled');
         
          default_success_confirmation({message : o.message, alert_type: "alert-success"});

          setTimeout(function(){
            $("#alert_confirmation_wrapper").hide();
          },3000)
        
        } else {
          default_success_confirmation({message : o.message, alert_type: "alert-danger"});
        }
      $('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);

      },

      beforeSubmit : function(evt){      
       $('#createEventModal #eventid').val();
       $(".select_btn").attr('disabled',true);
      },
        dataType : "json"
    });

    $('#event_form').trigger("reset"); 
    $('#date_start').datepicker({
      format: 'yyyy-mm-dd'
    });
</script>
<div class="modal-content modal-calendar" id="edit-modal-form-calendar">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3><?php echo ($event_id ? "Edit Appointment" : "Create Appointment") ?></h3>
  </div>
  
  <div class="modal-body">
    <form id="event_form" method="post" action="<?php echo url('calendar_management/save_event') ?>" style="width: 100%;">
      <script>
        $(function() {
          var opts=$("#invitee").html(), opts2="<option></option>"+opts;
            $("#invitees_id").each(function() { var e=$(this); e.html(e.hasClass("placeholder")?opts2:opts); });
            $("#invitees_id").select2({allowClear: true, placeholder: "Select Invitees"});

            var opts=$("#patient").html(), opts2="<option></option>"+opts;
            $("#patient_id").each(function() { var e=$(this); e.html(e.hasClass("placeholder")?opts2:opts); });
            $("#patient_id").select2({allowClear: true, placeholder: "Select Patients"});

            <?php if($preload_invitees) { ?>
              var preload_data = <?php echo $preload_invitees; ?>;
              $('#invitees_id').select2('data', preload_data,true);
             
            <?php } ?>

            <?php if($preload_patients) { ?>
              var preload_data = <?php echo $preload_patients; ?>;
              $('#patient_id').select2('data', preload_data,true);
             
            <?php } ?>

            var removeCounter = 0;
            $("#invitees_id").on('change', function(e){
                if(e.removed){
                  // console.log(e.removed.tag_id);
                  var id = e.removed.tag_id;
                  var x = confirm("Remove this user to invitees? You must click the update button to take effect.");
                  if (x){
                    removeCounter++;
                    var html = '';
                    var html = '<input type="hidden" name="remove_users[]" value="'+id+'">';
                    /*var option = '<option value="'+id+'">'+e.removed.text+'</option>';;
                    $("#invitee optgroup").append(option);*/
                    $("#users_removed").append(html);
                  } else {
                    var data = $(this).select2('data');
                    data.push(e.removed);
                    $(this).select2('data', data );
                  }
                }
            });

            $("#patient_id").on('change', function(e){
                if(e.removed){
                  // console.log(e.removed.tag_id);
                  var id = e.removed.tag_id;
                  var x = confirm("Remove this user to patients? You must click the update button to take effect.");
                  if (x){
                    removeCounter++;
                    var html = '';
                    var html = '<input type="hidden" name="remove_patients[]" value="'+id+'">';
                    /*var option = '<option value="'+id+'">'+e.removed.text+'</option>';;
                    $("#invitee optgroup").append(option);*/
                    $("#patient_removed").append(html);
                  } else {
                    var data = $(this).select2('data');
                    data.push(e.removed);
                    $(this).select2('data', data );
                  }
                }
            });
            
        });
      </script>
      <div class="clear"></div>

      <?php #debug_array($event) ?>
      <ul class="calendar-modal-content">
         <input type="hidden" id="apptStartTime" name="start" />
         <input type="hidden" id="apptEndTime" name="end" />
         <input type="hidden" id="apptAllDay" name="allDay" value="<?php echo $event['allDay'] ?>"/>
         <input type="hidden" id="eventid" name="event_id" value="<?php echo $event['id'] ?>"/>
        <li><span class="lable-name">Event Name <span style="color:red;">*</span>: </span>
        <input type="text" name="event_name" id="event_name" class="textbox validate[required] input-data" value="<?php echo $event['title'] ?>"></li>
        <br/>

        <script>
          $(function() {
            var opts=$("#user_source").html(), opts2="<option></option>"+opts;
              $("#user_list").each(function() { var e=$(this); e.html(e.hasClass("placeholder")?opts2:opts); });
              $("#user_list").select2({allowClear: true});

          });
        </script>
        <!-- <li><span class="lable-name">Patient: </span> 
          <select id="user_list" name="patient_id" class="populate input-data" style= "width:260px"></select>
          <select id="user_source" style="display:none">
          <option value="0">-Select Patient-</option> 
            <?php foreach($patient as $key=>$value): ?>
              <option data-name="<?php echo $value['patient_name'] ?>" value="<?php echo $value['id']; ?>" <?php echo ($event['patient_id'] == $value['id'] ? "selected" : "") ?>><?php echo $value['firstname'] . " " . $value['lastname']; ?></option>
            <?php endforeach; ?>
          </select>
          <input type="hidden" name="patient_name" id="patient_name">
        </li> -->

        <li><span class="lable-name">Patient: </span>
          <select id="patient_id" name="patient[]" multiple class="populate input-data" style="width:100%;"></select>
          <select id="patient" class="" style="display:none">
          <optgroup label="Patients">
               <?php foreach($patient as $key=>$value): ?>
                <option value="<?php echo $value['id']; ?>"><?php echo $value['lastname'] .', '. $value['firstname'] ?></option>
              <?php endforeach; ?>
          </optgroup> 
          </select>
          <div id="patient_removed"></div>
        </li>
        <br>
        <script>
          $.datetimepicker.setLocale('en');
          $('#start_datetimepicker').datetimepicker({
            format: 'Y/m/d h:i A'
          });

          $('#end_datetimepicker').datetimepicker({
            format: 'Y/m/d h:i A'

          });
        </script>
        <?php 
          if($event_id){ 
            /*$start_date  = new DateTime($event['start_db']);
            $end_date    = new DateTime($event['end_db']);
            echo $start_date->format('D, M jS,Y h:ia - ');
            echo $end_date->format('D, M jS,Y h:ia');*/
            $start_date  = new DateTime($event['start_db']);
            $end_date    = new DateTime($event['end_db']);
            $new_startdate = $start_date->format('Y/m/d h:i A');
            $new_enddate   = $end_date->format('Y/m/d h:i A');
          } 
        ?>

        <li>
         <!--  <input id="date_start" name="date_start" class="textbox validate[required]"> -->
         <!-- <span class="controls controls-row" id="when" style="margin-top:5px; font-size:13px;"> -->
         <b>Start & End Date:</b> 
         <input id="start_datetimepicker" name="start_datetimepicker" class="input-data textbox" style="width: 140px!important; margin-left: 30px!important;" type="text" value="<?php echo $new_startdate?>">   
         <b style="margin-left: 15px!important;" >End Date</b> 
         <input id="end_datetimepicker" name="end_datetimepicker" class="input-data textbox" style="width: 140px!important; margin-left: 30px!important;" type="text" value="<?php echo $new_enddate?>">  
        </li>
        <br/>

        <li><span class="lable-name">Category: </span>
        <input type="text" name="category" class="textbox input-data" value="<?php echo $event['category'] ?>"></li>
        <br/>
        <script>
          /*$('#keywords_id').select2({tags:true,  placeholder: 'Add tags'});*/
          $('#tags').tagsInput();

        </script>
        <li><span class="lable-name">Keywords: </span>
        <input id= "tags" value="<?php echo $keywords ?>" name="keywords[]">
        <!-- <select id="keywords_id" name="keywords[]" multiple class="populate input-data" style="width:50%;" data-role="tagsinput"></select> -->

        </li>
        <br/>

        <li><span class="lable-name">Location: </span>
          <select name="location" class="textbox input-data">
            <?php foreach ($location as $key => $value) { ?>
              <option value="<?php echo $value['id'] ?>" <?php echo ($event['location'] == $value['id'] ? "selected" : "") ?>><?php echo $value['value'] ?></option>
            <?php } ?>
          </select>  
        </li>
        <br>

        <li><span class="lable-name">Details: </span>
        <textarea name="details" id="details" class="input-data text-area-input" placeholder="Add Details"><?php echo $event['details'] ?></textarea></li>
        <br/>

        <script>
            var val = "<?php echo $event['status']?>";
            $('#status option:contains(' + val + ')').prop({selected: true});
        </script>

        <li><span class="lable-name">Status: </span>
        <select id="status" name="status" class="textbox input-data">
          <option value="Other">Other</option>
          <option value="Tentative">Tentative</option>
          <option value="Confirmed" selected>Confirmed</option>
          <option value="Postponed">Postponed</option>
          <option value="Completed">Completed</option>
          <option value="Cancelled">Cancelled</option>
          <option value="Reconfirmed">Reconfirmed</option>
        </select></li>
        <br/>

        <li><span class="lable-name">Type: </span>
          <select id="event_type" name="event_type" class="textbox input-data">
            <?php foreach ($event_type as $key => $value) { ?>
              <option data-color="<?php echo $value['color'] ?>" value="<?php echo $value['id'] ?>"><?php echo $value['value'] ?></option>
            <?php } ?>
          </select>
          <input type="hidden" name="color_events" id="color_events">
        </li>
        <br/>

        <li><span class="lable-name">Invitees: </span>
          <select id="invitees_id" name="invitees[]" multiple class="populate input-data" style="width:100%;"></select>
          <select id="invitee" class="validate[required]" style="display:none">
          <optgroup label="Users">
               <?php foreach($user as $key=>$value): ?>
                <option value="<?php echo $value['id']; ?>"><?php echo $value['lastname'] .', '. $value['firstname'] ?></option>
              <?php endforeach; ?>
          </optgroup> 
          </select>
          <div id="users_removed"></div>
        </li>

        <br/>
        
      </ul>
      <div class="clear"></div>
      <div class="modal-footer calendar-modal-footer">
        <button type="button" class="btn btn-primary select_btn" id="submitButton"><?php echo ($event_id ? "Update Event" : "Create Event") ?></button>
        <button type="button" class="btn btn-default" id="cancelButton" data-dismiss="modal">Cancel</button>
        <input type="hidden" value="<?php echo $user_id ?>" id="host">
      </div>
    </form>
  </div> 
</div>