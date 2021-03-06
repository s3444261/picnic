<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */

/**
 * Class MessagesView
 *
 * View class for viewing and manipulating messages.
 */
class MessagesView extends View
{
    private $_inboxMessages;
    private $_sentMessages;

    function __construct() {
        $this->SetData('navData', new NavData(NavData::Account));

        $h = new Humphree(Picnic::getInstance());
        $this->_inboxMessages = $h->getUserCommentsAsReceiver($this->loggedInUserID());
        $this->_sentMessages = $h->getUserCommentsAsSender($this->loggedInUserID());
    }

    public function loggedInUserID() : int {
        return $_SESSION['userID'];
    }

    public function inboxMessages() : array {
        return $this->_inboxMessages;
    }

    public function sentMessages(): array {
        return $this->_sentMessages;
    }

    public function inboxMessageCount(): int {
        return count($this->inboxMessages());
    }

    public function sentMessageCount(): int {
        return count($this->sentMessages());
    }

    public function messageText(array $item) : string {
        return $item['comment'];
    }

    public function isRead(array $message) : bool {
        return $message['status'] === 'read';
    }

    public function isUnread(array $message) : bool {
        return $message['status'] === 'unread';
    }

    public function messageDate(array $item) : string {
        return $item['created_at'];
    }

    public function senderName(array $item) : string {
        return $item['fromUser']['user'];
    }

    public function senderID(array $item) : string {
        return $item['fromUser']['userID'];
    }

    public function recipientID(array $item) : string {
        return $item['toUser']['userID'];
    }

    public function recipientName(array $item) : string {
        return $item['toUser']['user'];
    }

    public function itemID(array $item) : string {
        return $item['item']['itemID'];
    }

    public function itemTitle(array $item) : string {
        return $item['item']['title'];
    }

    public function forSaleUrl() : string {
        return BASE . '/Dashboard/ForSale';
    }

    public function wantedUrl() : string {
        return BASE . '/Dashboard/Wanted';
    }

    public function messagesUrl() : string {
        return BASE . '/Dashboard/Messages';
    }

    public function actionItemsUrl() : string {
        return BASE . '/Dashboard/ActionItems';
    }

    public function sendMessageUrl() : string {
        return BASE . '/Dashboard/SendMessage';
    }

    public function deleteMessageUrl(array $item) : string {
        return BASE . '/Dashboard/DeleteMessage/' . $item['commentID'];
    }

    public function markReadUrl(array $item) : string {
        return BASE . '/Dashboard/MarkMessageRead/' . $item['commentID'];
    }

    public function markUnreadUrl(array $item) : string {
        return BASE . '/Dashboard/MarkMessageUnread/' . $item['commentID'];
    }

    public function viewItemUrl(array $item) : string {
        return BASE . '/Item/View/' . $this->itemID($item);
    }
}
