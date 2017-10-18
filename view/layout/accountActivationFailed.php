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
    <div class="row">
        <div class="col"></div>
        <div class="col-9">
            <div class="jumbotron panel panel-default">
                <h1 class="display-3 text-center">Account activation failed</h1>
                <div class="alert alert-danger text-center">
                    The account could not be activated.
                    <?php if (isset ($this->data['error'])) {
                        echo $this->data['error'];
                    } ?>
                </div>
            </div>
        </div>
        <div class="col"></div>
    </div>

</div>
