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
			<div class="jumbotron">
				<h1 class="display-4 text-center">Site Map</h1>
				<hr>

				<ul>
					<li><a href="<?php echo BASE . '/Home' ?>">Humphree.org</a>
						<ul>
							<li>Home
								<ul>
									<li><a href="<?php echo BASE . '/Search/Advanced' ?>">Search</a></li>
									<li><a href="<?php echo BASE . '/About' ?>">About</a></li>
									<li><a href="<?php echo BASE . '/TermsOfService' ?>">Terms of Service</a></li>
									<li><a href="<?php echo BASE . '/PrivacyPolicy' ?>">Privacy Policy</a></li>
								</ul>
							</li>
							<li>Browse Listings
								<ul>
									<li><a href="<?php echo BASE . '/Category/View' ?>">For Sale</a>
                                        <ul>
                                            <li>View Item</li>
                                        </ul></li>
									<li><a href="<?php echo BASE . '/Category/View' ?>">Wanted</a>
                                        <ul>
                                            <li>View Item</li>
                                        </ul></li>
								</ul>
							</li>
							<li>Account
								<ul>

									<li>
                                        <?php if (isset($_SESSION[MODULE])) { ?>
                                            Register
                                        <?php } else { ?>
                                            <a href="<?php echo BASE . '/Account/Register' ?>">Register</a>
                                        <?php } ?>
                                    </li>
									<li>
										<?php if (isset($_SESSION[MODULE])) { ?>
                                            <a href="<?php echo BASE . '/Dashboard' ?>">Dashboard</a>
                                            <ul>
                                                <li><a href="<?php echo BASE . '/Dashboard/ForSale' ?>">Manage Items For Sale</a></li>
                                                <li><a href="<?php echo BASE . '/Dashboard/Wanted' ?>">Manage Wanted Items</a></li>
                                                <li><a href="<?php echo BASE . '/Dashboard/Messages' ?>">Message Center</a></li>
                                                <li>Rate Seller</li>
										    </ul>
										<?php } else { ?>
                                            Dashboard
                                            <ul>
                                                <li>Manage Items For Sale</li>
                                                <li>Manage Wanted Items</li>
                                                <li>Message Center</li>
                                                <li>Rate Seller</li>
                                            </ul>
										<?php } ?>
									</li>
									<li>Profile</li>
									<li>
										<?php if (isset($_SESSION[MODULE])) { ?>
                                            <a href="<?php echo BASE . '/Account/ChangePassword' ?>">Change Password</a>
										<?php } else { ?>
                                            Change Password
										<?php } ?>
                                    </li>
									<li>
										<?php if (isset($_SESSION[MODULE])) { ?>
                                            Forgot Password
										<?php } else { ?>
                                            <a href="<?php echo BASE . '/Account/ForgotPassword' ?>">Forgot Password</a>
										<?php } ?>
                                    </li>
                                    <li>
                                        <?php if (isset($_SESSION[MODULE])) { ?>
                                            <a href="<?php echo BASE . '/Account/Logout' ?>">Logout</a>
                                        <?php } else { ?>
                                            Logout
                                        <?php } ?>
                                    </li>
								</ul>
							</li>

							<?php if (isset($_SESSION['status']) && $_SESSION['status'] == 'admin') { ?>
                                <li>Admin<ul>
                                        <li><a href="<?php echo BASE . '/Administration' ?>">Manage Users</a></li>
                                        <li><a href="<?php echo BASE . '/Administration/ViewCategories' ?>">Manage Categories</a></li>
                                    </ul>

                                </li>
							<?php } ?>
						</ul>
					</li>
				</ul>
			</div> <!-- end .jumbotron -->
		</div> <!-- end .col-md-9 -->
		<div class="col"></div> <!-- right margin -->
	</div>
</div>
