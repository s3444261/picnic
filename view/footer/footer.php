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

<!--<hr />-->
<div class="container-fluid">
    <div class="row">
        <!-- Left margin -->
        <div class="col"></div>
        <!-- Central column -->
        <div class="col-9">
            <!-- Footer -->
            <footer>
                <ul class="nav justify-content-center top-n-tail">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE . '/Help/About' ?>">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE . '/Help/TermsOfService' ?>">Terms of Service</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo  BASE . '/Help/PrivacyPolicy' ?>">Privacy policy</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo  BASE . '/Help/SiteMap' ?>">Site map</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo  BASE . '/Help/Faq' ?>">FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo  BASE . '/Home' ?>">Search</a>
                    </li>

                    <?php if (isset ( $_SESSION [MODULE] )) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE  . '/Account/Logout' ?>">
                                Logout
                            </a>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE . '/Account/Login' ?>">
                                Login
                            </a>
                        </li>
                    <?php } ?>

                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE  . '/Category/View' ?>">
                            View catalogue
                        </a>
                    </li>
                </ul>
                <p class="small text-muted text-center">
                    Created by <a href="https://www.youtube.com/watch?v=XMfIRsNIakc">Picnic</a>
                </p>
            </footer>
        </div> <!-- end col-9 -->
        <!-- Right margin -->
        <div class="col"></div>
    </div> <!-- end row -->
</div> <!-- end container -->

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"
        integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"
        integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1"
        crossorigin="anonymous"></script>
<script>
    $(".myBox").click(function() {
      window.location = $(this).find("a").attr("href");
      return false;
    });
</script>
</body>
</html>

<?php 	unset($_SESSION['error']); ?>
