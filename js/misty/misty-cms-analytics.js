$(document).ready(function() {
	if (CI.page_uuid != '') {
		
		var dataObject = {'page_uuid':CI.page_uuid};
		
		var request = $.ajax(
		{
			type: "POST",
			url: '/index.php/site/record_page_visit/',
			data: dataObject,
			processData: true,
		}).done(function(html) {
			console.log(html);
		}).fail(function( jqXHR, textStatus ) {
			console.log(textStatus);
		});
	}
});