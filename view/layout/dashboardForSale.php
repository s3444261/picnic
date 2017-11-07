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
                        <a class="nav-link active" href="<?php echo BASE; ?>/Dashboard/ForSale">Items For Sale</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE; ?>/Dashboard/Wanted">Wanted Items</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE; ?>/Dashboard/Messages">Messages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE; ?>/Dashboard/ActionItems">Action Items</a>
                    </li>
                </ul>

                <h1 id="ForSale" class="display-4 text-center top-n-tail">My Items For Sale (<?php echo count($this->data['forSaleItems']);  ?>)</h1>

				<?php if ( count($this->data['forSaleItems']) === 0) { ?>
                    <div class="text-center"><strong>You don't have any items listed for sale.</strong></div>
				<?php }else {?>
                    <table class="table table-striped">
                        <thead class="thead-dark">
                        <tr>
                            <th></th>
                            <th class="col-md-1">Title</th>
                            <th class="col-md-1">Price</th>
                            <th class="col-md-1"></th>
                        </tr>
                        </thead>
                        <tbody>
						<?php foreach ( $this->data['forSaleItems'] as $item ) { ?>
                            <tr>
                                <td><a href="<?php echo BASE . '/Item/View/' . $item['itemID']; ?>"><img src="<?php echo BASE . '/Item/Thumb/' . $item['itemID']; ?>" alt="<?php echo $item['title']; ?>" style="max-width:64px; max-height:64px" /></a></td>
                                <td><a href="<?php echo BASE . '/Item/View/' . $item['itemID']; ?>"><?php echo $item['title']; ?></a> </td>
                                <td>$<?php echo $item['price']; ?></td>
                                <td>
                                    <a class="dropdown-toggle btn btn-primary btn-block" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Actions</a>
                                    <div class="dropdown-menu">
                                        <a href="<?php echo BASE . '/Item/Edit/' . $item['itemID']; ?>" class="dropdown-item">Edit</a>
                                        <a href="<?php echo BASE . '/Item/Delete/' . $item['itemID']; ?>" class="dropdown-item" onclick="return confirm('<?php echo "Are you sure you want to delete item ". $item['title'] ."?"; ?>');">Delete</a>
                                        <a href="<?php echo BASE . '/Item/MarkFoundOrSold/' . $item['itemID']; ?>" class="dropdown-item">Mark as <?php echo $item['status'] == 'Wanted' ? 'Found' : 'Sold';  ?></a>
                                    </div>
                                </td>
                            </tr>

						<?php } ?>
                        </tbody>
                    </table>
				<?php } ?>
                <div class="row">
                    <div class="col-4"></div>
                    <div class="col">
                        <a href="<?php echo BASE . '/Item/Create'; ?>" role="button" class="btn btn-primary btn-block">Add new item</a>
                    </div>
                    <div class="col-4"></div>
                </div>
            </div>

        </div>
        <div class="col"></div>
    </div>
</div>
