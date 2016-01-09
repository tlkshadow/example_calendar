<?php

namespace App\Calendar\Renderer;

use DateInterval;
use DatePeriod;
use DateTime;

/**
 * @author Marcel Domke <ma_domke@hotmail.com>
 */
class CalendarRenderer implements RenderInterface
{
    /**
     * @var DateTime
     */
    private $date;

    /**
     * @var DateTime
     */
    private $dateStart;

    /**
     * @var DateTime
     */
    private $dateEnd;

    /**
     * @var DateInterval
     */
    private $dateInterval;

    /**
     * @var array
     */
    private $options = [
        'entries_per_row' => 7,
        'start_with_sunday' => false
    ];

    public $templates = [
        'day' => '<div class="day %s">%s</div>',
        'highlight' => '<span class="hightlight">%s</span>',
        'row' => '<div class="calendar-row">%s</div>',
        'item-container' => '<div class="item-container">%s</div>',
        'item' => '<div class="item">%s</div>',
    ];

    public $formats = [
        'day' => 'l, d',
    ];

    /**
     * @var array
     */
    public $appointments = [];

    /**
     * @param DateTime $date
     */
    public function __construct(DateTime $date, $options = [])
    {
        $this->date = $date;

        $this->dateStart = clone $this->date;
        $this->dateStart->setDate(
            $this->date->format('Y'),
            $this->date->format('m'),
            1
        );

        $this->dateEnd = clone $this->date;
        $this->dateEnd->setDate(
            $this->date->format('Y'),
            $this->date->format('m'),
            $this->date->format('t')
        );

        $this->dateInterval = new DateInterval('P1D');
        $this->options = array_merge($this->options, $options);
    }

    /**
     * @param DateTime $date
     * @param string $content
     * @return $this
     */
    public function addAppointment(DateTime $date, $content)
    {
        $this->appointments[$date->format('Y-m-d')][] = $content;

        return $this;
    }

    /**
     * @return array
     */
    public function getAppointments()
    {
        return $this->appointments;
    }

    /**
     * @param DateTime $date
     * @return array
     */
    public function getAppointmentsByDate(DateTime $date)
    {
        $key = $date->format('Y-m-d');

        return (isset($this->appointments[$key])) ? $this->appointments[$key] : [];
    }

    /**
     * @return string
     */
    public function render()
    {
        $rows = [];
        $rowCount = 1;
        $rowItems = 1;

        /* @var $date DateTime */
        foreach ($this->getPreviousDatePeriod() as $date) {
            if ($rowItems <= $this->options['entries_per_row']) {
                $classes = ['previous-month'];
                if ($date->format('Y-m-d') == (new DateTime('now'))->format('Y-m-d')) {
                    $classes[] = 'today';
                }
                $rows[$rowCount][] = sprintf($this->templates['day'], implode(' ', $classes), $this->renderADay($date));
            }

            if ($rowItems == $this->options['entries_per_row']) {
                $rowCount++;
                $rowItems = 0;
            }
            $rowItems++;
        }

        /* @var $date DateTime */
        foreach ($this->getCurrentDatePeriod() as $date) {
            if ($rowItems <= $this->options['entries_per_row']) {
                $classes = ['current-month', strtolower($date->format('l'))];
                if ($date->format('Y-m-d') == (new DateTime('now'))->format('Y-m-d')) {
                    $classes[] = 'today';
                }
                $rows[$rowCount][] = sprintf($this->templates['day'], implode(' ', $classes), $this->renderADay($date));
            }

            if ($rowItems == $this->options['entries_per_row']) {
                $rowCount++;
                $rowItems = 0;
            }
            $rowItems++;
        }

        $lastRow = end($rows);

        for ($i = 0; $i < (7 - count($lastRow)); $i++) {
            $date = clone $this->date;
            $date->modify('+1 month');
            $date->setDate(
                $date->format('Y'),
                $date->format('m'),
                $i+1
            );

            if ($rowItems <= $this->options['entries_per_row']) {
                $classes = ['next-month', strtolower($date->format('l'))];
                if ($date->format('Y-m-d') == (new DateTime('now'))->format('Y-m-d')) {
                    $classes[] = 'today';
                }
                $rows[$rowCount][] = sprintf($this->templates['day'], implode(' ', $classes), $this->renderADay($date));
            }

            if ($rowItems == $this->options['entries_per_row']) {
                $rowCount++;
                $rowItems = 0;
            }
            $rowItems++;
        }


        return implode('', array_map(function ($row) {
            return sprintf($this->templates['row'], implode('', $row));
        }, $rows));
    }

    /**
     * @param DateTime $date
     * @return string
     */
    private function renderADay($date)
    {
        $content = sprintf($this->templates['highlight'], $date->format($this->formats['day']));

        $items = $this->getAppointmentsByDate($date);

        $content.= sprintf($this->templates['item-container'], implode('', array_map(function ($content) {
            return sprintf($this->templates['item'], $content);
        }, $items)));

        return $content;
    }

    /**
     * @return DatePeriod
     */
    private function getCurrentDatePeriod()
    {
        return new DatePeriod(
            $this->dateStart,
            $this->dateInterval,
            $this->dateEnd
        );
    }

    /**
     * @return DatePeriod
     */
    private function getPreviousDatePeriod()
    {
        $days = $this->getDays();
        $day = array_search($this->dateStart->format('D'), $days);

        $start = clone $this->date;
        $start->modify('-' . $day . ' days');

        $end = clone $this->date;
        $end = $end->setDate(
            $end->format('Y'),
            $end->format('m'),
            1
        );

        return new DatePeriod(
            $start,
            $this->dateInterval,
            $end
        );
    }

    /**
     * @todo should not be a static array with weekdays, in case someone uses the setLocale stuff and change to e.g. german
     *
     * @return array
     */
    private function getDays()
    {
        $days = ['Sun', 'Mon','Tue','Wed','Thu','Fri','Sat'];
        if ($this->options['start_with_sunday'] == false) {
            $sun = array_shift($days);
            $days[] = $sun;
        }

        return $days;
    }
}
