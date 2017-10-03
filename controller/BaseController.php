<?php
/*
 * Authors:
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

class BaseController {

	protected function RenderInMainTemplate($includePath) {
		include __DIR__ . '/../view/header/header.php';
		include __DIR__ . '/../view/nav/nav.php';

		include $includePath;

		include __DIR__ . '/../view/footer/footer.php';
	}
}