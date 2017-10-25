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


<script>

    // Master list of all categories.
   var categories = [
		<?php foreach ( $this->data['subCategories'] as $category ) {
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
            subCategorySelector.remove(1);
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


<div class="container-fluid">
    <div class="row">
        <div class="col"></div>
        <div class="col-9">
            <div class="jumbotron panel panel-default">
                <h1 class="display-3 text-center">Add item</h1>
                <hr>

				<?php if (isset ($this->data['error'])) { ?>
                    <div class="alert alert-danger"><?php echo $this->data['error'] ?></div>
				<?php } ?>

                <form data-toggle="validator" role="form" method="post" action="CreateConfirm">
                    <div class="form-group">
                        <label for="type">Type</label>
                        <label class="radio-inline"><input type="radio" name="type" value="ForSale" <?php if (isset($this->data['item']) && isset($this->data['item']['type']) && $this->data['item']['type'] == 'ForSale') { echo 'checked="checked"'; } ?>>For Sale</label>
                        <label class="radio-inline"><input type="radio" name="type" value="Wanted" <?php if (isset($this->data['item']) &&isset($this->data['item']['type']) && $this->data['item']['type'] == 'Wanted') { echo 'checked="checked"'; } ?>>Wanted</label>
                    </div>
                    <div class="form-group">
                        <label for="majorCategory">Category</label>
                        <select class="form-control" id="majorCategory" name="majorCategory" onchange="refreshSubCategories()" required>
                            <option value="-1" disabled <?php if (!isset($this->data['item']) || !isset($this->data['item']['majorCategory'])) { echo 'selected'; }  ?>>Select a category...</option>
							<?php foreach ( $this->data['categories'] as $category ) { ?>
                                <option id="<?php echo $category['categoryID'] ?>" value="<?php echo $category['categoryID'] ?>" <?php if (isset($this->data['item']) && isset($this->data['item']['majorCategory']) && $this->data['item']['majorCategory'] == $category['categoryID']) { echo 'selected'; }  ?>><?php echo $category['category'] ?></option>
							<?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="category">Sub-category</label>
                        <select class="form-control" id="category" name="category" disabled="disabled" required>
                            <option value="-1" disabled selected>Select a category...</option>
                        </select>
                    </div>
                    <div class="form-group"
                        <label for="title">Title</label>
                        <input type="text" class="form-control" name="title" id="title" placeholder="Example: Tamiya BRZ model kit 1:24" value="<?php if (isset($this->data['item'])) { echo $this->data['item']['title']; } ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea rows="10" class="form-control" name="description" id="description" placeholder="Description" required><?php if (isset($this->data['item'])) { echo $this->data['item']['description']; } ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" name="quantity" id="quantity" placeholder="Quantity" value="<?php if (isset($this->data['item'])) { echo $this->data['item']['quantity']; } ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="condition">Condition</label>
                        <label class="radio-inline"><input type="radio" name="condition" value="New" <?php if (isset($this->data['item']) && isset($this->data['item']['condition']) && $this->data['item']['condition'] == 'New') { echo 'checked="checked"'; } ?>>New</label>
                        <label class="radio-inline"><input type="radio" name="condition" value="Used" <?php if (isset($this->data['item']) &&isset($this->data['item']['condition']) && $this->data['item']['condition'] == 'Used') { echo 'checked="checked"'; } ?>>Used</label>
                    </div>
                    <div class="form-group">
                        <label for="price">Price in $</label>
                        <input type="number" class="form-control" name="price" id="price" placeholder="Example: 199.99" value="<?php if (isset($this->data['item'])) { echo $this->data['item']['price']; } ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-success">Next</button>
                </form>
            </div>
        </div>
        <div class="col"></div>
    </div>
</div>

<script>
    refreshSubCategories();
<?php if (isset($this->data['item']) && isset($this->data['item']['category'])) { ?>
    selectSubCategory("<?php echo $this->data['item']['category'] ?>");
<?php } ?>

</script>