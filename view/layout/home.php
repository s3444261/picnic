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
                <form>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Search</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="What are you looking for?">
                        <small id="emailHelp" class="form-text text-muted">e.g. smartphone, lawnmower, hat</small>
                        <small id="adv-search" class="form-text text-right"><a href="#">Advanced search</a></small>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg btn-block">Search</button>
                </form>
            </div> <!-- end .jumbotron -->
        </div> <!-- end col-9 -->
        <!-- Right margin -->
        <div class="col"></div>
    </div> <!-- end row -->

<!--	--><?php //if (isset ( $this->data['categories'] ) && count($this->data['categories']) != 0) { ?>
<!--        <div class="jumbotron">-->
<!--            <h2>Categories</h2>-->
<!--            <div class="container">-->
<!--                --><?php //foreach ( $this->data['categories'] as $category ) { ?>
<!--                    <div class="col-md-4">-->
<!--                        <a class="btn btn-secondary" href="--><?php //echo BASE.'/Category/View/'.$category->categoryID ?><!--" role="button">--><?php //echo $category->category ?><!--</a>-->
<!--                    </div>-->
<!--                --><?php //} ?>
<!--            </div>-->
<!--        </div>-->
<!--    --><?php //} ?>

<!--	<div class="jumbotron">-->
<!--		<div class="row">-->
<!--			<h2>Search Results</h2>-->
<!--		</div>-->
<!--	</div>-->
</div>
