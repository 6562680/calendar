<?php

namespace Gzhegow\Calendar;

use Gzhegow\Lib\Config\AbstractConfig;
use Gzhegow\Calendar\Parser\CalendarParserConfig;
use Gzhegow\Calendar\Manager\CalendarManagerConfig;
use Gzhegow\Calendar\Formatter\CalendarFormatterConfig;


/**
 * @property CalendarParserConfig    $parser
 * @property CalendarManagerConfig   $manager
 * @property CalendarFormatterConfig $formatter
 */
class CalendarConfig extends AbstractConfig
{
    /**
     * @var CalendarParserConfig
     */
    protected $parser;
    /**
     * @var CalendarParserConfig
     */
    protected $manager;
    /**
     * @var CalendarFormatterConfig
     */
    protected $formatter;


    public function __construct()
    {
        $this->__sections[ 'parser' ] = $this->parser = new CalendarParserConfig();
        $this->__sections[ 'manager' ] = $this->manager = new CalendarManagerConfig();
        $this->__sections[ 'formatter' ] = $this->formatter = new CalendarFormatterConfig();
    }
}
