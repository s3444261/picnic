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
	<div class="row">
		<div class="col"></div>
		<div class="col-9">
		    <div class="jumbotron panel panel-default">
		        <h1 class="display-4 text-center">Forgot Password</h1>
				<form data-toggle="validator" role="form" method="post" action="DoForgotPassword">
					<div class="form-group">
						<label for="email">Enter your email address and we'll email you a new password.</label>
						<input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
					</div>
					<button type="submit" class="btn btn-primary">Send</button>
				</form>
			</div>
		</div>
		<div class="col"></div>
	</div>
</div>
