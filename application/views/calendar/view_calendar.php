<!-- <link href="<?php echo BASE_FOLDER; ?>themes/css/fullcalendar.print.css" rel="stylesheet" media="print"> -->
<link href="<?php echo BASE_FOLDER; ?>themes/css/fullcalendar2.print.css" rel="stylesheet" media="print">
<html moznomarginboxes mozdisallowselectionprint>
<script type="text/javascript">
  var fromdate = $('#from_date');
    fromdate.datepicker({
          format: "yyyy-mm-dd",
          startDate: '+1d',
          endDate: "-2d"
      })
    .on('changeDate', function(){
      var newDate = new Date(fromdate.val())
      newDate.setDate(newDate.getDate() + 6);
      var formattedDate = newDate.toJSON().slice(0,10);
      $('#to_date').val(formattedDate);
    });

  var todate = $('#to_date');
    todate.datepicker({
          format: "yyyy-mm-dd",
          startDate: '+1d',
          endDate: "-2d"
      })
    .on('changeDate', function(){
      var newDate = new Date(todate.val())
      newDate.setDate(newDate.getDate() - 6);
      var formattedDate = newDate.toJSON().slice(0,10);
      $('#from_date').val(formattedDate);
    });
 

  /*$('#from_date').datepicker({
      format: 'yyyy-mm-dd'
       });*/
  $(document).ready(function() {
    $('.calendar_menu').addClass('hilited');
    $('.patient_menu').removeClass('hilited');
    $('.search-result').hide();
    
    loadCalendar();

   $('#calendar').fullCalendar('unselect');
       //$('#calendar').fullCalendar('refetchEvents');
   });


    function notifyError(){
      new PNotify({
          title: "Action not allowed!",
          text: "You are not allowed to do any changes in this event.",
          type: 'error',
          buttons: {
              sticker: false
          }
        });
    }
     
    /*LOAD FORM*/
    function loadCalendarForm(event_id,params){
      var event_id = parseInt(event_id);
      $("#createEventModal").html(default_ajax_loader);

      if(isNaN(event_id)){
        event_id = 0;
      }

      $.post(base_url+'calendar_management/loadEventForm', {event_id:event_id}, function(o){
        $("#createEventModal").html(o);
        
        var startdate_string = moment(params.start).format("YYYY/MM/DD hh:mm A");
        var enddate_string = moment(params.end).format("YYYY/MM/DD hh:mm A"); 
        if(event_id == 0){
            $('#createEventModal #apptStartTime').val(params.start);
            $('#createEventModal #apptEndTime').val(params.end);
            $('#createEventModal #type').val('new');
            $('#createEventModal #apptAllDay').val(params.allDay);
            $('#createEventModal #when').text(params.mywhen);
            $('#createEventModal #start_datetimepicker').val(startdate_string);
            $('#createEventModal #end_datetimepicker').val(enddate_string);
        }

        $('#createEventModal #when').text(params.mywhen);
        $("#createEventModal").modal('show');
      });
    }
    /* END LOAD FORM */
   
</script>
<section class="area calendar-area">
<div class="header-search-wrapper">
  <hgroup id="area-header" class="calendar-area-header">
    <ul class="page-title">
      <li><img style="width:37px; height:44px;" src="<?php echo BASE_FOLDER; ?>themes/images/header-calendar.png"></li>
      <li><h1>Calendar</h1></li>
      <input type="hidden" id="user_id" value="<?php echo $user_id ?>">      
    </ul>
    <!-- Search Calendar -->
    <div class="search-wrapper">
     <div class="input-group search-events-form">
              <div class="input-group-btn search-panel">
                  <button type="button" class="btn btn-default dropdown-toggle select-event-btn" data-toggle="dropdown">
                    <span id="search_concept" value="0">Filter by</span> <span class="caret"></span>
                  </button>
                   <ul class="dropdown-menu" role="menu">
                    <li><a href="#event" value="event">Event</a></li>
                    <li><a href="#patient" value="patient">Patient Name</a></li>
                    <li><a href="#category" value="category">Category</a></li>
                    <li><a href="#keyword" value="keyword">Keyword</a></li>
                    <li><a href="#date_range" value="date_range" class = "date_range">Date Range</a></li>
                    <li><a href="#user" value="<?php echo $user_id;?>" class = "user">User</a></li>
                    <li><a href="">Anything</a></li>
                  </ul>


              </div>
              <input type="hidden" name="search_param" value="all" id="search_param"> 
              <div class="input-box-wrapper">  
              <input type="text" class="form-control search_calendar" id="edValue" name="x" placeholder="Search term...">
              <input type="text" class="form-control from-date-input" id="from_date" name="from_date" placeholder="From..." style="display:none;">
              <input type="text" class="form-control to-date-input" id="to_date" name="to_date" placeholder="To..." style="display:none;">
              <script>
                $(function() {
                  var opts=$("#patient_source").html(), opts2="<option></option>"+opts;
                    $("#patient_list").each(function() { var e=$(this); e.html(e.hasClass("placeholder")?opts2:opts); });
                    $("#patient_list").select2({allowClear: true});
                });
              </script>
              <div class="user_wrapper" style="display:none; float: left;">
                <select id="patient_list" name="patient_id" class="populate user-filter"></select>
                <select id="patient_source" class="" style="display:none">
                  <option value="0">Select Users</option>
                    <?php foreach($user as $key=>$value): ?>
                      <option value="<?php echo $value['id']; ?>"><?php echo $value['firstname'] . " " . $value['lastname']; ?></option>
                  <?php endforeach; ?>
                </select>
             </div>
             <div class="search-result"></div>
             </div>
              <span class="input-group-btn search">
                  <button class="btn btn-default search-event-btn" type="button" style="height: 34px;"><span class="glyphicon glyphicon-search search_btn"></span></button>
              </span>

              <span class="input-group-btn search_date_range" style="display:none;">
                  <button class="btn btn-default" type="button" style="height: 34px;"><span class="glyphicon glyphicon-search search_date"></span></button>
              </span>
          </div>
          <div class="clear"></div>
          

    </div>
    <!-- End Search Calendar -->

    <div class="clear"></div>
  </hgroup>
</div>
  <div id='wrap'>
    <div id='calendar_wrapper'></div>
  </div>
<button class="printBtn hidden-print button01" style="font-size:18px">Print</button>
</section>
<section class="clear"></section>


<script src="<?php echo BASE_FOLDER.'themes/bootstrap/bootstrap.js' ?>"></script>
<script type="text/javascript">
$('.printBtn').on('click', function (){
    //$("#fc-time").removeAttr("data-start");
    $("#fc-time").attr("data-start", "");

    window.print();
  });
$(document).ready(function(e){
    $("select[id='patient_list']").live('change', function(){
      var user_id = $( "#patient_list option:selected" ).attr("value");
      //check_stock(med_id);
      $.post(base_url + "calendar_management/loadCalendarByUser", {user_id:user_id}, function(o){
        $('#calendar_wrapper').html(o);
      });
    });

    $('.search-panel .dropdown-menu').find('a').click(function(e) {
      $('.search-result').hide(); 
      e.preventDefault();
      var param = $(this).attr("href").replace("#","");
      var concept = $(this).text();
    
    if(concept == 'Date Range'){
      $('.search_calendar').val('');
      $('.search_calendar').hide(); 
      $('#from_date').show();
      $('#to_date').show();
      $('.search').hide(); 
      $('.search_date_range').show(); 
      $('.user_wrapper').hide();
    }else if(concept == 'User'){
      $('.search_calendar').hide(); 
      $('.user_wrapper').show();
      $('.search').hide(); 
      $('#from_date').hide();
      $('#to_date').hide();
      $('.search_date_range').hide(); 
    }else{
      $('.search_calendar').show(); 
      $('#from_date').hide();
      $('#to_date').hide();
      $('.search').show(); 
      $('.search_date_range').hide(); 
      $('.user_wrapper').hide();
    }
    $('.search-panel span#search_concept').text(concept);
    $('.input-group #search_param').val(param);
  });
});

/*$(".search_btn").on('click', function(event) {
  var filter_by = $('#search_param').val();
  var search_item = $(this).val();

  $('#search-result').html(default_ajax_loader);
   $.post(base_url + "calendar_management/search_filter", {filter_by:filter_by,search_item:search_item}, function(o){
     $('.search-result').html(o);
     $('.search-result').show();
   });
});*/

$('.search_calendar').blur(function(){
    if( $(this).val().length === 0 ) {
       $('.search-result').hide(); 
    }
});

$('.search_calendar').keypress(function(e){
  if(e.which == 13){//Enter key pressed
      //alert('Enter pressed: Submitting the form....');
      var filter_by = $('#search_param').val();
      var search_item = $(this).val();

      if($('.search_calendar').val().length === 0){
        alert("Please Enter a Word to Search");
       }else{
          $('#search-result').html(default_ajax_loader);
          $.post(base_url + "calendar_management/search_filter", {filter_by:filter_by,search_item:search_item}, function(o){
             $('.search-result').html(o);
             $('.search-result').show();
           });
       }

    
  }
});

$(".search-event-btn").on('click', function(event) {
   var search_word = $('.search_calendar').val();
   if($('.search_calendar').val().length === 0){
    alert("Please Enter a Word to Search");
   }else{

    var filter_by = $('#search_param').val();
    var search_item = $('.search_calendar').val();
    $('#search-result').html(default_ajax_loader);
     $.post(base_url + "calendar_management/search_filter", {filter_by:filter_by,search_item:search_item}, function(o){
       $('.search-result').html(o);
       $('.search-result').show();
     });
   }
});
/*$(".date_range").on('click', function(event) {
   $('.search_calendar').hide(); 
   $('#from_date').show();
   $('#to_date').show();
   $('.search').hide(); 
   $('.search_date_range').show(); 
});*/

$('.search_date_range').on('click', function(event) {
  var from_date = $('#from_date').val();
  var to_date   = $('#to_date').val();
  
  $.post(base_url + "calendar_management/loadCalendar", {from_date:from_date, to_date:to_date}, function(o){
    $('#calendar_wrapper').html(o);
  });
 
});

</script>
