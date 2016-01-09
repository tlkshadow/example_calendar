# example calendar

http://example-calendar.marcel-domke.de

## Setup

* run `composer install`

## How to

### appointments

```
    $calendar->addAppointment(
        new DateTime('now'), 
        'Ein Termin'
    );
```

### change calendar options

``` 
    $options = [
        'entries_per_row' => 7,
        'start_with_sunday' => false
    ];
    $calendar = new CalendarRenderer($date, $options)
``` 

### change calendar template

```
    $calendar = new CalendarRenderer($date);
    $calendar->templates = [
        'day' => '<div class="day %s">%s</div>',
        'highlight' => '<span class="hightlight">%s</span>',
        'row' => '<div class="calendar-row">%s</div>',
        'item-container' => '<div class="item-container">%s</div>',
        'item' => '<div class="item">%s</div>',
    ];
```