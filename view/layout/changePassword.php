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
                <h1 class="display-3 text-center">Change Password</h1>

                <?php if (isset ($this->data['error'])) { ?>
                    <div class="alert alert-danger"><?php echo $this->data['error'] ?></div>
                <?php } ?>

                <div class="editUser">
                    <form data-toggle="validator" role="form" method="post" action="DoChangePassword">
                        <div class="row">
                            <div class="col-xs-2 col-sm-2 col-md-4 col-lg-4"></div>
                            <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 text-center">
                                <div class="form-group">
                                    <label class="sr-only" for="password">Password</label> <input
                                            type="password" class="form-control" name="password"
                                            id="password" placeholder="Current Password">
                                </div>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-4 col-lg-4"></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-2 col-sm-2 col-md-4 col-lg-4"></div>
                            <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 text-center">
                                <div class="form-group">
                                    <label class="sr-only" for="newPassword">Password</label> <input
                                            type="password" class="form-control" name="newPassword"
                                            id="newPassword" placeholder="New Password">
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
                                    <button type="submit" name="changePassword" id="changePassword"
                                            class="btn btn-primary">Change Password
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-4 col-lg-4"></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col"></div>
    </div>

</div>
