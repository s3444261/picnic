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
						<a class="nav-link active" href="<?php echo $this->messagesUrl() ?>">Messages</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="<?php echo $this->actionItemsUrl() ?>">Action Items</a>
					</li>
				</ul>

				<!-- inbox -->
				<div class="container-fluid">
					<h1 class="display-4 text-center top-n-tail">Inbox (<?php echo $this->inboxMessageCount() ?>)</h1>

					<?php if ($this->inboxMessageCount() === 0) { ?>
					<div class="text-center"><strong>Your inbox is empty.</strong>
					</div>
					<?php } else {?>
					<table class="table table-responsive">
                        <thead class="thead-dark">
                            <?php foreach ($this->inboxMessages() as $m) { ?>
                            <tr>
                                <th style="width: 70%">Item: <a href="<?php echo $this->viewItemUrl($m) ?>"><?php echo $this->itemTitle($m) ?></a></th>
                                <th style="width: 30%"></th>
                            </tr>
						</thead>
						<tr>
							<td>
								<p><?php echo $this->messageText($m) ?></p>
							</td>
							<td>
								<p><strong>To:</strong> <?php echo $this->senderName($m) ?></p>
								<p><strong>Date:</strong> <?php echo $this->messageDate($m) ?></p>
							<!-- <th scope="row"><?php echo $this->senderName($m) ?></th> -->
							<!-- <td> -->
								<!-- <a href="<?php echo $this->viewItemUrl($m) ?>"><?php echo $this->itemTitle($m) ?></a> -->
							<!-- </td> -->
							<!-- <td><?php echo $this->messageDate($m) ?></td> -->
							<!-- <td> -->
							<a class="dropdown-toggle btn btn-primary btn-block" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Actions</a>
							<div class="dropdown-menu">
								<button type="button" class="dropdown-item" data-toggle="modal" data-target="#replyDialog<?php echo $this->itemID($m) ?>">Reply</button>
								<?php if ($this->isUnread($m)) { ?>
								<a href="<?php echo $this->markReadUrl($m) ?>" class="dropdown-item">Mark Read</a>
								<?php } else if ($this->isRead($m)) { ?>
								<a href="<?php echo $this->markUnreadUrl($m) ?>" class="dropdown-item">Mark Unread</a>
								<?php } ?>
								<button type="button" class="dropdown-item" data-toggle="modal" data-target="#deleteDialog<?php echo $this->itemID($m) ?>">Delete</button>
							</div>

							<!-- Reply Modal -->
							<div class="modal fade" id="replyDialog<?php echo $this->itemID($m) ?>" role="dialog">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title">Reply to <?php echo $this->senderName($m) ?></h4>
										</div>
										<div class="modal-body">
											<form data-toggle="validator" role="form" method="post" action="<?php echo $this->sendMessageUrl() ?>">
												<p><?php echo $this->messageText($m) ?></p>
												<input type="hidden" name="toUserID" value="<?php echo $this->senderID($m) ?>">
												<input type="hidden" name="itemID" value="<?php echo $this->itemID($m) ?>">
												<textarea rows="5" class="form-control" name="message" id="message" placeholder="Enter a reply..." required></textarea>
												<button type="submit" name="sendMessage" id="sendMessage" class="btn btn-primary">Send message</button>
												<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
											</form>
										</div>
									</div>
								</div>
							</div>

							<!-- Delete Modal -->
							<div class="modal fade" id="deleteDialog<?php echo $this->itemID($m) ?>" role="dialog">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title">Delete message from <?php echo $this->senderName($m) ?>?</h4>
										</div>
										<div class="modal-body">
											<a class="btn btn-primary" href="<?php echo $this->deleteMessageUrl($m) ?>">Yes, delete the message</a>
											<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
										</div>
									</div>
								</div>
							</div>
						</td>
					</tr>
					<!-- <tr>
						<td class="message-row text-center" scope="row" colspan="4"><strong>Message</strong></td>
					</tr> -->
					<!-- <tr class="message-body">
						<td colspan="4">
							<?php if ($this->isUnread($m)) { ?> <strong> <?php } ?>
							<?php echo $this->messageText($m) ?>
							<?php if ($this->isUnread($m)) { ?> </strong> <?php } ?>
						</td>
					</tr> -->
					<?php } ?>
			</table>
			<?php } ?>
		</div>

				<!-- sent items -->
				<div class="container-fluid">
					<h1 class="display-4 text-center top-n-tail">Sent Messages (<?php echo $this->sentMessageCount() ?>)</h1>
					<?php if ($this->sentMessageCount() === 0) { ?>
						<div class="text-center"><strong>You have no sent messages.</strong></div>
					<?php } else {?>
						<table class="table table-responsive">
							<div class="row">
								<thead class="thead-dark">
								<?php foreach ($this->sentMessages() as $m) { ?>
									<tr>
										<th style="width: 70%">Item: <a href="<?php echo $this->viewItemUrl($m) ?>"><?php echo $this->itemTitle($m) ?></a></th>
										<th style="width: 30%"></th>
									</tr>
								</thead>
								<tr>
									<td>
										<p><?php echo $this->messageText($m) ?></p>
									</td>
								<td>
								<p><strong>To:</strong> <?php echo $this->recipientName($m) ?></p>
								<p><strong>Date:</strong> <?php echo $this->messageDate($m) ?></p>
								<a class="dropdown-toggle btn btn-primary btn-block" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Actions</a>
								<div class="dropdown-menu top-n-tail">
									<button type="button" class="dropdown-item" data-toggle="modal" data-target="#sendDialog<?php echo $this->itemID($m) ?>">Send another message</button>
								</div>
								<!-- Modal -->
								<div class="modal fade" id="sendDialog<?php echo $this->itemID($m) ?>" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title">Send a message to <?php echo $this->recipientName($m) ?></h4>
											</div>
											<div class="modal-body">
												<form data-toggle="validator" role="form" method="post" action="<?php echo $this->sendMessageUrl() ?>">
													<input type="hidden" name="toUserID" value="<?php echo $this->recipientID($m) ?>">
													<input type="hidden" name="itemID" value="<?php echo $this->itemID($m) ?>">
													<textarea rows="5" class="form-control" name="message" id="message" placeholder="Enter a message..." required></textarea>
													<button type="submit" name="sendMessage" id="sendMessage" class="btn btn-primary">Send message</button>
													<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
												</form>
											</div>
										</div>
									</div>
								</div>
							</td>
						</tr>
						<?php } ?>
					</div>
				</table>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="col"></div>
</div>
</div>
