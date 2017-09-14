<?php
/*
 * Authors:
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */
?>
<nav class="navbar navbar-default navbar-inverse">
	<div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed"
				data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"
				aria-expanded="false">
				<span class="sr-only">Toggle navigation</span> <span
					class="icon-bar"></span> <span class="icon-bar"></span> <span
					class="icon-bar"></span>
			</button>
			<a class="navbar-brand hidden-xs" href="<?php echo PATH; ?>Home">Humphree</a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse"
			id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
<?php
if (isset ( $_SESSION [MODULE] )) {
	echo '<li><a href="' . PATH . 'Dashboard">Dashboard</a></li>';
}
?> 
				
<?php
if (isset ( $_SESSION ['status'] )) {
	if ($_SESSION ['status'] == 'admin') {
		echo '<li><a href="' . PATH . 'Administration">Admin</a></li>';
	}
}
?> 
			</ul>
			<!--  Note:  To obtain easy access to CreateDB, uncomment this link while testing to reset and reseed the database. -->
			<ul class="nav navbar-nav navbar-right">
<?php
if (isset ( $_SESSION [MODULE] )) {
	echo '<li><a href="' . PATH . 'Test">Test</a></li>
		<li><a href="' . PATH . 'CreateDB">Create DB</a></li>';
} else {
	// Remove this on the Production Server
	echo '<li><a href="' . PATH . 'Test">Test</a></li>
		<li><a href="' . PATH . 'CreateDB">Create DB</a></li>';
}
?>			
			</ul>  
<?php
if (isset ( $_SESSION [MODULE] )) {
	echo '<ul class="nav navbar-nav navbar-right">
		<li><a href="' . PATH . 'ChangePassword">Change Password</a></li>
		<li><a href="' . PATH . 'Logout">Logout</a></li>
      </ul>';
} else {
	echo '<ul class="nav navbar-nav navbar-right">
		<li><a href="' . PATH . 'Sign-up">Sign-up</a></li>
		<li><a href="' . PATH . 'Sign-in">Sign-in</a></li>
      </ul>';
}
?> 
    </div>
		<!-- /.navbar-collapse -->
	</div>
	<!-- /.container-fluid -->
</nav>

<?php
?>