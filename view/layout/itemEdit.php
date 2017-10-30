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

                <form data-toggle="validator" role="form" method="post" enctype="multipart/form-data" action="../Edit/<?php echo $this->itemID() ?>">
					<?php include("itemDetailsEditable.php") ?>

                    <div class="row">
                        <div>
                            <img src="../Thumb/<?php echo $this->itemID() ?>" alt="Uploaded Image" height="64" width="64"/>
                        </div>
                        <div>
                            <div class="form-group">
                                <label for="image">Select an image - leave empty to keep the existing image.</label>
                                <input type="file" class="form-control" name="image" id="image" placeholder="Select an image">
                                Images are limited to 20MB in size.
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-warning" formnovalidate="formnovalidate" name="cancel">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-success" name="confirm">Next</button>
                </form>
            </div>
        </div>
        <div class="col"></div>
    </div>
</div>
