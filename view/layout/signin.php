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
        <div class="col"></div> <!-- left margin -->
        <div class="col-md-9">
                <div class="jumbotron panel panel-default">
                    <?php if (!isset ($_SESSION [MODULE])) { ?>
                        <h1 class="display-3 text-center">Login</h1>

                        <?php if (isset ($this->data['error'])) { ?>
                            <div class="alert alert-danger"><?php echo $this->data['error'] ?></div>
                        <?php } ?>

                        <form data-toggle="validator" role="form" method="post" action="DoLogin">
                            <div class="form-group">
                                <label for="email">Email address</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Enter email" required>
                                <p class="text-muted"><small>We'll never share your email with anyone else.</small></p>
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Password" minlength="8" required>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-2 col-12">
                                    <button type="submit" class="btn btn-primary">Login</button>
                                </div>
                                <div class="form-group col-md-3 col-12">
                                    <div class="form-check form-check-inline mb-2 mr-sm-2 mb-sm-0">
                                      <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="remember"> Remember me
                                      </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-7 col-12">
                                    <p class="text-right"><small><a href="ForgotPassword">Forgot password?</a></small></p>
                                </div>
                            </div> <!-- end .form-row -->
                        </form>


                        <div>
                            <p></p>
                            <h5>Not yet registered?</h5>
                            <a class="btn btn-secondary btn-success" role="button" href="<?php echo BASE . '/Account/Register' ?>">Sign up now!</a>
                            <p><small>If you wish to contact an advertiser or list an item for sale, you are required to register.</small></p>
                        </div>
                    <?php } else { ?>
                        <p>You are already signed in as <?php echo $_SESSION ['user'] ?>.</p>
                    <?php } ?>
                </div>
        </div>
        <div class="col"></div> <!-- right margin -->
    </div>
</div>
