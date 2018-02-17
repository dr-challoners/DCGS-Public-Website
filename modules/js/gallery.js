$(document).ready(function() {

    // init Masonry
	var $gallery = $('.gallery').masonry({
	  	columnWidth: '.gallerySizer',
  		itemSelector: '.galleryItem',
  		percentPosition: true,
      transitionDuration: 0
	});
	// layout Masonry after each image loads
	$grid.imagesLoaded().progress( function() {
	  $grid.masonry('layout');
	});
    
});