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
	<h1>Edit User</h1>
	<div class="editUser">
		<form data-toggle="validator" role="form" class="form-inline"
			method="post" action="<?php echo PATH . 'Administration'; ?>">
			<div class="row">
				<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4"></div>
				<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 text-center">
					<div class="form-group">
						<label class="sr-only" for="user">Username</label> <input
							type="text" class="form-control" name="user" id="user"
							placeholder="Username"
							value="<?php if(isset($_SESSION['editUser'])){ echo $_SESSION['editUser']['user']; } ?>">
					</div>
				</div>
				<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4"></div>
			</div>
			<div class="row">
				<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4"></div>
				<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 text-center">
					<div class="form-group">
						<div class="form-group">
							<label class="sr-only" for="email">Email</label> <input
								type="text" class="form-control" name="email" id="email"
								placeholder="Email"
								value="<?php if(isset($_SESSION['editUser'])){ echo $_SESSION['editUser']['email']; } ?>">
						</div>
					</div>
				</div>
				<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4"></div>
			</div>
			<div class="row">
				<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4"></div>
				<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 text-center">
					<div class="form-group">
						<div class="form-group">
							<label class="sr-only" for="status">Status</label> <select
								class="form-control" name="status" id="status">
								<option selected="selected"
									value="<?php if(isset($_SESSION['editUser'])){ echo $_SESSION['editUser']['status']; } ?>"><?php if(isset($_SESSION['editUser'])){ echo $_SESSION['editUser']['status']; } ?></option>
								<option value="active">active</option>
								<option value="deleted">deleted</option>
								<option value="suspended">suspended</option>
								<option value="admin">admin</option>
							</select>
						</div>
					</div>
				</div>
				<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4"></div>
			</div>
			<div class="row">
				<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4"></div>
				<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 text-center">
					<div class="form-group">
						<div class="form-group">
							<input type="hidden" name="userID" id="userID"
								value="<?php if(isset($_SESSION['editUser'])){ echo $_SESSION['editUser']['userID']; } ?>">
							<button type="submit" name="update" id="update"
								class="btn btn-primary">Update User</button>
						</div>
					</div>
				</div>
				<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4"></div>
			</div>
		</form>



		<form data-toggle="validator" role="form" class="form-inline"
			method="post" action="<?php echo PATH . 'Administration'; ?>">
			<div class="row">
				<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4"></div>
				<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 text-center">
					<div class="form-group">
						<label class="sr-only" for="password">Password</label> <input
							type="password" class="form-control" name="password"
							id="password" placeholder="Password">
					</div>
				</div>
				<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4"></div>
			</div>
			<div class="row">
				<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4"></div>
				<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 text-center">
					<div class="form-group">
						<label class="sr-only" for="confirm">Confirm Password</label> <input
							type="password" class="form-control" name="confirm" id="confirm"
							placeholder="Confirm Password">
					</div>
				</div>
				<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4"></div>
			</div>
			<div class="row">
				<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4"></div>
				<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 text-center">
					<div class="form-group">
						<input type="hidden" name="userID" id="userID"
							value="<?php if(isset($_SESSION['editUser'])){ echo $_SESSION['editUser']['userID']; } ?>">
						<button type="submit" name="changePassword" id="changePassword"
							class="btn btn-primary">Change Password</button>
					</div>
				</div>
			</div>
			<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4"></div>
		</form>
	</div>
</div>
