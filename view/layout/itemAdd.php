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
                <h1 class="display-3 text-center">Add item</h1>
                <hr>

				<?php if ($this->hasError()) { ?>
                    <div class="alert alert-danger"><?php echo $this->error() ?></div>
				<?php } ?>
                <form data-toggle="validator" role="form" method="post" enctype="multipart/form-data" action="Create">
					<?php include("itemDetailsEditable.php") ?>

                    <div class="form-group">
                        <label for="quantity">Image</label>
                        <input type="file" class="form-control" name="image" id="image" placeholder="Select an image" required>
                        Images are limited to 20MB in size.
                    </div>

                    <button type="submit" class="btn btn-primary btn-warning" formnovalidate="formnovalidate" name="cancel">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-success" name="confirm">Next</button>
                </form>
            </div>
        </div>
        <div class="col"></div>
    </div>
</div>
