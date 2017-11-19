<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
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