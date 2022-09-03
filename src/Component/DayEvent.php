<?php declare(strict_types=1);

namespace Danek\EventCalendar\Component;

use Danek\EventCalendar\Util\DateParser;

class DayEvent
{
    /** @var \DateTime */
    public $date;

    /** @var string */
    public $label = '';

    /** @var int */
    public $eventLength = 1;

    /** @var string */
    public $color = '';

    /**
     * @param DateParser $dateParser
     * @param \DateTime|string $date
     * @param string $label
     * @param array $options
     *
     * Supported $options:
     * ---------------------------------------------------------------------------------------
     * color            label background color
     * event-length     event length in days (default: 1)
     */
    public function __construct(DateParser $dateParser, $date, string $label, array $options = [])
    {
        $options += [
            'color' => '',
            'length' => 1,
        ];

        $this->date = $dateParser->parse($date);
        $this->label = $label;
        $this->color = $options['color'];
        $this->eventLength = $options['length'];
    }

    public function __set($name, $value)
    {
        throw new \BadMethodCallException();
    }
}