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
				<h1 class="display-3 text-center">Edit item</h1>
				<hr>

				<?php if ($this->hasError()) { ?>
                    <div class="alert alert-danger"><?php echo $this->error() ?></div>
				<?php } ?>

				<h4 class="text-center">Please check the details for your listing</h4>

                <div class="row top-n-tail">
                    <div class="col-3">
                        <img class="item-image" src="../LastTempImage" alt="Uploaded Image" />
                    </div>
                    <div class="col">
						<?php include("itemDetailsReadOnly.php") ?>
                    </div>
                </div>

                <hr />
				<form data-toggle="validator" role="form" method="post" action="../Edit/<?php echo $this->itemID() ?>">
					<button type="submit" name="" class="btn btn-primary btn-warning">Oops - go back!</button>
					<button type="submit" name="commit" class="btn btn-primary btn-success">All good &rarr; update listing</button>
				</form>
			</div>
		</div>
		<div class="col"></div>
	</div>
</div>
