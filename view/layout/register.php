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
            <h2>Register</h2>

            <form data-toggle="validator" role="form" method="post" action="DoRegister">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Email"  required>
                    <p><small>We'll never share your email with anyone else.</small></p>
                </div>
                <div class="form-group">
                    <label for="email">User name</label>
                    <input type="text" class="form-control" name="username" id="username" placeholder="User name"  required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" minlength="8" required>
                </div>
                <div class="form-group">
                    <label for="password">Confirm Password</label>
                    <input type="password" class="form-control" name="password2" id="password2" placeholder="Password" minlength="8" required>
                </div>
                <button type="submit" class="btn btn-primary">Register</button>
            </form>
		<?php } ?>
    </div>
</div>
