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
                    <li class="nav-item"><a class="nav-link" href="<?php echo BASE . '/About' ?>">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo BASE . '/TermsOfService' ?>">Terms of Service</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo  BASE . '/PrivacyPolicy' ?>">Privacy policy</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo  BASE . '/Home' ?>">Search</a></li>

                    <?php if (isset ( $_SESSION [MODULE] )) { ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo BASE  . '/Logout' ?>">Logout</a></li>
                    <?php } else { ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo BASE . '/Signin' ?>">Login</a></li>
                    <?php } ?>

                    <li class="nav-item"><a class="nav-link" href="<?php echo BASE  . '/Category/View' ?>">View catalogue</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo BASE  . '/CreateDB' ?>">Create DB</a></li>
                </ul>
            </footer>
        </div> <!-- end col-9 -->
        <!-- Right margin -->
        <div class="col"></div>
    </div> <!-- end row -->
</div> <!-- end container -->

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
</body>
</html>