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
<div class="container-fluid">
	<h1>Humphree</h1>
	<div class="jumbotron">
		<div class="row">
			<div class="col-xs-2 col-sm-2 col-md-3"></div>
			<div class="col-xs-8 col-sm-8 col-md-6 hidden-xs">
<?php
if (! isset ( $_SESSION [MODULE] )) {
	echo '<h2>Login</h2></div>';
} else {
	echo '<h2>Welcome to Humphree!</h2></div>';
}
?>
	<div class="col-xs-2 col-sm-2 col-md-3"></div>
			</div>
			<div class="row">
				<div class="hidden-sm hidden-md hidden-lg col-xs-12 smallHeight"></div>
			</div>
			<div class="row">
				<div class="col-xs-2 col-sm-2 col-md-3"></div>
				<div class="col-xs-8 col-sm-8 col-md-6">
<?php
if (! isset ( $_SESSION [MODULE] )) {
	echo '<form data-toggle="validator" role="form" class="form-inline" method="post" action="Login">
					<div class="form-group">
						<label class="sr-only" for="email">Email</label> <input
							type="email" class="form-control" name="email" id="email" placeholder="Email" required>
					</div>
					<div class="form-group">
						<label class="sr-only" for="password">Password</label> <input
							type="password" class="form-control" name="password" id="password"
							placeholder="Password" minlength="8"  required>
					</div>
					<button type="submit" class="btn btn-primary">Login</button>
				</form>';
}
?>
			
		</div>
				<div class="col-xs-2 col-sm-2 col-md-3"></div>
			</div>
		</div>
	</div>
</div>