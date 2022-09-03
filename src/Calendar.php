<?php declare(strict_types=1);

namespace Danek\EventCalendar;

use Danek\EventCalendar\Component\DayCell;
use Danek\EventCalendar\Component\DayEvent;
use Danek\EventCalendar\Formatter\DefaultFormatter;
use Danek\EventCalendar\Formatter\FormatterInterface;
use Danek\EventCalendar\Util\DateParser;

class Calendar
{
    public const FIRST_SUNDAY = 0;
    public const FIRST_MONDAY = 1;

    /** @var DateParser */
    private $dateParser;
    /** @var \DateTimeInterface */
    private $selectedMonth;
    /** @var \DateTimeInterface */
    private $previousMonth;
    /** @var CalendarConfig */
    private $config;
    /** @var array */
    private $events = [];

    public function __construct(CalendarConfig $calendarConfig, DateParser $parser, $date = null)
    {
        $this->config = $calendarConfig;
        $this->dateParser = $parser;

        $this->selectedMonth = $this->dateParser->parse($date);
        $this->previousMonth = (clone $this->selectedMonth)->modify('first day of previous month');
    }

    /**
     * @param array|null $options {@see CalendarConfig::setOptions()}
     * @return CalendarConfig
     */
    public function configure(array $options = null): CalendarConfig
    {
        if ($options !== null) {
            $this->config->setOptions($options);
        }
        return $this->config;
    }

    private function prepareCalendarData(): array
    {
        // prepare
        $numDays = $this->selectedMonth->format('t');
        $numDaysLastMonth = $this->previousMonth->format('t');

        $firstDayOfWeek = (int)(new \DateTimeImmutable($this->selectedMonth->format('Y-m-1')))->format(
            ($this->config->getFirstDay() === self::FIRST_SUNDAY ? 'w' : 'N')
        );
        // for format 'N' the function returns 1-7, but the day name field is numbered 0-6
        // subtract 0 for Sunday and 1 for Monday
        $firstDayOfWeek -= $this->config->getFirstDay();

        // compose data
        $calendarData = [];
        $calendarData['css'] = ['calendar'];
        if ($this->config->isMiniMode()) {
            $calendarData['css'][] = 'minimode';
        }
        $calendarData['header'] = [
            'month' => $this->config->getOption('month-names', (int)$this->selectedMonth->format('n') - 1),
            'year' => $this->selectedMonth->format('Y'),
            'days' => $this->config->getDayNames(($this->config->isMiniMode() ? 2 : null)),
        ];
        $calendarData['cells'] = [];

        // render days of the previous month
        for ($i = $firstDayOfWeek; $i > 0; $i--) {
            $calendarData['cells'][] = new DayCell(($numDaysLastMonth - $i + 1), '', ['day-num', 'ignore'], true);
        }

        // render days of the selected month
        for ($i = 1; $i <= $numDays; $i++) {

            $cssClasses = ['day-num'];
            $selFormat = $this->selectedMonth->format('y-m-' . $i);
            switch ($selFormat) {
                case (new \DateTimeImmutable('yesterday'))->format('y-m-j'):
                    $cssClasses[] = 'yesterday';
                    break;
                case (new \DateTimeImmutable('today'))->format('y-m-j'):
                    $cssClasses[] = 'today';
                    break;
                case (new \DateTimeImmutable('tomorrow'))->format('y-m-j'):
                    $cssClasses[] = 'tomorrow';
                    break;
            }
            if ($i == $this->selectedMonth->format('d')) {
                $cssClasses[] = 'selected';
            }

            $dayCell = new DayCell($i, '', $cssClasses);

            /** @var DayEvent $event */
            foreach ($this->events as $event) {
                for ($d = 0; $d <= ($event->eventLength - 1); $d++) {
                    // temporary DT object
                    $tmp = new \DateTime($this->selectedMonth->format('y-m-' . $i));
                    $tmp->modify('-' . $d . 'days');
                    // add event
                    if ($tmp->format('y-m-d') == $event->date->format('y-m-d')) {
                        $dayCell->addEvent($event);
                    }
                }
            }

            $calendarData['cells'][] = $dayCell;
        }

        // render the days of the next month
        for ($i = 1; $i <= (42 - $numDays - max($firstDayOfWeek, 0)); $i++) {
            $calendarData['cells'][] = new DayCell($i, '', ['day-num', 'ignore'], true);
        }

        return $calendarData;
    }

    public function render(FormatterInterface $formatter = null): string
    {
        $data = $this->prepareCalendarData();
        if ($formatter === null) {
            $formatter = new DefaultFormatter();
        }
        return $formatter->format($this->config, $data);
    }

    /**
     * @param \DateTimeInterface|int|string|null $date
     * @param string $label {@see DayEvent::__construct()}
     * @param array $options {@see DayEvent::__construct()}
     */
    public function addEvent($date, string $label, array $options): void
    {
        $this->events[] = new DayEvent($this->dateParser, $date, $label, $options);
    }
}