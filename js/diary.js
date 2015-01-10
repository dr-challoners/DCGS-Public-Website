function __LoadGoogle(source, timeout) {
	Tabletop.init( { key: source, callback: __ShowGoogle, simpleSheet: false, parseNumbers: true } );
	// setTimeout("__LoadGoogle('" + source + "'," + timeout + ")", timeout);
}


function __ShowGoogle(data, tabletop) {

	// console.log("-- FIXTURES --");
	// console.log(tabletop.sheets("Fixtures"));
	__OutputFixtures(tabletop.sheets("Fixtures"));

}


function __OutputFixtures(fixtures) {
  
	for (i = 0; i < fixtures.elements.length; i++) {

		var fixture = fixtures.elements[i];

		console.log(fixture);
    
    		var fixture_Date = moment(fixture.date);
    		var _fixture_Date = fixture_Date.format("YYYYMMDD");
		var _div = $("#" + _fixture_Date);
		    if (_div) {
		      $("#" + _fixture_Date + " h2").removeClass("noevents");
		      $("#" + _fixture_Date + " hr").remove();
          
          var eventTime = "<p class='time'>" + fixture.timestart;
          if (fixture.timeend) {
            eventTime += " - " + fixture.timeend;
          }
          _div.append(eventTime);
          
          var eventTitle = "<h3>";
          if (fixture.sport) {
            eventTitle += fixture.sport;
          }
          if (fixture.sport && fixture.event) {
            eventTitle += ": ";
          }
          if (fixture.event) {
            eventTitle += fixture.event;
          }
          _div.append(eventTitle);
                   
          var matchLine = "<p class='details'>";
          if (fixture.venue) {
            matchLine += fixture.venue;
          }
          if (fixture.venue && (fixture.teams || fixture.results)) {
            matchLine += " - ";
          }
          if (fixture.teams) {
            var eventTeams = fixture.teams.split(",");
            var eventResults = fixture.results.split(",");
            for (var m = 0; m < eventTeams.length;) {
              matchLine += eventTeams[m].trim();
              if (m < eventResults.length && eventResults[m].trim()) {
                var scores = eventResults[m].trim().split("-");
                var outcome = "";
                if (scores[0] > scores[1]) { outcome = "won"; }
                if (scores[0] < scores[1]) { outcome = "lost"; }
                if (scores[0] === scores[1]) { outcome = "drew"; }
                if (outcome) {
                  matchLine += " <span id='" + outcome + "'>(" + outcome + " ";
                } else {
                matchLine += " <span>(";
                }
                matchLine += eventResults[m].trim() + ")</span>";
              }
              m++;
              if (m < eventTeams.length-1) {
                matchLine += ", ";
              } else if (m < eventTeams.length) {
                matchLine += " and ";
              }
            }
          } else if (fixture.results) {
            matchLine += "<span>" + fixture.results;
          }
          if (fixture.venue || fixture.teams || fixture.results) {
            _div.append(matchLine);
          }
          
          if (fixture.venuepostcode) {
            var eventMap = "<p class='details'><a href='https://www.google.co.uk/maps?q=" + fixture.venuepostcode + "' target='" + Math.random() + "'>See the location on Google Maps</a>";
            _div.append(eventMap);
          }
          
          if (fixture.otherdetails) {
            _div.append("<p class='details'>" + fixture.otherdetails);
          }
          
		      _div.append("<hr />");
		    }
    
	}
	
}
