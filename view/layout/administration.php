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
		<div class="col-md-9">
			<h1 class="text-center">Administration</h1>
		</div>
		<div class="col"></div>
	</div>
	<div class="row">
		<div class="col"></div>
		<div class="col-md-9">
			<!-- <div class="text-center col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-4 col-md-8 col-md-offset-2"> -->
				<form data-toggle="validator" role="form" class="form-inline"
					method="post" action="<?php echo BASE . '/Administration'; ?>">
					<div class="form-row">
						<div class="col">
							<div class="form-group addUser">
								<label class="sr-only" for="user">Username</label> <input
									type="text" class="form-control" name="user" id="user"
									placeholder="Username">
							</div>
						</div>
						<div class="col">
							<div class="form-group addUser">
								<label class="sr-only" for="email">Email</label> <input type="text"
									class="form-control" name="email" id="email" placeholder="Email">
							</div>
						</div>
						<div class="col">
							<div class="form-group addUser">
								<label class="sr-only" for="password">Password</label> <input
									type="password" class="form-control" name="password" id="password"
									placeholder="Password">
							</div>
						</div>
						<div class="col">
							<div class="form-group addUser">
								<label class="sr-only" for="confirm">Confirm Password</label> <input
									type="password" class="form-control" name="confirm" id="confirm"
									placeholder="Confirm Password">
							</div>
						</div>
						<div class="col">
							<div class="form-group addUser">
								<button type="submit" name="add" class="btn btn-primary">Add User</button>
							</div>
						</div>
					</div> <!-- end .form-row -->
				</form>
			<!-- </div> -->
		</div>
		<div class="col"></div>
	</div>

	<div class="row">
		<div class="col"></div>
		<div class="col-xs-6 col-xs-offset-3 top-n-tail">
			<?php echo Pager::Render('pagination pagination-sm list-inline', $this->data ['pagerData'], true); ?>
		</div>
		<div class="col"></div>
	</div>

	<div class="row">
		<div class="col"></div> <!-- left margin -->
		<div class="col-md-9">
			<table class="table listUser">
				<thead>
					<tr>
						<!-- <th class="col-md-3"></th> -->
						<th class="col-md-1">Username</th>
						<th class="col-md-1">Email</th>
						<th class="col-md-1">Status</th>
						<th class="col-md-1"></th>
						<th class="col-md-1"></th>
						<!-- <th class="col-md-4"></th> -->
					</tr>
				</thead>
				<tbody>
				<?php
				if (isset ( $this->data['users'] )) {
					foreach ( $this->data['users'] as $user ) {
						?>
						<tr>
						<!-- <td></td> -->
						<td><?php echo $user['user']; ?></td>
						<td><?php echo $user['email']; ?></td>
						<td><?php echo $user['status']; ?></td>
						<td><a
							href="<?php echo BASE . '/Administration/Edit/' . $user['userID']; ?>"
							class="btn btn-primary btn-xs pull-right" role="button">Edit</a></td>
						<td><a
							href="<?php echo BASE . '/Administration/Delete/' . $user['userID']; ?>"
							class="btn btn-primary btn-xs" role="button"
							onclick="return confirm('Are you sure you want to delete?');">Delete</a></td>
						<!-- <td></td> -->
					</tr>
					<?php
						}
					}
				?>
				</tbody>
			</table>
		</div> <!-- end .col-md-9 -->
		<div class="col"></div> <!-- right margin -->
	</div> <!-- end .row -->

	<div class="row">
		<div class="col"></div>
		<div class="col-xs-6 col-xs-offset-3 top-n-tail">
			<?php echo Pager::Render('pagination pagination-sm', $this->data ['pagerData'], false); ?>
		</div>
		<div class="col"></div>
	</div> <!-- end .row -->
</div> <!-- end .container-fluid -->
