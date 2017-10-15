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

<div class="container">
    <div class="jumbotron panel panel-default">
        <?php if (!isset ($_SESSION [MODULE])) { ?>
            <h2>Login</h2>

            <form data-toggle="validator" role="form" method="post" action="DoLogin">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Email"  required>
                    <p><small>We'll never share your email with anyone else.</small></p>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" minlength="8" required>
                </div>

                <div class="checkbox">
                    <label><input type="checkbox" name="remember"> Remember me</label>
                </div>

                <button type="submit" class="btn btn-primary">Login</button>

                <a href="ForgotPassword">Forgot password?</a>
            </form>

            <div>
                <p></p>
                <h5>Not yet registered?</h5>
                <a class="btn btn-secondary" role="button" href="<?php echo BASE . '/Account/Register' ?>">Sign up now!</a>
                <p><small>If you wish to contact an advertiser or list an item for sale, you are required to register.</small></p>
            </div>
        <?php } else { ?>
            <p>You are already signed in as <?php echo $_SESSION ['user'] ?>.</p>
        <?php } ?>
    </div>
</div>
