var NOTIFY_ENDPOINT = "php-includes/notify.php";

/*function PusherNotifier(channel, options) {
  options = options || {};
  
  this.settings = {
    eventName: 'notification',
    title: 'Notification',
    titleEventProperty: null, // if set the 'title' will not be used and the title will be taken from the event
    image: base_url + 'themes/jquery/gritter/images/notify.png',
    eventTextProperty: 'message',
    gritterOptions: {}
  };
  
  $.extend(this.settings, options);

  // this.settings = {
  //   eventName: 'notification',
  //   title: 'Test',
  //   eventTextProperty: 'message',
  // };
  
  var self = this;
  channel.bind(this.settings.eventName, function(data){ self._handleNotification(data); });// });
};
PusherNotifier.prototype._handleNotification = function(data) {
  /*var gritterOptions = {
   title: (this.settings.titleEventProperty? data[this.settings.titleEventProperty] : this.settings.title),
   text: data[this.settings.eventTextProperty].replace(/\\/g, ''),
   image: this.settings.image
  };
  
  $.extend(gritterOptions, this.settings.gritterOptions);
  
  $.gritter.add(gritterOptions);
  alert("test");
  new PNotify({
    title: "test title",
    text: data[this.settings.eventTextProperty].replace(/\\/g, ''),
    hide: false,
    buttons: {
        sticker: false
    }
  });
};*/


$(function(){
      
  var pusher = new Pusher('45aa8c5ecca116b2a369');
  //var pusher = new Pusher('e3c363e14534352d529b');
  var channel = pusher.subscribe('my_notifications');
  // var notifier = new PusherNotifier(channel);

  channel.bind("notification", function(data){
    new PNotify({
      title: data.title,
      text: data.message,
      type: data.type,
      //hide: false,
      delay: 1000,
      buttons: {
          sticker: false
      }
    });
  });
});


function send_notif(message, title, notif_type){
  $.ajax({
      url: base_url + NOTIFY_ENDPOINT,
      data: {"message": message, "title": title, "type": notif_type}
  });

  setTimeout(function(){
    $("#alert_confirmation_wrapper").hide();
  },3000);
  // clearTimeout(autoHide);
}

$(".show_myLastNotif").live('click', function(){
  // alert("test");
  $(this).trigger('pnotify.history-last');
});


$(".show_myLastNotif").tipsy({'gravity': 's'});