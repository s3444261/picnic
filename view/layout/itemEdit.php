<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <edwanhp@gmail.com>
 */
?>


<script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#imgPreview').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

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
                        <div class="col-lg-3 text-center">
                            <img id="imgPreview" src="../LastTempImage" alt="Uploaded Image" style="max-width:150px; max-height:150px"/>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <p>Select an image - leave empty to keep the existing image.</p>
                                <label class="custom-file">
                                    <input type="file"  class="form-control" name="image" id="image" placeholder="Select an image" onchange="readURL(this);" class="custom-file-input">
                                    <span class="custom-file-control"></span><br>
                                </label>
                                <p>Images are limited to 20MB in size.</p>

                            </div>
                        </div>
                    </div>

                    <hr />
                    <button type="submit" class="btn btn-primary btn-warning" formnovalidate="formnovalidate" name="cancel">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-success" name="confirm">Next</button>
                </form>
            </div>
        </div>
        <div class="col"></div>
    </div>
</div>
