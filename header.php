<?php


//$msg->display();
 //include_once("analyticstracking.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf=8">
    <link rel="icon" href="images/favicon.png" size="16X16" type="image/png"> 
    <title>SellAText.net - <?php echo $title;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/respond.js"></script>
    
</head>
<body>
    <div class="row">
    <nav class="navbar navbar-default " role="navigation">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>

        <div class="collapse navbar-collapse navbar-right" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li><a href="cart.php">Sell Your Books</a></li>
                        <li><a href="condition.php">Condition Guide</a></li>
                        <li><a href="cart.php">Shopping Cart</a></li>
                        <li><a href="faq.php">FAQ </a></li>
                        <!--<li><a href="about.php">About Us </a></li>-->
    <?php if(!isset($_SESSION['user_id'])) {?>
		<li><a href="login.php">Log In</a></li>
	<?php } else { ?>
                <li><a href="accountSettings.php">My Account</a></li>
		<li><a href="logout.php">Log Out</a></li>
	<?php } ?>
                </ul>
          
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
  </div>
  <header class="row">
      <div class="logo col-lg-8 col-sm-7">
          
      <a href="index.php"><img  border="0" src='images/textbooks3.png' class="img-responsive pull-left"width="450px" height="300px" title="SellAText.net"></a>   
      <?php if(!isset($_SESSION['user_id'])) {?>
      <p><h1>Welcome to SellAText.net!!</h1></p>
    <?php } else {
    	echo "<p><h1>Welcome back to SellAText.net!!</h1></p>";
    }?>
         
     </div>
     
  </header>  