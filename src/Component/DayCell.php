<?php declare(strict_types=1);

namespace Danek\EventCalendar\Component;

class DayCell
{
    /** @var int */
    private $id;

    /** @var string */
    private $content;

    /** @var array */
    private $cssClasses = [];

    /** @var array */
    private $events = [];

    /** @var bool */
    private $ignoredDay = false;

    /**
     * @param int $id
     * @param string $content
     * @param array $cssClasses
     * @param bool $ignoredDay
     */
    public function __construct(int $id, string $content = '', array $cssClasses = [], bool $ignoredDay = false)
    {
        $this->id = $id;
        $this->content = $content;
        $this->cssClasses = $cssClasses;
        $this->ignoredDay = $ignoredDay;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCssClasses(): array
    {
        return $this->cssClasses;
    }

    public function isIgnoredDay(): bool
    {
        return $this->ignoredDay;
    }

    public function getEvents(): array
    {
        return $this->events;
    }

    public function addEvent(DayEvent $event): void
    {
        $this->events[] = $event;
    }
}