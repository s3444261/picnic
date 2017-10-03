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

	<?php if (isset ( $this->data['categories'] ) && count($this->data['categories']) != 0) { ?>
        <div class="jumbotron">
            <h2>Categories</h2>
            <div class="container">
                <?php foreach ( $this->data['categories'] as $category ) { ?>
                    <div class="col-md-4">
                        <a class="btn btn-secondary" href="<?php echo BASE.'/Category/View/'.$category->categoryID ?>" role="button"><?php echo $category->category ?></a>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>

	<div class="jumbotron">
		<div class="row">
			<h2>Search Results</h2>
		</div>
	</div>
</div>
