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

<div class="jumbotron">
    <h2><?php echo $_SESSION ['category'] ['category'] ?></h2>
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

<h2>Items</h2>

<div class="container">
    <div class="row">
		<?php
		if (isset ( $_SESSION ['categoryItems'] )) {
			foreach ( $_SESSION ['categoryItems'] as $item ) {
				?>
                <div class="col-sm-12">
                    <p>
						<?php  echo '<img src="'
                            . BASE
                            . '/Item/Thumb/'
                            . $item ['item'] ['itemID']
                            . '"/><a class="btn btn-secondary" href="'
                            . BASE
                            . '/Item/View/'
                            . $item ['item'] ['itemID']
                            . '" role="button">'
							. $item ['item'] ['title']
							. '</a>'  ?>
                    </p>
                </div>
				<?php
			}
		}
		?>
    </div>
</div>