<?php
/*
 * Authors:
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 *
 * header.php
 */
?>
<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1 shrink-to-fit=no">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<script src="modernizr.js"></script>
<meta name="robots" content="noindex, nofollow" />
<title>Humphree - Buy and Sell Your Stuff</title>
<!-- Bootstrap via CDN -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
<!-- Material icons CSS -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">

<!-- Local Bootstrap-->
<link href="<?php echo BASE; ?>/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo BASE; ?>/css/main.css" rel="stylesheet">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
		      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		    <![endif]-->
<link rel="shortcut icon" href="<?php echo BASE; ?>/favicon.ico" type="image/x-icon">
<link rel="icon" href="<?php echo BASE; ?>/favicon.ico" type="image/x-icon">
</head>
<body>
<?php

if(isset($_SESSION['error'])){
	echo '<span class="error">' . $_SESSION['error'] . '</span>';
	$_SESSION['error'] = null;
}

if(isset($_SESSION['message'])){
	echo '<span class="message">' . $_SESSION['message'] . '</span>';
	$_SESSION['message'] = null;
}
?>

<div class="container-fluid">
    <div class="row">
        <!-- Left margin -->
        <div class="col"></div>
        <!-- Central column -->
        <div class="col-9">
            <a href="<?php echo BASE."/Home" ?>">
                <img src="<?php echo BASE; ?>/img/logo.png" class="logo img-fluid mx-auto d-block top-n-tail"  alt="Humphree logo">
            </a>
            <form class="navbar-form" role="search">
                <div class="input-group add-on">
                    <input class="form-control" placeholder="Search" name="srch-term" id="srch-term" type="text">
                    <div class="input-group-btn">
                        <button class="btn btn-default" type="submit">
                            <i class="material-icons md-light">search</i>
                        </button>
                    </div> <!-- end .input-group-btn -->
                </div> <!-- end .input-group .add-on -->
            </form>
        </div> <!-- end col-9 -->
        <!-- Right margin -->
        <div class="col"></div>
    </div> <!-- end .row -->
</div> <!-- end .container-fluid -->
