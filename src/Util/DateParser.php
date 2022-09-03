<?php declare(strict_types=1);

namespace Danek\EventCalendar\Util;

class DateParser
{
    public function __construct()
    {
    }

    /**
     * @param \DateTimeInterface|int|string|null $date
     * @return \DateTimeInterface
     */
    public function parse($date = null): \DateTimeInterface
    {
        try {
            // datetime object's
            if ($date instanceof \DateTime) {
                return \DateTimeImmutable::createFromMutable($date);
            }
            if ($date instanceof \DateTimeInterface) {
                return $date;
            }
            // timestamp
            if (is_int($date) || is_numeric($date)) {
                return (new \DateTimeImmutable())->setTimestamp($date);
            }
            // verbal expression of the date
            if (is_string($date)) {
                return new \DateTimeImmutable($date);
            }
        } catch (\Throwable $t) {
            return new \DateTimeImmutable();
        }
        return new \DateTimeImmutable();
    }
}