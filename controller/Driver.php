<?php

include __DIR__ . '/../config/config.php';
require_once  __DIR__ . '/../config/Picnic.php';

/**
 * The Driver Class is the controller of the application. It determines what is
 * being requested and displays the appropriate views with content that has been
 * generated  by the model.
 *
 * Ref: https://www.sitepoint.com/front-controller-pattern-1/
 *
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */
class Driver {

    private const DEFAULT_CONTROLLER_NAME       = 'Home';
    private const DEFAULT_CONTROLLER_CLASS_NAME = self::DEFAULT_CONTROLLER_NAME . 'Controller';
    private const DEFAULT_ACTION                = 'index';

    private $_controllerClassName                = self::DEFAULT_CONTROLLER_CLASS_NAME;
    private $_action                             = self::DEFAULT_ACTION;
    private $_params                             = [];

    /**
     * Processes a page request.
     */
    public function run(): void {
        $this->ensureSessionIsActive();
        $this->parseUri();
        $this->invokeController();
    }

    /**
     * Starts a mew session, if one is not already active.
     */
    private function ensureSessionIsActive(): void {
        if (!isset ($_SESSION)) {
            session_start();
        }

        // force immediate logout of banned users. Not that happy with this being
        // in this class, but can't think of a better place right now.
        if (isset ($_SESSION['userID'])) {
            $h = new Humphree(Picnic::getInstance());
            $user = $h->getUser($_SESSION['userID']);
            if ($user['blocked']) {
                session_destroy();
            }
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

        $parts = explode("/", $path, 3);

        if (count($parts) > 0) {
            $this->setController($parts[0]);
        }

        if (count($parts) > 1) {
            $this->setAction($parts[1]);
        }

        if (count($parts) > 2) {
            $this->setParams(explode("/", $parts[2]));
        } else {
            $this->setParams(['']);
        }
    }

    /**
     * Sets the name of the controller to be invoked.
     *
     * @param $controller string
     *        The name of the controller.
     */
    private function setController($controller): void {
        if (isset($controller)) {
            $this->_controllerClassName = $this->getControllerClassName($controller);
            $this->validateController();
        }
    }

    /**
     * Sets the name of the action to be invoked on the controller.
     *
     * @param $action string
     *           The name of the action to be invoked.
     */
    private function setAction($action): void {
        if (isset($action)) {
            $this->_action = $action;
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
            $this->_params = $params;
        }
    }

    /**
     * Runs the controller and generates the page output.
     */
    private function invokeController(): void {
        call_user_func_array(
            [new $this->_controllerClassName, $this->_action],
            $this->_params);
    }

    /**
     * Gets the adjusted request URI, ready to be parsed.
     *
     * @return string
     *        The adjusted, relative URI.
     */
    private function getRequestUri() : string {
        $path = '/' . trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");

        if (BASE != '') {
            if (strpos($path, BASE) === 0) {
                $path = substr($path, strlen(BASE));
            }
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
        return $controller . "Controller";
    }

    /**
     * Confirms that the current controller class name is valid.
     * Sets the 404 response code and dies if it is not.
     */
    private function validateController(): void {
        if (!class_exists($this->_controllerClassName)) {
            http_response_code(404);
            die();
        }
    }

    /**
     * Confirms that the current action is valid for the current controller.
     * Sets the 404 response code and dies if it is not.
     */
    private function validateAction(): void {
        $reflector = new ReflectionClass($this->_controllerClassName);

        if (!$reflector->hasMethod($this->_action)) {
            http_response_code(404);
            die();
        }
    }
}
