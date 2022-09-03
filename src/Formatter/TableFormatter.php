<?php declare(strict_types=1);

namespace Danek\EventCalendar\Formatter;

use Danek\EventCalendar\CalendarConfig;
use Danek\EventCalendar\Component\DayCell;
use Danek\EventCalendar\Component\DayEvent;

class TableFormatter implements FormatterInterface
{
    /** @var CalendarConfig */
    private $config;

    public function format(CalendarConfig $calendarConfig, array $data): string
    {
        $this->config = $calendarConfig;

        // render
        $output = "<table class=\"" . implode(' ', $data['css']) . "\">\n";
        $output .= "<thead>\n";
        $output .= "<tr>\n<th colspan=\"7\">" . sprintf("%s %d", $data['header']['month'], $data['header']['year']) . "</th>\n</tr>\n";

        $output .= "<tr>";
        foreach ($data['header']['days'] as $day) {
            $output .= "<th>" . $day . "</th>\n";
        }

        $output .= "</tr>\n";
        $output .= "</thead>\n";

        $output .= "<tbody>\n";
        foreach (array_chunk($data['cells'], 7) as $cellRow) {
            $output .= "<tr>\n";
            /** @var DayCell $cell */
            foreach ($cellRow as $cell) {
                $events = $cell->getEvents();
                $eventsCount = count($events);

                $cssClasses = $cell->getCssClasses();
                $eventTitle = '';
                if ($eventsCount > 0) {
                    $cssClasses[] = 'has-event';
                    $eventTitle = ($this->config->isMiniMode() ? sprintf("title=\"%s: %d \"", $calendarConfig->getEventLabel(), $eventsCount) : '');
                }

                $output .= "<td class=\"" . implode(' ', $cssClasses) . "\" data-events-count=\"" . $eventsCount . "\"" . $eventTitle . ">\n";
                $output .= "<span>" . $cell->getId() . "</span>";

                if (!$calendarConfig->isMiniMode()) {
                    foreach ($cell->getEvents() as $event) {
                        $output .= $this->renderEvent($event);
                    }
                }

                $output .= "</td>\n";
            }
            $output .= "</tr>\n";
        }
        $output .= "</tbody>\n";
        $output .= "</table>\n";

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