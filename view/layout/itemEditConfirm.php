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
		<div class="col"></div>
		<div class="col-9">
			<div class="jumbotron panel panel-default">
				<h1 class="display-3 text-center">Edit item</h1>
				<hr>

				<?php if (isset ($this->data['error'])) { ?>
					<div class="alert alert-danger"><?php echo $this->data['error'] ?></div>
				<?php } ?>
				<h4>Please confirm the following:</h4>

				<div class="container-fluid">
					<div class="row">
						<div class="col-md-3">Type</div>
						<div class="col-md-9"><?php echo $this->data['item']['status'] ?></div>
					</div>
					<div class="row">
						<div class="col-md-3">Category</div>
						<div class="col-md-9"><?php echo $this->data['item']['majorCategoryName'] . '/' . $this->data['item']['categoryName'] ?></div>
					</div>
					<div class="row">
						<div class="col-md-3">Title</div>
						<div class="col-md-9"><?php echo $this->data['item']['title'] ?></div>
					</div>
					<div class="row">
						<div class="col-md-3">Description</div>
						<div class="col-md-9"><?php echo $this->data['item']['description'] ?></div>
					</div>
					<div class="row">
						<div class="col-md-3">Quantity</div>
						<div class="col-md-9"><?php echo $this->data['item']['quantity'] ?></div>
					</div>
					<div class="row">
						<div class="col-md-3">Condition</div>
						<div class="col-md-9"><?php echo $this->data['item']['itemcondition'] ?></div>
					</div>
					<div class="row">
						<div class="col-md-3">Price</div>
						<div class="col-md-9">$<?php echo $this->data['item']['price'] ?></div>
					</div>
				</div>
				<form data-toggle="validator" role="form" method="get" action="Edit/<?php echo $this->data['item']['itemID']; ?>">
					<button type="submit" class="btn btn-primary btn-success">Oops - go back!</button>
				</form>
				<form data-toggle="validator" role="form" method="post" action="DoEdit">
					<button type="submit" class="btn btn-primary btn-success">All good -- update listing</button>
				</form>
			</div>
		</div>
		<div class="col"></div>
	</div>
</div>
