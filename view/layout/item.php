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
    <h2><?php echo $_SESSION ['item'] ['title'] ?></h2>
</div>

<div class="container">
	<div class="row">
		<?php
		if (isset ( $_SESSION ['item'] )) {
				?>
			<div class="row">
				<div class="col-md-3">ID</div>
				<div class="col-md-9"><?php echo $_SESSION ['item'] ['itemID'] ?></div>
			</div>
			<div class="row">
				<div class="col-md-3">Description</div>
				<div class="col-md-9"><?php echo $_SESSION ['item'] ['description'] ?> </div>
			</div>
			<div class="row">
				<div class="col-md-3">Quantity</div>
				<div class="col-md-9"><?php echo $_SESSION ['item'] ['quantity'] ?></div>
			</div>
			<div class="row">
				<div class="col-md-3">Condition</div>
				<div class="col-md-9"><?php echo $_SESSION ['item'] ['itemcondition'] ?></div>
			</div>
			<div class="row">
				<div class="col-md-3">Price</div>
				<div class="col-md-9">$<?php echo $_SESSION ['item'] ['price'] ?></div>
			</div>
			<div class="row">
				<div class="col-md-3">Status</div>
				<div class="col-md-9"><?php echo $_SESSION ['item'] ['status'] ?></div>
			</div>

            <?php
		}
		?>
	</div>
</div>
