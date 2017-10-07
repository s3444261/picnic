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

<h1><?php echo $this->data['item']['title'] ?></h1>

<div class="container">
    <div class="col-md-4">
        <!-- for now, I'm just using the thumb scaled up, to avoid uploading hundreds of MB of
             images to the dev server. If/when we want to show full size images, the URLS in the following
              anchor should be changed from /Item/Thumb/ to /Item/Image/ -->
        <img src="<?php echo BASE.'/Item/Thumb/'.$this->data['item']['itemID'] ?>" style="min-width:300px; min-height:300px" />
    </div>

    <div class="col-md-8">
        <?php if (isset ( $this->data['item'] )) { ?>
            <div class="row">
                <div class="col-md-3">ID</div>
                <div class="col-md-9"><?php echo $this->data['item']['itemID'] ?></div>
            </div>
            <div class="row">
                <div class="col-md-3">Description</div>
                <div class="col-md-9"><?php echo $this->data['item']['description'] ?> </div>
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
            <div class="row">
                <div class="col-md-3">Status</div>
                <div class="col-md-9"><?php echo $this->data['item']['status'] ?></div>
            </div>
        <?php } ?>
    </div>
</div>
