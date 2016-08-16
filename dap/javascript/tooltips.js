jQuery.noConflict();
jQuery(document).ready(function($) {

	$('a.tips').cluetip({width: '300px', showTitle: false, arrows: true, mouseOutClose: true});
	$('a.tipsW').cluetip({width: '500px', showTitle: false, arrows: true, mouseOutClose: true});
	$('a.title').cluetip({splitTitle: '|', mouseOutClose: true});
	$('a.sticky').cluetip({sticky: true, closePosition: 'title', arrows: true, mouseOutClose: true});
	$('a.stickyW').cluetip({sticky: true, closePosition: 'title', arrows: true, mouseOutClose: true, width: '500px'});
	$('a.stickyDW').cluetip({sticky: true, closePosition: 'title', arrows: true, mouseOutClose: false, width: '700px', cluezIndex: 5000});
	$('a.custom-width').cluetip({width: '200px', showTitle: true, mouseOutClose: true});

});