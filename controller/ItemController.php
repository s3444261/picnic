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

class ItemController {

	const THUMB_DIRECTORY = __DIR__ . "/../../item_thumbs/";
	const IMAGE_DIRECTORY = __DIR__ . "/../../item_images/";
	const DEFAULT_THUMB = __DIR__ . "/../img/default_thumb.png";
	const DEFAULT_IMAGE = __DIR__ . "/../img/default_image.png";


	/**
	 * Displays the item details page.
	 *
	 * @param $itemId
	 * 			The iID of the item to be displayed.
	 */
	public function View(int $itemId) {
		$h = new Humphree(Picnic::getInstance());

		$view = new View();
		$view->SetData('item', $h ->getItem($itemId));
		$view->SetData('navData',  new NavData(NavData::ViewListings));
		$view->Render('item');
	}

	public function Create() {
		if ($this->auth()) {
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				unset($_SESSION['itemAdd']);
				$view = new ItemView();
				$view->Render('itemAdd');
				return;
			} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				if (isset($_POST['confirm'])) {
					try {
						$_SESSION['itemAdd'] = $_POST;
						$this->validateItemData($_POST);
						$view = new ItemView();
						$view->Render('itemAddConfirm');
					} catch (ValidationException $e) {
						$_SESSION['error'] = $e->getError();
						$view = new ItemView();
						$view->Render('itemAdd');
					}
				} else if (isset($_POST['commit'])) {
					try {
						$h = new Humphree(Picnic::getInstance());
						$itemID = $h->addItem($_SESSION['userID'], $_SESSION['itemAdd'], intval($_SESSION['itemAdd']['category']));
						unset($_SESSION['itemAdd']);
						header('Location: ' . BASE . '/Item/View/' . $itemID);
					} catch (ValidationException $e) {
						$_SESSION['error'] = $e->getError();
						$view = new ItemView();
						$view->Render('itemAdd');
					}
				} else if (isset($_POST['cancel'])) {
					unset($_SESSION['itemAdd']);
					header('Location: ' . BASE . '/Dashboard/View');
				} else {
					$view = new ItemView();
					$view->Render('itemAdd');
				}
				return;
			}
		}

		header('Location: ' . BASE . '/Home');
	}

	public function Edit($itemID) {
		if ($this->auth()) {
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				// TODO: This should be enabled, turned off for now to work around an issue on the production server.
			 	//	unset($_SESSION['itemAdd']);
				// TODO:
				$view = new ItemView();
				$h = new Humphree(Picnic::getInstance());
				$_SESSION['itemAdd'] = $h->getItem($itemID);
				$category = $h->getItemCategory($itemID);
				$_SESSION['itemAdd']['category'] = $category['categoryID'];
				$_SESSION['itemAdd']['majorCategory'] = $category['parentID'];
				$view->Render('itemEdit');
				return;
			} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				if (isset($_POST['confirm'])) {
					try {
						$_SESSION['itemAdd'] = $_POST;
						$_SESSION['itemAdd']['itemID'] = $itemID;
						$this->validateItemData($_POST);
						$view = new ItemView();
						$view->Render('itemEditConfirm');
					} catch (ValidationException $e) {
						$_SESSION['error'] = $e->getError();
						$view = new ItemView();
						$view->Render('itemEdit');
					}
				} else if (isset($_POST['commit']) && isset($_SESSION['itemAdd'])) {
					try {
						$h = new Humphree(Picnic::getInstance());
						$h->updateItem($_SESSION['itemAdd']);

						$originalCategory = $h->getItemCategory($itemID)['categoryID'];
						if ($originalCategory !=$_SESSION['itemAdd']['category']) {
							$h->removeItemFromCategory($_SESSION['itemAdd']['itemID'], $originalCategory);
							$h->addItemToCategory($_SESSION['itemAdd']['itemID'], $_SESSION['itemAdd']['category']);
						}

						unset($_SESSION['itemAdd']);
						header('Location: ' . BASE . '/Item/View/' . $itemID);
					} catch (ValidationException $e) {
						$_SESSION['error'] = $e->getError();
						$view = new ItemView();
						$view->Render('itemEdit');
					}
				} else if (isset($_POST['cancel'])) {
					unset($_SESSION['itemAdd']);
					header('Location: ' . BASE . '/Dashboard/View');
				} else {
					$view = new ItemView();
					$view->Render('itemEdit');
				}
				return;
			}
		}

		header('Location: ' . BASE . '/Home');
	}

	private function validateItemData($data) {
		$validate = new Validation ();

		if (isset($data['status'])) {
			$validate->emptyField($data['status']);
		} else {
			throw new ValidationException('Please enter a listing type.');
		}

		if (isset($data['majorCategory'])) {
			$validate->emptyField($data['majorCategory']);
			$validate->number($data['majorCategory']);
			$validate->numberGreaterThanZero($data['majorCategory']);
		} else {
			throw new ValidationException('Please select a category.');
		}

		if (isset($data['category'])) {
			$validate->emptyField($data['category']);
			$validate->number($data['category']);
			$validate->numberGreaterThanZero($data['category']);
		} else {
			throw new ValidationException('Please select a sub-category.');
		}

		if (isset($data['title'])) {
			$validate->emptyField($data['title']);
		} else {
			throw new ValidationException('Please enter a title.');
		}

		if (isset($data['description'])) {
			$validate->emptyField($data['description']);
		} else {
			throw new ValidationException('Please enter a description.');
		}

		if (isset($data['itemcondition'])) {
			$validate->emptyField($data['itemcondition']);
		} else {
			throw new ValidationException('Please select an item condition.');
		}
		if (isset($data['quantity'])) {
			$validate->number($data ['quantity']);
		} else {
			throw new ValidationException('Please enter a quantity.');
		}

		if (isset($data['price'])) {
			$validate->number($data ['price']);
		} else {
			throw new ValidationException('Please enter a price.');
		}
	}

	public function Delete(int $itemId) {
		$h = new Humphree(Picnic::getInstance());
		$h ->deleteItem($itemId);
		header('Location: ' . BASE . '/Dashboard/View');
	}

	public function MarkFoundOrSold(int $itemId) {
		$h = new Humphree(Picnic::getInstance());

		$view = new View();
		$view->SetData('item', $h ->getItem($itemId));
		$view->SetData('navData',  new NavData(NavData::ViewListings));
		$view->Render('itemMarkFoundOrSold');
	}

	/**
	 * Sends the thumbnail image for the given item. If no thumb is found,
	 * sends a default image. If THAT is not found, returns 404.
	 *
	 * @param $itemId
	 * 			The ID of the item whose thumbnail will be sent.
	 */
	public function Thumb(int $itemId) {

		// to avoid killing the file system with millions of files in a single directory,
		// we store images in a structure like so:
		//
		// THUMB_DIRECTORY/XXX/YYYYYYY.jpg
		//
		// ... where XXX is the number of thousands in the item ID, and YYYYYY is the item ID itself.
		//
		// Examples:
		// Item 34567 would be found at:  THUMB_DIRECTORY/34/34567.jpg
		// Item 1234 would be found at:  THUMB_DIRECTORY/1/1234.jpg
		// Item 8 would be found at:  THUMB_DIRECTORY/0/8.jpg
		$paddedItemId = str_pad($itemId, 4, '0', STR_PAD_LEFT);
		$subDir = substr($paddedItemId, 0 , strlen($paddedItemId) - 3);

		$path = self::THUMB_DIRECTORY . "/" . $subDir . "/" .$itemId . ".jpg";

		if (file_exists($path)) {
			readfile($path);
			header("Content-Type: image/jpeg");
		} else if (file_exists(self::DEFAULT_THUMB)) {
			readfile(self::DEFAULT_THUMB);
			header("Content-Type: image/jpeg");
		} else {
			http_response_code(404);
		}
	}

	/**
	 * Sends the main image for the given item. If no image is found,
	 * sends a default image. If THAT is not found, returns 404.
	 *
	 * @param $itemId
	 * 			The ID of the item whose image will be sent.
	 */
	public function Image(int $itemId) {

		// for now, I'm just using the thumb scaled up, to avoid uploading hundreds of MB of
		// images to the dev server.
		$this->Thumb($itemId);
	}

	/**
	 * Determines whether the user is currently logged in.
	 */
	private function auth() : bool {

		return (isset($_SESSION[MODULE]))
			&& (isset($_SESSION['userID']))
			&& ($_SESSION['userID'] > 0);
	}

}
