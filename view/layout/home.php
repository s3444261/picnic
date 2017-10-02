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
	<h1>Humphree</h1>

    <div class="jumbotron">
        <h2>Categories</h2>

        <div class="container">
           <div class="row">

                <?php
                if (isset ( $_SESSION ['categories'] )) {
                    foreach ( $_SESSION ['categories'] as $category ) {
                    ?>
                    <div class="col-md-4">
                        <p>
                            <?php  echo '<a class="btn btn-secondary" href="'
                                . BASE
                                . '/Category/View/'
                                . $category ["category"] ["categoryID"]
                                . '" role="button">'
                                . $category ["category"] ["category"]
                                . '</a>'  ?>
                        </p>
                    </div>
                    <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>

	<div class="jumbotron">
		<div class="row">
			<h2>Search Results</h2>
		</div>
	</div>
</div>