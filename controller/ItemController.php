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
	public function View($itemId) {
		$h = new Humphree(Picnic::getInstance());

		$view = new View();
		$view->SetData('item', $h ->getItem($itemId));
		$view->SetData('navData',  new NavData(NavData::ViewListings));
		$view->Render('item');
	}

	public function Add() {
		$h = new Humphree(Picnic::getInstance());

		$view = new View();
		$view->SetData('navData',  new NavData(NavData::ViewListings));
		$view->Render('itemAdd');
	}

	public function Edit($itemId) {
		$h = new Humphree(Picnic::getInstance());

		$view = new View();
		$view->SetData('item', $h ->getItem($itemId));
		$view->SetData('navData',  new NavData(NavData::ViewListings));
		$view->Render('itemEdit');
	}

	public function Delete($itemId) {
		$h = new Humphree(Picnic::getInstance());
		$h ->deleteItem($itemId);
		header('Location: ' . BASE . '/Dashboard/View');
	}

	public function MarkFoundOrSold($itemId) {
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
	public function Thumb($itemId) {

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
	public function Image($itemId) {

		// for now, I'm just using the thumb scaled up, to avoid uploading hundreds of MB of
		// images to the dev server.
		$this->Thumb($itemId);
	}
}
