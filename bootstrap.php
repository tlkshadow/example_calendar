<?php

// composer autoload file
include __DIR__ . '/vendor/autoload.php';

use App\Calendar\Paginator\CalendarPaginator;
use App\Calendar\Renderer\CalendarRenderer;
use App\Calendar\Exception\DateTimeException;
use App\Calendar\Validator\RequestDateValidator;

$date = 'now';
if (isset($_GET['date'])) {
    $date = htmlspecialchars($_GET['date']);
    $validator = new RequestDateValidator($date);
    if (!$validator->isValid()) {
        throw new DateTimeException('The given date is not valid (' . RequestDateValidator::REQUEST_PATTERN . ')!');
    }
}
$currentMonth = new DateTime($date);
$calendar = new CalendarRenderer($currentMonth);
$paginator = new CalendarPaginator($currentMonth);

$calendar->addAppointment(new DateTime('now'), 'Ein Termin');
$calendar->addAppointment(new DateTime('yesterday'), 'Ein Termin');
$calendar->addAppointment(new DateTime('now'), 'Ein Termin');
$calendar->addAppointment(new DateTime('now'), '<a href="#">Ein Termin</a>');
$calendar->addAppointment(new DateTime('tomorrow'), 'Ein Termin');
$calendar->addAppointment(new DateTime('-1 week'), 'Ein Termin');
$calendar->addAppointment(new DateTime('-2 week'), 'Ein Termin');
