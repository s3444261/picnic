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
<!-- Main content -->
<div class="container-fluid">
	<div class="row">
		<!-- Left margin -->
		<div class="col"></div>
		<!-- Central column -->
		<div class="col-9">
				<?php if (!$this->itemIsVisibleToCurrentUser()) { ?>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-danger">This item is no longer available.</div>
                            </div>
                        </div>
                    </div> <!-- end .container -->
				<?php } else { ?>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <h2 class="item-header"><?php echo ucwords (strtolower(($this->itemTitle()))) ?> </h2>
                            </div>
                        </div>

						<?php if ($this->itemIsCompleted()) { ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-info">This item was matched with <a href="<?php echo $this->fullyMatchedItemUrl() ?>"><?php echo $this->fullyMatchedItemTitle() ?></a> and is no longer publicly available.</div>
                                </div>
                            </div>
						<?php } ?>

                        <div class="row">
                            <div class="col-md-4">
                                <img class="item-image" src="<?php echo BASE.'/Item/Image/'.$this->itemID() ?>" />
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12 item-desc-header"><strong>Item description</strong></div>
                                    <div class="col-md-12 item-desc">
                                        <?php echo $this->itemDescription() ?> </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12"><strong>Price</strong></div>
                                    <div class="col-md-12">
                                        <h2>$<?php echo $this->itemPrice() ?></h2></div>
                                </div>
                                <div class="row item-badges">
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-4 col-4"><strong>Quantity</strong></div>
                                            <div class="col-md-6 col-sm-8 col-8"><span class="badge badge-pill badge-primary"><?php echo $this->itemQuantity() ?></span></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-4 col-4"><strong>Condition</strong></div>
                                            <div class="col-md-6 col-sm-8 col-8"><span class="badge badge-pill badge-success"><?php echo $this->itemCondition() ?></span></div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-4 col-4"><strong>Status</strong></div>
                                            <div class="col-md-6 col-sm-8 col-8"><span class="badge badge-pill badge-warning"><?php echo $this->itemType() ?></span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                </div>
                                <div class="row">
                                </div>
                            </div>
                        </div> <!-- end .row -->
                    </div> <!-- end .container -->

					<?php if ($this->isLoggedInUser() && $this->itemIsActive()) {  ?>

                        <div class="container" >
                            <hr />

                            <div class="row col-md-12 item-desc-header top-n-tail">
								<?php if ($this->isItemForSale()) {
									echo '<strong>Send a message to the seller</strong>';
								} else {
									echo '<strong>Send a message to the buyer</strong>';
								} ?>

                            </div>

							<?php if ($this->hasError()) { ?>
                                <div class="alert alert-danger"><?php echo $this->error() ?></div>
							<?php } ?>

							<?php if ($this->hasInfoMessage()) { ?>
                                <div class="alert alert-info"><?php echo $this->infoMessage()?></div>
							<?php } ?>

                            <div>
                                <form data-toggle="validator" role="form" method="post" action="<?php echo BASE . '/Item/View/' . $this->itemID() ?>">
                                    <textarea rows="5" class="form-control" name="message" id="message" placeholder="Enter a message..." required></textarea>
                                    <div class="top-n-tail">
                                        <button type="submit" name="sendMessage" id="sendMessage" class="btn btn-primary">Send message</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="container" >
                            <hr />

                            <strong>Items that match this item:</strong>

                            <div class="row">
								<?php foreach ($this->matchedItems() as $matchedItem) { ?>
                                    <div class="col-sm-6 col-md-4 col-xl-3 top-n-tail">
                                        <div class="card myBox">
                                            <img class="card-img-top" src="<?php echo BASE.'/Item/Thumb/'.$matchedItem['otherItem']['itemID'] ?>" alt="">
                                            <div class="card-body">
                                                <h4 class="card-title truncate">
                                                    <a href="<?php echo BASE . '/Item/View/' . $matchedItem['otherItemID']; ?>"><?php echo ucwords (strtolower( ( $matchedItem['otherItem']['title'] ) ) ); ?></a>
                                                </h4>
                                                <h3 class="card-text text-center price top-padding"><?php echo '$' . $matchedItem['otherItem']['price'] ?></h3>
                                                <a href="<?php echo BASE.'/Item/View/'.$matchedItem['otherItem']['itemID'] ?>" class="btn btn-primary btn-block">Info</a>
                                            </div>
                                        </div>
                                    </div>
								<?php } ?>
                            </div>
                        </div>
					<?php } ?>
				<?php } ?>


		</div> <!-- end col-9 -->
		<!-- Right margin -->
		<div class="col"></div>
	</div> <!-- end row -->
</div> <!-- end container -->
