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
	const DEFAULT_THUMB = __DIR__ . "/../../default_thumb.png";
	const DEFAULT_IMAGE = __DIR__ . "/../../default_image.png";


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
		$view->Render('item');
	}

	/**
	 * Sends the thumbnail image for the given item. If no thumb is found,
	 * sends a default image. If THAT is not found, returns 404.
	 *
	 * @param $itemId
	 * 			The ID of the item whose thumbnail will be sent.
	 */
	public function Thumb($itemId) {
		$subDir = substr($itemId,0 , 3);
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
		$subDir = substr($itemId,0 , 3);
		$path = self::IMAGE_DIRECTORY . "/" . $subDir . "/" . $itemId . ".jpg";

		if (file_exists($path)) {
			readfile($path);
			header("Content-Type: image/jpeg");
		} else if (file_exists(self::DEFAULT_IMAGE)) {
			readfile(self::DEFAULT_IMAGE);
			header("Content-Type: image/jpeg");
		} else {
			http_response_code(404);
		}
	}
}
