
$(document).ready(function() {
	
$('#myModal1').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget); // Button that triggered the modal
  var img = button.data('whatever'); // Extract info from data-* attributes
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this); 
  modal.find('.modal-title').text('Mailing Label Tracking #' + img);
  modal.find('.modal-body img ').attr("src","tracking/label"+img+".gif");
});


