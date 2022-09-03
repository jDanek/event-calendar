<?php

use Danek\EventCalendar\CalendarFactory;
use Danek\EventCalendar\Formatter\DefaultFormatter;
use Danek\EventCalendar\Formatter\TableFormatter;

require __DIR__ . DIRECTORY_SEPARATOR . '../vendor/autoload.php';

$calFactory = new CalendarFactory();
$cal = $calFactory->createCalendar();

$cal->configure(
    [
        'first-day' => 1,
        'day-names' => ['Neděle', 'Pondělí', 'Úterý', 'Středa', 'Čtvrtek', 'Pátek', 'Sobota'],
        'month-names' => ['Leden', 'Únor', 'Březen', 'Duben', 'Květen', 'Červen', 'Červenec', 'Srpen', 'Září', 'Říjen', 'Listopad', 'Prosinec'],
        'event-label' => 'Události',
        'mini-mode' => true
    ]
);

$cal->addEvent('2022-09-01', 'Vacation', ['color' => 'green', 'length' => 5]);
$cal->addEvent('2022-09-03', 'Party', ['color' => 'blue', 'length' => 4]);
$cal->addEvent('2022-09-05', 'Cinema', ['color' => 'yellow', 'length' => 3]);
$cal->addEvent('2022-09-07', 'Worl', ['color' => 'red', 'length' => 2]);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf - 8">
    <title>Event Calendar</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <link href="calendar.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="content">
    <?= $cal->render(new DefaultFormatter()) ?>
    <hr>
    <?= $cal->render(new TableFormatter()) ?>
</div>
</body>
</html>