$(document).ready(function(){
	
$("#isbn").keydown(function(e) {
	 
		//retrieve price to pay (and other data)
    	if (e.keyCode == 9 || e.keyCode == 13) {
    		$.ajax({
				type: "POST",
				url: "process.php",
				async:false,
				dataType: "json",
				data: ({isbn: $('#isbn').val(), command:"add"}),
				success: function(data){console.log(data);
					$("#book-search").append('<tr><td>'+data.title+'</td><tr>
				}// success
			});// ajax
		}//keycode
});//isbn keydown

$("#emptyCart").click(function() {
	$.ajax({
				type: "POST",
				url: "process.php",
				async:false,
				dataType: "json",
				data: ({command:"emptyCart"}),
				success: function(data){
					
				}// success
			});// ajax
});//empty cart

});//ready function
