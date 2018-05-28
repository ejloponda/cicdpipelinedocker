<script>
	 /*Start == Execute Calendar Plugin */

     var empty;
     var from_date =  <?php echo empty($from_date) ?'empty' : $from_date; ?>;
     var to_date =  <?php echo empty($to_date) ?'empty' : $to_date; ?>;
     var user =  <?php echo empty($user) ?'empty' : $user; ?>;
     
     if(from_date == null && user == null){
     
      var calendar = $('#calendar').fullCalendar({
        //Fetching Events from Database :)
        //events: base_url+'calendar_management/view',
         events: {
            url: base_url+'calendar_management/view',
            cache: true,
            async: true
        },

        timezone: 'local',
       //header and other values
        header: {
          left: 'today',
          center: 'prev,title,next',
          right: 'agendaWeek,agendaDay'
        },
        defaultView: 'agendaWeek',
        editable: true,
        selectable: true,
        allDaySlot: true,
        //timeFormat: 'h:mm-h:mma ', // the output i.e. "10:00-10:00pm"
        timeFormat: 'h:mma ',      // the output i.e. "10:00pm"
        
        default: true,
        displayEventEnd : true,
        droppable: true,
        minTime: '03:00:00',
        maxTime: '23:00:00',
        slotDuration: '00:30:00', 
        columnFormat: 'ddd DD',
        displayEventTime: false,
        axisFormat: 'h:mma',

        /* error: function (xhr, ajaxOptions, thrownError) {
           console.log(xhr.status);
           console.log(xhr.responseText);
           console.log(thrownError);
       },*/

        
        eventRender: function (event, element, view) {
          if(event.status == "Cancelled"){
            element.find('.fc-content').append('<span class="append_event" style="text-decoration: line-through; display: inline-block; font-size: 0.90em;">'+event.title+'</span>');
            element.find('.fc-title').hide();

            if(event.color == "#f7c478"){ //Rex
              element.find('.append_event').css( "color", "#a55f04" );
            }else if(event.color == "#a6f3a6"){// Event
              element.find('.append_event').css( "color", "#0a580a" );
            }else if(event.color == "#fccdce"){// Bri
              element.find('.append_event').css( "color", "#f76e71" );
            }else if(event.color == "#6ef7ec"){//Birthday
              element.find('.append_event').css( "color", "#165cab" );
            }else if(event.color == "#c1b36f"){ //Skype
              element.find('.append_event').css( "color", "#727000" );
            }else if(event.color == "#f37f7f"){//Blood
              element.find('.append_event').css( "color", "#d21414" );
            }else if(event.color == "#59d4f3"){//OtherMD
              element.find('.append_event').css( "color", "#0d6073" );
            }else if(event.color == "#c1c1c1"){//Vacation
              element.find('.append_event').css( "color", "#595959" );
            }else if(event.color == "#ffff42"){ //Meeting
              element.find('.append_event').css( "color", "#999900" );
            }else{
              element.find('.append_event').css( "color", "#000000" );
            }
          }

          if(event.id){
            if(event.color == "#f7c478"){ //Rex
              element.find('.fc-title').css( "color", "#a55f04" );
            }else if(event.color == "#a6f3a6"){// Event
              element.find('.fc-title').css( "color", "#0a580a" );
            }else if(event.color == "#fccdce"){// Bri
              element.find('.fc-title').css( "color", "#f76e71" );
            }else if(event.color == "#6ef7ec"){//Birthday
              element.find('.fc-title').css( "color", "#165cab" );
            }else if(event.color == "#c1b36f"){ //Skype
              element.find('.fc-title').css( "color", "#727000" );
            }else if(event.color == "#f37f7f"){//Blood
              element.find('.fc-title').css( "color", "#d21414" );
            }else if(event.color == "#59d4f3"){//OtherMD
              element.find('.fc-title').css( "color", "#0d6073" );
            }else if(event.color == "#c1c1c1"){//Vacation
              element.find('.fc-title').css( "color", "#595959" );
            }else if(event.color == "#ffff42"){ //Meeting
              element.find('.fc-title').css( "color", "#999900" );
            }else{
              element.find('.fc-title').css( "color", "#000000" );
            }
          }
        },
        /*eventRender: function (event, element, view) {
              element.find('.fc-title').append('<div><span style="font-size: 10px; margin-left: -48px;">'+event.description+'</span></div>');
          },*/
        
       //Add Event Show modal when onclick specific time in specific day
        select: function(start, end, allDay,description) {
          starttime = moment(start).format('ddd, MMM Do, h:mma ');
          endtime = moment(end).format('ddd, MMM Do, h:mma ');
          var mywhen = starttime + ' - ' + endtime;
          var all_Day = !start.hasTime() && !end.hasTime();
          var params = {start: start, end:end, mywhen: mywhen, allDay:all_Day};
          loadCalendarForm(0,params);
          // console.log(params);
        },

        //Adding tooltip
        eventMouseover: function(event, jsEvent, view) {
          $(jsEvent.target).attr('title', event.title);
        },

        //View Event
        eventClick: function(callEvent, jsEvent, view) {

          starttime = moment(callEvent.start).format('ddd, MMM Do, h:mma ');
          endtime = moment(callEvent.end).format('ddd, MMM Do, h:mma ');
          console.log(callEvent.start);
          var mywhen = starttime + ' - ' + endtime;
          var params = {start: callEvent.start, end:callEvent.end, mywhen: mywhen, allDay:callEvent.allDay};

          $.post(base_url+'calendar_management/view_event',{event_id: callEvent.id}, function(o){
              $("#viewEventModal").html(o);
              $("#viewEventModal").modal('show');
          });    
        },
        eventResize: function(event, delta, revertFunc) {
          var user_id = $("#user_id").val();

          if(user_id){
            var confirm = new jBox('Confirm', {
              content: '<h3>Are you sure about this changes?</h3>',
              confirmButton: 'Yes',
              cancelButton: 'No',
              confirm: function(){
               $.post(base_url + 'calendar_management/dragEventUpdate', {start: event.start.format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ'), end: event.end.format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ'), id: event.id, allDay:event.allDay}, function(o){
                    default_success_confirmation({message : o.message, alert_type: "alert-success"});
                },'json');
              },
              cancel: function(){
                revertFunc();
              },
              animation: {open: 'tada', close: 'pulse'}
            });
            confirm.open();
          } else {
            
            notifyError();
            revertFunc();
          }

        },
        eventDrop: function(event, delta, revertFunc) {
          var user_id = $("#user_id").val();
          if(user_id){
            var confirm = new jBox('Confirm', {
              content: '<h3>Are you sure about this changes?</h3>',
              confirmButton: 'Yes',
              cancelButton: 'No',
              confirm: function(){
                $.post(base_url + 'calendar_management/dragEventUpdate', {start: event.start.format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ'), end: event.end.format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ'), id: event.id, allDay:event.allDay}, function(o){
                    default_success_confirmation({message : o.message, alert_type: "alert-success"});
                },'json'); 
              },
              cancel: function(){
                revertFunc();
              },
              animation: {open: 'tada', close: 'pulse'}
            });
            confirm.open();
          } else {
            notifyError();
            revertFunc();
          }
        },
      });
      /*End == Execute Calendar Plugin */
    } else if(from_date && user == null) {   
  
      var date = new Date(parseInt(from_date, 10) * 1000);
      var from = date.toLocaleString();
      
      var calendar = $('#calendar').fullCalendar({
        //Fetching Events from Database :)
        
        events: base_url+"calendar/view2/" + from_date + "/" + to_date,
        defaultDate: from,
        timezone: 'local',
       //header and other values
        header: {
          left: 'today',
          center: 'prev,title,next',
          right: 'agendaWeek,agendaDay'
        },
        defaultView: 'agendaWeek',
        editable: true,
        selectable: true,
        allDaySlot: true,
        //timeFormat: 'h:mm-h:mma ', // the output i.e. "10:00-10:00pm"
        timeFormat: 'h:mma ',      // the output i.e. "10:00pm"
        
        default: true,
        displayEventEnd : true,
        droppable: true,
        minTime: '03:00:00',
        maxTime: '23:00:00',
        slotDuration: '00:30:00', 
        columnFormat: 'ddd DD',
        displayEventTime: false,
        axisFormat: 'h:mma',
        
        eventRender: function (event, element, view) {
          if(event.status == "Cancelled"){
            element.find('.fc-content').append('<span class="append_event" style="text-decoration: line-through; display: inline-block; font-size: 0.90em;">'+event.title+'</span>');
            element.find('.fc-title').hide();

            if(event.color == "#f7c478"){ //Rex
              element.find('.append_event').css( "color", "#a55f04" );
            }else if(event.color == "#a6f3a6"){// Event
              element.find('.append_event').css( "color", "#0a580a" );
            }else if(event.color == "#fccdce"){// Bri
              element.find('.append_event').css( "color", "#f76e71" );
            }else if(event.color == "#6ef7ec"){//Birthday
              element.find('.append_event').css( "color", "#165cab" );
            }else if(event.color == "#c1b36f"){ //Skype
              element.find('.append_event').css( "color", "#727000" );
            }else if(event.color == "#f37f7f"){//Blood
              element.find('.append_event').css( "color", "#d21414" );
            }else if(event.color == "#59d4f3"){//OtherMD
              element.find('.append_event').css( "color", "#0d6073" );
            }else if(event.color == "#c1c1c1"){//Vacation
              element.find('.append_event').css( "color", "#595959" );
            }else if(event.color == "#ffff42"){ //Meeting
              element.find('.append_event').css( "color", "#999900" );
            }else{
              element.find('.append_event').css( "color", "#000000" );
            }
          }

          if(event.id){
            if(event.color == "#f7c478"){ //Rex
              element.find('.fc-title').css( "color", "#a55f04" );
            }else if(event.color == "#a6f3a6"){// Event
              element.find('.fc-title').css( "color", "#0a580a" );
            }else if(event.color == "#fccdce"){// Bri
              element.find('.fc-title').css( "color", "#f76e71" );
            }else if(event.color == "#6ef7ec"){//Birthday
              element.find('.fc-title').css( "color", "#165cab" );
            }else if(event.color == "#c1b36f"){ //Skype
              element.find('.fc-title').css( "color", "#727000" );
            }else if(event.color == "#f37f7f"){//Blood
              element.find('.fc-title').css( "color", "#d21414" );
            }else if(event.color == "#59d4f3"){//OtherMD
              element.find('.fc-title').css( "color", "#0d6073" );
            }else if(event.color == "#c1c1c1"){//Vacation
              element.find('.fc-title').css( "color", "#595959" );
            }else if(event.color == "#ffff42"){ //Meeting
              element.find('.fc-title').css( "color", "#999900" );
            }else{
              element.find('.fc-title').css( "color", "#000000" );
            }
          }
        },
        /*eventRender: function (event, element, view) {
              element.find('.fc-title').append('<div><span style="font-size: 10px; margin-left: -48px;">'+event.description+'</span></div>');
          },*/
        
       //Add Event Show modal when onclick specific time in specific day
        select: function(start, end, allDay,description) {
          starttime = moment(start).format('ddd, MMM Do, h:mma ');
          endtime = moment(end).format('ddd, MMM Do, h:mma ');
          var mywhen = starttime + ' - ' + endtime;
          var all_Day = !start.hasTime() && !end.hasTime();
          var params = {start: start, end:end, mywhen: mywhen, allDay:all_Day};
          loadCalendarForm(0,params);
          // console.log(params);
        },

        //Adding tooltip
        eventMouseover: function(event, jsEvent, view) {
          $(jsEvent.target).attr('title', event.title);
        },

        //View Event
        eventClick: function(callEvent, jsEvent, view) {

          starttime = moment(callEvent.start).format('ddd, MMM Do, h:mma ');
          endtime = moment(callEvent.end).format('ddd, MMM Do, h:mma ');
          console.log(callEvent.start);
          var mywhen = starttime + ' - ' + endtime;
          var params = {start: callEvent.start, end:callEvent.end, mywhen: mywhen, allDay:callEvent.allDay};

          $.post(base_url+'calendar_management/view_event',{event_id: callEvent.id}, function(o){
              $("#viewEventModal").html(o);
              $("#viewEventModal").modal('show');
          });    
        },
        eventResize: function(event, delta, revertFunc) {
          var user_id = $("#user_id").val();

          //if(user_id == event.host){
          if(user_id){
            var confirm = new jBox('Confirm', {
              content: '<h3>Are you sure about this change?</h3>',
              confirmButton: 'Yes',
              cancelButton: 'No',
              confirm: function(){
               $.post(base_url + 'calendar_management/dragEventUpdate', {start: event.start.format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ'), end: event.end.format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ'), id: event.id, allDay:event.allDay}, function(o){
                    default_success_confirmation({message : o.message, alert_type: "alert-success"});
                },'json');
              },
              cancel: function(){
                revertFunc();
              },
              animation: {open: 'tada', close: 'pulse'}
            });
            confirm.open();
          } else {
            
            notifyError();
            revertFunc();
          }

        },
        eventDrop: function(event, delta, revertFunc) {
          var user_id = $("#user_id").val();
          //if(user_id == event.host){
          if(user_id){
            var confirm = new jBox('Confirm', {
              content: '<h3>Are you sure about this changes?</h3>',
              confirmButton: 'Yes',
              cancelButton: 'No',
              confirm: function(){
                $.post(base_url + 'calendar_management/dragEventUpdate', {start: event.start.format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ'), end: event.end.format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ'), id: event.id, allDay:event.allDay}, function(o){
                    default_success_confirmation({message : o.message, alert_type: "alert-success"});
                },'json'); 
              },
              cancel: function(){
                revertFunc();
              },
              animation: {open: 'tada', close: 'pulse'}
            });
            confirm.open();
          } else {
            notifyError();
            revertFunc();
          }
        },
      });
      $('.fc-clear').val('Shows only the event on dates selected');
      /*End == Execute Calendar Plugin */
     /* $('.fc-next-button').hide();
      $('.fc-prev-button').hide();*/
      // $('.fc-next-button').on('click', function(event) {
      //   loadCalendar();
      // });

      // $('.fc-prev-button').on('click', function(event) {
      //   loadCalendar();
      // });

      $('.fc-today-button').click(function() {
           loadCalendar();
      });
    } else if(from_date == null && user){
      var calendar = $('#calendar').fullCalendar({
        //Fetching Events from Database :)
        
        events: base_url+"calendar/view3/" + user + "/",
        defaultDate: from,
        timezone: 'local',
       //header and other values
        header: {
          left: 'today',
          center: 'prev,title,next',
          right: 'agendaWeek,agendaDay'
        },
        defaultView: 'agendaWeek',
        editable: true,
        selectable: true,
        allDaySlot: true,
        //timeFormat: 'h:mm-h:mma ', // the output i.e. "10:00-10:00pm"
        timeFormat: 'h:mma ',      // the output i.e. "10:00pm"
        
        default: true,
        displayEventEnd : true,
        droppable: true,
        minTime: '03:00:00',
        maxTime: '23:00:00',
        slotDuration: '00:30:00', 
        columnFormat: 'ddd DD',
        displayEventTime: false,
        axisFormat: 'h:mma',

        eventRender: function (event, element, view) {
          if(event.status == "Cancelled"){
            element.find('.fc-content').append('<span class="append_event" style="text-decoration: line-through; display: inline-block; font-size: 0.90em;">'+event.title+'</span>');
            element.find('.fc-title').hide();

            if(event.color == "#f7c478"){ //Rex
              element.find('.append_event').css( "color", "#a55f04" );
            }else if(event.color == "#a6f3a6"){// Event
              element.find('.append_event').css( "color", "#0a580a" );
            }else if(event.color == "#fccdce"){// Bri
              element.find('.append_event').css( "color", "#f76e71" );
            }else if(event.color == "#6ef7ec"){//Birthday
              element.find('.append_event').css( "color", "#165cab" );
            }else if(event.color == "#c1b36f"){ //Skype
              element.find('.append_event').css( "color", "#727000" );
            }else if(event.color == "#f37f7f"){//Blood
              element.find('.append_event').css( "color", "#d21414" );
            }else if(event.color == "#59d4f3"){//OtherMD
              element.find('.append_event').css( "color", "#0d6073" );
            }else if(event.color == "#c1c1c1"){//Vacation
              element.find('.append_event').css( "color", "#595959" );
            }else if(event.color == "#ffff42"){ //Meeting
              element.find('.append_event').css( "color", "#999900" );
            }else{
              element.find('.append_event').css( "color", "#000000" );
            }
          }

          if(event.id){
            if(event.color == "#f7c478"){ //Rex
              element.find('.fc-title').css( "color", "#a55f04" );
            }else if(event.color == "#a6f3a6"){// Event
              element.find('.fc-title').css( "color", "#0a580a" );
            }else if(event.color == "#fccdce"){// Bri
              element.find('.fc-title').css( "color", "#f76e71" );
            }else if(event.color == "#6ef7ec"){//Birthday
              element.find('.fc-title').css( "color", "#165cab" );
            }else if(event.color == "#c1b36f"){ //Skype
              element.find('.fc-title').css( "color", "#727000" );
            }else if(event.color == "#f37f7f"){//Blood
              element.find('.fc-title').css( "color", "#d21414" );
            }else if(event.color == "#59d4f3"){//OtherMD
              element.find('.fc-title').css( "color", "#0d6073" );
            }else if(event.color == "#c1c1c1"){//Vacation
              element.find('.fc-title').css( "color", "#595959" );
            }else if(event.color == "#ffff42"){ //Meeting
              element.find('.fc-title').css( "color", "#999900" );
            }else{
              element.find('.fc-title').css( "color", "#000000" );
            }
          }
        },
       /* eventRender: function (event, element, view) {
              element.find('.fc-title').append('<div><span style="font-size: 10px; margin-left: -48px;">'+event.description+'</span></div>');
          },*/
        
       //Add Event Show modal when onclick specific time in specific day
        select: function(start, end, allDay,description) {
          starttime = moment(start).format('ddd, MMM Do, h:mma ');
          endtime = moment(end).format('ddd, MMM Do, h:mma ');
          var mywhen = starttime + ' - ' + endtime;
          var all_Day = !start.hasTime() && !end.hasTime();
          var params = {start: start, end:end, mywhen: mywhen, allDay:all_Day};
          loadCalendarForm(0,params);
          // console.log(params);
        },

        //Adding tooltip
        eventMouseover: function(event, jsEvent, view) {
          $(jsEvent.target).attr('title', event.title);
        },

        //View Event
        eventClick: function(callEvent, jsEvent, view) {

          starttime = moment(callEvent.start).format('ddd, MMM Do, h:mma ');
          endtime = moment(callEvent.end).format('ddd, MMM Do, h:mma ');
          console.log(callEvent.start);
          var mywhen = starttime + ' - ' + endtime;
          var params = {start: callEvent.start, end:callEvent.end, mywhen: mywhen, allDay:callEvent.allDay};

          $.post(base_url+'calendar_management/view_event',{event_id: callEvent.id}, function(o){
              $("#viewEventModal").html(o);
              $("#viewEventModal").modal('show');
          });    
        },
        eventResize: function(event, delta, revertFunc) {
          var user_id = $("#user_id").val();

          //if(user_id == event.host){
          if(user_id){
            var confirm = new jBox('Confirm', {
              content: '<h3>Are you sure about this change?</h3>',
              confirmButton: 'Yes',
              cancelButton: 'No',
              confirm: function(){
               $.post(base_url + 'calendar_management/dragEventUpdate', {start: event.start.format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ'), end: event.end.format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ'), id: event.id, allDay:event.allDay}, function(o){
                    default_success_confirmation({message : o.message, alert_type: "alert-success"});
                },'json');
              },
              cancel: function(){
                revertFunc();
              },
              animation: {open: 'tada', close: 'pulse'}
            });
            confirm.open();
          } else {
            
            notifyError();
            revertFunc();
          }

        },
        eventDrop: function(event, delta, revertFunc) {
        
          var user_id = $("#user_id").val();
          //if(user_id == event.host){
          if(user_id){
            var confirm = new jBox('Confirm', {
              content: '<h3>Are you sure about this changes?</h3>',
              confirmButton: 'Yes',
              cancelButton: 'No',
              confirm: function(){
                $.post(base_url + 'calendar_management/dragEventUpdate', {start: event.start.format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ'), end: event.end.format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ'), id: event.id, allDay:event.allDay}, function(o){
                    default_success_confirmation({message : o.message, alert_type: "alert-success"});
                },'json'); 
              },
              cancel: function(){
                revertFunc();
              },
              animation: {open: 'tada', close: 'pulse'}
            });
            confirm.open();
          } else {
            notifyError();
            revertFunc();
          }
        },
      });
      $('.fc-clear').val('Shows only the event on dates selected');
      /*End == Execute Calendar Plugin */
     /* $('.fc-next-button').hide();
      $('.fc-prev-button').hide();*/
      // $('.fc-next-button').on('click', function(event) {
      //   loadCalendar();
      // });

      // $('.fc-prev-button').on('click', function(event) {
      //   loadCalendar();
      // });

      $('.fc-today-button').click(function() {
           loadCalendar();
      });
    }
</script>
<div id='calendar'></div>