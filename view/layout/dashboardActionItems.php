<?php
/*
 * Authors:
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - s3418650@student.rmit.edu.au
 */
?>

<div class="container-fluid">
	<div class="row">
		<div class="col"></div>
		<div class="col-9">
			<div class="container-fluid">

				<!-- tab headers -->
				<ul class="nav nav-tabs nav-justified">
					<li class="nav-item">
						<a class="nav-link" href="<?php echo $this->forSaleUrl() ?>">Items For Sale</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="<?php echo $this->wantedUrl() ?>">Wanted Items</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="<?php echo $this->messagesUrl() ?>">Messages</a>
					</li>
					<li class="nav-item">
						<a class="nav-link active" href="<?php echo $this->actionItemsUrl() ?>">Action Items</a>
					</li>
				</ul>

				<div class="container-fluid">
					<h1 class="display-4 text-center top-n-tail">Action Items (<?php echo $this->totalMatches() ?>)</h1>

					<?php if ($this->totalMatches() === 0) { ?>
                        <div class="text-center"><strong>You have no action items.</strong></div>
					<?php } else {?>
                        <table class="table table-responsive">
                            <thead class="thead-dark">
                                <th scope="col">My Item</th>
                                <th scope="col">Match</th>
                                <th scope="col">My Status</th>
                                <th scope="col">Other Status</th>
                                <th scope="col"></th>

                            </thead>
                            <tbody>
                            <?php foreach ($this->matches() as $m) { ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo $this->viewMyItemUrl($m) ?>"><?php echo $this->myItemTitle($m) ?></a>
                                    </td>
                                    <td>
                                        <a href="<?php echo $this->viewOtherItemUrl($m) ?>"><?php echo $this->otherItemTitle($m) ?></a>
                                    </td>
                                    <td>
                                        <?php if ($this->myStatus($m) === 'Accepted') { ?>
                                            <img src="<?php echo $this->imageUrl('thumbs_up.png') ?>" width="40" height="40" />
										<?php } else if ($this->myStatus($m) === 'Rejected') { ?>
                                            <img src="<?php echo $this->imageUrl('thumbs_down.png') ?>" width="40" height="40" />
										<?php } ?>
                                    </td>
                                    <td>
										<?php if ($this->otherStatus($m) === 'Accepted') { ?>
                                            <img src="<?php echo $this->imageUrl('thumbs_up.png') ?>" width="40" height="40" />
										<?php } else if ($this->otherStatus($m) === 'Rejected') { ?>
                                            <img src="<?php echo $this->imageUrl('thumbs_down.png') ?>" width="40" height="40" />
										<?php } ?>
                                    </td>
                                    <td>
                                        <a class="dropdown-toggle btn btn-primary btn-block" data-toggle="dropdown"
                                              role="button" aria-haspopup="true" aria-expanded="false">Actions</a>
                                        <div class="dropdown-menu">
                                            <button type="button" class="dropdown-item" data-toggle="modal"
                                                    data-target="#sendDialog<?php echo $this->getMatchToItemID($m) ?>">Send message
                                            </button>

                                            <?php if ($this->myStatus($m) === 'None') { ?>
                                            <button type="button" class="dropdown-item" data-toggle="modal"
                                                    data-target="#acceptDialog<?php echo $this->getMatchToItemID($m) ?>">Accept match
                                            </button>
                                            <button type="button" class="dropdown-item" data-toggle="modal"
                                                    data-target="#deleteDialog<?php echo $this->getMatchToItemID($m) ?>">Discard match
                                            </button>

                                            <?php } ?>
                                        </div>

                                        <!-- Reply Modal -->
                                        <div class="modal fade" id="sendDialog<?php echo $this->getMatchToItemID($m) ?>"
                                             role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Send message to <?php echo $this->otherItemOwnerName($m) ?></h4>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div>About item: <?php echo $this->otherItemTitle($m) ?></div>
                                                        <form data-toggle="validator" role="form" method="post"
                                                              action="<?php echo $this->sendMessageUrl() ?>">
                                                            <input type="hidden" name="toUserID"
                                                                   value="<?php echo $this->otherItemOwnerID($m) ?>">
                                                            <input type="hidden" name="itemID"
                                                                   value="<?php echo $this->otherItemID($m) ?>">
                                                            <textarea rows="5" class="form-control" name="message"
                                                                      id="message" placeholder="Enter a message..."
                                                                      required></textarea>
                                                            <button type="submit" name="sendMessage" id="sendMessage"
                                                                    class="btn btn-primary">Send message
                                                            </button>
                                                            <button type="button" class="btn btn-default"
                                                                    data-dismiss="modal">Cancel
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Discard Modal -->
                                        <div class="modal fade" id="deleteDialog<?php echo $this->getMatchToItemID($m) ?>"
                                             role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Discard match with "<?php echo $this->otherItemTitle($m) ?>"?</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <a class="btn btn-primary"
                                                           href="<?php echo $this->discardMatchUrl($m) ?>">Yes,
                                                            discard this match</a>
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">
                                                            Cancel
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Accept Modal -->
                                        <div class="modal fade" id="acceptDialog<?php echo $this->getMatchToItemID($m) ?>"
                                             role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Accept match with "<?php echo $this->otherItemTitle($m) ?>"? This will notify the owner of that listing. If they also accept, both items will be marked as completed.</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <a class="btn btn-primary"
                                                           href="<?php echo $this->acceptMatchUrl($m) ?>">Yes,
                                                            accept this match</a>
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">
                                                            Cancel
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="col"></div>
	</div>
</div>
