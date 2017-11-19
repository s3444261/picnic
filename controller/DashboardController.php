<?php

require_once  __DIR__ . '/../config/Picnic.php';

/**
 * Controller for user dashboard-related functions.
 *
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */
class DashboardController {

	/**
	 * Displays a page listing the current user's For Sale items.
	 */
	public function ForSale() {
		if ($this->auth()) {
			$h = new Humphree(Picnic::getInstance());

			$view = new View();
			$view->SetData('forSaleItems', $h->getUserOwnedItems($_SESSION['userID'], "ForSale"));
			$view->SetData('navData', new NavData(NavData::Account));
			$view->Render('dashboardForSale');
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	/**
	 * Displays a page listing the current user's Wanted items.
	 */
	public function Wanted() {
		if ($this->auth()) {
			$h = new Humphree(Picnic::getInstance());

			$view = new View();
			$view->SetData('wantedItems', $h->getUserOwnedItems($_SESSION['userID'],  "Wanted"));
			$view->SetData('navData', new NavData(NavData::Account));
			$view->Render('dashboardWanted');
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	/**
	 * Displays a page listing the current user's messages.
	 */
	public function Messages() {
		if ($this->auth()) {
			$view = new MessagesView();
			$view->Render('dashboardMessages');
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	/**
	 * Displays a page listing the current user's action items.
	 */
	public function ActionItems() {
			if ($this->auth()) {
			$view = new ActionItemsView();
			$view->Render('dashboardActionItems');
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	/**
	 * Sends a message to another user, using posted data.
	 */
	public function SendMessage() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if (isset($_POST['sendMessage'])) {
				if (isset($_POST['message'])) {
					$comment = $this->sanitize($_POST)['message'];
					$h = new Humphree(Picnic::getInstance());
					$h->addItemComment($_SESSION['userID'], intval($_POST['toUserID']), intval($_POST['itemID']), $comment);
					header('Location: ' . BASE . '/Dashboard/Messages');
					return;
				}
			}
		}

		header('Location: ' . BASE . '/Home');
	}

	/**
	 * Marks the given match as rejected by the current user.
	 *
	 * @param int $itemID			The user's item.
	 * @param int $matchedItemID	The matched item.
	 */
	public function DiscardMatch(int $itemID, int $matchedItemID) {
		if ($this->auth()) {
			$h = new Humphree(Picnic::getInstance());
			$itemOwner = $h->getItemOwner($itemID);

			if ($itemOwner['userID'] === $_SESSION['userID']) {
				$h->discardMatch($itemID, $matchedItemID) ;
				header('Location: ' . BASE . '/Dashboard/ActionItems');
				return;
			}
		}

		header('Location: ' . BASE . '/Home');
	}

	/**
	 * Marks the given match as accepted by the current user.
	 *
	 * @param int $itemID			The user's item.
	 * @param int $matchedItemID	The matched item.
	 */
	public function AcceptMatch(int $itemID, int $matchedItemID) {
		if ($this->auth()) {
			$h = new Humphree(Picnic::getInstance());
			$itemOwner = $h->getItemOwner($itemID);

			if ($itemOwner['userID'] === $_SESSION['userID']) {
				$h->acceptMatch($itemID, $matchedItemID) ;
				header('Location: ' . BASE . '/Dashboard/ActionItems');
				return;
			}
		}

		header('Location: ' . BASE . '/Home');
	}

	/**
	 * Removes the given message from the current's user's message inbox.
	 *
	 * @param int $messageID	The ID of the message to be deleted.
	 */
	public function DeleteMessage(int $messageID) {
		if ($this->auth()) {
			$h = new Humphree(Picnic::getInstance());
			$message = $h->getComment($messageID);

			// we can only affect messages in our inbox.
			if ($message['toUserID'] === $_SESSION['userID']) {
				$args = ['commentID' => $messageID, 'status' => 'deleted'];
				$h->updateItemComment($args);
				header('Location: ' . BASE . '/Dashboard/Messages');
				return;
			}
		}

		header('Location: ' . BASE . '/Home');
	}

	/**
	 * Displays a page requesting a rating for the transaction that is identified
	 * by the given code.
	 *
	 * @param string $code		The code of the transaction.
	 */
	public function LeaveFeedback(string $code) {
		if (!$this->auth()) {
			$_SESSION['loginRedirect'] = $_SERVER['REQUEST_URI'];
			header('Location: ' . BASE . '/Account/Login');
			return;
		}

		if (!isset($code) || $code === '') {
			header('Location: ' . BASE . '/Home');
			return;
		}

		$h = new Humphree(Picnic::getInstance());
		if (!$h->feedbackCodeBelongsToUser($code, $_SESSION['userID'])) {
			$view = new View();
			$view->Render('invalidFeedbackCode');
			return;
		}

		if ($_SERVER['REQUEST_METHOD'] === 'GET') {
			$h = new Humphree(Picnic::getInstance());

			if ($h->isRatingLeftForCode($code)) {
				$view = new LeaveFeedbackView($code);
				$view->Render('feedbackAlreadyLeft');
			} else {
				$view = new LeaveFeedbackView($code);
				$view->Render('leaveFeedback');
			}
		} else if ($_SERVER['REQUEST_METHOD'] === 'POST'
				&& isset($_POST['submit'])) {
			try {
				if (!isset($_POST['rating'])) {
					throw new ValidationException("Please select a rating score.");
				}

				$v = new Validation();
				$v->emptyField($_POST['rating']);
				$v->numberGreaterThanZero($_POST['rating']);

				$h = new Humphree(Picnic::getInstance());
				$h->leaveRatingForCode($code, $_POST['rating']);

				$view = new LeaveFeedbackView($code);
				$view->Render('feedbackLeft');
			} catch (ValidationException $e) {
				$view = new LeaveFeedbackView($code);
				$_SESSION['error'] = $e->getError();
				$view->Render('leaveFeedback');
			}
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	/**
	 * Marks the given message as read by the current user.
	 *
	 * @param int $messageID	The ID of the message.
	 */
	public function MarkMessageRead(int $messageID) {
		if ($this->auth()) {
			$h = new Humphree(Picnic::getInstance());
			$message = $h->getComment($messageID);

			// we can only affect messages in our inbox.
			if ($message['toUserID'] === $_SESSION['userID']) {
				$args = ['commentID' => $messageID, 'status' => 'read'];
				$h->updateItemComment($args);
				header('Location: ' . BASE . '/Dashboard/Messages');
				return;
			}
		}

		header('Location: ' . BASE . '/Home');
	}

	/**
	 * Marks the given message as unread by the current user.
	 *
	 * @param int $messageID	The ID of the message.
	 */
	public function MarkMessageUnread(int $messageID) {
		if ($this->auth()) {
			$h = new Humphree(Picnic::getInstance());
			$message = $h->getComment($messageID);

			// we can only affect messages in our inbox.
			if ($message['toUserID'] === $_SESSION['userID']) {
				$args = ['commentID' => $messageID, 'status' => 'unread'];
				$h->updateItemComment($args);
				header('Location: ' . BASE . '/Dashboard/Messages');
				return;
			}
		}

		header('Location: ' . BASE . '/Home');
	}

	/**
	 * Determines whether the current user has been logged in.
	 *
	 * @return bool		True if the user is logged in, false otherwise.
	 */
	private function auth(): bool {
		if(isset($_SESSION[MODULE]) && isset($_SESSION['userID'])){
			if($_SESSION['userID'] > 0){
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	/**
	 * Removes suspect text from all entries in the given array.
	 *
	 * @param array $dataToSanitize		The array to be cleaned.
	 * @return array					An array containing the cleaned items.
	 */
	private function sanitize(array $dataToSanitize): array {
		$result = [];

		foreach ($dataToSanitize as $x => $x_value) {
			$result[$x] = filter_var($x_value, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
		}

		return $result;
	}
}
