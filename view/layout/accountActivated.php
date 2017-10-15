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
	<div class="jumbotron panel panel-default">
		<h2>Account activated</h2>
		<div>
			<p>Congratulations! Your account is now activated and you may contact sellers or list your own items for sale..</p>
		</div>

		<a type="submit" class="btn btn-primary" href="<?php echo BASE . '/Category/View'; ?>" role="button">View Listings</a>

		<a type="submit" class="btn btn-primary" href="<?php echo BASE . '/Item/Create/' ?>" role="button">Sell Items</a>
	</div>
</div>