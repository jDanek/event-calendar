<?php declare(strict_types=1);

namespace Danek\EventCalendar\Formatter;

use Danek\EventCalendar\CalendarConfig;
use Danek\EventCalendar\Component\DayCell;
use Danek\EventCalendar\Component\DayEvent;

class DefaultFormatter implements FormatterInterface
{
    /** @var CalendarConfig */
    private $config;

    public function format(CalendarConfig $calendarConfig, array $data): string
    {
        $this->config = $calendarConfig;

        // render
        $output = "<div class=\"" . implode(' ', $data['css']) . "\">\n";
        $output .= "<div class=\"header\">\n";
        $output .= "<div class=\"month-year\">" . sprintf("%s %d", $data['header']['month'], $data['header']['year']) . "</div>\n";
        $output .= "</div>\n";
        $output .= "<div class=\"days\">\n";

        // render day names
        foreach ($data['header']['days'] as $dayName) {
            $output .= "<div class=\"day-name\">" . $dayName . "</div>\n";
        }

        /** @var DayCell $cell */
        foreach ($data['cells'] as $cell) {
            $output .= $this->renderDayCell($cell);
        }

        $output .= "</div>\n";
        $output .= "</div>\n";
        return $output;
    }

    /**
     * Renders a cell for single days of non-current months
     *
     * @param DayCell $dayCell
     * @return string
     */
    private function renderDayCell(DayCell $dayCell): string
    {
        $events = $dayCell->getEvents();
        $eventsCount = count($events);

        $cssClasses = $dayCell->getCssClasses();
        $eventTitle = '';
        if ($eventsCount > 0) {
            $cssClasses[] = 'has-event';
            $eventTitle = ($this->config->isMiniMode() ? sprintf("title=\"%s: %d \"", $this->config->getEventLabel(), $eventsCount) : '');
        }
        $output = "<div class=\"" . implode(' ', $cssClasses) . "\" data-events-count=\"" . $eventsCount . "\"" . $eventTitle . ">\n"
            . "<span>" . $dayCell->getId() . "</span>\n";
        $output .= $dayCell->getContent();

        if (!$this->config->isMiniMode()) {
            foreach ($dayCell->getEvents() as $event) {
                $output .= $this->renderEvent($event);
            }
        }

        $output .= "</div>\n";

        return $output;
    }

    /**
     * Renders the event element
     *
     * @param DayEvent $event
     * @return string
     */
    private function renderEvent(DayEvent $event): string
    {
        $color = (!empty($event->color) ? ' ' . $event->color : '');
        return "<div class=\"event" . $color . "\">" . $event->label . "</div>\n";
    }
}