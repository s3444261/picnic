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
		<?php foreach ( $this->minorCategories() as $category ) {
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
        while (subCategorySelector.options.length > 0) {
            subCategorySelector.remove(0);
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

    function selectSubCategory($id) {
        var subCategorySelector = document.getElementById("category");
        for(var i=0; i < subCategorySelector.options.length; i++)
        {
            if(subCategorySelector.options[i].value === $id) {
                subCategorySelector.selectedIndex = i;
                return;
            }
        }
    }

</script>

<div class="form-group">
    <label for="status">Type</label>
    <label class="radio-inline"><input type="radio" name="status" value="ForSale" <?php if ($this->isItemForSale()) {
			echo 'checked="checked"';
		} ?>>For Sale</label>
    <label class="radio-inline"><input type="radio" name="status" value="Wanted" <?php if ($this->isItemWanted()) {
			echo 'checked="checked"';
		} ?>>Wanted</label>
</div>

<div class="form-group">
    <label for="majorCategory">Category</label>
    <select class="form-control" id="majorCategory" name="majorCategory" onchange="refreshSubCategories()" required>
        <option value="-1" disabled <?php if (!$this->isMajorCategorySelected()) {
			echo 'selected';
		} ?>>Select a category...
        </option>
		<?php foreach ($this->majorCategories() as $category) { ?>
            <option id="<?php echo $category['categoryID'] ?>"
                    value="<?php echo $category['categoryID'] ?>"
				<?php if ($this->isSelectedMajorCategory($category['categoryID'])) {
					echo 'selected';
				} ?>>
				<?php echo $category['category'] ?>
            </option>
		<?php } ?>
    </select>
</div>

<div class="form-group">
    <label for="category">Sub-category</label>
    <select class="form-control" id="category" name="category" disabled="disabled" required>
    </select>
</div>

<div class="form-group"
    <label for="title">Title</label>
    <input type="text" class="form-control" name="title" id="title" placeholder="Example: Tamiya BRZ model kit 1:24"
           value="<?php echo $this->itemTitle() ?>" required>
</div>

<div class="form-group">
    <label for="description">Description</label>
    <textarea rows="10" class="form-control" name="description" id="description" placeholder="Description"
              required><?php echo $this->itemDescription() ?></textarea>
</div>

<div class="form-group">
    <label for="quantity">Quantity</label>
    <input type="number" class="form-control" name="quantity" id="quantity" placeholder="Quantity"
           value="<?php echo $this->itemQuantity() ?>" required>
</div>

<div class="form-group">
    <label for="itemcondition">Condition</label>
    <label class="radio-inline"><input type="radio" name="itemcondition" value="New" <?php if ($this->isItemNew()) {
			echo 'checked="checked"';
		} ?>>New</label>
    <label class="radio-inline"><input type="radio" name="itemcondition" value="Used" <?php if ($this->isItemUsed()) {
			echo 'checked="checked"';
		} ?>>Used</label>
</div>

<div class="form-group">
    <label for="price">Price in $</label>
    <input type="number" step="0.01" min="0" class="form-control" name="price" id="price" placeholder="Example: 199.99"
           value="<?php echo $this->itemPrice() ?>" required>
</div>

<script>
    refreshSubCategories();
	<?php if ($this->isMinorCategorySelected()) { ?>
    selectSubCategory("<?php echo $this->selectedMinorCategory() ?>");
	<?php } ?>
</script>
