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
			<div class="container">
				<div class="row">
					<div class="col-md-9">
						<?php if (isset ( $this->data['item'] )) { ?>
						<h1 class="text-center"><?php echo $this->data['item']['title'] ?> </h1>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<img src="<?php echo BASE.'/Item/Image/'.$this->data['item']['itemID'] ?>" style="min-width:300px; min-height:300px" />
					</div>
					<div class="col-md-5">
						<?php if (isset ( $this->data['item'] )) { ?>
						<div class="row">
							<div class="col-md-12"><strong>Item description</strong></div>
							<div class="col-md-12">
								<?php echo $this->data['item']['description'] ?> </div>
						</div>
						<div class="row">
							<div class="col-md-12"><strong>Price</strong></div>
							<div class="col-md-12">
								<h2>$<?php echo $this->data['item']['price'] ?></h2></div>
						</div>
						<div class="row">
							<div class="col-md-3">Quantity</div>
							<div class="col-md-3">Condition</div>
							<div class="col-md-3">Status</div>
						</div>
						<div class="row">
							<div class="col-md-3"><span class="badge badge-pill badge-primary"><?php echo $this->data['item']['quantity'] ?></span></div>
							<div class="col-md-3"><span class="badge badge-pill badge-success"><?php echo $this->data['item']['itemcondition'] ?></span></div>
							<div class="col-md-3"><span class="badge badge-pill badge-warning"><?php echo $this->data['item']['status'] ?></span></div>
						</div>
						<div class="row">
						</div>
						<?php } ?>
					</div>
				</div> <!-- end .row -->
			</div> <!-- end .container -->
		</div> <!-- end col-9 -->
		<!-- Right margin -->
		<div class="col"></div>
	</div> <!-- end row -->
</div> <!-- end container -->
