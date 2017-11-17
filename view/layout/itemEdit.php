<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */
?>

<script src="<?php echo BASE . '/js/exif.js' ?>"></script>

<script type="text/javascript">

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            var img =  $('#imgPreview');
            img.hide();

            reader.onload = function (e) {
                img.attr('src', e.target.result);
                fixExifOrientation(img);
                sleep(500).then(function() {
                    img.show();
                });
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms);)
    }

    // ref:   https://stackoverflow.com/a/28843763
    function fixExifOrientation($img) {
        $img.on('load', function() {
            EXIF.getData($img[0], function() {
                switch(parseInt(EXIF.getTag(this, "Orientation"))) {
                    case 2:
                        $img.addClass('flip'); break;
                    case 3:
                        $img.addClass('rotate-180'); break;
                    case 4:
                        $img.addClass('flip-and-rotate-180'); break;
                    case 5:
                        $img.addClass('flip-and-rotate-270'); break;
                    case 6:
                        $img.addClass('rotate-90'); break;
                    case 7:
                        $img.addClass('flip-and-rotate-90'); break;
                    case 8:
                        $img.addClass('rotate-270'); break;
                    default:
                        $img.removeClass('flip rotate-180 flip-and-rotate-180 flip-and-rotate-270 rotate-90 flip-and-rotate-90 rotate-270');
                        break;
                }

                $img.off();
            });
        });
    }

</script>
<style>
    .rotate-90 {
        -moz-transform: rotate(90deg);
        -webkit-transform: rotate(90deg);
        -o-transform: rotate(90deg);
        transform: rotate(90deg);
    }

    .rotate-180 {
        -moz-transform: rotate(180deg);
        -webkit-transform: rotate(180deg);
        -o-transform: rotate(180deg);
        transform: rotate(180deg);
    }

    .rotate-270 {
        -moz-transform: rotate(270deg);
        -webkit-transform: rotate(270deg);
        -o-transform: rotate(270deg);
        transform: rotate(270deg);
    }

    .flip {
        -moz-transform: scaleX(-1);
        -webkit-transform: scaleX(-1);
        -o-transform: scaleX(-1);
        transform: scaleX(-1);
    }

    .flip-and-rotate-90 {
        -moz-transform: rotate(90deg) scaleX(-1);
        -webkit-transform: rotate(90deg) scaleX(-1);
        -o-transform: rotate(90deg) scaleX(-1);
        transform: rotate(90deg) scaleX(-1);
    }

    .flip-and-rotate-180 {
        -moz-transform: rotate(180deg) scaleX(-1);
        -webkit-transform: rotate(180deg) scaleX(-1);
        -o-transform: rotate(180deg) scaleX(-1);
        transform: rotate(180deg) scaleX(-1);
    }

    .flip-and-rotate-270 {
        -moz-transform: rotate(270deg) scaleX(-1);
        -webkit-transform: rotate(270deg) scaleX(-1);
        -o-transform: rotate(270deg) scaleX(-1);
        transform: rotate(270deg) scaleX(-1);
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col"></div>
        <div class="col-9">
            <div class="jumbotron panel panel-default">
                <h1 class="display-4 text-center">Edit item</h1>
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
