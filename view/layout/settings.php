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
	<h1>Settings</h1>
	<div class="row">
		<div
			class="text-center col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-4 col-md-8 col-md-offset-2">
			<form data-toggle="validator" role="form" class="form-inline"
				method="post" action="Settings">
				<div class="form-group addUser">
					<label class="sr-only" for="user">Username</label> <input
						type="text" class="form-control" name="user" id="user"
						placeholder="Username">
				</div>
				<div class="form-group addUser">
					<label class="sr-only" for="email">Email</label> <input type="text"
						class="form-control" name="email" id="email" placeholder="Email">
				</div>
				<div class="form-group addUser">
					<label class="sr-only" for="password">Password</label> <input
						type="password" class="form-control" name="password" id="password"
						placeholder="Password">
				</div>
				<div class="form-group addUser">
					<label class="sr-only" for="confirm">Confirm Password</label> <input
						type="password" class="form-control" name="confirm" id="confirm"
						placeholder="Confirm Password">
				</div>
				<div class="form-group addUser">
					<button type="submit" name="add" class="btn btn-primary">Add User</button>
				</div>
			</form>
		</div>
	</div>
	<div class="row">
		<table class="table listUser">
			<thead>
				<tr>
					<th class="col-md-3"></th>
					<th class="col-md-1">Username</th>
					<th class="col-md-1">Email</th>
					<th class="col-md-1">Status</th>
					<th class="col-md-1"></th>
					<th class="col-md-1"></th>
					<th class="col-md-4"></th>
				</tr>
			</thead>
			<tbody>
<?php
if (isset ( $_SESSION ['users'] )) {
	foreach ( $_SESSION ['users'] as $user ) {
		?>
		<tr>
					<td></td>
					<td><?php echo $user['user']; ?></td>
					<td><?php echo $user['email']; ?></td>
					<td><?php echo $user['status']; ?></td>
					<td><a
						href="<?php echo PATH . 'Settings/Edit/' . $user['userID']; ?>"
						class="btn btn-primary btn-xs pull-right" role="button">Edit</a></td>
					<td><a
						href="<?php echo PATH . 'Settings/Delete/' . $user['userID']; ?>"
						class="btn btn-primary btn-xs" role="button"
						onclick="return confirm('Are you sure you want to delete?');">Delete</a></td>
					<td></td>
				</tr>
<?php
	}
}
?>
</tbody>
		</table>
	</div>
</div>


