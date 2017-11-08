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

            <!-- tab headers -->
            <ul class="nav nav-tabs nav-justified">
                <li class="nav-item">
                    <a class="nav-link active" href="<?php echo BASE . '/Administration' ?>">Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE . '/Administration/ViewCategories' ?>">Categories</a>
                </li>
            </ul>

			<h1 class="display-4 text-center top-n-tail">Administration - Users</h1>

            <div class="text-center">
				<?php echo Pager::Render('pagination pagination-sm list-inline', $this->data ['pagerData'], true); ?>
            </div>

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
                                <a href="<?php echo BASE . '/Administration/EditUser/' . $user['userID']; ?>" class="dropdown-item">Edit</a>
                                <a href="<?php echo BASE . '/Administration/ChangePassword/' . $user['userID']; ?>" class="dropdown-item">Change Password</a>
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
                                               href="<?php echo BASE . '/Administration/DeleteUser/' . $user['userID']; ?>">Yes,
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

                            <!-- unBlock Modal -->
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

            <div class="text-center">
                <?php echo Pager::Render('pagination pagination-sm', $this->data ['pagerData'], false); ?>
            </div>

		</div> <!-- end .col-md-9 -->
		<div class="col"></div> <!-- right margin -->
	</div> <!-- end .row -->
</div> <!-- end .container-fluid -->
