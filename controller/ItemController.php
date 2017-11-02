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
	const TEMP_UPLOADS_DIRECTORY = __DIR__ . "/../../temp_uploads/";
	const THUMB_DIRECTORY = __DIR__ . "/../../item_thumbs/";
	const IMAGE_DIRECTORY = __DIR__ . "/../../item_images/";
	const DEFAULT_THUMB = __DIR__ . "/../img/default_thumb.png";
	const DEFAULT_IMAGE = __DIR__ . "/../img/default_image.png";
	const IMAGE_DIMENSION = 300;
	const THUMB_DIMENSION = 64;

	/**
	 * Displays the item details page.
	 *
	 * @param $itemId
	 * 			The iID of the item to be displayed.
	 */
	public function View(int $itemId) {
		if ($_SERVER['REQUEST_METHOD'] === 'GET') {
			$h = new Humphree(Picnic::getInstance());

			$view = new View();
			$view->SetData('item', $h->getItem($itemId));
			$view->SetData('navData', new NavData(NavData::ViewListings));
			$view->Render('item');
			return;
		} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if (isset($_POST['sendMessage'])) {
				if (isset($_POST['message'])) {
					$comment = $this->sanitize($_POST)['message'];
					$h = new Humphree(Picnic::getInstance());
					$item = $h->getItem($itemId);

					$view = new View();
					$view->SetData('item', $h->getItem($itemId));
					$view->SetData('navData', new NavData(NavData::ViewListings));

					if (!$h->addItemComment($_SESSION['userID'], $item['owningUserID'], $itemId, $comment)) {
						$view->SetData('error', 'Could not send the message: ' . $_SESSION['error']);
					} else {
						$view->SetData('info', 'Message sent.');
					}

					$view->Render('item');
					return;
				}
			}
		}

		header('Location: ' . BASE . '/Item/View/' . $itemId);
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
						if (isset($_SESSION['itemAdd']) && isset($_SESSION['itemAdd']['tempImageFile'])) {
							$previousUploadedFile = $_SESSION['itemAdd']['tempImageFile'];
						}

						$_SESSION['itemAdd'] = $this->sanitize($_POST);

						if ($_FILES["image"]["name"] !== '') {
							$this->saveUploadedImageToTempDir();
						} else if (isset($previousUploadedFile)) {
							$_SESSION['itemAdd']['tempImageFile'] = $previousUploadedFile;
						}

						$this->validateItemData($_SESSION['itemAdd']);

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

						if (isset($_SESSION['itemAdd']['tempImageFile'])) {
							$this->moveImageToFinalLocations($itemID);
						}

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

	public function Edit(int $itemID) {
		if ($this->auth()) {
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
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
						if (isset($_SESSION['itemAdd']) && isset($_SESSION['itemAdd']['tempImageFile'])) {
							$previousUploadedFile = $_SESSION['itemAdd']['tempImageFile'];
						}

						$_SESSION['itemAdd'] = $this->sanitize($_POST);
						$_SESSION['itemAdd']['itemID'] = $itemID;

						if ($_FILES["image"]["name"] !== '') {
							$this->saveUploadedImageToTempDir();
						} else if (isset($previousUploadedFile)) {
							$_SESSION['itemAdd']['tempImageFile'] = $previousUploadedFile;
						}

						$this->validateItemData($_SESSION['itemAdd']);

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

						if (isset($_SESSION['itemAdd']['tempImageFile'])) {
							$this->moveImageToFinalLocations($itemID);
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

		$paddedItemId = str_pad($itemId, 4, '0', STR_PAD_LEFT);
		$subDir = substr($paddedItemId, 0 , strlen($paddedItemId) - 3);

		$path = self::IMAGE_DIRECTORY . $subDir . "/" .$itemId . ".jpg";
		$thumbPath = self::THUMB_DIRECTORY . $subDir . "/" .$itemId . ".jpg";

		if (file_exists($path)) {
			header("Content-Type: image/jpeg");
			readfile($path);

		}  else if (file_exists($thumbPath)) {
			header("Content-Type: image/jpeg");
			readfile($thumbPath);

		} else if (isset($_SESSION['itemAdd']['tempImageFile'])) {
			header("Content-Type: image/jpeg");
			readfile($_SESSION['itemAdd']['tempImageFile']);

		} else if (file_exists(self::DEFAULT_IMAGE)) {
			header("Content-Type: image/jpeg");
			readfile(self::DEFAULT_IMAGE);

		} else {
			http_response_code(404);
		}
	}

	/**
	 * Sends the last image that was uploaded, but has not yet been filed away.
	 *
	 */
	public function LastTempImage() {

		if (isset($_SESSION['itemAdd']) && isset($_SESSION['itemAdd']['tempImageFile'])) {
			$path = self::TEMP_UPLOADS_DIRECTORY . $_SESSION['itemAdd']['tempImageFile'];
			if (file_exists($path)) {
				header("Content-Type: image/jpeg");
				readfile($path);

			} else if (file_exists(self::DEFAULT_IMAGE)) {
				header("Content-Type: image/jpeg");
				readfile(self::DEFAULT_IMAGE);

			} else {
				http_response_code(404);
			}
		} else if (isset($_SESSION['itemAdd']) && isset($_SESSION['itemAdd']['itemID'])) {
			$this->Image($_SESSION['itemAdd']['itemID']);
		} else {
			header("Content-Type: image/jpeg");
			readfile(self::DEFAULT_IMAGE);

		}
	}

	/**
	 * Determines whether the user is currently logged in.
	 */
	private function auth() : bool {

		return (isset($_SESSION[MODULE]))
			&& (isset($_SESSION['userID']))
			&& ($_SESSION['userID'] > 0);
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

	private function createThumbnail($sourceFile, $targetFIle, $targetWidth, $targetHeight, $background=false) {
		list($original_width, $original_height, $original_type) = getimagesize($sourceFile);
		if ($original_width > $original_height) {
			$new_width = $targetWidth;
			$new_height = intval($original_height * $new_width / $original_width);
		} else {
			$new_height = $targetHeight;
			$new_width = intval($original_width * $new_height / $original_height);
		}
		$dest_x = intval(($targetWidth - $new_width) / 2);
		$dest_y = intval(($targetHeight - $new_height) / 2);

		if ($original_type === 1) {
			$imgt = "ImageGIF";
			$imgcreatefrom = "imagecreatefromgif";
		} else if ($original_type === 2) {
			$imgt = "ImageJPEG";
			$imgcreatefrom = "imagecreatefromjpeg";
		} else if ($original_type === 3) {
			$imgt = "ImagePNG";
			$imgcreatefrom = "imagecreatefrompng";
		} else {
			return false;
		}

		$old_image = $imgcreatefrom($sourceFile);
		$new_image = imagecreatetruecolor($targetWidth, $targetHeight); // creates new image, but with a black background

		// figuring out the color for the background
		if(is_array($background) && count($background) === 3) {
			list($red, $green, $blue) = $background;
			$color = imagecolorallocate($new_image, $red, $green, $blue);
			imagefill($new_image, 0, 0, $color);
			// apply transparent background only if is a png image
		} else if($background === 'transparent' && $original_type === 3) {
			imagesavealpha($new_image, TRUE);
			$color = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
			imagefill($new_image, 0, 0, $color);
		}

		imagecopyresampled($new_image, $old_image, $dest_x, $dest_y, 0, 0, $new_width, $new_height, $original_width, $original_height);
		$imgt($new_image, $targetFIle);
		return file_exists($targetFIle);
	}

	private function saveUploadedImageToTempDir(): void {

		if ($_FILES["image"]['name'] === 0) {
			throw new ValidationException('There was an error uploading the file.');
		}

		if ($_FILES["image"]['tmp_name'] === '') {
			throw new ValidationException('There was an error uploading the file.');
		}

		if ($_FILES["image"]['error'] !== 0) {
			throw new ValidationException('There was an error uploading the file.');
		}

		$imageFileType = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);

		if (strtolower ($imageFileType) != "jpg" && strtolower($imageFileType) != "jpeg") {
			throw new ValidationException('Only JPG and JPEG files are supported.');
		}
		set_error_handler(function() { /* ignore errors */ });
		$check = getimagesize($_FILES["image"]["tmp_name"]);
		restore_error_handler();

		if ($check === false) {
			throw new ValidationException('The file is not an image file.');
		}

		if (!file_exists(self::TEMP_UPLOADS_DIRECTORY)) {
			mkdir (self::TEMP_UPLOADS_DIRECTORY, 0777, true);
		}

		$tempFileName = bin2hex(openssl_random_pseudo_bytes(16));
		$target_file = self::TEMP_UPLOADS_DIRECTORY . $tempFileName . '.' . $imageFileType;
		$_SESSION['itemAdd']['tempImageFile'] = $tempFileName . '.' . $imageFileType;
		$this->correctImageOrientation($_FILES["image"]["tmp_name"]);
		$this->createThumbnail($_FILES["image"]["tmp_name"], $target_file, self::IMAGE_DIMENSION, self::IMAGE_DIMENSION);
	}

	// ref:   https://stackoverflow.com/a/24337547
	private function correctImageOrientation($fullpath) {
		if (function_exists('exif_read_data')) {
			ini_set('memory_limit', '256M');
			$exif = exif_read_data($fullpath);
			if($exif && isset($exif['Orientation'])) {
				$orientation = $exif['Orientation'];
				if($orientation != 1){
					$img = imagecreatefromjpeg($fullpath);
					$deg = 0;
					switch ($orientation) {
						case 3:
							$deg = 180;
							break;
						case 6:
							$deg = 270;
							break;
						case 8:
							$deg = 90;
							break;
					}
					if ($deg) {
						$img = imagerotate($img, $deg, 0);
					}
					// then rewrite the rotated image back to the disk as $filename
					imagejpeg($img, $fullpath, 100);
				} // if there is some rotation necessary
			} // if have the exif orientation info
		} // if function exists
	}

	private function moveImageToFinalLocations($itemID): void {
		$pathInFinalFolder = intval($itemID / 1000) . '/' . $itemID . '.jpg';
		$tempFile = self::TEMP_UPLOADS_DIRECTORY . $_SESSION["itemAdd"]["tempImageFile"];
		$finalFile = self::IMAGE_DIRECTORY . $pathInFinalFolder;
		$thumbFile = self::THUMB_DIRECTORY . $pathInFinalFolder;

		if (!file_exists(self::IMAGE_DIRECTORY .intval($itemID / 1000) )) {
			mkdir (self::IMAGE_DIRECTORY .intval($itemID / 1000), 0777, true);
		}

		if (!file_exists(self::THUMB_DIRECTORY .intval($itemID / 1000) )) {
			mkdir (self::THUMB_DIRECTORY .intval($itemID / 1000), 0777, true);
		}
		$this->createThumbnail($tempFile, $finalFile, self::IMAGE_DIMENSION, self::IMAGE_DIMENSION);
		$this->createThumbnail($tempFile, $thumbFile, self::THUMB_DIMENSION, self::THUMB_DIMENSION);

		unlink($tempFile);
	}

	private function sanitize(array $dataToSanitize): array {
		$result = [];

		foreach ($dataToSanitize as $x => $x_value) {
			$result[$x] = filter_var($x_value, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
		}

		return $result;
	}
}
