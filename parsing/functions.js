function boxOpen(divId,boxType) { //Opens and closes boxes
	  if(document.getElementById(divId).className.match(/(?:^|\s)open(?!\S)/)) { var open = 1; } // Check to see if the specific item is currently open
				
		var inputs = document.getElementsByName(boxType);
		for(var i = 0; i < inputs.length; i++) { // Close every box of the same type
			inputs[i].className = document.getElementById(divId).className.replace( /(?:^|\s)open(?!\S)/g , '' );
			}
				
		if(open != 1) { // Only open the selected item if it was originally closed
			document.getElementById(divId).className += " open";
			}
		}