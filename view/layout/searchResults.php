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
		<!-- Left margin -->
		<div class="col"></div>
		<!-- Central column -->
		<div class="col-md-9">
            <div class="text-center">
				<?php echo Pager::Render('pagination pagination-sm list-inline', $this->data ['pagerData'], true); ?>
            </div>

			<div class="tab-content ">
				<div class="tab-pane active" id="1">
					<div class="row">
						<?php foreach ( $this->data['results'] as $item ) { ?>

							<div class="col-sm-6 col-md-4 col-xl-3 top-n-tail">
								<div class="card">
									<img class="card-img-top" src="<?php echo BASE.'/Item/Thumb/'.$item['itemID'] ?>" alt="Card image cap">
									<div class="card-body">
										<h4 class="card-title truncate"><?php echo ucwords (strtolower( ( $item['title'] ) ) ) ?></h4>
										<h3 class="card-text text-center price top-padding"><?php echo '$' . $item['price'] ?></h3>
										<a href="<?php echo BASE.'/Item/View/'.$item['itemID'] ?>" class="btn btn-primary btn-block">Info</a>
									</div>
								</div>
							</div>
						<?php } ?>
					</div>
				</div> <!-- end of tab pane #1 -->

                <div class="text-center">
					<?php echo Pager::Render('pagination pagination-sm list-inline', $this->data ['pagerData'], false); ?>
                </div>

			</div> <!-- end of .tab-content -->
		</div> <!-- end .col-md-9 -->
		<div class="col"></div> <!-- right margin -->
	</div> <!-- end .row -->
</div> <!-- end .container-fluid -->
