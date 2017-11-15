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
				<h1 class="display-4 text-center">Change Password</h1>
				<hr>

				<?php if (isset($_SESSION['error'])) { ?>
					<div class="alert alert-danger"><?php echo $_SESSION['error'] ?></div>
				<?php } ?>

				<form data-toggle="validator" role="form" method="post" action="<?php echo BASE . '/Administration/ChangePassword/' . $this->data['userID']; ?>">
					<div class="form-group">
						<label for="user">New password</label>
						<input type="password" class="form-control" name="password1" id="password1" placeholder="Password" required>
					</div>
					<div class="form-group">
						<label for="email">Confirm new password</label>
						<input type="password" class="form-control" name="password2" id="password2" placeholder="Confirm" required>
					</div>

					<button type="submit" name="update" id="update" class="btn btn-primary">Change</button>
				</form>
			</div>
		</div>
		<div class="col"></div>
	</div>
</div>

