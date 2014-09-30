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
	
}