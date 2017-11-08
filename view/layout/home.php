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
        <!-- Left margin -->
        <div class="col"></div>
        <!-- Central column -->
        <div class="col-9">
            <div class="jumbotron">
                <div class="alert alert-primary" role="alert">
                    <h2 class="text-center">Welcome to Humphree!</h2>
                    <h5 class="text-center">Please search using the form below</h5>
                </div>
                <form action="<?php echo BASE . '/Search/Results'?>">
                    <div class="form-group">
                        <label for="exampleInputTest">Search</label>
                        <input type="text" class="form-control" id="srch-term" name="srch-term" aria-describedby="searchHelp" placeholder="What are you looking for?">
                        <small id="searchHelp" class="form-text text-muted">e.g. smartphone, lawnmower, hat</small>
                        <small id="adv-search" class="form-text text-right"><a href="<?php echo BASE . '/Search/Advanced'?>" data-toggle="tooltip" data-placement="top" title="Coming soon">Advanced search</a></small>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg btn-block"  name="searchBasic" id="searchBasic">Search</button>
                </form>
            </div> <!-- end .jumbotron -->
        </div> <!-- end col-9 -->
        <!-- Right margin -->
        <div class="col"></div>
    </div> <!-- end row -->
</div>
