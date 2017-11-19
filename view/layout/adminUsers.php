<?php
/*
 * Authors:
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - s3418650@student.rmit.edu.au
 */
?>

<div class="container-fluid">
    <div class="row">
        <div class="col"></div>
        <div class="col-9">

            <!-- tab headers -->
            <ul class="nav nav-tabs nav-justified">
                <li class="nav-item">
                    <a class="nav-link active" href="<?php echo $this->usersUrl() ?>">Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $this->categoriesURL() ?>">Categories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $this->systemUrl() ?>">System</a>
                </li>
            </ul>

			<h1 class="display-4 text-center top-n-tail">Administration - Users</h1>

            <nav aria-label="Page navigation example">
            	<?php echo $this->renderPager('pagination pagination-sm list-inline', true); ?>
            </nav>

			<table class="table table-responsive listUser">
				<thead>
					<tr>
						<th class="col-md-1">Username</th>
						<th class="col-md-1">Email</th>
						<th class="col-md-1">Status</th>
						<th class="col-md-1"></th>
					</tr>
				</thead>
				<tbody>
				    <?php foreach ( $this->users() as $user ) { ?>
						<tr>
						<td><?php echo $this->userName($user); ?></td>
						<td><?php echo $this->userEmail($user); ?></td>
						<td><?php echo $this->userStatus($user); ?></td>
						<td>
                            <a class="dropdown-toggle btn btn-primary btn-block" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Actions</a>
                            <div class="dropdown-menu">
                                <a href="<?php echo $this->editUserUrl($user) ?>" class="dropdown-item">Edit</a>
                                <a href="<?php echo $this->changeUserPasswordUrl($user) ?>" class="dropdown-item">Change Password</a>
                                <button class="dropdown-item" data-toggle="modal" data-target="#<?php echo $this->deleteDialogId($user) ?>">Delete</button>

								<?php if ($this->userIsBlocked($user)) { ?>
                                    <button class="dropdown-item" data-toggle="modal" data-target="#<?php echo $this->unblockDialogId($user) ?>">Unblock User</button>
                                <?php } else { ?>
                                    <button class="dropdown-item" data-toggle="modal" data-target="#<?php echo $this->blockDialogId($user) ?>">Block User</button>
                                <?php } ?>
                            </div>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="<?php echo $this->deleteDialogId($user) ?>"
                                 role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Delete user '<?php echo $this->userName($user) ?>'?</h4>
                                        </div>
                                        <div class="modal-body">
                                            <a class="btn btn-primary"
                                               href="<?php echo $this->deleteUserUrl($user) ?>">Yes, delete this user</a>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Block Modal -->
                            <div class="modal fade" id="<?php echo $this->blockDialogId($user) ?>"
                                 role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Block user '<?php echo $this->userName($user) ?>'?</h4>
                                        </div>
                                        <div class="modal-body">
                                            <a class="btn btn-primary"
                                               href="<?php echo $this->blockUserUrl($user) ?>">Yes, block this user</a>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Unblock Modal -->
                            <div class="modal fade" id="<?php echo $this->unblockDialogId($user) ?>"
                                 role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Unblock user '<?php echo $this->userName($user) ?>'?</h4>
                                        </div>
                                        <div class="modal-body">
                                            <a class="btn btn-primary"
                                               href="<?php echo $this->unblockUserUrl($user) ?>">Yes,unblock this user</a>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
					</tr>
					<?php } ?>
				</tbody>
			</table>

            <div class="text-center">
                <?php echo $this->renderPager('pagination pagination-sm', false); ?>
            </div>

		</div> <!-- end .col-md-9 -->
		<div class="col"></div> <!-- right margin -->
	</div> <!-- end .row -->
</div> <!-- end .container-fluid -->
