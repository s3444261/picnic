<?php
/*
 * Authors:
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - s3418650@student.rmit.edu.au
 */

class View {

	protected $data;

	function Render($template) {
		ob_start();
		require __DIR__ . '/../view/header/header.php';
		require __DIR__ . '/../view/nav/nav.php';
		require "view/layout/" . $template . ".php";
		require __DIR__ . '/../view/footer/footer.php';
		$str = ob_get_contents();
		ob_end_clean();
		echo $str;
	}

	function SetData($key, $val) {
		$this->data[$key] = $val;
	}
}