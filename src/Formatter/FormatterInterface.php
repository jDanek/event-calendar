<?php declare(strict_types=1);

namespace Danek\EventCalendar\Formatter;

use Danek\EventCalendar\CalendarConfig;

interface FormatterInterface
{

    /**
     * @param CalendarConfig $calendarConfig
     * @param array $data
     * @return string
     */
    public function format(CalendarConfig $calendarConfig, array $data): string;
}