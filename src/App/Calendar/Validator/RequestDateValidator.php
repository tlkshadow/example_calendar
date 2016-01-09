<?php

namespace App\Calendar\Validator;

/**
 * @author Marcel Domke <ma_domke@hotmail.com>
 */
class RequestDateValidator
{
    /**
     * format should be YYYY-MM
     */
    const REQUEST_PATTERN = '#^([0-9]{4}-[0-9]{2})$#';

    /**
     * @param string $date
     */
    public function __construct($date)
    {
        $this->date = $date;
    }

    /**
     * @param string $pattern
     * @return bool
     */
    public function isValid($pattern = self::REQUEST_PATTERN)
    {
        return preg_match($pattern, $this->date);
    }
}
