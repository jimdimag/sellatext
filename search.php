

<form class="form-horizontal" role="form" method="post" action="cart.php">                        
                 <h3>Enter the <a data-toggle="modal" data-target="#myModal">ISBN</a> number from the back of the text book 
                 and press the RETURN (ENTER) key.</h3>
                <div class="form-group">
    <div class="col-sm-5">
    	<input type="hidden" name="command" value="addItemToCart">
      <input type="text"  id="isbn" name="isbn" class="form-control" placeholder="ISBN">
    </div>
  </div>
                <p>Please be sure to refer to our <a href="condition.php"> conditioning guide</a> so you know what we will accept and what discounts may be taken due to the condition of the book.</p>
            </form>
           <!-- <p>Sorry....We are experiencing some difficulties.  Please try again later.  Thank you.</p>-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">ISBN Number</h4>
      </div>
      <div class="modal-body">
        <img src="images/isbn.png"  height="450px" width="450px">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->   