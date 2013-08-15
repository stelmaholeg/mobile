<?php

if (!defined('_JEXEC')) die('Direct Access is not allowed.');

/**
 * Page navigation support class
 * @package Joomla RE
 */
class mosPageNav {
    /** @var int The record number to start dislpaying from */
    var $limitstart = null;
    /** @var int Number of rows to display per page */
    var $limit = null;
    /** @var int Total number of rows */
    var $total = null;

    function mosPageNav( $total, $limitstart, $limit ) {
        $this->total		= (int) $total;
        $this->limitstart	= (int) max( $limitstart, 0 );
        $this->limit		= (int) max( $limit, 0 );
    }



    /**
     * Writes the html links for pages, eg, previous, next, 1 2 3 ... x
     * @param string The basic link to include in the href
     */
    function writePagesLinks($prefix='') {
        $txt = '';

        $displayed_pages = 10;
        $total_pages = $this->limit ? ceil( $this->total / $this->limit ) : 0;
        $this_page = $this->limit ? ceil( ($this->limitstart+1) / $this->limit ) : 1;
        $start_loop = (floor(($this_page-1)/$displayed_pages))*$displayed_pages+1;
        if ($start_loop + $displayed_pages - 1 < $total_pages) {
            $stop_loop = $start_loop + $displayed_pages - 1;
        } else {
            $stop_loop = $total_pages;
        }
        
        if (!defined( '_PN_LT' ) || !defined( '_PN_RT' ) ) {
            DEFINE('_PN_LT','&lt;');
            DEFINE('_PN_RT','&gt;');
        }
        DEFINE('_PN_START','Первая');
        DEFINE('_PN_PREVIOUS','Предыдущая');
        DEFINE('_PN_NEXT','Следующая');
        DEFINE('_PN_END','Последняя');

        $pnSpace = '';
        if (_PN_LT || _PN_RT) $pnSpace = "&nbsp;";
        $txt .= '<span class="pagenav">';
        if ($this_page > 1) {
            $page = ($this_page - 2) * $this->limit;
            $txt .= '<a  onclick="'.$prefix.'loadProduct(0)" title="'. _PN_START .'">'. _PN_LT . _PN_LT . $pnSpace . _PN_START .'</a> ';
            $txt .= '<a  onclick="'.$prefix.'loadProduct('.$page.')" title="'. _PN_PREVIOUS .'">'. _PN_LT . $pnSpace . _PN_PREVIOUS .'</a> ';
        } else {
            $txt .= '<span>'. _PN_LT . _PN_LT . $pnSpace . _PN_START .'</span> ';
            $txt .= '<span>'. _PN_LT . $pnSpace . _PN_PREVIOUS .'</span> ';
        }

        for ($i=$start_loop; $i <= $stop_loop; $i++) {
            $page = ($i - 1) * $this->limit;
            if ($i == $this_page) {
                $txt .= '<span>'. $i .'</span> ';
            } else {
                $txt .= '<a  onclick="'.$prefix.'loadProduct('.$page.')" ><strong>'. $i .'</strong></a> ';
            }
        }

        if ($this_page < $total_pages) {
            $page = $this_page * $this->limit;
            $end_page = ($total_pages-1) * $this->limit;
            $txt .= '<a  onclick="'.$prefix.'loadProduct('.$page.')" title="'. _PN_NEXT .'">'. _PN_NEXT . $pnSpace . _PN_RT .'</a> ';
            $txt .= '<a  onclick="'.$prefix.'loadProduct('.$end_page.')" title="'. _PN_END .'">'. _PN_END . $pnSpace . _PN_RT . _PN_RT .'</a>';
        } else {
            $txt .= '<span>'. _PN_NEXT . $pnSpace . _PN_RT .'</span> ';
            $txt .= '<span>'. _PN_END . $pnSpace . _PN_RT . _PN_RT .'</span>';
        }
        $txt .= '</span>';
        return $txt;
    }
}
?>