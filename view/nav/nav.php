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
<!--Nav-->
<div class="container-fluid">
  <div class="row">
    <!-- Left margin -->
    <div class="col"></div>
    <!-- Central column -->
    <div class="col-9">
      <ul class="nav nav-pills nav-justified top-n-tail">
        <li class="nav-item">
          <a class="nav-link active" href="<?php echo BASE; ?>/Home">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">View listings</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">My account</a>
          <div class="dropdown-menu">
            <?php
            if (isset ( $_SESSION [MODULE] )) {
                echo '<a class="dropdown-item" href="' . BASE . '/Dashboard">Dashboard</a>';
              }
            ?>
            <?php
            if (isset ( $_SESSION ['status'] )) {
                if ($_SESSION ['status'] == 'admin') {
                echo '<a class="dropdown-item" href="' . BASE . '/Administration">Admin</a>';
              }
            }
            ?>
            <?php
            if (isset ( $_SESSION [MODULE] )) {
                echo '<div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="' . BASE . '/ChangePassword">Change Password</a>
                      <a class="dropdown-item" href="' . BASE . '/Logout">Logout</a>';
            } else {
                echo '<div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="' . BASE . '/Signup">Sign-up</a>
                      <a class="dropdown-item" href="' . BASE . '/Signin">Sign-in</a>';
                  }
            ?>
            <?php
            if (isset ( $_SESSION [MODULE] )) {
                echo '<div class="dropdown-divider"></div>
                      <a class="dropdown-item disabled" href="' . BASE . '/CreateDB">Create DB</a>';
            } else {
                // Remove this on the Production Server
                echo '<div class="dropdown-divider"></div>
                      <a class="dropdown-item disabled" href="' . BASE . '/CreateDB">Create DB</a>';
            }
            ?>
            <a class="dropdown-item disabled" href="#">Test</a>
            <a class="dropdown-item disabled" href="#">Create DB</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Logout</a>
            <!--<a class="dropdown-item" href="#">Separated link</a>-->
          </div>
        </li>
      </ul>
    </div> <!-- end col-9 -->
    <!-- Right margin -->
    <div class="col"></div>
  </div> <!-- end row -->
</div> <!-- end container -->
