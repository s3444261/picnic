<?php

/**
 * Controller for application help-rated functions.
 *
 * @author Troy Derrick     <s3202752@student.rmit.edu.au>
 * @author Diane Foster     <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds     <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead     <s3444261@student.rmit.edu.au>
 * @author Edwan Putro         <s3418650@student.rmit.edu.au>
 */
class HelpController {

    /**
     *    Displays the 'About Us' page.
     */
    public function About(): void {
        $this->DisplayPage('about');
    }

    /**
     *    Displays the privacy policy.
     */
    public function PrivacyPolicy(): void {
        $this->DisplayPage('privacyPolicy');
    }

    /**
     *    Displays the terms of service.
     */
    public function TermsOfService(): void {
        $this->DisplayPage('terms');
    }

    /**
     *    Displays the frequently asked questions.
     */
    public function Faq(): void {
        $view = new View();
        $view->SetData('navData', new NavData(NavData::Home));
        $view->SetData('userIsAdmin',  isset ($_SESSION['status']) && $_SESSION['status'] === 'admin');
        $view->Render('faq');
    }

    /**
     *    Displays the site map.
     */
    public function SiteMap(): void {
        $this->DisplayPage('siteMap');
    }

    /**
     *     Displays the named page.
     *
     * @param string $templateName        The name of the page to be displayed.
     */
    private function DisplayPage(string $templateName): void {
        $view = new View();
        $view->SetData('navData', new NavData(NavData::Home));
        $view->Render($templateName);
    }
}
