<?php

namespace ClickPizza\Service;


class Calculation
{
    /**
     * Returns the calculation of the number of pages for 10 orders per page
     *
     * @param integer $result
     */
    public function calculNumberOfPages($result) {
        $total = (int)$result['total'];
        $orderByPage = 10;
        // number of pages
        $numberOfPages = ceil($total/$orderByPage);
        return $numberOfPages;
    }
}