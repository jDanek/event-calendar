<?php declare(strict_types=1);

namespace Danek\EventCalendar;

use Danek\EventCalendar\Util\DateParser;

class CalendarFactory
{
    /**
     * @param \DateTimeInterface|int|string|null $date
     * @return Calendar
     */
    public function createCalendar($date): Calendar
    {
        return new Calendar(new CalendarConfig(), new DateParser(), $date);
    }
}