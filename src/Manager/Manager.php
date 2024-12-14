<?php

namespace Gzhegow\Calendar\Manager;

use Gzhegow\Calendar\Parser\Parser;
use Gzhegow\Calendar\Manager\ManagerConfig;
use Gzhegow\Calendar\Parser\ParserInterface;
use Gzhegow\Calendar\Exception\LogicException;
use Gzhegow\Calendar\CalendarFactoryInterface;


class Manager implements ManagerInterface
{
    /**
     * @var CalendarFactoryInterface
     */
    protected $factory;

    /**
     * @var ManagerConfig
     */
    protected $config;

    /**
     * @var Parser
     */
    protected $parser;


    public function __construct(
        CalendarFactoryInterface $factory,
        ParserInterface $parser,
        //
        ManagerConfig $config
    )
    {
        $this->factory = $factory;
        $this->parser = $parser;

        $this->config = $config;
        $this->config->validate();
    }


    public function dateTime($from = '', $dateTimeZone = '', array $formats = null) : ?\DateTime
    {
        if (null === $from) {
            return null;
        }

        $_from = $from;
        $_dateTimeZone = $dateTimeZone;

        if ('' === $from) {
            $_from = $this->config->dateTimeDefault;
        }
        if ('' === $dateTimeZone) {
            $_dateTimeZone = $this->config->dateTimeZoneDefault;
        }

        if (null !== $_dateTimeZone) {
            $_dateTimeZone = $this->parser->parseDateTimeZone($_dateTimeZone);

            if (null === $_dateTimeZone) {
                throw new LogicException(
                    [
                        'Invalid `dateTimeZone` passed',
                        $dateTimeZone,
                    ]
                );
            }
        }

        $dateTime = is_object($_from)
            ? $this->parser->parseDateTime($_from, $formats, $_dateTimeZone)
            : $this->factory->newDateTime($_from, $_dateTimeZone);

        return $dateTime;
    }

    public function dateTimeImmutable($from = '', $dateTimeZone = '', array $formats = null) : ?\DateTimeImmutable
    {
        if (null === $from) {
            return null;
        }

        $_from = $from;
        $_dateTimeZone = $dateTimeZone;

        if ('' === $from) {
            $_from = $this->config->dateTimeDefault;
        }
        if ('' === $dateTimeZone) {
            $_dateTimeZone = $this->config->dateTimeZoneDefault;
        }

        if (null !== $_dateTimeZone) {
            $_dateTimeZone = $this->parser->parseDateTimeZone($_dateTimeZone);

            if (null === $_dateTimeZone) {
                throw new LogicException(
                    [
                        'Invalid `dateTimeZone` passed',
                        $dateTimeZone,
                    ]
                );
            }
        }

        $dateTimeImmutable = is_object($_from)
            ? $this->parser->parseDateTimeImmutable($_from, $formats, $_dateTimeZone)
            : $this->factory->newDateTimeImmutable($_from, $_dateTimeZone);

        return $dateTimeImmutable;
    }

    public function dateTimeZone($from = '') : ?\DateTimeZone
    {
        if (null === $from) {
            return null;
        }

        $_from = $from;

        if ('' === $from) {
            $_from = $this->config->dateTimeZoneDefault;
        }

        $timezone = is_object($_from)
            ? $this->parser->parseDateTimeZone($_from)
            : $this->factory->newDateTimeZone($_from);

        return $timezone;
    }

    public function dateInterval($from = '', array $formats = null) : ?\DateInterval
    {
        if (null === $from) {
            return null;
        }

        $_from = $from;

        if ('' === $from) {
            $_from = $this->config->dateIntervalDefault;
        }

        $interval = is_object($_from)
            ? $this->parser->parseDateInterval($_from, $formats)
            : $this->factory->newDateInterval($_from);

        return $interval;
    }


    public function now($dateTimeZone = '') : \DateTime
    {
        $dateTime = $this->dateTime('now', $dateTimeZone);

        return $dateTime;
    }

    public function nowImmutable($dateTimeZone = '') : \DateTimeImmutable
    {
        $dateTimeImmutable = $this->dateTimeImmutable('now', $dateTimeZone);

        return $dateTimeImmutable;
    }


    public function nowMidnight($dateTimeZone = '') : \DateTime
    {
        $dateTime = $this->dateTime('now midnight', $dateTimeZone);

        return $dateTime;
    }

    public function nowMidnightImmutable($dateTimeZone = '') : \DateTimeImmutable
    {
        $dateTimeImmutable = $this->dateTimeImmutable('now midnight', $dateTimeZone);

        return $dateTimeImmutable;
    }
}