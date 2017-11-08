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
                    <a class="nav-link active" href="<?php echo $this->categoriesURL() ?>">Categories</a>
                </li>
            </ul>

            <h1 class="display-4 text-center top-n-tail">Administration - Categories</h1>
            <p class="text-center">Note: Categories can only be deleted when they contain zero items.</p>

            <button type="button" class="btn btn-sm" data-toggle="modal" data-target="#addMajorDialog">Add top level category</button>

            <!-- Add Top Modal -->
            <div class="modal fade" id="addMajorDialog" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add top level category</h4>
                        </div>
                        <div class="modal-body">
                            <form data-toggle="validator" role="form" method="post"
                                  action="<?php echo $this->addMajorCategoryUrl() ?>">

                                <input type="text" class="form-control" name="category"
                                       id="category" placeholder="Enter a category name..."
                                       required>
                                <button type="submit" name="add" id="add" class="btn btn-primary">Add
                                    Category
                                </button>
                                <button type="button" class="btn btn-default"
                                        data-dismiss="modal">Cancel
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <ul class="list-group">
				<?php foreach ($this->getMajorCategories() as $majorCategory) { ?>
                    <li class="list-group-item">
                        <button type="button" class="btn btn-sm" data-toggle="modal"
                                data-target="#addChildDialog_<?php echo $this->categoryId($majorCategory) ?>">Add Child
                        </button>
                        <button type="button" class="btn btn-sm" data-toggle="modal"
                                data-target="#deleteMajorDialog_<?php echo $this->categoryId($majorCategory) ?>"
							<?php if (!$this->canDelete($majorCategory)) {
								echo 'disabled';
							} ?>>Delete
                        </button>
                        <a data-toggle="collapse" href="#collapse_<?php echo $this->categoryId($majorCategory) ?>">
							<?php echo $this->categoryName($majorCategory) ?>
                        </a>

                        <!-- Add Child Modal -->
                        <div class="modal fade" id="addChildDialog_<?php echo $this->categoryId($majorCategory) ?>"
                             role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Add Child
                                            to <?php echo $this->categoryName($majorCategory) ?></h4>
                                    </div>
                                    <div class="modal-body">
                                        <form data-toggle="validator" role="form" method="post"
                                              action="<?php echo $this->addMinorCategoryUrl($majorCategory) ?>">

                                            <input type="text" class="form-control" name="category"
                                                   id="category" placeholder="Enter a category name..."
                                                   required>
                                            <button type="submit" name="add" id="add" class="btn btn-primary">Add
                                                Category
                                            </button>
                                            <button type="button" class="btn btn-default"
                                                    data-dismiss="modal">Cancel
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteMajorDialog_<?php echo $this->categoryId($majorCategory) ?>"
                             role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Delete category
                                            '<?php echo $this->categoryName($majorCategory) ?>'?</h4>
                                    </div>
                                    <div class="modal-body">
                                        <a class="btn btn-primary"
                                           href="<?php echo $this->deleteCategoryUrl($majorCategory) ?>">Yes,
                                            delete this category</a>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="collapse_<?php echo $this->categoryId($majorCategory) ?>"
                             class="panel-collapse collapse">
                            <ul class="list-group">
								<?php foreach ($this->getMinorCategories($majorCategory) as $minorCategory) { ?>
                                    <li class="list-group-item">
                                        <ul>
											<?php echo $this->categoryName($minorCategory) ?>
                                            ( <?php echo $this->countCategoryItems($minorCategory) ?> )
                                            <button type="button" class="btn btn-sm" data-toggle="modal"
                                                    data-target="#deleteDialog_<?php echo $this->categoryId($minorCategory) ?>"
												<?php if (!$this->canDelete($minorCategory)) {
													echo 'disabled';
												} ?>>Delete
                                            </button>

                                            <!-- Delete Modal -->
                                            <div class="modal fade"
                                                 id="deleteDialog_<?php echo $this->categoryId($minorCategory) ?>"
                                                 role="dialog">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Delete category
                                                                '<?php echo $this->categoryName($minorCategory) ?>
                                                                '?</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <a class="btn btn-primary"
                                                               href="<?php echo $this->deleteCategoryUrl($minorCategory) ?>">Yes,
                                                                delete this category</a>
                                                            <button type="button" class="btn btn-default"
                                                                    data-dismiss="modal">
                                                                Cancel
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </ul>
                                    </li>
								<?php } ?>
                            </ul>
                        </div>
                    </li>
				<?php } ?>
            </ul>
        </div> <!-- end .col-md-9 -->
        <div class="col"></div> <!-- right margin -->
    </div> <!-- end .row -->
</div> <!-- end .container-fluid -->
