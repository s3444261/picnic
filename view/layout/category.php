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
    <h1><?php echo $this->data['currentCategory']->category ?></h1>

	<?php if (isset ( $this->data['subCategories'] ) && count($this->data['subCategories']) != 0) { ?>
        <div class="jumbotron">
            <div class="container">
				<?php foreach ( $this->data['subCategories'] as $category ) { ?>
                    <div class="col-md-4">
                        <a class="btn btn-secondary" href="<?php echo BASE.'/Category/View/'.$category->categoryID ?>" role="button"><?php echo $category->category ?></a>
                    </div>
				<?php } ?>
            </div>
        </div>
	<?php } ?>

	<?php if (isset ( $this->data['items'] )) { ?>
        <div class="container">
            <?php foreach ( $this->data['items'] as $item ) { ?>
                    <div class="col-sm-12">
                        <a class="btn btn-secondary" href="<?php echo BASE.'/Item/View/'.$item->itemID ?>" role="button">
                            <img src="<?php echo BASE.'/Item/Thumb/'.$item->itemID ?>" />
                            <?php echo $item->title ?>
                        </a>
                    </div>
            <?php } ?>
        </div>
	<?php } ?>
</div>
