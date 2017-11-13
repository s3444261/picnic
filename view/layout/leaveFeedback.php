<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <edwanhp@gmail.com>
 */
?>

<div class="container-fluid">
	<div class="row">
		<div class="col"></div>
		<div class="col-9">
			<div class="jumbotron panel panel-default">
				<h1 class="display-3 text-center">Leave feedback</h1>

				<?php if ($this->hasError()) { ?>
                    <div class="alert alert-danger"><?php echo $this->error() ?></div>
				<?php } ?>

				<div class="row">Select a rating for  <?php echo $this->ratingUserName() ?>.</div>
				<div class="row">This score relates to the transaction with <?php echo $this->ratingUserName() ?> for the following matched items:</div>
				<ul>
					<li><a href="<?php echo $this->myItemUrl() ?>"><?php echo $this->myItemTitle() ?></a></li>
					<li><a href="<?php echo $this->otherItemUrl() ?>"><?php echo $this->otherItemTitle() ?></a></li>
				</ul>

				<form data-toggle="validator" role="form" method="post" action="<?php echo $this->postUrl() ?>">
					<div class="form-group">
						<label for="email">Select a rating from 1 to 5 for the transaction.</label>
						<label class="radio-inline"><input type="radio" name="rating" value="1">1</label>
						<label class="radio-inline"><input type="radio" name="rating" value="2">2</label>
						<label class="radio-inline"><input type="radio" name="rating" value="3">3</label>
						<label class="radio-inline"><input type="radio" name="rating" value="4">4</label>
						<label class="radio-inline"><input type="radio" name="rating" value="5">5</label>
					</div>
					<button type="submit" name="submit" class="btn btn-primary">Rate user</button>
				</form>
			</div>
		</div>
		<div class="col"></div>
	</div>
</div>
