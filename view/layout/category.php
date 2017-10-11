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
    <div class="col"></div> <!-- Left margin -->
    <div class="col-md-9">
      <h1><?php echo $this->data['currentCategoryName'] ?></h1>
    </div>
    <div class="col"></div> <!-- Right margin -->
  </div> <!-- end .row -->
	<?php if (isset ( $this->data['subCategories'] )) { ?>
  <div class="jumbotron">
    <div class="row">
      <!-- Left margin -->
      <div class="col"></div>
      <!-- Central column -->
      <div class="col-9">
        <div class="container">
    			<?php foreach ( $this->data['subCategories'] as $category ) { ?>
            <div class="col-md-4">
              <a class="btn btn-secondary" href="<?php echo BASE.'/Category/View/'.$category['categoryID'] ?>" role="button"><?php echo $category['category'] ?></a>
            </div>
    			<?php } ?>
        </div>
      </div> <!-- end .col-9 -->
      <!-- Right margin -->
      <div class="col"></div>
    </div> <!-- end .row -->
  </div> <!-- end .jumbotron -->
	<?php } ?>
</div> <!-- end .container-fluid -->

<div class="container-fluid">
  <div class="row">
    <div class="col"></div>
    <div class="col-md-9">
      <?php echo Pager::Render('pagination pagination-sm', $this->data ['pagerData'], true); ?>
    </div>
    <div class="col"></div>
  </div>
	<?php if (isset ( $this->data['forSaleItems'] ) || isset ( $this->data['wantedItems'] )) { ?>
  <div class="row">
    <!-- Left margin -->
    <div class="col"></div>
    <!-- Central column -->
    <div class="col-9">
      <div class="container">
      <ul class="nav nav-tabs nav-justified">
        <li class="nav-item active">
          <a class="nav-link" href="#1" data-toggle="tab">For sale</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#2" data-toggle="tab">Wanted</a>
        </li>
      </ul>
      <div class="tab-content ">
        <div class="tab-pane active" id="1">
          <div class="row">
            <?php foreach ( $this->data['forSaleItems'] as $item ) { ?>

              <div class="col-sm-6 col-md-4 col-xl-3 top-n-tail">
                <div class="card">
                  <img class="card-img-top" src="<?php echo BASE.'/Item/Thumb/'.$item['itemID'] ?>" alt="Card image cap">
                  <div class="card-body">
                    <h4 class="card-title"><?php echo $item['title'] ?></h4>
                    <p class="card-text">Short description of item here. Sed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero eu augue.</p>
                    <a href="<?php echo BASE.'/Item/View/'.$item['itemID'] ?>" class="btn btn-primary btn-block">Info</a>
                  </div>
                </div>
              </div>
            <?php } ?>
          </div>
        </div> <!-- end of tab pane #1 -->

        <div class="tab-pane active" id="2">
          <div class="row">
            <?php foreach ( $this->data['wantedItems'] as $item ) { ?>

              <div class="col-sm-6 col-md-4 col-xl-3 top-n-tail">
                <div class="card">
                  <img class="card-img-top" src="<?php echo BASE.'/Item/Thumb/'.$item['itemID'] ?>" alt="Card image cap">
                  <div class="card-body">
                    <h4 class="card-title"><?php echo $item['title'] ?></h4>
                    <p class="card-text">Short description of item here. Sed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero eu augue.</p>
                    <a href="<?php echo BASE.'/Item/View/'.$item['itemID'] ?>" class="btn btn-primary btn-block">Info</a>
                  </div>
                </div>
              </div>
            <?php } ?>
          </div>
        </div> <!-- end of tab pane #2 -->
      </div> <!-- end of .tab-content -->
    </div> <!-- end .container -->
  </div> <!-- end .col-9 -->
  <!-- Right margin -->
  <div class="col"></div>
</div> <!-- end .row -->
	<?php } ?>
  <?php echo Pager::Render('pagination pagination-sm', $this->data ['pagerData'], false); ?>
</div> <!-- end .container-fluid -->
