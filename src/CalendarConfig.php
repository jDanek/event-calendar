<?php declare(strict_types=1);

namespace Danek\EventCalendar;

class CalendarConfig
{
    private $sundayFirst = true;

    /** @var array */
    private $options = [
        'first-day' => Calendar::FIRST_SUNDAY,
        'day-names' => ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
        'month-names' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        'event-label' => 'Events',
        'render-events' => true,
        'mini-mode' => false,
    ];

    /**
     * CalendarOptions constructor.
     * @param array $options {@see CalendarConfig::setOptions()}
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    public function getFirstDay(): int
    {
        return $this->options['first-day'];
    }

    public function setFirstDay(int $firstDay): self
    {
        // correction
        if ($firstDay < 0) {
            $firstDay = 0;
        } elseif ($firstDay > 1) {
            $firstDay = 1;
        }
        $this->options['first-day'] = $firstDay;
        $this->moveSunday();
        return $this;
    }

    public function getDayNames(int $length = null): array
    {
        $short = null;
        if ($length !== null) {
            $short = [];
            foreach ($this->options['day-names'] as $name) {
                $short[] = mb_substr($name, 0, $length);
            }
        }
        return $short ?? $this->options['day-names'];
    }

    public function setDayNames(array $names): self
    {
        $count = count($names);
        if ($count !== 7) {
            throw new \InvalidArgumentException(sprintf("Number of days entered is '%d', expected is '%d'", $count, 7));
        }
        $this->options['day-names'] = $names;
        $this->moveSunday();
        return $this;
    }

    public function getMonthNames(int $length = null): array
    {
        $short = null;
        if ($length !== null) {
            $short = [];
            foreach ($this->options['month-names'] as $name) {
                $short[] = mb_substr($name, 0, $length);
            }
        }
        return $short ?? $this->options['month-names'];
    }

    public function setMonthNames(array $names): self
    {
        $count = count($names);
        if ($count !== 12) {
            throw new \InvalidArgumentException(sprintf("Number of months entered is '%d', expected is '%d'", $count, 12));
        }
        $this->options['month-names'] = $names;
        return $this;
    }

    public function getEventLabel(): string
    {
        return $this->options['event-label'];
    }

    public function setEventLabel(string $label): self
    {
        $this->options['event-label'] = $label;
        return $this;
    }

    public function isEventRendered(): bool
    {
        return $this->options['render-events'];
    }

    public function setEventRendering(bool $state = true): self
    {
        $this->options['render-events'] = $state;
        return $this;
    }

    public function isMiniMode(): bool
    {
        return $this->options['mini-mode'];
    }

    public function setMiniMode(bool $state = true): self
    {
        $this->options['mini-mode'] = $state;
        // events don't fit on mini-mode
        $this->setEventRendering(false);
        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param string $key
     * @param mixed|null $index
     * @return mixed|null
     */
    public function getOption(string $key, $index = null)
    {
        $result = null;
        if ($index !== null) {
            $result = $this->options[$key][$index] ?? null;
        } else {
            $result = $this->options[$key] ?? null;
        }
        return $result;
    }

    /**
     * @param array $options
     * Supported $options:
     * ------------------------------------------------------------------------------------------------
     * Key              Type        Description
     * ------------------------------------------------------------------------------------------------
     * first-day        int         set the first day of the week: Sunday = 0, Monday = 1
     * day-names        array       the list of day names, starting from Sunday ['Sun', 'Mon', ...]
     * month-names      array       the list of month names ['January', ...]
     * event-label      string      text displayed as a label of the number of events after mouseover.
     * render-events    bool        flag for drawing events to days cells
     * mini-mode        bool        flag for drawing mini-mode calendar (minimode turns off event rendering)
     */
    public function setOptions(array $options): void
    {
        if (isset($options['day-names'])) {
            $this->setDayNames($options['day-names']);
        }
        if (isset($options['month-names'])) {
            $this->setMonthNames($options['month-names']);
        }
        if (isset($options['event-label'])) {
            $this->setEventLabel($options['event-label']);
        }
        if (isset($options['render-events'])) {
            $this->setEventRendering($options['render-events']);
        }
        if (isset($options['first-day'])) {
            $this->setFirstDay($options['first-day']);
        }
        if (isset($options['mini-mode'])) {
            $this->setMiniMode($options['mini-mode']);
        }
    }

    private function moveSunday(): void
    {
        if ($this->options['first-day'] == Calendar::FIRST_MONDAY && $this->sundayFirst) {
            // move Sunday to the end of the array
            $firstElement = array_shift($this->options['day-names']);
            $this->options['day-names'][] = $firstElement;
            $this->sundayFirst = false;
        } elseif ($this->options['first-day'] == Calendar::FIRST_SUNDAY && !$this->sundayFirst) {
            // move Sunday to the start of the array
            $lastElement = array_shift($this->options['day-names']);
            array_unshift($this->options['day-names'], $lastElement);
            $this->sundayFirst = true;
        }
    }

}