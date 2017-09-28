<?php
/*
 * Authors: 
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

include __DIR__ . '/../config/config.php';

/**
 * The Driver Class is the controller of the application. It determines what is
 * being requested and displays the appropriate views with content that has been
 * manufactured by the model.
 *
 * Ref: https://www.sitepoint.com/front-controller-pattern-1/
 */
class Driver {

	private const DEFAULT_CONTROLLER_NAME       = 'Home';
	private const DEFAULT_CONTROLLER_CLASS_NAME = self::DEFAULT_CONTROLLER_NAME . 'Controller';
	private const DEFAULT_ACTION                = 'index';

	private $controllerClassName                = self::DEFAULT_CONTROLLER_CLASS_NAME;
	private $action                             = self::DEFAULT_ACTION;
	private $params                             = array();

	/**
	 * Processes a page request.
	 */
	public function run(): void {
		$this->ensureSessionIsActive();
		$this->parseUri();
		$this->processPage();
	}

	/**
	 * Starts a mew session, if one is not already active.
	 */
	private function ensureSessionIsActive(): void {
		if (!isset ($_SESSION)) {
			session_start();
		}
	}

	/**
	 * Analyzes the request URI and determines the controller and action to be invoked.
	 */
	private function parseUri(): void {
		$path = $this->getRequestUri();

		// if the request is to the root of the site, we interpret
		// that as a request for the default page.
		if ($path == '') {
			$path = self::DEFAULT_CONTROLLER_NAME;
		}

		@list($controller, $action, $params) = explode("/", $path, 3);

		$this->setController($controller);
		$this->setAction($action);
		$this->setParams(explode("/", $params));
	}

	/**
	 * Sets the name of the controller to be invoked.
	 *
	 * @param $controller string
	 *        The name of the controller.
	 */
	private function setController($controller): void {
		if (isset($controller)) {
			$this->controllerClassName = $this->getControllerClassName($controller);
			$this->validateController();
		}
	}

	/**
	 * Sets the name of the action to be invoked on the controller.
	 *
	 * @param $action string
	 * 		  The name of the action to be invoked.
	 */
	private function setAction($action): void {
		if (isset($action)) {
			$this->action = $action;
			$this->validateAction();
		}
	}

	/**
	 * Sets the params to be passed to the controller.
	 *
	 * @param array $params
	 *        The array of parameters to be passed to the controller.
	 */
	private function setParams(array $params): void {
		if (isset($params)) {
			$this->params = $params;
		}
	}

	/**
	 * Runs the controller and generates the page output.
	 */
	private function processPage(): void {
		include __DIR__ . '/../view/header/header.php';
		include __DIR__ . '/../view/nav/nav.php';
		call_user_func_array(array(new $this->controllerClassName, $this->action), $this->params);
		include __DIR__ . '/../view/footer/footer.php';
	}

	/**
	 * Gets the adjusted request URI, ready to be parsed.
	 *
	 * @return string
	 *        The adjusted, relative URI.
	 */
	private function getRequestUri() : string {
		$path = '/' . trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");

		if (strpos($path, BASE) === 0) {
			$path = substr($path, strlen(BASE));
		}

		$path = trim($path, '/');

		return $path;
	}

	/**
	 * Gets the full class name for the controller, from the controller
	 * part of the request URI.
	 *
	 * @param $controller string
	 *        The raw controller name from the request URI.
	 * @return string
	 *        The name of the class that implements that controller.
	 */
	private function getControllerClassName($controller): string {
		return ucfirst(strtolower($controller)) . "Controller";
	}

	/**
	 * Confirms that the current controller class name is valid.
	 * Sets the 404 response code and dies if it is not.
	 */
	private function validateController(): void {
		if (!class_exists($this->controllerClassName)) {
			http_response_code(404);
			die();
		}
	}

	/**
	 * Confirms that the current action is valid for the current controller.
	 * Sets the 404 response code and dies if it is not.
	 */
	private function validateAction(): void {
		$reflector = new ReflectionClass($this->controllerClassName);

		if (!$reflector->hasMethod($this->action)) {
			http_response_code(404);
			die();
		}
	}
}
