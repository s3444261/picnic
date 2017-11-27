<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */
if (session_status () == PHP_SESSION_NONE) {
    session_start ();
}

/**
 * The System class transmits information from the Humphree
 * Programming Interface to the model and back to Humphree.
 */
class System {
    private $_db;
    const SEARCH_STRING = 'searchString';
    const SEARCH_TEXT = 'srchText';
    const SEARCH_MINOR_CATEGORY_ID = 'srchMinorCategory';
    const SEARCH_MAJOR_CATEGORY_ID = 'srchMajorCategory';
    const SEARCH_MIN_PRICE = 'srchMinPrice';
    const SEARCH_MAX_PRICE = 'srchMaxPrice';
    const SEARCH_MIN_QUANTITY = 'srchMinQuantity';
    const SEARCH_CONDITION = 'srchCondition';
    const SEARCH_STATUS = 'srchStatus';
    const USER_RATING_NOT_ADDED = 'The UserRating was not added!';
    const ERROR_ITEM_NOT_EXIST = 'Item does not exist!';
    const ERROR_ITEM_ID_NOT_EXIST = 'The ItemID does not exist!';
    const ERROR_USER_NOT_EXIST = 'User does not exist!';
    const ERROR_CATEGORY_NOT_EXIST = 'Category does not exist!';
    const ERROR_CATEGORY_ID_NOT_EXIST = 'The categoryID does not exist!';
    
    // Constructor
    function __construct(PDO $pdo) {
        $this->_db = $pdo;
    }
    
    /**
     * Creates an account for a user.
     *
     * @param User $user
     *            User Object.
     * @return bool
     */
    public function createAccount(User $user): bool {
        try {
            if ($user->set () > 0) {
                return true;
            } else {
                return false;
            }
        } catch ( ModelException $e ) {
            $_SESSION ['error'] = $e->getMessage ();
            return false;
        }
    }
    
    /**
     * Returns a UserID based on a 32bit activation code.
     *
     * @param User $user
     *            User object.
     * @return int
     */
    public function getUserIdByActivationCode(User $user): int {
        try {
            return $user->getUserIdByActivationCode ();
        } catch ( ModelException $e ) {
            $_SESSION ['error'] = $e->getMessage ();
            return 0;
        }
    }
    
    /**
     * Returns the UserID for the given email address.
     *
     * @param User $user
     *            User object containing the email address.
     * @return int The UserID if one was found, otherwise zero.
     */
    public function getUserIdByEmailAddress(User $user): int {
        try {
            return $user->getUserIdByEmail ();
        } catch ( ModelException $e ) {
            $_SESSION ['error'] = $e->getMessage ();
            return 0;
        }
    }
    
    /**
     * Activates a user account.
     *
     * @param User $user
     *            User Object.
     * @return bool
     */
    public function activateAccount(User $user): bool {
        if ($user->activate ()) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Allows a password to be changed for the account.
     *
     * @param User $user
     *            User object.
     * @return bool
     */
    public function changePassword(User $user): bool {
        try {
            return $user->updatePassword ();
        } catch ( ModelException $e ) {
            $_SESSION ['error'] = $e->getMessage ();
            return false;
        }
    }
    
    /**
     * Allows a user to have a new random password sent to their email address.
     *
     * @param User $user
     *            User Object.
     * @return User
     */
    public function forgotPassword(User $user): User {
        try {
            $user->userID = $user->getUserIdByEmail ();
            $user->password = $user->getRandomPassword ();
            $randomPassword = $user->password;
            try {
                $user->updatePassword ();
                $user->password = $randomPassword;
                return $user;
            } catch ( ModelException $e ) {
                $_SESSION ['error'] = $e->getMessage ();
                return $user;
            }
        } catch ( ModelException $e ) {
            $_SESSION ['error'] = $e->getMessage ();
            return $user;
        }
    }
    
    /**
     * Allows an administrator to add a user to the system.
     *
     * @param User $user
     *            User object.
     * @return int The new user's ID. Zero means failure.
     */
    public function addUser(User $user): int {
        try {
            $user->set ();
            try {
                $user->get ();
                return $user->userID;
            } catch ( ModelException $e ) {
                $_SESSION ['error'] = $e->getMessage ();
                return 0;
            }
        } catch ( ModelException $e ) {
            $_SESSION ['error'] = $e->getMessage ();
            return 0;
        }
    }
    
    /**
     * Allows an administrator to update a user.
     *
     * @param User $user
     *            User Object.
     * @return bool
     */
    public function updateUser(User $user): bool {
        try {
            $user->update ();
            return true;
        } catch ( ModelException $e ) {
            $_SESSION ['error'] = $e->getError ();
            return false;
        }
    }
    
    /**
     * Retrieves a user based on the user's ID.
     *
     * @param User $user
     *            User Object.
     * @return User
     */
    public function getUser(User $user): User {
        $u = new User ( $this->_db );
        $u->userID = $user->userID;
        try {
            $u = $u->get ();
        } catch ( ModelException $e ) {
            $_SESSION ['error'] = $e->getError ();
        }
        return $u;
    }
    
    /**
     * Retrieves all users and returns them based on number of pages and number
     * of users per page.
     *
     * @param int $pageNumber
     *            The page number.
     * @param int $usersPerPage
     *            The number of users listed on the page.
     * @return array
     */
    public function getUsers(int $pageNumber, int $usersPerPage): array {
        $usersArray = [];
        $users = new Users ( $this->_db );
        try {
            $usersArray = $users->getUsers ( $pageNumber, $usersPerPage );
        } catch ( ModelException $e ) {
            $_SESSION ['error'] = $e->getError ();
            return $usersArray;
        }
        return $usersArray;
    }
    
    /**
     * Allows an administrator to suspend an account.
     *
     * @param User $user
     *            User object.
     * @return bool
     */
    public function blockUser(User $user): bool {
        if ($user->userID > 0) {
            $user = $this->getUser ( $user );
            $user->blocked = 1;
            try {
                $user->update ();
                return true;
            } catch ( ModelException $e ) {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Allows an administrator to un-suspend an account.
     *
     * @param User $user
     *            User object.
     * @return bool
     */
    public function unblockUser(User $user): bool {
        if ($user->userID > 0) {
            $user = $this->getUser ( $user );
            $user->blocked = 0;
            try {
                $user->update ();
                return true;
            } catch ( ModelException $e ) {
                return false;
            }
        } else {
            return false;
        }
    }
    /**
     * Allows an administrator to completely delete an account and all its
     * associated entries.
     *
     * @param User $user
     *            User Object.
     * @return bool
     */
    public function deleteUser(User $user): bool {
        try {
            return $user->delete ();
        } catch ( ModelException $e ) {
            $_SESSION ['error'] = $e->getMessage ();
            return false;
        }
    }
    
    /**
     * Allows and administrator to add a Category and
     * specify its position in the hierarchy.
     *
     * @param Category $category
     *            Category Object.
     * @return bool
     */
    public function addCategory(Category $category): bool {
        try {
            $category->categoryID = $category->set ();
            try {
                $category->get ();
                return true;
            } catch ( ModelException $e ) {
                $_SESSION ['error'] = $e->getMessage ();
                return false;
            }
        } catch ( ModelException $e ) {
            $_SESSION ['error'] = $e->getMessage ();
            return false;
        }
    }
    
    /**
     * Allows and administrator to update a Category and
     * its position in the hierarchy.
     *
     * @param Category $category
     *            Category object.
     * @return bool
     */
    public function updateCategory(Category $category): bool {
        if ($category->update ()) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Allows and administrator to delete a Category and
     * all associated database content.
     *
     * @param Category $category
     *            Category object.
     * @return bool
     */
    public function deleteCategory(Category $category): bool {
        if ($category->exists ()) {
            try {
                return $category->delete ();
            } catch ( ModelException $e ) {
                $_SESSION ['error'] = self::ERROR_CATEGORY_ID_NOT_EXIST;
            }
        } else {
            $_SESSION ['error'] = self::ERROR_CATEGORY_NOT_EXIST;
        }

        return false;
    }
    
    /**
     * Retrieves a Category.
     *
     * @param Category $category
     *            Category object.
     * @return Category
     */
    public function getCategory(Category $category): Category {
        $c = $category;
        try {
            $c = $c->get ();
        } catch ( ModelException $e ) {
            $_SESSION ['error'] = $e->getMessage ();
        }
        return $c;
    }
    
    /**
     * Retrieves all Categories.
     *
     * @return array
     */
    public function getCategories(): array {
        $cat = new Categories ( $this->_db );
        try {
            $c = $cat->getCategories ();
            return $c;
        } catch ( Exception $e ) {
            $_SESSION ['error'] = $e->getMessage ();
            return [];
        }
    }
    
    /**
     * Retrieves all Categories for the given parent category.
     *
     * @param int $parentID
     *            The ID of the parent category.
     * @return array
     */
    public function getCategoriesIn(int $parentID): array {
        $c = [];
        $cat = new Categories ( $this->_db );
        try {
            $c = $cat->getCategoriesIn ( $parentID );
        } catch ( ModelException $e ) {
            $_SESSION ['error'] = $e->getMessage ();
        }
        return $c;
    }
    
    /**
     * Counts the number of items in a category.
     *
     * @param Category $category
     *            Category object.
     * @return int
     */
    public function countCategoryItems(Category $category): int {
        $ci = new CategoryItems ( $this->_db );
        $ci->categoryID = $category->categoryID;
        
        try {
            return $ci->count ();
        } catch ( ModelException $e ) {
            $_SESSION ['error'] = $e->getMessage ();
            return 0;
        }
    }
    
    /**
     * Counts the number of comments for an Item.
     *
     * @param Item $item            
     * @return int
     */
    public function countItemComments(Item $item): int {
        $ic = new Comment ( $this->_db );
        $ic->itemID = $item->itemID;
        return $ic->countCommentsForItem ();
    }

    /**
     * Retrieves all items linked to a Category.
     *
     * @param Category $category            
     * @param int $pageNumber            
     * @param int $itemsPerPage            
     * @param string $type
     * @return array
     */
    public function getCategoryItemsByPage(
        Category $category,
        int $pageNumber,
        int $itemsPerPage,
        string $type): array {
        $pn = $pageNumber;
        $ipp = $itemsPerPage;
        $categoryItemsArray = [];
        $categoryItems = new CategoryItems ( $this->_db );
        $categoryItems->categoryID = $category->categoryID;
        try {
            $categoryItemsArray = $categoryItems->getCategoryItemsByPage ( $pn, $ipp, $type );
        } catch ( ModelException $e ) {
            $_SESSION ['error'] = $e->getMessage ();
        }
        return $categoryItemsArray;
    }

    
    /**
     * Removes the given item form the given category.
     *
     * @param int $itemID
     *            The item to be removed.
     * @param int $categoryID
     *            The category from which it will be removed.
     * @return bool True if the item was removed, false if the
     *         * was never a member of the category to start with.
     */
    public function removeItemFromCategory(int $itemID, int $categoryID): bool {
        $ic = new CategoryItems ( $this->_db );
        $ic->itemID = $itemID;
        $ic->categoryID = $categoryID;
        return $ic->delete ();
    }
    
    /**
     * Adds the given item to the given category.
     *
     * @param int $itemID
     *            The item to be added.
     * @param int $categoryID
     *            The category to which it will be added.
     * @return bool True if the item was added, false if the
     *         * was never a member of the category to start with.
     */
    public function addItemToCategory(int $itemID, int $categoryID): bool {
        $ic = new CategoryItems ( $this->_db );
        $ic->itemID = $itemID;
        $ic->categoryID = $categoryID;
        try {
            return $ic->set ();
        } catch ( ModelException $e ) {
            $_SESSION ['error'] = $e->getMessage ();
            return false;
        }
    }
    
    /**
     * Gets the first category associated with the given item.
     *
     * @param int $itemID            
     * @return array
     */
    public function getItemCategory(int $itemID): array {
        try {
            $ic = new CategoryItems ( $this->_db );
            $category = $ic->getItemCategory ( $itemID );
            
            $array = [ ];
            $array ['categoryID'] = $category->categoryID;
            $array ['category'] = $category->category;
            $array ['parentID'] = $category->parentID;
            return $array;
        } catch ( ModelException $e ) {
            $_SESSION ['error'] = $e->getError();
            return [];
        }
    }
    
    /**
     * Retrieves all items owned by a user.
     *
     * @param int $userID
     *            The user whose items will be returned.
     *            
     * @return array An array of Item objects.
     */
    public function getUserOwnedItems(int $userID): array {
        try {
            $user = new User ( $this->_db );
            $user->userID = $userID;
            return $user->getUserOwnedItems ();
        } catch ( ModelException $e ) {
            $_SESSION ['error'] = $e->getError ();
        }
        
        return [ ];
    }

    /**
     * Retrieves an item
     *
     * @param Item $item            
     * @return Item
     */
    public function getItem(Item $item): Item {
        $i = new Item ( $this->_db );
        $i->itemID = $item->itemID;
        try {
            $i = $i->get ();
        } catch ( ModelException $e ) {
            $_SESSION ['error'] = $e->getError ();
        }
        return $i;
    }
    
    /**
     * Adds an item to the items table.
     *
     * @param Item $item            
     * @param int $categoryID            
     * @return int
     */
    public function addItem(Item $item, int $categoryID): int {
        $i = $item;
        try {
            $i->itemID = $i->set ();
            if ($i->itemID > 0) {
                $this->addItemToCategory ( $i->itemID, $categoryID );
                $this->runMatchingFor($i->itemID);
                return $i->itemID;
            } else {
                return 0;
            }
        } catch ( ModelException $e ) {
            $_SESSION ['error'] = $e->getError ();
            return 0;
        }
    }

    /**
     * Updates an item.
     *
     * @param Item $item            
     * @return bool
     */
    public function updateItem(Item $item): bool {
        $i = $item;
        try {
            $i->update ();
            $this->runMatchingFor($i->itemID);
            return true;
        } catch ( ModelException $e ) {
            $_SESSION ['error'] = $e->getError ();
            return false;
        }
    }
    
    /**
     * Deletes all references to the ItemID throughout the database and
     * then deletes the Item.
     *
     * @param Item $item            
     * @return bool
     */
    public function deleteItem(Item $item): bool {
        if ($item->exists ()) {
            try {
                return $item->delete ();
            } catch ( ModelException $e ) {
                $_SESSION ['error'] = $e->getMessage ();
            }
        } else {
            $_SESSION ['error'] = self::ERROR_ITEM_NOT_EXIST;
        }
        return false;
    }

    /**
     * Retrieves all comments for an item and returns them as an array of
     * Comments objects.
     *
     * @param Item $item            
     * @return array
     */
    public function getItemComments(Item $item): array {
        $ic = new Comment ( $this->_db );
        $ic->itemID = $item->itemID;
        $comments = [];
        try {
            $comments = $ic->getItemComments ();
        } catch ( ModelException $e ) {
            $_SESSION ['error'] = $e->getMessage ();
        }
        return $comments;
    }
    
    /**
     * Retrieves an Item associated with a comment.
     *
     * @param Comment $comment            
     * @return Item
     */
    public function getItemComment(Comment $comment): Item {
        $ic = new Comment ( $this->_db );
        $ic->commentID = $comment->commentID;
        $item = new Item ( $this->_db );
        try {
            $item->itemID = $ic->get()->itemID;
            $item->get ();
        } catch ( ModelException $e ) {
            $_SESSION ['error'] = $e->getMessage ();
        }
        return $item;
    }
    
    /**
     * Adds an item and a comment to ItemComments.
     *
     * @param Comment $comment            
     * @return bool
     */
    public function addItemComment(Comment $comment): bool {
        try {
            $comment->set ();
            return true;
        } catch ( ModelException $e ) {
            $_SESSION ['error'] = $e->getMessage ();
            return false;
        }
    }

    /**
     * Updates an ItemComment
     *
     * @param Comment $comment
     * @return bool
     */
    public function updateItemComment(Comment $comment): bool {
        try {
            $comment->update ();
            return true;
        } catch ( ModelException $e ) {
            $_SESSION ['error'] = $e->getMessage ();
            return false;
        }
    }
    
    /**
     * Deletes a comment from both ItemComments and Comments
     *
     * @param Comment $comment            
     * @return bool
     */
    public function deleteItemComment(Comment $comment): bool {
        $itemComment = new Comment ( $this->_db );
        $itemComment->commentID = $comment->commentID;
        try {
            $itemComment->delete();
            return true;
        } catch ( ModelException $e ) {
            $_SESSION ['error'] = $e->getMessage ();
            return false;
        }
    }

    /**
     * Returns all comments associated with the given user ID, where the user is the sender.
     *
     * @param int $userID    The user ID whose comments will be returned.
     * @return array           An array of associated Comment objects.
     */
    public function getUserCommentsAsSender(int $userID): array {
        $comment = new Comment ( $this->_db );
        try {
            return $comment->getUserCommentsAsSender($userID);
        } catch (ModelException $e) {
            $_SESSION ['error'] = $e->getMessage ();
            return [];
        }
    }

    /**
     * Returns all comments associated with the given user ID, where the user is the receiver.
     *
     * @param int $userID    The user ID whose comments will be returned.
     * @return array           An array of associated Comment objects.
     */
    public function getUserCommentsAsReceiver(int $userID): array {
        $comment = new Comment ( $this->_db );
        try {
            return $comment->getUserCommentsAsReceiver($userID);
        } catch (ModelException $e) {
            $_SESSION ['error'] = $e->getMessage ();
            return [];
        }
    }

    /**
     * Returns all comments associated with the given user ID, either as the sender or as the
     * receiver.
     *
     * @param int $userID    The user ID whose comments will be returned.
     * @return array           An array of associated Comment objects.
     */
    public function getAllUserComments(int $userID): array {
        $comment = new Comment ( $this->_db );
        try {
            return $comment->getAllUserComments($userID);
        } catch (ModelException $e) {
            $_SESSION ['error'] = $e->getMessage ();
            return [];
        }
    }

    /**
     * Retrieves everything known about a user with respect to an
     * item and returns them as an array.
     *
     * @param Item $item            
     * @return array
     */
    public function getItemOwner(Item $item): array {
        $owner = [];
        
        if ($item->exists ()) {
            try {
                $item->get();
                $user = new User ( $this->_db );
                $user->userID = $item->owningUserID;
                $user = $user->get ();
                $owner ['userID'] = $user->userID;
                $owner ['user'] = $user->user;
                $owner ['email'] = $user->email;
                return $owner;
            } catch ( ModelException $e ) {
                $_SESSION ['error'] = $e->getMessage ();
                return $owner;
            }
        } else {
            $_SESSION ['error'] = self::ERROR_ITEM_NOT_EXIST;
            return $owner;
        }
    }

    /**
     * Searches Item Titles and returns an array of Items.
     *
     * @param string $searchString
     * @param int $pageNumber
     * @param int $itemsPerPage
     * @return array
     */
    public function search(string $searchString, int $pageNumber = 1, int $itemsPerPage = 5000): array {
        
        $items = new Items ( $this->_db );
        return $items->search ($searchString, $pageNumber, $itemsPerPage);
    }

    /**
     * Interim Advanced Search method
     *
     * @param string $searchText
     * @param string $srchMinPrice
     * @param string $srchMaxPrice
     * @param string $srchMinQuantity
     * @param string $srchCondition
     * @param string $srchStatus
     * @param int $majorCategoryID
     * @param int $minorCategoryID
     * @param int $pageNumber
     * @param int $itemsPerPage
     * @return array
     */
    public function searchAdvanced(
        string $searchText,
        string $srchMinPrice,
        string $srchMaxPrice,
        string $srchMinQuantity,
        string $srchCondition,
        string $srchStatus,
        int $majorCategoryID,
        int $minorCategoryID,
        int $pageNumber = 1,
        int $itemsPerPage = 5000): array {
        $args = [];
        $args [Items::SEARCH_TEXT] = '';
        $args [Items::SEARCH_MINOR_CATEGORY_ID] = 0;
        $args [Items::SEARCH_MAJOR_CATEGORY_ID] = 0;
        $args [Items::SEARCH_MIN_PRICE] = 0;
        $args [Items::SEARCH_MAX_PRICE] = 0x7FFFFFFF;
        $args [Items::SEARCH_MIN_QUANTITY] = 1;
        $args [Items::SEARCH_CONDITION] = '';
        $args [Items::SEARCH_STATUS] = '';
        
        if(strlen($searchText) > 0){
            $args [Items::SEARCH_TEXT] = $searchText;
        }
        if($minorCategoryID > 0){
            $args [Items::SEARCH_MINOR_CATEGORY_ID] = $minorCategoryID;
        }
        if($majorCategoryID > 0){
            $args [Items::SEARCH_MAJOR_CATEGORY_ID] = $majorCategoryID;
        }
        if($srchMinPrice > 0){
            $args [Items::SEARCH_MIN_PRICE] = $srchMinPrice;
        }
        if($srchMaxPrice > 0){
            $args [Items::SEARCH_MAX_PRICE] = $srchMaxPrice;
        }
        if($srchMinQuantity > 0){
            $args [Items::SEARCH_MIN_QUANTITY] = $srchMinQuantity;
        }
        if(strlen($srchCondition) > 0){
            $args [Items::SEARCH_CONDITION] = $srchCondition;
        }
        if(strlen($srchStatus) > 0){
            $args [Items::SEARCH_STATUS] = $srchStatus;
        }
        $items = new Items ( $this->_db );
        return $items->searchAdvanced ($args, $pageNumber, $itemsPerPage);
    }

    private function runMatchingFor(int $itemID)
    {
        $item = new Item($this->_db);
        $item->itemID = $itemID;
        $item->get();

        $category = $this->getItemCategory($item->itemID);

        $desiredStatus = ($item->type === 'ForSale' ? 'Wanted' : 'ForSale');

        if ($item->type === 'ForSale') {
            // We allow a 25% variation on min price, no max.
            $minPrice = (floatval($item->price) * 0.75);
            $maxPrice = 0x7FFFFFFF;
        } elseif ($item->type === 'Wanted') {
            // We allow a 50% variation on max price, no min.
            $minPrice = 0;
            $maxPrice = (floatval($item->price) * 1.5);
        } else {
            // We effectively don't consider price.
            $minPrice = 0;
            $maxPrice = 0x7FFFFFFF;
        }

        // remove all non alphanumeric chars from the title so we don't confuse the search engine.
        $cleanedTitle = preg_replace("/[^A-Za-z0-9 ]/", ' ', $item->title);

        $searchResults = $this->searchAdvanced(
            $cleanedTitle,
            $minPrice,
            $maxPrice,
            1,
            $item->itemcondition,
            $desiredStatus,
            $category['parentID'],
            $category['categoryID'],
            1,
            10);

        $item->removeAllMatches();

        // we take a maximum of 10 matches.
        foreach (array_slice($searchResults, 0, 10) as $result) {
            $item->addMatchWith($result->itemID);
        }
    }

    public function getMatchedItemsFor(int $itemID): array {
        $item = new Item($this->_db);
        $item->itemID = $itemID;
        return $item->getMatches();
    }

    public function runMatchingForAllItems() {
        set_time_limit(3000);

        $allItems = Item::getAllItemIDs($this->_db);

        $count = 0;
        foreach ($allItems as $itemID) {
            $this->runMatchingFor($itemID);

            if ($count % 1000 === 0) {
                echo $count . ' items matched<br />';
                flush();
            }

            ++$count;
        }
    }

    public function discardMatch(int $itemID, int $matchedItemID) {
        $item = new Item($this->_db);
        $item->itemID = $itemID;
        $item->discardMatchWith($matchedItemID);
    }

    public function acceptMatch(Mailer $mailer, int $itemID, int $matchedItemID) {
        if (!$this->isFullyAcceptedMatch($itemID, $matchedItemID)) {
            $item = new Item($this->_db);
            $item->itemID = $itemID;
            $item->acceptMatchWith($matchedItemID);

            if ($this->isFullyAcceptedMatch($itemID, $matchedItemID)) {
                $this->handleCompletedTransaction($mailer, $itemID, $matchedItemID);
            }
        }
    }

    public function leaveRatingForCode(string $accessCode, int $rating) {
        UserRatings::leaveRatingForCode($this->_db, $accessCode, $rating);
    }

    public function isValidRatingCode($accessCode) {
        return UserRatings::isValidRatingCode($this->_db, $accessCode);
    }

    public function isRatingLeftForCode(string $accessCode): bool {
        return UserRatings::isRatingLeftForCode($this->_db, $accessCode);
    }

    public function getRatingInfoForCode(string $accessCode): array {
        return UserRatings::getRatingInfoForCode($this->_db, $accessCode);
    }

    public function feedbackCodeBelongsToUser(string $accessCode, int $userID) {
        return UserRatings::feedbackCodeBelongsToUser($this->_db, $accessCode, $userID);
    }

    public function getFullyMatchedItemId(int $itemID) {
        return Item::getFullyMatchedItemId($this->_db, $itemID);
    }

    public function isFullyAcceptedMatch(int $itemID, int $matchedItemID): bool {
        $item = new Item($this->_db);
        $item->itemID = $itemID;
        return $item->isFullyAcceptedMatchWith($matchedItemID);
    }

    public function handleCompletedTransaction(Mailer $mailer, int $myItemID, int $otherItemID) {
        $thisItem = new Item($this->_db);
        $thisItem->itemID = $myItemID;
        $thisUser =  $this->getItemOwner($thisItem);
        $this->markItemCompleted($myItemID);
        $thisCode = Item::createRating($this->_db, $myItemID, $otherItemID);
        $this->sendRequestFeedbackEmail(
            $mailer,
            $thisUser,
            'http://humphree.org/Dashboard/LeaveFeedback/' . $thisCode);

        $otherItem = new Item($this->_db);
        $otherItem->itemID = $otherItemID;
        $otherUser = $this->getItemOwner($otherItem);
        $this->markItemCompleted($otherItemID);
        $otherCode = Item::createRating($this->_db, $otherItemID, $myItemID);
        $this->sendRequestFeedbackEmail(
            $mailer,
            $otherUser,
            'http://humphree.org/Dashboard/LeaveFeedback/' . $otherCode);
    }

    private function markItemCompleted(int $itemID) {
        $item = new Item($this->_db);
        $item->itemID = $itemID;
        $item->markCompleted();
    }

    private function sendRequestFeedbackEmail(Mailer $mailer, array $user, string $feedbackUrl) : void {
        $mailer->SendMail(
            $user['user'],
            $user['email'],
            'Feedback on your transaction',
            '<a href="' . $feedbackUrl . '">Click here</a> to leave feedback.');
    }

    public function getUserHasRating($userID): bool {
        return UserRatings::getUserHasRating($this->_db, $userID);
    }

    public function getUserRatingCount($userID): int {
        return UserRatings::getUserRatingCount($this->_db, $userID);
    }

    public function getUserRating($userID): int {
        return UserRatings::getUserRating($this->_db, $userID);
    }

    public function countUsers(): int {
        return Users::countUsers($this->_db);
    }

    public function isValidUserID(int $userID): bool {
        return Users::isValidUserID($this->_db, $userID);
    }
}
