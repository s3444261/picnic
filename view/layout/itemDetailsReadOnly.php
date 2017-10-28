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
        <div class="col-md-3"><strong>Type</strong></div>
        <div class="col-md-9"><?php echo $this->itemStatus() ?></div>
    </div>
    <div class="row">
        <div class="col-md-3"><strong>Category</strong></div>
        <div class="col-md-9"><?php echo $this->selectedMajorCategoryName() . '  /  ' . $this->selectedMinorCategoryName() ?></div>
    </div>
    <div class="row">
        <div class="col-md-3"><strong>Title</strong></div>
        <div class="col-md-9"><?php echo $this->itemTitle() ?></div>
    </div>
    <div class="row">
        <div class="col-md-3"><strong>Description</strong></div>
        <div class="col-md-9"><?php echo $this->itemDescription() ?></div>
    </div>
    <div class="row">
        <div class="col-md-3"><strong>Quantity</strong></div>
        <div class="col-md-9"><?php echo $this->itemQuantity() ?></div>
    </div>
    <div class="row">
        <div class="col-md-3"><strong>Condition</strong></div>
        <div class="col-md-9"><?php echo $this->itemCondition() ?></div>
    </div>
    <div class="row">
        <div class="col-md-3"><strong>Price</strong></div>
        <div class="col-md-9">$<?php echo $this->itemPrice() ?></div>
    </div>
</div>
