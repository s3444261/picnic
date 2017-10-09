<?php
/**
 * Created by PhpStorm.
 * User: Derricks
 * Date: 9/10/2017
 * Time: 7:14 PM
 */

class Pager
{
	public function CreateLinks( $links, $list_class, $page, $itemsPerPage, $totalItems  ) {
		if ( $itemsPerPage == 'all' ) {
			return '';
		}

		$last       = ceil( $totalItems / $itemsPerPage );

		$start      = ( ( $page - $links ) > 0 ) ? $page - $links : 1;
		$end        = ( ( $page + $links ) < $last ) ? $page + $links : $last;

		$html       = '<ul class="' . $list_class . '">';

		$class      = ( $page == 1 ) ? "disabled" : "";

		if ($page == 1)  {
			$html       .= '<li class="' . $class . '"><a href="?limit=' . $itemsPerPage . '&page=1">&laquo;</a></li>';
		} else {
			$html       .= '<li class="' . $class . '"><a href="?limit=' . $itemsPerPage . '&page=' . ( $page - 1 ) . '">&laquo;</a></li>';
		}


		if ( $start > 1 ) {
			$html   .= '<li><a href="?limit=' . $itemsPerPage . '&page=1">1</a></li>';
			$html   .= '<li class="disabled"><span>...</span></li>';
		}

		for ( $i = $start ; $i <= $end; $i++ ) {
			$class  = ( $page == $i ) ? "active" : "";
			$html   .= '<li class="' . $class . '"><a href="?limit=' . $itemsPerPage . '&page=' . $i . '">' . $i . '</a></li>';
		}

		if ( $end < $last ) {
			$html   .= '<li class="disabled"><span>...</span></li>';
			$html   .= '<li><a href="?limit=' . $itemsPerPage . '&page=' . $last . '">' . $last . '</a></li>';
		}

		$class      = ( $page == $last ) ? "disabled" : "";

		if ($page == $last) {
			$html       .= '<li class="' . $class . '"><a href="?limit=' . $itemsPerPage. '&page=' . $last . '">&raquo;</a></li>';
		} else {
			$html       .= '<li class="' . $class . '"><a href="?limit=' . $itemsPerPage. '&page=' . ( $page + 1 ) . '">&raquo;</a></li>';
		}


		$html       .= '</ul>';

		return $html;
	}


}