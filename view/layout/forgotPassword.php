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

<div class="container">
    <div class="jumbotron panel panel-default">
		<div class="row">
			<h2>Forgot Password</h2>
		</div>
		<form data-toggle="validator" role="form" method="post" action="DoForgotPassword">
			<div class="form-group">
				<label for="email">Enter your email address and we'll email you a new password.</label>
				<input type="email" class="form-control" name="email" id="email" placeholder="Email"  required>
			</div>
			<button type="submit" class="btn btn-primary">Send</button>
		</form>
	</div>
</div>