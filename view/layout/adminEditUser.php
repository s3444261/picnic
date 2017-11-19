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
            <div class="jumbotron panel panel-default">
                <h1 class="display-4 text-center">Edit user</h1>
                <hr />

				<?php if ($this->hasError()) { ?>
                    <div class="alert alert-danger"><?php echo $this->errorMessage() ?></div>
				<?php } ?>

                <form data-toggle="validator" role="form" method="post" action="<?php echo $this->submitUrl() ?>">
                    <div class="form-group">
                        <label for="user">Name</label>
                        <input type="text" class="form-control" name="user" id="user" placeholder="Name"
                               value="<?php echo $this->userName() ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email address"
                               value="<?php echo $this->userEmail() ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" name="status" id="status">
                            <option <?php if ($this->isStandardUser()) {
								echo ' selected="selected" ';
							} ?>
                                    value="user">user
                            </option>
                            <option <?php if ($this->isAdminUser()) {
								echo ' selected="selected" ';
							} ?>
                                    value="admin">admin
                            </option>
                        </select>
                    </div>

                    <button type="submit" name="update" id="update" class="btn btn-primary">Update User</button>
                </form>
            </div>
        </div>
        <div class="col"></div>
    </div>
</div>
