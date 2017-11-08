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
		<div class="col"></div>
		<div class="col-9">

			<!-- tab headers -->
			<ul class="nav nav-tabs nav-justified">
				<li class="nav-item">
					<a class="nav-link" href="<?php echo $this->usersUrl() ?>">Users</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?php echo $this->categoriesURL() ?>">Categories</a>
				</li>
				<li class="nav-item">
					<a class="nav-link active" href="<?php echo $this->systemUrl() ?>">System</a>
				</li>
			</ul>

			<h1 class="display-4 text-center top-n-tail">Administration - System</h1>

            <p>Rebuild the database</p>
            <div class="alert alert-danger">This will remove ALL custom data.</div>
			<button type="button" class="btn btn-sm" data-toggle="modal" data-target="#confirmDialog">Rebuild DB
			</button>

			<!-- Confirm Modal -->
			<div class="modal fade" id="confirmDialog"
				 role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Rebuild the database? This will remove ALL custom data!</h4>
						</div>
						<div class="modal-body">
							<a class="btn btn-primary"
							   href="<?php echo $this->rebuildDbUrl() ?>">Yes,
								reset all data</a>
							<button type="button" class="btn btn-default" data-dismiss="modal">
								Cancel
							</button>
						</div>
					</div>
				</div>
			</div>
		</div> <!-- end .col-md-9 -->
		<div class="col"></div> <!-- right margin -->
	</div> <!-- end .row -->
</div> <!-- end .container-fluid -->
