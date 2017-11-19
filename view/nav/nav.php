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
<!--Nav-->
<div class="container-fluid">
  <div class="row">
    <!-- Left margin -->
    <div class="col"></div>
    <!-- Central column -->
    <div class="col-9">
      <ul class="nav nav-pills nav-justified top-n-tail">
        <li class="nav-item">
          <a class="nav-link<?php if (!isset($this->data['navData']) || $this->data['navData']->Selected == NavData::Home) { echo ' active'; } ?>" href="<?php echo BASE; ?>/Home">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link<?php if (isset($this->data['navData']) && $this->data['navData']->Selected == NavData::ViewListings) { echo ' active'; } ?>" href="<?php echo BASE; ?>/Category/View">View listings</a>
        </li>
        <li class="nav-item">
          <a class="nav-link<?php if (isset($this->data['navData']) && $this->data['navData']->Selected == NavData::Account) { echo ' active'; } ?>
            <?php if (isset ( $_SESSION [MODULE] )) {
                echo 'dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"';
            } else {
                echo '" href="' . BASE . '/Account/Login"';
            } ?>">

			  <?php
			  if (isset ( $_SESSION [MODULE] )) {
				  echo 'My account ( ' . $_SESSION ['user'] .' )';
			  } else {
			      echo 'Log In';
              }
			  ?>
          </a>
          <div class="dropdown-menu">
            <?php
            if (isset ( $_SESSION [MODULE] )) {
                echo '<a class="dropdown-item" href="' . BASE . '/Dashboard/Messages">Dashboard</a>';
              }
            ?>
            <?php
            if (isset ( $_SESSION ['status'] )) {
                if ($_SESSION ['status'] == 'admin') {
                echo '<a class="dropdown-item" href="' . BASE . '/Administration/Users">Admin</a>';
              }
            }
            ?>
            <?php
            if (isset ( $_SESSION [MODULE] )) {
                echo '<div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="' . BASE . '/Account/ChangePassword">Change Password</a>
                      <a class="dropdown-item" href="' . BASE . '/Account/Logout">Logout</a>';
            } else {
                echo '<div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="' . BASE . '/Account/Register">Sign-up</a>
                      <a class="dropdown-item" href="' . BASE . '/Account/Login">Sign-in</a>';
                  }
            ?>
          </div>
        </li>
      </ul>
    </div> <!-- end col-9 -->
    <!-- Right margin -->
    <div class="col"></div>
  </div> <!-- end row -->
</div> <!-- end container -->
