(function($) {
  
  'use strict';
  $(function() {
    
    if ($('#calendar').length) {
      $('#calendar').fullCalendar({
        header: {
          left: 'prev,next today',
          center: 'title',
          // right: 'month,basicWeek,basicDay',
          right: 'month',
        },
        eventClick: function(event, jsEvent, view) {
          console.log(event.title);
            $('#modalTitle').html(event.type);
            $('#modalBody').html(event.title);
            $('#eventUrl').attr('href',event.url);
            $('#event_data').modal();
      },
      
        defaultDate: new Date(),
        navLinks: true, // can click day/week names to navigate views
        editable: false,
        eventLimit: false, // allow "more" link when too many events
        events: data_holidays,
      
        
      },
      )
    }
  });
})(jQuery);