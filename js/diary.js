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
      _div.append("<p class='time'>" + fixture.timestart + " - " + fixture.timeend + "</p>");
      _div.append("<h3>" + fixture.title + "</h3>");
      _div.append("<hr>");
    }
    
	}
  <h4>Upcoming Google Developer Events</h4>

<div id="agenda"></div>

<script>
  function listEvents(root) {
    var feed = root.feed;
    var entries = feed.entry || [];
    var html = ['<ul>'];

    for (var i = 0; i < entries.length; ++i) {
      var entry = entries[i];
      var title = (entry.title.type == 'html') ? entry.title.$t : escape(entry.title.$t);
      var start = (entry['gd$when']) ? entry['gd$when'][0].startTime : "";	

      html.push('<li>', start, ' ', title, '</li>');
    }

    html.push('</ul>');
    document.getElementById("agenda").innerHTML = html.join("");
  }
</script>

<script src="https://www.google.com/calendar/embed?src=challoners.org_8h6ikktg6vv0haq6squ06r1je8%40group.calendar.google.com&ctz=Europe/London">
</script>

 // <a herf="https://www.google.com/calendar/embed?src=challoners.org_8h6ikktg6vv0haq6squ06r1je8%40group.calendar.google.com&ctz=Europe/London" </a herf>
	
}