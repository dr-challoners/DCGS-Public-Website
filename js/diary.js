// Make the preview popup appear in place of the links menu when hovering over the widget
  function diaryPreview(text) {
    document.getElementById('diaryLinks').style.display = 'none';
    document.getElementById('diaryPreview').innerHTML = '<ul>';
    var previews = text.split('//');
    for (x in previews) {
      if (previews[x] != '') {
        document.getElementById('diaryPreview').innerHTML += '<li>' + previews[x] + '</li>';
      }
    }
    document.getElementById('diaryPreview').innerHTML += '</ul>';
    document.getElementById('diaryPreview').style.display = 'block';
  }
  function diaryLinks() {
    document.getElementById('diaryLinks').style.display='block';
    document.getElementById('diaryPreview').style.display='none';
  }
  function scaffoldDiary(d, s, data) {
    // Grabs a reference to the element with the ID of diaryCalendar and clears it.
        var diary = $("#diaryCalendar");
        diary.empty();
        
        // Adds the header and the forward/back links
        var month = $('<p/>', {class: 'month', text: d.format(' MMMM YYYY ')}).prepend(
          $('<a/>', {class: 'last', html: "&#171;", href: "#"}).click(function(){generateDiary(d.clone().subtract(1, 'month'), s);})
        ).append(
          $('<a/>', {class: 'next', html: "&#187;", href: "#"}).click(function(){generateDiary(d.clone().add(1, 'month'), s);})
        ).appendTo(diary);
        
        // Adds the Days
        var days = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
        var weekdays = $('<div/>', {class: 'weekdays'}).appendTo(diary);
        for (var day = 0; day < days.length; day++) {
          weekdays.append($('<p/>', {text: days[day]}));
        }
        
        // Grab the numerical of the 'd' Date = Start of Month (ISO, Monday = 1)
        var _day = d.clone().date(1).isoWeekday() - 1; // Now 0 = Monday
        var day_Date = d.clone().subtract(_day, 'days').startOf('day');
        var _weeks = 6;
        
        for (var week = 0; week < _weeks; week++) {
          var _week = $('<div/>', {class: 'week'}).appendTo(diary)
          for (var day = 0; day < 7; day++) {
            var _dayLink = $('<a/>', {text: day_Date.date(), href: day_Date.format('[/diary/]DD/MM/YYYY/')})
            var _day = $('<p/>').append(_dayLink).appendTo(_week);
            var _dayF = day_Date.format('YYYYMMDD');

            if (data) {
              var _d = data.events[_dayF];
              if (_d) {
                _day.addClass("event")
                var preview = "";
                $.each(_d, function(key, val){
                  if (preview) preview += "//";
                  preview += val.event;
                })
                _dayLink.attr("onmouseover", 'diaryPreview("' + preview + '");').attr("onmouseleave", 'diaryLinks();')
              }
            }
            
            if (day_Date.month() !== d.month()) _day.addClass("notMonth");
            if (_dayF == s.format('YYYYMMDD')) _day.addClass("selected");
            if (_dayF == moment().format('YYYYMMDD')) _day.addClass("today");
            
            // Do Events here
            day_Date.add(1, 'day');
          }
        }
  }
  function generateDiary(d, s) {
    // Builds the URL to grab the JSON-encoded events
    var jsonUrl = "/data_diary/data-" + d.format("YYYY") + "-" + d.format("MM") + ".json"
    // Makes an sync call (wrapped by JQuery)
    $.ajax({
      url: jsonUrl,
      dataType:'JSON',
      success:function(data){
        
        scaffoldDiary(d, s, data);
        /* $.each(data.events, function(key, val){
          var dt = new Date(key.substr(0,4) + "-" + key.substr(4,2) + "-" + key.substr(6,2));
          if (!isNaN(dt.getTime() ) ) {
            console.log(dt);
            $.each(val, function(key, val){
              console.log("Id: " + key);
              console.log("Event: " + val.event);
              if (val.timestart) {
               console.log("Start: " + val.timestart);
               if (val.timeend) {
                 console.log("End: " + val.timeend);
                }
              }
              if (val.otherdetails) {
               console.log("Details: " + val.otherdetails);
              }
            })
          }
        }) */
      },
      error:function(){
        scaffoldDiary(d, s);
      }
    });
  }