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
        <div class="col-md-3">Type</div>
        <div class="col-md-9"><?php echo $this->itemStatus() ?></div>
    </div>
    <div class="row">
        <div class="col-md-3">Category</div>
        <div class="col-md-9"><?php echo $this->selectedMajorCategoryName() . '  /  ' . $this->selectedMinorCategoryName() ?></div>
    </div>
    <div class="row">
        <div class="col-md-3">Title</div>
        <div class="col-md-9"><?php echo $this->itemTitle() ?></div>
    </div>
    <div class="row">
        <div class="col-md-3">Description</div>
        <div class="col-md-9"><?php echo $this->itemDescription() ?></div>
    </div>
    <div class="row">
        <div class="col-md-3">Quantity</div>
        <div class="col-md-9"><?php echo $this->itemQuantity() ?></div>
    </div>
    <div class="row">
        <div class="col-md-3">Condition</div>
        <div class="col-md-9"><?php echo $this->itemCondition() ?></div>
    </div>
    <div class="row">
        <div class="col-md-3">Price</div>
        <div class="col-md-9">$<?php echo $this->itemPrice() ?></div>
    </div>
</div>