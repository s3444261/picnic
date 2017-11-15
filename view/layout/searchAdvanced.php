<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <edwanhp@gmail.com>
 */
?>

<script>

    // Master list of all categories.
    var categories = [
		<?php foreach ($this->data['minorCategories'] as $category ) {
		if ($category['parentID'] !== null) {
			echo '[' . $category['parentID'] . ',' . $category['categoryID'] . ',"' . $category['category']  . '"],
        ';
		}
	} ?>
    ];

    // Refreshes the contents of the sub-category list, based on the currently
    // selected major category.
    function refreshSubCategories() {
        clearSubCategories();
        addSubCategoriesForCurrentMajorCategory();
        setSubCategoryEnablement();
    }

    // Removes all items from the sub-category list.
    function clearSubCategories() {
        var subCategorySelector = document.getElementById("category");

        // leave the first item, which is the placeholder "Select a Category"
        while (subCategorySelector.options.length > 1) {
            subCategorySelector.remove(subCategorySelector.options.length - 1);
        }
    }

    // Populates the sub-category list with items that are sub-categories of
    // the specified major category.
    function addSubCategoriesForCurrentMajorCategory() {
        var currentMajorCategory = selectedMajorCategory();
        for (var i = 0; i < categories.length; ++i) {
            if (categories[i][0] === currentMajorCategory) {
                addSubCategoryOption(categories[i]);
            }
        }
    }

    // Determines the currently selected major category.
    function selectedMajorCategory() {
        var majorCategorySelector = document.getElementById("majorCategory");
        return parseInt(majorCategorySelector.options[majorCategorySelector.selectedIndex].id);
    }

    // Adds a new option to the sub-category selector, using the data in the given array.
    function addSubCategoryOption(data) {
        var subCategorySelector = document.getElementById("category");
        var option = document.createElement("option");
        option.value = data[1];
        option.text = data[2];
        subCategorySelector.add(option);
    }

    // Enables or disables the sub-category selector ,based on whether there is
    // anything to select.
    function setSubCategoryEnablement() {
        var subCategorySelector = document.getElementById("category");
        subCategorySelector.disabled = (selectedMajorCategory() === -1);
        subCategorySelector.selectedIndex = 0;
    }

</script>

<div class="container-fluid">
	<div class="row">
		<div class="col"></div>
		<div class="col-9">
			<div class="jumbotron panel panel-default">
				<h1 class="display-43 text-center">Advanced Search</h1>
				<hr>

				<form data-toggle="validator" role="form" action="<?php echo BASE . '/Search/Results'?>">

					<div class="form-group">
						<label for="title">Search text</label>
						<input type="text" class="form-control" name="srch-term" id="srch-term" placeholder="Example: Tamiya BRZ model kit 1:24">
					</div>

					<div class="form-group">
						<div class="form-check form-check-inline">
							<label for="status">Search for items that are:</label>
						</div>
						<div class="form-check form-check-inline">
							<label class="radio-inline">
								<input type="radio" name="status" value="ForSale"><span class="btn btn-sm btn-info">For Sale</span>
							</label>
						</div>
						<div class="form-check form-check-inline">
							<label class="radio-inline">
								<input type="radio" name="status" value="Wanted"> <span class="btn btn-sm btn-warning">Wanted</span>
							</label>
						</div>
					</div>

					<div class="form-group">
						<label for="majorCategory">Search in category:</label>
						<select class="form-control" id="majorCategory" name="majorCategory" onchange="refreshSubCategories()" required>
							<option value="-1">All categories</option>
							<?php foreach ($this->data['majorCategories'] as $category) { ?>
								<option id="<?php echo $category['categoryID'] ?>" value="<?php echo $category['categoryID'] ?>">
									<?php echo $category['category'] ?>
								</option>
							<?php } ?>
						</select>
					</div>

					<div class="form-group">
						<label for="category">Search in sub-category</label>
						<select class="form-control" id="minorCategory" name="minorCategory" disabled="disabled" required>
							<option value="-1">All Sub-categories</option>
						</select>
					</div>



					<div class="form-group">
						<label for="quantity">Min Quantity</label>
						<input type="number" class="form-control" name="minQuantity" id="minQuantity" placeholder="Quantity" value="1">
					</div>

					<div class="form-group">
						<label for="itemcondition">Condition</label>
						<label class="radio-inline"><input type="radio" name="itemcondition" value="New">New</label>
						<label class="radio-inline"><input type="radio" name="itemcondition" value="Used">Used</label>
					</div>

					<div class="form-group">
						<label for="price">Min Price in $</label>
						<input type="number" step="0.01" min="0" class="form-control" name="minPrice" id="minPrice" placeholder="Example: 100">
					</div>

					<div class="form-group">
						<label for="price">Max Price in $</label>
						<input type="number" step="0.01" min="0" class="form-control" name="maxPrice" id="maxPrice" placeholder="Example: 250">
					</div>

					<hr />

					<button type="submit" class="btn btn-primary btn-success" name="searchAdvanced">Search</button>
				</form>

			</div>
		</div>
		<div class="col"></div>
	</div>
</div>
