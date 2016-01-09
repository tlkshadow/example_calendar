<?php

namespace App\Calendar\Paginator;

use DateTime;

/**
 * @author Marcel Domke <ma_domke@hotmail.com>
 */
class CalendarPaginator
{
    /**
     * @var DateTime
     */
    private $date;

    /**
     * @param DateTime $date
     */
    public function __construct(DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @return DateTime
     */
    public function getPreviousMonth()
    {
        $date = clone $this->date;
        
        return $date->modify('-1 month');
    }

    /**
     * @return DateTime
     */
    public function getNextMonth()
    {
        $date = clone $this->date;
        
        return $date->modify('+1 month');
    }

    /**
     * @return DateTime
     */
    public function getPreviousYear()
    {
        $date = clone $this->date;
        
        return $date->modify('-1 year');
    }

    /**
     * @return DateTime
     */
    public function getNextYear()
    {
        $date = clone $this->date;
        
        return $date->modify('+1 year');
    }
}
