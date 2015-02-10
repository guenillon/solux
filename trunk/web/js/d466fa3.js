$(document).ready(function() {
	$('.jpi_table_data_table').dataTable({
	    "language": {
		"url": "https://cdn.datatables.net/plug-ins/3cfcc339e89/i18n/French.json"
	    }
	});
	
	$(".clickableRow").click(function() {
        window.document.location = $(this).data("href");
  });
} );