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
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<script src="modernizr.js"></script>
<meta name="robots" content="noindex, nofollow" />
<title>Picnic</title>

<!-- Bootstrap -->
<link href="<?php echo PATH; ?>css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo PATH; ?>css/main.css" rel="stylesheet">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
			      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
			    <![endif]-->
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