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
						<th class="col-md-1">Username</th>
						<th class="col-md-1">Email</th>
						<th class="col-md-1">Status</th>
						<th class="col-md-1"></th>
					</tr>
				</thead>
				<tbody>
				<?php
				if (isset ( $this->data['users'] )) {
					foreach ( $this->data['users'] as $user ) {
						?>
						<tr>
						<td><?php echo $user['user']; ?></td>
						<td><?php echo $user['email']; ?></td>
						<td><?php if ($user['blocked'] != 0) { echo 'BLOCKED'; } else {echo $user['status']; } ?></td>
						<td>
                            <a class="dropdown-toggle btn btn-primary btn-block" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Actions</a>
                            <div class="dropdown-menu">
                                <a href="<?php echo BASE . '/Administration/Edit/' . $user['userID']; ?>" class="dropdown-item">Edit</a>
                                <button class="dropdown-item" data-toggle="modal" data-target="#deleteDialog<?php echo $user['userID'] ?>">Delete</button>
								<?php if ($user['blocked'] != 0) { ?>
                                    <button class="dropdown-item" data-toggle="modal" data-target="#unblockDialog<?php echo $user['userID'] ?>">Unblock User</button>
                                <?php } else { ?>
                                    <button class="dropdown-item" data-toggle="modal" data-target="#blockDialog<?php echo $user['userID'] ?>">Block User</button>
                                <?php } ?>
                            </div>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteDialog<?php echo $user['userID'] ?>"
                                 role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Delete user '<?php echo $user['user'] ?>'?</h4>
                                        </div>
                                        <div class="modal-body">
                                            <a class="btn btn-primary"
                                               href="<?php echo BASE . '/Administration/Delete/' . $user['userID']; ?>">Yes,
                                                delete this user</a>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Block Modal -->
                            <div class="modal fade" id="blockDialog<?php echo $user['userID'] ?>"
                                 role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Block user '<?php echo $user['user'] ?>'?</h4>
                                        </div>
                                        <div class="modal-body">
                                            <a class="btn btn-primary"
                                               href="<?php echo BASE . '/Administration/BlockUser/' . $user['userID']; ?>">Yes,
                                                block this user</a>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Block Modal -->
                            <div class="modal fade" id="unblockDialog<?php echo $user['userID'] ?>"
                                 role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Unblock user '<?php echo $user['user'] ?>'?</h4>
                                        </div>
                                        <div class="modal-body">
                                            <a class="btn btn-primary"
                                               href="<?php echo BASE . '/Administration/UnblockUser/' . $user['userID']; ?>">Yes,
                                                unblock this user</a>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
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
