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
		$h = new Humphree(Picnic::getInstance());

		$view = new View();
		$view->SetData('categories', $h->getCategoriesIn(Category::ROOT_CATEGORY));
		$view->SetData('subCategories', $h->getCategories());
		$view->SetData('navData',  new NavData(NavData::Account));

		if (isset($_SESSION['error'])) {
			$view->SetData('error',  $_SESSION['error']);
		}

		if (isset($_SESSION['itemAdd'])) {
			$view->SetData('item',  $_SESSION['itemAdd']);
		}

		$view->Render('itemAdd');
	}

	public function CreateConfirm() {
		if ($this->auth()) {
			try {
				$_SESSION['itemAdd'] = $_POST;

				$validate = new Validation ();
				$validate->emptyField($_POST['majorCategory']);
				$validate->emptyField($_POST['category']);
				$validate->number($_POST['majorCategory']);
				$validate->number($_POST['category']);
				$validate->numberGreaterThanZero($_POST['majorCategory']);
				$validate->numberGreaterThanZero($_POST['category']);
				$validate->emptyField($_POST['title']);
				$validate->emptyField($_POST['description']);
				$validate->emptyField($_POST['condition']);
				$validate->number($_POST ['quantity']);
				$validate->number($_POST['price']);


				$h = new Humphree(Picnic::getInstance());
				$category = $h->getCategory(intval($_POST['category']));
				$_SESSION['itemAdd']['categoryName'] = $category['category'];

				$majorCategory = $h->getCategory(intval($category['parentID']));
				$_SESSION['itemAdd']['majorCategoryName'] = $majorCategory['category'];

				$view = new View();
				$view->SetData('item',  $_SESSION['itemAdd']);
				$view->SetData('navData',  new NavData(NavData::Account));
				$view->Render('itemAddConfirm');
			} catch (ValidationException $e) {
				$_SESSION['error'] =  $e->getError();
				header('Location: ' . BASE . '/Item/Create');
			}
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	public function DoCreate() {
		if ($this->auth()) {
			if ($_SESSION['itemAdd']) {
				try {
					$h = new Humphree(Picnic::getInstance());

					$params = [];
					$params['title'] = $_SESSION['itemAdd']['title'];
					$params['description'] = $_SESSION['itemAdd']['description'];
					$params['quantity'] = $_SESSION['itemAdd']['quantity'];
					$params['itemcondition'] = $_SESSION['itemAdd']['condition'];
					$params['price'] = $_SESSION['itemAdd']['price'];
					$params['status'] = 'ForSale';

					$itemID = $h->addItem($_SESSION['userID'], $params, intval($_SESSION['itemAdd']['category']));

					unset($_SESSION['itemAdd']);

					header('Location: ' . BASE . '/Item/View/' . $itemID);
				} catch (ValidationException $e) {
					$_SESSION['error'] =  $e->getError();
					header('Location: ' . BASE . '/Item/Create');
				}
			} else {
				header('Location: ' . BASE . '/Account/Register');
			}
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	public function Edit(int $itemID) {
		$h = new Humphree(Picnic::getInstance());

		$view = new View();
		$view->SetData('categories', $h->getCategoriesIn(Category::ROOT_CATEGORY));
		$view->SetData('subCategories', $h->getCategories());
		$view->SetData('navData',  new NavData(NavData::Account));

		if (!isset($_SESSION['itemEdit']) || $_SESSION['itemEdit']['itemID'] != $itemID ) {
			$_SESSION['itemEdit'] = $h ->getItem($itemID);
			$_SESSION['itemEdit']['itemID'] = $itemID;

			$category = $h->getItemCategory($itemID);
			$_SESSION['itemEdit']['majorCategory'] = $category['parentID'];
			$_SESSION['itemEdit']['category'] = $category['categoryID'];
		}

		$view->SetData('item', $_SESSION['itemEdit']);
		$view->Render('itemEdit');
	}

	public function EditConfirm() {
		if ($this->auth()) {
			try {
				$itemID = $_SESSION['itemEdit']['itemID'];

				$_SESSION['itemEdit'] = $_POST;
				$_SESSION['itemEdit']['itemID'] = $itemID;

				$validate = new Validation ();
				$validate->emptyField($_POST['majorCategory']);
				$validate->emptyField($_POST['category']);
				$validate->number($_POST['majorCategory']);
				$validate->number($_POST['category']);
				$validate->numberGreaterThanZero($_POST['majorCategory']);
				$validate->numberGreaterThanZero($_POST['category']);
				$validate->emptyField($_POST['title']);
				$validate->emptyField($_POST['description']);
				$validate->emptyField($_POST['itemcondition']);
				$validate->number($_POST ['quantity']);
				$validate->number($_POST['price']);

				$h = new Humphree(Picnic::getInstance());
				$category = $h->getCategory(intval($_POST['category']));
				$_SESSION['itemEdit']['categoryName'] = $category['category'];

				$majorCategory = $h->getCategory(intval($category['parentID']));
				$_SESSION['itemEdit']['majorCategoryName'] = $majorCategory['category'];

				$view = new View();
				$view->SetData('item',  $_SESSION['itemEdit']);
				$view->SetData('navData',  new NavData(NavData::Account));
				$view->Render('itemEditConfirm');
			} catch (ValidationException $e) {
				$_SESSION['error'] =  $e->getError();
				header('Location: ' . BASE . '/Item/Edit/' . $itemID);
			}
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	public function DoEdit() {
		if ($this->auth()) {
			if (isset($_SESSION['itemEdit'])) {
				try {
					$itemID = $_SESSION['itemEdit']['itemID'];

					$h = new Humphree(Picnic::getInstance());
					$h->updateItem($_SESSION['itemEdit']);
					unset($_SESSION['itemEdit']);

					header('Location: ' . BASE . '/Item/View/' . $itemID);
				} catch (ValidationException $e) {
					$_SESSION['error'] =  $e->getError();
					header('Location: ' . BASE . '/Item/Create');
				}
			} else {
				header('Location: ' . BASE . '/Account/Register');
			}
		} else {
			header('Location: ' . BASE . '/Home');
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
