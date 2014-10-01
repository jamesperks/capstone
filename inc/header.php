<?php
error_reporting(-1);
session_start();
require('inc/dbinfo.php');
$this_page = basename($_SERVER['SCRIPT_NAME']);
$logged_in = $_SESSION['logged_in'];
if($logged_in) {
    $menu = '<li'.(($this_page == 'schedule.php') ? ' class="active"':NULL).'><a href="schedule.php">My Schedule</a></li>
    <li'.(($this_page == 'class.php') ? ' class="active"':NULL).'><a href="class.php">Add Class</a></li>
    <li'.(($this_page == 'editclasses.php') ? ' class="active"':NULL).'><a href="editclasses.php">Edit Classes</a></li>
    <li><a href="logout.php">Logout</a></li>';
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>NJIT Schedule Maker</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    
    <!-- Bootstrap core CSS -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
    
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
            
    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Lato%3A100%2C400%2C700%2C900%2C400italic%2C900italic%7CPT+Serif%3A400%2C700%2C400italic%2C700italic%7COpen+Sans&#038;ver=4.0' type='text/css' media='all' />

    <?php if($this_page == 'hours.php') echo '<script src="js/jquery.radios-to-slider.js"></script>
    
    <link rel="stylesheet" href="css/radios-to-slider.css">'; ?>
    
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <link rel="stylesheet" href="css/style.css">
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">NJIT Schedule Maker</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li<?php echo (($this_page == 'index.php' || $this_page == 'main.php') ? ' class="active"':NULL); ?>><a href="index.php">Home</a></li>
            <?php echo $menu; ?>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>