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
        <div class="col"></div> <!-- left margin -->
        <div class="col-9">
            <div class="jumbotron panel panel-default">
                <h1 class="display-4 text-center">Successfully logged in</h1>
                <div class="text-center top-n-tail">
                    <a class="btn btn-primary" href="<?php echo BASE . '/Category/View'; ?>" role="button">View Listings</a>
                    <a class="btn btn-primary" href="<?php echo BASE . '/Item/Create' ?>" role="button">Sell Items</a>
                </div>
            </div>
        </div>
        <div class="col"></div> <!-- right margin -->
    </div>

</div>
