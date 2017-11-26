<?php

/**
 * Provides a pager UI.
 *
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */
class Pager{

    const DEFAULT_ITEMS_PER_PAGE = 25;
    const DEFAULT_PAGE_NUMBER = 1;
    const NUMBER_OF_SURROUNDING_LINKS = 3;

    /**
     * Renders the Pager with the given settings.
     *
     * @param $list_class
     *             The CSS class name to be applied to the root pager element.
     *
     * @param PagerData $pagerData
     *             A PagerData describing the state of the Pager.
     *
     * @param bool $includeMessage
     *             True if a text message should be included. This message will be of the form
     *          'Showing X-Y of Z items.'
     *
     * @return string
     *             The rendered HTML string.
     */
    public static function Render($list_class, PagerData $pagerData, bool $includeMessage) {

        if ($pagerData->totalItems == 0) {
            return '';
        }

        if ( $pagerData->itemsPerPage == 'all' ) {
            return '';
        }

        $last       = ceil( $pagerData->totalItems / $pagerData->itemsPerPage );

        $start      = ( ( $pagerData->pageNumber - self::NUMBER_OF_SURROUNDING_LINKS ) > 0 )
            ? $pagerData->pageNumber - self::NUMBER_OF_SURROUNDING_LINKS
            : 1;
        $end        = ( ( $pagerData->pageNumber + self::NUMBER_OF_SURROUNDING_LINKS ) < $last )
            ? $pagerData->pageNumber + self::NUMBER_OF_SURROUNDING_LINKS
            : $last;

        $html       = '<ul class="justify-content-center ' . $list_class . '">';

        $class      = ( $pagerData->pageNumber  == 1 ) ? "disabled" : "";

        $existingQuery = '';
        if (isset($_REQUEST)) {
            foreach ($_REQUEST as $key => $value) {
                if ($key != 'limit' && $key != 'page') {
                    $existingQuery = $existingQuery . $key .'=' . $value . '&';
                }
            }
        }

        if ($pagerData->pageNumber == 1)  {
            $html       .= '<li class="page-item list-inline-item ' . $class . '"><a class="page-link" href="?'
                . $existingQuery . 'limit=' . $pagerData->itemsPerPage . '&page=1">&laquo;</a></li>';
        } else {
            $html       .= '<li class="page-item list-inline-item ' . $class . '"><a class="page-link" href="?'
                . $existingQuery . 'limit=' . $pagerData->itemsPerPage . '&page=' . ( $pagerData->pageNumber - 1 )
                . '">&laquo;</a></li>';
        }


        if ( $start > 1 ) {
            $html   .= '<li class="page-item list-inline-item"><a class="page-link" href="?' . $existingQuery
                . 'limit=' . $pagerData->itemsPerPage . '&page=1">1</a></li>';
            $html   .= '<li class="page-item list-inline-item disabled"><span>...</span></li>';
        }

        for ( $i = $start ; $i <= $end; $i++ ) {
            $class  = ( $pagerData->pageNumber == $i ) ? "active" : "";
            $html   .= '<li class="page-item list-inline-item ' . $class . '"><a class="page-link" href="?'
                . $existingQuery . 'limit=' . $pagerData->itemsPerPage . '&page=' . $i . '">' . $i . '</a></li>';
        }

        if ( $end < $last ) {
            $html   .= '<li class="disabled page-item list-inline-item "><span>...</span></li>';
            $html   .= '<li class="page-item list-inline-item"><a class="page-link" href="?' . $existingQuery
                . 'limit=' . $pagerData->itemsPerPage . '&page=' . $last . '">' . $last . '</a></li>';
        }

        $class      = ( $pagerData->pageNumber == $last ) ? "disabled" : "";

        if ( $pagerData->pageNumber  == $last) {
            $html       .= '<li class="page-item list-inline-item ' . $class . '"><a class="page-link" href="?'
                . $existingQuery . 'limit=' . $pagerData->itemsPerPage. '&page=' . $last . '">&raquo;</a></li>';
        } else {
            $html       .= '<li class="page-item list-inline-item ' . $class . '"><a class="page-link" href="?'
                . $existingQuery . 'limit=' . $pagerData->itemsPerPage. '&page=' . ( $pagerData->pageNumber + 1 )
                . '">&raquo;</a></li>';
        }


        $html       .= '</ul>';

        if ($includeMessage) {
            $html .= '<div class="text-center"><p>Displaying ' .((($pagerData->pageNumber  - 1) *
                        $pagerData->itemsPerPage) + 1) . ' - ' . min(($pagerData->pageNumber
                    * $pagerData->itemsPerPage), $pagerData->totalItems)  . ' of ' . $pagerData->totalItems
                . ' items.</p></div>';
        }

        return $html;
    }

    /**
     * Parses the URI query string to find the current page number and number of items
     * per page.
     *
     * @return PagerData
     *          A PagerData instance containing the parsed values.
     */
    public static function ParsePagerDataFromQuery() : PagerData {

        if (isset($_SERVER['QUERY_STRING'])) {
            parse_str($_SERVER['QUERY_STRING']);
        }

        if (!isset($page))  {
            $page = self::DEFAULT_PAGE_NUMBER;
        }

        if (!isset($limit))  {
            $limit = self::DEFAULT_ITEMS_PER_PAGE;
        }

        $pagerData = new PagerData();
        $pagerData->pageNumber = $page;
        $pagerData->itemsPerPage = $limit;

        return $pagerData;
    }
}

/**
 * Information needed to render a Pager.
 */
class PagerData {

    /**
     * @var int      The current page number.
     */
    public  $pageNumber;

    /**
     * @var int        The number of items to display on each page.
     */
    public $itemsPerPage;

    /**
     * @var    int        The total number of items on all pages.
     */
    public $totalItems;
}
