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
<div class="container-fluid">
	<div class="row">
		<div class="col"></div>
		<div class="col-9">
            <div class="container-fluid">
                <ul class="nav nav-tabs nav-justified">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE; ?>/Dashboard/ForSale">Items For Sale</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE; ?>/Dashboard/Wanted">Wanted Items</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?php echo BASE; ?>/Dashboard/Messages">Messages</a>
                    </li>
                </ul>

                <div class="container-fluid">
                    <h1 id="Wanted"  class="display-4 text-center top-n-tail">Inbox</h1>

                    <table class="table table-striped">
                        <thead class="thead-dark">
                        <tr>
                            <th class="col-md-1">Message</th>
                            <th class="col-md-1">From</th>
                            <th class="col-md-1">Item</th>
                            <th class="col-md-1">Date</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php foreach ( $this->data['inboxMessages'] as $item ) { ?>
                                <tr>
                                    <td><?php echo $item['comment']; ?></td>
                                    <td><?php echo $item['fromUser']['user']; ?></td>
                                    <td><a href="<?php echo BASE . '/Item/View/' .  $item['item']['itemID'] ?>"><?php echo $item['item']['title']; ?></a></td>
                                    <td><?php echo $item['created_at']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="container-fluid">
                    <h1 id="Wanted"  class="display-4 text-center top-n-tail">Sent Messages</h1>

                    <table class="table table-striped">
                        <thead class="thead-dark">
                        <tr>
                            <th class="col-md-1">Message</th>
                            <th class="col-md-1">To</th>
                            <th class="col-md-1">Item</th>
                            <th class="col-md-1">Date</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php foreach ( $this->data['sentMessages'] as $item ) { ?>
                                <tr>
                                    <td><?php echo $item['comment']; ?></td>
                                    <td><?php echo $item['toUser']['user']; ?></td>
                                    <td><a href="<?php echo BASE . '/Item/View/' .  $item['item']['itemID'] ?>"><?php echo $item['item']['title']; ?></a></td>
                                    <td><?php echo $item['created_at']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
		</div>
		<div class="col"></div>
	</div>
</div>
