<?php

/**
 * Controller for the application home page.
 *
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */
class HomeController {

    /**
     *     Displays the home page.
     */
    public function index(): void {
        $view = new View();
        $view->SetData('navData',  new NavData(NavData::Home));
        $view->Render('home');
    }
}
