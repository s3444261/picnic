<?php
/**
 *
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
 * The Humphree class is the application programming interface for
 * the model.
 */
class Humphree {
    private $_db;
    private $_system;
    
    // Constructor
    function __construct(PDO $pdo) {
        $this->_db = $pdo;
        $this->_system = new System ( $pdo );
    }
    
    /**
     * Allows a user to create their own account and join Humphree granting them access to add
     * and update items as well as view.
     *
     * @param string $userName
     *            Provides the User Name for the account.
     * @param string $email
     *            Provides an email address for the account.
     * @param string $password
     *            Provides a password for the account.
     * @return bool
     */
    public function createAccount(string $userName, string $email, string $password): bool {
        $user = new User ( $this->_db );
        $user->user = $userName;
        $user->email = $email;
        $user->password = $password;
        
        if ($this->_system->createAccount ( $user )) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Returns a UserID on receipt of an activation code.
     *
     * @param string $activationCode
     *            A 32bit string passed through a URL.
     * @return int
     */
    public function getUserIdByActivationCode(string $activationCode): int {
        $user = new User ( $this->_db );
        $user->activate = $activationCode;
        
        return $this->_system->getUserIdByActivationCode ( $user );
    }
    
    /**
     * Returns the UserID for the given email address.
     *
     * @param string $emailAddress
     *            The email address for which to search.
     * @return int The UserID if one was found, otherwise zero.
     */
    public function getUserIdByEmailAddress(string $emailAddress): int {
        $user = new User ( $this->_db );
        $user->email = $emailAddress;
        
        return $this->_system->getUserIdByEmailAddress ( $user );
    }
    
    /**
     * Verifies the email address of the new user and makes the account active.
     *
     * @param int $userID
     *            The userID of the account holder.
     * @return bool
     */
    public function activateAccount(int $userID): bool {
        $user = new User ( $this->_db );
        $user->userID = $userID;
        if ($this->_system->activateAccount ( $user )) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Allows a user or administrator to change a password for an account.
     *
     * @param int $userID
     *            UserID of the account.
     * @param string $password
     *            Password of the account.
     * @return bool
     */
    public function changePassword(int $userID, string $password): bool {
        $user = new User ( $this->_db );
        $user->userID = $userID;
        $user->password = $password;
        if ($this->_system->changePassword ( $user )) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Allows a user to generate a new password which is sent to the users account via email.
     *
     * @param string $email
     *            Email address of the account.
     * @return array
     */
    public function forgotPassword(string $email): array {
        $user = new User ( $this->_db );
        $user->email = $email;
        $user = $this->_system->forgotPassword ( $user );
        $userArray = [];
        $userArray ['userID'] = $user->userID;
        $userArray ['email'] = $user->email;
        $userArray ['password'] = $user->password;
        return $userArray;
    }
    
    /**
     * Allows an administrator to add a user and pre-activate the account.
     *
     * @param string $userName
     *            User name of the account.
     * @param string $email
     *            Email address of the account.
     * @param string $password
     *            Password of the account.
     * @return int The new user's ID if successful. Zero means failure.
     */
    public function addUser(string $userName, string $email, string $password): int {
        $user = new User ( $this->_db );
        $user->user = $userName;
        $user->email = $email;
        $user->password = $password;
        return ($this->_system->addUser ( $user ));
    }
    
    /**
     * Allows an administrator to update a users details.
     *
     * @param array $userArray
     *            An array of user attributes.
     * @return bool
     */
    public function updateUser(array $userArray): bool {
        $user = new User ( $this->_db );
        $user->userID = $userArray ['userID'];
        $user->user = $userArray ['user'];
        $user->email = $userArray ['email'];
        $user->status = $userArray ['status'];
        return ($this->_system->updateUser ( $user ));
    }
    
    /**
     * Allows an administrator to retrieve a user.
     *
     * @param int $userID
     *            ID of a user.
     * @return array
     */
    public function getUser(int $userID): array {
        $userArray = [];
        $user = new User ( $this->_db );
        $user->userID = $userID;
        $user = $this->_system->getUser ( $user );
        $userArray ['userID'] = $user->userID;
        $userArray ['user'] = $user->user;
        $userArray ['email'] = $user->email;
        $userArray ['status'] = $user->status;
        $userArray ['blocked'] = $user->blocked;
        $userArray ['activate'] = $user->activate;
        return $userArray;
    }
    
    /**
     * Allows an administrator to retrieve all users and display them paginated.
     *
     * @param int $page
     *            The number of pages to be displayed
     * @param int $usersPerPage
     *            The number of users to be displayed on each page.
     * @return array
     */
    public function getUsers(int $page, int $usersPerPage): array {
        $usersArray = [];
        $users = $this->_system->getUsers ( $page, $usersPerPage );
        
        foreach ( $users as $user ) {
            $userArray = [];
            $userArray ['userID'] = $user->userID;
            $userArray ['user'] = $user->user;
            $userArray ['email'] = $user->email;
            $userArray ['status'] = $user->status;
            $userArray ['blocked'] = $user->blocked;

            $usersArray [] = $userArray;
        }
        return $usersArray;
    }

    public function countUsers(): int {
        return $this->_system->countUsers();
    }

    /**
     * Allows an administrator to suspend a users account.
     *
     * @param int $userID
     *            The ID of the user.
     * @return bool
     */
    public function blockUser(int $userID): bool {
        $user = new User ( $this->_db );
        $user->userID = $userID;
        $user->get();
        if ($this->_system->blockUser ( $user )) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Allows an administrator to un-suspend a users account.
     *
     * @param int $userID
     *            The ID of the user.
     * @return bool
     */
    public function unblockUser(int $userID): bool {
        $user = new User ( $this->_db );
        $user->userID = $userID;
        $user->get();
        if ($this->_system->unblockUser ( $user )) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Allows an administrator to delete a user from the database.
     *
     * @param int $userID
     *            ID of the user.
     * @return bool
     */
    public function deleteUser(int $userID): bool {
        $user = new User ( $this->_db );
        $user->userID = $userID;
        if ($this->_system->deleteUser ( $user )) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Allows an administrator to add a category and specify it's position in the hierarchy.
     *
     * @param int $parentID
     *            The ID of the parent category.
     * @param string $category
     *            The name of the category.
     * @return bool
     */
    public function addCategory(int $parentID, string $category): bool {
        $cat = new Category ( $this->_db );

        if ($parentID !== Category::ROOT_CATEGORY)
        {
            $cat->parentID = $parentID;
        }

        $cat->category = $category;
        if ($this->_system->addCategory ( $cat )) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Allows an administrator to update a categories name and its position in the hierarchy.
     *
     * @param int $categoryID
     *            The ID of a category.
     * @param int $parentID
     *            The ID of the parent category.
     * @param string $category
     *            The name of the category.
     * @return bool
     */
    public function updateCategory(int $categoryID, int $parentID, string $category): bool {
        $cat = new Category ( $this->_db );
        $cat->categoryID = $categoryID;
        $cat->parentID = $parentID;
        $cat->category = $category;
        if ($this->_system->updateCategory ( $cat )) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Allows an administrator to completely delete a category and its associated content.
     *
     * @param int $categoryID
     *            The ID of the category.
     * @return bool
     */
    public function deleteCategory(int $categoryID): bool {
        $category = new Category ( $this->_db );
        $category->categoryID = $categoryID;
        if ($this->_system->deleteCategory ( $category )) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Retrieves a category and its attributes from the database.
     *
     * @param int $categoryID
     *            The ID of the category.
     * @return array
     */
    public function getCategory(int $categoryID): array {
        $cat = [];
        $category = new Category ( $this->_db );
        $category->categoryID = $categoryID;
        $category = $this->_system->getCategory ( $category );
        $cat ['categoryID'] = $category->categoryID;
        $cat ['parentID'] = $category->parentID;
        $cat ['category'] = $category->category;
        
        return $cat;
    }
    
    /**
     * Returns all categories.
     *
     * @return array
     */
    public function getCategories(): array {
        $cats = [];
        $categories = $this->_system->getCategories ();
        
        foreach ( $categories as $category ) {
            $cat = [];
            $cat ['categoryID'] = $category->categoryID;
            $cat ['parentID'] = $category->parentID;
            $cat ['category'] = $category->category;
            
            $cats [] = $cat;
        }
        
        return $cats;
    }
    
    /**
     * Returns all categories belonging to a parent category.
     *
     * @param int $parentID
     *            The ID of the parent category.
     * @return array
     */
    public function getCategoriesIn(int $parentID): array {
        $cats = [];
        $categories = $this->_system->getCategoriesIn ( $parentID );
        
        foreach ( $categories as $category ) {
            $cat = [];
            $cat ['categoryID'] = $category->categoryID;
            $cat ['parentID'] = $category->parentID;
            $cat ['category'] = $category->category;
            
            $cats [] = $cat;
        }
        
        return $cats;
    }
    
    /**
     * Returns the number of items in a category.
     *
     * @param int $categoryID
     *            The ID of a category.
     * @return int
     */
    public function countCategoryItems(int $categoryID): int {
        $category = new Category ( $this->_db );
        $category->categoryID = $categoryID;
        $numCategoryItems = $this->_system->countCategoryItems ( $category );
        
        return $numCategoryItems;
    }
    
    /**
     * Counts the number of Comments for an Item.
     *
     * @param int $itemID            
     * @return int
     */
    public function countItemComments(int $itemID): int {
        $item = new Item ( $this->_db );
        $item->itemID = $itemID;
        $numItemComments = $this->_system->countItemComments ( $item );
        
        return $numItemComments;
    }

    /**
     * Retrieves all items for an itemID and returns them based on the status of the item, the number
     * of items per page and the page of items requested.
     *
     * @param int $categoryID            
     * @param int $pageNumber            
     * @param int $itemsPerPage            
     * @param string $type
     * @return array
     */
    public function getCategoryItemsByPage(int $categoryID, int $pageNumber, int $itemsPerPage, string $type): array {
        $category = new Category ( $this->_db );
        $category->categoryID = $categoryID;
        $categoryItems = $this->_system->getCategoryItemsByPage ( $category, $pageNumber, $itemsPerPage, $type );
        $its = [];
        
        foreach ( $categoryItems as $item ) {
            $it = [];
            
            $it ['itemID'] = $item->itemID;
            $it ['title'] = $item->title;
            $it ['description'] = $item->description;
            $it ['quantity'] = $item->quantity;
            $it ['itemcondition'] = $item->itemcondition;
            $it ['price'] = $item->price;
            $its [] = $it;
        }
        
        return $its;
    }

    public function getUserOwnedItems(int $userID, string $type = ''): array {
        $user = new User ( $this->_db );
        $user->userID = $userID;
        $items = $this->_system->getUserOwnedItems ( $userID );
        $result = [];

        foreach ( $items as $item ) {
            if (($type === '' || $item->type == $type) && $item->status !== 'Deleted' ) {
                $it = [];
                $it ['itemID'] = $item->itemID;
                $it ['owningUserID'] = $item->owningUserID;
                $it ['title'] = $item->title;
                $it ['description'] = $item->description;
                $it ['quantity'] = $item->quantity;
                $it ['itemcondition'] = $item->itemcondition;
                $it ['price'] = $item->price;
                $it ['status'] = $item->status;
                $result [] = $it;
            }
        }

        return $result;
    }

    /**
     * Retrieves an item.
     *
     * @param int $itemID            
     * @return array
     */
    public function getItem(int $itemID): array {
        $item = new Item ( $this->_db );
        $item->itemID = $itemID;
        $item = $this->_system->getItem ( $item );
        $it = [];

        $it ['itemID'] = $item->itemID;
        $it ['owningUserID'] = $item->owningUserID;
        $it ['title'] = $item->title;
        $it ['description'] = $item->description;
        $it ['quantity'] = $item->quantity;
        $it ['itemcondition'] = $item->itemcondition;
        $it ['price'] = $item->price;
        $it ['status'] = $item->status;
        $it ['type'] = $item->type;

        return $it;
    }

    /**
     * Adds an item.
     *
     * @param int $userID            
     * @param array $item            
     * @param int $categoryID            
     * @return int
     */
    public function addItem(int $userID, array $item, int $categoryID): int {
        $it = new Item ( $this->_db );
        $it->owningUserID = $userID;
        $it->title = $item ['title'];
        $it->description = $item ['description'];
        $it->quantity = $item ['quantity'];
        $it->itemcondition = $item ['itemcondition'];
        $it->price = $item ['price'];
        $it->type = $item ['type'];
        return  $this->_system->addItem ($it, $categoryID );
    }
    
    /**
     * Updates an item.
     *
     * @param array $item            
     * @return bool
     */
    public function updateItem(array $item): bool {
        $it = new Item ( $this->_db );
        $it->itemID = $item ['itemID'];
        $it->title = $item ['title'];
        $it->description = $item ['description'];
        $it->quantity = $item ['quantity'];
        $it->itemcondition = $item ['itemcondition'];
        $it->price = $item ['price'];
        $it->type = $item ['type'];

        return ($this->_system->updateItem ( $it ));
    }
    
    /**
     * Deletes an item and all associated database content.
     *
     * @param int $itemID            
     * @return bool
     */
    public function deleteItem(int $itemID): bool {
        $item = new Item ( $this->_db );
        $item->itemID = $itemID;
        if ($this->_system->deleteItem ( $item )) {
            return true;
        } else {
            return false;
        }
    }

    public function getFullyMatchedItemId(int $itemID) {
        return $this->_system->getFullyMatchedItemId($itemID);
    }

    public function getMatchedItemsFor(int $itemID): array {
        $matches =  $this->_system->getMatchedItemsFor($itemID);
        $results = [];
        foreach ($matches as $match) {
            $match['myItem'] = $this->getItem($match['myItemID']);
            $match['otherItem'] = $this->getItem($match['otherItemID']);
            $results[] = $match;
        }

        return $results;
    }

    public function getUserHasRating(int $userID): bool {
        return $this->_system->getUserHasRating($userID);
    }

    public function getUserRatingCount(int $userID): int {
        return $this->_system->getUserRatingCount($userID);
    }

    public function getUserRating(int $userID): int {
        return $this->_system->getUserRating($userID);
    }

    /**
     * Retrieves all comments for an item.
     *
     * @param int $itemID            
     * @return array
     */
    public function getItemComments(int $itemID): array {
        $item = new Item ( $this->_db );
        $item->itemID = $itemID;
        $itemComments = $this->_system->getItemComments ( $item );
        $cs = [];
        
        foreach ( $itemComments as $itemComment ) {
            $c = [];
            
            $c ['commentID'] = $itemComment->commentID;
            $c ['userID'] = $itemComment->userID;
            $user = new User ( $this->_db );
            $user->userID = $itemComment->userID;
            $user = $this->_system->getUser ( $user );
            $c ['user'] = $user->user;
            $c ['comment'] = $itemComment->comment;
            
            $cs [] = $c;
        }
        return $cs;
    }

    /**
     * Gets the details for a comment.
     *
     * @param int $commentID
     * @return array
     */
    public function getComment(int $commentID): array {
        $comment = new Comment ( $this->_db );
        $comment->commentID = $commentID;
        $comment->get();
        $it = [];
        $it ['commentID'] = $comment->commentID;
        $it ['itemID'] = $comment->itemID;
        $it ['toUserID'] = $comment->toUserID;
        $it ['fromUserID'] = $comment->fromUserID;
        $it ['comment'] = $comment->comment;
        $it ['status'] = $comment->status;
        $it ['created_at'] = $comment->created_at;
        $it ['updated_at'] = $comment->updated_at;

        return $it;
    }

    /**
     * Retrieves an item associated with a comment.
     *
     * @param int $commentID            
     * @return array
     */
    public function getItemComment(int $commentID): array {
        $comment = new Comment ( $this->_db );
        $comment->commentID = $commentID;
        $item = $this->_system->getItemComment ( $comment );
        $it = [];
        $it ['itemID'] = $item->itemID;
        $it ['title'] = $item->title;
        $it ['description'] = $item->description;
        $it ['quantity'] = $item->quantity;
        $it ['itemcondition'] = $item->itemcondition;
        $it ['price'] = $item->price;
        $it ['status'] = $item->status;
        
        return $it;
    }

    /**
     * Adds a comment for an item.
     *
     * @param int $fromUserID    The user ID who made the comment.
     * @param int $toUserID        The user ID at which the comment is directed.
     * @param int $itemID        The item ID to which the comment relates.
     * @param string $comment    The test of the comment.
     * @return bool                True if the comment was added successfully, false otherwise.
     */
    public function addItemComment(int $fromUserID, int $toUserID, int $itemID, string $comment): bool {
        $c = new Comment ( $this->_db );
        $c->itemID = $itemID;
        $c->comment = $comment;
        $c->toUserID = $toUserID;
        $c->fromUserID = $fromUserID;
        $c->status = 'unread';
        if ($this->_system->addItemComment ( $c )) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Updates a comment.
     *
     * @param array $c            
     * @return bool
     */
    public function updateItemComment(array $c): bool {
        $comment = new Comment ( $this->_db );
        $comment->commentID = $c ['commentID'];

        // the only things that can be sensibly updated in a comment, are the
        // comment text and the status.
        if (isset($c ['status'])) {
            $comment->status = $c ['status'];
        }

        if (isset($c ['comment'])) {
            $comment->comment = $c ['comment'];
        }

        if ($this->_system->updateItemComment ( $comment )) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Deletes a comment.
     *
     * @param int $commentID            
     * @return bool
     */
    public function deleteItemComment(int $commentID): bool {
        $comment = new Comment ( $this->_db );
        $comment->commentID = $commentID;
        if ($this->_system->deleteItemComment ( $comment )) {
            return true;
        } else {
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
        return $this->commentsToArrays($this->_system->getUserCommentsAsSender($userID));
    }

    /**
     * Returns all comments associated with the given user ID, where the user is the receiver.
     *
     * @param int $userID    The user ID whose comments will be returned.
     * @return array           An array of associated Comment objects.
     */
    public function getUserCommentsAsReceiver(int $userID): array {
        return $this->commentsToArrays($this->_system->getUserCommentsAsReceiver($userID));
    }

    /**
     * Returns all comments associated with the given user ID, either as the sender or as the
     * receiver.
     *
     * @param int $userID    The user ID whose comments will be returned.
     * @return array           An array of associated Comment objects.
     */
    public function getAllUserComments(int $userID): array {
        return $this->commentsToArrays($this->_system->getAllUserComments($userID));
    }

    /**
     * Converts an array of Comment objects into a generic data array.
     *
     * @param array $comments    The array to be converted.
     * @return array             The converted array.
     */
    private function commentsToArrays(array $comments) : array {
        $result = [];

        foreach ($comments as $comment) {
            $result[] = $this->commentToArray($comment);
        }

        return $result;
    }

    /**
     * Converts a Comment object into a generic data array.
     *
     * @param Comment $comment     The Comment object to be converted.
     * @return array             The converted array.
     */
    private function commentToArray(Comment $comment) : array {
        $it = [];
        $it ['commentID'] = $comment->commentID;
        $it ['item'] = $this->getItem($comment->itemID);
        $it ['toUser'] = $this->getUser($comment->toUserID);
        $it ['fromUser'] = $this->getUser($comment->fromUserID);
        $it ['comment'] = $comment->comment;
        $it ['status'] =  $comment->status;
        $it ['created_at'] = $comment->created_at;
        $it ['updated_at'] = $comment->updated_at;
        return $it;
    }

    /**
     * Removes the given item form the given category.
     *
     * @param int $itemID        The item to be removed.
     * @param int $categoryID    The category from which it will be removed.
     */
    public function removeItemFromCategory(int $itemID, int $categoryID): void {
        $this->_system->removeItemFromCategory($itemID, $categoryID);
    }

    /**
     * Adds the given item to the given category.
     *
     * @param int $itemID        The item to be added.
     * @param int $categoryID    The category to which it will be added.
     */
    public function addItemToCategory(int $itemID, int $categoryID): void {
        $this->_system->addItemToCategory($itemID, $categoryID);
    }

    /**
     * Gets the first category associated with the given item.
     *
     * @param int $itemID
     * @return array
     */
    public function getItemCategory(int $itemID): array {
        return $this->_system->getItemCategory($itemID);
    }
    
    /**
     * Returns details of the user that has posted the item.
     *
     * @param int $itemID            
     * @return array
     */
    public function getItemOwner(int $itemID): array {
        $i = new Item ( $this->_db );
        $i->itemID = $itemID;
        return $this->_system->getItemOwner ( $i );
    }

    public function discardMatch($itemID, $matchedItemID) {
        $this->_system->discardMatch ($itemID, $matchedItemID);
    }

    public function acceptMatch($itemID, $matchedItemID) {
        $this->_system->acceptMatch (new ProductionMailer(), $itemID, $matchedItemID);
    }

    public function isValidRatingCode(string $accessCode) : bool {
        return $this->_system->isValidRatingCode($accessCode);
    }

    public function leaveRatingForCode(string $accessCode, int $rating) {
        $this->_system->leaveRatingForCode($accessCode, $rating);
    }

    public function isRatingLeftForCode(string $accessCode): bool {
        return $this->_system->isRatingLeftForCode($accessCode);
    }

    public function getRatingInfoForCode(string $accessCode): array {
        return $this->_system->getRatingInfoForCode ($accessCode);
    }

    public function feedbackCodeBelongsToUser(string $accessCode, int $userID) {
        return $this->_system->feedbackCodeBelongsToUser ($accessCode, $userID);
    }

    /**
     * Searches Item Titles based on search string and returns an array of Items.
     *
     * @param string $searchString
     * @param int $pageNumber
     * @param int $itemsPerPage
     * @return array
     */
    public function search(string $searchString, int $pageNumber, int $itemsPerPage): array {
        $items = $this->_system->search ( $searchString, $pageNumber, $itemsPerPage );
        $its = [];
        foreach ( $items as $item ) {
            $it = [];
            $it ['itemID'] = $item->itemID;
            $it ['title'] = $item->title;
            $it ['description'] = $item->description;
            $it ['quantity'] = $item->quantity;
            $it ['itemcondition'] = $item->itemcondition;
            $it ['price'] = $item->price;
            $it ['status'] = $item->status;
            
            $its [] = $it;
        }
        return $its;
    }

    /**
     * Advanced search method.
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
        int $pageNumber,
        int $itemsPerPage): array {

        $results = $this->_system->searchAdvanced(
            $searchText,
            $srchMinPrice,
            $srchMaxPrice,
            $srchMinQuantity,
            $srchCondition,
            $srchStatus,
            $majorCategoryID,
            $minorCategoryID,
            $pageNumber,
            $itemsPerPage);

        $its = [];
        foreach ( $results as $result ) {
            $it = [];
            $it ['itemID'] = $result->itemID;
            $it ['title'] = $result->title;
            $it ['description'] = $result->description;
            $it ['quantity'] = $result->quantity;
            $it ['itemcondition'] = $result->itemcondition;
            $it ['price'] = $result->price;
            $it ['status'] = $result->status;
            $its [] = $it;
        }
        return $its;
    }

    public function countAdvancedSearchResults(
        string $searchText,
        string $srchMinPrice,
        string $srchMaxPrice,
        string $srchMinQuantity,
        string $srchCondition,
        string $srchStatus,
        int $majorCategoryID,
        int $minorCategoryID) {
        $maxResults = 1000;

        return sizeof(
            $this->searchAdvanced(
                $searchText,
                $srchMinPrice,
                $srchMaxPrice,
                $srchMinQuantity,
                $srchCondition,
                $srchStatus,
                $majorCategoryID,
                $minorCategoryID,
                1,
                $maxResults));
    }

    public function runMatchingForAllItems()
    {
        $this->_system->runMatchingForAllItems();
    }

    public function isValidUserID(int $userID): bool {
        return $this->_system->isValidUserID($userID);
    }

}