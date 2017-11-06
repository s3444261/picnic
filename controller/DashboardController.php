<?php
/*
 * Authors: 
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

require_once  __DIR__ . '/../config/Picnic.php';

class DashboardController {

	public function index() {
		header('Location: ' . BASE . '/Dashboard/ForSale');
	}

	public function View() {
		header('Location: ' . BASE . '/Dashboard/ForSale');
	}

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

	public function Messages() {
		if ($this->auth()) {
			$view = new MessagesView();
			$view->Render('dashboardMessages');
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	public function ActionItems() {
			if ($this->auth()) {
			$view = new ActionItemsView();
			$view->Render('dashboardActionItems');
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

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

	private function auth(){
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

	private function sanitize(array $dataToSanitize): array {
		$result = [];

		foreach ($dataToSanitize as $x => $x_value) {
			$result[$x] = filter_var($x_value, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
		}

		return $result;
	}
}
