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
            <div class="jumbotron">
                <div>
                    <h1 class="display-3 text-center">My Items (<?php echo count($this->data['items']);  ?>)</h1>
                    <a href="<?php echo BASE . '/Item/Add/'; ?>" role="button">Add new item</a>

                    <table class="table">
                        <thead>
                        <tr>
                            <th class="col-md-1">Title</th>
                            <th class="col-md-1">Price</th>
                            <th class="col-md-1">Status</th>
                            <th class="col-md-1"></th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php foreach ( $this->data['items'] as $item ) { ?>
                                <tr>
                                    <td><a href="<?php echo BASE . '/Item/View/' . $item['itemID']; ?>"><?php echo $item['title']; ?></a> </td>
                                    <td>$<?php echo $item['price']; ?></td>
                                    <td><?php echo $item['status']; ?></td>
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
                </div>
            </div>
        </div>
        <div class="col"></div>
    </div>
</div>
