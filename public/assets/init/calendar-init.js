$(document).ready( function(){
  var cTime = new Date(), month = cTime.getMonth()+1, year = cTime.getFullYear();
	theMonths = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
	theDays = ["S", "M", "T", "W", "T", "F", "S"];
    events = [
      [
        "4/"+month+"/"+year, 
        'New Fridge',
        '#', 
        '#177bbb', 
        'Contents here1'
      ],
      [
        "4/"+month+"/"+year, 
        'New Fridge',
        '#', 
        '#1bbacc',
        'Contents here2'
      ],
      [
        "17/"+month+"/"+year,
        'New Fridge',
        '#cccc',
        '#fcc633', 
        'Contents here'
      ],
      [
        "19/"+month+"/"+year, 
        'A link', 
        'http://www.google.com', 
        '#e33244'
      ]
    ];
  $url = $('#calendar').data('url');
  $.getJSON($url,function(data){
    $('#calendar').calendar({
      months: theMonths,
      days: theDays,
      events:data,
      popover_options:{
        placement: 'top',
        html: true
      }
    });
  });
  $(document).on('click','.list-group-item', function(){
    //$(this).next($(this).attr('href')).collapse('toggle');
  })
});